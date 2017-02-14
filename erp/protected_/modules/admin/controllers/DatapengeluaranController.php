<?php

class DatapengeluaranController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Data Penjualan';

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
		$model=new Datapengeluaranreport;
		$model->unsetAttributes();  // clear any default values
		
		$this->render('index',array(
			'model'=>$model,
		));
	}
        
        public function actionSubmit()
        {
            $model=new Datapengeluaranreport;
            $model->attributes=$_POST['Datapengeluaranreport'];
            if(isset($_POST['Datapengeluaranreport']))
            {	
                $valid = $model->validate();
                if($valid)
                { 
                    echo CJSON::encode(array('result'=>'OK','divisi'=>$_POST['Datapengeluaranreport']['divisi'],'bulan'=>$_POST['Datapengeluaranreport']['bulan']));  
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
               
}
