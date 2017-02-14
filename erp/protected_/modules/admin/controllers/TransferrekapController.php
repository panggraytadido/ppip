<?php

class TransferrekapController extends Controller
{
	public $layout='//layouts/column2';
	public $pageTitle = 'Transfer Rekap - Admin';
    
	public function actionIndex()
	{           
            $model=new Transferrekap('search');
            $model->unsetAttributes();
            
            if(isset($_GET['Transferrekap']))
                    $model->attributes = $_GET['Transferrekap'];
            
            
            $this->render('index',array(
                    'model'=>$model,
            ));
	}                
        
        public function actionDetail()
        {
            $supplierid = Yii::app()->getRequest()->getParam('id');
            $child = Transferkeluar::model()->listDetail($supplierid);	
          
            // partially rendering "_relational" view
            $this->renderPartial('detail', array(
                            'id' => Yii::app()->getRequest()->getParam('id'),
                            'child' => $child,
            ));
        }
        
        public function actionCreate()
        {
            $model = new Transferkeluar;
            
            if(isset($_POST['Transferkeluar']))
            {
                $model2 = new Transfer;
                //print("<pre>".print_r($_POST['Transferkeluar'],true)."</pre>");
                $model2->jenistransferid=$_POST['Transferkeluar']['jenisTransfer'];
                $model2->rekeningid=$_POST['Transferkeluar']['rekening'];
                $model2->jenistransferid=$_POST['Transferkeluar']['jenisTransfer'];
                $model2->supplierid=$_POST['Transferkeluar']['supplier'];
                $model2->kredit=$_POST['Transferkeluar']['jumlah'];
                $model2->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Transferkeluar']['tanggal']);
                $model2->createddate=date("Y-m-d H:i:s", time());
                $model2->userid=Yii::app()->user->id;
                if($model2->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',                                
                    ));
                    Yii::app ()->user->setFlash ( 'success', "Data Transfer Berhasil Ditambah." );
                    Yii::app()->end();
                }
            }
            
            $this->layout='a';
            $this->render('form',array(
                    'model'=>$model,                    
            ), false, true );
            
            Yii::app()->end();
        }
		
		public function actionDelete($id)
		{
			$trans = Transfer::model()->findByPk($id);
			
			//jika penerimaan barang dari supplier 
			if($trans->penerimaanbarangid!="" AND $trans->penjualanbarangkesupplierid==0)
			{
				
			}
			//jika penjualan barang ke supplier
			elseif($trans->penerimaanbarangid==0 AND $trans->penjualanbarangkesupplierid!="")
			{
				
			}
		}
}        