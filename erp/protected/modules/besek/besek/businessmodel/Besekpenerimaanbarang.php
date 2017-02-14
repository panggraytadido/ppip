<?php

class Besekpenerimaanbarang extends Penerimaanbarang
{       
        public $nama;
        public $namaperusahaan;

        public function rules()
	{
		return array(
                        array('barangid, supplierid, divisiid, tanggal, jumlah, beratlori, totalbarang, lokasipenyimpananbarangid', 'required'),
			array('barangid, supplierid, divisiid, jumlah, beratlori, totalbarang, userid, lokasipenyimpananbarangid', 'numerical', 'integerOnly'=>true),
			array('jumlah', 'numerical', 'min'=>1),
                        array('createddate, updatedate, isdeleted', 'safe'),
		);
	}
        
        public function searchPenerimaanbarang()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'p.id, tanggal, b.nama, namaperusahaan, jumlah';
                $criteria->alias = 'p';
                $criteria->join = 'LEFT JOIN master.barang AS b ON p.barangid=b.id '; 
                $criteria->join .= 'LEFT JOIN master.supplier AS s ON p.supplierid=s.id '; 
                $criteria->condition = 'p.divisiid='.Yii::app()->session['divisiid'];
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                $criteria->condition .= ' and isdeleted=false';
                
                if(isset($_GET['Besekpenerimaanbarang']))
                {
                    if($_GET['Besekpenerimaanbarang']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Besekpenerimaanbarang']['tanggal'])));
                    $criteria->compare('upper(nama)',  strtoupper($_GET['Besekpenerimaanbarang']['nama']),true);
                    $criteria->compare('upper(namaperusahaan)',strtoupper($_GET['Besekpenerimaanbarang']['namaperusahaan']),true);
                    $criteria->compare('upper(jumlah)',strtoupper($this->jumlah));
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'p.id desc',
                    'p.id',
                    'nama'=>array(
                                      'asc'=>'b.nama',
                                      'desc'=>'b.nama desc',
                                    ),
                    'namaperusahaan'=>array(
                                      'asc'=>'namaperusahaan',
                                      'desc'=>'namaperusahaan desc',
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

            $criteria->compare('barangid',$_POST['Besekpenerimaanbarang']['barangid']);
            $criteria->compare('supplierid',$_POST['Besekpenerimaanbarang']['supplierid']);
            $criteria->compare('divisiid',$_POST['Besekpenerimaanbarang']['divisiid']);
            if($_POST['Besekpenerimaanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Besekpenerimaanbarang']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Besekpenerimaanbarang']['jumlah']);
            $criteria->compare('beratlori',$_POST['Besekpenerimaanbarang']['beratlori']);
            $criteria->compare('totalbarang',$_POST['Besekpenerimaanbarang']['totalbarang']);
            $criteria->compare('createddate',$_POST['Besekpenerimaanbarang']['createddate']);
            $criteria->compare('userid',$_POST['Besekpenerimaanbarang']['userid']);
            $criteria->compare('isdeleted',$_POST['Besekpenerimaanbarang']['isdeleted']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Besekpenerimaanbarang']['lokasipenyimpananbarangid']);
            
            $jumlah = Penerimaanbarang::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Besekpenerimaanbarang']['barangid']);
            $criteria->compare('supplierid',$_POST['Besekpenerimaanbarang']['supplierid']);
            $criteria->compare('divisiid',$_POST['Besekpenerimaanbarang']['divisiid']);
            if($_POST['Besekpenerimaanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Besekpenerimaanbarang']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Besekpenerimaanbarang']['jumlah']);
            $criteria->compare('beratlori',$_POST['Besekpenerimaanbarang']['beratlori']);
            $criteria->compare('totalbarang',$_POST['Besekpenerimaanbarang']['totalbarang']);
            $criteria->compare('createddate',$_POST['Besekpenerimaanbarang']['createddate']);
            $criteria->compare('userid',$_POST['Besekpenerimaanbarang']['userid']);
            $criteria->compare('isdeleted',$_POST['Besekpenerimaanbarang']['isdeleted']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Besekpenerimaanbarang']['lokasipenyimpananbarangid']);
            
            $id = Penerimaanbarang::model()->find($criteria)->id;
                
            return $id;
        }
        
        public function cekExistKaryawan($dataKaryawan)
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('karyawanid',$dataKaryawan['karyawanid']);
            $criteria->compare('penerimaanbarangid',$dataKaryawan['penerimaanbarangid']);
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
