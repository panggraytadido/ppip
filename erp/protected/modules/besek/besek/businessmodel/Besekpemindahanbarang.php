<?php

class Besekpemindahanbarang extends Pemindahanbarang
{       
        public $kode;
        public $nama;
        public $namalokasi;

        public function rules()
	{
		return array(
                        array('barangid, jumlah, lokasipenyimpananbarangid, lokasipenyimpananbarangtujuanid, userid, tanggal, divisiid', 'required'),
			array('barangid, jumlah, lokasipenyimpananbarangid, lokasipenyimpananbarangtujuanid, userid, divisiid', 'numerical', 'integerOnly'=>true),
                        array('tanggal, createddate, updateddate', 'safe'),
                    
			array('id, barangid, jumlah, lokasipenyimpananbarangid, lokasipenyimpananbarangtujuanid, tanggal, userid, createddate, updateddate, divisiid', 'safe', 'on'=>'search'),
		);
	}
        
        public function searchPemindahanbarang()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'p.id, kode, b.nama, tanggal, jumlah, b.kode, l.nama as namalokasi, jumlah';
                $criteria->alias = 'p';
                $criteria->join = 'LEFT JOIN master.barang AS b ON p.barangid=b.id '; 
                $criteria->join .= 'LEFT JOIN transaksi.lokasipenyimpananbarang AS l ON p.lokasipenyimpananbarangtujuanid=l.id '; 
                $criteria->condition = 'p.divisiid='.Yii::app()->session['divisiid'].' AND p.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                
                if(isset($_GET['Besekpemindahanbarang']))
                {
                    $criteria->compare('b.kode',$_GET['Besekpemindahanbarang']['kode'],true);
                    $criteria->compare('b.nama',$_GET['Besekpemindahanbarang']['nama'],true);
                    $criteria->compare('l.nama',$_GET['Besekpemindahanbarang']['namalokasi'],true);
                    if($_GET['Besekpemindahanbarang']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Besekpemindahanbarang']['tanggal'])));
                    $criteria->compare('jumlah',$this->jumlah);
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
                    'namalokasi'=>array(
                                      'asc'=>'l.nama',
                                      'desc'=>'l.nama desc',
                                    ),
                    'tanggal',
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

            $criteria->compare('barangid',$_POST['Besekpemindahanbarang']['barangid']);
            $criteria->compare('jumlah',$_POST['Besekpemindahanbarang']['jumlah']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Besekpemindahanbarang']['lokasipenyimpananbarangid']);
            $criteria->compare('lokasipenyimpananbarangtujuanid',$_POST['Besekpemindahanbarang']['lokasipenyimpananbarangtujuanid']);
            if($_POST['Besekpemindahanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Besekpemindahanbarang']['tanggal'])));
            $criteria->compare('userid',$_POST['Besekpemindahanbarang']['userid']);
            $criteria->compare('createddate',$_POST['Besekpemindahanbarang']['createddate']);
            $criteria->compare('divisiid',$_POST['Besekpemindahanbarang']['divisiid']);
            
            $jumlah = Pemindahanbarang::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Besekpemindahanbarang']['barangid']);
            $criteria->compare('jumlah',$_POST['Besekpemindahanbarang']['jumlah']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Besekpemindahanbarang']['lokasipenyimpananbarangid']);
            $criteria->compare('lokasipenyimpananbarangtujuanid',$_POST['Besekpemindahanbarang']['lokasipenyimpananbarangtujuanid']);
            if($_POST['Besekpemindahanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Besekpemindahanbarang']['tanggal'])));
            $criteria->compare('userid',$_POST['Besekpemindahanbarang']['userid']);
            $criteria->compare('createddate',$_POST['Besekpemindahanbarang']['createddate']);
            $criteria->compare('divisiid',$_POST['Besekpemindahanbarang']['divisiid']);
            
            $id = Pemindahanbarang::model()->find($criteria)->id;
                
            return $id;
        }
        
        public function cekExistKaryawan($dataKaryawan)
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('karyawanid',$dataKaryawan['karyawanid']);
            $criteria->compare('pemindahanbarangid',$dataKaryawan['pemindahanbarangid']);
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
