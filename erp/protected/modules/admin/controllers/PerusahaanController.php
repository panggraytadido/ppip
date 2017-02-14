<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PerusahaanController extends Controller {

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters

      public function filters()
      {
      /*
      return array(
      'accessControl', // perform access control for CRUD operations
      'postOnly + delete', // we only allow deletion via POST request
      );

      }
     */

    /**
     * Lists all models.
     */
    public function actionIndex() {
        
        $model = Perusahaan::model()->findByPk(1);
        
        $kontak = Kontak::model()->findAll("perusahaanid=1");
        
        $model->tanggalberdiri = date("m/d/Y", strtotime($model->tanggalberdiri));
        $this->render('index', array(
            'model' => $model,
            'kontak' => $kontak,
        ));
    }
    
    
    public function actionSimpan()
    {
        if (Yii::app()->request->isAjaxRequest) {
            
            $perusahaan = Perusahaan::model()->findByPk(1);
            $perusahaan->nama=$_POST['Perusahaan']['nama'];
            $perusahaan->titleperusahaan=$_POST['Perusahaan']['titleperusahaan'];
            $perusahaan->alamat=$_POST['Perusahaan']['alamat'];
            $perusahaan->owner=$_POST['Perusahaan']['owner'];
            $perusahaan->telepon=$_POST['Perusahaan']['telepon'];
            $perusahaan->tanggalberdiri=Yii::app()->DateConvert->ConvertTanggal($_POST['Perusahaan']['tanggalberdiri']);
            if($perusahaan->save())
            {                 
                Yii::app ()->user->setFlash ( 'success', "Profile Perusahaan Berhasil disimpan" );
                echo CJSON::encode(array
                    (
                    'result' => "OK",
                ));
            }           
        }
    }

    public function actionUploadLogo() {
        
        $model = new Perusahaan;
        $id = 1;

        $upload = CUploadedFile::getInstance($model, 'logo');
       // echo $upload["name"];
       // print_r($upload);
       // die;

        $rnd = rand(0123456789, 9876543210);    // generate random number between 0123456789-9876543210
        $timeStamp = time();    // generate current timestamp
        $fileName = "{$rnd}-{$timeStamp}-{$upload}";  // random number + Timestamp + file name


        $kar = Perusahaan::model()->findByPk($id);
        $kar->logo = $fileName;
        if ($kar->save()) {
            $model->logo = CUploadedFile::getInstance($model, 'logo');
            $path = Yii::app()->basePath . '/../upload/' . $fileName;
            $model->logo->saveAs($path);

            Yii::app()->user->setFlash('success', "Logo Perusahaan Berhasil diupload");
            echo CJSON::encode(array
                (
                'result' => "success",
            ));
            Yii::app()->end();
        }
    }

}
