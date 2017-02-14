<?php

class RinciansetoranController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Rincian Setoran';

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
		$this->render('index',array(			
		));
	}
        
        public function actionSubmit()
        {
            if(isset($_POST))
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);                                
                echo CJSON::encode(array('result'=>'OK','tanggal'=>$tanggal));  
            }
        }
        
        public function actionCetak()
        {
            $this->render('report',array(                 
            ));
        }
               
}
