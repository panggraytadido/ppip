<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DatapengeluaranController extends Controller
{
    public $pageTitle = 'Keuangan - Data Pengeluaran';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Datapengeluaran('search');
        $model->unsetAttributes();

        if(isset($_GET['Datapengeluaran']))
                $model->attributes=$_GET['Datapengeluaran'];   
        
        $this->render('index',array(
                'model'=>$model,                  
        ));
    }
    
    
    public function actionCreate()
    {
       $model=new Datapengeluaran;
        
        $this->performAjaxValidation($model);
        
        if(isset($_POST['Datapengeluaran']))
        {
            $model->attributes=$_POST['Datapengeluaran'];
            $valid = $model->validate();   
            
            if($valid)
            {                        
                                
                $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Datapengeluaran']['tanggal']);
                $model->divisiid=$_POST['Datapengeluaran']['divisiid'];            
                $model->hargasatuan=$_POST['Datapengeluaran']['hargasatuan'];
                $model->jumlah=$_POST['Datapengeluaran']['jumlah'];
                $model->total=$_POST['Datapengeluaran']['total'];
                $model->createddate=date("Y-m-d H:", time());
                $model->userid = Yii::app()->user->id;

                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                        'id'=>$model->id
                    ));

                    Yii::app ()->user->setFlash ( 'success', "Data Pengeluaran Berhasil Ditambah." );
                    Yii::app()->end();
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
        $this->render('_form',array(
                'model'=>$model,                
        ));
        
    }
    
    //update
    public function actionUpdate($id)
    {
        if (Yii::app ()->request->isAjaxRequest) {
            $model = Datapengeluaran::model()->findByPk($id);   
            //$model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Datapengeluaran']['tanggal']);
            $model->divisiid=$_POST['Datapengeluaran']['divisiid'];            
            $model->hargasatuan=$_POST['Datapengeluaran']['hargasatuan'];
            $model->nama=$_POST['Datapengeluaran']['nama'];
            $model->jumlah=$_POST['Datapengeluaran']['jumlah'];
            $model->total=$_POST['Datapengeluaran']['total'];
            $model->createddate=date("Y-m-d H:", time());
            $model->userid = Yii::app()->user->id;
            
            if($model->save())
            {
                echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'id'=>$model->id
                ));
                
                Yii::app ()->user->setFlash ( 'success', "Data Pengeluaran Berhasil Diupdate." );
                Yii::app()->end();
            }
            
        }
    }
        
    public function actionView($id)
    {
        $model = Datapengeluaran::model()->findByPk($id);        

        $this->render('view',array(
                'model'=>$model,                
        ));
    }
       
    public function actionPopupupdate($id) {
            
        if (Yii::app ()->request->isAjaxRequest) {
            $this->layout = 'a';
            $model = Datapengeluaran::model ()->findByPk (intval($id));                
            $this->render ( 'update_form', array (
                            'model' => $model,                                    
            ), false, true );
            Yii::app()->end();
        }
    }                       
    
    protected function performAjaxValidation($model)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='dataperjalanan-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
        
}    