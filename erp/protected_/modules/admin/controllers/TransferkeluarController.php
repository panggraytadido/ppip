<?php

class TransferkeluarController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Transfer Keluar - Admin';
    
		public function actionIndex()
		{           
				$model=new Supplier('search');
				$model->unsetAttributes();
				
				if(isset($_GET['Supplier']))
						$model->attributes = $_GET['Supplier'];
				
				
				$this->render('index',array(
						'model'=>$model,
				));
		}                
        
        public function actionDetail()
        {
            $supplierid = Yii::app()->getRequest()->getParam('id');
            $child = Transferkeluar::model()->listDetail($supplierid);	
          
            // partially rendering "_relational" view
            $this->renderPartial('detail', array(
                            'id' => Yii::app()->getRequest()->getParam('id'),
                            'child' => $child,
            ));
        }
        
        public function actionCreate()
        {
            $model = new Transferkeluar;
            
            if(isset($_POST['Transferkeluar']))
            {
				$this->performAjaxValidation($model);        
				$model->attributes=$_POST['Transferkeluar'];
                $valid = $model->validate();				
                if($valid)
                {				
					//Transfer
					if($_POST['Transferkeluar']["jenisTransfer"]==3)
					{
						
						$transaction = Yii::app()->db->beginTransaction();
						try{
									$connection=Yii::app()->db;
									$supplierid =$_POST['Transferkeluar']['supplier'];
									$sql ="select max(id) as id from transaksi.transfer 
											group by supplierid,isdeleted
												having supplierid=$supplierid and isdeleted=false
													limit 1";           
														
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
						
									
																			
									$model2 = new Transfer;
									//print("<pre>".print_r($_POST['Transferkeluar'],true)."</pre>");
									$model2->saldo = $saldoTerakhir - $_POST['Transferkeluar']['jumlah'];
									$model2->jenistransferid=3;
									$model2->rekeningid=$_POST['Transferkeluar']['rekening'];                
									$model2->supplierid=$_POST['Transferkeluar']['supplier'];
									$model2->kredit=$_POST['Transferkeluar']['jumlah'];
									$model2->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkeluar']['tanggal']);
									$model2->createddate=date("Y-m-d H:i:s", time());
									$model2->userid=Yii::app()->user->id;
									if($model2->save())
									{
											$connection=Yii::app()->db;
											$sql ="select max(id) as id from transaksi.transfer where rekeningid=".intval($_POST['Transferkeluar']['rekening'])." AND supplierid=0";           
											$data=$connection->createCommand($sql)->queryRow();
											$id = $data["id"];                        
											if($id!="")
											{
												$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
												if($saldo!="" || $saldo!=0)
												{
													$saldoTerakhirRek = $saldo;
												}
												else 
												{
													$saldoTerakhirRek = 0;
												}														
											}														
											else
											{
												$saldoTerakhirRek = 0;
											}
											
											$modelTransfer = new Transfer;									
											$modelTransfer->saldo = $saldoTerakhirRek - $_POST['Transferkeluar']['jumlah'];
											$modelTransfer->jenistransferid=3;
											$modelTransfer->rekeningid=$_POST['Transferkeluar']['rekening'];                											
											$modelTransfer->kredit=$_POST['Transferkeluar']['jumlah'];
											$modelTransfer->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkeluar']['tanggal']);
											$modelTransfer->createddate=date("Y-m-d H:i:s", time());
											$modelTransfer->userid=Yii::app()->user->id; 
											if($modelTransfer->save())
											{
												$transaction ->commit();		
												echo CJSON::encode(array
												(
													'result'=>'OK',                                
												));
												Yii::app ()->user->setFlash ( 'success', "Data Transfer Berhasil Ditambah." );
												Yii::app()->end();
											}																							
									}																	
						}
						catch (Exception $error) 
						{
							$transaction ->rollback();
							throw $error;
						} 							
					}
					//PEMBAYARAN CASH
					if($_POST['Transferkeluar']["jenisTransfer"]==4)
					{		
						$transaction = Yii::app()->db->beginTransaction();
						try{
							
							$tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkeluar']['tanggal']);
							$keuanganHarian = Pencatatankeuanganharian::model()->find("cast(tanggal as date)='$tanggal'");
							
							$connection=Yii::app()->db;
							$supplierid =$_POST['Transferkeluar']['supplier'];
							$sql ="select max(id) as id from transaksi.transfer 
									group by supplierid,isdeleted
										having supplierid=$supplierid and isdeleted=false
											limit 1";           
												
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
									$saldoTerakhir=0;
								}		
							}		
							else
							{
								$saldoTerakhir = 0;
							}
							
							$model = new Transfer;
							$model->saldo = $saldoTerakhir - $_POST['Transferkeluar']['jumlah'];
							$model->jenistransferid=4;						
							$model->kredit=$_POST['Transferkeluar']['jumlah'];
							$model->supplierid=$supplierid;
							$model->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkeluar']['tanggal']);
							$model->createddate=date("Y-m-d H:i:s", time());
							$model->userid=Yii::app()->user->id;
							if($model->save())
							{
								$keuanganHarian->jumlah=$keuanganHarian->jumlah-$_POST['Transferkeluar']['jumlah'];
								$keuanganHarian->totalkeuanganharian=$keuanganHarian->totalkeuanganharian-$_POST['Transferkeluar']['jumlah'];
								if($keuanganHarian->save())
								{
									$cash = new Pembayarancashkesupplier;
									$cash->pencatatankeuanganharianid=$keuanganHarian->id;
									$cash->transferid=$model->id;
									$cash->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkeluar']['tanggal']);
									$cash->jumlah=$_POST['Transferkeluar']['jumlah'];
									$cash->createddate=date("Y-m-d H:i:s", time());
									$cash->userid=Yii::app()->user->id;
									if($cash->save())
									{
										$transaction ->commit();
										echo CJSON::encode(array
										(
											'result'=>'OK',                                
										));
										Yii::app ()->user->setFlash ( 'success', "Data Pembayaran Cash Berhasil Ditambah." );
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
                }
                else
                    {
                            $error = CActiveForm::validate($model);
                            if($error!='[]')
                                    echo $error;
                            Yii::app()->end();
                    }	
                Yii::app()->end();
            }
            
            $this->layout='a';
            $this->render('form',array(
                    'model'=>$model,                    
            ), false, true );
            
            Yii::app()->end();
        }
		
		public function actionGetSaldo()
		{
			if (Yii::app ()->request->isAjaxRequest) 
			{				
				$connection=Yii::app()->db;
				$rekeningid =$_POST['rekeningid'];
				$sql ="select max(id) as id from transaksi.transfer where rekeningid=".intval($rekeningid);           
				$data=$connection->createCommand($sql)->queryRow();
				$id = $data["id"];                        
				if($id!="")
				{
					$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
					if($saldo!="" || $saldo!=0)
					{
						echo CJSON::encode(array
                        (
                            'saldo'=>$saldo,                                
                        ));
					}
					else 
					{
						echo CJSON::encode(array
                        (
                            'saldo'=>0,                                
                        ));
					}														
				}														
				else
				{
					echo CJSON::encode(array
                        (
                            'saldo'=>0,                                
                        ));
				}											
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
	
		public function actionGetSaldoSupplier()
		{
			$connection=Yii::app()->db;
			$supplierid =$_POST['supplier'];
			$sql ="select max(id) as id from transaksi.transfer 
					group by supplierid,isdeleted
						having supplierid=$supplierid and isdeleted=false
							limit 1";           
								
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
					$saldoTerakhir=0;
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
		
		protected function performAjaxValidation($model)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='transfer-form')
            {
                echo CActiveForm::validate(array($model));
                Yii::app()->end();
            }
        }
}        