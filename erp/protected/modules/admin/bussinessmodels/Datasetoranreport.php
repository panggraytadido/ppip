<?php

class Datasetoranreport extends Faktur
{       
                
    public $pelanggan;
        
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
        
    public function listDataSetoran()
    {
        $criteria=new CDbCriteria;

        $criteria->select = 'distinct f.nofaktur,f.bayar,f.sisa,f.hargatotal,f.tanggal,f.diskon,p.nama as pelanggan,f.id';

        $criteria->alias = 'f';
        $criteria->join = 'INNER JOIN transaksi.fakturpenjualanbarang AS fjb ON fjb.fakturid=f.id';         
        $criteria->join .= ' INNER JOIN transaksi.penjualanbarang AS pb ON pb.id=fjb.penjualanbarangid';
        $criteria->join .= ' INNER JOIN master.pelanggan AS p ON p.id=f.pelangganid';
        $criteria->condition = "pb.jenispembayaran='kredit' and statuspenjualan='false'";
        //$criteria->order = 'f.id';

        if(isset($_GET['Datasetoran']))
        {                                        
            if($_GET['Datasetoran']['nofaktur']!='')                                            
             $criteria->compare('upper(f.nofaktur)',  strtoupper ($_GET['Datasetoran']['nofaktur']),true);          
            
            if($_GET['Datasetoran']['pelanggan']!='')                                            
             $criteria->compare('upper(p.nama)',  strtoupper ($_GET['Datasetoran']['pelanggan']),true);                   
        }

        $sort = new CSort();
        $sort->attributes = array(
            'defaultOrder'=>'f.id desc',            
            'nofaktur'=>array(
                              'asc'=>'f.nofaktur',
                              'desc'=>'f.nofaktur desc',
                            ),
            'bayar'=>array(
                              'asc'=>'f.bayar',
                              'desc'=>'f.bayar desc',
                            ),
            'sisa'=>array(
                              'asc'=>'f.sisa',
                              'desc'=>'f.sisa desc',
                            ),
            'hargatotal',
            'tanggal',
            'diskon',
            'pelanggan',
        );

        return new CActiveDataProvider('Datasetoranreport', array(
                'criteria'=>$criteria,
                'sort'=>$sort,
                'pagination'=>array(
                                    'pageSize'=>20,
                                ),
        ));
    }
    
}
