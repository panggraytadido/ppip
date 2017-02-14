<?php

class PemindahanbarangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Hygienis - Pemindahan Barang';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Hygienispemindahanbarang('search');
            $model->unsetAttributes();
            if(isset($_GET['Hygienispemindahanbarang']))
                    $model->attributes=$_GET['Hygienispemindahanbarang'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionCreate()
        {
            $modelPemindahan = new Hygienispemindahanbarang;            

            $this->performAjaxValidation($modelPemindahan);

            if(isset($_POST['Hygienispemindahanbarang']))
            {
                $createddate = date("Y-m-d H:", time());
                
                $model = new Hygienispemindahanbarang;
                $_POST['Hygienispemindahanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                
                $jumlahTotal = 0;
                $_POST['Hygienispemindahanbarang']['jumlah'] = $jumlahTotal;
                
                $_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangid'] = Yii::app()->session['lokasiid'];
                $_POST['Hygienispemindahanbarang']['userid'] = Yii::app()->user->id;
                $_POST['Hygienispemindahanbarang']['createddate'] = $createddate;
                $_POST['Hygienispemindahanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                
                $model->attributes=$_POST['Hygienispemindahanbarang'];
                $valid = $model->validate();

                if($valid)
                {
                    $_POST['Hygienispemindahanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Hygienispemindahanbarang']['tanggal']);
                    
                    for($i=0;$i<count($_POST['jumlah']);$i++)
                    {
                        $jumlahTotal += $_POST['jumlah'][$i];
                    }
                    $_POST['Hygienispemindahanbarang']['jumlah'] = $jumlahTotal;
                    
                    $model->attributes=$_POST['Hygienispemindahanbarang'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
						$transaction = Yii::app()->db->beginTransaction();
						try{
							
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
									
									$stockSesudah = Stocksupplier::model()->find('lokasipenyimpananbarangid = '.$_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangtujuanid'].' and barangid = '.$_POST['Hygienispemindahanbarang']['barangid'].' and supplierid = '.$_POST['supplierid'][$i]);
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
										$stockSesudah->barangid = $_POST['Hygienispemindahanbarang']['barangid'];
										$stockSesudah->lokasipenyimpananbarangid = $_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangtujuanid'];
										$stockSesudah->jumlah = $_POST['jumlah'][$i];
										$stockSesudah->createddate = $createddate;
										$stockSesudah->userid = Yii::app()->user->id;
										$stockSesudah->save();
									}
								}
								
								//-- update stock
								$stockAmbil = Stock::model()->find('lokasipenyimpananbarangid = '.Yii::app()->session['lokasiid'].' and barangid = '.$_POST['Hygienispemindahanbarang']['barangid']);
								$stockAmbil->jumlah = $stockAmbil->jumlah-$jumlahTotal;
								if($stockAmbil->save()) //kurangi stock pengambilan barang
								{
									//tambah stock 
									$stockPindah = Stock::model()->find('lokasipenyimpananbarangid = '.$_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangtujuanid'].' and barangid = '.$_POST['Hygienispemindahanbarang']['barangid']);
									$stockPindah->jumlah = $stockPindah->jumlah+$jumlahTotal;
									$stockPindah->save();
								} 								
							}
							
							$transaction ->commit();
							
							echo CJSON::encode(array
									(
										'result'=>'OK',
										'pemindahanid'=>$model->getPrimaryKey()
									));
							Yii::app ()->user->setFlash ( 'success', "Data Pemindahan Barang Berhasil Ditambah." );
							Yii::app()->end();
						}
						catch (Exception $error) 
						{
							$transaction ->rollback();
							throw $error;
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
            ), false, true );
            
            Yii::app()->end();
        }
 
        public function actionUpdate($id)
        {
            $modelPemindahan = Hygienispemindahanbarang::model()->findByPk($id);
            $modelPemindahanDetail = Pemindahanbarangdetail::model()->findAll('pemindahanbarangid='.$modelPemindahan->id.' and isdeleted=false');           

            $lokasipenyimpananbarangtujuanidLama = $modelPemindahan->lokasipenyimpananbarangtujuanid;
            $modelPemindahan->tanggal = date("m/d/Y", strtotime($modelPemindahan->tanggal));
            
            $this->performAjaxValidationUpdate($modelPemindahan);
            
            if(isset($_POST['Hygienispemindahanbarang']))
            {
                $modelPemindahan->attributes=$_POST['Hygienispemindahanbarang'];
                $valid = $modelPemindahan->validate();

                if($valid)
                {
                    // set nilai variable
                    $createddate = $_POST['waktuinsert'];
                    $updatedate = $_POST['waktuupdate'];
                    
                    // set data pemindahan barang
                    $_POST['Hygienispemindahanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Hygienispemindahanbarang']['tanggal']);
                    $_POST['Hygienispemindahanbarang']['updateddate'] = $updatedate;
                    $jumlahTotal = 0;
                    for($i=0;$i<count($_POST['jumlah']);$i++)
                    {
                        $jumlahTotal += $_POST['jumlah'][$i];
                    }
                    $_POST['Hygienispemindahanbarang']['jumlah'] = $jumlahTotal;
                    
                    $modelPemindahan->attributes=$_POST['Hygienispemindahanbarang'];
                    $transaction = Yii::app()->db->beginTransaction();
						try{
							
							if($modelPemindahan->save())
							{
								// update tabel pemindahanbarangdetail
								
								// jika lokasi tidak berubah
								if($lokasipenyimpananbarangtujuanidLama==$_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangtujuanid'])
								{
									$i=0;
									$r = Pemindahanbarangdetail::model()->findAll('pemindahanbarangid='.$id.' and isdeleted=false');
									foreach($r as $modelDetail)
									{
										$modelDetail->supplierid = $_POST['supplierid'][$i];
										$modelDetail->updateddate = $updatedate;

										// stok barang asal
										$modelStockSupplier = Stocksupplier::model()->find('lokasipenyimpananbarangid='.$modelPemindahan->lokasipenyinpananbarangid.' and barangid='.$modelPemindahan->barangid.' and supplierid='.$_POST['supplierid'][$i]);
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
										$stockSesudah = Stocksupplier::model()->find('lokasipenyimpananbarangid = '.$_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangtujuanid'].' and barangid = '.$modelPemindahan->barangid.' and supplierid = '.$_POST['supplierid'][$i]);
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
											$stockSesudah->lokasipenyimpananbarangid = $_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangtujuanid'];
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
										$stockTujuan = Stock::model()->find('barangid='.$modelPemindahan->barangid.' AND lokasipenyimpananbarangid='.$_POST['Hygienispemindahanbarang']['lokasipenyimpananbarangtujuanid']);
										$stockTujuan->jumlah = $stockTujuan->jumlah+$jumlahTotal;
										$stockTujuan->save(false);
									}
									//

								}                        
							}
							$transaction ->commit();
							
							echo CJSON::encode(array
									(
										'result'=>'OK'
									));
							Yii::app ()->user->setFlash ( 'success', "Data Pemindahan Barang Berhasil Diupdate.");
							Yii::app()->end();
							exit;
						}
						catch (Exception $error) 
						{
							$transaction ->rollback();
							throw $error;
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
            $modelPemindahan = Hygienispemindahanbarang::model()->findByPk($id);
            $modelDetail = Pemindahanbarangdetail::model()->findAll('pemindahanbarangid='.$id);
            $modelBongkarmuat = Bongkarmuat::model()->findAll('pemindahanbarangid='.$modelPemindahan->id.' and isdeleted=false');
            
            $this->render('view',array(
                    'modelPemindahan'=>$modelPemindahan,
                    'modelDetail'=>$modelDetail,
                    'modelBongkarmuat'=>$modelBongkarmuat,
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
                $rec = Karyawan::model()->with('absensi')->findAll("jabatanid=11 and tanggal::text like '".$tanggal."%' and jammasuk is not null"); // jabatan pegawai bongkar muat & absen di hari transaksi
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
                
                $jml = Karyawan::model()->with('absensi')->count("jabatanid=11 and tanggal::text like '".$tanggal."%' and jammasuk is not null");
                
                echo $jml;
            }
            else
                echo 0;
        }
        
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='hygienispemindahanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        protected function performAjaxValidationUpdate($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='hygienispemindahanbarang-form')
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
                
                $penjualan = Hygienispemindahanbarang::model()->findByPk($id);
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