<?php

class Outbox extends Penjualanbarang
{       
        public $kode;
        public $nama;
        public $namapelanggan;

        
        public function search()
	{
		$criteria=new CDbCriteria;

                $criteria->select = 'p.id, barangid, tanggal, jumlah, b.kode, b.nama, l.nama as namapelanggan, box, hargasatuan, hargatotal';
                $criteria->alias = 'p';
                $criteria->join = 'LEFT JOIN master.barang AS b ON p.barangid=b.id '; 
                $criteria->join .= 'LEFT JOIN master.pelanggan AS l ON p.pelangganid=l.id '; 
                $criteria->condition = 'p.divisiid='.Yii::app()->session['divisiid'];
                $criteria->condition .= ' and p.lokasipenyimpananbarangid='.Yii::app()->session['lokasiid'];
                $criteria->condition .= " and p.tanggal='".date("Y-m-d", time())."' ";
                $criteria->condition .= " and p.statuspenjualan=false";
                
                if(isset($_GET['Outbox']))
                {
                    $criteria->compare('upper(b.kode)',  strtoupper($_GET['Outbox']['kode']),true);
                    $criteria->compare('upper(b.nama)',  strtoupper($_GET['Outbox']['nama']),true);
                    $criteria->compare('upper(l.nama)',  strtoupper($_GET['Outbox']['namapelanggan']),true);
                    if($_GET['Outbox']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Outbox']['tanggal'])));
                    $criteria->compare('box',$this->box);
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
		));
	}
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
