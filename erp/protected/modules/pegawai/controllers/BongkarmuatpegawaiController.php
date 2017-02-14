<?php

class BongkarmuatpegawaiController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Pegawai - Bongkar Muat';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Bongkarmuatpegawai('search');
            $model->unsetAttributes();
            if(isset($_GET['Bongkarmuatpegawai']))
                $model->attributes=$_GET['Bongkarmuatpegawai'];

            $totalUpah = Bongkarmuat::model()->findBySql("select sum(upah) as upah from transaksi.bongkarmuat where karyawanid=".Yii::app()->session['karyawanid']." and tanggal::text like '".date('Y-m-')."%' and isdeleted=false")->upah;
            
            $this->render('index',array(
                    'model'=>$model,
                    'totalUpah'=>$totalUpah,
            ));
        }
 
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='bongkarmuatpegawai-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}