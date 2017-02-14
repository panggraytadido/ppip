<?php

class LokasiController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
        public $pageTitle = 'Admin - Lokasi Pabrik';

	/**
	 * @return array action filters
	
	public function filters()
	{
            /*
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
		);
            
	}
         */
        
	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}
        
        public function actionTe()
        {
            /*
            $criteria = new CDbCriteria;
            $criteria->select='id';
            $criteria->distinct=true;
            $barang = Barang::model()->findAll($criteria);            
            for($i=0;$i<count($barang);$i++)
            {
               
            }
             * 
             */
            $criteria = new CDbCriteria;
            $criteria->select='barangid,supplierid';
            $criteria->distinct=true;
            $barang = Stocksupplier::model()->findAll($criteria);            
            print("<pre>".print_r($barang,true)."</pre>");	
            //print(count($barang));
        }

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	*/
	public function actionCreate()
	{           
            $model=new Lokasi;
            $model->attributes=$_POST['Lokasi'];
            if(isset($_POST['Lokasi']))
            {	
                    $valid = $model->validate();
                    if($valid)
                    {    			    			    		    			    	    			    	
                           $model->attributes=$_POST['Lokasi'];
                           $model->createddate=date("Y-m-d H:i:s", time());
                           $model->userid=Yii::app()->user->id;
                            if($model->save()) 
                            {
                                $criteria = new CDbCriteria;
                                $criteria->select='id';
                                $criteria->distinct=true;
                                $stock = Barang::model()->findAll($criteria);            
                                for($i=0;$i<count($stock);$i++)
                                {
                                    $modelStock = new Stock;
                                    
                                    $modelStock->barangid=$stock[$i]['id'];
                                    $modelStock->jumlah=0;
                                    $modelStock->lokasipenyimpananbarangid=$model->id;
                                    $modelStock->tanggal=date("Y-m-d H:i:s", time());
                                    $modelStock->createddate=date("Y-m-d H:i:s", time());
                                    $modelStock->userid=Yii::app()->user->id; 
                                    $modelStock->save();
                                    
                                }
                                
                                $criteria = new CDbCriteria;
                                $criteria->select='barangid,supplierid';
                                $criteria->distinct=true;
                                $stockSupplier = Stocksupplier::model()->findAll($criteria);            
                                for($i=0;$i<count($stockSupplier);$i++)
                                {
                                    $modelStockSupp = new Stocksupplier;
                                    
                                    $modelStockSupp->barangid=$stockSupplier[$i]['barangid'];
                                    $modelStockSupp->supplierid=$stockSupplier[$i]['supplierid'];
                                    $modelStockSupp->jumlah=0;
                                    $modelStockSupp->lokasipenyimpananbarangid=$model->id;                                    
                                    $modelStockSupp->createddate=date("Y-m-d H:i:s", time());
                                    $modelStockSupp->userid=Yii::app()->user->id; 
                                    $modelStockSupp->save();
                                    
                                }
                                
                                
                                echo CJSON::encode(array
                                (
                                    'result'=>'OK',
                                ));    	
                                 Yii::app ()->user->setFlash ( 'success', "Data Lokasi Berhasil diTambahkan" );
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

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
            if (Yii::app ()->request->isAjaxRequest) {
		$model=Lokasi::model()->findByPk($id);                                
                $model->nama=$_POST["Lokasi"]["nama"];                 
                if($model->save())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Lokasi Berhasil diUpdate" );
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

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Lokasi('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Lokasi']))
			$model->attributes=$_GET['Lokasi'];

		$this->render('index',array(
			'model'=>$model,
		));
	}

	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Bagian the loaded model
	 * @throws CHttpException
	 */
	public function loadModel($id)
	{
		$model= Lokasi::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Bagian $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='bagian-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
        
        public function actionPopupupdate($id) {
            
		if (Yii::app ()->request->isAjaxRequest) {
                    $this->layout = 'a';
                    $model = Lokasi::model ()->findByPk ($id);                
                    $this->render ( 'update_form', array (
                                    'model' => $model,                                    
                    ), false, true );
                    Yii::app()->end();
		}
	}
}
