<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DatatabunganController extends Controller
{
    public $pageTitle = 'Keuangan - Data Tabungan';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Datatabungan('search');
        $model->unsetAttributes();

        if(isset($_GET['Datatabungan']))
                $model->attributes=$_GET['Datatabungan'];   
        
        $this->render('index',array(
                'model'=>$model,                  
        ));
    }
    
    
    public function actionCreate()
    {
       $model=new Datatabungan();
        
        $this->performAjaxValidation($model);
        
        if(isset($_POST['Datatabungan']))
        {
            $model->attributes=$_POST['Datatabungan'];
            $valid = $model->validate();   

            if($valid)
            {                      
                
                $checkTabungan = Datatabungan::model()->find('pelangganid='.$_POST['Datatabungan']['pelangganid']);                                
                if($checkTabungan!="")
                {
                    //echo $checkTabungan->jumlah;
                    //die;
                    $tabunganId = $checkTabungan->id;
                    $model = new Jumlahtabungan;                    
                    $model->tabunganid=$tabunganId;
                    $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Datatabungan']['tanggal']);
                    $model->jumlah = $_POST['Datatabungan']['jumlah'];       
                    $model->createddate=date("Y-m-d H:", time());
                    $model->userid = Yii::app()->user->id;
                    if($model->save(false))
                    {
                        $checkTabungan->jumlah = $checkTabungan->jumlah + $_POST['Datatabungan']['jumlah'];
                        if($checkTabungan->save(false))
                        {
                            echo CJSON::encode(array
                            (
                                'result'=>'OK',                                
                            ));

                            Yii::app ()->user->setFlash ( 'success', "Data Tabungan Berhasil Ditambah." );
                            Yii::app()->end();
                        }
                    }                                        
                }
                else
                {
                    //print("<pre>".print_r($checkTabungan,true)."</pre>");
                    //die;
                    $model1 = new Datatabungan;                                                
                    $model1->pelangganid=$_POST['Datatabungan']['pelangganid'];
                    $model1->jumlah=$_POST['Datatabungan']['jumlah'];                    
                    $model1->createddate=date("Y-m-d H:", time());
                    $model1->userid = Yii::app()->user->id;

                    if($model1->save(false))
                    {
                        $model2 = new Jumlahtabungan;
                        $model2->tabunganid=$model1->id;
                        $model2->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Datatabungan']['tanggal']);
                        $model2->jumlah = $_POST['Datatabungan']['jumlah'];       
                        $model2->createddate=date("Y-m-d H:", time());
                        $model2->userid = Yii::app()->user->id;
                        if($model2->save())
                        {
                            echo CJSON::encode(array
                            (
                                'result'=>'OK',
                                'id'=>$model1->id
                            ));

                            Yii::app ()->user->setFlash ( 'success', "Data Tabungan Berhasil Ditambah." );
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
        else 
        {
            $this->layout='a';
            $this->render('_form',array(
                    'model'=>$model,                         
            ), false, true );
        }
    }
    
    public function actionDetail()
    {
        $id = Yii::app()->getRequest()->getParam('id');                            
                    
        $data = Jumlahtabungan::model()->findAll('tabunganid='.$id);
        
        // partially rendering "_relational" view
        $this->renderPartial('detail', array(
                        'id' => Yii::app()->getRequest()->getParam('id'),
                        'child' => $data,
                        'barangid'=>$id
        ));         
    }

    public function actionAmbil()
    {
        //print("<pre>".print_r($_POST,true)."</pre>");
        if (Yii::app ()->request->isAjaxRequest) {
            if($_POST['jumlahambil']=='')
            {
                echo CJSON::encode(array
                (
                    'result'=>'Masukan Jumlah yang diambil',        
                ));
            }
            else
            {
                $data = Datatabungan::model()->find('pelangganid='.intval($_POST['pelangganid']));
                $data->jumlah = $data->jumlah - $_POST['jumlahambil'];
                if($data->save(false))
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',                        
                    ));
                    Yii::app ()->user->setFlash ( 'success', "Tabungan Berhasil Diambil." );
                    Yii::app()->end();
                }
            }
        }
    }

    public function actionPopupambiltabungan($id) {
            
        if (Yii::app ()->request->isAjaxRequest) {
            $this->layout = 'a';
            $model = Datatabungan::model ()->findByPk (intval($id));                
            $this->render ( 'ambil_form', array (
                            'model' => $model,                                    
            ), false, true );
            Yii::app()->end();
        }
    }                       
    
    protected function performAjaxValidation($model)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
        
}    