<?php

class Datadiskon extends Faktur
{       
        public $status;
    
        public function listDiskon()
    	{
            $criteria=new CDbCriteria;

            //$criteria->select = "id, tanggal, jammasuk, jamkeluar, status";            
            $criteria->condition = 'diskon!=0';
            
            if(isset($_GET['Datadiskon']))
            {
                if($_GET['Datadiskon']['tanggal']!='')
                    $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Datadiskon']['tanggal'])));
                $criteria->compare('status',$this->status);
            }

            $sort = new CSort();
            $sort->attributes = array(
                'defaultOrder'=>'tanggal desc',
                'id',
                'nofaktur',
                'hargatotal',
                'diskon',
                'pelangganid',
            );
                
    		return new CActiveDataProvider($this, array(
    			'criteria'=>$criteria,
                            'sort'=>$sort,
                            'pagination'=>array(
                    'pageSize'=>10,
                ),
    		));
    	}
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
