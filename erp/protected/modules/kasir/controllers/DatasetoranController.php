<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DatasetoranController extends Controller {

    //put your code here
    public $pageTitle = 'Kasir - Data Setoran';

    public function filters() {
        return array();
    }

    public function actionIndex() {
        $model = new Datasetoran('search');
        $model->unsetAttributes();

        if (isset($_GET['Datasetoran']))
            $model->attributes = $_GET['Datasetoran'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionRedirectToIndex() {
        Yii::app()->user->setFlash('success', "Data Setoran Berhasil Ditambah.");
        $this->redirect(array('index'));
    }

    public function actionChildDataSetoran() {
        $id = Yii::app()->getRequest()->getParam('id');
        $id = explode("--", $id);

        if ($id[3] != 0) { // jika tanggal cetak ada, atw faktur sudah diprint
            // echo 'ada';
        } else {
            // echo 'tidak ada';
        }

        $pelangganid = $id[0];
        $tanggal = $id[1];
        $pembelianke = $id[2];

        $child = Datasetoran::model()->listDetail($pelangganid, $tanggal, $pembelianke);

        // partially rendering "_relational" view
        $this->renderPartial('detail', array(
            'id' => Yii::app()->getRequest()->getParam('id'),
            'child' => $child,
        ));
    }

    public function actionSetor($id) {

        if (Yii::app()->request->isAjaxRequest) {
            if ($_POST['bayar'] != '' AND $_POST['jenispembayaran'] != '' AND $_POST['tanggalsetoran'] != '') {
                $cekFaktur = Faktur::model()->findByPk($id);

                //setoran                          					
                $transaction = Yii::app()->db->beginTransaction();
                try {

                    //update date
                    $faktur = Faktur::model()->findByPk($id);
                    $faktur->updateddate = date("Y-m-d H:i:s", time());
                    $faktur->save();

                    //cash								
                    if ($_POST['jenispembayaran'] == 1) {
                        
                        //print_r($_POST['bayar']);
                        
                        $setoran = new Setoran;
                        $setoran->fakturid = $faktur->id;
                        $setoran->tanggalsetoran = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggalsetoran']);
                        $setoran->jumlah = $_POST['bayar'];
                        $setoran->jenisbayar = 'setoran';
                        $setoran->createddate = date("Y-m-d H:", time());
                        $setoran->userid = Yii::app()->user->id;
                        if ($setoran->save()) {
                            $faktur->sisa = $faktur->sisa - $_POST['bayar'];

                            $faktur->bayar = $faktur->bayar + $_POST['bayar'];
                            if ($faktur->save()) {
                                if ($faktur->sisa == 0) {
                                    echo CJSON::encode(array('result' => 'LUNAS', 'pelangganid' => $faktur->pelangganid,
                                        'tanggalpembelian' => $faktur->tanggal, 'tanggalcetak' => $faktur->tanggalcetak,
                                        'pembelianke' => $cekFaktur->pembelianke));
                                } else {
                                    echo CJSON::encode(array('result' => 'OK'));
                                }
                            }
                        }                         
                    }
                    //Transfer
                    if ($_POST['jenispembayaran'] == 2) {

                        $connection = Yii::app()->db;
                        $rekeningid = $_POST['rekeningid'];
             
                        $sql = "select max(id) as id from transaksi.transfer where rekeningid=" . intval($_POST['rekeningid']);
                        $data = $connection->createCommand($sql)->queryRow();
                        $id = $data["id"];
                        if ($id != "") {
                            $saldo = Transfer::model()->findByPk($data["id"])->saldo;
                            if ($saldo != "" || $saldo != 0) {
                                $saldoTerakhir = $saldo;
                            } else {
                                $saldoTerakhir = 0;
                            }
                        } else {
                            $saldoTerakhir = 0;
                        }

                        $model = new Transfer;
                        $model->jenistransferid = 5; //transfer masuk
                        $model->rekeningid = $_POST['rekeningid'];
                        $model->pelangganid = $faktur->pelangganid;
                        $model->kredit = $_POST['bayar'];
                        $model->saldo = $_POST['bayar'] + $saldoTerakhir;
                        $model->tanggal = date("Y-m-d H:", time());
                        $model->createddate = date("Y-m-d H:", time());
                        $model->userid = Yii::app()->user->id;
                        if ($model->save()) {
                            $setoran = new Setoran;
                            $setoran->fakturid = $faktur->id;
                            $setoran->tanggalsetoran = date("Y-m-d H:", time());
                            $setoran->jumlah = $_POST['bayar'];
                            $setoran->jenisbayar = 'transfer';
                            $setoran->createddate = date("Y-m-d H:", time());
                            $setoran->userid = Yii::app()->user->id;
                            if ($setoran->save()) {
                                $faktur->sisa = $faktur->sisa - $_POST['bayar'];
                                $faktur->bayar = $faktur->bayar + $_POST['bayar'];
                                if ($faktur->save()) {
                                    if ($faktur->sisa == 0) {
                                        echo CJSON::encode(array('result' => 'LUNAS', 'pelangganid' => $faktur->pelangganid,
                                            'tanggalpembelian' => $faktur->tanggal,
                                            'tanggalCetak' => $faktur->tanggalcetak, 'pembelianke' => $cekFaktur->pembelianke));
                                    } else {
                                        echo CJSON::encode(array('result' => 'OK'));
                                    }
                                }
                            }
                        }
                    }
                    $transaction->commit();
                } catch (Exception $error) {
                    $transaction->rollback();
                    throw $error;
                }
            } else {
                echo CJSON::encode(array('result' => 'FAIL'));
            }
        }
    }

    public function actionCheckDataSetoran() {
        if (Yii::app()->request->isAjaxRequest) {

            $criteria = new CDbCriteria;
            $criteria->select = "jumlah,tanggalsetoran,jenisbayar";
            $faktur = $_POST['faktur'];
            $criteria->condition = "nofaktur = '$faktur'";
            $criteria->join = 'inner join transaksi.faktur f on f.id=t.fakturid';
            $data = Setoran::model()->findAll($criteria);

            $this->layout = 'a';
            $this->render('check_data_setoran', array(
                'data' => $data,
                    ), false, true);
            Yii::app()->end();
        }
    }

    public function actionCetak($id) {
        if (Yii::app()->request->isAjaxRequest) {

            $pelangganid = substr($id, 10, 4);
            $kodePelanggan = Pelanggan::model()->findByPk($pelangganid)->kode;
            $tanggal = substr($id, 0, 10);
            $noFaktur = $kodePelanggan . "/" . $tanggal;

            $totalHarga = Datasetoran::model()->getTotalBelanja($pelangganid, $tanggal);
            $cekFaktur = Faktur::model()->find("nofaktur='$noFaktur'");

            if ($cekFaktur == '') {
                $faktur = new Faktur;
                $faktur->nofaktur = $noFaktur;
                $faktur->createddate = date("Y-m-d H:i:s", time());
                $faktur->userid = Yii::app()->user->id;
                $faktur->hargatotal = $totalHarga;
                $faktur->sisa = $totalHarga;
                $faktur->bayar = 0;
                $faktur->lokasipenyimpananbarangid = Yii::app()->session['lokasiid'];
                $faktur->jenispembayaran = 'kredit';
                if ($faktur->save()) {
                    //select data barang untuk input ke table fakturpenjualangbarang
                    $dataPenjualan = Datasetoran::model()->findAll("cast(tanggal as date)='$tanggal' AND pelangganid=$pelangganid and lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                    for ($i = 0; $i < count($dataPenjualan); $i++) {
                        $penjualanBarang = new Fakturpenjualanbarang;
                        $penjualanBarang->penjualanbarangid = $dataPenjualan[$i]['id'];
                        $penjualanBarang->fakturid = $faktur->id;
                        $penjualanBarang->save();
                    }

                    echo CJSON::encode(array('pelangganid' => $pelangganid, 'tanggal' => $tanggal));
                }
            } else {
                echo CJSON::encode(array('pelangganid' => $pelangganid, 'tanggal' => $tanggal));
            }
        }
    }

    //check apakah masuk ke form faktur atw cetak faktur
    public function actionCheckFormFaktur($id) {
        $id = explode("--", $id);

        $pelangganid = $id[0];
        $tanggal = $id[1];
        $pembelianke = $id[2];

        $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' AND pelangganid=" . $pelangganid . " 
                                                AND jenispembayaran='kredit' AND pembelianke=" . $pembelianke . " "
                . "AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);

        if (count($cekFaktur) != 0) {
            $tanggalPembelian = date("Y-m-d", strtotime($cekFaktur->tanggal));
            $tanggalCetak = date("Y-m-d", strtotime($cekFaktur->tanggalcetak));
            echo CJSON::encode(array('status' => 'true', 'pelangganid' => $pelangganid,
                'tanggalcetak' => $tanggalCetak,
                'tanggalpembelian' => $tanggalPembelian,
                'pembelianke' => $pembelianke));
        } else {

            $transaction = Yii::app()->db->beginTransaction();
            try {

                $criteria = new CDbCriteria;
                $criteria->select = '*';
                $criteria->condition = "cast(tanggal as date)='$tanggal' "
                        . "                 and pembelianke=" . $pembelianke . " and issendtokasir=true and statuspenjualan=false and isdeleted=false"
                        . "                     and pelangganid=" . $pelangganid . " "
                        . "                         and jenispembayaran='kredit' and lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid'];

                $penjualanBarang = Datasetoran::model()->findAll($criteria);
                for ($i = 0; $i < count($penjualanBarang); $i++) {
                    $totalHarga[] = $penjualanBarang[$i]["hargatotal"];
                }
                //print("<pre>".print_r($totalHarga,true)."</pre>");	
                //die;
                $totalHarga = array_sum($totalHarga);
                $faktur = new Faktur;
                $faktur->nofaktur = Pelanggan::model()->findByPk($pelangganid)->kode . "" . $this->generateRandomFaktur();
                $faktur->pelangganid = $pelangganid;
                $faktur->pembelianke = $pembelianke;
                $faktur->createddate = date("Y-m-d H:i:s", time());
                $faktur->userid = Yii::app()->user->id;
                $faktur->tanggal = $tanggal;
                $faktur->tanggalcetak = date("Y-m-d H:i:s", time());
                $faktur->jenispembayaran = 'kredit';
                $faktur->hargatotal = $totalHarga;
                $faktur->sisa = $totalHarga;
                $faktur->diskon = 0;
                $faktur->bayar = 0;
                $faktur->lokasipenyimpananbarangid = Yii::app()->session['lokasiid'];
                if ($faktur->save()) { // save faktur
                    $dataPenjualan = Datasetoran::model()->findAll("cast(tanggal as date)='$tanggal' AND isdeleted=false
                                                                    AND issendtokasir=true AND statuspenjualan=false AND 
                                                                        pembelianke=$pembelianke
                                                                            AND pelangganid=$pelangganid AND 
                                                                                    lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                    for ($i = 0; $i < count($dataPenjualan); $i++) {
                        //faktur penjualan
                        $fakturpenjualan = new Fakturpenjualanbarang;
                        $fakturpenjualan->penjualanbarangid = $dataPenjualan[$i]['id'];
                        $fakturpenjualan->fakturid = $faktur->id;
                        $fakturpenjualan->save(false);
                        //
                    }

                    $tanggalCetak = date("Y-m-d");
                    $tanggalPembelian = $tanggal;

                    $updateStatusPenjualan = Datasetoran::model()->updateStatusPenjualan($pelangganid, $tanggalPembelian, $pembelianke, $tanggalCetak);
                }
                $transaction->commit();
                echo CJSON::encode(array('status' => 'false', 'pelangganid' => $pelangganid, 'tanggalpembelian' => $tanggalPembelian, 'pembelianke' => $pembelianke, 'tanggalcetak' => $tanggalCetak));
            } catch (Exception $error) {
                $transaction->rollback();
                throw $error;
            }
        }
    }

    //form faktur
    public function actionFormFaktur($pelangganid, $tanggalpembelian, $pembelianke, $tanggalcetak) {
        $data = Datasetoran::model()->detailFormFaktur($pelangganid, $tanggalpembelian, $pembelianke, $tanggalcetak);
        $this->render('form_faktur', array(
            'data' => $data,
            'pelangganid' => $pelangganid,
            'tanggalpembelian' => $tanggalpembelian,
            'pembelianke' => $pembelianke,
            'tanggalcetak' => $tanggalcetak,
        ));
    }

    public function generateRandomFaktur($digits = 4) {
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while ($i < $digits) {
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    //simpan form faktur
    public function actionSimpan() {
        if (Yii::app()->request->isAjaxRequest) {

            $tanggalpembelian = $_POST['tanggalpembelian'];
            $tanggalcetak = $_POST['tanggalcetak'];
            $pelangganid = $_POST['pelangganid'];
            $pembelianke = $_POST['pembelianke'];
            $nofaktur = $_POST['nofaktur'];


            if ($tanggalcetak != '' && $pelangganid != '' && $pembelianke != '' && $tanggalpembelian != '' && $nofaktur != '') {
                //die;
                $transaction = Yii::app()->db->beginTransaction();
                try {
                    $faktur = Faktur::model()->find("nofaktur='$nofaktur'");
                    //print("<pre>".print_r($faktur,true)."</pre>");	
                    // die;
                    $faktur->bayar = 0;
                    if ($_POST['totaldiskoninput'] != 0 || $_POST['totaldiskoninput'] != "") {
                        $faktur->sisa = $faktur->hargatotal - ($_POST['totaldiskoninput']);
                        $faktur->diskon = $_POST['totaldiskoninput'];
                    }
                    if ($faktur->save()) { // save faktur
                        //select data berdasarkan penjualan barang 
                        for ($i = 0; $i < count($_POST['penjualanbarangid']); $i++) {
                            //klw ada diskon
                            if ($_POST['diskoninput'][$i] != 0) {
                                $model = Datasetoran::model()->findByPk($_POST['penjualanbarangid'][$i]);
                                $model->diskon = intval($_POST['diskoninput'][$i]);
                                $model->save(false);
                            }
                            //                                                            
                        }

                        //select data barang untuk diupdate stocknya
                        $dataPenjualan = Datasetoran::model()->findAll("cast(tanggal as date)='$tanggalpembelian' and jenispembayaran='kredit'
						AND pelangganid=$pelangganid and lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid'] . " 
						AND statuspenjualan=true AND isdeleted=false AND issendtokasir=true AND pembelianke=" . $pembelianke);
                        //print("<pre>".print_r($dataPenjualan,true)."</pre>");	
                        // die;
                        for ($i = 0; $i < count($dataPenjualan); $i++) {
                            //kurangi stock barang keseluruhan                            
                            $stock = Stock::model()->find("barangid=" . $dataPenjualan[$i]["barangid"] . " AND lokasipenyimpananbarangid=" . $dataPenjualan[$i]["lokasipenyimpananbarangid"]);
                            if (count($stock) > 0) {
                                if ($stock->jumlah >= $dataPenjualan[$i]["jumlah"]) {
                                    $stock->jumlah = $stock->jumlah - $dataPenjualan[$i]["jumlah"];
                                    if ($stock->save()) {
                                        //stock supplier
                                        $stockSupplier = Stocksupplier::model()->find("barangid=" . $dataPenjualan[$i]["barangid"] . " AND lokasipenyimpananbarangid=" . $dataPenjualan[$i]["lokasipenyimpananbarangid"] . " AND supplierid=" . $dataPenjualan[$i]["supplierid"]);
                                        if (count($stockSupplier) > 0) {
                                            if ($stockSupplier->jumlah >= $dataPenjualan[$i]["jumlah"]) {
                                                $stockSupplier->jumlah = $stockSupplier->jumlah - $dataPenjualan[$i]["jumlah"];
                                                $stockSupplier->save();
                                            }
                                        }
                                        //end 
                                    }
                                    // end stock keseuluruhan
                                }
                            }
                        }
                        //end					

                        $transaction->commit();
                        //redirect ke print faktur
                        echo CJSON::encode(array('result' => 'OK',
                            'pelangganid' => $_POST['pelangganid'],
                            'tanggalpembelian' => $_POST['tanggalpembelian'],
                            'pembelianke' => $pembelianke,
                            'tanggalcetak' => $tanggalcetak));
                    }
                } catch (Exception $error) {
                    $transaction->rollback();
                    throw $error;
                }
            }
        }
    }

    public function actionPrintFaktur() {
        $this->renderPartial('cetak_faktur', array(
        ));
    }

    public function actionPopupSetor($id) {

        if (Yii::app()->request->isAjaxRequest) {

            $id = explode("--", $id);

            $pelangganid = $id[0];
            $tanggal = $id[1];
            $pembelianke = $id[2];
            $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' AND pelangganid=" . $pelangganid . " 
															AND jenispembayaran='kredit' AND pembelianke=" . $pembelianke . " 
																AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
            if (count($cekFaktur) != 0) {
                $model = new Datasetoran;

                $this->layout = 'a';
                $this->renderPartial('form_setoran', array(
                    'noFaktur' => $cekFaktur->nofaktur,
                    'model' => $model,
                    'totalHarga' => $cekFaktur->hargatotal,
                    'totalSetoran' => $cekFaktur->bayar,
                    'sisa' => $cekFaktur->sisa,
                    'tanggal' => $tanggal,
                    'pelangganid' => $pelangganid,
                    'pembelianke' => $pembelianke,
                    'id' => $cekFaktur->id,
                    'totalDiskon' => $cekFaktur->diskon,
                        ), false, true);
                Yii::app()->end();
            } else {
                echo 'Cetak Faktur Terlebih Dahulu';
            }
        }
    }

    public function actionGetNorek() {
        if (Yii::app()->request->isAjaxRequest) {
            $model = Rekening::model()->findByPk(intval($_POST['rekeningid']));
            echo CJSON::encode(array
                (
                'result' => 'OK',
                'data' => $model
            ));
        }
    }

    public function actionGetSaldoRek() {
        if (Yii::app()->request->isAjaxRequest) {
            $connection = Yii::app()->db;
            $sql = "select max(id) as id from transaksi.transfer where rekeningid=" . intval($_POST['rekeningid']);
            $data = $connection->createCommand($sql)->queryRow();
            $id = $data["id"];
            if ($id != "") {
                $saldo = Transfer::model()->findByPk($data["id"])->saldo;
                if ($saldo != "" || $saldo != 0) {
                    $saldoTerakhir = $saldo;
                } else {
                    $saldoTerakhir = 0;
                }
            } else {
                $saldoTerakhir = 0;
            }

            echo CJSON::encode(array
                (
                'saldo' => $saldoTerakhir
            ));
        }
    }

    protected function performAjaxValidation($model1, $model2) {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'gudangpenjualanbarang-form') {
            echo CActiveForm::validate(array($model1, $model2));
            Yii::app()->end();
        }
    }

}
