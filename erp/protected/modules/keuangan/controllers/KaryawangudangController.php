<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class KaryawangudangController extends Controller
{
    public $pageTitle = 'Gudang - Penjualan Barang';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $modelPenerimaan=new Karyawangudangpenerimaan('search');
        $modelPenerimaan->unsetAttributes();

        if(isset($_GET['Karyawangudangpenerimaan']))
                $modelPenerimaan->attributes=$_GET['Karyawangudangpenerimaan'];
        
        $modelPenjualan=new Karyawangudangpenjualan('search');
        $modelPenjualan->unsetAttributes();

        if(isset($_GET['Karyawangudangpenjualan']))
                $modelPenjualan->attributes=$_GET['Karyawangudangpenjualan'];

        $this->render('index',array(
                'modelPenerimaan'=>$modelPenerimaan,
                'modelPenjualan'=>$modelPenjualan,
        ));
    }
    
    /**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{           
            $model=new Databarang;
            $model->attributes=$_POST['Databarang'];
            if(isset($_POST['Databarang']))
            {	
                
                    $valid = $model->validate();
                    if($valid)
                    {    			    			    		    			    	    			    	
                           //$model->attributes=$_POST['Databarang'];
                           //print("<pre>".print_r($_POST['Databarang'],true)."</pre>");	
                           //die;
                            $model->divisiid=$_POST['Databarang']['divisiid'];
                            $model->kode=$_POST['Databarang']['kode'];
                            $model->nama=$_POST['Databarang']['nama'];
                            $model->hargagrosir=$_POST['Databarang']['hargagrosir'];
                            $model->hargaeceran=$_POST['Databarang']['hargaeceran'];
                            $model->hargamodal=$_POST['Databarang']['hargamodal'];
                            if($model->save()) 
                            {
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',
                                ));    	
                                 Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diTambahkan" );
                                Yii::app()->end();
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
	}
        
        public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $model = Databarang::model ()->findByPk ($id);                
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
		$model=Databarang::model()->findByPk($id);
                $model->kode=$_POST["Databarang"]["kode"]; 
                $model->divisiid=$_POST["Databarang"]["divisiid"]; 
                $model->nama=$_POST["Databarang"]["nama"]; 
                $model->hargaeceran=$_POST["Databarang"]["hargaeceran"]; 
                $model->hargagrosir=$_POST["Databarang"]["hargagrosir"]; 
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diUpdate" );
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