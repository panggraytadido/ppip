<?php

class Gudangpenjualanbarangkesupplier extends Penjualanbarangkesupplier
{       
        public $kode;
        public $nama;
        public $namasupplier;

        public function rules()
	{
		return array(
                        array('barangid, tanggal, lokasipenyimpananbarangid, jumlah, hargatotal, kategori, hargasatuan, supplierid, supplierpembeliid', 'required'),
			array('barangid, divisiid, supplierpembeliid, lokasipenyimpananbarangid, kategori, userid, box, labarugi, hargasatuan, netto', 'numerical', 'integerOnly'=>true),
                        array('jumlah', 'numerical', 'min'=>1),
                        array('tanggal, createddate, updateddate, isdeleted', 'safe'),
                    
			array('id, barangid, divisiid, tanggal, jumlah, hargatotal, kategori, createddate, updateddate, userid, box, labarugi, hargasatuan, lokasipenyimpananbarangid, statuspenjualan, netto, jenispembayaran, supplierid, isdeleted', 'safe', 'on'=>'search'),
		);
	}
        
        public function searchPenjualanbarang()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'p.id, barangid, tanggal, jumlah, b.kode, b.nama, l.namaperusahaan as namasupplier, box, hargasatuan, hargatotal';
                $criteria->alias = 'p';
                $criteria->join = 'LEFT JOIN master.barang AS b ON p.barangid=b.id '; 
                $criteria->join .= 'LEFT JOIN master.supplier AS l ON p.supplierpembeliid=l.id '; 
                $criteria->condition = 'p.divisiid='.Yii::app()->session['divisiid'];
                $criteria->condition .= ' and p.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                $criteria->condition .= " and p.isdeleted=false ";
                
                if(isset($_GET['Gudangpenjualanbarangkesupplier']))
                {
                    $criteria->compare('upper(b.kode)',  strtoupper($_GET['Gudangpenjualanbarangkesupplier']['kode']),true);
                    $criteria->compare('upper(b.nama)',strtoupper($_GET['Gudangpenjualanbarangkesupplier']['nama']),true);
                    $criteria->compare('upper(l.namaperusahaan)',strtoupper($_GET['Gudangpenjualanbarangkesupplier']['namasupplier']),true);
                    if($_GET['Gudangpenjualanbarangkesupplier']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Gudangpenjualanbarangkesupplier']['tanggal'])));
                    $criteria->compare('upper(box)',strtoupper($this->box));
                    $criteria->compare('upper(hargasatuan)',strtoupper($this->hargasatuan));
                    $criteria->compare('upper(hargatotal)',strtoupper($this->hargatotal));
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'p.id desc',
                    'p.id',
                    'kode'=>array(
                                      'asc'=>'b.kode',
                                      'desc'=>'b.kode desc',
                                    ),
                    'nama'=>array(
                                      'asc'=>'b.nama',
                                      'desc'=>'b.nama desc',
                                    ),
                    'namasupplier'=>array(
                                      'asc'=>'l.namaperusahaan',
                                      'desc'=>'l.namaperusahaan desc',
                                    ),
                    'tanggal',
                    'box',
                    'hargasatuan',
                    'hargatotal',
					'jumlah',
                );
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
						'pagination'=>array(
                                            'pageSize'=>10,
					),
		));
	}
        
        public function cekExist()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';

            $criteria->compare('barangid',$_POST['Gudangpenjualanbarangkesupplier']['barangid']);
            $criteria->compare('divisiid',$_POST['Gudangpenjualanbarangkesupplier']['divisiid']);
            $criteria->compare('supplierpembeliid',$_POST['Gudangpenjualanbarangkesupplier']['supplierpembeliid']);
            if($_POST['Gudangpenjualanbarangkesupplier']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpenjualanbarangkesupplier']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Gudangpenjualanbarangkesupplier']['jumlah']);
            $criteria->compare('hargatotal',$_POST['Gudangpenjualanbarangkesupplier']['hargatotal']);
            $criteria->compare('kategori',$_POST['Gudangpenjualanbarangkesupplier']['kategori']);
            $criteria->compare('createddate',$_POST['Gudangpenjualanbarangkesupplier']['createddate']);
            $criteria->compare('userid',$_POST['Gudangpenjualanbarangkesupplier']['userid']);
            $criteria->compare('box',$_POST['Gudangpenjualanbarangkesupplier']['box']);
            $criteria->compare('labarugi',$_POST['Gudangpenjualanbarangkesupplier']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Gudangpenjualanbarangkesupplier']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpenjualanbarangkesupplier']['lokasipenyimpananbarangid']);
            $criteria->compare('statuspenjualan',$_POST['Gudangpenjualanbarangkesupplier']['statuspenjualan']);
            $criteria->compare('supplierid',$_POST['Gudangpenjualanbarangkesupplier']['supplierid']);
            
            $jumlah = Penjualanbarangkesupplier::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Gudangpenjualanbarangkesupplier']['barangid']);
            $criteria->compare('divisiid',$_POST['Gudangpenjualanbarangkesupplier']['divisiid']);
            $criteria->compare('supplierpembeliid',$_POST['Gudangpenjualanbarangkesupplier']['supplierpembeliid']);
            if($_POST['Gudangpenjualanbarangkesupplier']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpenjualanbarangkesupplier']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Gudangpenjualanbarangkesupplier']['jumlah']);
            $criteria->compare('hargatotal',$_POST['Gudangpenjualanbarangkesupplier']['hargatotal']);
            $criteria->compare('kategori',$_POST['Gudangpenjualanbarangkesupplier']['kategori']);
            $criteria->compare('createddate',$_POST['Gudangpenjualanbarangkesupplier']['createddate']);
            $criteria->compare('userid',$_POST['Gudangpenjualanbarangkesupplier']['userid']);
            $criteria->compare('box',$_POST['Gudangpenjualanbarangkesupplier']['box']);
            $criteria->compare('labarugi',$_POST['Gudangpenjualanbarangkesupplier']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Gudangpenjualanbarangkesupplier']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpenjualanbarangkesupplier']['lokasipenyimpananbarangid']);
            $criteria->compare('supplierid',$_POST['Gudangpenjualanbarangkesupplier']['supplierid']);
            
            $id = Penjualanbarangkesupplier::model()->find($criteria)->id;
                
            return $id;
        }
        
        public function cekExistKaryawan($dataKaryawan)
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('karyawanid',$dataKaryawan['karyawanid']);
            $criteria->compare('penjualanbarangid',$dataKaryawan['penjualanbarangid']);
            $criteria->compare('upah',$dataKaryawan['upah']);
            if($dataKaryawan['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($dataKaryawan['tanggal'])));
            $criteria->compare('createddate',$dataKaryawan['createddate']);
            $criteria->compare('userid',$dataKaryawan['userid']);
            $criteria->compare('divisiid',$dataKaryawan['divisiid']);
            $criteria->compare('isdeleted',$dataKaryawan['isdeleted']);
            
            $jumlah = Bongkarmuat::model()->count($criteria);
                
            return $jumlah;
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
