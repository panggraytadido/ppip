<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Stockupdatehygienis extends Stocksupplier
{
    public $kode;
    public $nama;
    public $hargamodal;
    public $hargaeceran;
    public $hargagrosir;
	public $totalstock;
        
    public function listStock()
    {
        $criteria=new CDbCriteria;
        $divisiid = Divisi::model()->find("kode='3'")->id;
        $criteria->with=array('barang');  
        $criteria->condition = 'divisiid = :divisiid';
        $criteria->params = array(':divisiid' =>$divisiid);

        $sort = new CSort();					
        $sort->attributes = array(
                        'pb.tanggal',
                        'barang.kode',
                        'pelangganid',
                        'barang.nama', 
                        'supplierid',
                        'tanggal'
        );
        
        
        return new CActiveDataProvider('Stock', array(
            'criteria'=>$criteria,
            'sort'=>array(
                //'defaultOrder'=>'t.id ASC',
            ),
            'pagination'=>array(
                'pageSize'=>10,
            ),
        ));
    }
    
    public function listStockUpdate()
    {
		$criteria=new CDbCriteria;
                $divisiid = Divisi::model()->find("kode='3'")->id;
                $criteria->select = 'b.id as id,                                         
                    sum(s.jumlah) as totalstock';
                
                $criteria->alias = 's';               
                $criteria->join = 'INNER JOIN master.barang AS b ON b.id=s.barangid';
                $criteria->group= 'b.id';
                $criteria->condition = 'b.divisiid='.$divisiid;
                $criteria->order ='b.id asc';
                                        
                if(isset($_GET['Stockupdatehygienis']))
                {                                        
                    if($_GET['Stockupdatehygienis']['barang']!='')                                            
                     $criteria->compare('upper(b.nama)',  strtoupper($_GET['Stockupdatehygienis']['barang']),true);                     
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'b.id desc',                                              
                    
                );
                
		return new CActiveDataProvider('Stockupdatehygienis', array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
                        'pagination'=>array(
							'pageSize'=>10,
					),
		));
		
		/*
		$criteria=new CDbCriteria;
                $divisiid = Divisi::model()->find("kode='3'")->id;
                $criteria->select = 'hb.barangid as id,                                         
                    sum(s.jumlah) as totalstock,
                    CASE 
						WHEN sum(hb.hargamodal*s.jumlah ) <> 0 THEN (sum(hb.hargamodal*s.jumlah )/sum(s.jumlah))
						ELSE 0 
					END  as harga,
					CASE 
						WHEN sum(s.jumlah)<> 0 THEN (sum(hb.hargamodal*s.jumlah )/sum(s.jumlah)) * sum(s.jumlah)
						ELSE 0
					END  as total';
                
                $criteria->alias = 's';
                $criteria->join = 'INNER JOIN transaksi.hargabarang AS hb ON hb.barangid=s.barangid ';         
                $criteria->join .= ' INNER JOIN master.barang AS b ON b.id=hb.barangid';
                $criteria->group= 'hb.barangid';
                $criteria->condition = 's.barangid=s.barangid and b.divisiid='.$divisiid;
                $criteria->order ='hb.barangid asc';
                                        
                if(isset($_GET['Stockupdatehygienis']))
                {                                        
                    if($_GET['Stockupdatehygienis']['barang']!='')                                            
                     $criteria->compare('upper(b.nama)',  strtoupper($_GET['Stockupdatehygienis']['barang']),true);                     
                }

                $sort = new CSort();
                $sort->attributes = array(
                    'defaultOrder'=>'b.barangid desc',                                              
                    
                );
                
		return new CActiveDataProvider('Databarang', array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
                        'pagination'=>array(
							'pageSize'=>10,
					),
		));
		*/
    }
    
    /*
    public function listStockUpdate()
    {
            $divisiid = Divisi::model()->find("kode='3'")->id;
                
                $count=Yii::app()->db->createCommand(
                'SELECT 
                    count(*)
                   FROM 
                     transaksi.hargabarang, 
                     transaksi.stocksupplier,
                     master.barang
                   WHERE 
                     hargabarang.supplierid = stocksupplier.supplierid AND
                     hargabarang.barangid = stocksupplier.barangid 
                     AND hargabarang.barangid=barang.id and master.barang.divisiid='.$divisiid)->queryScalar();

            // If gv_hospitals was received from CGridview, then
            // filter records with a WHERE clause
            if( ($this->nama !== null) && 
                ($this->nama !== ''))
            {
                $criteria   = new CDbCriteria;
                $criteria->compare('hosp_name',$this->gv_hospitals, true);
                $condition  = $criteria->condition;
                $params     = $criteria->params;
                unset($criteria);

                $sql = Yii::app()->db->createCommand()
                ->select("
                        concat(tbl_hosp.hosp_id, tbl_dept.dept_id) AS compkey,
                        tbl_hosp.hosp_id    AS hosp_id,
                        tbl_hosp.hosp_name  AS hosp_name,
                        tbl_dept.dept_name  AS dept_name
                ")
                ->from("tbl_hosp, tbl_dept")
                ->where($condition, $params);
            }
            else
            {
                $sql = Yii::app()->db->createCommand('SELECT 
                    hargabarang.barangid as barangid,                     
                    master.barang.nama as barang,
                    sum(stocksupplier.jumlah) as totalstock,
                    (sum(hargabarang.hargamodal*stocksupplier.jumlah )/sum(stocksupplier.jumlah)) as harga,
                    (sum(hargabarang.hargamodal*stocksupplier.jumlah )/sum(stocksupplier.jumlah)) * sum(stocksupplier.jumlah) as total
                   FROM 
                     transaksi.hargabarang, 
                     transaksi.stocksupplier,
                     master.barang
                   WHERE 
                     hargabarang.supplierid = stocksupplier.supplierid AND
                     hargabarang.barangid = stocksupplier.barangid 
                     AND hargabarang.barangid=barang.id
                    group by hargabarang.barangid,master.barang.divisiid,master.barang.nama
                   having  master.barang.divisiid='.$divisiid.' order by hargabarang.barangid asc');
            }

            return new CSqlDataProvider($sql, array(

                // Use the composite key to keep the (hidden) key values of your
                // gridview rows unique,
                // because functions like getChecked() return key values of
                // checked ROWS - not the id or value of the checkboxes.
                'keyField' => 'barangid',

                'totalItemCount'=>$count,

                'sort' => array(

                    // Indicate what can be sorted
                    'attributes' => array(
                        'barangid'=>array(
                             'asc' =>'barangid ASC',
                             'desc'=>'barangid DESC',
                        ),                         
                    ),

                    // Default order in CGridview
                    'defaultOrder' => array( 
                        'barangid' => CSort::SORT_ASC,                        
                    ),
                ),

                'pagination'=>array(
                    'pageSize'=>20, //Show all records
                ),
            )); 
    }
     * 
     */
    
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}