<?php

class KaryawanController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Karyaawan';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
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
            $model=new Karyawan;
            
            if(isset($_POST['Karyawan']))
            {	
                    $model->attributes=$_POST['Karyawan'];
                    $valid = $model->validate();
                    if($valid)
                    {    	                         
                           $model->nik =$_POST['Karyawan']['nik'];
                           $model->nama =$_POST['Karyawan']['nama'];
                           $model->jeniskelamin =$_POST['Karyawan']['jeniskelamin'];
                           $model->alamat =$_POST['Karyawan']['alamat'];
                           if($_POST['Karyawan']['tanggallahir']!="")
                           {
                                $model->tanggallahir=Yii::app()->DateConvert->ConvertTanggal($_POST['Karyawan']['tanggallahir']);
                           }                           
                           $model->jeniskaryawanid =$_POST['Karyawan']['jeniskaryawanid'];
                           $model->tempatlahir =$_POST['Karyawan']['tempatlahir'];
                           $model->hp =$_POST['Karyawan']['hp'];
                           $model->pendidikan =$_POST['Karyawan']['pendidikan'];
                           $model->jabatanid =$_POST['Karyawan']['jabatanid'];
                           $model->jeniskelamin =$_POST['Karyawan']['jeniskelamin'];       
                           $model->createddate = date("Y-m-d H:i:s", time());
                           $model->userid = Yii::app()->user->id;
                           if($_POST['Karyawan']['tmtmasuk']!="")
                           {
                               $model->tmtmasuk=Yii::app()->DateConvert->ConvertTanggal($_POST['Karyawan']['tmtmasuk']);
                           }                           
                            if($model->save()) 
                            {
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',
                                    'karyawanid'=>$model->id
                                ));    	
                                 Yii::app ()->user->setFlash ( 'success', "Data Karyawan Berhasil diTambahkan" );
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
            $model = new Karyawan;    	    
            $id = $_POST['karyawanidhidden'];    	

            $upload = CUploadedFile::getInstance($model, 'photo');

            $rnd = rand(0123456789, 9876543210);    // generate random number between 0123456789-9876543210
            $timeStamp = time();    // generate current timestamp
            $fileName = "{$rnd}-{$timeStamp}-{$upload}";  // random number + Timestamp + file name

            
            $kar = Karyawan::model()->findByPk($id);
            $kar->photo=$fileName;
            if($kar->save())
            {
                $model->photo = CUploadedFile::getInstance($model, 'photo');
                $path = Yii::app()->basePath.'/../upload/'.$fileName;
                $model->photo->saveAs($path);

                Yii::app ()->user->setFlash ( 'success', "Data Karyawan Berhasil diTambahkan" );                    
                echo CJSON::encode(array
                                (
                                                'result'=>"success",

                ));
                Yii::app()->end();
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
		$model=Karyawan::model()->findByPk($id);                
                $model->nik =$_POST['Karyawan']['nik'];
                $model->nama =$_POST['Karyawan']['nama'];
                $model->jeniskelamin =$_POST['Karyawan']['jeniskelamin'];
                $model->alamat =$_POST['Karyawan']['alamat'];
                if($_POST['Karyawan']['tanggallahir']!="")
                {
                     $model->tanggallahir=Yii::app()->DateConvert->ConvertTanggal($_POST['Karyawan']['tanggallahir']);
                } 
                $model->jeniskaryawanid =$_POST['Karyawan']['jeniskaryawanid'];                
                $model->tempatlahir =$_POST['Karyawan']['tempatlahir'];
                $model->hp =$_POST['Karyawan']['hp'];
                $model->pendidikan =$_POST['Karyawan']['pendidikan'];
                $model->jabatanid =$_POST['Karyawan']['jabatanid'];
                $model->jeniskelamin =$_POST['Karyawan']['jeniskelamin'];            
                if($_POST['Karyawan']['tmtmasuk']!="")
                {
                    $model->tmtmasuk=Yii::app()->DateConvert->ConvertTanggal($_POST['Karyawan']['tmtmasuk']);
                } 
                $model->updatedate = date("Y-m-d H:i:s", time());
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Karyawan Berhasil diUpdate" );
                    Yii::app()->end();
                }
            }   
	}
        
        public function actionUpdateuploadfile()
        {    	     	
            $model = new Karyawan;
            $id = $_POST['karyawanidhidden'];                
            $upload = CUploadedFile::getInstance($model, 'photo');                          		    	
                    
                    $kar = Karyawan::model()->findByPk($id);
                    $path = Yii::app()->basePath.'/../upload/'.$kar->photo;                       
                    //if(unlink($path))
                    //{
                        $rnd = rand(0123456789, 9876543210);    // generate random number between 0123456789-9876543210
                        $timeStamp = time();    // generate current timestamp
                        $fileName = "{$rnd}-{$timeStamp}-{$upload}";  // random number + Timestamp + file name

                        $kar->photo=$fileName;
                        if($kar->save())
                        {
                            $model->photo = CUploadedFile::getInstance($model, 'photo');
                            $path = Yii::app()->basePath.'/../upload/'.$fileName;
                            $model->photo->saveAs($path);
                            echo CJSON::encode(array
                            (
                                'result'=>"OK",

                            ));

                            Yii::app()->end();
                        }                      
                    //}                              
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
		$model=new Karyawan('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Karyawan']))
			$model->attributes=$_GET['Karyawan'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Karyawan('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Karyawan']))
			$model->attributes=$_GET['Karyawan'];

		$this->render('admin',array(
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
                    $model = Karyawan::model ()->findByPk ($id);                
                    $model->tanggallahir = date("m/d/Y", strtotime($model->tanggallahir));
                    $model->tmtmasuk = date("m/d/Y", strtotime($model->tmtmasuk));
                    $this->render ( 'update_form', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
}
