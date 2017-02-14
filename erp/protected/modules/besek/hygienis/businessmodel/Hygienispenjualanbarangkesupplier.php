<?php

class Hygienispenjualanbarangkesupplier extends Penjualanbarangkesupplier
{       
        public $kode;
        public $nama;
        public $namasupplier;

        public function rules()
	{
		return array(
                        array('barangid, tanggal, lokasipenyimpananbarangid, jumlah, hargatotal, kategori, hargasatuan, supplierid, supplierpembeliid', 'required'),
			array('barangid, divisiid, supplierpembeliid, lokasipenyimpananbarangid, hargatotal, kategori, userid, box, labarugi, hargasatuan, netto', 'numerical', 'integerOnly'=>true),
                        array('jumlah', 'numerical', 'min'=>1),
                        array('tanggal, createddate, updateddate, isdeleted', 'safe'),
                    
			array('id, barangid, divisiid, tanggal, jumlah, hargatotal, kategori, createddate, updateddate, userid, box, labarugi, hargasatuan, lokasipenyimpananbarangid, statuspenjualan, netto, jenispembayaran, supplierid, isdeleted', 'safe', 'on'=>'search'),
		);
	}
        
        public function searchPenjualanbarang()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'p.id, barangid, tanggal, jumlah, b.kode, b.nama, l.namaperusahaan as namasupplier, p.jumlah, hargasatuan, hargatotal';
                $criteria->alias = 'p';
                $criteria->join = 'LEFT JOIN master.barang AS b ON p.barangid=b.id '; 
                $criteria->join .= 'LEFT JOIN master.supplier AS l ON p.supplierpembeliid=l.id '; 
                $criteria->condition = 'p.divisiid='.Yii::app()->session['divisiid'];
                $criteria->condition .= ' and p.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                $criteria->condition .= " and p.isdeleted=false ";
                
                if(isset($_GET['Hygienispenjualanbarangkesupplier']))
                {
                    if($_GET['Hygienispenjualanbarangkesupplier']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Hygienispenjualanbarangkesupplier']['tanggal'])));
                    $criteria->compare('b.kode',$_GET['Hygienispenjualanbarangkesupplier']['kode']);
                    $criteria->compare('b.nama',$_GET['Hygienispenjualanbarangkesupplier']['nama']);
                    $criteria->compare('l.namaperusahaan',$_GET['Hygienispenjualanbarangkesupplier']['namasupplier'], true);
                    $criteria->compare('jumlah',$this->jumlah);
                    $criteria->compare('hargasatuan',$this->hargasatuan);
                    $criteria->compare('hargatotal',$this->hargatotal);
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
                    'jumlah',
                    'hargasatuan',
                    'hargatotal',
                );

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
		));
	}
        
        public function cekExist()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';

            $criteria->compare('barangid',$_POST['Hygienispenjualanbarangkesupplier']['barangid']);
            $criteria->compare('divisiid',$_POST['Hygienispenjualanbarangkesupplier']['divisiid']);
            $criteria->compare('supplierpembeliid',$_POST['Hygienispenjualanbarangkesupplier']['supplierpembeliid']);
            if($_POST['Hygienispenjualanbarangkesupplier']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Hygienispenjualanbarangkesupplier']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Hygienispenjualanbarangkesupplier']['jumlah']);
            $criteria->compare('hargatotal',$_POST['Hygienispenjualanbarangkesupplier']['hargatotal']);
            $criteria->compare('kategori',$_POST['Hygienispenjualanbarangkesupplier']['kategori']);
            $criteria->compare('createddate',$_POST['Hygienispenjualanbarangkesupplier']['createddate']);
            $criteria->compare('userid',$_POST['Hygienispenjualanbarangkesupplier']['userid']);
            $criteria->compare('labarugi',$_POST['Hygienispenjualanbarangkesupplier']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Hygienispenjualanbarangkesupplier']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Hygienispenjualanbarangkesupplier']['lokasipenyimpananbarangid']);
            $criteria->compare('statuspenjualan',$_POST['Hygienispenjualanbarangkesupplier']['statuspenjualan']);
            $criteria->compare('netto',$_POST['Hygienispenjualanbarangkesupplier']['netto']);
            $criteria->compare('supplierid',$_POST['Hygienispenjualanbarangkesupplier']['supplierid']);
            
            $jumlah = Penjualanbarangkesupplier::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Hygienispenjualanbarangkesupplier']['barangid']);
            $criteria->compare('divisiid',$_POST['Hygienispenjualanbarangkesupplier']['divisiid']);
            $criteria->compare('supplierpembeliid',$_POST['Hygienispenjualanbarangkesupplier']['supplierpembeliid']);
            if($_POST['Hygienispenjualanbarangkesupplier']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Hygienispenjualanbarangkesupplier']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Hygienispenjualanbarangkesupplier']['jumlah']);
            $criteria->compare('hargatotal',$_POST['Hygienispenjualanbarangkesupplier']['hargatotal']);
            $criteria->compare('kategori',$_POST['Hygienispenjualanbarangkesupplier']['kategori']);
            $criteria->compare('createddate',$_POST['Hygienispenjualanbarangkesupplier']['createddate']);
            $criteria->compare('userid',$_POST['Hygienispenjualanbarangkesupplier']['userid']);
            $criteria->compare('box',$_POST['Hygienispenjualanbarangkesupplier']['box']);
            $criteria->compare('labarugi',$_POST['Hygienispenjualanbarangkesupplier']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Hygienispenjualanbarangkesupplier']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Hygienispenjualanbarangkesupplier']['lokasipenyimpananbarangid']);
            $criteria->compare('supplierid',$_POST['Hygienispenjualanbarangkesupplier']['supplierid']);
            
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
