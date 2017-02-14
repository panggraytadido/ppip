<?php

class DatapendapatanController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
         public $pageTitle = 'Admin - Data Pendapatan';

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
		$model=new Datapendapatan;
		$model->unsetAttributes();  // clear any default values
		
		$this->render('index',array(
			'model'=>$model,
		));
	}
        
        public function actionSubmit()
        {
            $model=new Datapendapatan;
            $model->attributes=$_POST['Datapendapatan'];
            if(isset($_POST['Datapendapatan']))
            {
                $valid = $model->validate();
                    if($valid)
                    {
                        if($_POST['Datapendapatan']['pilihan']==1)
                        {
                            $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Datapendapatan']['tanggal1']);
                            echo CJSON::encode(array('result'=>'OK','pilihan'=>1,'divisi'=>$_POST['Datapendapatan']['divisi'],'tanggal'=>$tanggal));                              	
                        }
                        else
                        {
                            $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Datapendapatan']['tanggal2']);
                            echo CJSON::encode(array('result'=>'OK','pilihan'=>2,'divisi'=>0,'tanggal'=>$tanggal));       
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
        }
        
        public function actionView($tanggal,$pilihan,$divisi)
        {
            //per divisi
            if($pilihan==1)
            {
                $model=new Pendapatanperdivisi('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pendapatanperdivisi']))
			$model->attributes=$_GET['Pendapatanperdivisi'];

                
                $this->render('pendapatan_per_divisi',array(
			'divisi'=>$divisi,
                        'tanggal'=>$tanggal,
                        'pilihan'=>$pilihan,
                        'model'=>$model
		));
            }
            //all divisi
            else
            {
                $model=new Pendapatanalldivisi('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Pendapatanalldivisi']))
			$model->attributes=$_GET['Pendapatanalldivisi'];
                
                 $this->render('pendapatan_all_divisi',array(
			'divisi'=>$divisi,
                        'tanggal'=>$tanggal,
                        'pilihan'=>$pilihan,
                        'model'=>$model
		));
            }
                      
        }
        
        public function actionSimpanAllDivisi()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                $tanggal = $_POST['tanggal'];              
                $cekData = Pendapatanalldivisi::model()->find("cast(tanggal as date)='$tanggal'");
                if(count($cekData)!=0)
                {
                    $model = Pendapatanalldivisi::model()->find("cast(tanggal as date)='$tanggal'");                    
                    $model->tanggal =$_POST['tanggal'];
                    $model->totalpenjualan=$_POST['totalpenjualan'];
                    $model->totalmodal=$_POST['totalmodal'];
                    $model->totallaba=$_POST['totallaba'];
                    $model->totaldiskon=$_POST['totaldiskon'];
                    $model->totalpiutang=$_POST['totalpiutang'];
                    $model->totalpengeluaran=$_POST['totalpengeluaran'];
                    $model->createddate=date("Y-m-d H:i:s", time());
                    $model->userid=Yii::app()->user->id;          
                    if($model->save())
                    {
                        echo CJSON::encode(array
                        (
                            'result'=>'OK',
                        ));    	
                        Yii::app ()->user->setFlash ( 'success', "Data Pendapatan Berhasil diTambahkan" );
                        Yii::app()->end();
                    }
                }
                else
                {
                    $model = new Pendapatanalldivisi;
                    $model->tanggal =$_POST['tanggal'];
                    $model->totalpenjualan=$_POST['totalpenjualan'];
                    $model->totalmodal=$_POST['totalmodal'];
                    $model->totallaba=$_POST['totallaba'];
                    $model->totaldiskon=$_POST['totaldiskon'];
                    $model->totalpiutang=$_POST['totalpiutang'];
                    $model->totalpengeluaran=$_POST['totalpengeluaran'];
                    $model->createddate=date("Y-m-d H:i:s", time());
                    $model->userid=Yii::app()->user->id;          
                    if($model->save())
                    {
                        echo CJSON::encode(array
                        (
                            'result'=>'OK',
                        ));    	
                        Yii::app ()->user->setFlash ( 'success', "Data Pendapatan Berhasil diTambahkan" );
                        Yii::app()->end();
                    }
                }                                    
            }
        }
        
        public function actionSimpanPerDivisi()
        {
            if (Yii::app ()->request->isAjaxRequest) {
                $tanggal = $_POST['tanggal'];
                $divisiid = $_POST['divisiid'];
                $cekData = Pendapatanperdivisi::model()->find("cast(tanggal as date)='$tanggal'");
                if(count($cekData)!=0)
                {
                    $model = Pendapatanperdivisi::model()->find("cast(tanggal as date)='$tanggal' AND divisiid=".$divisiid);
                    $model->namadivisi =$_POST['divisi'];
                    $model->divisiid =$_POST['divisiid'];
                    $model->tanggal =$_POST['tanggal'];
                    $model->totalpenjualan=$_POST['totalpenjualan'];
                    $model->totalmodal=$_POST['totalmodal'];
                    $model->totallaba=$_POST['totallaba'];
                    $model->totalpengeluaran=$_POST['totalpengeluaran'];
                    $model->createddate=date("Y-m-d H:i:s", time());
                    $model->userid=Yii::app()->user->id;          
                    if($model->save())
                    {
                        echo CJSON::encode(array
                        (
                            'result'=>'OK',
                        ));    	
                        Yii::app ()->user->setFlash ( 'success', "Data Pendapatan Berhasil diTambahkan" );
                        Yii::app()->end();
                    }
                }
                else
                {
                    $model = new Pendapatanperdivisi;
                    $model->namadivisi =$_POST['divisi'];
                    $model->divisiid =$_POST['divisiid'];
                    $model->tanggal =$_POST['tanggal'];
                    $model->totalpenjualan=$_POST['totalpenjualan'];
                    $model->totalmodal=$_POST['totalmodal'];
                    $model->totallaba=$_POST['totallaba'];
                    $model->totalpengeluaran=$_POST['totalpengeluaran'];
                    $model->createddate=date("Y-m-d H:i:s", time());
                    $model->userid=Yii::app()->user->id;          
                    if($model->save())
                    {
                        echo CJSON::encode(array
                        (
                            'result'=>'OK',
                        ));    	
                        Yii::app ()->user->setFlash ( 'success', "Data Pendapatan Berhasil diTambahkan" );
                        Yii::app()->end();
                    }
                }                                    
            }
        }
        
        public function actionDeletePerDivisi($id)
        {
            $model = Pendapatanperdivisi::model()->findByPk($id);
             if($model->delete())
             {
                 Yii::app ()->user->setFlash ( 'success', "Data Pendapatan berhasil dihapus" );
                 $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
             }
        }

	
}
