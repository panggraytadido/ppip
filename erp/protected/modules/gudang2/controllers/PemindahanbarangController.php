<?php

class PemindahanbarangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Gudang - Penjualan Barang';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Gudangpemindahanbarang('search');
            $model->unsetAttributes();
            if(isset($_GET['Gudangpemindahanbarang']))
                    $model->attributes=$_GET['Gudangpemindahanbarang'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionCreate()
        {
            $modelPemindahan = new Gudangpemindahanbarang;
            $modelBongkarmuat = new Bongkarmuat;

            $this->performAjaxValidation($modelPemindahan, $modelBongkarmuat);

            if(isset($_POST['Gudangpemindahanbarang'],$_POST['Bongkarmuat']))
            {
                $createddate = date("Y-m-d H:", time());
                
                $model = new Gudangpemindahanbarang;
                $_POST['Gudangpemindahanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                
                $jumlahTotal = 0;
                $_POST['Gudangpemindahanbarang']['jumlah'] = $jumlahTotal;
                
                $_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangid'] = Yii::app()->session['lokasiid'];
                $_POST['Gudangpemindahanbarang']['userid'] = Yii::app()->user->id;
                $_POST['Gudangpemindahanbarang']['createddate'] = $createddate;
                $_POST['Gudangpemindahanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                
                $model->attributes=$_POST['Gudangpemindahanbarang'];
                $valid = $model->validate();

                if( ($valid && is_numeric($_POST['Bongkarmuat']['upah'])) && (isset($_POST['karyawanid']) && $_POST['Bongkarmuat']['jumlahkaryawan']==count($_POST['karyawanid'])))
                {
                    $_POST['Gudangpemindahanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpemindahanbarang']['tanggal']);
                    
                    for($i=0;$i<count($_POST['jumlah']);$i++)
                    {
                        $jumlahTotal += $_POST['jumlah'][$i];
                    }
                    $_POST['Gudangpemindahanbarang']['jumlah'] = $jumlahTotal;
                    
                    $model->attributes=$_POST['Gudangpemindahanbarang'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
                        if($model->save())
                        {
                            // insert tabel pemindahanbarangdetail
                            for($i=0;$i<count($_POST['jumlah']);$i++)
                            {
                                $modelDetail = new Pemindahanbarangdetail;
                                $modelDetail->pemindahanbarangid = $model->getPrimaryKey();;
                                $modelDetail->supplierid = $_POST['supplierid'][$i];
                                $modelDetail->jumlah = $_POST['jumlah'][$i];
                                $modelDetail->createddate = $createddate;
                                $modelDetail->userid = Yii::app()->user->id;
                                $modelDetail->save();
                            }
                                                       
                            
                            // --- update stocksupplier
                            for($i=0;$i<count($_POST['jumlah']);$i++)
                            {
                                $stockSebelum = Stocksupplier::model()->findByPk($_POST['stocksupplierid'][$i]);
                                $stockSebelum->jumlah = $_POST['stock'][$i] - $_POST['jumlah'][$i];
                                $stockSebelum->updateddate = $createddate;
                                $stockSebelum->save();
                                
                                $stockSesudah = Stocksupplier::model()->find('lokasipenyimpananbarangid = '.$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid'].' and barangid = '.$_POST['Gudangpemindahanbarang']['barangid'].' and supplierid = '.$_POST['supplierid'][$i]);
                                if($stockSesudah!=null)
                                {
                                    $stockSesudah->jumlah = $stockSesudah->jumlah + $_POST['jumlah'][$i];
                                    $stockSesudah->updateddate = $createddate;
                                    $stockSesudah->save();
                                }
                                else
                                {
                                    $stockSesudah = new Stocksupplier;
                                    $stockSesudah->supplierid = $_POST['supplierid'][$i];
                                    $stockSesudah->barangid = $_POST['Gudangpemindahanbarang']['barangid'];
                                    $stockSesudah->lokasipenyimpananbarangid = $_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid'];
                                    $stockSesudah->jumlah = $_POST['jumlah'][$i];
                                    $stockSesudah->createddate = $createddate;
                                    $stockSesudah->userid = Yii::app()->user->id;
                                    $stockSesudah->save();
                                }
                            }
                            
                            //-- update stock
                            $stockAmbil = Stock::model()->find('lokasipenyimpananbarangid = '.Yii::app()->session['lokasiid'].' and barangid = '.$_POST['Gudangpemindahanbarang']['barangid']);
                            $stockAmbil->jumlah = $stockAmbil->jumlah-$jumlahTotal;
                            if($stockAmbil->save()) //kurangi stock pengambilan barang
                            {
                                //tambah stock 
                                $stockPindah = Stock::model()->find('lokasipenyimpananbarangid = '.$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid'].' and barangid = '.$_POST['Gudangpemindahanbarang']['barangid']);
                                $stockPindah->jumlah = $stockPindah->jumlah+$jumlahTotal;
                                $stockPindah->save();
                            } 

                            echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                        'pemindahanid'=>$model->getPrimaryKey()
                                    ));
                            Yii::app ()->user->setFlash ( 'success', "Data Pemindahan Barang Berhasil Ditambah." );
                            Yii::app()->end();
                        }
                    }
                    else
                    {
                        $idnya = $model->getId();

                        echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                        'pemindahanid'=>$idnya
                                    ));
                            Yii::app ()->user->setFlash ( 'success', "Data Pemindahan Barang Berhasil Ditambah." );
                            Yii::app()->end();
                    }
                }
                else
                {
                        $error = CActiveForm::validate($model);
                        if($error!='[]')
                        {
                            echo $error;
                        }
                       
                        Yii::app()->end();
                }

                Yii::app()->end();
            }

            $this->layout='a';
            $this->render('_form',array(
                    'modelPemindahan'=>$modelPemindahan,
                    'modelBongkarmuat'=>$modelBongkarmuat
            ), false, true );
            
            Yii::app()->end();
        }
 
        public function actionUpdate($id)
		{
            $modelPemindahan = Gudangpemindahanbarang::model()->findByPk($id);
            $modelPemindahanDetail = Pemindahanbarangdetail::model()->findAll('pemindahanbarangid='.$modelPemindahan->id.' and isdeleted=false');            

            $lokasipenyimpananbarangtujuanidLama = $modelPemindahan->lokasipenyimpananbarangtujuanid;
            $modelPemindahan->tanggal = date("m/d/Y", strtotime($modelPemindahan->tanggal));
            
            $this->performAjaxValidationUpdate($modelPemindahan);
            
            if(isset($_POST['Gudangpemindahanbarang'],$_POST['Bongkarmuat']))
            {
                $modelPemindahan->attributes=$_POST['Gudangpemindahanbarang'];
                $valid = $modelPemindahan->validate();

                if( ($valid && is_numeric($_POST['Bongkarmuat']['upah'])) && (isset($_POST['karyawanid']) && $_POST['Bongkarmuat']['jumlahkaryawan']==count($_POST['karyawanid'])))
                {
                    // set nilai variable
                    $createddate = $_POST['waktuinsert'];
                    $updatedate = $_POST['waktuupdate'];
                    
                    // set data pemindahan barang
                    $_POST['Gudangpemindahanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpemindahanbarang']['tanggal']);
                    $_POST['Gudangpemindahanbarang']['updateddate'] = $updatedate;
                    $jumlahTotal = 0;
                    for($i=0;$i<count($_POST['jumlah']);$i++)
                    {
                        $jumlahTotal += $_POST['jumlah'][$i];
                    }
                    $_POST['Gudangpemindahanbarang']['jumlah'] = $jumlahTotal;
                    
                    $modelPemindahan->attributes=$_POST['Gudangpemindahanbarang'];
                    
                    if($modelPemindahan->save())
                    {
                        // update tabel pemindahanbarangdetail
                        
                        // jika lokasi tidak berubah
                        if($lokasipenyimpananbarangtujuanidLama==$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid'])
                        {
                            $i=0;
                            $r = Pemindahanbarangdetail::model()->findAll('pemindahanbarangid='.$id.' and isdeleted=false');
                            foreach($r as $modelDetail)
                            {
                                $modelDetail->supplierid = $_POST['supplierid'][$i];
                                $modelDetail->updateddate = $updatedate;

                                // stok barang asal
                                $modelStockSupplier = Stocksupplier::model()->find('lokasipenyimpananbarangid='.$modelPemindahan->lokasipenyimpananbarangid.' and barangid='.$modelPemindahan->barangid.' and supplierid='.$_POST['supplierid'][$i]);
                                $modelStockSupplier->jumlah = ($modelStockSupplier->jumlah + $modelDetail->jumlah) - $_POST['jumlah'][$i];
                                $modelStockSupplier->updateddate = $updatedate;
                                $modelStockSupplier->save();
                             
                                // stok barang tujuan
                                $modelStockSupplier = Stocksupplier::model()->find('lokasipenyimpananbarangid='.$modelPemindahan->lokasipenyimpananbarangtujuanid.' and barangid='.$modelPemindahan->barangid.' and supplierid='.$_POST['supplierid'][$i]);
                                $modelStockSupplier->jumlah = ($modelStockSupplier->jumlah - $modelDetail->jumlah) + $_POST['jumlah'][$i];
                                $modelStockSupplier->updateddate = $updatedate;
                                $modelStockSupplier->save();

                                $modelDetail->jumlah = $_POST['jumlah'][$i];
                                $modelDetail->save();
                                
                                $i++;
                            }													
                        }
                        else // jika lokasi berubah
                        {
							
                            $i=0;
                            $r = Pemindahanbarangdetail::model()->findAll('pemindahanbarangid='.$id.' and isdeleted=false');
							
                            foreach($r as $modelDetail)
                            {
                                // stok barang asal
                                $modelStockSupplier = Stocksupplier::model()->find('lokasipenyimpananbarangid='.$modelPemindahan->lokasipenyimpananbarangid.' and barangid='.$modelPemindahan->barangid.' and supplierid='.$modelDetail->supplierid);
                                $modelStockSupplier->jumlah = ($modelStockSupplier->jumlah + $modelDetail->jumlah);
                                $modelStockSupplier->updateddate = $updatedate;
                                $modelStockSupplier->save();

                                // stok barang lama tujuan
                                $modelStockSupplier = Stocksupplier::model()->find('lokasipenyimpananbarangid='.$lokasipenyimpananbarangtujuanidLama.' and barangid='.$modelPemindahan->barangid.' and supplierid='.$modelDetail->supplierid);
                                $modelStockSupplier->jumlah = ($modelStockSupplier->jumlah - $modelDetail->jumlah);
                                $modelStockSupplier->updateddate = $updatedate;
                                $modelStockSupplier->save();

                                //$modelDetail->isdeleted = 1; DIPAKAI JIKA BARANG DIUBAH
                                $modelDetail->jumlah = $_POST['jumlah'][$i];
                                $modelDetail->updateddate = $updatedate;
                                $modelDetail->save();
                                
                                $i++;
                            }
							
                            
                            // --- update stocksupplier
                            for($i=0;$i<count($_POST['jumlah']);$i++)
                            {
                                // stock asal
                                $stockSebelum = Stocksupplier::model()->findByPk($_POST['stocksupplierid'][$i]);
                                $stockSebelum->jumlah = $stockSebelum->jumlah - $_POST['jumlah'][$i];
                                $stockSebelum->updateddate = $updatedate;
                                $stockSebelum->save();
                                
                                // stock tujuan
                                $stockSesudah = Stocksupplier::model()->find('lokasipenyimpananbarangid = '.$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid'].' and barangid = '.$modelPemindahan->barangid.' and supplierid = '.$_POST['supplierid'][$i]);
                                if($stockSesudah!=null)
                                {
                                    $stockSesudah->jumlah = $stockSesudah->jumlah + $_POST['jumlah'][$i];
                                    $stockSesudah->updateddate = $updatedate;
                                    $stockSesudah->save();
                                }
                                else
                                {
                                    $stockSesudah = new Stocksupplier;
                                    $stockSesudah->supplierid = $_POST['supplierid'][$i];
                                    $stockSesudah->barangid = $modelPemindahan->barangid;
                                    $stockSesudah->lokasipenyimpananbarangid = $_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid'];
                                    $stockSesudah->jumlah = $_POST['jumlah'][$i];
                                    $stockSesudah->createddate = $createddate;
                                    $stockSesudah->userid = Yii::app()->user->id;
                                    $stockSesudah->save();
                                }
                            }
							
							//stock per lokasi
							$stockAsal = Stock::model()->find('barangid='.$modelPemindahan->barangid.' AND lokasipenyimpananbarangid='.$lokasipenyimpananbarangtujuanidLama);
							$stockAsal->jumlah= $stockAsal->jumlah-$modelPemindahan->jumlah;
							if($stockAsal->save(false))
							{
								$stockTujuan = Stock::model()->find('barangid='.$modelPemindahan->barangid.' AND lokasipenyimpananbarangid='.$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid']);
								$stockTujuan->jumlah = $stockTujuan->jumlah+$jumlahTotal;
								$stockTujuan->save(false);
							}
							//

                        }
                       

                        echo CJSON::encode(array
                                (
                                    'result'=>'OK'
                                ));
                        Yii::app ()->user->setFlash ( 'success', "Data Pemindahan Barang Berhasil Diupdate.");
                        Yii::app()->end();
                        exit;
                    }
                }
                else
                {
                        $error = CActiveForm::validate($modelPemindahan);
                        if($error!='[]')
                        {
                            echo $error;
                        }
                       
                        Yii::app()->end();
                }

                Yii::app()->end();
            }
            
            $this->layout='a';
            $this->renderPartial( '_form_update', array (
                            'modelPemindahan' => $modelPemindahan,
                            'modelPemindahanDetail' => $modelPemindahanDetail,                           
            ), false, true );
            Yii::app()->end();
	}
 
        public function actionView($id)
        {
            $modelPemindahan = Gudangpemindahanbarang::model()->findByPk($id);
            $modelDetail = Pemindahanbarangdetail::model()->findAll('pemindahanbarangid='.$id);            
            
            $this->render('view',array(
                    'modelPemindahan'=>$modelPemindahan,
                    'modelDetail'=>$modelDetail,                    
            ));
        }
 
        public function actionHargabarang()
        {
            $barangid = $_POST['barangid'];
            $kategori = $_POST['kategori'];
            $supplierid = $_POST['supplierid'];
            
            $recBarang = Hargabarang::model()->find('barangid='.$barangid.' and supplierid='.$supplierid);
            
            if($kategori==1) // jika harga eceran
                echo $recBarang->hargaeceran;
            else
                echo $recBarang->hargagrosir;
        }

        public function actionCekstock()
        {
            $barangid = $_POST['barangid'];
            $lokasiid = $_POST['lokasiid'];
            $supplierid = $_POST['supplierid'];
            $aksi = $_POST['aksi'];
            $id = $_POST['id'];
                
            $criteria=new CDbCriteria;
            $criteria->select = 'sum(jumlah) as jumlah';
            $criteria->condition = 'statuspenjualan = false';
            $criteria->condition .= ' and barangid = '.$barangid.' and lokasipenyimpananbarangid='.$lokasiid.' and supplierid='.$supplierid;
            if($aksi=='update') // jika form update
                $criteria->condition .= ' and id != '.$id;
            $recAntrian = Pemindahanbarang::model()->find($criteria);
            $stockAntrian = ($recAntrian->jumlah!=null) ? $recAntrian->jumlah : 0;
            $stockGudang = Stocksupplier::model()->find('barangid='.$barangid.' and lokasipenyimpananbarangid='.$lokasiid.' and supplierid='.$supplierid)->jumlah;
            $stockTersedia = ($stockGudang - $stockAntrian);
            // =========================================
            
            echo CJSON::encode(array(
                    'stockTersedia' => $stockTersedia,
                    'stockGudang' => $stockGudang,
                    'stockAntrian' => $stockAntrian,
                ));
        }
        
        public function actionGetlokasibarang()
        {
            $barangid = $_POST['barangid'];
            
            $criteria=new CDbCriteria;
            $criteria->select = 't.id, t.nama';
            $criteria->join = 'inner join transaksi.stocksupplier s on t.id=s.lokasipenyimpananbarangid';
            $criteria->condition = 't.isdeleted = false and barangid = '.$barangid.' and s.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
            $rec = Lokasipenyimpananbarang::model()->findAll($criteria);
            
            $i=0;
            $id = array();
            $nama = array();
            foreach($rec as $r)
            {
                $id[$i] = $r->id;
                $nama[$i] = $r->nama;
                $i++;
            }
            
            echo CJSON::encode(array(
                    'id' => $id,
                    'text' => $nama,
                ));
        }

        public function actionGetSupplierBarang()
        {
            $barangid = $_POST['barangid'];
            
            $criteria=new CDbCriteria;
            $criteria->select = 't.id, s.id as fax, t.namaperusahaan, s.jumlah as hp';
            $criteria->join = 'inner join transaksi.stocksupplier s on t.id=s.supplierid ';
            $criteria->join .= 'inner join master.barang b on s.barangid=b.id';
            $criteria->condition = 'barangid = '.$barangid.' and lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'].' and b.divisiid='.Yii::app()->session['divisiid'];
            
            $rec = Supplier::model()->findAll($criteria);
            
            $i=0;
            $stocksupplierid = array();
            $id = array();
            $nama = array();
            $jumlah = array();
            foreach($rec as $r)
            {
                $stocksupplierid[$i] = $r->fax;
                $id[$i] = $r->id;
                $nama[$i] = $r->namaperusahaan;
                $jumlah[$i] = $r->hp;
                $i++;
            }
            
            echo CJSON::encode(array(
                    'stocksupplierid' => $stocksupplierid,
                    'supplierid' => $id,
                    'namasupplier' => $nama,
                    'jumlah' => $jumlah,
                ));
        }
        
        public function actionGetkaryawanabsen()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                $option = '';
                $jml = 0;
                //$rec = Karyawan::model()->with('absensi')->findAll("jabatanid=11 and tanggal::text like '".$tanggal."%' and jammasuk is not null"); // jabatan pegawai bongkar muat & absen di hari transaksi
				$rec = Karyawan::model()->findAll("jabatanid=11"); // jabatan pegawai bongkar muat & absen di hari transaksi
                foreach($rec as $r)
                {
                    $option .= '<option value="'.$r->id.'">'.$r->nama.'</option>';
                    $jml++;
                }
                
                echo CJSON::encode(array(
                  'option'=>$option,
                  'jml'=>$jml
                ));
            }
            else
            {
                echo CJSON::encode(array(
                  'option'=>'',
                  'jml'=>0
                ));
            }
        }
        
        public function actionGetjumlahabsen()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                //$jml = Karyawan::model()->with('absensi')->count("jabatanid=11 and tanggal::text like '".$tanggal."%' and jammasuk is not null");
				$jml = Karyawan::model()->count("jabatanid=11");
                
                echo $jml;
            }
            else
                echo 0;
        }
        
        protected function performAjaxValidation($model1, $model2)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpemindahanbarang-form')
            {
                echo CActiveForm::validate(array($model1, $model2));
                Yii::app()->end();
            }
        }
        
        protected function performAjaxValidationUpdate($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpemindahanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        public function actionDelete($id)
	{
            if(Yii::app()->request->isPostRequest)
            {
                $Bongkarmuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$id);
                foreach($Bongkarmuat as $recBongkarmuat)
                {
                    $data = Bongkarmuat::model()->findByPk($recBongkarmuat->id);
                    $data->isdeleted = 1;
                    $data->save();
                }
                
                $penjualan = Gudangpemindahanbarang::model()->findByPk($id);
                $penjualan->isdeleted = 1;
                $penjualan->save();

                // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                if(!isset($_GET['ajax']))
                        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
            else
                throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}