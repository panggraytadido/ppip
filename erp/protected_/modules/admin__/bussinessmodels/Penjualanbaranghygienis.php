<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Penjualanbaranghygienis extends Penjualanbarang
{          
    
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

			array('barangid, pelangganid, tanggal, jumlah', 'required'),
			//array('barangid, supplierid, divisiid, jumlah, beratlori, totalbarang, userid, lokasipenyimpananbarangid', 'numerical', 'integerOnly'=>true),			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			//array('id, kode,barangid, supplierid, divisiid, tanggal, jumlah, beratlori, totalbarang, createddate, userid, updatedate, isdeleted, lokasipenyimpananbarangid', 'safe', 'on'=>'search'),
		);
	}    
        
        public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'barangid' => 'Barang',
			'divisiid' => 'Divisi',
			'pelangganid' => 'Pelanggan',
			'tanggal' => 'Tanggal',
			'jumlah' => 'Jumlah',
			'hargatotal' => 'Harga Total',
			'kategori' => 'Kategori',
			'createddate' => 'Created date',
			'updateddate' => 'Updated date',
			'userid' => 'Userid',
			'box' => 'Box',
			'labarugi' => 'Laba Rugi',
			'hargasatuan' => 'Harga Satuan',
                        'lokasipenyimpananbarangid' => 'Lokasi Barang',
                        'statuspenjualan' => 'Status Penjualan',
		);
	}
    
    public function listBarang()
    {
        $criteria=new CDbCriteria;        
        $divisiid = Divisi::model()->find("kode='3'")->id;
        $criteria->alias = 'pb'; 
        $criteria->with=array('barang','pelanggan','divisi','lokasipenyimpananbarang');     
        $criteria->condition = 'pb.divisiid = :divisiid';
        $criteria->params = array(':divisiid' =>$divisiid);
        $criteria->order = 'pb.id desc';
        
        if(isset($_GET['Penjualanbaranggudang']))
        {
                    if($_GET['Penjualanbaranggudang']['pelangganid']!='')
                        $criteria->addCondition("pelangganid= $this->pelangganid");
                    
                    if($_GET['Penjualanbaranggudang']['barangid']!='')
                        $criteria->addCondition("barangid= $this->barangid");
                    
                        //$criteria->compare('pb.supplierid',$_GET['Penerimaanbaranggudang']['supplierid'],true);
                    //$criteria->compare('nama',$_GET['Penerimaanbaranggudang']['nama'],true);
                    if($_GET['Penjualanbaranggudangs']['tanggal']!='')
                        $criteria->compare('pb.tanggal', date("Y-m-d", strtotime($_GET['Penjualanbaranggudangs']['tanggal'])));
        }
        
        $sort = new CSort();					
        $sort->attributes = array(
                        'pb.tanggal',
                        'barang.kode',
                        'pelangganid',
                        'barang.nama', 
                        'supplierid',
                        'tanggal'
        );

        return new CActiveDataProvider('Penjualanbarang', array(
            'criteria'=>$criteria,
            'sort'=>$sort,
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
    }
    
    public function updateUpahBongkarMuat($upah,$penjualannBarangId)
    {
        $connection =Yii::app()->db;
    	$sql = "update transaksi.bongkarmuat set upah=$upah where penjualanbarangid=$penjualannBarangId";
    	$command = $connection->createCommand($sql);
    	$command->execute();
    }
    
    public function getHargaBarangEceran($barangid)
    {
        $connection=Yii::app()->db;
        $sql="select hargaeceran from master.barang where barang.id=$barangid";					

        $data=$connection->createCommand($sql)->queryRow();
        return number_format($data["hargaeceran"]);
    }

    public function getHargaBarangGrosir($barangid)
    {
        $connection=Yii::app()->db;
        $sql="select hargagrosir from master.barang where barang.id=$barangid";					

        $data=$connection->createCommand($sql)->queryRow();
        return number_format($data["hargagrosir"]);
    }
    
    public function checkStockPending($barangid,$divisiid,$lokasipenyimpananbarangid)
    {
        $connection=Yii::app()->db;
       $sql="select sum(jumlah) as totalstockpending from transaksi.penjualanbarang "
                . "where barangid=$barangid and divisiid=$divisiid and lokasipenyimpananbarangid=$lokasipenyimpananbarangid and statuspenjualan=false";					

        $data=$connection->createCommand($sql)->queryRow();
        return $data["totalstockpending"];
    }
    
    public function grafikPenjualanBarang()
    {
        $tahun = date('Y');
        $divisiid = Divisi::model()->find("kode='3'")->id;
        $connection=Yii::app()->db;
        $sql="select sum(hargasatuan) as hargasatuan,EXTRACT(MONTH FROM tanggal) as bulan  "
                    . "from transaksi.penjualanbarang  group by EXTRACT(MONTH FROM tanggal), EXTRACT(YEAR FROM tanggal),divisiid having EXTRACT(YEAR FROM tanggal)='$tahun' and divisiid=$divisiid order by EXTRACT(MONTH FROM tanggal) asc";					
        $data=$connection->createCommand($sql)->queryAll();
        return $data;
    }
    
    
    
}