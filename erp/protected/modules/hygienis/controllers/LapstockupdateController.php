<?php
class LapstockupdateController extends Controller
{
    
        public $pageTitle = 'Hyggienis - Laporan Stock Update';
    
        public function filters()
	{
		return array(
			
		);
	}
    
	public function actionIndex()
	{
		$this->render('index');
	}
        
        public function actionSubmit()
        {
            echo CJSON::encode(array('result'=>'OK'));  
        }
        
        public function actionPrint()
        {
            $this->render("report",array());
        }
}