<?php

class SahamController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Bagian';

	/**
	 * @return array action filters
	
	public function filters()
	{
            /*
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
            
	}
         */
	
        
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
            $model=new Bagian;
            $model->attributes=$_POST['Bagian'];
            if(isset($_POST['Bagian']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			    		    			    	    			    	
                           $model->attributes=$_POST['Bagian'];
                            if($model->save()) 
                            {
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',
                                ));    	
                                 Yii::app ()->user->setFlash ( 'success', "Data Bagian Berhasil diTambahkan" );
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
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
            if (Yii::app ()->request->isAjaxRequest) {
				$model=Saham::model()->findByPk($id);
				
                $model->nama=$_POST["Saham"]["nama"]; 
                $model->nosurat=$_POST["Saham"]["nosurat"]; 
                $model->hargasahamperlembar=$_POST["Saham"]["hargasahamperlembar"]; 
                $model->tanggalpenetapan=Yii::app()->DateConvert->ConvertTanggal($_POST['Saham']['tanggalpenetapan']);; 
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Saham Berhasil diUpdate" );
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
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Saham('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Saham']))
			$model->attributes=$_GET['Saham'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/*
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Bagian the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Bagian::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Bagian $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bagian-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $model = Saham::model ()->findByPk ($id);      
					$model->tanggalpenetapan = date("m/d/Y", strtotime($model->tanggalpenetapan));	
                    $this->render ( '_updateform', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
}
