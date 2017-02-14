<?php

class LappenerimaanbarangController extends Controller
{
    
        public $pageTitle = 'Hyggienis - Laporan Penerimaan Barang';
    
        public function filters()
	{
		return array(
			
		);
	}
    
	public function actionIndex()
	{
                $model = new Lappenerimaanbaranghygienis;
		$this->render('index',array(
                    'model'=>$model
                ));
	}
        
        public function actionSubmit()
        {
            $model=new Lappenerimaanbaranghygienis;
            $model->attributes=$_POST['Lappenerimaanbaranghygienis'];
            if(isset($_POST['Lappenerimaanbaranghygienis']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			
                          //per tanggal  
                          if($_POST['Lappenerimaanbaranghygienis']['pilih']==1)
                          {
                              $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Lappenerimaanbaranghygienis']['tanggal']);
                              echo CJSON::encode(array('result'=>'OK','pilihan'=>$tanggal,'status'=>'1'));  
                          }
                          // per bulan
                          if($_POST['Lappenerimaanbaranghygienis']['pilih']==2)
                          {
                              $bulan = $_POST['Lappenerimaanbaranghygienis']['bulan'];
                              echo CJSON::encode(array('result'=>'OK','pilihan'=>$bulan,'status'=>'2'));  
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
        
        
        public function actionPrint()
        {
            $this->render("report",array());
        }
}