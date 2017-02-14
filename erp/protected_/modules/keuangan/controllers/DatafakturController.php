<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DatafakturController extends Controller
{
    public $pageTitle = 'Keuangan - Data Faktur';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Datafaktur('search');
        $model->unsetAttributes();

        if(isset($_GET['Datafaktur']))
                $model->attributes=$_GET['Datafaktur'];   
        $this->render('index',array(
                'model'=>$model,                  
        ));
    }
    
    public function actionCetakFaktur()
    {
        $this->render('cetak_faktur',array(
                //'model'=>$model,                  
        ));
    }
    
    public function actionCheckFaktur($pelangganid,$tanggal,$nofaktur)
    {
        echo CJSON::encode(array('status'=>1, 'pelangganid'=>$pelangganid,'tanggal'=>$tanggal,'nofaktur'=>$nofaktur));  
    }
                
}    