<?php
class LappenjualanbarangController extends Controller
{
    
        public $pageTitle = 'Hyggienis - Laporan Penjualan Barang';
    
        public function filters()
	{
		return array(
			
		);
	}
    
	public function actionIndex()
	{
                $model = new Lappenjualanbaranghygienis;
              
		$this->render('index',array('model'=>$model));
	}
        
        public function actionSubmit()
        {
            $model=new Lappenjualanbaranghygienis;
            $model->attributes=$_POST['Lappenjualanbaranghygienis'];
            if(isset($_POST['Lappenjualanbaranghygienis']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			
                          //per tanggal  
                          if($_POST['Lappenjualanbaranghygienis']['pilih']==1)
                          {
                              $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Lappenjualanbaranghygienis']['tanggal']);
                              echo CJSON::encode(array('result'=>'OK','pilihan'=>$tanggal,'status'=>'1'));  
                          }
                          // per bulan
                          if($_POST['Lappenjualanbaranghygienis']['pilih']==2)
                          {
                              $bulan = $_POST['Lappenjualanbaranghygienis']['bulan'];
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