<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class KaryawanbulananController extends Controller
{
    public $pageTitle = 'Keuangan - Karyawan Bulanan';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Karyawanbulanan('search');
        $model->unsetAttributes();

        if(isset($_GET['Karyawanbulanan']))
                $model->attributes=$_GET['Karyawanbulanan'];
        
        $this->render('index',array(
                'model'=>$model,                
        ));
    }
    
    /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{           
            $model=new Karyawanbulanan;            
            if(isset($_POST['Karyawanbulanan']))
            {	
                $model->attributes=$_POST['Karyawanbulanan'];
                    $valid = $model->validate();
                    if($valid)
                    {    			    			    		    			    	    			    	
                           //$model->attributes=$_POST['Databarang'];
                           //print("<pre>".print_r($_POST['Databarang'],true)."</pre>");	
                           //die;
						   
						   $check = Karyawanbulanan::model()->find('karyawanid='.$_POST['Karyawanbulanan']['karyawanid']);
						   if($check!="" || $check->gaji!=0)
						   {
							   $check->gaji=$_POST['Karyawanbulanan']['gaji'];
							   if($check->save()) 
								{
									echo CJSON::encode(array
									(
										'result'=>'OK',
									));    	
									
									
									Yii::app ()->user->setFlash ( 'success', "Data Gaji Karyawan Bulanan Berhasil diTambahkan" );
									Yii::app()->end();
								}
						   }
						   else{
								$model->jabatanid=$_POST['Karyawanbulanan']['jabatanid'];
								$model->karyawanid=$_POST['Karyawanbulanan']['karyawanid'];
								$model->gaji=$_POST['Karyawanbulanan']['gaji'];
								$model->createddate=date("Y-m-d H:", time());
								$model->userid = Yii::app()->user->id;
								if($model->save()) 
								{
									echo CJSON::encode(array
									(
										'result'=>'OK',
									));    	
									 Yii::app ()->user->setFlash ( 'success', "Data Gaji Karyawan Bulanan Berhasil diTambahkan" );
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
			$this->layout='a';
            $this->render('_form',array(
                    'model'=>$model,                         
            ), false, true );
	}
	
	
	public function actionCheckexist()
	{
		$model = Karyawanbulanan::model()->find("karyawanid=".$_POST['karyawanid']);
		if($model->gaji!=0)
		{
			echo CJSON::encode(array
			(
				'result'=>1,
			)); 
		}
		else{
			 echo CJSON::encode(array
			(
				'result'=>0,
			)); 
		}
	}
        
         public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $model = Karyawanbulanan::model ()->findByPk ($id);                
                    $this->render ( 'update_form', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
        
         /**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
            if (Yii::app ()->request->isAjaxRequest) {
				$model=Karyawanbulanan::model()->findByPk($id);
                $model->gaji=$_POST["Karyawanbulanan"]["gaji"]; 
                $model->karyawanid=$_POST["Karyawanbulanan"]["karyawanid"]; 
                $model->updateddate=date("Y-m-d H:", time());;                
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Gaji Karyawan Bulanan Berhasil diUpdate" );
                    Yii::app()->end();
                }
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
		$model=  Databarang::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
}    