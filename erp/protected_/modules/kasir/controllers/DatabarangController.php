<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DatabarangController extends Controller
{
    public $pageTitle = 'Gudang - Penjualan Barang';
    
    public function filters()
    {
            return array(

            );
    }
    
    public function actionIndex()
    {
        $model=new Databarang('search');
        $model->unsetAttributes();

        if(isset($_GET['Databarang']))
                $model->attributes=$_GET['Databarang'];

        $this->render('index',array(
                'model'=>$model,
        ));
    }
    
    public function actionDetailBarang()
    {
           $id = Yii::app()->getRequest()->getParam('id');         
           
           /*
            $criteria=new CDbCriteria;
            $critetia->select='DISTINCT supplierid,barangid,lokasipenyimpanabarangid';
            $criteria->condition = 'barangid ='.$id;                        
            $criteria->order='supplierid asc';
        
           $child = Stocksupplier::model()->findAll($criteria);	
           // print("<pre>".print_r($child,true)."</pre>");	
            * 
            */
           
           $connection=Yii::app()->db;
            $sql ="SELECT distinct supplierid,barangid FROM transaksi.stocksupplier t WHERE
                    t.barangid =$id";
            
            $data=$connection->createCommand($sql)->queryAll();
            
           
           //die;
            // partially rendering "_relational" view
            $this->renderPartial('detail', array(
                            'id' => Yii::app()->getRequest()->getParam('id'),
                            'child' => $data,
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
                            $modelBarang = new Barang;
                            $modelBarang->divisiid=$_POST['Databarang']['divisiid'];
                            $modelBarang->kode=$_POST['Databarang']['kode'];
                            $modelBarang->nama=$_POST['Databarang']['nama'];
                            if($modelBarang->save())
                            {
                                $modelHargaBarang = new Hargabarang;
                                $modelHargaBarang->barangid=$modelBarang->id;
                                $modelHargaBarang->supplierid=$_POST['Databarang']['supplierid'];
                                $modelHargaBarang->hargamodal=$_POST['Databarang']['hargamodal'];
                                $modelHargaBarang->hargaeceran=$_POST['Databarang']['hargaeceran'];
                                $modelHargaBarang->hargagrosir = $_POST['Databarang']['hargagrosir'];    
                                $modelHargaBarang->createddate=date("Y-m-d H:", time());
                                $modelHargaBarang->userid=Yii::app()->user->id;
                                if($modelHargaBarang->save()) 
                                {
                                    $lokasi = Lokasipenyimpananbarang::model()->findAll();
                                    foreach($lokasi as $row)
                                    {
                                        $modelStock = new Stock;
                                        $modelStock->barangid=$modelBarang->id;
                                        $modelStock->jumlah=0;
                                        $modelStock->lokasipenyimpananbarangid=$row["id"];
                                        $modelStock->tanggal=date("Y-m-d H:i:s", time());
                                        $modelStock->createddate=date("Y-m-d H:i:s", time());
                                        $modelStock->userid=Yii::app()->user->id;          
                                        $modelStock->save();
                                    }
                                        
                               }
                                    echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                    ));    	
                                     Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diTambahkan" );
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
        
        public function actionFormUbahHarga($supplierid,$barangid)
        {
            
            $this->render ( 'form_ubah_harga', array (
                                    'supplierid' => $supplierid,
                                    'barangid' => $barangid,
                    ), false, true );
        }
        
        public function actionUpdateBarang()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                
                $supplierid = $_POST['supplierid'];
                $barangid = $_POST['barangid'];
                $hargamodal = $_POST['hargamodal'];
                $hargaeceran = $_POST['hargaeceran'];
                $hargagrosir = $_POST['hargagrosir'];
                
                $connection =Yii::app()->db;
                $sql = "update transaksi.hargabarang set hargamodal=$hargamodal,hargaeceran=$hargaeceran,hargagrosir=$hargagrosir where supplierid=$supplierid and  barangid=$barangid";
                $command = $connection->createCommand($sql);
                if($command->execute())
                {
                    echo CJSON::encode(array
                    (
                        'result'=>'OK',
                    ));    
                    Yii::app ()->user->setFlash ( 'success', "Data Barang Berhasil diubah" );
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