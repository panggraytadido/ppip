<?php

class InboxController extends Controller {

    public $layout = '//layouts/column2';
    public $pageTitle = 'Inbox - Kasir';

    public function actionIndex() {

        $model = new Inbox('search');
        $model->unsetAttributes();

        if (isset($_GET['Inbox']))
            $model->attributes = $_GET['Inbox'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    public function actionChildInbox() {

        $id = Yii::app()->getRequest()->getParam('id');
        $id = explode("--", $id);

        $pelangganid = $id[0];
        $tanggal = $id[1];
        $pembelianke = $id[2];
        //die;

        $child = Inbox::model()->listDetailInbox($pelangganid, $tanggal, $pembelianke);
        $this->renderPartial('detail', array(
            //'id' => Yii::app()->getRequest()->getParam('id'),
            'child' => $child,
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

    public function actionFormFaktur($pelangganid, $tanggal, $pembelianke) {

        if ($pelangganid != "" && $tanggal != "" && $pembelianke != "") {
            $transaction = Yii::app()->db->beginTransaction();
            try {

                // $pembelianke=1;
                $data = Inbox::model()->detailFormFaktur($pelangganid, $tanggal, $pembelianke);
                $faktur = new Faktur;
                $faktur->nofaktur = Pelanggan::model()->findByPk($pelangganid)->kode . "" . $this->generateRandomFaktur();
                $faktur->pelangganid = $pelangganid;
                $faktur->pembelianke = $pembelianke;
                $faktur->createddate = date("Y-m-d H:i:s", time());
                $faktur->userid = Yii::app()->user->id;
                $faktur->tanggal = $tanggal;
                $faktur->jenispembayaran = 'tunai';
                $faktur->lokasipenyimpananbarangid = Yii::app()->session['lokasiid'];
                if ($faktur->save()) { // save faktur
                    $dataPenjualan = Inbox::model()->findAll("cast(tanggal as date)='$tanggal' 
																	AND isdeleted=false AND issendtokasir=true 
																		AND statuspenjualan=false 
																			AND pelangganid=$pelangganid
																				AND pembelianke=$pembelianke
																				AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                    for ($i = 0; $i < count($dataPenjualan); $i++) {
                        //faktur penjualan
                        $fakturpenjualan = new Fakturpenjualanbarang;
                        $fakturpenjualan->penjualanbarangid = $dataPenjualan[$i]['id'];
                        $fakturpenjualan->fakturid = $faktur->id;
                        $fakturpenjualan->save(false);
                        //
                    }
                }

                $transaction->commit();
            } catch (Exception $error) {
                $transaction->rollback();
                throw $error;
            }
        }

        $this->render('form_faktur', array(
            'data' => $data,
            'pelangganid' => $pelangganid,
            'tanggal' => $tanggal,
            'pembelianke' => $pembelianke,
        ));
    }

    //simpan form faktur
    public function actionSimpan() {
        $tanggal = $_POST['tanggal'];
        $pelangganid = $_POST['pelangganid'];
        $pembelianke = $_POST['pembelianke'];

        if ($tanggal != "" && $pelangganid != "" && $pembelianke != "") {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                $faktur = Faktur::model()->find("pelangganid=" . intval($pelangganid) . " 
								and cast(tanggal as date)='$tanggal' 
									and pembelianke=" . $pembelianke . " AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);

                $faktur->hargatotal = $_POST['hargatotalinput'];
                $faktur->bayar = $_POST['bayar'];
                $faktur->sisa = 0;//$_POST['hargatotalinput'] - ($_POST['bayar'] - $_POST['totaldiskoninput']);
                $faktur->diskon = $_POST['totaldiskoninput'];
                $faktur->tanggalcetak = date("Y-m-d H:i:s", time());
                if ($faktur->save()) { // save faktur
                    $setoran = new Setoran;
                    $setoran->fakturid = $faktur->id;
                    $setoran->tanggalsetoran = date("Y-m-d H:", time());
                    $setoran->jumlah = $_POST['bayar'];                     
                    $setoran->jenisbayar = 'cash';
                    $setoran->createddate = date("Y-m-d H:", time());
                    $setoran->userid = Yii::app()->user->id;
                    $setoran->save();


                    //select data berdasarkan penjualan barang 
                    for ($i = 0; $i < count($_POST['penjualanbarangid']); $i++) {
                        //klw ada diskon
                        if ($_POST['diskoninput'][$i] != 0) {
                            $model = Inbox::model()->findByPk($_POST['penjualanbarangid'][$i]);
                            $model->diskon = intval($_POST['diskoninput'][$i]);
                            $model->save(false);
                        }
                        //                                                            
                    }

                    //select data barang untuk diupdate stocknya							
                    $dataPenjualan = Inbox::model()->findAll("cast(tanggal as date)='$tanggal' 
                                                                                                    AND isdeleted=false AND issendtokasir=true 
                                                                                                            AND statuspenjualan=false AND pelangganid=$pelangganid 
                                                                                                                    AND pembelianke=$pembelianke and jenispembayaran='tunai'
                                                                                                                    AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                    for ($i = 0; $i < count($dataPenjualan); $i++) {
                        //kurangi stock barang keseluruhan                 
                        $stock = Stock::model()->find("barangid=" . $dataPenjualan[$i]["barangid"] . " 
												AND lokasipenyimpananbarangid=" . $dataPenjualan[$i]["lokasipenyimpananbarangid"]);
                        if (count($stock) > 0) {
                            if ($stock->jumlah >= $dataPenjualan[$i]["jumlah"]) {
                                $stock->jumlah = $stock->jumlah - $dataPenjualan[$i]["jumlah"];
                                if ($stock->save()) {
                                    //stock supplier
                                    $stockSupplier = Stocksupplier::model()->find("barangid=" . $dataPenjualan[$i]["barangid"] . " 
                                                                                                                AND lokasipenyimpananbarangid=" . $dataPenjualan[$i]["lokasipenyimpananbarangid"] . " 
                                                                                                                        AND supplierid=" . $dataPenjualan[$i]["supplierid"]);
                                    if (count($stockSupplier) > 0) {
                                        if ($stockSupplier->jumlah >= $dataPenjualan[$i]["jumlah"]) {
                                            $stockSupplier->jumlah = $stockSupplier->jumlah - $dataPenjualan[$i]["jumlah"];
                                            $stockSupplier->save();
                                            //end 
                                        }
                                    }
                                }
                                // end stock keseuluruhan
                            }
                        }
                    }
                    //end

                    $tanggalCetak = date("Y-m-d");
                    $tanggalPembelian = $tanggal;

                    //update status penjualan 
                    $updateStatusPenjualan = Inbox::model()->updateStatusPenjualan(
                            $_POST['pelangganid'], $_POST['tanggal'], $pembelianke, $tanggalCetak);

                    $transaction->commit();
                    //redirect ke print faktur
                    echo CJSON::encode(array('result' => 'OK',
                        'pelangganid' => $_POST['pelangganid'],
                        'tanggal' => $_POST['tanggal'],
                        'pembelianke' => $pembelianke));
                }
            } catch (Exception $error) {
                $transaction->rollback();
                throw $error;
            }
        }
    }

    public function actionCetak() {
        $this->render('cetak_faktur', array(
        ));
    }

    public function actionPopupSetJenisPembayaran($id) {
        if (Yii::app()->request->isAjaxRequest) {
            $id = explode("--", $id);

            $pelangganid = $id[0];
            $tanggal = $id[1];
            $pembelianke = $id[2];


            $pelanggan = Pelanggan::model()->findByPk($pelangganid)->nama;


            $this->layout = 'a';
            $this->render('form_set_jenis_pembayaran', array(
                'pelanggan' => $pelanggan,
                'tanggal' => $tanggal,
                'pelangganid' => $pelangganid,
                'pembelianke' => $pembelianke
                    ), false, true);
            Yii::app()->end();
        }
    }

    public function actionSetJenisPembayaran() {
        if (Yii::app()->request->isAjaxRequest) {

            $pelangganid = $_POST['pelangganid'];
            $tanggal = $_POST['tanggal'];
            $pembelianke = $_POST['pembelianke'];

            if ($pelangganid != "" && $tanggal != "" && $pembelianke != "") {
                //inbox
                if ($_POST['pilihan'] == 1) {
                    echo CJSON::encode(array(
                        'result' => 'INBOX',
                        'tanggal' => $tanggal,
                        'pelangganid' => $pelangganid,
                        'pembelianke' => $pembelianke,
                    ));
                    Yii::app()->end();
                }
                //data setoran
                if ($_POST['pilihan'] == 2) {
                    $data = Inbox::model()->setJenisPembayaran($pelangganid, $tanggal, $pembelianke);
                    Yii::app()->user->setFlash('success', "Data Inbox Berhasil diPindahkan ke Data Setoran.");
                    echo CJSON::encode(array('result' => 'SETORAN'));
                    Yii::app()->end();
                }
            }
        }
    }

    public function actionDelete($id) {
        if (Yii::app()->request->isPostRequest) {
            $id = explode("--", $id);

            $pelangganid = $id[0];
            $tanggal = $id[1];
            $pembelianke = $id[2];

            $criteria = new CDbCriteria;
            $criteria->select = "*";
            $criteria->condition = "cast(tanggal as date)='$tanggal' and pelangganid=" . $pelangganid . " "
                    . " and pembelianke=" . $pembelianke . " and jenispembayaran='tunai' "
                    . "and lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid'];
            $data = Inbox::model()->findAll($criteria);
            if (count($data) > 0) {
                for ($i = 0; $i < count($data); $i++) {
                    $penjualan = Inbox::model()->findByPk($data[$i]["id"]);
                    if (count($penjualan) > 0) {
                        $penjualan->isdeleted = true;
                        $penjualan->save(false);
                    }
                }
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

}
