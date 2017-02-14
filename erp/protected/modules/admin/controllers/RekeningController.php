<?php

class RekeningController extends Controller
{
		public $layout='//layouts/column2';
			/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			
		);
	}
	
	
	
	
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
	
		public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}


	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/


	public function actionCreate()
	{
            $model=new Rekening;
            $model->attributes=$_POST['Rekening'];
            if(isset($_POST['Rekening']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			    		    			    	    			    	
                           $model->attributes=$_POST['Rekening'];
                            if($model->save()) 
                            {
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',
                                ));    	
                                 Yii::app ()->user->setFlash ( 'success', "Data Bank Berhasil diTambahkan" );
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
	public function actionUpdate($id)
	{
		if (Yii::app ()->request->isAjaxRequest) {
		$model=  Rekening::model()->findByPk($id);                
                $model->namabank=$_POST["Rekening"]["namabank"]; 
                $model->norekening=$_POST["Rekening"]["norekening"];                
                $model->namapemilik=$_POST["Rekening"]["namapemilik"];                
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Bank Berhasil diUpdate" );
                    Yii::app()->end();
                }
            }
	}

	
	
	
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
	
	
	
	public function actionIndex()
	{
			$model=new Rekening('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Rekening']))
			$model->attributes=$_GET['Rekening'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
			public function actionAdmin()
	{
		$model=new Rekening('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Rekening']))
			$model->attributes=$_GET['Rekening'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}
	
	public function loadModel($id)
	{
		$model=Rekening::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='rekening-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $model = Rekening::model ()->findByPk ($id);                
                    $this->render ( 'update_form', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
}