<?php

class PenerimaanbarangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Gudang - Penerimaan Barang';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Gudangpenerimaanbarang('search');
            $model->unsetAttributes();
            if(isset($_GET['Gudangpenerimaanbarang']))
                    $model->attributes=$_GET['Gudangpenerimaanbarang'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
 
        public function actionCreate()
        {
            $modelPenerimaan = new Gudangpenerimaanbarang;
           
            
            $this->performAjaxValidation($modelPenerimaan);
            
            if(isset($_POST['Gudangpenerimaanbarang']))
            {
                $model=new Gudangpenerimaanbarang;
                
                $createddate = date("Y-m-d H:", time());
                
                $_POST['Gudangpenerimaanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                
                $model->attributes=$_POST['Gudangpenerimaanbarang'];
                $valid = $model->validate();
                
                if( $valid)
                {
                    $_POST['Gudangpenerimaanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenerimaanbarang']['tanggal']);
                    $_POST['Gudangpenerimaanbarang']['createddate'] = $createddate;
                    $_POST['Gudangpenerimaanbarang']['userid'] = Yii::app()->user->id;
                    $_POST['Gudangpenerimaanbarang']['isdeleted'] = 0;
                    
                    $model->attributes=$_POST['Gudangpenerimaanbarang'];
                
                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
						$transaction = Yii::app()->db->beginTransaction();
						try{							
							if($model->save())
							{
										$supplierid = $_POST['Gudangpenerimaanbarang']['supplierid'];
										$tanggal = date("Y-m-d", strtotime($model->tanggal));																			

										$modelStockin = new Stockin;
										$modelStockin->barangid = $_POST['Gudangpenerimaanbarang']['barangid'];
										$modelStockin->jumlah = $_POST['Gudangpenerimaanbarang']['jumlah'];
										$modelStockin->tanggal = $_POST['Gudangpenerimaanbarang']['tanggal'];
										$modelStockin->createddate = $createddate;
										$modelStockin->userid = Yii::app()->user->id;
										$modelStockin->penerimaanbarangid = $model->getPrimaryKey();
										$modelStockin->save();

										$modelStock = Stock::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
										$modelStock->jumlah = $modelStock->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah'];
										$modelStock->updateddate = $createddate;
										$modelStock->save();

										$modelStocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenerimaanbarang']['supplierid'].' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
										$modelStocksupplier->jumlah = $modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah'];
										$modelStocksupplier->updateddate = $createddate;
										
										
										
										if($modelStocksupplier->save())
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

												//table transfer
												$barang = Gudangpenerimaanbarang::model()->findByPk($model->id);
												$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Gudangpenerimaanbarang']['supplierid'])->hargamodal;
												
												$transfer = new Transfer;
												$transfer->jenistransferid=1;
												$transfer->debit=$barang->jumlah*$hargaBarang;
												$transfer->supplierid=$_POST['Gudangpenerimaanbarang']['supplierid'];
												$transfer->penerimaanbarangid=$model->id;
												$transfer->saldo=$saldoTerakhir+($barang->jumlah*$hargaBarang);
												$transfer->tanggal=$barang->tanggal;
												$transfer->createddate=$barang->tanggal;
												$transfer->userid=Yii::app()->user->id;
												$transfer->save();
												//
										}                            								
							}						
							
							$transaction ->commit();	
							
							echo CJSON::encode(array
											(
												'result'=>'OK',
												'penerimaanid'=>$model->getPrimaryKey()
											));
							Yii::app ()->user->setFlash ( 'success', "Data Penerimaan Barang Berhasil Ditambah." );
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
                                        'penerimaanid'=>$idnya
                                    ));
                            Yii::app ()->user->setFlash ( 'success', "Data Penerimaan Barang Berhasil Ditambah." );
                            Yii::app()->end();
                    }
              
                        $error = CActiveForm::validate($model);
                        if($error!='[]')
                        {
                            echo $error;
                        }                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }
            
            $this->layout='';
            $this->renderPartial('_form',array(
                    'modelPenerimaan'=>$modelPenerimaan,                   
            ), false, true );

            Yii::app()->end();
        }
 
        public function actionUpdate($id)
        {
            $modelPenerimaan = Gudangpenerimaanbarang::model()->findByPk($id);            

            $modelPenerimaan->tanggal = date("m/d/Y", strtotime($modelPenerimaan->tanggal));

            $this->performAjaxValidationUpdate($modelPenerimaan);
						

            if(isset($_POST['Gudangpenerimaanbarang']))
            {
                // set nilai variable
                $barangidLama = $modelPenerimaan->barangid;
                $lokasiLama = $modelPenerimaan->lokasipenyimpananbarangid;
                $supplierLama = $modelPenerimaan->supplierid;
                $jumlahLama = $modelPenerimaan->jumlah;
                $createddate = date("Y-m-d H:i:s", time());
                $updatedate = date("Y-m-d H:i:s", time());

                // set data penerimaan barang
                $_POST['Gudangpenerimaanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenerimaanbarang']['tanggal']);
                $_POST['Gudangpenerimaanbarang']['updateddate'] = $updatedate;
                $modelPenerimaan->attributes=$_POST['Gudangpenerimaanbarang'];
                $valid = $modelPenerimaan->validate();

                if($valid)
                {
					$transaction = Yii::app()->db->beginTransaction();
					try{
						if($modelPenerimaan->save())
						{							
							$modelStockin = Stockin::model()->find('penerimaanbarangid='.$modelPenerimaan->id);
							$modelStockin->barangid = $_POST['Gudangpenerimaanbarang']['barangid'];
							$modelStockin->jumlah = $_POST['Gudangpenerimaanbarang']['jumlah'];
							$modelStockin->tanggal = $_POST['Gudangpenerimaanbarang']['tanggal'];
							if($modelStockin->save())
							{									
									$transfer = Transfer::model()->find('penerimaanbarangid='.$id);	
									if(count($transfer)>0)
									{
										$tanggal = date("Y-m-d", strtotime($modelPenerimaan->tanggal));	
										$supplierid = $_POST['Gudangpenerimaanbarang']['supplierid'];
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

											//table transfer
											$barang = Gudangpenerimaanbarang::model()->findByPk($modelPenerimaan->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Gudangpenerimaanbarang']['supplierid'])->hargamodal;
											
											$transfer = new Transfer;
											$transfer->jenistransferid=1;
											$transfer->debit=$barang->jumlah*$hargaBarang;
											$transfer->supplierid=$_POST['Gudangpenerimaanbarang']['supplierid'];
											$transfer->penerimaanbarangid=$modelPenerimaan->id;
											$transfer->saldo=$saldoTerakhir+($barang->jumlah*$hargaBarang);
											$transfer->tanggal=$barang->tanggal;
											$transfer->createddate=$barang->tanggal;
											$transfer->userid=Yii::app()->user->id;
											$transfer->save();
											//
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
											
											//table transfer
											$barang = Gudangpenerimaanbarang::model()->findByPk($modelPenerimaan->id);
											$hargaBarang = Hargabarang::model()->find("barangid=".$barang->barangid." AND supplierid=".$_POST['Gudangpenerimaanbarang']['supplierid'])->hargamodal;
											
											//update transfer		
											$transfer = new Transfer;							
											$transfer->debit=$barang->jumlah*$hargaBarang;
											$transfer->jenistransferid=1;
											$transfer->supplierid=$_POST['Gudangpenerimaanbarang']['supplierid'];							
											$transfer->saldo=$saldoTerakhir+($barang->jumlah*$hargaBarang);
											$transfer->penerimaanbarangid=$modelPenerimaan->id;	
											$transfer->tanggal=$modelPenerimaan->tanggal;
											$transfer->createddate=$modelPenerimaan->tanggal;
											$transfer->updateddate=$updatedate;
											$transfer->userid=Yii::app()->user->id;
											$transfer->save();
											//
										}
									}															
							}															
							

							// UPDATE TRANSAKSI.STOCKSUPPLIER
							// S | S | S 
							if($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama==$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid, supplier dan lokasi tidak berubah
							{
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama) + $_POST['Gudangpenerimaanbarang']['jumlah'];
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();																			
							}
							// S | ? | ? 
							elseif($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama==$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama!=$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid,supplier sama dan lokasi berubah
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							elseif($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama!=$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid sama, supplier berubah, lokasi sama 
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$_POST['Gudangpenerimaanbarang']['supplierid'].' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
							
							}
							elseif($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama!=$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama!=$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid sama dan supplier, lokasi berubah
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$_POST['Gudangpenerimaanbarang']['supplierid'].' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							
							// ? | S | ? 
							elseif($barangidLama!=$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama==$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid beda, supplier sama dan lokasi sama 
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							elseif($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama==$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama!=$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid sama, supplier sama, lokasi beda 
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							elseif($barangidLama!=$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama==$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama!=$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid beda dan supplier sama lokasi beda 
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							
							// ? | ? | S 
							elseif($barangidLama!=$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama==$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid beda, supplier sama, lokasi sama
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							elseif($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama!=$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid sama, supplier beda, lokasi sama
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$_POST['Gudangpenerimaanbarang']['supplierid'].' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							elseif($barangidLama!=$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama!=$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid beda, supplier beda, lokasi sama
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenerimaanbarang']['supplierid'].' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							
							// B | B | B 
							elseif($barangidLama!=$_POST['Gudangpenerimaanbarang']['barangid'] && $supplierLama!=$_POST['Gudangpenerimaanbarang']['supplierid'] && $lokasiLama!=$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid beda, supplier beda, lokasi beda
							{
								// update stock barang lama
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$barangidLama.' and supplierid='.$supplierLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah - $jumlahLama);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();

								// update stock barang baru
								$modelStocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenerimaanbarang']['supplierid'].' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStocksupplier->jumlah = ($modelStocksupplier->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStocksupplier->updateddate = $updatedate;
								$modelStocksupplier->userid = Yii::app()->user->id;
								$modelStocksupplier->save();
								
							}
							// ============================
							
							// UPDATE TRANSAKSI.STOCK
							if($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid dan lokasi tidak berubah
							{
								$modelStock = Stock::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStock->jumlah = ($modelStock->jumlah - $jumlahLama) + $_POST['Gudangpenerimaanbarang']['jumlah'];
								$modelStock->updateddate = $updatedate;
								$modelStock->userid = Yii::app()->user->id;
								$modelStock->save();
							}
							elseif($barangidLama==$_POST['Gudangpenerimaanbarang']['barangid'] && $lokasiLama!=$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid sama dan lokasi berubah
							{
								// update stock barang lama
								$modelStock = Stock::model()->find('barangid='.$barangidLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStock->jumlah = ($modelStock->jumlah - $jumlahLama);
								$modelStock->updateddate = $updatedate;
								$modelStock->userid = Yii::app()->user->id;
								$modelStock->save();

								// update stock barang baru
								$modelStock = Stock::model()->find('barangid='.$barangidLama.' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStock->jumlah = ($modelStock->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStock->updateddate = $updatedate;
								$modelStock->userid = Yii::app()->user->id;
								$modelStock->save();
							}
							elseif($barangidLama!=$_POST['Gudangpenerimaanbarang']['barangid'] && $lokasiLama==$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid berubah dan lokasi sama
							{
								// update stock barang lama
								$modelStock = Stock::model()->find('barangid='.$barangidLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStock->jumlah = ($modelStock->jumlah - $jumlahLama);
								$modelStock->updateddate = $updatedate;
								$modelStock->userid = Yii::app()->user->id;
								$modelStock->save();

								// update stock barang baru
								$modelStock = Stock::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStock->jumlah = ($modelStock->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStock->updateddate = $updatedate;
								$modelStock->userid = Yii::app()->user->id;
								$modelStock->save();
							}
							elseif($barangidLama!=$_POST['Gudangpenerimaanbarang']['barangid'] && $lokasiLama!=$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']) // jika barangid dan lokasi berubah
							{
								// update stock barang lama
								$modelStock = Stock::model()->find('barangid='.$barangidLama.' and lokasipenyimpananbarangid='.$lokasiLama);
								$modelStock->jumlah = ($modelStock->jumlah - $jumlahLama);
								$modelStock->updateddate = $updatedate;
								$modelStock->userid = Yii::app()->user->id;
								$modelStock->save();

								// update stock barang baru
								$modelStock = Stock::model()->find('barangid='.$_POST['Gudangpenerimaanbarang']['barangid'].' and lokasipenyimpananbarangid='.$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
								$modelStock->jumlah = ($modelStock->jumlah + $_POST['Gudangpenerimaanbarang']['jumlah']);
								$modelStock->updateddate = $updatedate;
								$modelStock->userid = Yii::app()->user->id;
								$modelStock->save();
							}																	
						}						
						$transaction ->commit();
						echo CJSON::encode(array
									(
										'result'=>'OK'
									));
							Yii::app ()->user->setFlash ( 'success', "Data Penerimaan Barang Berhasil Diupdate.");
							Yii::app()->end();
					}
					catch (Exception $error) {
						$transaction ->rollback();
						throw $error;
					} 	                    
                }
                else
                {
                        $error = CActiveForm::validate($modelPenerimaan);
                        if($error!='[]')
                        {
                            echo $error;
                        }
                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }
                
            $this->layout='';
            $this->renderPartial( '_form_update', array (
                            'modelPenerimaan' => $modelPenerimaan,                            
            ), false, true );
            Yii::app()->end();
	}
	
		public function actionDeleteOneKaryawan()
		{
			if($_POST['karyawanid']!="" && $_POST['penerimaanbarangid']!="")
			{
				$bongkarMuat = Bongkarmuat::model()->find('karyawanid='.intval($_POST['karyawanid']).' AND penerimaanbarangid='.intval($_POST['penerimaanbarangid']));
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
 
        public function actionView($id)
        {
            $modelPenerimaan = Gudangpenerimaanbarang::model()->findByPk($id);
            $modelBongkarmuat = Bongkarmuat::model()->findAll('penerimaanbarangid='.$modelPenerimaan->id.' and isdeleted=false');
            
            $this->render('view',array(
                    'modelPenerimaan'=>$modelPenerimaan,
                    'modelBongkarmuat'=>$modelBongkarmuat,
            ));
        }
        
        public function actionGetSupplierBarang()
        {
            $barangid = $_POST['barangid'];
            
            if($barangid!='')
            {
                $criteria=new CDbCriteria;
                $criteria->distinct = 't.id';
                $criteria->select = 't.id, t.namaperusahaan';
                $criteria->join = 'inner join transaksi.stocksupplier h on t.id=h.supplierid';
                $criteria->condition = 't.status = 1 and barangid = '.$barangid.' ';

                $rec = Supplier::model()->findAll($criteria);

                $data=CHtml::listData($rec,'id','namaperusahaan');
                $cmbSupplier = "<option value=''>-- Pilih Supplier --</option>";
                foreach($data as $value=>$namaperusahaan)
                $cmbSupplier .= CHtml::tag('option', array('value'=>$value),CHtml::encode($namaperusahaan),true);

                $cmbLokasi = CHtml::tag('option', array('value'=>''),CHtml::encode('-- Pilih Lokasi --'),true);

                echo CJSON::encode(array(
                  'cmbSupplier'=>$cmbSupplier,
                  'cmbLokasi'=>$cmbLokasi
                ));
            }
            else
            {
                echo CJSON::encode(array(
                  'cmbSupplier'=>CHtml::tag('option', array('value'=>''),CHtml::encode('-- Pilih Barang Dahulu --'),true),
                  'cmbLokasi'=>CHtml::tag('option', array('value'=>''),CHtml::encode('-- Pilih Supplier Dahulu --'),true)
                ));
            }
        }
        
        public function actionGetlokasibarang()
        {
            $barangid = $_POST['barangid'];
            $supplierid = $_POST['supplierid'];
            
            if($barangid!='' && $supplierid!='')
            {
                $criteria=new CDbCriteria;
                $criteria->select = 't.id, t.nama';
                $criteria->join = 'left join transaksi.stocksupplier s on t.id=s.lokasipenyimpananbarangid';
                $criteria->condition = 't.isdeleted = false and s.supplierid = '.$supplierid.' and barangid = '.$barangid.' and s.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $rec = Lokasipenyimpananbarang::model()->findAll($criteria);

                $data=CHtml::listData($rec,'id','nama');
                echo "<option value=''>-- Pilih Lokasi --</option>";
                foreach($data as $value=>$nama)
                echo CHtml::tag('option', array('value'=>$value),CHtml::encode($nama),true);
            }
            else
                echo CHtml::tag('option', array('value'=>''),CHtml::encode('-- Pilih Supplier Dahulu --'),true);
        }
 
        public function actionGetkaryawanabsen()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                $option = '';
                $jml = 0;
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
				
				
				/*
                foreach($rec as $r)
                {
					$option[] = array(
						$r->id,
						$r->nama
					);	
                    //$option .= '<option value="'.$r->id.'">'.$r->nama;
                    //$jml++;
                }
                */
                echo CJSON::encode(
					$rec
                );	
				
            }
            else
            {
                echo CJSON::encode(array(
                  'option'=>'',
                  //'jml'=>0
                ));
            }
        }
        
        public function actionGetjumlahabsen()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                $jml = Karyawan::model()->count("jabatanid=11");
                
                echo $jml;
            }
            else
                echo 0;
        }
        
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenerimaanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        protected function performAjaxValidationUpdate($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenerimaanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        public function actionDelete($id)
		{
				if(Yii::app()->request->isPostRequest)
				{
					$Bongkarmuat = Bongkarmuat::model()->findAll('penerimaanbarangid='.$id);
					foreach($Bongkarmuat as $recBongkarmuat)
					{
						$data = Bongkarmuat::model()->findByPk($recBongkarmuat->id);
						$data->isdeleted = false;
						$data->save();
					}
					
					$penerimaan = Penerimaanbarang::model()->findByPk($id);
					$penerimaan->isdeleted = 1;
					$penerimaan->save();

					// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
					if(!isset($_GET['ajax']))
							$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
				}
				else
					throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
}