<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Datafaktur1 extends Faktur {
    //put your code here
    
    public $nik;
    public $tujuan;
    public $jumlah;
    public $karyawan;
    public $kendaraan;
    public $bayar;
    public $pelanggan;
    
    /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tanggal,karyawanid,jumlah', 'required'),			
                        array('tanggal,nofaktur', 'safe', 'on'=>'search'),
		);
	}
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'tanggal' => 'Tanggal',
			'karyawanid' => 'Karyawan',
			'jumlah' => 'jumlah',
		);
	}
    
    
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    
    public function listFaktur()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;
                $criteria->select="f.nofaktur,f.tanggal,pel.nama as pelanggan,f.pelangganid,f.hargatotal,f.bayar,f.sisa";
                $criteria->alias="f";
                $criteria->join="INNER JOIN master.pelanggan pel on pel.id=f.pelangganid";
		
                if(isset($_GET['Datafaktur1']))
                {
                    $criteria->compare('LOWER(nofaktur)',strtolower($this->nofaktur),true);
                    if($_GET['Datafaktur1']['tanggal']!='')
                    {
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Datafaktur1']['tanggal'])));
                    } 
                    
                    if($_GET['Datafaktur1']['pelanggan']!='')
                    {
                        $criteria->compare('upper(pel.nama)', strtoupper($_GET['Datafaktur1']['pelanggan']));
                    } 
                    
                    //$criteria->compare('LOWER(trfgsj.nomor)',strtolower($this->nomorsj),true);
                    //$criteria->compare('nama',$this->nama,true);
                }
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'pagination'=>array(
                            'pageSize'=>10,
                        ),
		));                
    }
    
    public function getTotalBayar($id)
    {
        $connection=Yii::app()->db;
        $sql ="select sum(pk.jumlah) as totalbayar from transaksi.kasbon k inner join transaksi.pembayarankasbon pk on pk.kasbonid=k.id where k.id=$id";            
        $data=$connection->createCommand($sql)->queryRow();
        return $data["totalbayar"];
    }
      
    public function detailFormFakturCetak($nofaktur)
    {
        $connection=Yii::app()->db;
        $sql ="select b.nama as barang,pb.box,pb.jumlah,pb.hargasatuan,pb.hargatotal,pb.kategori,pb.id as id,b.id as barangid from transaksi.faktur f inner join 
	transaksi.fakturpenjualanbarang fjb on fjb.fakturid=f.id
		inner join transaksi.penjualanbarang pb on pb.id=fjb.penjualanbarangid
			inner join master.barang b on b.id=pb.barangid
			where f.nofaktur='$nofaktur'";            
        $data=$connection->createCommand($sql)->queryAll();
        return $data;
    }   
    
}
