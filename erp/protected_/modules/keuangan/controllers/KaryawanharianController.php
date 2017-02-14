<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class KaryawanharianController extends Controller
{
    public $pageTitle = 'Keuangan - Karyawan Harian';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Karyawanharian('search');
        $model->unsetAttributes();

        if(isset($_GET['Karyawanharian']))
                $model->attributes=$_GET['Karyawanharian'];
        
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
            $model=new Karyawanharian;            
            if(isset($_POST['Karyawanharian']))
            {	
					$model->attributes=$_POST['Karyawanharian'];
                    $valid = $model->validate();					
                    if($valid)
                    {    	
			
                           //$model->attributes=$_POST['Databarang'];
                           //print("<pre>".print_r($_POST['Databarang'],true)."</pre>");	
                           //die;
						   
						   $check = Karyawanharian::model()->find('karyawanid='.$_POST['Karyawanharian']['karyawanid']);
						   if($check!="" || $check->gaji!=0)
						   {
							   $check->gaji=$_POST['Karyawanharian']['gaji'];
							   if($check->save()) 
								{
									echo CJSON::encode(array
									(
										'result'=>'OK',
									));    	
									
									
									Yii::app ()->user->setFlash ( 'success', "Data Gaji Karyawan Harian Berhasil diTambahkan" );
									Yii::app()->end();
								}
						   }
						   else{
								$model->jabatanid=$_POST['Karyawanharian']['jabatanid'];
								$model->karyawanid=$_POST['Karyawanharian']['karyawanid'];
								$model->gaji=$_POST['Karyawanharian']['gaji'];
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
		$model = Karyawanharian::model()->find("karyawanid=".$_POST['karyawanid']);
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
                    $model = Karyawanharian::model ()->findByPk ($id);                
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
				$model=Karyawanharian::model()->findByPk($id);
                $model->gaji=$_POST["Karyawanharian"]["gaji"]; 
                $model->karyawanid=$_POST["Karyawanharian"]["karyawanid"]; 
                $model->updateddate=date("Y-m-d H:", time());;                
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Gaji Karyawan Harian Berhasil diUpdate" );
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