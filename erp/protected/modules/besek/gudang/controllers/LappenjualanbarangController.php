<?php
class LappenjualanbarangController extends Controller
{
        public $pageTitle = 'Gudang - Penjualan Barang';
    
        public function filters()
	{
		return array(
			
		);
	}
    
	public function actionIndex()
	{
                $model = new Lappenjualanbaranggudang;
              
		$this->render('index',array('model'=>$model));
	}
        
        public function actionSubmit()
        {
            $model=new Lappenjualanbaranggudang;
            $model->attributes=$_POST['Lappenjualanbaranggudang'];
            if(isset($_POST['Lappenjualanbaranggudang']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			
                          //per tanggal  
                          if($_POST['Lappenjualanbaranggudang']['pilih']==1)
                          {
                              $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Lappenjualanbaranggudang']['tanggal']);
                              echo CJSON::encode(array('result'=>'OK','pilihan'=>$tanggal,'status'=>'1'));  
                          }
                          // per bulan
                          if($_POST['Lappenjualanbaranggudang']['pilih']==2)
                          {
                              $bulan = $_POST['Lappenjualanbaranggudang']['bulan'];
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