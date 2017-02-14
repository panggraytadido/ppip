<?php

class AnggotaController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Karyawan';

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
            $model=new Anggota;
            
            if(isset($_POST['Anggota']))
            {	
					//print("<pre>".print_r($_POST['Anggota'],true)."</pre>");    	
					//die;
                    $model->attributes=$_POST['Anggota'];
                    $valid = $model->validate();
                    if($valid)
                    {    	                         
					   $model->kode =$_POST['Anggota']['kode'];
					   $model->nama =$_POST['Anggota']['nama'];
					   $model->jeniskelamin =$_POST['Anggota']['jeniskelamin'];
					   $model->alamat =$_POST['Anggota']['alamat'];
					   if($_POST['Anggota']['tanggalbermitra']!="")
					   {
							$model->tanggalbermitra=Yii::app()->DateConvert->ConvertTanggal($_POST['Anggota']['tanggalbermitra']);
					   }                           
					   $model->hp =$_POST['Anggota']['hp'];
					   $model->telepon =$_POST['Anggota']['telepon'];
					   $model->noktp =$_POST['Anggota']['noktp'];
					   $model->ispemegangsaham = $_POST['Anggota']['ispemegangsaham'];                                                    
						if($model->save()) 
						{
							echo CJSON::encode(array
							(
								'result'=>'OK',
								'anggotaid'=>$model->id
							));    	
							 Yii::app ()->user->setFlash ( 'success', "Data Anggota Berhasil diTambahkan" );
							Yii::app()->end();
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
            $this->render('_form',array(
                    'model'=>$model,                    
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


	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
            if (Yii::app ()->request->isAjaxRequest) {
				$model=Anggota::model()->findByPk($id);                
               $model->kode =$_POST['Anggota']['kode'];
			   $model->nama =$_POST['Anggota']['nama'];
			   $model->jeniskelamin =$_POST['Anggota']['jeniskelamin'];
			   $model->alamat =$_POST['Anggota']['alamat'];
			   if($_POST['Anggota']['tanggalbermitra']!="")
			   {
					$model->tanggalbermitra=Yii::app()->DateConvert->ConvertTanggal($_POST['Anggota']['tanggalbermitra']);
			   }                           
			   $model->hp =$_POST['Anggota']['hp'];
			   $model->telepon =$_POST['Anggota']['telepon'];
			   $model->noktp =$_POST['Anggota']['noktp'];
			   $model->ispemegangsaham = $_POST['Anggota']['ispemegangsaham'];           
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Anggota Berhasil diUpdate" );
                    Yii::app()->end();
                }
            }   
	}
                
	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
                        
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Anggota('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Anggota']))
			$model->attributes=$_GET['Anggota'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Karyawan the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Karyawan::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Karyawan $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='karyawan-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {                    
                    $this->layout = 'a';
                    $model = Anggota::model ()->findByPk ($id);                
                    $model->tanggalbermitra = date("m/d/Y", strtotime($model->tanggalbermitra));                    
                    $this->render ( 'update_form', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
}
