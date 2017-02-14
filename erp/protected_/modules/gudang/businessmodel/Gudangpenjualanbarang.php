<?php

class Gudangpenjualanbarang extends Penjualanbarang
{       
        public $kode;
        public $nama;
        public $namapelanggan;

        public function rules()
	{
		return array(
                        array('barangid, pelangganid, tanggal, lokasipenyimpananbarangid, jumlah, hargatotal, kategori, hargasatuan, supplierid', 'required'),
			array('barangid, divisiid, pelangganid, lokasipenyimpananbarangid, kategori, userid, box, labarugi, hargasatuan, netto', 'numerical', 'integerOnly'=>true),
                        //array('jumlah', 'numerical', 'min'=>1),
                        array('tanggal, createddate, updateddate, isdeleted', 'safe'),
                    
			array('id, barangid, divisiid, pelangganid, tanggal, jumlah, hargatotal, kategori, createddate, updateddate, userid, box, labarugi, hargasatuan, lokasipenyimpananbarangid, statuspenjualan, netto, jenispembayaran, supplierid, isdeleted', 'safe', 'on'=>'search'),
		);
	}
        
        public function searchPenjualanbarang()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'p.id, barangid, tanggal, jumlah, b.kode, b.nama, l.nama as namapelanggan, box, hargasatuan, hargatotal';
                $criteria->alias = 'p';
                $criteria->join = 'LEFT JOIN master.barang AS b ON p.barangid=b.id '; 
                $criteria->join .= 'LEFT JOIN master.pelanggan AS l ON p.pelangganid=l.id '; 
                $criteria->condition = 'p.divisiid='.Yii::app()->session['divisiid'];
                $criteria->condition .= ' and p.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                $criteria->condition .= " and p.isdeleted=false ";
                
                if(isset($_GET['Gudangpenjualanbarang']))
                {
                    $criteria->compare('upper(b.kode)',strtoupper($_GET['Gudangpenjualanbarang']['kode']),true);
                    $criteria->compare('upper(b.nama)',  strtoupper($_GET['Gudangpenjualanbarang']['nama']),true);
                    $criteria->compare('upper(l.nama)',strtoupper($_GET['Gudangpenjualanbarang']['namapelanggan']),true);
                    if($_GET['Gudangpenjualanbarang']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Gudangpenjualanbarang']['tanggal'])));
                    $criteria->compare('upper(box)',  strtoupper($this->box));
                    $criteria->compare('upper(hargasatuan)',  strtoupper($this->hargasatuan));
                    $criteria->compare('upper(hargatotal)',  strtoupper($this->hargatotal));
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
                    'namapelanggan'=>array(
                                      'asc'=>'l.nama',
                                      'desc'=>'l.nama desc',
                                    ),
                    'tanggal',
                    'box',
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

            $criteria->compare('barangid',$_POST['Gudangpenjualanbarang']['barangid']);
            $criteria->compare('divisiid',$_POST['Gudangpenjualanbarang']['divisiid']);
            $criteria->compare('pelangganid',$_POST['Gudangpenjualanbarang']['pelangganid']);
            if($_POST['Gudangpenjualanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpenjualanbarang']['tanggal'])));
            //$criteria->compare('jumlah',$_POST['Gudangpenjualanbarang']['jumlah']);
            //$criteria->compare('hargatotal',$_POST['Gudangpenjualanbarang']['hargatotal']);
            $criteria->compare('kategori',$_POST['Gudangpenjualanbarang']['kategori']);
            $criteria->compare('createddate',$_POST['Gudangpenjualanbarang']['createddate']);
            $criteria->compare('userid',$_POST['Gudangpenjualanbarang']['userid']);
            $criteria->compare('box',$_POST['Gudangpenjualanbarang']['box']);
            $criteria->compare('labarugi',$_POST['Gudangpenjualanbarang']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Gudangpenjualanbarang']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpenjualanbarang']['lokasipenyimpananbarangid']);
            $criteria->compare('statuspenjualan',$_POST['Gudangpenjualanbarang']['statuspenjualan']);
            $criteria->compare('supplierid',$_POST['Gudangpenjualanbarang']['supplierid']);
            
            $jumlah = Penjualanbarang::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Gudangpenjualanbarang']['barangid']);
            $criteria->compare('divisiid',$_POST['Gudangpenjualanbarang']['divisiid']);
            $criteria->compare('pelangganid',$_POST['Gudangpenjualanbarang']['pelangganid']);
            if($_POST['Gudangpenjualanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpenjualanbarang']['tanggal'])));
            //$criteria->compare('jumlah',$_POST['Gudangpenjualanbarang']['jumlah']);
            //$criteria->compare('hargatotal',$_POST['Gudangpenjualanbarang']['hargatotal']);
            $criteria->compare('kategori',$_POST['Gudangpenjualanbarang']['kategori']);
            $criteria->compare('createddate',$_POST['Gudangpenjualanbarang']['createddate']);
            $criteria->compare('userid',$_POST['Gudangpenjualanbarang']['userid']);
            $criteria->compare('box',$_POST['Gudangpenjualanbarang']['box']);
            $criteria->compare('labarugi',$_POST['Gudangpenjualanbarang']['labarugi']);
            $criteria->compare('hargasatuan',$_POST['Gudangpenjualanbarang']['hargasatuan']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpenjualanbarang']['lokasipenyimpananbarangid']);
            $criteria->compare('supplierid',$_POST['Gudangpenjualanbarang']['supplierid']);
            
            $id = Penjualanbarang::model()->find($criteria)->id;
                
            return $id;
        }
        
        public function cekExistKaryawan($dataKaryawan)
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('karyawanid',$dataKaryawan['karyawanid']);
            $criteria->compare('penjualanbarangid',$dataKaryawan['penjualanbarangid']);
            //$criteria->compare('upah',$dataKaryawan['upah']);
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
