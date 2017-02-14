<?php 

class PemegangsahamController extends Controller
{
	public $layout='//layouts/column2';
	public $pageTitle = 'Admin - Pemegang Saham';
	
	
	public function actionIndex()
	{
		$model=new Pemegangsaham('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pemegangsaham']))
			$model->attributes=$_GET['Pemegangsaham'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	
	public function actionCreate()
	{
		$model=new Jumlahsaham;		
		$this->performAjaxValidation($model);
		$hargaSaham = Saham::model()->findByPk(1)->hargasahamperlembar;
		if(isset($_POST['Jumlahsaham']))
		{								
				$valid = $model->validate();				
				if($valid)
				{ 			
					$transaction = Yii::app()->db->beginTransaction();
					try{
							$criteria = new  CDbCriteria;
							$criteria->select = 'max(sahamke) as sahamke';
							$criteria->condition='anggotaid='.$_POST['Jumlahsaham']['nama'];
							$sahamKe = Jumlahsaham::model()->find($criteria)->sahamke;
														 
							$jumlahSaham = $_POST['Jumlahsaham']['jumlahsaham'];	
							for($i=1;$i<=$jumlahSaham;$i++)								
							{
								$sahamKe = $sahamKe+1;
								$model2 = new Jumlahsaham;
								$anggota = Anggota::model()->findByPk($_POST['Jumlahsaham']['nama']);							
								$model2->sahamid=Saham::model()->findByPk(1)->id;
								$model2->anggotaid=$anggota->id;						
								$model2->createddate=date("Y-m-d H:", time());;
								$model2->userid=Yii::app()->user->id;
								$model2->sahamke=$sahamKe;
								$model2->save();	
								
							}
							
							
							$transaction ->commit();									
							echo CJSON::encode(array
							(
								'result'=>'OK',
							));    	
							 Yii::app ()->user->setFlash ( 'success', "Data Pemegang Saham Berhasil diTambahkan" );
							 Yii::app()->end();	
							
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


				Yii::app()->end();
		}
		
		$this->layout='';
		$this->renderPartial('_form',array(
				'model'=>$model,		
				'hargaSaham'=>$hargaSaham	
		), false, true );

		Yii::app()->end();
		
	}
	
	public function actionUpdate($id)
	{
		$model = Anggota::model()->findByPk($id);
		$totalSaham = Pemegangsaham::model()->Gettotalsaham($id);	
		$hargaSaham = Saham::model()->findByPk(1)->hargasahamperlembar;
		$this->performAjaxValidationUpdate($model);
		
		if(isset($_POST['Anggota']))
        {
			$transaction = Yii::app()->db->beginTransaction();
					try{
					
							$criteria = new  CDbCriteria;
							$criteria->select = 'count(*) as jumlahsaham';
							$criteria->condition='anggotaid='.$id;
							$jumlahSaham = Jumlahsaham::model()->find($criteria)->jumlahsaham;
							
							if($jumlahSaham==$_POST['Anggota']['jumlahsaham'])
							{
								echo CJSON::encode(array
								(
									'result'=>'OK',
								));    	
								 Yii::app ()->user->setFlash ( 'success', "Data Pemegang Saham Berhasil diubah" );
								 Yii::app()->end();	
							}
							
							if($_POST['Anggota']['jumlahsaham']<$jumlahSaham)
							{
								$rowMin = $jumlahSaham-$_POST['Anggota']['jumlahsaham'];
								
								$connection =Yii::app()->db;
								$sql = 'delete from transaksi.jumlahsaham where anggotaid='.$id.' and sahamke >'.$_POST['Anggota']['jumlahsaham'];
								$command = $connection->createCommand($sql);
								if($command->execute())
								{
									$transaction ->commit();	
									echo CJSON::encode(array
									(
										'result'=>'OK',
									));    	
									 Yii::app ()->user->setFlash ( 'success', "Data Pemegang Saham Berhasil diubah" );
									 Yii::app()->end();	
								}
							}
							
							if($_POST['Anggota']['jumlahsaham']>$jumlahSaham)
							{
								$criteria = new  CDbCriteria;
								$criteria->select = 'max(sahamke) as sahamke';
								$criteria->condition='anggotaid='.$id;
								$sahamKe = Jumlahsaham::model()->find($criteria)->sahamke;
							
								$jumlah = $_POST['Anggota']['jumlahsaham']-$jumlahSaham;								
								for($i=1;$i<=$jumlah;$i++)
								{							
									$sahamKe = $sahamKe+1;
									$model1 = new Jumlahsaham;					
									$model1->sahamid=Saham::model()->findByPk(1)->id;
									$model1->anggotaid=$id;						
									$model1->createddate=date("Y-m-d H:", time());;
									$model1->userid=Yii::app()->user->id;
									$model1->sahamke=$sahamKe;
									$model1->save();									
								}					
								
								$transaction ->commit();	
								echo CJSON::encode(array
								(
									'result'=>'OK',
								));    	
								 Yii::app ()->user->setFlash ( 'success', "Data Pemegang Saham Berhasil diubah" );
								 Yii::app()->end();	
								
							}
							
					}
					catch (Exception $error) 
					{
						$transaction ->rollback();
						throw $error;
					}
								
		}
		
		$this->layout='';
		$this->renderPartial('_updateform',array(
				'model'=>$model,
				'totalSaham'=>number_format($totalSaham),
				'jumlahSaham'=>	Pemegangsaham::model()->getJumlahSaham($id),
				'hargaSaham'=>$hargaSaham	
		), false, true );

		Yii::app()->end();		
	}
	
	public function actionUploadfile()
        {    	
            $model = new Anggota;    	    
            $id = $_POST['anggotaidhidden'];    	

            $upload = CUploadedFile::getInstance($model, 'photoktp');

            $rnd = rand(0123456789, 9876543210);    // generate random number between 0123456789-9876543210
            $timeStamp = time();    // generate current timestamp
            $fileName = "{$rnd}-{$timeStamp}-{$upload}";  // random number + Timestamp + file name

            
            $kar = Anggota::model()->findByPk($id);
			//$path = Yii::app()->basePath.'/../../upload/'.$fileName;
			//echo $path;
			//print("<pre>".print_r($kar,true)."</pre>");
			//die;
            $kar->photoktp=$fileName;
            if($kar->save())
            {
                $model->photoktp = CUploadedFile::getInstance($model, 'photoktp');
                $path = Yii::app()->basePath.'/../upload/'.$fileName;
                $model->photoktp->saveAs($path);

                Yii::app ()->user->setFlash ( 'success', "Data Anggota Berhasil diTambahkan" );                    
                echo CJSON::encode(array
				(
					'result'=>"success",

                ));
                Yii::app()->end();
            }
                			    	      	    
        }
		
		public function actionUpdateuploadfile()
        {    	     	
            $model = new Anggota;
            $id = $_POST['anggotaidhidden'];                
            $upload = CUploadedFile::getInstance($model, 'photoktp');        
			//print("<pre>".print_r($upload,true)."</pre>");	
			if(count($upload)!=0)
			{
				$kar = Anggota::model()->findByPk($id);
				$path = Yii::app()->basePath.'/../upload/'.$kar->photo;                       
				//if(unlink($path))
				//{
					$rnd = rand(0123456789, 9876543210);    // generate random number between 0123456789-9876543210
					$timeStamp = time();    // generate current timestamp
					$fileName = "{$rnd}-{$timeStamp}-{$upload}";  // random number + Timestamp + file name

					$kar->photoktp=$fileName;
					if($kar->save())
					{
						$model->photoktp = CUploadedFile::getInstance($model, 'photoktp');
						$path = Yii::app()->basePath.'/../upload/'.$fileName;
						$model->photoktp->saveAs($path);
						echo CJSON::encode(array
						(
							'result'=>"success",

						));

						Yii::app()->end();
					}                      
				//}   
			}			                                                               
        }
	
	
	public function actionGetjumlahsaham()
	{
		if (Yii::app ()->request->isAjaxRequest) {
				
			$pemegangSaham = Pemegangsaham::model()->getJumlahSaham($_POST['anggotaid']);	
			echo CJSON::encode(array
			(
				'jumlah'=>$pemegangSaham
			));    	
		}	
	}
	
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function performAjaxValidationUpdate($model)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='form')
            {
                echo CActiveForm::validate(array($model));
                Yii::app()->end();
            }
        }
        
	
	
	
	
}