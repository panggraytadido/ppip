<?php

class PenjualanbarangkesupplierController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Besek - Penjualan Barang ke Supplier';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {            
            $model=new Besekpenjualanbarangkesupplier('search');
            $model->unsetAttributes();
            if(isset($_GET['Besekpenjualanbarangkesupplier']))
                $model->attributes=$_GET['Besekpenjualanbarangkesupplier'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionCreate()
        {
            $modelPenjualan = new Besekpenjualanbarangkesupplier;

            $this->performAjaxValidation($modelPenjualan);

            if(isset($_POST['Besekpenjualanbarangkesupplier']))
            {
                $model=new Besekpenjualanbarangkesupplier;
                $_POST['Besekpenjualanbarangkesupplier']['divisiid'] = Yii::app()->session['divisiid'];
                $_POST['Besekpenjualanbarangkesupplier']['statuspenjualan'] = 0;
                
                $model->attributes=$_POST['Besekpenjualanbarangkesupplier'];
                
                $valid = $model->validate();

                if($valid)
                {
                    $createddate = date("Y-m-d H:", time());
                    
                    $_POST['Besekpenjualanbarangkesupplier']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Besekpenjualanbarangkesupplier']['tanggal']);
                    $_POST['Besekpenjualanbarangkesupplier']['createddate'] = $createddate;
                    $_POST['Besekpenjualanbarangkesupplier']['userid'] = Yii::app()->user->id;

                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Besekpenjualanbarangkesupplier']['barangid'].' and supplierid='.$_POST['Besekpenjualanbarangkesupplier']['supplierid'])->hargamodal;
                    $_POST['Besekpenjualanbarangkesupplier']['labarugi'] = $_POST['Besekpenjualanbarangkesupplier']['hargatotal'] - ($hargamodal * $_POST['Besekpenjualanbarangkesupplier']['jumlah']);
                    
                    $_POST['Besekpenjualanbarangkesupplier']['netto'] = $_POST['Besekpenjualanbarangkesupplier']['jumlah'];
//                    $_POST['Besekpenjualanbarangkesupplier']['jumlah'] = 0;

					$model->issendtokasir='true';
					$model->box=0;
                    $model->attributes=$_POST['Besekpenjualanbarangkesupplier'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
						$stocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Besekpenjualanbarangkesupplier']['barangid'].' and lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'].' and supplierid='.$_POST['Besekpenjualanbarangkesupplier']['supplierid'])->jumlah;
						if($_POST['Besekpenjualanbarangkesupplier']['jumlah']>$stocksupplier)
						{
							echo CJSON::encode(array
											(
												'result'=>'FALSE',
												'stock'=>$stocksupplier,
												'jumlahpenjualan'=>$_POST['Besekpenjualanbarangkesupplier']['jumlah']
												
											));
																				
							Yii::app()->end();
						}
						else
						{
							if($model->save())
							{
								$transaction = Yii::app()->db->beginTransaction();
								try{
								
									$modelStockout = new Stockout;
									$modelStockout->barangid = $_POST['Besekpenjualanbarangkesupplier']['barangid'];
									$modelStockout->jumlah = $_POST['Besekpenjualanbarangkesupplier']['jumlah'];
									$modelStockout->tanggal = $_POST['Besekpenjualanbarangkesupplier']['tanggal'];
									$modelStockout->createddate = $createddate;
									$modelStockout->userid = Yii::app()->user->id;
									$modelStockout->penjualanbarangid = $model->getPrimaryKey();
									
									$tanggal = date("Y-m-d", strtotime($model->tanggal));	
									$supplierid = $_POST['Besekpenjualanbarangkesupplier']['supplierpembeliid']; //krna supplier pembeli
									
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
												if($saldo!="")
												{
													$saldoTerakhir = $saldo;
												}   													
											}		
											else
											{
												$saldoTerakhir = 0;
											}	

											$barang = Besekpenjualanbarangkesupplier::model()->findByPk($model->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Besekpenjualanbarangkesupplier']['supplierid'])->hargagrosir;
											$transfer = new Transfer;
											$transfer->jenistransferid=2;
											$transfer->kredit=$_POST['Besekpenjualanbarangkesupplier']['hargatotal'];
											$transfer->penjualanbarangkesupplierid=$model->id;
											$transfer->supplierid=$barang->supplierpembeliid; // krna yg dicatat adalah supllier pembelinya
											$transfer->saldo=$saldoTerakhir-($_POST['Besekpenjualanbarangkesupplier']['hargatotal']);
											$transfer->tanggal=$barang->tanggal;
											$transfer->createddate=$barang->tanggal;
											$transfer->userid=Yii::app()->user->id;
											$transfer->save();
									}
									
									$transaction ->commit();
								}	
								catch (Exception $error) 
								{
									$transaction ->rollback();
									throw $error;
								} 							                                                      

								echo CJSON::encode(array
										(
											'result'=>'OK',
											'penjualanid'=>$model->getPrimaryKey()
										));
								Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Ke Supplier Berhasil Ditambah." );
								Yii::app()->end();
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
                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }

            $this->layout='a';
            $this->render('_form',array(
                    'modelPenjualan'=>$modelPenjualan
            ), false, true );
            
            Yii::app()->end();
        }
 
        public function actionUpdate($id)
        {
            $modelPenjualan = Besekpenjualanbarangkesupplier::model()->findByPk($id);

            $modelPenjualan->tanggal = date("m/d/Y", strtotime($modelPenjualan->tanggal));
            
            $this->performAjaxValidation($modelPenjualan);
            
            if(isset($_POST['Besekpenjualanbarangkesupplier']))
            {
                $_POST['Besekpenjualanbarangkesupplier']['statuspenjualan'] = ($modelPenjualan->statuspenjualan==false) ? 0 : 1;
            
                $modelPenjualan->attributes=$_POST['Besekpenjualanbarangkesupplier'];
                $valid = $modelPenjualan->validate();

                if($valid)
                {
                    // set nilai variable
                    $barangidLama = $modelPenjualan->barangid;
                    $jumlahLama = $modelPenjualan->jumlah;
                    $createddate = $_POST['waktuinsert'];
                    $updatedate = $_POST['waktuupdate'];
                    
                    // set data penjualan barang
                    $_POST['Besekpenjualanbarangkesupplier']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Besekpenjualanbarangkesupplier']['tanggal']);
                    $_POST['Besekpenjualanbarangkesupplier']['updateddate'] = $updatedate;
                    
                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Besekpenjualanbarangkesupplier']['barangid'].' and supplierid='.$_POST['Besekpenjualanbarangkesupplier']['supplierid'])->hargamodal;
                    $_POST['Besekpenjualanbarangkesupplier']['labarugi'] = $_POST['Besekpenjualanbarangkesupplier']['hargatotal'] - ($hargamodal * $_POST['Besekpenjualanbarangkesupplier']['jumlah']);
                    
                    $_POST['Besekpenjualanbarangkesupplier']['netto'] = $_POST['Besekpenjualanbarangkesupplier']['jumlah'];
                    
                    $modelPenjualan->attributes=$_POST['Besekpenjualanbarangkesupplier'];
                    
                    if($modelPenjualan->save())
                    {
						$transaction = Yii::app()->db->beginTransaction();
						try{
						
							$tanggal = date("Y-m-d", strtotime($modelPenjualan->tanggal));	
							$supplierid = $_POST['Besekpenjualanbarangkesupplier']['supplierpembeliid']; //krna supplier pembeli
						
							$modelStockout = Stockout::model()->find('penjualanbarangid='.$modelPenjualan->id);
							$modelStockout->barangid = $_POST['Besekpenjualanbarangkesupplier']['barangid'];
							$modelStockout->jumlah = $_POST['Besekpenjualanbarangkesupplier']['jumlah'];
							$modelStockout->tanggal = $_POST['Besekpenjualanbarangkesupplier']['tanggal'];
							$modelStockout->updatedate = $updatedate;
							$modelStockout->save();
							
							
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
												if($saldo!="")
												{
													$saldoTerakhir = $saldo;
												}   													
											}		
											else
											{
												$saldoTerakhir = 0;
											}	

											$barang = Besekpenjualanbarangkesupplier::model()->findByPk($modelPenjualan->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Besekpenjualanbarangkesupplier']['supplierid'])->hargagrosir;
											$transfer = new Transfer;
											$transfer->jenistransferid=2;
											$transfer->kredit=$_POST['Besekpenjualanbarangkesupplier']['hargatotal'];
											$transfer->penjualanbarangkesupplierid=$modelPenjualan->id;
											$transfer->supplierid=$barang->supplierpembeliid; // krna yg dicatat adalah supllier pembelinya
											$transfer->saldo=$saldoTerakhir-($_POST['Besekpenjualanbarangkesupplier']['hargatotal']);
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
												if($saldo!="")
												{
													$saldoTerakhir = $saldo;
												}   													
											}		
											else
											{
												$saldoTerakhir = 0;
											}	

											$barang = Besekpenjualanbarangkesupplier::model()->findByPk($modelPenjualan->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Besekpenjualanbarangkesupplier']['supplierid'])->hargagrosir;
											$transfer = new Transfer;
											$transfer->jenistransferid=2;
											$transfer->kredit=$_POST['Besekpenjualanbarangkesupplier']['hargatotal'];
											$transfer->penjualanbarangkesupplierid=$modelPenjualan->id;
											$transfer->supplierid=$barang->supplierpembeliid; // krna yg dicatat adalah supllier pembelinya
											$transfer->saldo=$saldoTerakhir-($_POST['Besekpenjualanbarangkesupplier']['hargatotal']);
											$transfer->tanggal=$barang->tanggal;
											$transfer->createddate=$barang->tanggal;
											$transfer->userid=Yii::app()->user->id;
											$transfer->save();
									}		
							}																			
							$transaction ->commit();
						}	
						catch (Exception $error) 
						{
							$transaction ->rollback();
							throw $error;
						} 	

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
                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }
            
            $this->layout='a';
            $this->render( '_form_update', array (
                            'modelPenjualan' => $modelPenjualan
            ), false, true );
            Yii::app()->end();
	}
 
        public function actionView($id)
        {
            $modelPenjualan = Besekpenjualanbarangkesupplier::model()->findByPk($id);
            
            $this->render('view',array(
                    'modelPenjualan'=>$modelPenjualan,
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
            //$stockAntrian = ($recAntrian->jumlah!=null) ? $recAntrian->jumlah : 0;
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
            $criteria->distinct = 't.id';
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
        
        protected function performAjaxValidation($model)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='besekpenjualanbarang-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
        
        public function actionDelete($id)
		{
				if(Yii::app()->request->isPostRequest)
				{					
					$penjualan = Besekpenjualanbarangkesupplier::model()->findByPk($id);
					$penjualan->isdeleted = true;
					$penjualan->save();
					
					$transfer = Transfer::model()->find('penjualanbarangkesupplierid='.$id);					
					$transfer->isdeleted=true;
					$transfer->save();
					

					// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
					if(!isset($_GET['ajax']))
							$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
				}
				else
					throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
}