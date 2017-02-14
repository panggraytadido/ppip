<?php

class PenjualanbarangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Hygienis - Penjualan Barang';

	public function filters()
	{
		return array(
			
		);
	}
        
        public function actionIndex()
        {
            $model=new Hygienispenjualanbarang('search');
            $model->unsetAttributes();
            if(isset($_GET['Hygienispenjualanbarang']))
                $model->attributes=$_GET['Hygienispenjualanbarang'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
		
        public function actionCreate()
        {
            $modelPenjualan = new Hygienispenjualanbarang;            

            $this->performAjaxValidation($modelPenjualan);

            if(isset($_POST['Hygienispenjualanbarang']))
            {
                $model=new Hygienispenjualanbarang;
                $_POST['Hygienispenjualanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                $_POST['Hygienispenjualanbarang']['statuspenjualan'] = 0;
                
                $model->attributes=$_POST['Hygienispenjualanbarang'];
                
                $valid = $model->validate();

                if($valid)
                {
                    $createddate = date("Y-m-d H:", time());
                    
                    $_POST['Hygienispenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Hygienispenjualanbarang']['tanggal']);
                    $_POST['Hygienispenjualanbarang']['createddate'] = $createddate;
                    $_POST['Hygienispenjualanbarang']['userid'] = Yii::app()->user->id;

                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Hygienispenjualanbarang']['barangid'].' and supplierid='.$_POST['Hygienispenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Hygienispenjualanbarang']['labarugi'] = $_POST['Hygienispenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Hygienispenjualanbarang']['jumlah']);
                    
                    $_POST['Hygienispenjualanbarang']['netto'] = $_POST['Hygienispenjualanbarang']['jumlah'];
//                    $_POST['Hygienispenjualanbarang']['jumlah'] = 0;

                    $model->attributes=$_POST['Hygienispenjualanbarang'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
						$stocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Hygienispenjualanbarang']['barangid'].' and lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'].' and supplierid='.$_POST['Hygienispenjualanbarang']['supplierid'])->jumlah;
						if($_POST['Hygienispenjualanbarang']['jumlah']>$stocksupplier)
						{
							echo CJSON::encode(array
											(
												'result'=>'FALSE',
												'stock'=>$stocksupplier,
												'jumlahpenjualan'=>$_POST['Hygienispenjualanbarang']['jumlah']
												
											));
																				
							Yii::app()->end();
						}
						else
						{
							$transaction = Yii::app()->db->beginTransaction();
							try{
								
								if($model->save())
								{
								   
									$modelStockout = new Stockout;
									$modelStockout->barangid = $_POST['Hygienispenjualanbarang']['barangid'];
									$modelStockout->jumlah = $_POST['Hygienispenjualanbarang']['jumlah'];
									$modelStockout->tanggal = $_POST['Hygienispenjualanbarang']['tanggal'];
									$modelStockout->createddate = $createddate;
									$modelStockout->userid = Yii::app()->user->id;
									$modelStockout->penjualanbarangid = $model->getPrimaryKey();
									$modelStockout->save();

								}
								
								$transaction ->commit();	
								
								echo CJSON::encode(array
										(
											'result'=>'OK',
											'penjualanid'=>$model->getPrimaryKey()
										));
								Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Ditambah." );
								Yii::app()->end();
							}
							catch (Exception $error) 
							{
								$transaction ->rollback();
								throw $error;
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
                    'modelPenjualan'=>$modelPenjualan,                    
            ), false, true );
            
            Yii::app()->end();
        }
 
        public function actionUpdate($id)
        {
            $modelPenjualan = Hygienispenjualanbarang::model()->findByPk($id);            

            $modelPenjualan->tanggal = date("m/d/Y", strtotime($modelPenjualan->tanggal));
            
            $this->performAjaxValidationUpdate($modelPenjualan);
            
            if(isset($_POST['Hygienispenjualanbarang']))
            {
                $_POST['Hygienispenjualanbarang']['statuspenjualan'] = ($modelPenjualan->statuspenjualan==false) ? 0 : 1;
            
                $modelPenjualan->attributes=$_POST['Hygienispenjualanbarang'];
                $valid = $modelPenjualan->validate();

                if($valid)
                {
                    // set nilai variable
                    $barangidLama = $modelPenjualan->barangid;
                    $jumlahLama = $modelPenjualan->jumlah;
                    $createddate = $_POST['waktuinsert'];
                    $updatedate = $_POST['waktuupdate'];
                    
                    // set data penjualan barang
                    $_POST['Hygienispenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Hygienispenjualanbarang']['tanggal']);
                    $_POST['Hygienispenjualanbarang']['updateddate'] = $updatedate;
                    
                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Hygienispenjualanbarang']['barangid'].' and supplierid='.$_POST['Hygienispenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Hygienispenjualanbarang']['labarugi'] = $_POST['Hygienispenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Hygienispenjualanbarang']['jumlah']);
                    
                    $_POST['Hygienispenjualanbarang']['netto'] = $_POST['Hygienispenjualanbarang']['jumlah'];
                    
                    $modelPenjualan->attributes=$_POST['Hygienispenjualanbarang'];
                    $transaction = Yii::app()->db->beginTransaction();
					try{
						
						if($modelPenjualan->save())
						{                                               
							$modelStockout = Stockout::model()->find('penjualanbarangid='.$modelPenjualan->id);
							$modelStockout->barangid = $_POST['Hygienispenjualanbarang']['barangid'];
							$modelStockout->jumlah = $_POST['Hygienispenjualanbarang']['jumlah'];
							$modelStockout->tanggal = $_POST['Hygienispenjualanbarang']['tanggal'];
							$modelStockout->updatedate = $updatedate;
							$modelStockout->save();
						}
						
						$transaction ->commit();	
						
						echo CJSON::encode(array
								(
									'result'=>'OK'
								));
						Yii::app ()->user->setFlash ( 'success', "Data Penjualan Barang Berhasil Diupdate.");
						Yii::app()->end();
					}
					catch (Exception $error) 
					{
						$transaction ->rollback();
						throw $error;
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
                            'modelPenjualan' => $modelPenjualan,                            
            ), false, true );
            Yii::app()->end();
	}
 
        public function actionView($id)
        {
            $modelPenjualan = Hygienispenjualanbarang::model()->findByPk($id);
            $modelBongkarmuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$modelPenjualan->id.' and isdeleted=false');
            
            $this->render('view',array(
                    'modelPenjualan'=>$modelPenjualan,
                    'modelBongkarmuat'=>$modelBongkarmuat,
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
        
        public function actionGetkaryawanabsen()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                $option = '';
                $jml = 0;
                //$rec = Karyawan::model()->with('absensi')->findAll("jabatanid=11 and tanggal::text like '".$tanggal."%' and jammasuk is not null"); // jabatan pegawai bongkar muat & absen di hari transaksi
				$rec = Karyawan::model()->findAll("jabatanid=11"); // jabatan pegawai bongkar muat & absen di hari transaksi
                foreach($rec as $r)
                {
                    $option .= '<option value="'.$r->id.'">'.$r->nama.'</option>';
                    $jml++;
                }
                
                echo CJSON::encode(array(
                  'option'=>$option,
                  'jml'=>$jml
                ));
            }
            else
            {
                echo CJSON::encode(array(
                  'option'=>'',
                  'jml'=>0
                ));
            }
        }
        
        public function actionGetjumlahabsen()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                //$jml = Karyawan::model()->with('absensi')->count("jabatanid=11 and tanggal::text like '".$tanggal."%' and jammasuk is not null");
				$jml = Karyawan::model()->count("jabatanid=11");
                
                echo $jml;
            }
            else
                echo 0;
        }
        
        protected function performAjaxValidation($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='hygienispenjualanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        protected function performAjaxValidationUpdate($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='hygienispenjualanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        public function actionDelete($id)
	{
            if(Yii::app()->request->isPostRequest)
            {                            
                $penjualan = Hygienispenjualanbarang::model()->findByPk($id);
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
                $penjualan  = Hygienispenjualanbarang::model()->findByPk($id);
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