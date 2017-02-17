<?php

class PenjualanbarangController extends Controller
{
	public $layout='//layouts/column2';
        public $pageTitle = 'Gudang - Penjualan Barang';

		public function filters()
		{
			return array(
				
			);
		}
        
        public function actionIndex()
        {
            $model=new Gudangpenjualanbarang('search');
            $model->unsetAttributes();
            if(isset($_GET['Gudangpenjualanbarang']))
                    $model->attributes=$_GET['Gudangpenjualanbarang'];
            

            $this->render('index',array(
                    'model'=>$model,
            ));
        }
        
        public function actionCreate()
        {
            $modelPenjualan = new Gudangpenjualanbarang;            

            $this->performAjaxValidation($modelPenjualan);

            if(isset($_POST['Gudangpenjualanbarang']))
            {
                $model=new Gudangpenjualanbarang;
                $_POST['Gudangpenjualanbarang']['divisiid'] = Yii::app()->session['divisiid'];
                $_POST['Gudangpenjualanbarang']['statuspenjualan'] = 0;
                $model->attributes=$_POST['Gudangpenjualanbarang'];
                $valid = $model->validate();

                if( $valid )
                {
                    $createddate = date("Y-m-d H:", time());
                    
                    $_POST['Gudangpenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenjualanbarang']['tanggal']);
                    $_POST['Gudangpenjualanbarang']['createddate'] = $createddate;
                    $_POST['Gudangpenjualanbarang']['userid'] = Yii::app()->user->id;

                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Gudangpenjualanbarang']['labarugi'] = $_POST['Gudangpenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Gudangpenjualanbarang']['jumlah']);
					
					$hargaBarang = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid']);
					
					$model->hargabarangid=$hargaBarang->id;
					
					$tanggal = $_POST['Gudangpenjualanbarang']['tanggal'];
					$cekFaktur = Faktur::model()->findAll("pelangganid=" . $_POST['Gudangpenjualanbarang']['pelangganid'] . " 
                                                                                                    AND cast(tanggal as date)='$tanggal' 
                                                                                                            and lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                if (count($cekFaktur) != 0) {
                    for ($i = 0; $i < count($cekFaktur); $i++) {
                        $data[] = $cekFaktur[$i]['pembelianke'];
                    }
                    $pembelianke = max($data) + 1;
                } else {
                    $pembelianke = 1;
                }

                $model->pembelianke = $pembelianke;

                    $model->attributes=$_POST['Gudangpenjualanbarang'];

                    $cekExist = $model->cekExist();

                    if($cekExist==0)
                    {
						$stocksupplier = Stocksupplier::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid'])->jumlah;
						if($_POST['Gudangpenjualanbarang']['jumlah']>$stocksupplier)
						{
							echo CJSON::encode(array
											(
												'result'=>'FALSE',
												'stock'=>$stocksupplier,
												'jumlahpenjualan'=>$_POST['Gudangpenjualanbarang']['jumlah']
												
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
									$modelStockout->barangid = $_POST['Gudangpenjualanbarang']['barangid'];
									$modelStockout->jumlah = $_POST['Gudangpenjualanbarang']['jumlah'];
									$modelStockout->tanggal = $_POST['Gudangpenjualanbarang']['tanggal'];
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
							catch (Exception $error) {
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
            $modelPenjualan = Gudangpenjualanbarang::model()->findByPk($id);            

            $modelPenjualan->tanggal = date("m/d/Y", strtotime($modelPenjualan->tanggal));
            //$_POST['Gudangpenjualanbarang']['statuspenjualan'] = ($modelPenjualan->statuspenjualan==false) ? 0 : 1;
            
            $this->performAjaxValidationUpdate($modelPenjualan);
            
            if(isset($_POST['Gudangpenjualanbarang']))
            {
							
                $modelPenjualan->attributes=$_POST['Gudangpenjualanbarang'];
                $valid = $modelPenjualan->validate();

                if( $valid )
                {
                    // set nilai variable
                    $barangidLama = $modelPenjualan->barangid;
                    $jumlahLama = $modelPenjualan->jumlah;
                    
                    
                    // set data penjualan barang
                    $_POST['Gudangpenjualanbarang']['tanggal'] = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenjualanbarang']['tanggal']);
                    $_POST['Gudangpenjualanbarang']['updateddate'] = date("Y-m-d H:", time());
                    
                    $hargamodal = 0;
                    $hargamodal = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid'])->hargamodal;
                    $_POST['Gudangpenjualanbarang']['labarugi'] = $_POST['Gudangpenjualanbarang']['hargatotal'] - ($hargamodal * $_POST['Gudangpenjualanbarang']['jumlah']);
                    
					$hargaBarang = Hargabarang::model()->find('barangid='.$_POST['Gudangpenjualanbarang']['barangid'].' and supplierid='.$_POST['Gudangpenjualanbarang']['supplierid']);
					
					$modelPenjualan->hargabarangid=$hargaBarang->id;
					
                    $modelPenjualan->attributes=$_POST['Gudangpenjualanbarang'];
					
                    $transaction = Yii::app()->db->beginTransaction();
					try{
						
						if($modelPenjualan->save())
						{							
							$modelStockout = Stockout::model()->find('penjualanbarangid='.$modelPenjualan->id);
							$modelStockout->barangid = $_POST['Gudangpenjualanbarang']['barangid'];
							$modelStockout->jumlah = $_POST['Gudangpenjualanbarang']['jumlah'];
							//$modelStockout->tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['Gudangpenjualanbarang']['tanggal']);
							$modelStockout->updatedate = date("Y-m-d H:", time());;
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
            $modelPenjualan = Gudangpenjualanbarang::model()->findByPk($id);
                     
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
                   // 'stockAntrian' => $stockAntrian,
                ));
        }
		
		public function actionDeleteOneKaryawan()
		{
			if($_POST['karyawanid']!="" && $_POST['penjualanbarangid']!="")
			{
				$bongkarMuat = Bongkarmuat::model()->find('karyawanid='.intval($_POST['karyawanid']).' AND penjualanbarangid='.intval($_POST['penjualanbarangid']));
				if(count($bongkarMuat)>0)
				{
					if($bongkarMuat->delete())
					{
						echo CJSON::encode(array(
						  'result'=>'OK'						 
						));
					}
				}
			}
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
		
		public function actionGetkaryawanabsenupdate()
        {
            if($_POST['tanggal']!='')
            {
                $tanggal = Yii::app()->DateConvert->ConvertTanggal($_POST['tanggal']);
                
                $option = '';
                $jml = 0;               
				$criteria=new CDbCriteria;
				$criteria->select = 'id,nama';
				$criteria->condition='jabatanid=11';
				$rec = Karyawan::model()->findAll($criteria); // jabatan pegawai bongkar muat & absen di hari transaksi
				//print("<pre>".print_r($rec,true)."</pre>");	
						
                echo CJSON::encode(
					$rec
                );	
				
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
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenjualanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        protected function performAjaxValidationUpdate($model1)
        {
            if(isset($_POST['ajax']) && $_POST['ajax']==='gudangpenjualanbarang-form')
            {
                echo CActiveForm::validate(array($model1));
                Yii::app()->end();
            }
        }
        
        public function actionDelete($id)
		{
				if(Yii::app()->request->isPostRequest)
				{
					$Bongkarmuat = Bongkarmuat::model()->findAll('penjualanbarangid='.$id);
					foreach($Bongkarmuat as $recBongkarmuat)
					{
						$data = Bongkarmuat::model()->findByPk($recBongkarmuat->id);
						$data->isdeleted = true;
						$data->save();
					}
					
					$penjualan = Gudangpenjualanbarang::model()->findByPk($id);
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
                $penjualan  = Gudangpenjualanbarang::model()->findByPk($id);
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