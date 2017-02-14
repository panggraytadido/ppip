<?php

class HygienisController extends Controller
{
	public function actionIndex()
	{
            $modelStockupdate =  new Stockupdatehygienis('search');            
            $modelStockupdate->unsetAttributes();
            if(isset($_GET['Stockupdatehygienis']))
                    $modelStockupdate->attributes=$_GET['Stockupdatehygienis'];
            //end   
            
            //penerimaan barang             
            $modelPenerimaanBarangHygienis = new Penerimaanbaranghygienis('search');            
            $modelPenerimaanBarangHygienis->unsetAttributes();
            if(isset($_GET['Penerimaanbaranghygienis']))
                    $modelPenerimaanBarangHygienis->attributes=$_GET['Penerimaanbaranghygienis'];
            //end 
            
            //penjualan barang
            $modelPenjualanBarangHygienis = new Penjualanbaranghygienis('search');
            $modelPenjualanBarangHygienis->unsetAttributes();
            if(isset($_GET['Penjualanbaranghygienis']))
                    $modelPenjualanBarangHygienis->attributes=$_GET['Penjualanbaranghygienis'];
            //
            
            //grafik
            //$dataChartPenjualanBarang = Yii::app()->Grafik->PenjualanBarangBesek();
            //$dataChartPenerimaanBarang = Yii::app()->Grafik->PenerimaanBarangBesek();            
            ////
            
            $this->render('index',
                    array(
                        'modelStockupdate'=>$modelStockupdate,                        
                        'modelPenerimaanBarangHygienis'=>$modelPenerimaanBarangHygienis,
                        'modelPenjualanBarangHygienis'=>$modelPenjualanBarangHygienis,
                        //'dataChartPenjualanBarang'=>$dataChartPenjualanBarang,
                        //'dataChartPenerimaanBarang'=>$dataChartPenerimaanBarang,
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