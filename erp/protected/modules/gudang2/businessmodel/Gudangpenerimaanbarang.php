<?php

class Gudangpenerimaanbarang extends Penerimaanbarang
{       
        public $kode;
        public $nama;

        
        public function searchPenerimaanbarang()
		{
				$criteria=new CDbCriteria;

                $criteria->select = 'p.id, barangid, tanggal, jumlah, kode, nama';
                $criteria->alias = 'p';
                $criteria->join = 'LEFT JOIN master.barang AS b ON p.barangid=b.id '; 
                $criteria->condition = 'p.divisiid='.Yii::app()->session['divisiid'];
                $criteria->condition .= ' and p.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                $criteria->condition .= ' and isdeleted=false';
                
                if(isset($_GET['Gudangpenerimaanbarang']))
                {
                    $criteria->compare('upper(kode)',  strtoupper($_GET['Gudangpenerimaanbarang']['kode']),true);
                    $criteria->compare('upper(nama)',strtoupper($_GET['Gudangpenerimaanbarang']['nama']),true);
                    if($_GET['Gudangpenerimaanbarang']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Gudangpenerimaanbarang']['tanggal'])));
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

            $criteria->compare('barangid',$_POST['Gudangpenerimaanbarang']['barangid']);
            $criteria->compare('supplierid',$_POST['Gudangpenerimaanbarang']['supplierid']);
            $criteria->compare('divisiid',$_POST['Gudangpenerimaanbarang']['divisiid']);
            if($_POST['Gudangpenerimaanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpenerimaanbarang']['tanggal'])));
            //$criteria->compare('jumlah',$_POST['Gudangpenerimaanbarang']['jumlah']);
            $criteria->compare('beratlori',$_POST['Gudangpenerimaanbarang']['beratlori']);
            //$criteria->compare('totalbarang',$_POST['Gudangpenerimaanbarang']['totalbarang']);
            $criteria->compare('createddate',$_POST['Gudangpenerimaanbarang']['createddate']);
            $criteria->compare('userid',$_POST['Gudangpenerimaanbarang']['userid']);
            $criteria->compare('isdeleted',$_POST['Gudangpenerimaanbarang']['isdeleted']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
            
            $jumlah = Penerimaanbarang::model()->count($criteria);
                
            return $jumlah;
        }
        
        public function getId()
        {
            $criteria=new CDbCriteria;
            $criteria->select = 'id';
            
            $criteria->compare('barangid',$_POST['Gudangpenerimaanbarang']['barangid']);
            $criteria->compare('supplierid',$_POST['Gudangpenerimaanbarang']['supplierid']);
            $criteria->compare('divisiid',$_POST['Gudangpenerimaanbarang']['divisiid']);
            if($_POST['Gudangpenerimaanbarang']['tanggal']!='')
                $criteria->compare('tanggal', date("Y-m-d", strtotime($_POST['Gudangpenerimaanbarang']['tanggal'])));
            $criteria->compare('jumlah',$_POST['Gudangpenerimaanbarang']['jumlah']);
            $criteria->compare('beratlori',$_POST['Gudangpenerimaanbarang']['beratlori']);
            $criteria->compare('totalbarang',$_POST['Gudangpenerimaanbarang']['totalbarang']);
            $criteria->compare('createddate',$_POST['Gudangpenerimaanbarang']['createddate']);
            $criteria->compare('userid',$_POST['Gudangpenerimaanbarang']['userid']);
            $criteria->compare('isdeleted',$_POST['Gudangpenerimaanbarang']['isdeleted']);
            $criteria->compare('lokasipenyimpananbarangid',$_POST['Gudangpenerimaanbarang']['lokasipenyimpananbarangid']);
            
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
