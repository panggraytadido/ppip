<?php

class TransfermasukController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Transfer Masuk - Admin';
    
	public function actionIndex()
	{           
            $model=new Transfermasuk('search');
            $model->unsetAttributes();
            
            if(isset($_GET['Transfermasuk']))
                    $model->attributes = $_GET['Transfermasuk'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
	}
        
        public function actionChildDataSetoran()
        {
               $id = Yii::app()->getRequest()->getParam('id');
               
               $child = Transfermasuk::model()->detail($id);	
			   
				//print("<pre>".print_r($child,true)."</pre>");
				//die;

                // partially rendering "_relational" view
                $this->renderPartial('detail', array(
                                //'id' => Yii::app()->getRequest()->getParam('id'),
                                'child' => $child,
                ));
        }
        
        public function actionSimpan()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                
                
                //print("<pre>".print_r($_POST,true)."</pre>");
                //die;
                $model = new Transfer;
                $model->jenistransferid=5; //transfer masuk
                $model->rekeningid=$_POST['rekening'];
                $model->pelangganid=$_POST['pelanggan'];
                $model->debit=$_POST['nilaitransaksiinput'];                
                $model->saldo=$_POST['totalsaldoinput'];
                $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                $model->createddate =date("Y-m-d H:", time());
                $model->userid = Yii::app()->user->id;
                if($model->save())
                {                                                                    
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',                        
                    )); 
                    Yii::app ()->user->setFlash ( 'success', "Data Transfer Masuk Berhasil Ditambah." );
                    Yii::app()->end();
                }
            }
        }
		
		public function actionCheckupdate($id)
		{
			if (Yii::app ()->request->isAjaxRequest) {
				$data = Setoran::model()->with('faktur')->findAll(); 
				
				$pelangganid = $data[0]["faktur"]["pelangganid"];
				$tanggal = date("Y-m-d", strtotime($data[0]["faktur"]["tanggal"]));
				$saldo = $data[0]["jumlah"];
									
				$transfer = Transfer::model()->find("pelangganid=".$pelangganid."and cast(tanggal as date)='$tanggal' and saldo=$saldo");
				echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'tanggal'=>date("m/d/Y", strtotime($transfer->tanggal)),
                    'rekeningid'=>$transfer->rekeningid,
					'pelangganid'=>$transfer->pelangganid,
					'nilaitransaksi'=>$transfer->debit,
					'sisatransaksi'=>$data[0]["faktur"]["sisa"],
					'saldo'=>$transfer->saldo,
                ));   
				//print("<pre>".print_r($transfer,true)."</pre>");
			}	
		}
        
        public function actiongetNoRekening()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                $rekening = Rekening::model()->findByPk(intval($_POST['id']))->norekening;
                
                //select saldo terakhir berdasarkan tanggal rekening dan tanggal terakhir
                $connection=Yii::app()->db;
                $sql ="select max(cast(tanggal as date)) as tanggal,max(id) as id from transaksi.transfer where rekeningid=".intval($_POST['id']);           
                $data=$connection->createCommand($sql)->queryRow();
                $tanggal = $data["tanggal"];                        
                $id = $data["id"];
                
                if($tanggal!='' AND $id!='')
                {
                    $criteria = new CDbCriteria;                        
                    $criteria->condition="rekeningid=".intval($_POST['id'])." AND cast(tanggal as date)='$tanggal' AND id=".$id;
                    $d = Transfer::model()->find($criteria);
                    $saldo=$d->saldo;
                }
                else
                {
                    $saldo=0;
                }
                
                
                
                echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'rekening'=>$rekening,
                    'saldo'=>$saldo
                ));                 
                //print("<pre>".print_r($_POST,true)."</pre>");
            }
        }
        
        public function getSaldo($rekeningid)
        {
            $connection=Yii::app()->db;                        
            $sql="select sum(debit)-sum(kredit) as saldo from transaksi.transfer where rekeningid=$rekeningid";					
            $data=$connection->createCommand($sql)->queryRow();
            return $data['saldo'];
        }
        
        public function actionPopupfaktur()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                
                $criteria = new CDbCriteria;
                $criteria->condition='pelangganid='.intval($_POST['pelangganid']).' AND sisa!=0';
                $faktur = Faktur::model()->findAll($criteria);
                
                $this->layout='a';
                $this->render('form_setoran_faktur',array(
                        'faktur'=>$faktur,
                        'pelangganid'=>intval($_POST['pelangganid'])
                ), false, true );

                Yii::app()->end();
            }
        }
        
        public function actionSimpanSetoran()
        {
            if (Yii::app ()->request->isAjaxRequest) {                                            
                 $transaction = Yii::app()->db->beginTransaction();
                    try{
                            for($i=0;$i<count($_POST['fakturid']);$i++)
                            {
                                $faktur = Faktur::model()->findByPk(intval($_POST['fakturid'][$i]));

                                if($_POST['bayar'][$i]!='')
                                {
                                    $setoran = new Setoran;
                                    $setoran->fakturid=$faktur->id;
                                    $setoran->tanggalsetoran=date("Y-m-d H:", time());
                                    $setoran->jumlah=intval($_POST['bayar'][$i]);
                                    $setoran->jenisbayar='transfer';
                                    $setoran->createddate =date("Y-m-d H:", time());
                                    $setoran->userid = Yii::app()->user->id;                               
                                    if($setoran->save())
                                    {
                                        $faktur->bayar=$faktur->bayar+intval($_POST['bayar'][$i]);
                                        if($faktur->save())
                                        {
                                            if($faktur->bayar==$faktur->hargatotal)
                                            {
                                                $faktur->sisa=0;
                                            }
                                            else
                                            {
                                                $faktur->sisa=$faktur->hargatotal-$faktur->bayar;
                                            }
                                        }
                                                                                
                                        $faktur->updateddate=date("Y-m-d H:", time());
                                        $faktur->save();                                                                                                                            
                                    }
                                }                                
                            }   
                            
                                $criteria = new CDbCriteria;
                                $criteria->select='sum(sisa) as sisa';
                                $criteria->condition='pelangganid='.$_POST['pelangganid'].' AND sisa!=0';                                            
                                $sisaTransaksi = Faktur::model()->find($criteria)->sisa;
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',                                                
                                    'sisaTransaksi'=>$sisaTransaksi
                                )); 
                            
                            $transaction ->commit();
                        } 
                    catch (Exception $error) {
                        
                        $transaction ->rollback();
                        throw $error;
                    }   
            }
        }
		
		public function actionUpdate()
		{
			
		}
}        