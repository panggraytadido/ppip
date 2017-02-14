<?php
class LappenjualanbarangController extends Controller
{
        public $pageTitle = 'Besek - Laporan Penjualan Barang';
    
        public function filters()
	{
		return array(
			
		);
	}
    
	public function actionIndex()
	{
                $model = new Lappenjualanbarangbesek;
              
		$this->render('index',array('model'=>$model));
	}
        
        public function actionSubmit()
        {
            $model=new Lappenjualanbarangbesek;
            $model->attributes=$_POST['Lappenjualanbarangbesek'];
            if(isset($_POST['Lappenjualanbarangbesek']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			
                          //per tanggal  
                          if($_POST['Lappenjualanbarangbesek']['pilih']==1)
                          {
                              $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Lappenjualanbarangbesek']['tanggal']);
                              echo CJSON::encode(array('result'=>'OK','pilihan'=>$tanggal,'status'=>'1'));  
                          }
                          // per bulan
                          if($_POST['Lappenjualanbarangbesek']['pilih']==2)
                          {
                              $bulan = $_POST['Lappenjualanbarangbesek']['bulan'];
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