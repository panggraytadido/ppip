<?php

class Dataabsensi extends Absensi
{       
        public function searchAbsensi()
    	{
            $criteria=new CDbCriteria;

            $criteria->select = "id, tanggal, jammasuk, jamkeluar, status,karyawanid";            
            $criteria->condition = 'isdeleted=false';
            
            if(isset($_GET['Absensipegawai']))
            {
                if($_GET['Absensipegawai']['tanggal']!='')
                    $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Absensipegawai']['tanggal'])));
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
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
