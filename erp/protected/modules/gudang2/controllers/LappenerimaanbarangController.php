<?php

class LappenerimaanbarangController extends Controller
{
        public $pageTitle = 'Gudang - Laporan Penerimaan Barang';
    
        public function filters()
	{
		return array(
			
		);
	}
    
	public function actionIndex()
	{
                $model = new Lappenerimaanbaranggudang;
		$this->render('index',array(
                    'model'=>$model
                ));
	}
        
        public function actionSubmit()
        {
            $model=new Lappenerimaanbaranggudang;
            $model->attributes=$_POST['Lappenerimaanbaranggudang'];
            if(isset($_POST['Lappenerimaanbaranggudang']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			
                          //per tanggal  
                          if($_POST['Lappenerimaanbaranggudang']['pilih']==1)
                          {
                              $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Lappenerimaanbaranggudang']['tanggal']);
                              echo CJSON::encode(array('result'=>'OK','pilihan'=>$tanggal,'status'=>'1'));  
                          }
                          // per bulan
                          if($_POST['Lappenerimaanbaranggudang']['pilih']==2)
                          {
                              $bulan = $_POST['Lappenerimaanbaranggudang']['bulan'];
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