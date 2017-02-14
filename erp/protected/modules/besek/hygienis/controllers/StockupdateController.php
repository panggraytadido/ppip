<?php

class StockupdateController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Hygienis - Stock update';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Stockupdate('search');
            $model->unsetAttributes();
            if(isset($_GET['Stockupdate']))
                    $model->attributes=$_GET['Stockupdate'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionDetail()
        {
           $id = Yii::app()->getRequest()->getParam('id');                                     
           $child = Stockupdate::model()->listDetail($id);
           $countSupplier = Stocksupplier::model()->count( 'barangid=:barangid', array(':barangid' => $id));
           
           if($countSupplier!=0)
           {
                $this->renderPartial('detail_stock_update',array(
                    'child'=>$child,
                    'id'=>$id
                ));
           }    
           else 
           {
               echo CHtml::link('Set Stock', array('formsetstock','id'=>$id), array('class'=>'btn btn-warning btn-xs'));
           }
           
        }
                       
        
        public function actionFormSetStock($id)                
        {
            //cek jika character '-' yg berarti ada supplierid 
            if (preg_match('/[\'\/~`\!@#\$%\^&\*\(\)_\-\+=\{\}\[\]\|;:"\<\>,\.\?\\\]/', $id))
            {
                $data = explode("-", $id);
                $supplierid = $data[0];
                $barangid = $data[1];

                $stocksupplier = Stocksupplier::model()->find('supplierid='.$supplierid. ' AND barangid='. $barangid .' AND lokasipenyimpananbarangid='.Yii::app()->session['lokasiid']);
                if($stocksupplier!='')
                {
                     $model = new Stockupdate;                    

                     $this->render('form_set_stock',array(
                        'barangid'=>$barangid,
                        'supplierid'=>$supplierid,
                         'model'=>$model,
                         'stocksupplier'=>$stocksupplier
                    ));
                }
                else
                {
                    Yii::app ()->user->setFlash ( 'success', 'Data Barang Tidak Ada');
                    $this->redirect(array('index'));
                } 
            }
            else
            {
                $model = new Stockupdate; 
                $this->render('set_supplier_stock',array(
                        'barangid'=>$id,                      
                         'model'=>$model
                    ));
            }                                                      
        }
        
        //jika belum ada supplier
        public function actionSetSupplierStockAwal($barangid)
        {
            if (Yii::app ()->request->isAjaxRequest) {
                $model = new Stockupdate;
                $model->attributes=$_POST['Stockupdate'];
                
                $valid = $model->validate();
                if($valid)
                {
                    $modelStockSupplier = new Stocksupplier;
                    $modelStockSupplier->barangid=$barangid;
                    $modelStockSupplier->supplierid=$_POST['Stockupdate']['supplierid'];
                    $modelStockSupplier->jumlah = $_POST['Stockupdate']['jumlah'];
                    $modelStockSupplier->lokasipenyimpananbarangid=Yii::app()->session['lokasiid'];                    
                    $modelStockSupplier->createddate=date("Y-m-d H:i:s", time());
                    $modelStockSupplier->userid=Yii::app()->user->id; 
                    if($modelStockSupplier->save())
                    {
                        $stock = Stock::model()->find('barangid='.$barangid.' AND lokasipenyimpananbarangid='.Yii::app()->session['lokasiid']);
                        $stock->jumlah = $stock->jumlah+$_POST['Stockupdate']['jumlah'];
                        $stock->updateddate=date("Y-m-d H:", time());
                        $stock->userid=Yii::app()->user->id;
                        if($stock->save())
                        {
                             Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil di Set Stock dan Suppplier nya" );                                
                            echo CJSON::encode(array('result'=>'OK','barangid'=>$barangid,'supplierid'=>$_POST['Stockupdate']['supplierid']));  
                            Yii::app()->end();  
                        }
                    }                                                           
                }
                else
                {
                    $error = CActiveForm::validate($model);
                    if($error!='[]')
                            echo $error;
                    Yii::app()->end();
                }	


                   Yii::app()->end();
                
            }
        }
        
        //jika sudah ada supplier , tinggal set stock awal
        public function actionSetStockAwal($supplierid,$barangid)
        {
            if (Yii::app ()->request->isAjaxRequest) {
                
                $model = new Stockupdate;
                $model->attributes=$_POST['Stockupdate'];
                //print("<pre>".print_r($_POST['Stockupdate'],true)."</pre>");	
                //die;
                if(isset($_POST['Stockupdate']))
                {
                    $valid = $model->validate();
                    if($valid)
                    {                            
                            $stocksupplier = Stocksupplier::model()->find('supplierid='.$supplierid. ' AND barangid='. $barangid .' AND lokasipenyimpananbarangid='.Yii::app()->session['lokasiid']);
                            $stocksupplier->jumlah = $stocksupplier->jumlah+$_POST['Stockupdate']['jumlah'];
                            $stocksupplier->updateddate=date("Y-m-d H:", time());
                            $stocksupplier->userid=Yii::app()->user->id;
                            if($stocksupplier->save())
                            {
                                $stock = Stock::model()->find('barangid='.$barangid.' AND lokasipenyimpananbarangid='.Yii::app()->session['lokasiid']);
                                $stock->jumlah = $stock->jumlah+$_POST['Stockupdate']['jumlah'];
                                $stock->updateddate=date("Y-m-d H:", time());
                                $stock->userid=Yii::app()->user->id;
                                if($stock->save())
                                {
                                    Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil di Set Stock" );                                
                                    echo CJSON::encode(array('result'=>'OK','barangid'=>$barangid,'supplierid'=>$supplierid));  
                                    Yii::app()->end();  
                                }
                            }                            
                    }
                    else
                    {
                            $error = CActiveForm::validate($model);
                            if($error!='[]')
                                    echo $error;
                            Yii::app()->end();
                    }	


                    Yii::app()->end();
                }                                
            }
            
        }
        
        public function actionView($supplierid,$barangid)
        {
            $jumlah = Stocksupplier::model()->find('supplierid='.$supplierid. ' AND barangid='. $barangid .' AND lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'])->jumlah;
            
            $this->render('view',array(
                    'barangid'=>$barangid,
                    'supplierid'=>$supplierid,
                    'jumlah'=>$jumlah
                ));
        }
 
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='stockupdate-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
}