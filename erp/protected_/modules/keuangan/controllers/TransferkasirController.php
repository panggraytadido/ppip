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
                        
                        $model = new Transferkasir;
                        $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkasir']['tanggal']);
                        $model->rekeningid=$_POST['Transferkasir']['rekeningid'];
                        $model->jumlah=$_POST['Transferkasir']['jumlah'];                        
                        $model->createddate=date("Y-m-d H:", time());
                        $model->userid = Yii::app()->user->id; 
                        if($model->save())
                        {
                            echo CJSON::encode(array
                            (
                                'result'=>'OK',
                                'id'=>$model->id
                            ));

                            Yii::app ()->user->setFlash ( 'success', "Data Transfer Berhasil Ditambah." );
                            Yii::app()->end();
                        }                                                
                    }
                    else
                    {
                        $error = CActiveForm::validate($model);
                        if($error!='[]')
                        {
                            echo $error;
                        }
                    }                			
		}

                $this->layout='a';
		$this->render('_form',array(
			'model'=>$model,
		));
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

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Transferkasir']))
		{
			$model->attributes=$_POST['Transferkasir'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
}
