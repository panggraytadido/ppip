<?php

class TransferkasirController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			
		);
	}
	

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Transferkasir;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Transferkasir']))
		{			
			$model->attributes=$_POST['Transferkasir'];
			$valid = $model->validate();   
			
			if($valid)
			{
				$transaction = Yii::app()->db->beginTransaction();
				try{
					//select saldo terakhir berdasarkan tanggal rekening dan tanggal terakhir
					$connection=Yii::app()->db;
					$sql ="select max(id) as id from transaksi.transfer 
						where rekeningid=".intval($_POST['Transferkasir']['rekeningid']);           
					$data=$connection->createCommand($sql)->queryRow();
					$id = $data["id"];                        
					if($id!="")
					{
						$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
						if($saldo!="" || $saldo!=0)
						{
							$saldoTerakhir = $saldo;
						}
						else 
						{
							$saldoTerakhir = 0;
						}														
					}														
					else
					{
						$saldoTerakhir = 0;
					}	
					
					$transfer = new Transfer;
					$transfer->rekeningid=$_POST['Transferkasir']['rekeningid'];
					$transfer->kredit=$_POST['Transferkasir']['jumlah'];
					$transfer->jenistransferid=8;
					$transfer->saldo=$_POST['Transferkasir']['jumlah']+$saldoTerakhir;
					$transfer->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkasir']['tanggal']);
					$transfer->userid = Yii::app()->user->id; 
					if($transfer->save())
					{
						$model = new Transferkasir;
						$model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkasir']['tanggal']);
						$model->rekeningid=$_POST['Transferkasir']['rekeningid'];
						$model->jumlah=$_POST['Transferkasir']['jumlah'];                        
						$model->createddate=date("Y-m-d H:", time());
						$model->userid = Yii::app()->user->id; 
						$model->transferid=$transfer->id;			
						if($model->save())
						{
							$tanggal =Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkasir']['tanggal']);
							$keuanganHarian = Pencatatankeuanganharian::model()->find("cast(tanggal as date)='$tanggal'");
							$keuanganHarian->totalkeuanganharian =  $keuanganHarian->totalkeuanganharian-$_POST['Transferkasir']['jumlah'];
							$keuanganHarian->updateddate=date("Y-m-d H:", time());
							if($keuanganHarian->save())
							{			
								$transaction ->commit();	
								echo CJSON::encode(array
								(
									'result'=>'OK',
									'id'=>$model->id
								));

								Yii::app ()->user->setFlash ( 'success', "Data Transfer Kasir Berhasil Ditambah." );
								Yii::app()->end();
												
							}
						}
					}									
				}
				catch (Exception $error) 
				{
					$transaction ->rollback();
					throw $error;
				} 																			             
			}
			else
			{
				$error = CActiveForm::validate($model);
				if($error!='[]')
						echo $error;
				Yii::app()->end();
			}                			
		}

                $this->layout='a';
		$this->render('_form',array(
			'model'=>$model,
		));
	}
	
	
	public function actionUpdate($id)
	{
		$model = Transferkasir::model()->findByPk($id);		
		$model->tanggal = date("m/d/Y", strtotime($model->tanggal));
		
		$tanggal = Yii::app()->DateConvert->ConvertTanggal($model->tanggal);		
		$keuanganHarian = Pencatatankeuanganharian::model()->find("cast(tanggal as date)='$tanggal'");		
		//rekening
		$rek = Rekening::model()->findByPk($model->rekeningid);
		//		
		$saldoTerakhirView = Transfer::model()->findByPk($model->transferid)->saldo;
							
		if(isset($_POST['Transferkasir']))
		{			
				$transaction = Yii::app()->db->beginTransaction();
				try{

					if($_POST['Transferkasir']['jumlah']==$model->jumlah)
					{
						$keuanganHarian->totalkeuanganharian = $keuanganHarian->totalkeuanganharian;
					}
					if($_POST['Transferkasir']['jumlah']>$model->jumlah)
					{
						$keuanganHarian->totalkeuanganharian = ($keuanganHarian->totalkeuanganharian+$model->jumlah)-$_POST['Transferkasir']['jumlah'];
					}
					if($_POST['Transferkasir']['jumlah']<$model->jumlah)
					{
						$keuanganHarian->totalkeuanganharian = ($keuanganHarian->totalkeuanganharian+$model->jumlah)-$_POST['Transferkasir']['jumlah'];
					}
					
					if($keuanganHarian->save())
					{						
							$transfer = Transfer::model()->findByPk($model->transferid);
														
							if(count($transfer)>0)
							{													
								if($transfer->delete())
								{
																		
									//saldo rekening
									$connection=Yii::app()->db;
									$sql ="select max(id) as id from transaksi.transfer where rekeningid=".intval($_POST['Transferkasir']['rekeningid']);           		
									$data=$connection->createCommand($sql)->queryRow();
									$id = $data["id"];                        
									if($id!="")
									{
										$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
										if($saldo!="" || $saldo!=0)
										{
											$saldoTerakhir = $saldo;
										}
										else 
										{
											$saldoTerakhir = 0;
										}														
									}														
									else
									{
										$saldoTerakhir = 0;
									}
									//																		
									
									$transferModel = new Transfer;
									$transferModel->rekeningid=$_POST['Transferkasir']['rekeningid'];
									$transferModel->jenistransferid=8;
									$transferModel->kredit=$_POST['Transferkasir']['jumlah'];
									$transferModel->saldo=$_POST['Transferkasir']['jumlah']+$saldoTerakhir;
									$transferModel->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkasir']['tanggal']);
									$transferModel->userid = Yii::app()->user->id; 
									$transferModel->createddate=date("Y-m-d H:", time());									
									if($transferModel->save())
									{										
										$modelTransferKasir = new Transferkasir;
										$modelTransferKasir->transferid=$transferModel->id;
										$modelTransferKasir->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkasir']['tanggal']);
										$modelTransferKasir->rekeningid=$_POST['Transferkasir']['rekeningid'];
										$modelTransferKasir->jumlah=$_POST['Transferkasir']['jumlah'];                        
										$modelTransferKasir->createddate=date("Y-m-d H:", time());
										$modelTransferKasir->userid = Yii::app()->user->id; 
										
											//$modelTransferKasir->attributes=$_POST['Transferkasir'];																			
										if($modelTransferKasir->save())
										{
											
										}																														
									}
								}
							}							
					}
					$transaction ->commit();	
				}
				catch (Exception $error) 
				{
					$transaction ->rollback();
					throw $error;
				} 															
		}
					
		$this->layout='a';
		$this->render( 'update', array (
						'model' => $model,       
						'jumlahkeuanganharian'=>$keuanganHarian->totalkeuanganharian,
						'rek'=>$rek,
						'saldorek'=>$saldoTerakhirView	
		), false, true );
		Yii::app()->end();
	}
        
	public function actionGetNorek()
	{
		if (Yii::app ()->request->isAjaxRequest) {                
			$model = Rekening::model()->findByPk(intval( $_POST['rekeningid']));
			echo CJSON::encode(array
			(
				'result'=>'OK',
				'data'=>$model
			));
		}
	}
		
		public function actionGetSaldoRek()
		{
			if (Yii::app ()->request->isAjaxRequest) {
				$connection=Yii::app()->db;
				$sql ="select max(id) as id from transaksi.transfer where rekeningid=".intval($_POST['rekeningid']);           
				$data=$connection->createCommand($sql)->queryRow();
				$id = $data["id"];                        
				if($id!="")
				{
					$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
					if($saldo!="" || $saldo!=0)
					{
						$saldoTerakhir = $saldo;
					}
					else 
					{
						$saldoTerakhir = 0;
					}														
				}														
				else
				{
					$saldoTerakhir = 0;
				}
				
				echo CJSON::encode(array
				(					
					'saldo'=>$saldoTerakhir
				));
			}	
		}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	
	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Transferkasir('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Transferkasir']))
			$model->attributes=$_GET['Transferkasir'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Transferkasir('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Transferkasir']))
			$model->attributes=$_GET['Transferkasir'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function actionDelete($id)
	{		
		$transfer = Transferkasir::model()->findByPk($id);
		$model=Transfer::model()->findByPk($transfer->transferid);
		$model->delete();
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Transferkasir the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Transferkasir::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Transferkasir $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='transfer-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionGetSaldoKeuanganHarian()
	{
		if (Yii::app ()->request->isAjaxRequest) {          		
			$tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
			$saldo = Pencatatankeuanganharian::model()->find("cast(tanggal as date)='$tanggal'");			
			if($saldo!="" || $saldo!=0)
			{
				echo CJSON::encode(array
				(				
					'saldo'=>$saldo->totalkeuanganharian
				));
			}
			else
			{
				echo CJSON::encode(array
				(				
					'saldo'=>0
				));
			}		
		}
	}
}
