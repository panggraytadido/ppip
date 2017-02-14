<?php

class KeuanganharianController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Keuangan - Keuangan Harian';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $this->render('index');

            Yii::app()->end();
        }
        
        public function actionCetak()
        {
            $tanggalLabel = date('d M Y', strtotime($_POST['tanggal']));
            $tanggal = date('Y-m-d', strtotime($_POST['tanggal']));
            
            
            $data = Pencatatankeuanganharian::model()->find("tanggal::text like '".date("Y-m-d", strtotime($tanggal))."%'");
            
            if($data==null)
                $modelInput = new Pencatatankeuanganharian;
            else
                $modelInput = $data;
            
            $this->performAjaxValidation($modelInput);
            
            if(isset($_POST['Pencatatankeuanganharian']))
            {
                //echo 'berhasil';
               // print("<pre>".print_r($modelInput,true)."</pre>");	
                //die;
                $modelInput->seratusribu = $_POST['Pencatatankeuanganharian']['seratusribu'];
                $modelInput->limapuluhribu = $_POST['Pencatatankeuanganharian']['limapuluhribu'];
                $modelInput->duapuluhribu = $_POST['Pencatatankeuanganharian']['duapuluhribu'];
                $modelInput->sepuluhribu = $_POST['Pencatatankeuanganharian']['sepuluhribu'];
                $modelInput->limaribu = $_POST['Pencatatankeuanganharian']['limaribu'];
                $modelInput->duaribu = $_POST['Pencatatankeuanganharian']['duaribu'];
                $modelInput->seribu = $_POST['Pencatatankeuanganharian']['seribu'];
                $modelInput->jumlah = $_POST['Pencatatankeuanganharian']['jumlah'];
                $modelInput->totalkeuanganharian = $_POST['Pencatatankeuanganharian']['totalkeuanganharian'];                
                if($_POST['Pencatatankeuanganharian']['jumlah'] >= $_POST['Pencatatankeuanganharian']['totalkeuanganharian'])
                {
                    $modelInput->kelebihan = $_POST['kuranglebih'];
                    $modelInput->kekurangan = 0;
                }
                else
                {
                    $modelInput->kelebihan = 0;
                    $modelInput->kekurangan = $_POST['kuranglebih'];
                }
                
                
                $modelInput->tanggal = $_POST['tanggal'];
                
                if($modelInput->isNewRecord)
                {
                    $modelInput->userid = Yii::app()->user->id;
                    $modelInput->createddate = date("Y-m-d H:i:s", time());
                }
                else
                {
                    $modelInput->updateddate = date("Y-m-d H:i:s", time());
                }
                
                
                if($modelInput->save(false))
                {
                    Yii::app ()->user->setFlash ( 'success', "Data Berhasil Disimpan" );
                    $this->redirect(array('index'));                    
                }
                
            }
            
            $model = new Keuanganharian;
            $jumlahUangCash = $model->jumlahUangCash(Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']));
            $jumlahUangSetoran = $model->jumlahUangSetoran(Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']));
            $jumlahUangTabungan = $model->jumlahUangTabungan(Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']));
            $totalUangHarian = ($jumlahUangCash + $jumlahUangSetoran + $jumlahUangTabungan);
            $jumlahTransfer = $model->jumlahTransfer(Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']));
            $jumlahBeliTunai = $model->jumlahBeliTunai(Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']));
            $jumlahPengeluaran = $model->jumlahPengeluaran(Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']));
            $sisaUangHarian = $totalUangHarian - ($jumlahBeliTunai + $jumlahPengeluaran + $jumlahTransfer);
            
            $this->render('cetak',array(
                'modelInput'=>$modelInput,
                'tanggalLabel'=>$tanggalLabel,
                'tanggal'=>$tanggal,
                'jumlahUangCash'=>$jumlahUangCash,
                'jumlahUangSetoran'=>$jumlahUangSetoran,
                'jumlahUangTabungan'=>$jumlahUangTabungan,
                'jumlahTransfer'=>$jumlahTransfer,
                'totalUangHarian'=>$totalUangHarian,                
                'jumlahBeliTunai'=>$jumlahBeliTunai,
                'jumlahPengeluaran'=>$jumlahPengeluaran,
                'sisaUangHarian'=>$sisaUangHarian
            ));

            Yii::app()->end();
        }
        
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='keuanganharian-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}