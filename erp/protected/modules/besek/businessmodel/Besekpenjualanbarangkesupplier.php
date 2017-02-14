<?php

class Besekpenjualanbarangkesupplier extends Penjualanbarangkesupplier
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
                    //supplierpembeliid
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
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                $criteria->condition .= " and p.isdeleted=false ";
                
                if(isset($_GET['Besekpenjualanbarangkesupplier']))
                {
                    if($_GET['Besekpenjualanbarangkesupplier']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Besekpenjualanbarangkesupplier']['tanggal'])));
                    $criteria->compare('b.kode',$_GET['Besekpenjualanbarangkesupplier']['kode']);
                    $criteria->compare('b.nama',$_GET['Besekpenjualanbarangkesupplier']['nama']);
                    $criteria->compare('l.namaperusahaan',$_GET['Besekpenjualanbarangkesupplier']['namasupplier'],true);
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
						'pagination'=>array(
                                            'pageSize'=>10,
					),
		));
	}
        
        public function cekExist()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';

            $criteria->compare('barangid',$_POST['Besekpenjualanbarangkesupplier']['barangid']);
            $criteria->compare('divisiid',$_POST['Besekpenjualanbarangkesupplier']['divisiid']);
            $criteria->compare('supplierpembeliid',$_POST['Besekpenjualanbarangkesupplier']['supplierpembeliid']);
            if($_POST['Besekpenjualanbarangkesupplier']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Besekpenjualanbarangkesupplier']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Besekpenjualanbarangkesupplier']['jumlah']);
            $criteria->compare('hargatotal',$_POST['Besekpenjualanbarangkesupplier']['hargatotal']);
            $criteria->compare('kategori',$_POST['Besekpenjualanbarangkesupplier']['kategori']);
            $criteria->compare('createddate',$_POST['Besekpenjualanbarangkesupplier']['createddate']);
            $criteria->compare('userid',$_POST['Besekpenjualanbarangkesupplier']['userid']);
            $criteria->compare('labarugi',$_POST['Besekpenjualanbarangkesupplier']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Besekpenjualanbarangkesupplier']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Besekpenjualanbarangkesupplier']['lokasipenyimpananbarangid']);
            $criteria->compare('statuspenjualan',$_POST['Besekpenjualanbarangkesupplier']['statuspenjualan']);
            $criteria->compare('netto',$_POST['Besekpenjualanbarangkesupplier']['netto']);
            $criteria->compare('supplierid',$_POST['Besekpenjualanbarangkesupplier']['supplierid']);
            
            $jumlah = Penjualanbarangkesupplier::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Besekpenjualanbarangkesupplier']['barangid']);
            $criteria->compare('divisiid',$_POST['Besekpenjualanbarangkesupplier']['divisiid']);
            $criteria->compare('supplierpembeliid',$_POST['Besekpenjualanbarangkesupplier']['supplierpembeliid']);
            if($_POST['Besekpenjualanbarangkesupplier']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Besekpenjualanbarangkesupplier']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Besekpenjualanbarangkesupplier']['jumlah']);
            $criteria->compare('hargatotal',$_POST['Besekpenjualanbarangkesupplier']['hargatotal']);
            $criteria->compare('kategori',$_POST['Besekpenjualanbarangkesupplier']['kategori']);
            $criteria->compare('createddate',$_POST['Besekpenjualanbarangkesupplier']['createddate']);
            $criteria->compare('userid',$_POST['Besekpenjualanbarangkesupplier']['userid']);
            $criteria->compare('box',$_POST['Besekpenjualanbarangkesupplier']['box']);
            $criteria->compare('labarugi',$_POST['Besekpenjualanbarangkesupplier']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Besekpenjualanbarangkesupplier']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Besekpenjualanbarangkesupplier']['lokasipenyimpananbarangid']);
            $criteria->compare('supplierid',$_POST['Besekpenjualanbarangkesupplier']['supplierid']);
            
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
