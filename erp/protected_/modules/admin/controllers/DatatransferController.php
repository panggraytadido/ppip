<?php

class DatatransferController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Data Setoran';

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
            $model=new Datatransfer;
            $model->unsetAttributes();  // clear any default values

            $this->render('index',array(
                    'model'=>$model,
            ));
	}
        
        public function actionSubmit()
        {
            echo CJSON::encode(array
            (
                'result'=>$_POST['Datatransfer']['nama'],                
            ));
        }
                       
}
