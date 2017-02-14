<?php

class SlipgajikaryawanController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Keuangan - Slip Gaji Karyawan';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            if(isset($_POST['jeniskaryawan']))
            {                
               echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'jeniskaryawan'=>$_POST['jeniskaryawan']
                ));
               
               Yii::app()->end();
            }
            
            $this->render('index');
            Yii::app()->end();
        }
        
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='slipgajikaryawan-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
		
		public function actionTest()
		{
			 echo Yii::app()->Report->Export("gajikaryawangudang.jrxml","Gaji Pegawai Gudang",array(
                intval(154),"08",intval(2016)),array("karyawanid"=>"karyawanid","bulan"=>"bulan","tahun"=>"tahun"),"pdf");
			/*
			//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
			//Yii::import('application.extensions.phpjasperxml.classes.tcpdf.tcpdf');
			//Yii::import('application.extensions.phpjasperxml.classes.ExportXLS');
			$PHPJasperXML = Yii::app()->phpxml;
			
			//print("<pre>".print_r($PHPJasperXML,true)."</pre>");
			//$PHPJasperXML = new PHPJasperXML("en","TCPDF");
			$PHPJasperXML->debugsql=true;
			$PHPJasperXML->arrayParameter=array("karyawanid"=>154,"bulan"=>'08',"tahun"=>2016);
			//print("<pre>".print_r($PHPJasperXML,true)."</pre>");
			$PHPJasperXML->load_xml_file("gajikaryawangudang.jrxml");

			//$PHPJasperXML->transferDBtoArray($server,$user,$pass,$db);
			$PHPJasperXML->transferDBtoArray('localhost','postgres','dido','cindygroup','psql');			
			$PHPJasperXML->outpage("I");    //page output method I:standard output  D:Download file
			//print("<pre>".print_r($PHPJasperXML,true)."</pre>");
			*/

		}
}