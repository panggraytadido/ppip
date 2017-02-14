<?php

class GudangController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Gudang - Penerimaan Barang';
    
	public function actionIndex()
	{
            //print("<pre>".print_r($data,true)."</pre>");	
            
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
            $dataChartPenjualanBarang = Yii::app()->Grafik->PenjualanBarangGudang();
            $dataChartPenerimaanBarang = Yii::app()->Grafik->PenerimaanBarangGudang();            
            //
            
            //print("<pre>".print_r($dataChartPenjualanBarang,true)."</pre>");
            
            //die;
            $this->render('index',
                    array(
                        'modelStockupdate'=>$modelStockupdate,                        
                        'modelPenerimaanBarangGudang'=>$modelPenerimaanBarangGudang,
                        'modelPenjualanBarangGudang'=>$modelPenjualanBarang,                        
                        'dataChartPenjualanBarang'=>$dataChartPenjualanBarang,
                        'dataChartPenerimaanBarang'=>$dataChartPenerimaanBarang,
                    )   
                    );
	}           
              
        public function actionDetailStockUpdate()
        {
           $id = Yii::app()->getRequest()->getParam('id');         
           
            $criteria=new CDbCriteria;                      
            $criteria->condition = 'barangid ='.$id;        
			$criteria->order='lokasipenyimpananbarangid,supplierid asc';	
        
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
		
		public function actionFormhargabarang()
		{
			if(isset($_POST['jenisharga']))
            {                
               echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'jenisharga'=>$_POST['jenisharga']
                ));
               
               Yii::app()->end();
            }
		}
		
		public function actionCetak()
		{
			if($_GET['jenisharga']==1)
			{
				$this->render('report_harga_barang_internal');
			}
			else
			{
				$this->render('report_harga_barang_pelanggan');
			}
			//$this->render('report_harga_barang');
		}
}