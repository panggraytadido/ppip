<?php

class InboxController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Inbox - Kasir';
    
		public function actionIndex()
		{
				
				$model=new Inbox('search');
				$model->unsetAttributes();
				
				if(isset($_GET['Inbox']))
						$model->attributes=$_GET['Inbox'];
				
				$this->render('index',array(
						'model'=>$model,
				));
		}
        
        
        public function actionChildInbox()
        {
           $id = Yii::app()->getRequest()->getParam('id');
           $pelangganid = substr($id, 10,4);           
           $tanggal = substr($id, 0,10);          
           
           $child = Inbox::model()->listDetailInbox($pelangganid,$tanggal);	            
            $this->renderPartial('detail', array(
                            'id' => Yii::app()->getRequest()->getParam('id'),
                            'child' => $child,
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

        
        public function actionFormFaktur($pelangganid,$tanggal)
        {   
	
			//die;
            //$pelangganid = substr($id, 10,4);           
            //$tanggal = substr($id, 0,10);
            
            $cekFaktur = Faktur::model()->findAll("pelangganid=".intval($pelangganid)." and cast(tanggal as date)='$tanggal'");                       
            if(count($cekFaktur)!=0)
            {                                 
                for($i=0;$i<count($cekFaktur);$i++)
                {
                   $data[]= $cekFaktur[$i]['pembelianke'];
                }
                $pembelianke = max($data)+1;
                                
                $cekFakturRefresh = Faktur::model()->findAll("pelangganid=".intval($pelangganid)." and cast(tanggal as date)='$tanggal' and pembelianke=".$pembelianke);             
                //print("<pre>".print_r($cekFakturRefresh,true)."</pre>");	
                                
                if(count($cekFakturRefresh)!=0)
                {                                   
                    $this->redirect(array('index'));                   
                }
                else
                {                                       
                    $transaction = Yii::app()->db->beginTransaction();
                    try{
                        
                        $data = Inbox::model()->detailFormFaktur($pelangganid,$tanggal);                    
                        $faktur = new Faktur;
                        $faktur->nofaktur=Pelanggan::model()->findByPk($pelangganid)->kode."".$this->generateRandomFaktur();
                        $faktur->pelangganid=$pelangganid;
                        $faktur->pembelianke=$pembelianke;
                        $faktur->createddate=date("Y-m-d H:i:s", time());
                        $faktur->userid=Yii::app()->user->id;
                        $faktur->tanggal=$tanggal;           
						$faktur->jenispembayaran='tunai';
                        $faktur->lokasipenyimpananbarangid=Yii::app()->session['lokasiid'];                        
                        if($faktur->save()) // save faktur
                        {
                            $dataPenjualan= Inbox::model()->findAll("cast(tanggal as date)='$tanggal' AND issendtokasir=true AND statuspenjualan=false AND pelangganid=$pelangganid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
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
                    } 
                    catch (Exception $error) {
                        $transaction ->rollback();
                        throw $error;
                    }                                        
                }                                                   
            }
            //pertama kali membeli pada hari dan tgl yg sama
            else                
            {
                $transaction = Yii::app()->db->beginTransaction();
                try{
                    
                    $pembelianke=1;
                    $data = Inbox::model()->detailFormFaktur($pelangganid,$tanggal);
                    $faktur = new Faktur;
                    $faktur->nofaktur=Pelanggan::model()->findByPk($pelangganid)->kode."".$this->generateRandomFaktur();
                    $faktur->pelangganid=$pelangganid;
                    $faktur->pembelianke=1;
                    $faktur->createddate=date("Y-m-d H:i:s", time());
                    $faktur->userid=Yii::app()->user->id;
                    $faktur->tanggal=$tanggal;
					$faktur->jenispembayaran='tunai';
                    $faktur->lokasipenyimpananbarangid=Yii::app()->session['lokasiid'];
                    if($faktur->save()) // save faktur
                    {
                        $dataPenjualan= Inbox::model()->findAll("cast(tanggal as date)='$tanggal' AND statuspenjualan=false AND pelangganid=$pelangganid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
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
                } 
                catch (Exception $error) {
                        $transaction ->rollback();
                        throw $error;
                }
                                
            }
                        
             $this->render('form_faktur',array(
                    'data'=>$data,
                    'pelangganid'=>$pelangganid,
                    'tanggal'=>$tanggal,
                    'pembelianke'=>$pembelianke,
            ));
        }
        
        //simpan form faktur
        public function actionSimpan()
        {                        
            $tanggal = $_POST['tanggal'];
            $pelangganid = $_POST['pelangganid'];
            $pembelianke = $_POST['pembelianke'];
            
			if($tanggal!="" && $pelangganid!="" && $pembelianke!="")
			{
					$transaction = Yii::app()->db->beginTransaction();
					try{
						$faktur = Faktur::model()->find("pelangganid=".intval($pelangganid)." and cast(tanggal as date)='$tanggal' and pembelianke=".$pembelianke);            
						$faktur->hargatotal=$_POST['hargatotalinput'];
						$faktur->bayar=$_POST['bayar'];
						$faktur->sisa=$_POST['hargatotalinput'] - ($_POST['bayar']-$_POST['totaldiskoninput']);
						$faktur->diskon=$_POST['totaldiskoninput'];
						if($faktur->save()) // save faktur
						{
							$setoran = new Setoran;
							$setoran->fakturid=$faktur->id;
							$setoran->tanggalsetoran=date("Y-m-d H:", time());
							$setoran->jumlah=$_POST['bayar'];;
							$setoran->jenisbayar='cash';
							$setoran->createddate =date("Y-m-d H:", time());
							$setoran->userid = Yii::app()->user->id;   
							$setoran->save();
							
							
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

							//update status penjualan 
							$updateStatusPenjualan = Inbox::model()->updateStatusPenjualan($_POST['pelangganid'],$_POST['tanggal']);

							//redirect ke print faktur
							echo CJSON::encode(array('result'=>'OK','pelangganid'=>$_POST['pelangganid'],'tanggal'=>$_POST['tanggal'],'pembelianke'=>$pembelianke));                  
						}
						$transaction ->commit();
					} 
					catch (Exception $error) {
							$transaction ->rollback();
							throw $error;
					}
			}			                                                                                                            
        }
        
        public function actionCetak()
        {
            $this->render('cetak_faktur',array(                 
            ));
        }
        
        public function actionPrintfaktur($id)
        {
            //$updateStatusPenjualan = Inbox::model()->findByPk($id);
            //print("<pre>".print_r($updateStatusPenjualan,true)."</pre>");	
            //echo $updateStatusPenjualan['hargasatuan'];
            //die;
                   
            //echo Yii::app()->Report->Export("test.jrxml","Faktur Penjualan",array(intval($id)),array("penjualanbarangid"=>"penjualanbarangid"),"pdf");
            //die;          
                    $updateStatusPenjualan = Inbox::model()->findByPk($id); //select penjualan barang
                    $updateStatusPenjualan->statuspenjualan=true;
                    if($updateStatusPenjualan->save())
                    {                          
                        $divisiIdGudang=  Divisi::model()->find("kode='1'")->id;
                        $divisiIdHygienis=  Divisi::model()->find("kode='3'")->id;
                        // cek apakah divisi gudang atw hygienis, karena yg terdapat di 2 tempat(parung dan ciseeng) hanya divisi gudang dan hygienis 
                        if($updateStatusPenjualan["divisiid"]!=$divisiIdGudang AND $updateStatusPenjualan["divisiid"]!=$divisiIdHygienis)
                        {
                            //select stock exist
                            $criteria=new CDbCriteria;                                        
                            $criteria->condition = 'barangid = :barangid and divisiid= :divisiid';
                            $criteria->params = array('barangid'=>intval($updateStatusPenjualan["barangid"]),'divisiid'=>intval($updateStatusPenjualan["divisiid"]));
                            $stockExist = Stock::model()->find($criteria);
                            //

                            $stockExist->jumlah=intval($stockExist->jumlah)-intval($updateStatusPenjualan["jumlah"]);                              
                            if($stockExist->save())
                            {
                                //cetak faktur
                                echo Yii::app()->Report->Export("test.jrxml","Faktur Penjualan",array(intval($id)),array("penjualanbarangid"=>"penjualanbarangid"),"pdf");
                            }
                        }   
                        else 
                        {
                            //select stock exist
                            $criteria=new CDbCriteria;                                        
                            $criteria->condition = 'barangid = :barangid and lokasipenyimpananbarangid= :lokasipenyimpananbarangid';
                            $criteria->params = array('barangid'=>intval($updateStatusPenjualan["barangid"]),'lokasipenyimpananbarangid'=>intval($updateStatusPenjualan["lokasipenyimpananbarangid"]));
                            $stockExist = Stock::model()->find($criteria);
                            //

                            $stockExist->jumlah=intval($stockExist->jumlah)-intval($updateStatusPenjualan["jumlah"]);                              
                            if($stockExist->save())
                            {
                                //cetak faktur
                                echo Yii::app()->Report->Export("test.jrxml","Faktur Penjualan",array(intval($id)),array("penjualanbarangid"=>"penjualanbarangid"),"pdf");
                            }
                        }                                                  
                    }                
        }
        
        
        public function actionPopupSetJenisPembayaran($id)
        {
            if (Yii::app ()->request->isAjaxRequest) 
            {            
                    $pelangganid = substr($id, 10,4);      
                    
                    $pelanggan = Pelanggan::model()->findByPk($pelangganid)->nama;
                    $tanggal = substr($id, 0,10);                                                                             
            
                    
                    $this->layout = 'a';                     
                    $this->render ( 'form_set_jenis_pembayaran', array (
                                    'pelanggan'=>$pelanggan,
                                    'tanggal'=>$tanggal,
                                    'pelangganid'=>$pelangganid
                    ), false, true );
                    Yii::app()->end();
			}
        }
        
        public function actionSetJenisPembayaran()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                
				//inbox
				if($_POST['pilihan']==1)
				{
					echo CJSON::encode(array(
						'result'=>'INBOX',
						'tanggal'=>$_POST['tanggal'],
						'pelangganid'=>$_POST['pelangganid'],
						));       
					Yii::app()->end();
				}	
				//data setoran
				if($_POST['pilihan']==2)
				{
					$data = Inbox::model()->setJenisPembayaran($_POST['pelangganid'],$_POST['tanggal']);                                          
					Yii::app ()->user->setFlash ( 'success', "Data Inbox Berhasil diPindahkan ke Data Setoran." );                           
					echo CJSON::encode(array('result'=>'SETORAN'));       
					Yii::app()->end();
				}					               
            }
        }
		
		public function actionInboxOrSetoran()
		{
			
		}
        
      
	
}