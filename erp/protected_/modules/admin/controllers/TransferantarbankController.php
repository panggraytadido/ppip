<?php

class TransferantarbankController extends Controller
{
        public $layout='//layouts/column2';
        public $pageTitle = 'Transfer Antar Bank - Admin';
    
	public function actionIndex()
	{           
            $model=new Transferantarbank('search');
            $model->unsetAttributes();
            
            if(isset($_GET['Transferantarbank']))
                    $model->attributes = $_GET['Transferantarbank'];
            
            $this->render('index',array(
                    'model'=>$model,
            ));
	}
       
        
        public function actionCreate()
	{
		$model=new Transferantarbank;

		// Uncomment the following line if AJAX validation is needed
		 $this->performAjaxValidation($model);

		if(isset($_POST['Transferantarbank']))
		{
                    $model->attributes=$_POST['Transferantarbank'];
                    $valid = $model->validate();   
                    
                    if($valid)
                    {
                        //rekening asal 
                        $connection=Yii::app()->db;
                        $sql ="select max(cast(tanggal as date)) as tanggal,max(id) as id from transaksi.transfer where rekeningid=".$_POST['Transferantarbank']['rekeningid'];           
                        $data=$connection->createCommand($sql)->queryRow();
                        $tanggal = $data["tanggal"];                        
                        $id = $data["id"];                      
                     
                        if($tanggal!='' AND $id!='')
                        {
                            $criteria = new CDbCriteria;                        
                            $criteria->condition="rekeningid=".$_POST['Transferantarbank']['rekeningid']." AND cast(tanggal as date)='$tanggal' AND id=".$id;
                            $rekeningAsalDb = Transfer::model()->find($criteria); 
                            $rekeningAsalDb = $rekeningAsalDb->saldo;
                        }
                        else
                        {
                            $rekeningAsalDb = 0;
                        }                                                    
                        //

                        
                        //rekening tujuan 
                        $connection=Yii::app()->db;
                        $sql ="select max(cast(tanggal as date)) as tanggal,max(id) as id from transaksi.transfer where rekeningid=".$_POST['Transferantarbank']['rekeningtujuanid'];           
                        $data=$connection->createCommand($sql)->queryRow();
                        $tanggal = $data["tanggal"];                        
                        $id = $data["id"];                      
                         
                        if($tanggal!='' AND $id!='')
                        {
                            $criteria = new CDbCriteria;                        
                            $criteria->condition="rekeningid=".$_POST['Transferantarbank']['rekeningtujuanid']." AND cast(tanggal as date)='$tanggal' AND id=".$id;
                            $rekeningTujuanDb = Transfer::model()->find($criteria);  
                            $rekeningTujuanDb = $rekeningTujuanDb->saldo;  
                        }                           
                        else
                        {
                            $rekeningTujuanDb = 0;  
                        }
                        //
                        
                        
                        $rekeningAsal = new Transfer;
                        $rekeningAsal->jenistransferid=6;
                        $rekeningAsal->rekeningid=$_POST['Transferantarbank']['rekeningid'];
                        $rekeningAsal->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Transferantarbank']['tanggal']);
                        $rekeningAsal->kredit=$_POST['Transferantarbank']['jumlah'];     
                        $rekeningAsal->nama ='Transfer Antar Bank';
                        $rekeningAsal->saldo = $rekeningAsalDb-$_POST['Transferantarbank']['jumlah'];     
                        $rekeningAsal->createddate=date("Y-m-d H:", time());
                        $rekeningAsal->userid = Yii::app()->user->id; 
                        if($rekeningAsal->save())                            
                        {
                            
                            $rekeningTujuan  = new Transfer;                            
                            $rekeningTujuan->jenistransferid=6;
                            $rekeningTujuan->rekeningid=$_POST['Transferantarbank']['rekeningtujuanid'];
                            $rekeningTujuan->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Transferantarbank']['tanggal']);
                            $rekeningTujuan->kredit=$_POST['Transferantarbank']['jumlah'];     
                            $rekeningTujuan->nama ='Transfer Antar Bank';
                            $rekeningTujuan->saldo = $rekeningTujuanDb+$_POST['Transferantarbank']['jumlah'];     
                            $rekeningTujuan->createddate=date("Y-m-d H:", time());
                            $rekeningTujuan->userid = Yii::app()->user->id; 
                            if($rekeningTujuan->save())
                            {
                                $model = new Transferantarbank;
                                $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Transferantarbank']['tanggal']);
                                $model->rekeningid=$_POST['Transferantarbank']['rekeningid'];
                                $model->rekeningtujuanid=$_POST['Transferantarbank']['rekeningtujuanid'];
                                $model->jumlah=$_POST['Transferantarbank']['jumlah'];                        
                                $model->createddate=date("Y-m-d H:", time());
                                $model->userid = Yii::app()->user->id; 
                                //$model->transferid=$transfer->id;
                                if($model->save())
                                {
                                    echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                        'id'=>$model->id
                                    ));

                                    Yii::app ()->user->setFlash ( 'success', "Data Transfer Antar Bank Berhasil Ditambah." );
                                    Yii::app()->end();
                                }   
                            }                                                        
                        }                                                                                                                     
                    }
                    else
                    {
                        $error = CActiveForm::validate($model);
                        if($error!='[]')
                        {
                            echo $error;
                        }
                    }                			
		}

                $this->layout='a';
		$this->render('form',array(
			'model'=>$model,
		));
	}
        
        public function actiongetNoRekening()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                $rekening = Rekening::model()->findByPk(intval($_POST['rekeningid']))->norekening;
                $pemilik = Rekening::model()->findByPk(intval($_POST['rekeningid']))->namapemilik;
                //select saldo terakhir berdasarkan tanggal rekening dan tanggal terakhir
                $connection=Yii::app()->db;
                $sql ="select max(cast(tanggal as date)) as tanggal,max(id) as id from transaksi.transfer where rekeningid=".intval($_POST['rekeningid']);           
                $data=$connection->createCommand($sql)->queryRow();
                $tanggal = $data["tanggal"];                        
                $id = $data["id"];
                
                if($tanggal!="" AND $id!="")
                {
                    $criteria = new CDbCriteria;                        
                    $criteria->condition="rekeningid=".intval($_POST['rekeningid'])." AND cast(tanggal as date)='$tanggal' AND id=".$id;
                    $d = Transfer::model()->find($criteria);
                    $saldo = $d->saldo;
                }
                else
                {
                    $saldo=0;
                }
                
                
                
                echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'rekening'=>$rekening,
                    'pemilik'=>$pemilik,
                    'saldo'=>$saldo
                ));                 
                //print("<pre>".print_r($_POST,true)."</pre>");
            }
        }        
        
        /**
	 * Performs the AJAX validation.
	 * @param Transferkasir $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='transferantarbank-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}        