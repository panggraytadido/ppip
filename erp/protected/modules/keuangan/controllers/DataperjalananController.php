<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DataperjalananController extends Controller
{
    public $pageTitle = 'Keuangan - Data Perjalanan';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Dataperjalanan('search');
        $model->unsetAttributes();

        if(isset($_GET['Dataperjalanan']))
                $model->attributes=$_GET['Dataperjalanan'];
        
        $modelDataPerjalanan = new Dataperjalanan;
        $modelBiayaPerjalanan = new Biayaperjalanan;
        $modelKendaraan = new Kendaraan;
   
        $this->render('index',array(
                'model'=>$model,   
                'modelDataPerjalanan'=>$modelDataPerjalanan,      
                'modelBiayaPerjalanan'=>$modelBiayaPerjalanan,
                'modelKendaraan'=>$modelKendaraan,
        ));
    }
    
    
    public function actionCreate()
    {
        $modelDataPerjalanan = new Dataperjalanan;
        $modelBiayaPerjalanan = new Biayaperjalanan;
        $modelKendaraan = new Kendaraan;
        
        
        if(isset($_POST['Dataperjalanan']))
        {
            $this->performAjaxValidation($modelDataPerjalanan, $modelBiayaPerjalanan,$modelKendaraan);
        
            $modelDataPerjalanan->attributes=$_POST['Dataperjalanan'];
            $valid = $modelDataPerjalanan->validate();   

            if($valid)
            {
                $tujuan = $_POST['Biayaperjalanan']['nama'];
                $kendaraan = $_POST['Kendaraan']['nama'];

                $biayaPerjalananID = Biayaperjalanan::model()->find("nama='$tujuan' AND kendaraanid=$kendaraan")->id;

                $modelDataPerjalanan->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Dataperjalanan']['tanggal']);
                $modelDataPerjalanan->karyawanid=$_POST['Dataperjalanan']['karyawanid'];
                $modelDataPerjalanan->bbm=$_POST['bbminput'];
                $modelDataPerjalanan->upah=$_POST['gajiinput'];
                $modelDataPerjalanan->biayaperjalananid=$biayaPerjalananID;
                $modelDataPerjalanan->createddate=date("Y-m-d H:", time());
                $modelDataPerjalanan->userid = Yii::app()->session['divisiid'];
                $modelDataPerjalanan->kendaraanid=$_POST['Kendaraan']['nama'];
                if($modelDataPerjalanan->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                        'id'=>$modelDataPerjalanan->id
                    ));

                    Yii::app ()->user->setFlash ( 'success', "Data Perjalanan Berhasil Ditambah." );
                    Yii::app()->end();
                }
            }
            else 
            {
                $error = CActiveForm::validate(array($modelDataPerjalanan, $modelBiayaPerjalanan,$modelKendaraan));
                if($error!='[]')
                {
                    echo $error;
                }
            }  
        }
        else 
        {
                $this->layout='a';
                $this->render('_form',array(
                        'modelDataPerjalanan'=>$modelDataPerjalanan,      
                        'modelBiayaPerjalanan'=>$modelBiayaPerjalanan,
                        'modelKendaraan'=>$modelKendaraan,
                ), false, true );
        }
                
    }
    
    public function actionUpdate($id)
    {
        if (Yii::app ()->request->isAjaxRequest) {
            $modelDataPerjalanan = Perjalanan::model()->findByPk($id);
            $modelDataPerjalanan->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Dataperjalanan']['tanggal']);
            $modelDataPerjalanan->karyawanid=$_POST['Dataperjalanan']['karyawanid'];
            $modelDataPerjalanan->bbm=$_POST['bbminput'];
            $modelDataPerjalanan->upah=$_POST['gajiinput'];
            $modelDataPerjalanan->biayaperjalananid=$biayaPerjalananID;
            $modelDataPerjalanan->createddate=date("Y-m-d H:", time());
            $modelDataPerjalanan->userid = Yii::app()->session['divisiid'];
            $modelDataPerjalanan->kendaraanid=$_POST['Kendaraan']['nama'];         
            
            if($modelDataPerjalanan->save())
            {
                echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'id'=>$modelDataPerjalanan->id
                ));
                
                Yii::app ()->user->setFlash ( 'success', "Data Perjalanan Berhasil Diupdate." );
                Yii::app()->end();
            }
            
        }
    }
        
    public function actionView($id)
    {
        $model = Perjalanan::model()->findByPk($id);        

        $this->render('view',array(
                'model'=>$model,                
        ));
    }
    
    public function actionHitungBiaya()
    {
        if (Yii::app ()->request->isAjaxRequest) {
            
            $tujuan = $_POST['tujuan'];
            $kendaraanId= $_POST['kendaraan'];
                        
            $upah = Biayaperjalanan::model()->find("nama='$tujuan' AND kendaraanid='$kendaraanId'")->upah;
          
            echo CJSON::encode(array('result'=>'OK','upah'=>$upah)); 
        }
    }
    
    public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $modelDataPerjalanan = Perjalanan::model ()->findByPk (intval($id));                
                    $this->render ( 'update_form', array (
                                    'modelDataPerjalanan' => $modelDataPerjalanan,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
    
    protected function performAjaxValidation($model1, $model2,$model3)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='dataperjalanan-form')
            {
                echo CActiveForm::validate(array($model1, $model2,$model3));
                Yii::app()->end();
            }
        }
        
}    