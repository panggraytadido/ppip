<?php

class AbsensipegawaiController extends Controller
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
            $model=new Absensipegawai('search');
            $model->unsetAttributes();
            if(isset($_GET['Absensipegawai']))
                $model->attributes=$_GET['Absensipegawai'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
        }
 
        public function actionProses()
        {                              
                $now = date("Y-m-d H:i:s", time());
                $model = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=1");
                //print("<pre>".print_r($model,true)."</pre>");
                //die;
                if($model==null)
                {
                  
                    $model2 = new Absensi;

                    $data['karyawanid'] = Yii::app()->session['karyawanid'];
                    $data['jammasuk'] = $now;//$_POST['jammasuk'];
                    $data['tanggal'] = $_POST['tanggal'];
                    $data['status'] = $_POST['status'];
                    $data['createddate'] = $now;
                    $data['userid'] = Yii::app()->user->id;
                    $data['isdeleted'] = false;
                    $data['jenisabsensiid'] = 1; // jenis absensi "LEMBUR"

                    $model2->attributes = $data;
                    if($model2->save())
                    {
                        Yii::app ()->user->setFlash ( 'success', "Anda Berhasil Melakukan Absen Masuk" );
                        Yii::app()->end();                              
                    }                   
                }
                else
                {
                    
                    if($model->jammasuk!='' && $model->jamkeluar=='')
                    {
                        $model2 = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=1");
                        //$model2 = new Absensi;

                        $data['karyawanid'] = Yii::app()->session['karyawanid'];
                        $data['jamkeluar'] = $now;//$_POST['jammasuk'];
                        $data['tanggal'] = $_POST['tanggal'];
                        $data['status'] = $_POST['status'];
                        $data['createddate'] = $now;
                        $data['userid'] = Yii::app()->user->id;
                        $data['isdeleted'] = false;
                        $data['jenisabsensiid'] = 1; // jenis absensi "LEMBUR"

                        $model2->attributes = $data;
                        if($model2->save())
                        {
                            $jumlahJam = Lemburpegawai::model()->getJumlahJam($model->id);
                            $model2->jumlahjam=$jumlahJam;
                            if($model2->save())
                            {
                                Yii::app ()->user->setFlash ( 'success', "Anda Berhasil Melakukan Absen Keluar" );
                                Yii::app()->end();                                
                            }
                        }
                    }
                    else
                    {
                        Yii::app ()->user->setFlash ( 'success', "Anda Sudah Melakukan Absen Masuk dan Keluar" );
                        Yii::app()->end();
                    }                        
                }
                                              
                
                /*
                if($_POST['jammasuk']=='') // jika absen masuk
                {
                    $model = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=1");
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
                        $data['jenisabsensiid'] = 1; // jenis absensi "LEMBUR"

                        $model->attributes = $data;
                        $model->save();

                        echo 'Anda Berhasil Melakukan Absen Masuk';
                    }
                    else
                        echo 'Anda Sudah Melakukan Absen Masuk Sebelumnya';
                }
                else // jika absen keluar
                {
                    // ambil record absen hari ini
                    $model = Absensi::model()->find('karyawanid='.Yii::app()->session['karyawanid']." and tanggal='".date('Y-m-d')." 00:00:00' and jenisabsensiid=1");
                    
                    if($model!=null) // ada record absen masuk
                    {
                        if($model->jammasuk!=null && $model->jammasuk!='') // jika benar2 ada jam masuk
                        {                            
                            if($model->jamkeluar!='' || $model->jamkeluar!=null)
                            {
                                echo 'Anda Sudah Melakukan Absen Keluar';
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
                                        echo 'Anda Berhasil Melakukan Absen Keluar';
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
                            
                            echo 'Anda Berhasil Melakukan Absen Keluar, Tanpa Absen Masuk';
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
                        
                        echo 'Anda Berhasil Melakukan Absen Keluar, Tanpa Absen Masuk';
                    }
                }
            }
            else
                
                echo 'Proses Absen Tidak Bisa Dilakukan di Jam Sekarang';   
                 * 
                 */                          
        }
 
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='absensipegawai-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}