<?php

class DataabsensiController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

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
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$model=new Dataabsensi('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Dataabsensi']))
			$model->attributes=$_GET['Dataabsensi'];

		$this->render('index',array(
			'model'=>$model,
		));
	}
	
	public function actionCreate()
	{
		$model=new Dataabsensi;            
		if(isset($_POST['Dataabsensi']))
		{	                
				$model->attributes=$_POST['Dataabsensi'];
				$valid = $model->validate();
				if($valid)
				{                            
					   //$model->attributes=$_POST['Databarang'];
					   //print("<pre>".print_r($_POST['Databarang'],true)."</pre>");	                           
						$model = new Barang;
						$model->divisiid=$_POST['Crudbarang']['divisiid'];
						$modelBarang->kode=$_POST['Crudbarang']['kode'];
						$modelBarang->nama=$_POST['Crudbarang']['nama'];
						if($modelBarang->save())
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
						
		}
		else 
		{
			$this->layout='a';
			$this->render('_form',array(
					'model'=>$model,                               
			), false, true );

			Yii::app()->end();

		}  
	}

}
