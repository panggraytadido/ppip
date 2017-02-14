<?php

class PenjualanbarangkesupplierController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Gudang - Penjualan Barang ke Supplier';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Gudangpenjualanbarangkesupplier('search');
            $model->unsetAttributes();
            if(isset($_GET['Gudangpenjualanbarangkesupplier']))
                    $model->attributes=$_GET['Gudangpenjualanbarangkesupplier'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionCreate()
        {
            $modelPenjualan = new Gudangpenjualanbarangkesupplier;
            $modelBongkarmuat = new Bongkarmuat;

            $this->performAjaxValidation($modelPenjualan, $modelBongkarmuat);

            if(isset($_POST['Gudangpenjualanbarangkesupplier'],$_POST['Bongkarmuat']))
            {
                $model=new Gudangpenjualanbarangkesupplier;
                $_POST['Gudangpenjualanbarangkesupplier']['divisiid'] = Yii::app()->session['divisiid'];
                $_POST['Gudangpenjualanbarangkesupplier']['statuspenjualan'] = 0;
                $model->attributes=$_POST['Gudangpenjualanbarangkesupplier'];
                $valid = $model->validate();

                if( ($valid && is_numeric($_POST['Bongkarmuat']['upah'])) && (isset($_POST['karyawanid']) && $_POST['Bongkarmuat']['jumlahkaryawan']==count($_POST['karyawanid'])))
                {
                    $createddate = date("Y-m-d H:", time());
                    
                    $_POST['Gudangpenjualanbarangkesupplier']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenjualanbarangkesupplier']['tanggal']);
                    $_POST['Gudangpenjualanbarangkesupplier']['createddate'] = $createddate;
                    $_POST['Gudangpenjualanbarangkesupplier']['userid'] = Yii::app()->user->id;

                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarangkesupplier']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarangkesupplier']['supplierid'])->hargamodal;
                    $_POST['Gudangpenjualanbarangkesupplier']['labarugi'] = $_POST['Gudangpenjualanbarangkesupplier']['hargatotal'] - ($hargamodal * $_POST['Gudangpenjualanbarangkesupplier']['jumlah']);

                    $model->attributes=$_POST['Gudangpenjualanbarangkesupplier'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
						$stocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenjualanbarangkesupplier']['barangid'].' and lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'].' and supplierid='.$_POST['Gudangpenjualanbarangkesupplier']['supplierid'])->jumlah;
						if($_POST['Gudangpenjualanbarangkesupplier']['jumlah']>$stocksupplier)
						{
							echo CJSON::encode(array
											(
												'result'=>'FALSE',
												'stock'=>$stocksupplier,
												'jumlahpenjualan'=>$_POST['Gudangpenjualanbarangkesupplier']['jumlah']
												
											));
																				
							Yii::app()->end();
						}		
						else
						{
							$transaction = Yii::app()->db->beginTransaction();
							try{
																						
								if($model->save())
								{
										$supplierid = $_POST['Gudangpenjualanbarangkesupplier']['supplierpembeliid']; //krna supplier pembeli
										$tanggal = date("Y-m-d", strtotime($model->tanggal));
								
										if($_POST['Bongkarmuat']['jumlahkaryawan']!='0') // jika input jumlah karyawan, insert data bongkar muat 
										{
											$karyawanid = $_POST['karyawanid'];

											for($i=0;$i<count($karyawanid);$i++)
											{
												$modelBongkar = new Bongkarmuat;
												$modelBongkar->karyawanid = $_POST['karyawanid'][$i];
												$modelBongkar->upah = $_POST['Bongkarmuat']['upah'];
												$modelBongkar->tanggal = $_POST['Gudangpenjualanbarangkesupplier']['tanggal'];
												$modelBongkar->createddate = $createddate;
												$modelBongkar->userid = Yii::app()->user->id;
												$modelBongkar->penjualanbarangid = $model->getPrimaryKey();
												$modelBongkar->divisiid = Yii::app()->session['divisiid'];
												$modelBongkar->save();
											}
										}

										$modelStockout = new Stockout;
										$modelStockout->barangid = $_POST['Gudangpenjualanbarangkesupplier']['barangid'];
										$modelStockout->jumlah = $_POST['Gudangpenjualanbarangkesupplier']['jumlah'];
										$modelStockout->tanggal = $_POST['Gudangpenjualanbarangkesupplier']['tanggal'];
										$modelStockout->createddate = $createddate;
										$modelStockout->userid = Yii::app()->user->id;
										$modelStockout->penjualanbarangid = $model->getPrimaryKey();
										if($modelStockout->save())
										{
											$connection=Yii::app()->db;
											//tambahan 01-08-2016, masuk ke table transfer
											//saldo
											$sql ="select max(id) as id from transaksi.transfer 
													group by supplierid,isdeleted
														having supplierid=$supplierid and isdeleted=false
															limit 1";           
																
											$data=$connection->createCommand($sql)->queryRow();
											$id = $data["id"];                        
											if($id!="")
											{
												$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
												if($saldo!="" || $saldo!=0)
												{
													$saldoTerakhir = $saldo;
												}
												else 
												{
													$saldoTerakhir = 0;
												}														
											}		
											else
											{
												$saldoTerakhir = 0;
											}			
											

											$barang = Gudangpenjualanbarangkesupplier::model()->findByPk($model->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Gudangpenjualanbarangkesupplier']['supplierid'])->hargagrosir;
											$transfer = new Transfer;
											$transfer->jenistransferid=2;
											$transfer->kredit=$_POST['Gudangpenjualanbarangkesupplier']['hargatotal'];
											$transfer->penjualanbarangkesupplierid=$model->id;
											$transfer->supplierid=$barang->supplierpembeliid; // krna yg dicatat adalah supllier pembelinya
											$transfer->saldo=$saldoTerakhir-($_POST['Gudangpenjualanbarangkesupplier']['hargatotal']);
											$transfer->tanggal=$barang->tanggal;
											$transfer->createddate=$barang->tanggal;
											$transfer->userid=Yii::app()->user->id;
											$transfer->save();
										}
										
										//end tambahan                                      

					//                                PROSES INI DILAKUKAN DI DIVISI KASIR
					//                                $modelStock = Stock::model()->find('barangid='.$_POST['Gudangpenjualanbarangkesupplier']['barangid']);
					//                                $modelStock->jumlah = $modelStock->jumlah - $_POST['Gudangpenjualanbarangkesupplier']['jumlah'];
					//                                $modelStock->updatedate = $createddate;
					//                                $modelStock->userid = Yii::app()->user->id;
					//                                $modelStock->save();                                   
								}
								$transaction ->commit();    
								echo CJSON::encode(array
											   (
												   'result'=>'OK',
												   'penjualanid'=>$model->getPrimaryKey()
											   ));
									   Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Ditambah." );
									   Yii::app()->end();
							} 
							catch (Exception $error) {
								$transaction ->rollback();
								throw $error;
							}	
						}                                                         
                    }
                    else
                    {
                        $idnya = $model->getId();

                        echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                        'penjualanid'=>$idnya
                                    ));
                            Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Ditambah." );
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
                        elseif(!is_numeric($_POST['Bongkarmuat']['upah']))
                        {
                            echo '{"Bongkarmuat_upah":["Pilih Jumlah Karyawan"], "Bongkarmuat_jumlahkaryawan":[""]}';
                        }
                        elseif(!isset($_POST['karyawanid']) || $_POST['Bongkarmuat']['jumlahkaryawan']!=count($_POST['karyawanid']))
                        {
                            echo '{"Bongkarmuat_upah":[""], "Bongkarmuat_jumlahkaryawan":["Tekan Tombol SET"]}';
                        }
                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }

            $this->layout='a';
            $this->render('_form',array(
                    'modelPenjualan'=>$modelPenjualan,
                    'modelBongkarmuat'=>$modelBongkarmuat
            ), false, true );
            
            Yii::app()->end();
        }
 
        public function actionUpdate($id)
        {
            $modelPenjualan = Gudangpenjualanbarangkesupplier::model()->findByPk($id);
            $modelBongkarmuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$modelPenjualan->id.' and isdeleted=false');

            $modelPenjualan->tanggal = date("m/d/Y", strtotime($modelPenjualan->tanggal));
            $_POST['Gudangpenjualanbarangkesupplier']['statuspenjualan'] = ($modelPenjualan->statuspenjualan==false) ? 0 : 1;
            
            $this->performAjaxValidationUpdate($modelPenjualan);
            
            if(isset($_POST['Gudangpenjualanbarangkesupplier'],$_POST['Bongkarmuat']))
            {
                $modelPenjualan->attributes=$_POST['Gudangpenjualanbarangkesupplier'];
                $valid = $modelPenjualan->validate();

                if( ($valid && is_numeric($_POST['Bongkarmuat']['upah'])) && (isset($_POST['karyawanid']) && $_POST['Bongkarmuat']['jumlahkaryawan']==count($_POST['karyawanid'])))
                {
                    // set nilai variable
                    $barangidLama = $modelPenjualan->barangid;
                    $jumlahLama = $modelPenjualan->jumlah;
                    $createddate = $_POST['waktuinsert'];
                    $updatedate = $_POST['waktuupdate'];
                    
                    // set data penjualan barang
                    $_POST['Gudangpenjualanbarangkesupplier']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenjualanbarangkesupplier']['tanggal']);
                    $_POST['Gudangpenjualanbarangkesupplier']['updateddate'] = $updatedate;
                    
                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarangkesupplier']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarangkesupplier']['supplierid'])->hargamodal;
                    $_POST['Gudangpenjualanbarangkesupplier']['labarugi'] = $_POST['Gudangpenjualanbarangkesupplier']['hargatotal'] - ($hargamodal * $_POST['Gudangpenjualanbarangkesupplier']['jumlah']);
                    
                    $modelPenjualan->attributes=$_POST['Gudangpenjualanbarangkesupplier'];
                    
                    if($modelPenjualan->save())
                    {
						$supplierid = $_POST['Gudangpenjualanbarangkesupplier']['supplierpembeliid'];
						$tanggal = date("Y-m-d", strtotime($modelPenjualan->tanggal));
						
                        if($_POST['Bongkarmuat']['jumlahkaryawan']!='0') // jika input jumlah karyawan, insert/update data bongkar muat 
                        {
                            $karyawanBaru = $_POST['karyawanid'];
                            $karyawanLama = array();

							$bngkarMuat = Bongkarmuat::model()->findAll("penjualanbarangid=".$modelPenjualan->id);								
							for($x=0;$x<count($karyawanBaru);$x++)
							{
									if($karyawanBaru[$x]!=isset($bngkarMuat[$x]))
									{
										$model = new Bongkarmuat;
										$model->karyawanid = $karyawanBaru[$x];
										$model->penjualanbarangid = $modelPenjualan->id;
										$model->upah = $_POST['Bongkarmuat']['upah'];
										$model->tanggal = date("Y-m-d H:i:s", time());
										$model->divisiid=Yii::app()->session['divisiid'];
										$model->save();
										
									}										
							}
													
                        }

                        $modelStockout = Stockout::model()->find('penjualanbarangid='.$modelPenjualan->id);
                        $modelStockout->barangid = $_POST['Gudangpenjualanbarangkesupplier']['barangid'];
                        $modelStockout->jumlah = $_POST['Gudangpenjualanbarangkesupplier']['jumlah'];
                        $modelStockout->tanggal = $_POST['Gudangpenjualanbarangkesupplier']['tanggal'];
                        $modelStockout->updatedate = $updatedate;
                        if($modelStockout->save())
						{
							$transfer = Transfer::model()->find('penjualanbarangkesupplierid='.$id);	
							if(count($transfer)>0)
							{
									if($transfer->delete())
									{
											$connection=Yii::app()->db;
											//tambahan 01-08-2016, masuk ke table transfer
											//saldo
											$sql ="select max(id) as id from transaksi.transfer 
													group by supplierid,isdeleted
														having supplierid=$supplierid and isdeleted=false
															limit 1";           
																
											$data=$connection->createCommand($sql)->queryRow();
											$id = $data["id"];                        
											if($id!="")
											{
												$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
												if($saldo!="" || $saldo!=0)
												{
													$saldoTerakhir = $saldo;
												}
												else 
												{
													$saldoTerakhir = 0;
												}														
											}		
											else
											{
												$saldoTerakhir = 0;
											}	

											$barang = Gudangpenjualanbarangkesupplier::model()->findByPk($modelPenjualan->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Gudangpenjualanbarangkesupplier']['supplierid'])->hargagrosir;
											$transfer = new Transfer;
											$transfer->jenistransferid=2;
											$transfer->kredit=$_POST['Gudangpenjualanbarangkesupplier']['hargatotal'];
											$transfer->penjualanbarangkesupplierid=$modelPenjualan->id;
											$transfer->supplierid=$barang->supplierpembeliid; // krna yg dicatat adalah supllier pembelinya
											$transfer->saldo=$saldoTerakhir-($_POST['Gudangpenjualanbarangkesupplier']['hargatotal']);
											$transfer->tanggal=$barang->tanggal;
											$transfer->createddate=$barang->tanggal;
											$transfer->userid=Yii::app()->user->id;
											$transfer->save();
									}
									else
									{
											$connection=Yii::app()->db;
											//tambahan 01-08-2016, masuk ke table transfer
											//saldo
											$sql ="select max(id) as id from transaksi.transfer 
													group by supplierid,isdeleted
														having supplierid=$supplierid and isdeleted=false
															limit 1";           
																
											$data=$connection->createCommand($sql)->queryRow();
											$id = $data["id"];                        
											if($id!="")
											{
												$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
												if($saldo!="" || $saldo!=0)
												{
													$saldoTerakhir = $saldo;
												}
												else 
												{
													$saldoTerakhir = 0;
												}														
											}		
											else
											{
												$saldoTerakhir = 0;
											}	

											$barang = Gudangpenjualanbarangkesupplier::model()->findByPk($modelPenjualan->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Gudangpenjualanbarangkesupplier']['supplierid'])->hargagrosir;
											$transfer = new Transfer;
											$transfer->jenistransferid=2;
											$transfer->kredit=$_POST['Gudangpenjualanbarangkesupplier']['hargatotal'];
											$transfer->penjualanbarangkesupplierid=$modelPenjualan->id;
											$transfer->supplierid=$barang->supplierpembeliid; // krna yg dicatat adalah supllier pembelinya
											$transfer->saldo=$saldoTerakhir-($_POST['Gudangpenjualanbarangkesupplier']['hargatotal']);
											$transfer->tanggal=$barang->tanggal;
											$transfer->createddate=$barang->tanggal;
											$transfer->userid=Yii::app()->user->id;
											$transfer->save();
									}		
							}
						}											
//                          PROSES INI DILAKUKAN DI DIVISI KASIR
//                            if($barangidLama==$_POST['Gudangpenjualanbarangkesupplier']['barangid']) // jika barangid tidak berubah
//                            {
//                                $modelStock = Stock::model()->find('barangid='.$_POST['Gudangpenjualanbarangkesupplier']['barangid']);
//                                
//                                if($updatedate!=$modelStock->updatedate)
//                                {
//                                    $modelStock->jumlah = ($modelStock->jumlah + $jumlahLama) - $_POST['Gudangpenjualanbarangkesupplier']['jumlah'];
//                                    $modelStock->updatedate = $updatedate;
//                                    $modelStock->save();
//                                }
//                            }
//                            else // jika barangid berubah
//                            {
//                                // update stock barang lama
//                                $modelStock = Stock::model()->find('barangid='.$barangidLama);
//                                if($updatedate!=$modelStock->updatedate)
//                                {
//                                    $modelStock->jumlah = ($modelStock->jumlah + $jumlahLama);
//                                    $modelStock->updatedate = $updatedate;
//                                    $modelStock->save();
//                                }
//                                
//                                // update stock barang baru
//                                $modelStock2 = Stock::model()->find('barangid='.$_POST['Gudangpenjualanbarangkesupplier']['barangid']);
//                                if($updatedate!=$modelStock2->updatedate)
//                                {
//                                    $modelStock2->jumlah = ($modelStock2->jumlah - $_POST['Gudangpenjualanbarangkesupplier']['jumlah']);
//                                    $modelStock2->updatedate = $updatedate;
//                                    $modelStock2->save();
//                                }
//                            }

                        echo CJSON::encode(array
                                (
                                    'result'=>'OK'
                                ));
                        Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Diupdate.");
                        Yii::app()->end();
                    }
                }
                else
                {
                        $error = CActiveForm::validate($modelPenjualan);
                        if($error!='[]')
                        {
                            echo $error;
                        }
                        elseif(!is_numeric($_POST['Bongkarmuat']['upah']))
                        {
                            echo '{"Bongkarmuat_upah":["Tambah Data Karyawan"], "Bongkarmuat_jumlahkaryawan":[""]}';
                        }
                        elseif(!isset($_POST['karyawanid']) || $_POST['Bongkarmuat']['jumlahkaryawan']!=count($_POST['karyawanid']))
                        {
                            echo '{"Bongkarmuat_upah":[""], "Bongkarmuat_jumlahkaryawan":["Tekan Tombol SET"]}';
                        }
                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }
            
            $this->layout='a';
            $this->render( '_form_update', array (
                            'modelPenjualan' => $modelPenjualan,
                            'modelBongkarmuat' => $modelBongkarmuat
            ), false, true );
            Yii::app()->end();
	}
 
        public function actionView($id)
        {
            $modelPenjualan = Gudangpenjualanbarangkesupplier::model()->findByPk($id);
            $modelBongkarmuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$modelPenjualan->id.' and isdeleted=false');
            
            $this->render('view',array(
                    'modelPenjualan'=>$modelPenjualan,
                    'modelBongkarmuat'=>$modelBongkarmuat,
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
            $recAntrian = Penjualanbarangkesupplier::model()->find($criteria);
            $stockAntrian = ($recAntrian->jumlah!=null) ? $recAntrian->jumlah : 0;
            $stockGudang = Stocksupplier::model()->find('barangid='.$barangid.' and lokasipenyimpananbarangid='.$lokasiid.' and supplierid='.$supplierid)->jumlah;
            $stockTersedia = $stockGudang;
            // =========================================
            
            echo CJSON::encode(array(
                    'stockTersedia' => $stockTersedia,
                    'stockGudang' => $stockGudang,
                    //'stockAntrian' => $stockAntrian,
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
            $criteria->select = 't.id, t.namaperusahaan';
            $criteria->join = 'inner join transaksi.hargabarang h on t.id=h.supplierid';
            $criteria->condition = 't.status = 1 and barangid = '.$barangid.' ';
            
            $rec = Supplier::model()->findAll($criteria);
            
            $i=0;
            $id = array();
            $nama = array();
            foreach($rec as $r)
            {
                $id[$i] = $r->id;
                $nama[$i] = $r->namaperusahaan;
                $i++;
            }
            
            echo CJSON::encode(array(
                    'id' => $id,
                    'text' => $nama,
                ));
        }
		
		public function actionDeleteOneKaryawan()
		{
			if($_POST['karyawanid']!="" && $_POST['penjualanbarangid']!="")
			{
				$bongkarMuat = Bongkarmuat::model()->find('karyawanid='.intval($_POST['karyawanid']).' AND penjualanbarangid='.intval($_POST['penjualanbarangid']));
				if(count($bongkarMuat)>0)
				{
					if($bongkarMuat->delete())
					{
						echo CJSON::encode(array(
						  'result'=>'OK'						 
						));
					}
				}
			}
		}
		
		public function actionGetkaryawanabsenupdate()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                $option = '';
                $jml = 0;               
				$criteria=new CDbCriteria;
				$criteria->select = 'id,nama';
				$criteria->condition='jabatanid=11';
				$rec = Karyawan::model()->findAll($criteria); // jabatan pegawai bongkar muat & absen di hari transaksi
				//print("<pre>".print_r($rec,true)."</pre>");	
						
                echo CJSON::encode(
					$rec
                );	
				
            }
            else
            {
                echo CJSON::encode(array(
                  'option'=>'',
                  'jml'=>0
                ));
            }
        }
        
        public function actionGetkaryawanabsen()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                $option = '';
                $jml = 0;
                //$rec = Karyawan::model()->with('absensi')->findAll("jabatanid=11 and tanggal::text like '".$tanggal."%' and jammasuk is not null"); // jabatan pegawai bongkar muat & absen di hari transaksi
				$rec = Karyawan::model()->with('absensi')->findAll("jabatanid=11"); // jabatan pegawai bongkar muat & absen di hari transaksi
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
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenjualanbarang-form')
            {
                echo CActiveForm::validate(array($model1, $model2));
                Yii::app()->end();
            }
        }
        
        protected function performAjaxValidationUpdate($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenjualanbarang-form')
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
                    $data->isdeleted = true;
                    $data->save();
                }
                
                $penjualan = Gudangpenjualanbarangkesupplier::model()->findByPk($id);
                $penjualan->isdeleted = true;
                $penjualan->save();

                // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
                if(!isset($_GET['ajax']))
                        $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
            }
            else
                throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}
}