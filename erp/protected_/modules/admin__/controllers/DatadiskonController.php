<?php

class DatadiskonController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Datadiskon('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Datadiskon']))
			$model->attributes=$_GET['Datadiskon'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	

	/**
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
       
}
