<?php

class JualkesupplierController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Jual Ke Supplier - Kasir';
    
	public function actionIndex()
	{           
            $model=new Jualkesupplier('search');
            $model->unsetAttributes();
            
            if(isset($_GET['Jualkesupplier']))
                    $model->attributes=$_GET['Jualkesupplier'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
	}
        
        public function actionChildInbox()
        {
           $id = Yii::app()->getRequest()->getParam('id');
           $pelangganid = substr($id, 10,4);           
           $tanggal = substr($id, 0,10);          
           
           $child = Jualkesupplier::model()->listDetail($pelangganid,$tanggal);	
           //print("<pre>".print_r($child,true)."</pre>");
           //die;
            // partially rendering "_relational" view
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
        
        public function actionFormFaktur($id)
        {            
            $supplierpembeliid = substr($id, 10,4);           
            $tanggal = substr($id, 0,10);
            
            $cekFaktur = Faktursupplier::model()->findAll("supplierpembeliid=".intval($supplierpembeliid)." and cast(tanggal as date)='$tanggal'");            
            
            if(count($cekFaktur)!=0)
            {                                 
                for($i=0;$i<count($cekFaktur);$i++)
                {
                   $data[]= $cekFaktur[$i]['pembelianke'];
                }
                $pembelianke = max($data)+1;
                                
                $cekFakturRefresh = Faktursupplier::model()->findAll("supplierpembeliid=".intval($supplierpembeliid)." and cast(tanggal as date)='$tanggal' and pembelianke=".$pembelianke);             
                //print("<pre>".print_r($cekFakturRefresh,true)."</pre>");	
                
                //die;
                if(count($cekFakturRefresh)!=0)
                {
                    $this->redirect(array('index'));                   
                }
                else
                {                       
                    //$pembelianke=$pembelianke;
                     //print("<pre>".print_r($data,true)."</pre>");	
                     //$pembelianKe = $cekFaktur->pembelianke+1;
                     //die;      
                    $transaction = Yii::app()->db->beginTransaction();
                    try{
                        
                        $data = Jualkesupplier::model()->detailFormFaktur($supplierpembeliid,$tanggal);                    
                        $faktur = new Faktursupplier;
                        $faktur->nofaktur=  Supplier::model()->findByPk($supplierpembeliid)->namaperusahaan."-".$this->generateRandomFaktur();
                        $faktur->supplierpembeliid=$supplierpembeliid;
                        $faktur->pembelianke=1;
                        $faktur->lokasipenyimpananbarangid = Yii::app()->session['lokasiid'];
                        $faktur->createddate=date("Y-m-d H:i:s", time());
                        $faktur->userid=Yii::app()->user->id;
                        $faktur->tanggal=$tanggal;                    
                        if($faktur->save()) // save faktur
                        {
                            $dataPenjualan= Jualkesupplier::model()->findAll("cast(tanggal as date)='$tanggal' AND statuspenjualan=FALSE AND issendtokasir=true AND supplierpembeliid=$supplierpembeliid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
                            for($i=0;$i<count($dataPenjualan);$i++)
                            {
                                //faktur penjualan
                                $fakturpenjualan = new Faktursupplierpenjualanbarang;
                                $fakturpenjualan->penjualanbarangkesupplierid=$dataPenjualan[$i]['id'];
                                $fakturpenjualan->faktursupplierid=$faktur->id;
                                $fakturpenjualan->save();
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
                try
                {
                    $pembelianke=1;
                    $data = Jualkesupplier::model()->detailFormFaktur($supplierpembeliid,$tanggal);                    
                    $faktur = new Faktursupplier;
                    $faktur->nofaktur=  Supplier::model()->findByPk($supplierpembeliid)->namaperusahaan."-".$this->generateRandomFaktur();
                    $faktur->supplierpembeliid=$supplierpembeliid;
                    $faktur->pembelianke=1;
                    $faktur->lokasipenyimpananbarangid = Yii::app()->session['lokasiid'];
                    $faktur->createddate=date("Y-m-d H:i:s", time());
                    $faktur->userid=Yii::app()->user->id;
                    $faktur->tanggal=$tanggal;                    
                    // save faktur
                    if($faktur->save()) 
                    {
                        $dataPenjualan= Jualkesupplier::model()->findAll("cast(tanggal as date)='$tanggal' AND statuspenjualan=FALSE AND supplierpembeliid=$supplierpembeliid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
                        for($i=0;$i<count($dataPenjualan);$i++)
                        {
                            //faktur penjualan
                            $fakturpenjualan = new Faktursupplierpenjualanbarang;
                            $fakturpenjualan->penjualanbarangkesupplierid=$dataPenjualan[$i]['id'];
                            $fakturpenjualan->faktursupplierid=$faktur->id;
                            $fakturpenjualan->save(false);
                            //
                        }
                    }                    
                    $transaction ->commit();
                } 
                catch (Exception $error) 
                {                        
                    $transaction ->rollback();
                    throw $error;
                }
            }
                        
             $this->render('form_faktur',array(
                    'data'=>$data,
                    'supplierpembeliid'=>$supplierpembeliid,
                    'tanggal'=>$tanggal,
                    'pembelianke'=>$pembelianke,
            ));
        }
        
        //simpan form faktur
        public function actionSimpan()
        {                        
            $tanggal = $_POST['tanggal'];
            $supplierpembeliid = $_POST['supplierpembeliid'];
            $pembelianke = $_POST['pembelianke'];
                        
            $transaction = Yii::app()->db->beginTransaction();
            try{
                $faktur = Faktursupplier::model()->find("supplierpembeliid=".intval($supplierpembeliid)." and cast(tanggal as date)='$tanggal' and pembelianke=".$pembelianke);   
                //print("<pre>".print_r($faktur,true)."</pre>");
                //die;
                $faktur->hargatotal=$_POST['hargatotalinput'];
                $faktur->bayar=$_POST['bayar'];
                $faktur->sisa=$_POST['hargatotalinput'] - ($_POST['bayar']-$_POST['totaldiskoninput']);
                $faktur->diskon=$_POST['totaldiskoninput'];                
                if($faktur->save()) // save faktur
                {                 
                    //select data berdasarkan penjualan barang 
                    for($i=0;$i<count($_POST['penjualanbarangid']);$i++)
                    {                        
                        //klw ada diskon
                        if($_POST['diskoninput'][$i]!=0)
                        {
                            $model = Jualkesupplier::model()->findByPk($_POST['penjualanbarangid'][$i]);                
                            $model->diskon =  intval($_POST['diskoninput'][$i]);
                            $model->save(false);               
                        }
                        //                                                                              
                    }
                    
                    //select data barang untuk diupdate stocknya
                    $dataPenjualan= Jualkesupplier::model()->findAll("cast(tanggal as date)='$tanggal' AND supplierpembeliid=$supplierpembeliid and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);
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
                    $updateStatusPenjualan = Jualkesupplier::model()->updateStatusPenjualan($supplierpembeliid,$_POST['tanggal']);
                    
                    //insert ke transfer 
                    $criteria = new CDbCriteria;
                    
                    //

                    //redirect ke print faktur
                    echo CJSON::encode(array('result'=>'OK','supplierpembeliid'=>$supplierpembeliid,'tanggal'=>$_POST['tanggal'],'pembelianke'=>$pembelianke));                  
                }
                $transaction ->commit();
            } 
            catch (Exception $error) {
                    $transaction ->rollback();
                    throw $error;
            }                                                                                                  
        }
        
        public function actionCetak()
        {
            $this->render('cetak_faktur',array(                 
            ));
        }
}        