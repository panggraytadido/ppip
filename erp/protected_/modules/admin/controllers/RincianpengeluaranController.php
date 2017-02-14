<?php

class RincianpengeluaranController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
    public $pageTitle = 'Admin - Rincian Pengeluaran';
	

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
		$model = new Rincianpengeluaran;
		$this->render('index',array(	'model'=>$model		
		));
	}
        
        public function actionSubmit()
        {
            if(isset($_POST))
            {
				if($_POST['Rincianpengeluaran']['tanggal']!="" AND $_POST['Rincianpengeluaran']['divisi']!="")
				{
					$tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Rincianpengeluaran']['tanggal']);
					$divisi = $_POST['Rincianpengeluaran']['divisi'];
					$data = Rincianpengeluaran::model()->listPengeluaran($tanggal,$divisi);				
					if(count($data)!=0)
					{
						echo CJSON::encode(array('result'=>'OK','tanggal'=>$tanggal,'divisi'=>$divisi));  
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
					Yii::app ()->user->setFlash ( 'success', "Masukan Tanggal dan Divisi." );
					  
				    echo CJSON::encode(array('result'=>'False'));  
					Yii::app()->end();
				}					
				//print("<pre>".print_r($data,true)."</pre>");	
				//die;				
            }
        }
        
        public function actionCetak()
        {
            $this->render('report',array(                 
            ));
        }
               
}
