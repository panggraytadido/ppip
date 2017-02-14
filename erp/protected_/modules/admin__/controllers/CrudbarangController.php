<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class CrudbarangController extends Controller
{
    public $pageTitle = 'Admin - Data Barang';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Crudbarang('search');
        $model->unsetAttributes();

        if(isset($_GET['Crudbarang']))
                $model->attributes=$_GET['Crudbarang'];

        $this->render('index',array(
                'model'=>$model,
        ));
    }
    
    public function actionDetailBarang()
    {
           $id = Yii::app()->getRequest()->getParam('id');                            
           
           $cekExistHarga = Hargabarang::model()->find('barangid='.$id);
           if($cekExistHarga!='')
           {
                $connection=Yii::app()->db;
                $sql ="SELECT distinct supplierid,barangid FROM transaksi.stocksupplier t WHERE t.barangid =$id";
                
                $data=$connection->createCommand($sql)->queryAll();
                //print("<pre>".print_r($data,true)."</pre>");	 
               //die;    
                 // partially rendering "_relational" view
                 $this->renderPartial('detail', array(
                                 'id' => Yii::app()->getRequest()->getParam('id'),
                                 'child' => $data,
                                 'barangid'=>$id
                 ));
           }      
           else 
           {
                echo CHtml::link('Set Harga per Supplier', array('formsethargapersupplier','id'=>$id), array('class'=>'btn btn-warning btn-xs'));
           }
    }
    
    
    public function actionFormSetHargaPerSupplier($id)                
    {                     
        $model = new Crudbarang; 
        $this->render('set_harga_per_supplier',array(
                'barangid'=>$id,                      
                 'model'=>$model
            ));                                           
    }
    
        
        /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{           
            $model=new Crudbarang;            
            if(isset($_POST['Crudbarang']))
            {	                
                    $model->attributes=$_POST['Crudbarang'];
                    $valid = $model->validate();
                    if($valid)
                    {                            
                           //$model->attributes=$_POST['Databarang'];
                           //print("<pre>".print_r($_POST['Databarang'],true)."</pre>");	                           
                            $modelBarang = new Barang;
                            $modelBarang->divisiid=$_POST['Crudbarang']['divisiid'];
                            $modelBarang->kode=$_POST['Crudbarang']['kode'];
                            $modelBarang->nama=$_POST['Crudbarang']['nama'];
                            if($modelBarang->save())
                            {             
                                    $lokasi = Lokasipenyimpananbarang::model()->findAll();
                                    foreach($lokasi as $row)
                                    {
                                        $modelStock = new Stock;
                                        $modelStock->barangid=$modelBarang->id;
                                        $modelStock->jumlah=0;
                                        $modelStock->lokasipenyimpananbarangid=$row["id"];
                                        $modelStock->tanggal=date("Y-m-d H:i:s", time());
                                        $modelStock->createddate=date("Y-m-d H:i:s", time());
                                        $modelStock->userid=Yii::app()->user->id;          
                                        $modelStock->save();
                                    }
                                
                                    echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                    ));    	
                                     Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diTambahkan" );
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
            else 
            {
                $this->layout='a';
                $this->render('_form',array(
                        'model'=>$model,                               
                ), false, true );

                Yii::app()->end();

            }                  
    }
	
        
        public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $model = Crudbarang::model ()->findByPk ($id);                
                    $this->render ( 'form_update', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
        
        /**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate()
	{            
            if (Yii::app ()->request->isAjaxRequest) {
		$model=  Crudbarang::model()->findByPk($_POST["Crudbarang"]["id"]);
                $model->kode=$_POST["Crudbarang"]["kode"]; 
                $model->divisiid=$_POST["Crudbarang"]["divisiid"]; 
                $model->nama=$_POST["Crudbarang"]["nama"];                 
                if($model->save())
                {                                                                    
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diubah" );
                    Yii::app()->end();
                }
            }   
	}
        
        public function actionFormUpdate($supplierid,$barangid)
        {            
            $this->render ( 'form_ubah_harga', array (
                                    'supplierid' => $supplierid,
                                    'barangid' => $barangid,
                    ), false, true );
        }
        
        // Sort the multidimensional array
     
        // Define the custom sort function
        public function custom_sort($a,$b) {
             return $a['hargamodal']>$b['hargaeceran'];
        }
        
        public function actionSetHargaBarang()
        {
            if(isset($_POST['supplier']))
            {       
                $data = array();
                for($i=1;$i<=count($_POST['supplier']);$i++)
                {
                    
                    $data[] = array(
                        'barangid'=>$_POST['barangid'],    
                        'supplier'=>$_POST['supplier'][$i],
                        'hargamodal'=>$_POST['hargamodal'][$i],
                        'hargaeceran'=>$_POST['hargamodal'][$i],
                        'hargagrosir'=>$_POST['hargagrosir'][$i],
                    );                                                 
                }
               // print("<pre>".print_r($data,true)."</pre>");	
                //$data1 = arsort($data);
                //print("<pre>".print_r($data1,true)."</pre>");	               
              
                foreach($data as $key => $value)
                {
                    if($value["hargamodal"]!=0 || $value["hargaeceran"] !=0 || $value["hargagrosir"]!=0)
                    {
                       unset($data[$key]);
                    }
                }
             
               //klw ada isian yg tidak diisi
                if(count($data)!=0)
                {
                     echo CJSON::encode(array('result'=>'Failed'));  
                }
                else
                {
                    //echo CJSON::encode(array('result'=>'OK'));  
                    
                    for($i=1;$i<=count($_POST['supplier']);$i++)
                    {
                        $modelHarga = new Hargabarang;
                        $modelHarga->barangid=$_POST['barangid'];
                        $modelHarga->supplierid=$_POST['supplier'][$i];
                        $modelHarga->hargamodal=$_POST['hargamodal'][$i];
                        $modelHarga->hargaeceran=$_POST['hargaeceran'][$i];
                        $modelHarga->hargagrosir=$_POST['hargagrosir'][$i];
                        $modelHarga->createddate=date("Y-m-d H:", time());;
                        $modelHarga->userid=Yii::app()->user->id;
                        if($modelHarga->save())
                        {

                            $lokasi = Lokasipenyimpananbarang::model()->findAll();
                            foreach($lokasi as $row)
                            {
                                $modelStockSupplier = new Stocksupplier;
                                $modelStockSupplier->barangid=$_POST['barangid'];
                                $modelStockSupplier->supplierid=$_POST['supplier'][$i];
                                $modelStockSupplier->jumlah=0;
                                $modelStockSupplier->lokasipenyimpananbarangid=$row["id"];
                                //$modelStockSupplier->tanggal=date("Y-m-d H:i:s", time());
                                $modelStockSupplier->createddate=date("Y-m-d H:i:s", time());
                                $modelStockSupplier->userid=Yii::app()->user->id;          
                                $modelStockSupplier->save();
                            }

                        }
                    }
                    
                     Yii::app ()->user->setFlash ( 'success', "Harga Barang Per Supplier Berhasil diset" );                                
                    echo CJSON::encode(array('result'=>'OK'));  
                    Yii::app()->end();        
                    
                }    
                //print("<pre>".print_r($data,true)."</pre>");	
                                                              
            }         
        }
        
        public function actionUpdateBarang()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                
                $supplieridlama = $_POST['supplierlamaid'];
                $supplierid = $_POST['supplierid'];
                $barangid = $_POST['barangid'];
                $hargamodal = $_POST['hargamodal'];
                $hargaeceran = $_POST['hargaeceran'];
                $hargagrosir = $_POST['hargagrosir'];
                
                // klw supliiernya sama
                if($supplieridlama==$supplierid)
                {
                    //print("<pre>".print_r($_POST,true)."</pre>");	
                    //die;
                    $connection =Yii::app()->db;
                    $sql = "update transaksi.hargabarang set hargamodal=$hargamodal,hargaeceran=$hargaeceran,hargagrosir=$hargagrosir where supplierid=$supplierid and  barangid=$barangid";
                    $command = $connection->createCommand($sql);
                    if($command->execute())
                    {
                        echo CJSON::encode(array
                        (
                            'result'=>'OK',
                        ));    
                        Yii::app ()->user->setFlash ( 'success', "Harga Barang Berhasil Diubah" );
                        Yii::app()->end();
                    }
                }
                //jika supplier berbeda
                else
                {            
                    //print("<pre>".print_r($_POST,true)."</pre>");	
                  //die;
                    
                    $hargaBarang = Hargabarang::model()->find("barangid=".$barangid." AND supplierid=".$supplieridlama);
                    $hargaBarang->supplierid=$supplierid;
                    $hargaBarang->hargamodal=$hargamodal;
                    $hargaBarang->hargagrosir=$hargagrosir;
                    $hargaBarang->hargaeceran=$hargaeceran;
                    if($hargaBarang->save())
                    {
                        $connection =Yii::app()->db;
                        $sql = "update transaksi.stocksupplier set supplierid=".$supplierid." where barangid=".$barangid." and supplierid=".$supplieridlama;                    
                        $command = $connection->createCommand($sql);
                        if($command->execute())
                        {
                            echo CJSON::encode(array
                            (
                                'result'=>'OK',
                            ));    
                            Yii::app ()->user->setFlash ( 'success', "Harga Barang dan Supplier Berhasil Diubah" );
                            Yii::app()->end();
                        
                        }                                                   
                    }
                    //print("<pre>".print_r($hargaBarang,true)."</pre>");	
                    //die;
                    /*
                    $connection =Yii::app()->db;
                    $sqlHargaBarang = "delete from transaksi.hargabarang where supplierid=$supplieridlama and  barangid=$barangid";                    
                    $commandHargaBarang = $connection->createCommand($sqlHargaBarang);
                    if($commandHargaBarang->execute())
                    {
                        $sqlStockSupplier = "delete from transaksi.stocksupplier where supplierid=$supplieridlama and  barangid=$barangid";      
                        $commandStockSupplier = $connection->createCommand($sqlStockSupplier);
                        if($commandStockSupplier->execute())
                        {
                            $modelHarga = new Hargabarang;
                            $modelHarga->supplierid=$supplierid;
                            $modelHarga->barangid=$barangid;
                            $modelHarga->hargaeceran=$hargaeceran;
                            $modelHarga->hargagrosir=$hargagrosir;
                            $modelHarga->hargamodal=$hargamodal;
                            $modelHarga->createddate=date("Y-m-d H:i:s", time());;
                            $modelHarga->userid=Yii::app()->user->id;  
                            if($modelHarga->save())
                            {
                                $lokasi = Lokasipenyimpananbarang::model()->findAll();
                                foreach($lokasi as $row)
                                {
                                    $lokasi = Lokasipenyimpananbarang::model()->findAll();
                                    foreach($lokasi as $row)
                                    {
                                        $modelStockSupplier = new Stocksupplier;
                                        $modelStockSupplier->barangid=$barangid;
                                        $modelStockSupplier->supplierid=$supplierid;
                                        $modelStockSupplier->jumlah=0;
                                        $modelStockSupplier->lokasipenyimpananbarangid=$row["id"];
                                        //$modelStockSupplier->tanggal=date("Y-m-d H:i:s", time());
                                        $modelStockSupplier->createddate=date("Y-m-d H:i:s", time());
                                        $modelStockSupplier->userid=Yii::app()->user->id;          
                                        $modelStockSupplier->save();
                                    }
                                }
                                
                                
                                
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',
                                ));    
                                Yii::app ()->user->setFlash ( 'success', "Harga Barang dan Supplier Berhasil Diubah" );
                                Yii::app()->end();
                            }
                        }                                                                    
                    }
                    */
                }    
                    
            }
        }
        
        
        public function actionGetSupplier()
        {
            $rec = Supplier::model()->findAll();
            
            $i=0;
            $id = array();
            $nama = array();
            foreach($rec as $r)
            {
                $data[] = array(
                    'id'=>$r->id,
                    'nama'=>$r->namaperusahaan
                );                         
                $i++;
            }
            
            echo CJSON::encode(
                    $data
                );
        }
             
        //delete barang per supplier
        public function actionDeleteBarangPerSupplier($barangid,$supplierid)
        {
            $connection =Yii::app()->db;
            $sql = "delete from transaksi.hargabarang where barangid=".$barangid." and supplierid=".$supplierid;                    
            $command = $connection->createCommand($sql);
            if($command->execute())
            {
                $sql = "delete from transaksi.stocksupplier where barangid=".$barangid." and supplierid=".$supplierid;                    
                $command = $connection->createCommand($sql);
                if($command->execute())
                {
                   Yii::app ()->user->setFlash ( 'success', "Data Supplier Barang berhasil dihapus" );
                   $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
                }   
            }                                                                                      
        }
        
        
        /**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
            $del  = $this->loadModel($id)->delete();
            if($del)
            {
                $connection =Yii::app()->db;
                $sql = "delete from transaksi.hargabarang where barangid=".$id;                    
                $command = $connection->createCommand($sql);
                if($command->execute())
                {
                    $sql = "delete from transaksi.stock where barangid=".$id;                    
                    $command = $connection->createCommand($sql);
                    if($command->execute())
                    {
                        $sql = "delete from transaksi.stock where barangid=".$id;                    
                        $command = $connection->createCommand($sql);
                        if($command->execute())
                        {
                            Yii::app ()->user->setFlash ( 'success', "Data Barang berhasil dihapus" );
                            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
                        }                                               
                    }   
                }           
            }

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if(!isset($_GET['ajax']))
                    $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
        
        public function loadModel($id)
	{
		$model= Crudbarang::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}    