<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PerbaikandataController extends Controller {
    
    //put your code here
    public $pageTitle = 'Kasir - Perbaikan Data';
    
    public function filters()
    {
            return array();

    }            
    
    public function actionIndex()
    {            
            $model=new Perbaikandata('search');
            $model->unsetAttributes();
                                    
            $this->render('index',array(
                    'model'=>$model,
            ));
    }
    
    public function actionView()
    {
        if (Yii::app ()->request->isAjaxRequest) {
            if(isset($_POST['Perbaikandata']))
            {
                $model = new Perbaikandata;
                $valid = $model->validate();
                if($valid)
                {
                    $pelangganId = $_POST['Perbaikandata']['pelangganId'];
                    $tanggalPembelian = $_POST['Perbaikandata']['tanggalPembelian'];
                    $tanggalPembelian = Yii::app()->DateConvert->ConvertTanggal($_POST['Perbaikandata']['tanggalPembelian']);                

                    if(isset($_POST['Perbaikandata']['pembelianKe']))
                    {
                        $pembelianKe = $_POST['Perbaikandata']['pembelianKe'];
                        $criteria = new CDbCriteria;
                        $criteria->select='distinct jenispembayaran';
                        $criteria->condition="cast(tanggal as date)='$tanggalPembelian' and pelangganid=".$pelangganId." and pembelianke=".$pembelianKe;

                        $jenis = Penjualanbarang::model()->find($criteria);

                    }
                    else
                    {
                        $jenis="";
                    }

                    $data = Perbaikandata::model()->findAll("pelangganid=".$pelangganId." AND cast(tanggal as date)='$tanggalPembelian'");   

                    $this->layout = 'a';                     
                    $this->renderPartial ( 'detail_data', array (
                                    'data'=>$data,       
                                    'jenis'=>$jenis,       
                    ), false, true );
                    Yii::app()->end();
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
        }
    }
    
    public function actionProses()
    {
        $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
        $pelangganId = $_POST["pelangganid"];
        $pembelianKe = $_POST["pembelianke"];
        $jenis = $_POST["jenis"];
                       
        //inbox
        if($_POST['jenis']==1)
        {
             $data = Perbaikandata::model()->findAll("cast(tanggal as date)='$tanggal' "
                    . "AND pelangganid=".$pelangganId." AND pembelianke=".$pembelianKe);
            
            //update data penjualan
            for($i=0;$i<count($data);$i++)
            {
                $dataPenjualan = Perbaikandata::model()->findByPk($data[$i]["id"]);
                $dataPenjualan->jenispembayaran='tunai';
                $dataPenjualan->save(false);                
            }       
            
            Yii::app ()->user->setFlash ( 'success', "Perbaikan Data Berhasil dipindahkan ke INBOX." );
            Yii::app()->end();
        }
        //data setoran
        else
        {
            //delete faktur, karena diinbox lngsung generate faktur
            $criteria = new CDbCriteria;
            $criteria->select='distinct f.id';
            $criteria->alias='f';
            $criteria->join = "INNER JOIN transaksi.fakturpenjualanbarang fjb ON fjb.fakturid = f.id";
            $criteria->join .= " INNER JOIN transaksi.penjualanbarang pb ON fjb.penjualanbarangid = pb.id";
            $criteria->condition = "f.pelangganid =".$pelangganId." AND cast(f.tanggal as date)='$tanggal' and pb.pembelianke=".$pembelianKe;
            $faktur = Faktur::model()->findAll($criteria);
            if(count($faktur)>0)
            {
                for($i=0;$i<count($faktur);$i++)
                {
                    Faktur::model()->findByPk($faktur[$i]["id"])->delete();                
                }
                //end delete             
            }
            
            $data = Perbaikandata::model()->findAll("cast(tanggal as date)='$tanggal' "
                    . "AND pelangganid=".$pelangganId." AND pembelianke=".$pembelianKe);
            
            //update data penjualan
            for($i=0;$i<count($data);$i++)
            {
                $dataPenjualan = Perbaikandata::model()->findByPk($data[$i]["id"]);
                $dataPenjualan->jenispembayaran='kredit';
                $dataPenjualan->save(false);                
            }                     
            
            Yii::app ()->user->setFlash ( 'success', "Perbaikan Data Berhasil dipindahkan ke Data Setoran." );
            Yii::app()->end();
        }
                
    }
    
    public function actionPembelianKe()
    {
        $tanggal =Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
        $pelangganid = $_POST['pelangganid'];
            
        $criteria=new CDbCriteria;
        $criteria->select = 'distinct pembelianke';        
        $criteria->condition ="cast(tanggal as date)='$tanggal' and pelangganid=".$pelangganid;

        $rec = Penjualanbarang::model()->findAll($criteria);

        $i=0;
        $id = array();
        $nama = array();
        foreach($rec as $r)
        {
            $id[$i] = $r->pembelianke;
            $nama[$i] = $r->pembelianke;
            $i++;
        }

        echo CJSON::encode(array(
                'id' => $id,
                'text' => $nama,
            ));
    }
    
   
    protected function performAjaxValidation($model1, $model2)
    {
        if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenjualanbarang-form')
        {
            echo CActiveForm::validate(array($model1, $model2));
            Yii::app()->end();
        }
    }
         
}
