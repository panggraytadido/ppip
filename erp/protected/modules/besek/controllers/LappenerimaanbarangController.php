<?php

class LappenerimaanbarangController extends Controller
{
    public $pageTitle = 'Besek - Laporan Penerimaan Barang';
    
        public function filters()
	{
		return array(
			
		);
	}
    
	public function actionIndex()
	{
                $model = new Lappenerimaanbarangbesek;
		$this->render('index',array(
                    'model'=>$model
                ));
	}
        
        public function actionSubmit()
        {
            $model=new Lappenerimaanbarangbesek;
            $model->attributes=$_POST['Lappenerimaanbarangbesek'];
            if(isset($_POST['Lappenerimaanbarangbesek']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			
                          //per tanggal  
                          if($_POST['Lappenerimaanbarangbesek']['pilih']==1)
                          {
                              $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Lappenerimaanbarangbesek']['tanggal']);
                              echo CJSON::encode(array('result'=>'OK','pilihan'=>$tanggal,'status'=>'1'));  
                          }
                          // per bulan
                          if($_POST['Lappenerimaanbarangbesek']['pilih']==2)
                          {
                              $bulan = $_POST['Lappenerimaanbarangbesek']['bulan'];
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