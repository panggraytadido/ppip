<?php

class PemegangsahamController extends Controller {

    public $layout = '//layouts/column2';
    public $pageTitle = 'Admin - Pemegang Saham';

    public function actionIndex() {
        $model = new Jumlahsaham('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Jumlahsaham']))
            $model->attributes = $_GET['Jumlahsaham'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /* 	public function loadModel($id)
      {
      $model=Transfer::model()->findByPk($id);
      if($model===null)
      throw new CHttpException(404,'The requested page does not exist.');
      return $model;
      } */

    public function loadModel($id) {
        $model = Jumlahsaham::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    public function actionCreate() {
        $model = new Jumlahsaham;

        if (isset($_POST['Jumlahsaham'])) {
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model);
            $model->attributes = $_POST['Jumlahsaham'];
            $valid = $model->validate();

            if ($valid) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                   $saldoTerakhir = Yii::app()->Saldo->getSaldoRekening($_POST['Jumlahsaham']['rekeningid']);
                  
                        $transfer = new Transfer;
                        $transfer->jenistransferid = 11;
                        $transfer->rekeningid = $_POST['Jumlahsaham']['rekeningid'];
                        $transfer->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Jumlahsaham']['tanggal']);
                        $transfer->debit = $_POST['Jumlahsaham']['totalsaham'];
                        $transfer->nama = 'TRANSFER MASUK SAHAM';
                        $transfer->saldo = $saldoTerakhir + $_POST['Jumlahsaham']['totalsaham'];
                        $transfer->createddate = date("Y-m-d H:", time());
                        $transfer->userid = Yii::app()->user->id;
                        //	$transfer->jumlahsahamid = $model->id ; 

                        if ($transfer->save()) {
                            $model = new Jumlahsaham;
                            $model->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Jumlahsaham']['tanggal']);
                            $model->rekeningid = $_POST['Jumlahsaham']['rekeningid'];                            
                            $model->kode = $_POST['Jumlahsaham']['kode'];
                            $model->anggotaid = $_POST['Jumlahsaham']['anggotaid'];
                            $model->sahamid = $_POST['Jumlahsaham']['sahamid'];
                            $model->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Jumlahsaham']['tanggal']);
                            $model->jumlahsaham = $_POST['Jumlahsaham']['jumlahsaham'];
                            $model->hargasaham = $_POST['Jumlahsaham']['hargasaham'];
                            $model->totalsaham = $_POST['Jumlahsaham']['totalsaham'];
                            $model->createddate = date("Y-m-d H:", time());
                            $model->userid = Yii::app()->user->id;
                            $model->transferid = $transfer->id;
                            if ($model->save()) {
                                $transaction->commit();
                                echo CJSON::encode(array
                                    (
                                    'result' => 'OK',
                                    'id' => $model->id
                                ));

                                Yii::app()->user->setFlash('success', "Data Transfer Berhasil Ditambah.");
                                Yii::app()->end();
                            }
                        }
                    
                } catch (Exception $error) {
                    $transaction->rollback();
                    throw $error;
                }
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }
        }

        $this->layout = 'a';
        $this->render('_form', array(
            'model' => $model,
        ));
    }

    public function actionUpdate($id) {
        $model = Jumlahsaham::model()->findByPk($id);

        if (isset($_POST['Jumlahsaham'])) {
            $model->attributes = $_POST['Jumlahsaham'];
            $valid = $model->validate();
            if ($valid) {
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $saldoTerakhir = Yii::app()->Saldo->getSaldoRekening($_POST['Jumlahsaham']['rekeningid']);
                    
                    $model->kode = $_POST['Jumlahsaham']['kode'];
                    $model->anggotaid = $_POST['Jumlahsaham']['anggotaid'];
                    $model->sahamid = $_POST['Jumlahsaham']['sahamid'];
                    if ($_POST['Jumlahsaham']['tanggal'] != "") {
                        $model->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Jumlahsaham']['tanggal']);
                    }
                    $model->jumlahsaham = $_POST['Jumlahsaham']['jumlahsaham'];
                    $model->hargasaham = $_POST['Jumlahsaham']['hargasaham'];
                    $model->totalsaham = $_POST['Jumlahsaham']['totalsaham'];
                    $model->updateddate = date("Y-m-d H:", time());
                    if ($model->save()) {
                        $transfer = Transfer::model()->find("id=".$model->transferid);
                        $transfer->jenistransferid = 11;
                        $transfer->rekeningid = $_POST['Jumlahsaham']['rekeningid'];
                        $transfer->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Jumlahsaham']['tanggal']);
                        $transfer->debit = $_POST['Jumlahsaham']['totalsaham'];
                        $transfer->nama = 'TRANSFER MASUK SAHAM';
                        $transfer->saldo = $saldoTerakhir + $_POST['Jumlahsaham']['totalsaham'];
                        $transfer->updateddate = date("Y-m-d H:", time());
                        $transfer->userid = Yii::app()->user->id;
                        if($transfer->save())
                        {
                            $transaction->commit();                        
                            echo CJSON::encode(array
                                (
                                'result' => 'OK',
                                'sahamid' => $model->id
                            ));
                            Yii::app()->user->setFlash('success', "Data Pemegang Saham Berhasil diUbah");
                            Yii::app()->end();
                        }                        
                    }
                    
                } catch (Exception $error) {
                    $transaction->rollback();
                    throw $error;
                }                
            } else {
                $error = CActiveForm::validate($model);
                if ($error != '[]')
                    echo $error;
                Yii::app()->end();
            }


            Yii::app()->end();
        }

        $this->layout = 'a';
        $this->render('_updateform', array(
            'model' => $model,
                ), false, true);

        Yii::app()->end();
    }

    public function actionDelete($id) {
        
        $model = Jumlahsaham::model()->findByPk($id);
        $model->isdeleted=true;
        if($model->save(false))
        {
            $transfer = Transfer::model()->find("id=".$model->transferid);
            $transfer->isdeleted=true;
            $transfer->save(false);
        }
        //$this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
    }

    public function actionUploadfile() {
        $model = new Jumlahsaham;
        $id = $_POST['jumlahsahamidhidden'];

        $upload = CUploadedFile::getInstance($model, 'dokumen');
        if ($upload != "") {
            $rnd = rand(0123456789, 9876543210);    // generate random number between 0123456789-9876543210
            $timeStamp = time();    // generate current timestamp
            $fileName = "{$rnd}-{$timeStamp}-{$upload}";  // random number + Timestamp + file name


            $kar = Jumlahsaham::model()->findByPk($id);
            $kar->dokumen = $fileName;
        } else {

            $fileName = "{$upload}";  // random number + Timestamp + file name


            $kar = Jumlahsaham::model()->findByPk($id);
            $kar->dokumen = $fileName;
        }


        if ($kar->save()) {
            $model->dokumen = CUploadedFile::getInstance($model, 'dokumen');
            $path = Yii::app()->basePath . '/../upload/' . $fileName;
            $model->dokumen->saveAs($path);

            Yii::app()->user->setFlash('success', "Data Jumlahsaham Berhasil diTambahkan");
            echo CJSON::encode(array
                (
                'result' => "success",
            ));
            Yii::app()->end();
        }
    }

    public function actionUpdateuploadfile() {
        $model = new Jumlahsaham;
        $id = $_POST['jumlahsahamidhiddenupdate'];
        $upload = CUploadedFile::getInstance($model, 'dokumen');

        $kar = Jumlahsaham::model()->findByPk($id);
        $path = Yii::app()->basePath . '/../upload/' . $kar->dokumen;
        //if(unlink($path))
        //{
        $rnd = rand(0123456789, 9876543210);    // generate random number between 0123456789-9876543210
        $timeStamp = time();    // generate current timestamp
        $fileName = "{$rnd}-{$timeStamp}-{$upload}";  // random number + Timestamp + file name

        $kar->dokumen = $fileName;
        if ($kar->save()) {
            $model->dokumen = CUploadedFile::getInstance($model, 'dokumen');
            $path = Yii::app()->basePath . '/../upload/' . $fileName;
            $model->dokumen->saveAs($path);
            echo CJSON::encode(array
                (
                'result' => "OK",
            ));

            Yii::app()->end();
        }
        //}                              
    }

    public function actionGetNorek() {
        if (Yii::app()->request->isAjaxRequest) {
            $model = Rekening::model()->findByPk(intval($_POST['rekeningid']));
            $saldo = Yii::app()->Saldo->getSaldoRekening($_POST['rekeningid']);
            echo CJSON::encode(array
                (
                'result' => 'OK',
                'data' => $model,
                'saldo' => $saldo,
            ));
        }
    }

    public function GetSaldoRek() {
        if (Yii::app()->request->isAjaxRequest) {
            
            $saldoTerakhir = Yii::app()->Saldo->getSaldoRekening($_POST['rekeningid']);
      
        echo CJSON::encode(array
            (
            'saldo' => $saldoTerakhir
        ));
            
            $connection = Yii::app()->db;
            $sql = "select max(id) as id from transaksi.transfer where rekeningid=" . intval($_POST['rekeningid']);
            $data = $connection->createCommand($sql)->queryRow();
            $id = $data["id"];
            if ($id != "") {
                $saldo = Transfer::model()->findByPk($data["id"])->saldo;
                if ($saldo != "" || $saldo != 0) {
                    return $saldoTerakhir = $saldo;
                } else {
                    return $saldoTerakhir = 0;
                }
            } else {
                return $saldoTerakhir = 0;
            }
        }
    }

    public function actionGetHargasaham() {


        $sahamid = $_POST['sahamid'];



        $recSaham = Saham::model()->find('id=' . $sahamid);


        echo $recSaham->hargasahamperlembar;
    }

    protected function performAjaxValidation($model) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'jumlahsaham-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    protected function afterDelete() {
        parent::afterDelete();
        $rootPath = Yii::app()->getBasePath() . '/..';
        unlink($rootPath . '/upload/' . $this->dokumen);
    }

    public function actionPopupupdate($id) {

        if (Yii::app()->request->isAjaxRequest) {
            $this->layout = 'a';
            $model = Jumlahsaham::model()->findByPk($id);
            $model->tanggal = date("m/d/Y", strtotime($model->tanggal));
            $this->render('_updateform', array(
                'model' => $model,
                    ), false, true);
            Yii::app()->end();
        }
    }

}
