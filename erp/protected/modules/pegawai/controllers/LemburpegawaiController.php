<?php

class LemburpegawaiController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Pegawai - Absensi';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Lemburpegawai('search');
            $model->unsetAttributes();
            if(isset($_GET['Lemburpegawai']))
                $model->attributes=$_GET['Lemburpegawai'];

            /*
            $rec = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=2");
            if($rec!=null)
            {
                if($rec->jammasuk!=null && $rec->jammasuk!='')
                {
                    $jammasuklembur = 25;
                    
                    if($rec->jamkeluar!=null && $rec->jamkeluar!='')
                        $jamkeluarlembur = 25;
                    else
                        $jamkeluarlembur = 0;
                }
                else
                {
                    $jammasuklembur = 18;
                    $jamkeluarlembur = 25;
                }
                    
            }
            else
            {
                $jammasuklembur = 18;
                $jamkeluarlembur = 25;
            }
             * 
             */
            $jammasuklembur = 18;
            $jamkeluarlembur = 25;    
                    
            $this->render('index',array(
                    'model'=>$model,
                    'jammasuklembur'=>$jammasuklembur,
                    'jamkeluarlembur'=>$jamkeluarlembur
            ));
        }
 
        public function actionProses()
        {       
            
            if($_POST['jammasuk']!='')
            {
                $now = date("Y-m-d H:i:s", time());
                $model = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=2");                
                if($model==null)
                {
                    //print("<pre>".print_r($model,true)."</pre>");                
                    //die;
                    $model2 = new Absensi;

                    $data['karyawanid'] = Yii::app()->session['karyawanid'];
                    $data['jammasuk'] = $now;//$_POST['jammasuk'];
                    $data['tanggal'] = $_POST['tanggal'];
                    $data['status'] = $_POST['status'];
                    $data['createddate'] = $now;
                    $data['userid'] = Yii::app()->user->id;
                    $data['isdeleted'] = false;
                    $data['jenisabsensiid'] = 2; // jenis absensi "LEMBUR"

                    $model2->attributes = $data;
                    if($model2->save())
                    {
                        Yii::app ()->user->setFlash ( 'success', "Anda Berhasil Melakukan Absen Lembur Masuk" );
                        Yii::app()->end();                              
                    }
                }   
                elseif($model->jammasuk!='' && $model->jamkeluar=='')
                {
                    $now = date("Y-m-d H:i:s", time());    
                    $model3 = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=2");                                                     
                    //print("<pre>".print_r($model3,true)."</pre>");                
                    //die;                                                          
                    $model3->jamkeluar=$now;
                   
                    if($model3->save())                        
                    {   
                        $jumlahJam = Lemburpegawai::model()->getJumlahJam($model3->id);
                        $model3->jumlahjam=$jumlahJam;
                        if($model3->save())
                        {
                            Yii::app ()->user->setFlash ( 'success', "Anda Berhasil Melakukan Absen Keluar" );
                            Yii::app()->end();                                
                        }                        
                    }
                }
                else
                {
                    Yii::app ()->user->setFlash ( 'success', "Anda Sudah Melakukan Absen Lembur Masuk dan Keluar" );
                    Yii::app()->end();        
                }
            }
            else 
            {
                Yii::app ()->user->setFlash ( 'success', "Absen Lembur Belum dimulai" );
                Yii::app()->end();   
            }
            /*
            die;
            if($_POST['jammasuk']!='' || $_POST['jamkeluar']!='')
            {
                $now = date("Y-m-d H:i:s", time());
                
                if($_POST['jammasuk']!='') // jika absen masuk
                {
                    $model = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=2");
                    //print("<pre>".print_r($model,true)."</pre>");
                    //die;
                    if($model==null) // jika belum absen masuk
                    {
                        $model = new Absensi;

                        $data['karyawanid'] = Yii::app()->session['karyawanid'];
                        $data['jammasuk'] = $now;//$_POST['jammasuk'];
                        $data['tanggal'] = $_POST['tanggal'];
                        $data['status'] = $_POST['status'];
                        $data['createddate'] = $now;
                        $data['userid'] = Yii::app()->user->id;
                        $data['isdeleted'] = false;
                        $data['jenisabsensiid'] = 2; // jenis absensi "LEMBUR"

                        $model->attributes = $data;
                        $model->save();

                        echo 'Anda Berhasil Melakukan Absen Lembur Masuk';
                    }
                    else
                        echo 'Anda Sudah Melakukan Absen Lembur Masuk Sebelumnya';
                }
                else // jika absen keluar
                {
                    // ambil record absen hari ini
                    $model = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=2");
                    
                    if($model!=null) // ada record absen masuk
                    {
                        if($model->jammasuk!=null && $model->jammasuk!='') // jika benar2 ada jam masuk
                        {
                            
                            if($model->jamkeluar!='' || $model->jamkeluar!=null)
                            {
                                echo 'Anda Sudah Melakukan Absen Lembur Keluar';
                            }
                            else
                            {
                                $data['jamkeluar'] = $now;//$_POST['jamkeluar'];
                                //$data['jumlahjam'] = $jumlahjam;

                                $model->attributes = $data;
                                if($model->save())
                                {
                                    $jumlahJam = Lemburpegawai::model()->getJumlahJam($model->id);
                                    $model->jumlahjam=$jumlahJam;
                                    if($model->save())
                                    {
                                        echo 'Anda Berhasil Melakukan Absen Lembur Keluar';
                                    }
                                }
                            }                                                                                                                
                        }
                        else // jika tidak ada jam masuk
                        {
                            //$data['jamkeluar'] = $_POST['jamkeluar'];
                            $data['jamkeluar'] = $now;//$_POST['jamkeluar'];
                            $data['jumlahjam'] = 0;

                            $model->attributes = $data;
                            $model->save();
                            
                            echo 'Anda Berhasil Melakukan Absen Lembur Keluar, Tanpa Absen Lembur Masuk';
                        }
                    }
                    else // jika tidak ada record absen masuk
                    {
                        $model = new Absensi;

                        $data['karyawanid'] = Yii::app()->session['karyawanid'];
                        $data['jamkeluar'] = $now;
                        $data['tanggal'] = $_POST['tanggal'];
                        $data['jumlahjam'] = 0;
                        $data['createddate'] = $now;
                        $data['userid'] = Yii::app()->user->id;
                        $data['isdeleted'] = false;
                        $data['jenisabsensiid'] = 2; // jenis absensi "LEMBUR"

                        $model->attributes = $data;
                        $model->save();
                        
                        echo 'Anda Berhasil Melakukan Absen Lembur Keluar, Tanpa Absen Lembur Masuk';
                    }
                }
            }
            else
                
                echo 'Proses Absen Lembur Tidak Bisa Dilakukan di Jam Sekarang';
             * 
             */
                
        }
 
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='lemburpegawai-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}