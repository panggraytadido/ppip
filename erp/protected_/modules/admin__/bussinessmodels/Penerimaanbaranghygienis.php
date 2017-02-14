<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Penerimaanbaranghygienis extends Penerimaanbarang
{
    //public $tanggal;
    public $nama;
    public $divisiid;
    public $supplierid;
    public $barangid;
    public $lokasipenyimpananbarangid;
    public $jumlah;
    public $beratlori;
    public $totalbarang;
    public $kode;
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('barangid, supplierid, tanggal, jumlah', 'required'),
			array('barangid, supplierid, divisiid, jumlah, beratlori, totalbarang, userid, lokasipenyimpananbarangid', 'numerical', 'integerOnly'=>true),			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, kode,barangid, supplierid, divisiid, tanggal, jumlah, beratlori, totalbarang, createddate, userid, updatedate, isdeleted, lokasipenyimpananbarangid', 'safe', 'on'=>'search'),
		);
	}    
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'barangid' => 'Barang',
			'supplierid' => 'Supplier',
			'divisiid' => 'Divisi',
			'tanggal' => 'Tanggal',
			'jumlah' => 'Jumlah',
			'beratlori' => 'Beratlori',
			'totalbarang' => 'Totalbarang',
			'createddate' => 'Createddate',
			'userid' => 'Userid',
			'updatedate' => 'Updatedate',
			'isdeleted' => 'Isdeleted',
			'lokasipenyimpananbarangid' => 'Lokasi Penyimpanan',
		);
	}
    
    public function listBarang()
    {
        $criteria=new CDbCriteria;        
        $divisiid = Divisi::model()->find("kode='3'")->id;
        $criteria->alias = 'pb'; 
        $criteria->with=array('barang','supplier');     
        $criteria->condition = 'pb.divisiid = :divisiid';
        $criteria->params = array(':divisiid' =>$divisiid);
        $criteria->order = 'pb.id desc';
        
        if(isset($_GET['Penerimaanbaranghygenis']))
        {
                    if($_GET['Penerimaanbaranghygenis']['supplierid']!='')
                        $criteria->addCondition("pb.supplierid= $this->supplierid");
                        //$criteria->compare('pb.supplierid',$_GET['Penerimaanbaranggudang']['supplierid'],true);
                    //$criteria->compare('nama',$_GET['Penerimaanbaranggudang']['nama'],true);
                    if($_GET['Penerimaanbaranghygenis']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Penerimaanbaranghygenis']['tanggal'])));
        }
        
        $sort = new CSort();					
        $sort->attributes = array(
                        'pb.tanggal',
                        'barang.kode',
                        'barang.nama', 
                        'supplierid',
                        'tanggal'
        );

        return new CActiveDataProvider('Penerimaanbarang', array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
    }
    
    public function updateUpahBongkarMuat($upah,$penerimaanBarangId)
    {
        $connection =Yii::app()->db;
    	$sql = "update transaksi.bongkarmuat set upah=$upah where penerimaanbarangid=$penerimaanBarangId";
    	$command = $connection->createCommand($sql);
    	$command->execute();
    }
        
    public function grafikPenerimaanBarangHygienis()
    {
        $tahun = date('Y');
         $divisiid = Divisi::model()->find("kode='3'")->id;
        $connection=Yii::app()->db;
        $sql="select sum(pb.jumlah)*sum(hb.hargamodal) as total,EXTRACT(MONTH FROM pb.tanggal) as bulan,pb.divisiid from transaksi.penerimaanbarang pb inner join transaksi.hargabarang hb on hb.barangid=pb.barangid
                group by EXTRACT(MONTH FROM pb.tanggal), EXTRACT(YEAR FROM pb.tanggal),pb.supplierid,hb.supplierid,pb.divisiid
                    having pb.supplierid=hb.supplierid and pb.divisiid=$divisiid and EXTRACT(YEAR FROM pb.tanggal)='$tahun'";					
        $data=$connection->createCommand($sql)->queryAll();
        return $data;
    }
    
    
    
}