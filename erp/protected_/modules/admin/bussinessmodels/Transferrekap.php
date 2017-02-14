<?php

class Transferrekap extends Transfer
{                                                        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public function search()
        {
                // Warning: Please modify the following code to remove attributes that
                // should not be searched.

                $criteria=new CDbCriteria;
				$criteria->condition='isdeleted=false';

                if(isset($_GET['Transferrekap']))
                {                                        
                    if($_GET['Transferrekap']['tanggal']!='')
                        $criteria->compare('tanggal', date("Y-m-d", strtotime($_GET['Transferrekap']['tanggal'])));                                      
                                        
                }

                return new CActiveDataProvider($this, array(
                        'criteria'=>$criteria,
                ));
        }
}
