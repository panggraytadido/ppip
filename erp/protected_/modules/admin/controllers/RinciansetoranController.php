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
				if($_POST['tanggal']!="")
				{
					$tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);     
					$data = Rinciansetoran::model()->listDataSetoran($tanggal);  
					if(count($data)!=0)					
					{
						echo CJSON::encode(array('result'=>'OK','tanggal'=>$tanggal));  	
					}		
					else
					{
						Yii::app ()->user->setFlash ( 'success', "Tidak Ada Data Pengeluaran." );
					  
					   echo CJSON::encode(array('result'=>'False'));  
						Yii::app()->end();
					}						
				}
				else
				{
					Yii::app ()->user->setFlash ( 'success', "Masukan Tanggal." );
					  
				   echo CJSON::encode(array('result'=>'False'));  
					Yii::app()->end();
				}	                
            }
        }
        
        public function actionCetak()
        {
            $this->render('report',array(                 
            ));
        }
               
}
