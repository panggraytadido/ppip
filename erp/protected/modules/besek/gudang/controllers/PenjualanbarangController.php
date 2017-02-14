<?php

class PenjualanbarangController extends Controller
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
            $model=new Gudangpenjualanbarang('search');
            $model->unsetAttributes();
            if(isset($_GET['Gudangpenjualanbarang']))
                    $model->attributes=$_GET['Gudangpenjualanbarang'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionCreate()
        {
            $modelPenjualan = new Gudangpenjualanbarang;
            $modelBongkarmuat = new Bongkarmuat;

            $this->performAjaxValidation($modelPenjualan, $modelBongkarmuat);

            if(isset($_POST['Gudangpenjualanbarang'],$_POST['Bongkarmuat']))
            {
                $model=new Gudangpenjualanbarang;
                $_POST['Gudangpenjualanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                $_POST['Gudangpenjualanbarang']['statuspenjualan'] = 0;
                $model->attributes=$_POST['Gudangpenjualanbarang'];
                $valid = $model->validate();

                if( ($valid && is_numeric($_POST['Bongkarmuat']['upah'])) && (isset($_POST['karyawanid']) && $_POST['Bongkarmuat']['jumlahkaryawan']==count($_POST['karyawanid'])))
                {
                    $createddate = date("Y-m-d H:", time());
                    
                    $_POST['Gudangpenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenjualanbarang']['tanggal']);
                    $_POST['Gudangpenjualanbarang']['createddate'] = $createddate;
                    $_POST['Gudangpenjualanbarang']['userid'] = Yii::app()->user->id;

                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Gudangpenjualanbarang']['labarugi'] = $_POST['Gudangpenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Gudangpenjualanbarang']['jumlah']);

                    $model->attributes=$_POST['Gudangpenjualanbarang'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
						$stocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid'])->jumlah;
						if($_POST['Gudangpenjualanbarang']['jumlah']>$stocksupplier)
						{
							echo CJSON::encode(array
											(
												'result'=>'FALSE',
												'stock'=>$stocksupplier,
												'jumlahpenjualan'=>$_POST['Gudangpenjualanbarang']['jumlah']
												
											));
																				
							Yii::app()->end();
						}
						else
						{							
							$transaction = Yii::app()->db->beginTransaction();
							try{
								
								if($model->save())
								{
									if($_POST['Bongkarmuat']['jumlahkaryawan']!='0') // jika input jumlah karyawan, insert data bongkar muat 
									{
										$karyawanid = $_POST['karyawanid'];

										for($i=0;$i<count($karyawanid);$i++)
										{
											$modelBongkar = new Bongkarmuat;
											$modelBongkar->karyawanid = $_POST['karyawanid'][$i];
											$modelBongkar->upah = $_POST['Bongkarmuat']['upah'];
											$modelBongkar->tanggal = $_POST['Gudangpenjualanbarang']['tanggal'];
											$modelBongkar->createddate = $createddate;
											$modelBongkar->userid = Yii::app()->user->id;
											$modelBongkar->penjualanbarangid = $model->getPrimaryKey();
											$modelBongkar->divisiid = Yii::app()->session['divisiid'];
											$modelBongkar->save();
										}
									}

									$modelStockout = new Stockout;
									$modelStockout->barangid = $_POST['Gudangpenjualanbarang']['barangid'];
									$modelStockout->jumlah = $_POST['Gudangpenjualanbarang']['jumlah'];
									$modelStockout->tanggal = $_POST['Gudangpenjualanbarang']['tanggal'];
									$modelStockout->createddate = $createddate;
									$modelStockout->userid = Yii::app()->user->id;
									$modelStockout->penjualanbarangid = $model->getPrimaryKey();
									$modelStockout->save();
								
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
            $modelPenjualan = Gudangpenjualanbarang::model()->findByPk($id);
            $modelBongkarmuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$modelPenjualan->id.' and isdeleted=false');

            $modelPenjualan->tanggal = date("m/d/Y", strtotime($modelPenjualan->tanggal));
            $_POST['Gudangpenjualanbarang']['statuspenjualan'] = ($modelPenjualan->statuspenjualan==false) ? 0 : 1;
            
            $this->performAjaxValidationUpdate($modelPenjualan);
            
            if(isset($_POST['Gudangpenjualanbarang'],$_POST['Bongkarmuat']))
            {
                $modelPenjualan->attributes=$_POST['Gudangpenjualanbarang'];
                $valid = $modelPenjualan->validate();

                if( ($valid && is_numeric($_POST['Bongkarmuat']['upah'])) && (isset($_POST['karyawanid'])))
                {
                    // set nilai variable
                    $barangidLama = $modelPenjualan->barangid;
                    $jumlahLama = $modelPenjualan->jumlah;
                    $createddate = $_POST['waktuinsert'];
                    $updatedate = $_POST['waktuupdate'];
                    
                    // set data penjualan barang
                    $_POST['Gudangpenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenjualanbarang']['tanggal']);
                    $_POST['Gudangpenjualanbarang']['updateddate'] = $updatedate;
                    
                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Gudangpenjualanbarang']['labarugi'] = $_POST['Gudangpenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Gudangpenjualanbarang']['jumlah']);
                    
                    $modelPenjualan->attributes=$_POST['Gudangpenjualanbarang'];
					
                    $transaction = Yii::app()->db->beginTransaction();
					try{
						
						if($modelPenjualan->save())
						{
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

								/*
								foreach($modelBongkarmuat as $r) // delete/update karyawan lama
								{
									array_push($karyawanLama,$r->karyawanid);

									if (!in_array($r->karyawanid, $karyawanBaru)) // jika tidak ada di input baru, delete
									{
										$deleteBongkarmuat = Bongkarmuat::model()->find('penjualanbarangid='.$modelPenjualan->id.' and karyawanid='.$r->karyawanid);
										$deleteBongkarmuat->isdeleted = 1;
										$deleteBongkarmuat->save();
									}
									else // jika masih ada di input baru, update
									{
										// update upah, tanggal, divisiid, updatedate
										$updateBongkarmuat = Bongkarmuat::model()->find('penjualanbarangid='.$modelPenjualan->id.' and karyawanid='.$r->karyawanid);
										$updateBongkarmuat->upah = $_POST['Bongkarmuat']['upah'];
										$updateBongkarmuat->tanggal = $_POST['Gudangpenjualanbarang']['tanggal'];
										$updateBongkarmuat->updateddate = $updatedate;
										$updateBongkarmuat->save();
									}
								}

								for($i=0;$i<count($karyawanBaru);$i++) // insert karyawan baru
								{
									if (!in_array($karyawanBaru[$i], $karyawanLama))
									{
										$dataKaryawan = array(
											'karyawanid' => $karyawanBaru[$i],
											'penjualanbarangid' => $modelPenjualan->id,
											'upah' => $_POST['Bongkarmuat']['upah'],
											'tanggal' => $_POST['Gudangpenjualanbarang']['tanggal'],
											'createddate' => $createddate,
											'userid' => Yii::app()->user->id,
											'divisiid' => Yii::app()->session['divisiid'],
											'isdeleted' => 0,
										);

										$cekExistKaryawan = $modelPenjualan->cekExistKaryawan($dataKaryawan); // cek exist karyawan (menghindari double input)

										if($cekExistKaryawan==0)
										{
											$modelBongkar = new Bongkarmuat;
											$modelBongkar->karyawanid = $karyawanBaru[$i];
											$modelBongkar->penjualanbarangid = $modelPenjualan->id;
											$modelBongkar->upah = $_POST['Bongkarmuat']['upah'];
											$modelBongkar->tanggal = $_POST['Gudangpenjualanbarang']['tanggal'];
											$modelBongkar->createddate = $createddate;
											$modelBongkar->userid = Yii::app()->user->id;
											$modelBongkar->divisiid = Yii::app()->session['divisiid'];
											$modelBongkar->save();
										}
									}
								}
								*/
							}

							$modelStockout = Stockout::model()->find('penjualanbarangid='.$modelPenjualan->id);
							$modelStockout->barangid = $_POST['Gudangpenjualanbarang']['barangid'];
							$modelStockout->jumlah = $_POST['Gudangpenjualanbarang']['jumlah'];
							$modelStockout->tanggal = $_POST['Gudangpenjualanbarang']['tanggal'];
							$modelStockout->updatedate = $updatedate;
							$modelStockout->save();

	//                          PROSES INI DILAKUKAN DI DIVISI KASIR
	//                            if($barangidLama==$_POST['Gudangpenjualanbarang']['barangid']) // jika barangid tidak berubah
	//                            {
	//                                $modelStock = Stock::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid']);
	//                                
	//                                if($updatedate!=$modelStock->updatedate)
	//                                {
	//                                    $modelStock->jumlah = ($modelStock->jumlah + $jumlahLama) - $_POST['Gudangpenjualanbarang']['jumlah'];
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
	//                                $modelStock2 = Stock::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid']);
	//                                if($updatedate!=$modelStock2->updatedate)
	//                                {
	//                                    $modelStock2->jumlah = ($modelStock2->jumlah - $_POST['Gudangpenjualanbarang']['jumlah']);
	//                                    $modelStock2->updatedate = $updatedate;
	//                                    $modelStock2->save();
	//                                }
	//                            }                      
						}
						
						$transaction ->commit();
						 echo CJSON::encode(array
                                (
                                    'result'=>'OK'
                                ));
                        Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Diupdate.");
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
            $modelPenjualan = Gudangpenjualanbarang::model()->findByPk($id);
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
            $recAntrian = Penjualanbarang::model()->find($criteria);
            //$stockAntrian = ($recAntrian->jumlah!=null) ? $recAntrian->jumlah : 0;
            $stockGudang = Stocksupplier::model()->find('barangid='.$barangid.' and lokasipenyimpananbarangid='.$lokasiid.' and supplierid='.$supplierid)->jumlah;
            //$stockTersedia = ($stockGudang - $stockAntrian);
			$stockTersedia = $stockGudang;
            // =========================================
            
            echo CJSON::encode(array(
                    'stockTersedia' => $stockTersedia,
                    'stockGudang' => $stockGudang,
                   // 'stockAntrian' => $stockAntrian,
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
					
					$penjualan = Gudangpenjualanbarang::model()->findByPk($id);
					$penjualan->isdeleted = true;
					$penjualan->save();

					// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
					if(!isset($_GET['ajax']))
							$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
				}
				else
					throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
        
        public function actionSendToKasir($id)
        {
            if(Yii::app()->request->isPostRequest)
            {
                $penjualan  = Gudangpenjualanbarang::model()->findByPk($id);
                $penjualan->issendtokasir=TRUE;
                if($penjualan->save())
                {
                        Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Dikirim ke Kasir.");
                        echo CJSON::encode(array('result'=>'OK'));                               
                        Yii::app()->end();
                   
                }
            }
        }
}