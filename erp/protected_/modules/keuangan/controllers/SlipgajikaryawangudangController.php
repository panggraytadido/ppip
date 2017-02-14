<?php

class SlipgajikaryawangudangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Keuangan - Slip Gaji Karyawan Gudang';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            if(isset($_POST['jeniskaryawan']) && isset($_POST['tahun']) && isset($_POST['bulan']))
            {       
                //parameter
                $bulan = $_POST['bulan'];
                $tahun = $_POST['tahun'];
                $karyawanid = $_POST['jeniskaryawan'];
                //
                
                $connection=Yii::app()->db;
                //total gaji
                $sql ="select sum(upah) as upah from transaksi.bongkarmuat where EXTRACT(MONTH FROM tanggal) =$bulan and EXTRACT(YEAR FROM tanggal)=$tahun and karyawanid=".$karyawanid;            
                $data=$connection->createCommand($sql)->queryRow();
                $totalGaji = $data["upah"];
                //
                                
                
                //kasbon
                $sql ="select sum(jumlah) as kasbon from transaksi.kasbon where status=false and karyawanid=".$karyawanid;            
                $data=$connection->createCommand($sql)->queryRow();
                $totalKasbon = $data["kasbon"];
                //
                
                //bayar kasbon 
                $sql ="select sum(pk.jumlah) as totalbayarkasbon from transaksi.kasbon k inner join transaksi.pembayarankasbon pk on pk.kasbonid=k.id
                        where k.status = false and karyawanid=".$karyawanid;            
                $data=$connection->createCommand($sql)->queryRow();
                $totalBayarKasbon = $data["totalbayarkasbon"];
                
                
                $this->render('slip',
                        array(
                            'karyawanid'=>$karyawanid,
                            'tahun'=>$_POST['tahun'],
                            'bulan'=>$_POST['bulan'],
                            'totalGaji'=>$totalGaji,
                            'totalKasbon'=>$totalKasbon,
                            'totalBayarKasbon'=>$totalBayarKasbon,
                        )
                );
                Yii::app()->end();
            }
            
            $this->render('index');
            Yii::app()->end();
        }
        
        public function actionCheckPrint()
        {
            if(isset($_POST['karyawanid']) && isset($_POST['tahun']) && isset($_POST['bulan']))
            {
                
                //print("<pre>".print_r($_POST,true)."</pre>");	
                //die;
                $transaction = Yii::app()->db->beginTransaction();
                $bulan = $_POST['bulan'];
                try{
                    $model = Gajikaryawangudang::model()->find("tahun=".$_POST['tahun']." AND bulan='$bulan' AND karyawanid=".$_POST['karyawanid']);                     
                    if(count($model)!=0)
                    {
                        $model->tahun=$_POST['tahun'];
                        $model->bulan=$_POST['bulan'];
                        $model->tanggal=date('Y-m-d');                   
                        $model->totalkasbon=$_POST['totalkasbon'];
                        $model->totalbayarkasbon=$_POST['bayarkasbon'];
                        $model->sisakasbon=$_POST['sisakasbon'];
                        $model->insentive=$_POST['insentive'];
                        $model->bonus=$_POST['bonus'];
                        $model->karyawanid=$_POST['karyawanid'];
                        $model->createddate=date("Y-m-d H:i:s", time());
                        $model->userid=Yii::app()->user->id;                    
                        $model->gaji =  $_POST['totalgaji'];
                        $model->totalgaji = $_POST['totalgaji']+$_POST['bonus']+$_POST['insentive'];           
                        if($model->save())
                        {
                            echo CJSON::encode(array
                            (
                                'result'=>'OK',
                                'karyawanid'=>$_POST['karyawanid'],
                                'tahun'=>$_POST['tahun'],
                                'bulan'=>$_POST['bulan']
                            ));
                        }
                    }
                    else 
                    {
                        $model = new Gajikaryawangudang;
                        $model->tahun=$_POST['tahun'];
                        $model->bulan=$_POST['bulan'];
                        $model->tanggal=date('Y-m-d');                 
                        $model->totalkasbon=$_POST['totalkasbon'];
                        $model->totalbayarkasbon=$_POST['bayarkasbon'];
                        $model->sisakasbon=$_POST['sisakasbon'];
                        $model->insentive=$_POST['insentive'];
                        $model->bonus=$_POST['bonus'];
                        $model->karyawanid=$_POST['karyawanid'];
                        $model->createddate=date("Y-m-d H:i:s", time());
                        $model->userid=Yii::app()->user->id;                    
                        $model->gaji =  $_POST['totalgaji'];
                        $model->totalgaji = $_POST['totalgaji']+$_POST['bonus']+$_POST['insentive'];           
                        if($model->save())
                        {
                            echo CJSON::encode(array
                            (
                                'result'=>'OK',
                                'karyawanid'=>$_POST['karyawanid'],
                                'tahun'=>$_POST['tahun'],
                                'bulan'=>$_POST['bulan']
                            ));
                        }
                    }                    
                    $transaction ->commit();
                } 
                catch (Exception $error) 
                {
                    $transaction ->rollback();
                    throw $error;
                }                                                
            }
        }
        
        public function actionPrint($karyawanid,$tahun,$bulan)
        {
            echo Yii::app()->Report->Export("gajikaryawangudang.jrxml","Gaji Pegawai",array(
                intval($karyawanid),$bulan,intval($tahun)),array("karyawanid"=>"karyawanid","bulan"=>"bulan","tahun"=>"tahun"),"pdf");
        }


        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='slipgajikaryawangudang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}