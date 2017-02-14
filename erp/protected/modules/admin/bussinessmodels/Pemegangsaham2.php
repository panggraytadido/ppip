<?php

class Pemegangsaham extends Jumlahsaham
{       
        
	public $kode;	
    public $anggotaid;
	public $sahamid;
    public $barang;
    public $supplierid;
    public $hargamodal;
    public $hargagrosir;
    public $hargaeceran;
    public $jumlahsaham;
	public $totalsaham;
	public $pemegangsaham;
	public $tanggal;

	public $createddate;
	public $userid;
	public $nama;
	
   
	/**
	 * @return array validation rules for model attributes.
	 */

	
    /**
	 * @return array validation rules for model attributes.
	 */
   
    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
		
		
		);
	}
        
    public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'anggotaid' => 'Anggota',
			'sahamid' => 'Saham',
			'rekeningid' => 'Rekening',
			'jumlahsaham' => 'Jumlah Saham',
			'totalsaham' => 'Total Saham',
			'hargasaham' => 'Harga Per Lembar Saham',
			'dokumen' => 'Dokumen',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
			'kode' => 'No Saham',
		);
	}
	
    
    public function listPemegangSaham()
    {
            $criteria=new CDbCriteria;

            $criteria->select = 'a.id, a.kode,a.nama ,count(a.id) as jumlahsaham,sum(s.hargasahamperlembar) as totalsaham';

            $criteria->alias = 'js';
            $criteria->join = 'INNER JOIN master.anggota AS a ON js.anggotaid=a.id ';
			$criteria->join .= ' INNER JOIN master.saham AS s ON s.id=js.sahamid ';		
			$criteria->group ='a.kode,a.nama,a.id';	
           

            if(isset($_GET['Pemegangsaham']))
            {                                        
                if($_GET['Pemegangsaham']['pemegangsaham']!='')                                            
                 $criteria->compare('upper(a.nama)',  strtoupper ($_GET['Pemegangsaham']['pemegangsaham']),true);          
                
               if($_GET['Pemegangsaham']['kode']!='')                                            
                 $criteria->compare('upper(a.kode)',  strtoupper ($_GET['Pemegangsaham']['kode']),true);          
                
            }

            $sort = new CSort();
              $sort->attributes = array(
                    'defaultOrder'=>'a.id desc',                
                    'kode'=>array(
                                      'asc'=>'a.kode',
                                      'desc'=>'a.kode desc',
                                    ),
                     'nama'=>array(
                                      'asc'=>'a.nama',
                                      'desc'=>'a.nama desc',
                                    ),
                                 
                );


            return new CActiveDataProvider('Pemegangsaham', array(
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
	
	public function getJumlahSaham($anggotaId)
	{
		$connection=Yii::app()->db;
		$sql = "select hargasahamperlembar as jumlah from master.saham where id=".$sahamid;				
		$data=$connection->createCommand($sql)->queryRow();
		return $data["hargasahamperlembar"];		
	}
	public function getNilaiSaham($sahamId)
	{
		$connection=Yii::app()->db;
		$sql = "select hargasahamperlembar from master.saham where id=".$sahamId;				
		$data=$connection->createCommand($sql)->queryRow();
		return $data["hargasahamperlembar"];		
	}
	
	public function getTotalSaham($anggotaid)
	{
		$criteria=new CDbCriteria;

		$criteria->select = 'sum(s.hargasahamperlembar) as totalsaham';

		$criteria->alias = 'js';
		$criteria->join = 'INNER JOIN master.anggota AS a ON js.anggotaid=a.id ';
		$criteria->join .= ' INNER JOIN master.saham AS s ON s.id=js.sahamid ';	
		$criteria->condition = 'js.anggotaid='.$anggotaid;

		$totalSaham = Pemegangsaham::model()->find($criteria)->totalsaham;	
		return $totalSaham;
	}
}
