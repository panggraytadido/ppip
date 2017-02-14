<?php

class PelangganController extends Controller
{
         public $pageTitle = 'Gudang - Pelanggan';
    
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
            $model=new Pelanggan;
            $this->performAjaxValidation($model);
                    
           
            if(isset($_POST['Pelanggan']))
            {	
                 $model->attributes=$_POST['Pelanggan'];
                    $valid = $model->validate();
                    if($valid)
                    {    			    			    		    			    	    			    	
                           $model->attributes=$_POST['Pelanggan'];
                            if($model->save()) 
                            {
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',
                                ));    	
                                 Yii::app ()->user->setFlash ( 'success', "Data Pelanggan Berhasil diTambahkan" );
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
            }
            else
            {
                $this->layout='a';
                $this->render('_form',array(
                        'model'=>$model,                        
                ), false, true );
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
		$model=Pelanggan::model()->findByPk($id);                
                $model->kode=$_POST["Pelanggan"]["kode"]; 
                $model->nama=$_POST["Pelanggan"]["nama"]; 
                //$model->status=$_POST["Pelanggan"]["status"]; 
                $model->alamat=$_POST["Pelanggan"]["alamat"]; 
                $model->propinsiid=$_POST["Pelanggan"]["propinsiid"]; 
                $model->kotaid=$_POST["Pelanggan"]["kotaid"]; 
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Pelanggan Berhasil diUpdate" );
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
		$model=new Pelanggan('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pelanggan']))
			$model->attributes=$_GET['Pelanggan'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Pelanggan('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pelanggan']))
			$model->attributes=$_GET['Pelanggan'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Pelanggan the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model=Pelanggan::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Pelanggan $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='pelanggan-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $model = Pelanggan::model ()->findByPk ($id);                
                    $this->render ( 'update_form', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
}
