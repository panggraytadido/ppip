<?php

class Gudangpemindahanbarang extends Pemindahanbarang
{       
        public $kode;
        public $nama;
        public $namalokasi;
		public $barang;

        public function rules()
	{
		return array(
                        array('barangid, jumlah, lokasipenyimpananbarangid, lokasipenyimpananbarangtujuanid, userid, tanggal, divisiid', 'required'),
			array('barangid, lokasipenyimpananbarangid, lokasipenyimpananbarangtujuanid, userid, divisiid', 'numerical', 'integerOnly'=>true),
                        array('tanggal, createddate, updateddate', 'safe'),
                    
			array('id, barangid, jumlah, lokasipenyimpananbarangid, lokasipenyimpananbarangtujuanid, tanggal, userid, createddate, updateddate, divisiid', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'pemindahanbarangdetail' => array(self::HAS_MANY, 'Pemindahanbarangdetail', 'pemindahanbarangid'),
			'lokasipemyinpananbarang' => array(self::BELONGS_TO, 'Lokasipenyimpananbarang', 'lokasipemyinpananbarangid'),
			'barang' => array(self::BELONGS_TO, 'Barang', 'barangid')
			,
			'lokasipenyimpananbarangtujuan' => array(self::BELONGS_TO, 'Lokasipenyimpananbarang', 'lokasipenyimpananbarangtujuanid'),
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
                //$criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                
                if(isset($_GET['Gudangpemindahanbarang']))
                {
                    $criteria->compare('upper(b.kode)',strtoupper($_GET['Gudangpemindahanbarang']['kode']),true);
                    $criteria->compare('upper(b.nama)',strtoupper($_GET['Gudangpemindahanbarang']['nama']),true);
                    $criteria->compare('upper(l.nama)',strtoupper($_GET['Gudangpemindahanbarang']['namalokasi']),true);
                    if($_GET['Gudangpemindahanbarang']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Gudangpemindahanbarang']['tanggal'])));
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

            $criteria->compare('barangid',$_POST['Gudangpemindahanbarang']['barangid']);
            //$criteria->compare('jumlah',$_POST['Gudangpemindahanbarang']['jumlah']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangid']);
            $criteria->compare('lokasipenyimpananbarangtujuanid',$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid']);
            if($_POST['Gudangpemindahanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpemindahanbarang']['tanggal'])));
            $criteria->compare('userid',$_POST['Gudangpemindahanbarang']['userid']);
            $criteria->compare('createddate',$_POST['Gudangpemindahanbarang']['createddate']);
            $criteria->compare('divisiid',$_POST['Gudangpemindahanbarang']['divisiid']);
            
            $jumlah = Pemindahanbarang::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Gudangpemindahanbarang']['barangid']);
            //$criteria->compare('jumlah',$_POST['Gudangpemindahanbarang']['jumlah']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangid']);
            $criteria->compare('lokasipenyimpananbarangtujuanid',$_POST['Gudangpemindahanbarang']['lokasipenyimpananbarangtujuanid']);
            if($_POST['Gudangpemindahanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpemindahanbarang']['tanggal'])));
            $criteria->compare('userid',$_POST['Gudangpemindahanbarang']['userid']);
            $criteria->compare('createddate',$_POST['Gudangpemindahanbarang']['createddate']);
            $criteria->compare('divisiid',$_POST['Gudangpemindahanbarang']['divisiid']);
            
            $id = Pemindahanbarang::model()->find($criteria)->id;
                
            return $id;
        }
        
        public function cekExistKaryawan($dataKaryawan)
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('karyawanid',$dataKaryawan['karyawanid']);
            $criteria->compare('pemindahanbarangid',$dataKaryawan['pemindahanbarangid']);
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
