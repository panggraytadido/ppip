<?php

class Bongkarmuatpegawai extends Bongkarmuat
{
        public function searchBongkarmuatpegawai()
    	{
            $criteria=new CDbCriteria;

            $criteria->select = "id, tanggal, penerimaanbarangid, penjualanbarangid, upah";
            $criteria->condition = 'karyawanid='.Yii::app()->session['karyawanid'];
            $criteria->condition .= " and tanggal::text like '".date('Y-m-')."%' ";
            $criteria->condition .= ' and isdeleted=false';
            
            if(isset($_GET['Bongkarmuatpegawai']))
            {
                if($_GET['Bongkarmuatpegawai']['tanggal']!='')
                    $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Bongkarmuatpegawai']['tanggal'])));
                $criteria->compare('upah',$this->upah);
            }

            $sort = new CSort();
            $sort->attributes = array(
                'defaultOrder'=>'tanggal desc',
                'id',
                'tanggal',
                'penerimaanbarangid',
                'penjualanbarangid',
                'upah',
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
