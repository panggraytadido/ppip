<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DatabelitunaiController extends Controller
{
    public $pageTitle = 'Keuangan - Data Beli Tunai';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Databelitunai('search');
        $model->unsetAttributes();

        if(isset($_GET['Databelitunai']))
                $model->attributes=$_GET['Databelitunai'];   
        $this->render('index',array(
                'model'=>$model,                  
        ));
    }    
    
    public function actionCreate()
    {
        $model = new Databelitunai;
        
        if(isset($_POST['Databelitunai']))
        {
            $this->performAjaxValidation($model);
        
            $model->attributes=$_POST['Databelitunai'];
            $valid = $model->validate();   

            if($valid)
            {                
                $model->tanggal=Yii::app()->DateConvert->ConvertTanggal($_POST['Databelitunai']['tanggal']);
                $model->pelangganid=$_POST['Databelitunai']['pelangganid'];
                $model->nama=$_POST['Databelitunai']['nama'];
                $model->jumlah=$_POST['Databelitunai']['jumlah'];             
                $model->harga=$_POST['Databelitunai']['harga'];              
                $model->total=$_POST['Databelitunai']['total'];              
                $model->createddate=date("Y-m-d H:", time());
                $model->userid = Yii::app()->user->id;         
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                        'id'=>$model->id
                    ));

                    Yii::app ()->user->setFlash ( 'success', "Data Beli Tunai Berhasil Ditambah." );
                    Yii::app()->end();
                }
            }
            else 
            {
                $error = CActiveForm::validate(array($model));
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
            $model = Databelitunai::model()->findByPk($id);              
            //$model->tanggal=$_POST['Datapengeluaran']['divisiid'];            
            $model->pelangganid=$_POST['Databelitunai']['pelangganid'];
            $model->nama=$_POST['Databelitunai']['nama'];
            $model->jumlah=$_POST['Databelitunai']['jumlah'];
            $model->total=$_POST['Databelitunai']['total'];
            $model->harga=$_POST['Databelitunai']['harga'];
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
    
    public function actionPopupupdate($id) {
            
        if (Yii::app ()->request->isAjaxRequest) {
            $this->layout = 'a';
            $model = Databelitunai::model ()->findByPk (intval($id));     
            
            $this->render ( 'update_form', array (
                            'model' => $model,                                    
            ), false, true );
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
		$model= Databelitunai::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
    
    protected function performAjaxValidation($model)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='form')
            {
                echo CActiveForm::validate(array($model));
                Yii::app()->end();
            }
        }
}    