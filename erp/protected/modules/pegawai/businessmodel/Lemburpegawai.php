<?php

class Lemburpegawai extends Absensi
{
        public function searchLembur()
    	{
            $criteria=new CDbCriteria;

            $criteria->select = "id, tanggal, jammasuk, jamkeluar, status,jumlahjam";
            $criteria->condition = 'karyawanid='.Yii::app()->session['karyawanid'];
            $criteria->condition .= " and tanggal::text like '".date('Y-m-')."%' ";
            $criteria->condition .= ' and isdeleted=false and jenisabsensiid=2';
            
            if(isset($_GET['Lemburpegawai']))
            {
                if($_GET['Lemburpegawai']['tanggal']!='')
                    $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Lemburpegawai']['tanggal'])));
                $criteria->compare('status',$this->status);
            }

            $sort = new CSort();
            $sort->attributes = array(
                'defaultOrder'=>'tanggal desc',
                'id',
                'tanggal',
                'jammasuk',
                'jamkeluar',
                'status',
            );
                
    		return new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
                            'sort'=>$sort,
    		));
    	}
        
        public function getJumlahJam($absensiId)
        {
            $connection=Yii::app()->db;
            $sql ="SELECT floor(EXTRACT(EPOCH FROM (jamkeluar - jammasuk)/3600)) as jumlahjam
                    FROM transaksi.absensi where id=".$absensiId;
            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["jumlahjam"];
        }
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
