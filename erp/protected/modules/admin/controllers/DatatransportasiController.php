<?php

class DatatransportasiController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Data Transportasi';
    
	public function actionIndex()
	{
            //print("<pre>".print_r($data,true)."</pre>");	                        
            //die;
            
            $modelKota=new Kota('search');
                $modelKota->unsetAttributes();

            if(isset($_GET['Kota']))
                $modelKota->attributes=$_GET['Kota'];
            
            $modelKendaraan=new Kendaraan('search');
                $modelKendaraan->unsetAttributes();

            if(isset($_GET['Kendaraan']))
                $modelKendaraan->attributes=$_GET['Kendaraan'];
            
            $modelBiaya=new Biayaperjalanan('search');
                $modelBiaya->unsetAttributes();
                
                
            if(isset($_GET['Biayaperjalanan']))
                $modelBiaya->attributes=$_GET['Biayaperjalanan'];
            
            $modelDataPerjalanan=new Dataperjalanan('search');
                $modelDataPerjalanan->unsetAttributes(); 
                

            if(isset($_GET['Dataperjalanan']))
                $modelDataPerjalanan->attributes=$_GET['Dataperjalanan'];
            
            $this->render('index',
                    array(
                        'modelDataPerjalanan'=>$modelDataPerjalanan,
                        'modelKota'=>$modelKota,
                        'modelKendaraan'=>$modelKendaraan,
                        'modelBiaya'=>$modelBiaya
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
}