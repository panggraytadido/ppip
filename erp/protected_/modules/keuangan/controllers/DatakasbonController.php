<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DatakasbonController extends Controller
{
    public $pageTitle = 'Keuangan - Data Kasbon';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Datakasbon('search');
        $model->unsetAttributes();

        if(isset($_GET['Datakasbon']))
                $model->attributes=$_GET['Datakasbon'];   
        $this->render('index',array(
                'model'=>$model,                  
        ));
    }
    
    
    public function actionCreate()
    {
       $model=new Datakasbon();
        
        $this->performAjaxValidation($model);
        
        if(isset($_POST['Datakasbon']))
        {
            $model->attributes=$_POST['Datakasbon'];
            $valid = $model->validate();   

            if($valid)
            {                        
                $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Datakasbon']['tanggal']);
                $model->karyawanid=$_POST['Datakasbon']['karyawanid'];
                $model->jumlah=$_POST['Datakasbon']['jumlah'];
                $model->keterangan=$_POST['Datakasbon']['keterangan'];
                $model->createddate=date("Y-m-d H:", time());
                $model->userid = Yii::app()->session['divisiid'];

                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                        'id'=>$model->id
                    ));

                    Yii::app ()->user->setFlash ( 'success', "Data Kasbon Berhasil Ditambah." );
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
        else 
        {
            $this->layout='a';
            $this->render('_form',array(
                    'model'=>$model,                         
            ), false, true );
        }
    }
    
    //update
    public function actionUpdate($id)
    {
        if (Yii::app ()->request->isAjaxRequest) {
            $model = Kasbon::model()->findByPk($id);
            $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Datakasbon']['tanggal']);
            $model->karyawanid=$_POST['Dataperjalanan']['karyawanid'];
            $model->jumlah=$_POST['Datakasbon']['jumlah'];
            $model->keterangan=$_POST['Datakasbon']['keterangan'];
            $model->createddate=date("Y-m-d H:", time());
            $model->userid = Yii::app()->session['divisiid'];
            
            if($model->save())
            {
                echo CJSON::encode(array
                (
                    'result'=>'OK',
                    'id'=>$model->id
                ));
                
                Yii::app ()->user->setFlash ( 'success', "Data Kasbon Berhasil Diupdate." );
                Yii::app()->end();
            }
            
        }
    }
        
    public function actionView($id)
    {
        $model = Kasbon::model()->findByPk($id);        

        $this->render('view',array(
                'model'=>$model,                
        ));
    }
       
    public function actionPopupupdate($id) {
            
        if (Yii::app ()->request->isAjaxRequest) {
            $this->layout = 'a';
            $model = Kasbon::model ()->findByPk (intval($id));                
            $this->render ( 'update_form', array (
                            'model' => $model,                                    
            ), false, true );
            Yii::app()->end();
        }
    }
        
    public function actionPopupbayar($id) {
            
        if (Yii::app ()->request->isAjaxRequest) {
            $this->layout = 'a';
            $modelPembayaranKasbon = new Pembayarankasbon;
            $model = Kasbon::model ()->findByPk (intval($id));                
            $this->render ( 'form_bayar', array (
                            'model' => $model,    
                            'modelPembayaranKasbon' => $modelPembayaranKasbon,    
            ), false, true );
            Yii::app()->end();
        }
    }    
    
    
    public function actionBayar($id)
    {
        if (Yii::app ()->request->isAjaxRequest) {            
           if($_POST['bayarinput']!='' && $_POST['Pembayarankasbon']['tanggalbayar']!='')
           {
                $model = new Pembayarankasbon;
                $model->tanggalbayar=Yii::app()->DateConvert->ConvertTanggal($_POST['Pembayarankasbon']['tanggalbayar']);           
                $model->createddate=date("Y-m-d H:", time());
                $model->userid = Yii::app()->session['divisiid'];
                $model->jumlah = $_POST['bayarinput'];
                $model->kasbonid=$id;
                if($model->save())
                {
                    $dataKasbon = Datakasbon::model()->findByPk($id);
                    $dataBayar = Datakasbon::model()->getTotalBayar($id); 
                    if($dataBayar==$dataKasbon->jumlah)
                    {
                        $dataKasbon->status=TRUE;
                        if($dataKasbon->save())
                        {
                             echo CJSON::encode(array
                             (
                                 'result'=>'LUNAS',
                                 'id'=>$id
                             ));

                             Yii::app ()->user->setFlash ( 'success', "Data Kasbon Lunas." );
                             Yii::app()->end();
                        }                   
                    }
                    else 
                    {
                        echo CJSON::encode(array
                         (
                             'result'=>'OK',
                             'id'=>$id
                         ));

                         Yii::app ()->user->setFlash ( 'success', "Data Pembayaran Kasbon berhasil ditambah." );
                         Yii::app()->end();
                    }

                }
           }
           else 
           {
               echo CJSON::encode(array
                (
                    'result'=>'salah',                    
                ));
           }                    
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
    
    /**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}
        
        public function loadModel($id)
	{
		$model=  Datakasbon::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
        
}    