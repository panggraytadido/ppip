<?php

class PenjualanbarangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Besek - Penjualan Barang';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {            
            $model=new Besekpenjualanbarang('search');
            $model->unsetAttributes();
            if(isset($_GET['Besekpenjualanbarang']))
                $model->attributes=$_GET['Besekpenjualanbarang'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionCreate()
        {
            $modelPenjualan = new Besekpenjualanbarang;

            $this->performAjaxValidation($modelPenjualan);

            if(isset($_POST['Besekpenjualanbarang']))
            {
                $model=new Besekpenjualanbarang;
                $_POST['Besekpenjualanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                $_POST['Besekpenjualanbarang']['statuspenjualan'] = 0;
                
                $model->attributes=$_POST['Besekpenjualanbarang'];
                
                $valid = $model->validate();

                if($valid)
                {
                    $createddate = date("Y-m-d H:", time());
                    
                    $_POST['Besekpenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Besekpenjualanbarang']['tanggal']);
                    $_POST['Besekpenjualanbarang']['createddate'] = $createddate;
                    $_POST['Besekpenjualanbarang']['userid'] = Yii::app()->user->id;

                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Besekpenjualanbarang']['barangid'].' and supplierid='.$_POST['Besekpenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Besekpenjualanbarang']['labarugi'] = $_POST['Besekpenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Besekpenjualanbarang']['jumlah']);
                    
                    $_POST['Besekpenjualanbarang']['netto'] = $_POST['Besekpenjualanbarang']['jumlah'];
//                    $_POST['Besekpenjualanbarang']['jumlah'] = 0;

                    $model->attributes=$_POST['Besekpenjualanbarang'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {						
						$stocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Besekpenjualanbarang']['barangid'].' and lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'].' and supplierid='.$_POST['Besekpenjualanbarang']['supplierid'])->jumlah;
						if($_POST['Besekpenjualanbarang']['jumlah']>$stocksupplier)
						{
							echo CJSON::encode(array
											(
												'result'=>'FALSE',
												'stock'=>$stocksupplier,
												'jumlahpenjualan'=>$_POST['Besekpenjualanbarang']['jumlah']
												
											));
																				
							Yii::app()->end();
						}
						else
						{
							if($model->save())
							{
								$modelStockout = new Stockout;
								$modelStockout->barangid = $_POST['Besekpenjualanbarang']['barangid'];
								$modelStockout->jumlah = $_POST['Besekpenjualanbarang']['jumlah'];
								$modelStockout->tanggal = $_POST['Besekpenjualanbarang']['tanggal'];
								$modelStockout->createddate = $createddate;
								$modelStockout->userid = Yii::app()->user->id;
								$modelStockout->penjualanbarangid = $model->getPrimaryKey();
								$modelStockout->save();

								echo CJSON::encode(array
										(
											'result'=>'OK',
											'penjualanid'=>$model->getPrimaryKey()
										));
								Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Ditambah." );
								Yii::app()->end();
							}
                        
						}						
                        
                    }
                    else
                    {
                        $idnya = $model->getId();

                        echo CJSON::encode(array
                                    (
                                        'result'=>'OK',
                                        'penjualanid'=>$idnya
                                    ));
                            Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Ditambah." );
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
                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }

            $this->layout='a';
            $this->render('_form',array(
                    'modelPenjualan'=>$modelPenjualan
            ), false, true );
            
            Yii::app()->end();
        }
 
        public function actionUpdate($id)
        {
            $modelPenjualan = Besekpenjualanbarang::model()->findByPk($id);

            $modelPenjualan->tanggal = date("m/d/Y", strtotime($modelPenjualan->tanggal));
            
            $this->performAjaxValidation($modelPenjualan);
            
            if(isset($_POST['Besekpenjualanbarang']))
            {
                $_POST['Besekpenjualanbarang']['statuspenjualan'] = ($modelPenjualan->statuspenjualan==false) ? 0 : 1;
            
                $modelPenjualan->attributes=$_POST['Besekpenjualanbarang'];
                $valid = $modelPenjualan->validate();

                if($valid)
                {
                    // set nilai variable
                    $barangidLama = $modelPenjualan->barangid;
                    $jumlahLama = $modelPenjualan->jumlah;
                    $createddate = $_POST['waktuinsert'];
                    $updatedate = $_POST['waktuupdate'];
                    
                    // set data penjualan barang
                    $_POST['Besekpenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Besekpenjualanbarang']['tanggal']);
                    $_POST['Besekpenjualanbarang']['updateddate'] = $updatedate;
                    
                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Besekpenjualanbarang']['barangid'].' and supplierid='.$_POST['Besekpenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Besekpenjualanbarang']['labarugi'] = $_POST['Besekpenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Besekpenjualanbarang']['jumlah']);
                    
                    $_POST['Besekpenjualanbarang']['netto'] = $_POST['Besekpenjualanbarang']['jumlah'];
                    
                    $modelPenjualan->attributes=$_POST['Besekpenjualanbarang'];
                    
                    if($modelPenjualan->save())
                    {
                        $modelStockout = Stockout::model()->find('penjualanbarangid='.$modelPenjualan->id);
                        $modelStockout->barangid = $_POST['Besekpenjualanbarang']['barangid'];
                        $modelStockout->jumlah = $_POST['Besekpenjualanbarang']['jumlah'];
                        $modelStockout->tanggal = $_POST['Besekpenjualanbarang']['tanggal'];
                        $modelStockout->updatedate = $updatedate;
                        $modelStockout->save();

                        echo CJSON::encode(array
                                (
                                    'result'=>'OK'
                                ));
                        Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Diupdate.");
                        Yii::app()->end();
                    }
                }
                else
                {
                        $error = CActiveForm::validate($modelPenjualan);
                        if($error!='[]')
                        {
                            echo $error;
                        }
                        
                        Yii::app()->end();
                }

                Yii::app()->end();
            }
            
            $this->layout='a';
            $this->render( '_form_update', array (
                            'modelPenjualan' => $modelPenjualan
            ), false, true );
            Yii::app()->end();
	}
 
        public function actionView($id)
        {
            $modelPenjualan = Besekpenjualanbarang::model()->findByPk($id);
            
            $this->render('view',array(
                    'modelPenjualan'=>$modelPenjualan,
            ));
        }
 
        public function actionHargabarang()
        {
            $barangid = $_POST['barangid'];
            $kategori = $_POST['kategori'];
            $supplierid = $_POST['supplierid'];
            
            $recBarang = Hargabarang::model()->find('barangid='.$barangid.' and supplierid='.$supplierid);
            
            if($kategori==1) // jika harga eceran
                echo $recBarang->hargaeceran;
            else
                echo $recBarang->hargagrosir;
        }

        public function actionCekstock()
        {
            $barangid = $_POST['barangid'];
            $lokasiid = $_POST['lokasiid'];
            $supplierid = $_POST['supplierid'];
            $aksi = $_POST['aksi'];
            $id = $_POST['id'];
                
            $criteria=new CDbCriteria;
            $criteria->select = 'sum(jumlah) as jumlah';
            $criteria->condition = 'statuspenjualan = false';
            $criteria->condition .= ' and barangid = '.$barangid.' and lokasipenyimpananbarangid='.$lokasiid.' and supplierid='.$supplierid;
            if($aksi=='update') // jika form update
                $criteria->condition .= ' and id != '.$id;
            $recAntrian = Penjualanbarang::model()->find($criteria);
            //$stockAntrian = ($recAntrian->jumlah!=null) ? $recAntrian->jumlah : 0;
            $stockGudang = Stocksupplier::model()->find('barangid='.$barangid.' and lokasipenyimpananbarangid='.$lokasiid.' and supplierid='.$supplierid)->jumlah;
            //$stockTersedia = ($stockGudang - $stockAntrian);
			$stockTersedia = $stockGudang;
            // =========================================
            
            echo CJSON::encode(array(
                    'stockTersedia' => $stockTersedia,
                    'stockGudang' => $stockGudang,
                    //'stockAntrian' => $stockAntrian,
                ));
        }
        
        public function actionGetlokasibarang()
        {
            $barangid = $_POST['barangid'];
            
            $criteria=new CDbCriteria;
            $criteria->distinct = 't.id';
            $criteria->select = 't.id, t.nama';
            $criteria->join = 'inner join transaksi.stocksupplier s on t.id=s.lokasipenyimpananbarangid';
            $criteria->condition = 't.isdeleted = false and barangid = '.$barangid.' and s.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
            
            $rec = Lokasipenyimpananbarang::model()->findAll($criteria);
            
            $i=0;
            $id = array();
            $nama = array();
            foreach($rec as $r)
            {
                $id[$i] = $r->id;
                $nama[$i] = $r->nama;
                $i++;
            }
            
            echo CJSON::encode(array(
                    'id' => $id,
                    'text' => $nama,
                ));
        }
        
        public function actionGetSupplierBarang()
        {
            $barangid = $_POST['barangid'];
            
            $criteria=new CDbCriteria;
            $criteria->select = 't.id, t.namaperusahaan';
            $criteria->join = 'inner join transaksi.hargabarang h on t.id=h.supplierid';
            $criteria->condition = 't.status = 1 and barangid = '.$barangid.' ';
            
            $rec = Supplier::model()->findAll($criteria);
            
            $i=0;
            $id = array();
            $nama = array();
            foreach($rec as $r)
            {
                $id[$i] = $r->id;
                $nama[$i] = $r->namaperusahaan;
                $i++;
            }
            
            echo CJSON::encode(array(
                    'id' => $id,
                    'text' => $nama,
                ));
        }
        
        protected function performAjaxValidation($model)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='besekpenjualanbarang-form')
            {
                echo CActiveForm::validate($model);
                Yii::app()->end();
            }
        }
        
        public function actionDelete($id)
		{
				if(Yii::app()->request->isPostRequest)
				{               
					
					$penjualan = Besekpenjualanbarang::model()->findByPk($id);
					$penjualan->isdeleted = true;
					$penjualan->save();

					// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
					if(!isset($_GET['ajax']))
							$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
				}
				else
					throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}
        
         public function actionSendToKasir($id)
        {
            if(Yii::app()->request->isPostRequest)
            {
                $penjualan  = Besekpenjualanbarang::model()->findByPk($id);
                $penjualan->issendtokasir=TRUE;
                if($penjualan->save())
                {
                        Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Dikirim ke Kasir.");
                        echo CJSON::encode(array('result'=>'OK'));                               
                        Yii::app()->end();
                   
                }
            }
        }
}