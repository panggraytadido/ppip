<?php

class GudangController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Gudang - Penerimaan Barang';
    
	public function actionIndex()
	{
            $modelStockupdate = new Stockupdategudang('search');        
              $modelStockupdate->unsetAttributes();
            if(isset($_GET['Stockupdategudang']))
                    $modelStockupdate->attributes=$_GET['Stockupdategudang'];
            
            //penerimaan barang             
            $modelPenerimaanBarangGudang = new Penerimaanbaranggudang('search');            
            $modelPenerimaanBarangGudang->unsetAttributes();
            if(isset($_GET['Penerimaanbaranggudang']))
                    $modelPenerimaanBarangGudang->attributes=$_GET['Penerimaanbaranggudang'];
            //end 
            
            //penjualan barang
            $modelPenjualanBarang = new Penjualanbaranggudang('search');
            $modelPenjualanBarang->unsetAttributes();
            if(isset($_GET['Penjualanbaranggudang']))
                    $modelPenjualanBarang->attributes=$_GET['Penjualanbaranggudang'];
            //
            
            //grafik         
            $grafikPenjualanBarang = Penjualanbaranggudang::model()->grafikPenjualanBarang();
            //print("<pre>".print_r($data,true)."</pre>");	
            
            $hargaSatuan = array();
            $bulan = array();
            $dataChartPenjualanBarang = array();
            $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            
            
            for($i=0;$i<count($grafikPenjualanBarang);$i++)
            {                           
                foreach($dataBulan as $keyBulan=>$valueBulan)
                {
                    if($keyBulan==$grafikPenjualanBarang[$i]["bulan"])
                    {
                         $dataChart[] = array(
                            "name"=>$valueBulan,
                            "data"=>array(intval($grafikPenjualanBarang[$i]["hargasatuan"])),
                            //"stack"=>$valueBulan 
                        ); 
                    }
                }                                                                           
            }
           //print("<pre>".print_r($dataChart,true)."</pre>");	                                                        
            
            $this->render('index',
                    array(
                        'modelStockupdate'=>$modelStockupdate,                        
                        'modelPenerimaanBarangGudang'=>$modelPenerimaanBarangGudang,
                        'modelPenjualanBarangGudang'=>$modelPenjualanBarang,                        
                        'dataChartPenjualanBarang'=>$dataChartPenjualanBarang
                    )   
                    );
	}  
        
        public function actionDetailStockUpdate()
        {
           $id = Yii::app()->getRequest()->getParam('id');         
           
            $criteria=new CDbCriteria;                      
            $criteria->condition = 'barangid ='.$id;            
        
           $child = Stocksupplier::model()->findAll($criteria);	
         // print("<pre>".print_r($child,true)."</pre>");	
           
           //die;
            // partially rendering "_relational" view
            $this->renderPartial('detail_stock_update', array(
                            'id' => Yii::app()->getRequest()->getParam('id'),
                            'child' => $child,
            ));
        }
        
        // add penerimaan barang
        public function actionAddpenerimaanbarang()
        {
            if (Yii::app ()->request->isAjaxRequest) {        
                    //stock 
                    //print("<pre>".print_r($_POST,true)."</pre>");	
                    //die;
                    $model= new Penerimaanbaranggudang;
                    $model->attributes=$_POST['Penerimaanbaranggudang'];
                    
                    $valid = $model->validate();                    
                    if($valid)
                    {                    
                        $divisiid = Divisi::model()->find("kode='1'")->id;
                        $model->divisiid=$divisiid;
                        $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Penerimaanbaranggudang']['tanggal']);
                        $model->supplierid=$_POST['Penerimaanbaranggudang']['supplierid'];
                        $model->barangid=$_POST['Penerimaanbaranggudang']['barangid'];
                        $model->jumlah=$_POST['Penerimaanbaranggudang']['jumlah'];
                        $model->beratlori=$_POST['Penerimaanbaranggudang']['beratlori'];
                        $model->totalbarang=$_POST['Penerimaanbaranggudang']['totalbarang'];
                        $model->userid=Yii::app()->user->id;
                        $model->createddate = date("Y-m-d H:i:s", time());
                        $model->isdeleted = false;

                        //save penerimaan barang
                        if($model->save())
                        {
                            //save karyawan bongkat muat
                            for($i=1;$i<=count($_POST['karyawan']);$i++)
                            {
                                $modelBongkarMuat = new Bongkarmuat;
                                $modelBongkarMuat->penerimaanbarangid=$model->id;
                                $modelBongkarMuat->upah=$_POST['upahkaryawanpenerimaaninput'];
                                $modelBongkarMuat->karyawanid=$_POST['karyawan'][$i];
                                $modelBongkarMuat->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Penerimaanbaranggudang']['tanggal']);
                                $modelBongkarMuat->divisiid=$divisiid;
                                $modelBongkarMuat->userid=Yii::app()->user->id;
                                $modelBongkarMuat->createddate = date("Y-m-d H:i:s", time());
                                $modelBongkarMuat->save();
                            }

                            //stock
                            $existJumlahStock = Stock::model()->find("barangid=".$_POST['Penerimaanbaranggudang']['barangid']."AND lokasipenyimpananbarangid=".$_POST['Penerimaanbaranggudang']['lokasipenyimpananbarangid'])->jumlah;
                            //print("<pre>".print_r($existJumlahStock,true)."</pre>");	
                            $modelStock = Stock::model()->find("barangid=".$_POST['Penerimaanbaranggudang']['barangid']."AND lokasipenyimpananbarangid=".$_POST['Penerimaanbaranggudang']['lokasipenyimpananbarangid']);
                            $modelStock->jumlah= $existJumlahStock+$_POST['Penerimaanbaranggudang']['jumlah'];
                            if($modelStock->save())
                            {                                                                
                                echo CJSON::encode(array('result'=>'OK'));    	
                                Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil ditambahkan" );
                                Yii::app()->end();                      
                            }

                        }
                    }                                         
                    else
                    {               
                            //$valid = $model->validate();
                            //print("<pre>".print_r($valid,true)."</pre>");	
                            //die;
                            $error = CActiveForm::validate($model);
                            if($error!='[]')                                    
                                    echo $error;
                            Yii::app()->end();
                    }	                
            }
        }
        
        // update penerimaan barang
        public function actionUpdatepenerimaanbarang($id)
        {
            if (Yii::app ()->request->isAjaxRequest) {                            
                    if(isset($_POST['Penerimaanbaranggudang']))
                    {
                        $bongkarMuat = Bongkarmuat::model()->findAll('penerimaanbarangid='.$id);
                        $jumlahBongkarMuat = count($bongkarMuat);
                        $jumlahBongkarMuatUpdate = count($_POST['updateKaryawanPenerimaanBarang']);
                                                    
                        if(intval($jumlahBongkarMuatUpdate>$jumlahBongkarMuat))
                        {                            
                            //print("<pre>".print_r($bongkarMuat,true)."</pre>");	
                            //die;                            
                            for($i=0;$i<=$jumlahBongkarMuatUpdate;$i++)
                            {
                                if(isset($bongkarMuat[$i]["karyawanid"])==isset($_POST['updateKaryawanPenerimaanBarang'][$i]))
                                {                                                                        
                                    echo '';                                                                       
                                }  
                                else 
                                {                                    
                                    $modelBongkarMuat = new Bongkarmuat;
                                    $modelBongkarMuat->penerimaanbarangid=$id;
                                    $modelBongkarMuat->upah=$_POST['upahkaryawanpenerimaan'];
                                    $modelBongkarMuat->karyawanid=$_POST['updateKaryawanPenerimaanBarang'][$i];
                                    //$modelBongkarMuat->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Penerimaanbarang']['tanggal']);
                                    $modelBongkarMuat->divisiid=$_POST['Penerimaanbaranggudang']['divisiid'];
                                    $modelBongkarMuat->userid=Yii::app()->user->id;
                                    $modelBongkarMuat->createddate = date("Y-m-d H:i:s", time());
                                    if($modelBongkarMuat->save())
                                    {
                                        $modelPenerimaanBarangGudang = new Penerimaanbaranggudang;
                                        $modelPenerimaanBarangGudang->updateUpahBongkarMuat(intval($_POST['upahkaryawanpenerimaan']),intval($id));                                            
                                        //Penerimaanbaranggudang::model()->updateUpahBongkarMuat(intval($id),intval($_POST['upahkaryawanpenerimaan']));                                           
                                        echo CJSON::encode(array('result'=>'OK'));    	
                                        Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diupdate" );
                                        Yii::app()->end();
                                    }
                                    
                                }   
                            }                                                                                      
                        }
                        else
                        {                            
                            $model = Penerimaanbaranggudang::model()->findAllByPk($id);
                            $model->divisiid=$_POST['Penerimaanbaranggudang']['divisiid'];
                            $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Penerimaanbaranggudang']['tanggal']);
                            $model->supplierid=$_POST['Penerimaanbaranggudang']['supplierid'];
                            $model->barangid=$_POST['Penerimaanbaranggudang']['barangid'];
                            $model->jumlah=$_POST['Penerimaanbaranggudang']['jumlah'];
                            $model->beratlori=$_POST['Penerimaanbaranggudang']['beratlori'];
                            $model->totalbarang=$_POST['Penerimaanbaranggudang']['totalbarang'];
                            $model->userid=Yii::app()->user->id;
                            $model->createddate = date("Y-m-d H:i:s", time());
                            $model->isdeleted = false;
                            $model->save();
                        }
                    }
                    else 
                    {
                        $model = Penerimaanbaranggudang::model()->findByPk($id);
                        $bongkarMuat = Bongkarmuat::model()->findAll('penerimaanbarangid='.$id);
                        $jumlahBongkarMuat = count($bongkarMuat); 
                        $karyawan= Karyawan::model()->findAll();
                        
                        //upah
                        $criteria = new CDbCriteria;
                        $criteria->limit=1;
                        $criteria->condition='penerimaanbarangid='.$id;
                        $upahBongkarMuat=Bongkarmuat::model()->find($criteria)->upah;                       
                        
                        $this->layout = 'a';                                
                        $this->render ( 'penerimaanbarang/update_form_penerimaan_barang', array (
                                        'modelPenerimaanBarang' => $model,
                                        'jumlahBongkarMuat' => $jumlahBongkarMuat,
                                        'modelBongkarMuat' => $bongkarMuat,
                                        'modelKaryawan' => $karyawan,
                                        'upahBongkarMuat' => $upahBongkarMuat,
                        ), false, true );
                        
                        Yii::app()->end();
                    }
            }
        }
        
        public function actionAddpenjualanbarang()
        {
            if (Yii::app ()->request->isAjaxRequest) {        
                //stock
                  
                    $model= new Penjualanbaranggudang;
                    $model->attributes=$_POST['Penjualanbaranggudang'];
                    $valid = $model->validate();
                    if($valid)
                    { 

                        $divisiid = Divisi::model()->find("kode='1'")->id;
                        $criteria=new CDbCriteria;    
                        $criteria->alias = 's'; 
                        $criteria->with=array('barang');     
                        $criteria->condition = 's.barangid = :barangid and s.lokasipenyimpananbarangid= :lokasipenyimpananbarangid and barang.divisiid=:divisiid';
                        $criteria->params = array(':divisiid' =>$divisiid,'barangid'=>intval($_POST['Penjualanbaranggudang']['barangid']),'lokasipenyimpananbarangid'=>intval($_POST['Penjualanbaranggudang']['lokasipenyimpananbarangid']));

                        $stock = Stock::model()->find($criteria);   
                        if($_POST['Penjualanbaranggudang']['jumlah']<=$stock["jumlah"])
                        {
                            //print("<pre>".print_r($_POST,true)."</pre>");	
                                //die;
                                if($_POST['kategori']==1)
                                {
                                    $kategori=1;
                                    $hargasatuan = Barang::model()->findByPk(intval($_POST['Penjualanbaranggudang']['barangid']))->hargaeceran;
                                }
                                else 
                                {
                                    $kategori=2;
                                    $hargasatuan = Barang::model()->findByPk(intval($_POST['Penjualanbaranggudang']['barangid']))->hargagrosir;
                                }

                                $hargaModal = Barang::model()->findByPk(intval($_POST['Penjualanbaranggudang']['barangid']))->hargamodal;
                                $totalModal=$hargaModal*$_POST['Penjualanbaranggudang']['jumlah'];

                                $divisiid = Divisi::model()->find("kode='1'")->id;
                                $totalHarga = $hargasatuan*$_POST['Penjualanbaranggudang']['jumlah'];

                                $model->barangid=$_POST['Penjualanbaranggudang']['barangid'];
                                $model->divisiid=$divisiid;                        
                                $model->pelangganid=$_POST['Penjualanbaranggudang']['pelangganid'];
                                $model->lokasipenyimpananbarangid=$_POST['Penjualanbaranggudang']['lokasipenyimpananbarangid'];
                                $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Penjualanbaranggudang']['tanggal']);
                                $model->hargatotal=$totalHarga;
                                $model->labarugi=$totalHarga-$totalModal;
                                $model->jumlah=$_POST['Penjualanbaranggudang']['jumlah'];
                                $model->netto=$_POST['Penjualanbaranggudang']['jumlah'];
                                $model->kategori=$kategori;
                                $model->hargatotal=$_POST['Penjualanbaranggudang']['hargatotalinput'];
                                $model->box=$_POST['Penjualanbaranggudang']['box'];
                                $model->hargasatuan=$_POST['hargainput'];
                                $model->userid=Yii::app()->user->id;
                                $model->createddate = date("Y-m-d H:i:s", time());
                                //$model->isdeleted = false;

                                //save penjualan barang
                                if($model->save(false))
                                {
                                    //save karyawan bongkat muat
                                    for($i=1;$i<=count($_POST['karyawan']);$i++)
                                    {
                                        $modelBongkarMuat = new Bongkarmuat;
                                        $modelBongkarMuat->penjualanbarangid=$model->id;
                                        $modelBongkarMuat->karyawanid=$_POST['karyawan'][$i];
                                        $modelBongkarMuat->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Penjualanbaranggudang']['tanggal']);
                                        $modelBongkarMuat->divisiid=$divisiid;
                                        $modelBongkarMuat->upah=$_POST['upahkaryawanpenjualaninput'];                                
                                        $modelBongkarMuat->userid=Yii::app()->user->id;
                                        $modelBongkarMuat->createddate = date("Y-m-d H:i:s", time());
                                        $modelBongkarMuat->save();
                                    }

                                    echo CJSON::encode(array('result'=>'OK'));    	
                                    Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil diTambahkan" );
                                    Yii::app()->end();                                                         
                                }
                        }
                        else 
                        {
                            echo CJSON::encode(array('result'=>'NotOK'));    	
                            //Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil diTambahkan" );
                            Yii::app()->end();                                                         
                        }                                                         
                    }                                         
                    else
                    {                        
                            $error = CActiveForm::validate($model);
                            if($error!='[]')
                                    echo $error;
                            Yii::app()->end();
                    }	                
            }
        }
        
        // update penjualan barang
        public function actionUpdatepenjualanbarang($id)
        {
            if (Yii::app ()->request->isAjaxRequest) {                            
                    if(isset($_POST['Penjualanbaranggudang']))
                    {
                        $bongkarMuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$id);
                        $jumlahBongkarMuat = count($bongkarMuat);
                        $jumlahBongkarMuatUpdate = count($_POST['updateKaryawanPenerimaanBarang']);
                                                    
                        if(intval($jumlahBongkarMuatUpdate>$jumlahBongkarMuat))
                        {                            
                            //print("<pre>".print_r($bongkarMuat,true)."</pre>");	
                            //die;                            
                            for($i=0;$i<=$jumlahBongkarMuatUpdate;$i++)
                            {
                                if(isset($bongkarMuat[$i]["karyawanid"])==isset($_POST['updateKaryawanPenjualanBarang'][$i]))
                                {                                                                        
                                    echo '';                                                                       
                                }  
                                else 
                                {                                    
                                    $modelBongkarMuat = new Bongkarmuat;
                                    $modelBongkarMuat->penjualanbarangid=$id;
                                    $modelBongkarMuat->upah=$_POST['upahkaryawanpenerimaan'];
                                    $modelBongkarMuat->karyawanid=$_POST['updateKaryawanPenerimaanBarang'][$i];
                                    //$modelBongkarMuat->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Penerimaanbarang']['tanggal']);
                                    //$modelBongkarMuat->divisiid=$_POST['Penjualanbaranggudang']['divisiid'];
                                    $modelBongkarMuat->userid=Yii::app()->user->id;
                                    $modelBongkarMuat->createddate = date("Y-m-d H:i:s", time());
                                    if($modelBongkarMuat->save())
                                    {
                                        $modelPenjualanBarangGudang = new Penjualanbaranggudang;
                                        $modelPenjualanBarangGudang->updateUpahBongkarMuat(intval($_POST['upahkaryawanpenerimaan']),intval($id));                                            
                                        //Penerimaanbaranggudang::model()->updateUpahBongkarMuat(intval($id),intval($_POST['upahkaryawanpenerimaan']));                                           
                                        echo CJSON::encode(array('result'=>'OK'));    	
                                        Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diupdate" );
                                        Yii::app()->end();
                                    }
                                    
                                }   
                            }                                                                                      
                        }
                        else
                        {
                            
                        }
                    }
                    else 
                    {
                        $model = Penjualanbaranggudang::model()->findByPk($id);
                        $bongkarMuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$id);
                        $jumlahBongkarMuat = count($bongkarMuat);                         
                        $karyawan= Karyawan::model()->findAll();
                        
                        //upah
                        $criteria = new CDbCriteria;
                        $criteria->limit=1;
                        $criteria->condition='penjualanbarangid='.$id;
                        $upahBongkarMuat=Bongkarmuat::model()->find($criteria)->upah;                       
                        
                        $this->layout = 'a';                                
                        $this->render ( 'penjualanbarang/update_form_penjualan_barang', array (
                                        'modelPenjualanBarang' => $model,
                                        'jumlahBongkarMuat' => $jumlahBongkarMuat,
                                        'modelBongkarMuat' => $bongkarMuat,
                                        'modelKaryawan' => $karyawan,
                                        'upahBongkarMuat' => $upahBongkarMuat,
                        ), false, true );
                        
                        Yii::app()->end();
                    }
            }
        }
        
        
        public function actionDeletepenjualangudang($id)
        {
            if (Yii::app ()->request->isAjaxRequest) {    
                $del=  Penjualanbaranggudang::model()->findByPk($id)->delete();                
                if($del)
                {
                    $del2 = Bongkarmuat::model()->deleteAll(
                        'penjualanbarangid=:penjualanbarangid',
                        array(':penjualanbarangid' => $id)
                    );
                    
                    if($del2)
                    {                        			
			if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array());                       
                    }
                }
            }
        }
        
        //get karyawan
        public function actionGetkaryawangudang()
        {
            $karyawan= Karyawan::model()->findAll();
             //$Productmodel=Product::model()->findAll(array('condition'=>'isactive=1 and catid='.$_POST['catid'],'order'=>'name'));
             //$data=CHtml::listData($karyawan,'id','nama');
             echo CJSON::encode($karyawan);    	
                Yii::app()->end();
        }
        
        
         //get karyawan
        public function actionDeletekaryawangudang()
        {
            $del = Bongkarmuat::model()->findByPk(intval($_POST['penerimaanbarangid']))->delete();
            if($del)
            {
                echo CJSON::encode(array('result'=>'OK'));    	                                        
                Yii::app()->end();
            }            
        }
                
        
        //get harga
        public function actionGetharga()
        {
            if (Yii::app ()->request->isAjaxRequest) {    
                //harga eceran
                if($_POST['kategori']==1)
                {
                     echo Barang::model()->findByPk(intval($_POST['barangid']))->hargaeceran;
                }
                //harga eceran
                if($_POST['kategori']==2)
                {
                    echo Barang::model()->findByPk(intval($_POST['barangid']))->hargagrosir;
                }
                
            }
        }
        
        
        public function actionCheckstockbarang()
        {
            if (Yii::app ()->request->isAjaxRequest) { 
                $divisiid = Divisi::model()->find("kode='1'")->id;
                $criteria1=new CDbCriteria;    
                $criteria1->alias = 's'; 
                $criteria1->with=array('barang');     
                $criteria1->condition = 's.barangid = :barangid and s.lokasipenyimpananbarangid= :lokasipenyimpananbarangid and barang.divisiid=:divisiid';
                $criteria1->params = array(':divisiid' =>$divisiid,'barangid'=>intval($_POST['barangid']),'lokasipenyimpananbarangid'=>intval($_POST['lokasipenyimpananbarangid']));
                                
                $stock = Stock::model()->find($criteria1);   
                
               $stockPending = Penjualanbaranggudang::model()->checkStockPending(intval($_POST['barangid']),6,intval($_POST['lokasipenyimpananbarangid']));
                //echo $stock["jumlah"];
                //print("<pre>".print_r($stockPending,true)."</pre>");
                //die;
                echo CJSON::encode(array('stock'=>$stock["jumlah"],'stockPending'=>$stockPending));    	                                        
               Yii::app()->end();
            }            
        }
        
        public function actionT()
        {
           Divisi::model()->find("kode='1'")->id;
             $criteria = new CDbCriteria;
             $criteria->condition="divisiid='1'";
            $data = Barang::model()->findAll($criteria);
            print_r($data);
        }
}