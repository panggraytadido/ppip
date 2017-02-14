<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



class DatasetoranController extends Controller {
    
    //put your code here
    public $pageTitle = 'Kasir - Data Setoran';
    
    public function filters()
    {
            return array();

    }            
    
    public function actionIndex()
    {            
            $model=new Datasetoran('search');
            $model->unsetAttributes();
            
            if(isset($_GET['Datasetoran']))
                    $model->attributes=$_GET['Datasetoran'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
    }
    
    public function actionRedirectToIndex()
    {
        Yii::app ()->user->setFlash ( 'success', "Data Setoran Berhasil Ditambah." );
        $this->redirect(array('index'));
    }
               
    
    public function actionChildDataSetoran()
    {
           $id = Yii::app()->getRequest()->getParam('id');
           $pelangganid = substr($id, 10,4);           
           $tanggal = substr($id, 0,10);          
           
           $child = Datasetoran::model()->listDetail($pelangganid,$tanggal);	
          
            // partially rendering "_relational" view
            $this->renderPartial('detail', array(
                            'id' => Yii::app()->getRequest()->getParam('id'),
                            'child' => $child,
            ));
    }
        
    public function actionSetor($id)
    {       			
		
        if (Yii::app ()->request->isAjaxRequest) {

				//echo Yii::app()->DateConvert->ConvertTanggal($_POST['tanggalsetoran']);
				//print("<pre>".print_r($_POST,true)."</pre>"); 
				//die;
                if($_POST['bayar']!='' AND $_POST['jenispembayaran']!='' AND $_POST['tanggalsetoran']!='')
                {
                    $pelangganid = substr($id, 10,4);       
                    $kodePelanggan = Pelanggan::model()->findByPk($pelangganid)->kode;
                    $tanggal = substr($id, 0,10);
                    $noFaktur = $kodePelanggan."/".$tanggal;                          

                    $totalHarga = Faktur::model()->find("cast(tanggal as date)='$tanggal' AND pelangganid=".$pelangganid);                                           
                    $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' AND pelangganid=".$pelangganid);

                    if(count($cekFaktur)==0)
                    {
                        $transaction = Yii::app()->db->beginTransaction();
                        try {
                                $faktur = new Faktur;
                                $faktur->nofaktur=$noFaktur;
                                $faktur->createddate=date("Y-m-d H:i:s", time());
                                $faktur->userid=Yii::app()->user->id;
                                $faktur->hargatotal=$totalHarga;
                                $faktur->bayar=$_POST['bayar'];
                                $faktur->sisa=$totalHarga-$_POST['bayar'];
                                $faktur->lokasipenyimpananbarangid=Yii::app()->session['lokasiid'];
                                $faktur->jenispembayaran='kredit';
                                if($faktur->save())
                                {                                      
                                    //select data barang untuk input ke table fakturpenjualangbarang
                                    $dataPenjualan= Datasetoran::model()->findAll("cast(tanggal as date)='$tanggal' AND pelangganid=$pelangganid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
                                    for($i=0;$i<count($dataPenjualan);$i++)
                                    {
                                        $penjualanBarang = new Fakturpenjualanbarang;
                                        $penjualanBarang->penjualanbarangid=$dataPenjualan[$i]['id'];
                                        $penjualanBarang->fakturid=$faktur->id;
                                        $penjualanBarang->save();                     
                                    }        
                                    
                                                                      
                                    //input ke table setoran
                                    $setoran = new Setoran;
                                    $setoran->fakturid=$faktur->id;
                                    $setoran->tanggalsetoran=date("Y-m-d H:", time());
                                    $setoran->jumlah=$_POST['bayar'];;
                                    $setoran->jenisbayar='cash';
                                    $setoran->createddate =date("Y-m-d H:", time());
                                    $setoran->userid = Yii::app()->user->id;                               
                                    if($setoran->save())
                                    {
                                        $totalSetoran = Datasetoran::model()->getTotalSetoran($pelangganid,$tanggal); //jika sudah lunas
                                        if($totalSetoran==$totalHarga)
                                        {
                                            //update data faktur
                                            $faktur->sisa=$faktur->sisa-$_POST['bayar'];
                                            $faktur->bayar=$faktur->bayar+$_POST['bayar'];
                                            if($faktur->save())
                                            {
                                                Datasetoran::model()->updateStatusPenjualan($pelangganid,$tanggal);  //update status penjualan
                                            } 
                                            
                                            $transaction ->commit();
                                            echo CJSON::encode(array('result'=>'LUNAS','pelangganid'=>$pelangganid,'tanggal'=>$tanggal,'pembelianke'=>1)); 

                                        }
                                        else 
                                        {
                                            $transaction ->commit();
                                            echo CJSON::encode(array('result'=>'OK'));  
                                        }        
                                    }
                                }
                                
                                
                            }    
                        catch (Exception $error) {
                                     $transaction ->rollback();
                                     throw $error;
                            }	                                    
                    }
					//setoran
                    else 
                    {                                                
                        $transaction = Yii::app()->db->beginTransaction();
                        try {
							
								//update date
								$faktur = Faktur::model()->find("tanggal='$tanggal' AND pelangganid=".$pelangganid);                      
								$faktur->updateddate=date("Y-m-d H:i:s", time());
								$faktur->save();

								
								//cash
								if($_POST['jenispembayaran']==1)
								{									
									$setoran = new Setoran;
									$setoran->fakturid=$faktur->id;
									$setoran->tanggalsetoran=Yii::app()->DateConvert->ConvertTanggal($_POST['tanggalsetoran']);
									$setoran->jumlah=$_POST['bayar'];
									$setoran->jenisbayar='setoran';									
									$setoran->createddate =date("Y-m-d H:", time());
									$setoran->userid = Yii::app()->user->id;                                                   
									if($setoran->save())
									{										
										$totalSetoran = Datasetoran::model()->getTotalSetoran($pelangganid,$tanggal);
										if($totalSetoran==$totalHarga)
										{
												//update data faktur
												$faktur->sisa=$faktur->sisa-$_POST['bayar'];
												$faktur->bayar=$faktur->bayar+$_POST['bayar'];
												if($faktur->save())
												{
													Datasetoran::model()->updateStatusPenjualan($pelangganid,$tanggal);  //update status penjualan
												}                                        

												echo CJSON::encode(array('result'=>'LUNAS','pelangganid'=>$pelangganid,'tanggal'=>$tanggal,'pembelianke'=>1)); 
										}
										else 
										{
											//update data faktur
											if($faktur->sisa!=0)
											{
												$faktur->sisa = $faktur->hargatotal-($faktur->bayar+$_POST['bayar']);
											}
											else
											{
												$faktur->sisa=$faktur->hargatotal-$_POST['bayar'];
											}                                
											$faktur->bayar=$faktur->bayar+$_POST['bayar'];
											if($faktur->save())
											{
												echo CJSON::encode(array('result'=>'OK'));  
											}                                
										}                    
									}
								}
								//Transfer
								if($_POST['jenispembayaran']==2)
								{
												
										$connection=Yii::app()->db;
										$rekeningid =$_POST['rekeningid'];
										$sql ="select max(id) as id from transaksi.transfer where rekeningid=".intval($_POST['rekeningid']);           
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

										$model = new Transfer;
										$model->jenistransferid=5; //transfer masuk
										$model->rekeningid=$_POST['rekeningid'];
										$model->pelangganid=$pelangganid;
										$model->kredit=$_POST['bayar'];                
										$model->saldo=$_POST['bayar']+$saldoTerakhir;
										$model->tanggal=date("Y-m-d H:", time());
										$model->createddate =date("Y-m-d H:", time());
										$model->userid = Yii::app()->user->id;	
										if($model->save())
										{
											$setoran = new Setoran;
											$setoran->fakturid=$faktur->id;
											$setoran->tanggalsetoran=date("Y-m-d H:", time());
											$setoran->jumlah=$_POST['bayar'];
											$setoran->jenisbayar='transfer';									
											$setoran->createddate =date("Y-m-d H:", time());
											$setoran->userid = Yii::app()->user->id;                                                   
											if($setoran->save())
											{
												$totalSetoran = Datasetoran::model()->getTotalSetoran($pelangganid,$tanggal);
												if($totalSetoran==$totalHarga)
												{
														//update data faktur
														$faktur->sisa=$faktur->sisa-$_POST['bayar'];
														$faktur->bayar=$faktur->bayar+$_POST['bayar'];
														if($faktur->save())
														{
															Datasetoran::model()->updateStatusPenjualan($pelangganid,$tanggal);  //update status penjualan
														}                                        

														echo CJSON::encode(array('result'=>'LUNAS','pelangganid'=>$pelangganid,'tanggal'=>$tanggal,'pembelianke'=>1)); 
												}
												else 
												{
													//update data faktur
													if($faktur->sisa!=0)
													{
														$faktur->sisa = $faktur->hargatotal-($faktur->bayar+$_POST['bayar']);
													}
													else
													{
														$faktur->sisa=$faktur->hargatotal-$_POST['bayar'];
													}                                
													$faktur->bayar=$faktur->bayar+$_POST['bayar'];
													if($faktur->save())
													{
														echo CJSON::encode(array('result'=>'OK'));  
													}                                
												} 
											}
										}
										
								}									
                            $transaction ->commit();
                        } 
                        catch (Exception $error) {
                            $transaction ->rollback();
                                     throw $error;
                        }                                              
                    }
                }
                else 
                {
                    echo CJSON::encode(array('result'=>'FAIL'));  
                }
        }
       
    }
    
    public function actionCheckDataSetoran()
    {
        if (Yii::app ()->request->isAjaxRequest) {
            
            $criteria=new CDbCriteria;
            $criteria->select = "jumlah,tanggalsetoran,jenisbayar";
            $faktur= $_POST['faktur'];
            $criteria->condition = "nofaktur = '$faktur'";
            $criteria->join = 'inner join transaksi.faktur f on f.id=t.fakturid';
            $data = Setoran::model()->findAll($criteria);            
            
            $this->layout = 'a';                     
                    $this->render ( 'check_data_setoran', array (
                                    'data'=>$data,                                  
                    ), false, true );
                    Yii::app()->end();
        }
    }
    
    public function actionCetak($id)
    {        
        if (Yii::app ()->request->isAjaxRequest) {
            
                    $pelangganid = substr($id, 10,4);       
                    $kodePelanggan = Pelanggan::model()->findByPk($pelangganid)->kode;
                    $tanggal = substr($id, 0,10);
                    $noFaktur = $kodePelanggan."/".$tanggal;                          

                    $totalHarga = Datasetoran::model()->getTotalBelanja($pelangganid,$tanggal);                                               
                    $cekFaktur = Faktur::model()->find("nofaktur='$noFaktur'");

                    if($cekFaktur=='')
                    {
                        $faktur = new Faktur;
                        $faktur->nofaktur=$noFaktur;
                        $faktur->createddate=date("Y-m-d H:i:s", time());
                        $faktur->userid=Yii::app()->user->id;
                        $faktur->hargatotal=$totalHarga;                        
                        $faktur->sisa=$totalHarga;                        
                        $faktur->bayar=0;
                        $faktur->lokasipenyimpananbarangid=Yii::app()->session['lokasiid'];
                        $faktur->jenispembayaran='kredit';
                        if($faktur->save())
                        {                                      
                            //select data barang untuk input ke table fakturpenjualangbarang
                            $dataPenjualan= Datasetoran::model()->findAll("cast(tanggal as date)='$tanggal' AND pelangganid=$pelangganid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
                            for($i=0;$i<count($dataPenjualan);$i++)
                            {
                                $penjualanBarang = new Fakturpenjualanbarang;
                                $penjualanBarang->penjualanbarangid=$dataPenjualan[$i]['id'];
                                $penjualanBarang->fakturid=$faktur->id;
                                $penjualanBarang->save();                     
                            }        
                            
                            echo CJSON::encode(array('pelangganid'=>$pelangganid,'tanggal'=>$tanggal));  
                            
                        }
                    }
                    else 
                    {
                        echo CJSON::encode(array('pelangganid'=>$pelangganid,'tanggal'=>$tanggal));  
                    }
        }                
    }
    
    //check apakah masuk ke form faktur atw cetak faktur
    public function actionCheckFormFaktur($id)
    {            
        $pelangganid = substr($id, 10,4);       
        $kodePelanggan = Pelanggan::model()->findByPk($pelangganid)->kode;
        $tanggal = substr($id, 0,10);        
        
        $cekFaktur = Faktur::model()->findAll("pelangganid=".$pelangganid." AND cast(tanggal as date)='$tanggal'");
        
        if(count($cekFaktur)!=0)
        {
            
            echo CJSON::encode(array('status'=>'true', 'pelangganid'=>$pelangganid,'tanggal'=>$tanggal,'pembelianke'=>1));              
        }
        else
        {
            $transaction = Yii::app()->db->beginTransaction();
            try {
                
                    $pembelianke=1;
                    $data = Datasetoran::model()->detailFormFaktur($pelangganid,$tanggal);
                    $faktur = new Faktur;
                    $faktur->nofaktur=Pelanggan::model()->findByPk($pelangganid)->kode."".$this->generateRandomFaktur();
                    $faktur->pelangganid=$pelangganid;
                    $faktur->pembelianke=1;
                    $faktur->createddate=date("Y-m-d H:i:s", time());
                    $faktur->userid=Yii::app()->user->id;
                    $faktur->tanggal=$tanggal;
                    $faktur->lokasipenyimpananbarangid=Yii::app()->session['lokasiid'];
                    $faktur->jenispembayaran='kredit';
                    if($faktur->save()) // save faktur
                    {
                        $dataPenjualan= Datasetoran::model()->findAll("cast(tanggal as date)='$tanggal' AND statuspenjualan=false AND pelangganid=$pelangganid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
                        for($i=0;$i<count($dataPenjualan);$i++)
                        {
                            //faktur penjualan
                            $fakturpenjualan = new Fakturpenjualanbarang;
                            $fakturpenjualan->penjualanbarangid=$dataPenjualan[$i]['id'];
                            $fakturpenjualan->fakturid=$faktur->id;
                            $fakturpenjualan->save(false);
                            //
                        }
                    }
                    $transaction ->commit();
                    echo CJSON::encode(array('status'=>'false', 'pelangganid'=>$pelangganid,'tanggal'=>$tanggal,'pembelianke'=>1));                
            } 
            catch (Exception $error) {
                $transaction ->rollback();
                        throw $error;
            }
            
        }        
    }
    
    //form faktur
    public function actionFormFaktur($pelangganid,$tanggal,$pembelianke)
    {
        $data = Datasetoran::model()->detailFormFaktur($pelangganid,$tanggal);
        $this->render('form_faktur',array(
               'data'=>$data,
               'pelangganid'=>$pelangganid,
               'tanggal'=>$tanggal,
                'pembelianke'=>$pembelianke,
       ));
    }
    public function generateRandomFaktur($digits = 4){
            $i = 0; //counter
            $pin = ""; //our default pin is blank.
            while($i < $digits){
                //generate a random number between 0 and 9.
                $pin .= mt_rand(0, 9);
                $i++;
            }
            return $pin;
    }
    
    
    
     //simpan form faktur
    public function actionSimpan()
    {       
        if (Yii::app ()->request->isAjaxRequest) {
            $tanggal = $_POST['tanggal'];
            $pelangganid = $_POST['pelangganid'];
            $pembelianke = $_POST['pembelianke'];
            
            if($tanggal!='' && $pelangganid!='' && $pembelianke!='')
            {
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    $faktur = Faktur::model()->find("pelangganid=".intval($pelangganid)." and cast(tanggal as date)='$tanggal' and pembelianke=".$pembelianke);            
                    $faktur->hargatotal=$_POST['hargatotalinput'];
                    $faktur->bayar=$_POST['bayar'];
                    $faktur->sisa=$_POST['hargatotalinput'] - ($_POST['bayar']-$_POST['totaldiskoninput']);
                    $faktur->diskon=$_POST['totaldiskoninput'];
                    $faktur->lokasipenyimpananbarangid=Yii::app()->session['lokasiid'];
                    $faktur->jenispembayaran='kredit';
                    if($faktur->save()) // save faktur
                    {
                        //select data berdasarkan penjualan barang 
                        for($i=0;$i<count($_POST['penjualanbarangid']);$i++)
                        {
                            //klw ada diskon
                            if($_POST['diskoninput'][$i]!=0)
                            {
                                $model = Inbox::model()->findByPk($_POST['penjualanbarangid'][$i]);                
                                $model->diskon =  intval($_POST['diskoninput'][$i]);
                                $model->save(false);               
                            }
                            //                                                            
                        }

                         //select data barang untuk diupdate stocknya
                        $dataPenjualan= Inbox::model()->findAll("cast(tanggal as date)='$tanggal' AND pelangganid=$pelangganid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']." AND statuspenjualan=false");
                        for($i=0;$i<count($dataPenjualan);$i++)
                        {
                            //kurangi stock barang keseluruhan                 
                            $stock = Stock::model()->find("barangid=".$dataPenjualan[$i]["barangid"]." AND lokasipenyimpananbarangid=".$dataPenjualan[$i]["lokasipenyimpananbarangid"]);       
                            $stock->jumlah=$stock->jumlah-$dataPenjualan[$i]["jumlah"];
                            if($stock->save())
                            {
                                //stock supplier
                                $stockSupplier = Stocksupplier::model()->find("barangid=".$dataPenjualan[$i]["barangid"]." AND lokasipenyimpananbarangid=".$dataPenjualan[$i]["lokasipenyimpananbarangid"]." AND supplierid=".$dataPenjualan[$i]["supplierid"]); 
                                $stockSupplier->jumlah = $stockSupplier->jumlah-$dataPenjualan[$i]["jumlah"];
                                $stockSupplier->save();
                                //end 
                            }
                            // end stock keseuluruhan
                        }                                
                        //end

                        $transaction ->commit();
                        //redirect ke print faktur
                        echo CJSON::encode(array('result'=>'OK','pelangganid'=>$_POST['pelangganid'],'tanggal'=>$_POST['tanggal'],'pembelianke'=>$pembelianke));                  
                    }
                    
                } catch (Exception $error) {
                        $transaction ->rollback();
                        throw $error;
                }                
                
            }
                        
        }                                                                               
    }
    
    public function actionPrintFaktur()
    {        
        $this->renderPartial('cetak_faktur', array(                           
            ));
        
    }
    
    public function actionPopupSetor($id)
    {
		
        if (Yii::app ()->request->isAjaxRequest) {
            
                    $pelangganid = substr($id, 10,4);       
                    $kodePelanggan = Pelanggan::model()->findByPk($pelangganid)->kode;
                    $tanggal = substr($id, 0,10);
                    $noFaktur = $kodePelanggan."/".$tanggal;            
                    
                    //$totalHarga = Datasetoran::model()->getTotalBelanja($pelangganid,$tanggal);       
                    //$totalSetoran = Datasetoran::model()->getTotalSetoran($pelangganid,$tanggal);
                    
                    $faktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' AND pelangganid=".$pelangganid." AND jenispembayaran='kredit'");                                                     
                    $cekFaktur = Faktur::model()->find("tanggal='$tanggal' AND  pelangganid=".$pelangganid);
                    //print("<pre>".print_r($cekFaktur,true)."</pre>");                       
                    
                    if(count($cekFaktur)!=0)
                    {
                        
                        $diskon = Faktur::model()->find("tanggal='$tanggal' AND  pelangganid=".$pelangganid." AND jenispembayaran='kredit'")->diskon;
                        $model = new Datasetoran;
            
                        $this->layout = 'a';                     
                        $this->render ( 'form_setoran', array (
                                        'noFaktur'=>$noFaktur,
                                        'model'=>$model,
                                        'totalHarga'=>$faktur->hargatotal,
                                        'totalSetoran'=>$faktur->bayar,
                                        'sisa'=>$faktur->sisa,
                                        'tanggal'=>$tanggal,
                                        'pelangganid'=>$pelangganid,
                                        'id'=>$id,
                                        'diskon'=>$faktur->diskon,
                        ), false, true );
                        Yii::app()->end();
                    }
                    else
                    {
                        echo 'Cetak Faktur Terlebih Dahulu';
                    }
                                                           
		}
    }
	
	public function actionGetNorek()
	{
		if (Yii::app ()->request->isAjaxRequest) {                
			$model = Rekening::model()->findByPk(intval( $_POST['rekeningid']));
			echo CJSON::encode(array
			(
				'result'=>'OK',
				'data'=>$model
			));
		}
	}
		
		public function actionGetSaldoRek()
		{
			if (Yii::app ()->request->isAjaxRequest) {
				$connection=Yii::app()->db;
				$sql ="select max(id) as id from transaksi.transfer where rekeningid=".intval($_POST['rekeningid']);           
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
				
				echo CJSON::encode(array
				(					
					'saldo'=>$saldoTerakhir
				));
			}	
		}
   
    protected function performAjaxValidation($model1, $model2)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenjualanbarang-form')
        {
            echo CActiveForm::validate(array($model1, $model2));
            Yii::app()->end();
        }
    }
         
}
