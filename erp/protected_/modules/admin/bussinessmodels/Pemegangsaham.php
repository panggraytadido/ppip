<?php

class Pemegangsaham extends Jumlahsaham
{       
        
	public $kode;	
    public $anggotaid;
    public $barang;
    public $supplierid;
    public $hargamodal;
    public $hargagrosir;
    public $hargaeceran;
    public $jumlahsaham;
	public $totalsaham;
	public $pemegangsaham;
	public $tanggal;
	public $sahamid;
	public $createddate;
	public $userid;
	public $nama;
	public $hargasahamperlembar;
	
    
    /**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
            // NOTE: you should only define rules for those attributes that
            // will receive user inputs.
            return array(

                    array('tanggal,nama', 'required'),		
                    array('nama', 'safe', 'on'=>'search'),
            );
    }

    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
		
		);
	}
    
    public function listPemegangSaham()
    {
            $criteria=new CDbCriteria;

            $criteria->select = 'a.id, a.kode,a.nama ,count(a.id) as jumlahsaham,min(s.hargasahamperlembar) as hargasahamperlembar,sum(s.hargasahamperlembar) as totalsaham';

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
                     'a.nama'=>array(
                                      'asc'=>'a.nama',
                                      'desc'=>'a.nama desc',
                                    ),
					'jumlahsaham'=>array(
                                      'asc'=>'jumlahsaham',
                                      'desc'=>'jumlahsaham desc',
                                    ),	
					'hargasahamperlembar'=>array(
                                      'asc'=>'hargasahamperlembar',
                                      'desc'=>'hargasahamperlembar desc',
                                    ),	
					'totalsaham'=>array(
                                      'asc'=>'totalsaham',
                                      'desc'=>'totalsaham desc',
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
		$sql = "select count(*) as jumlah from transaksi.jumlahsaham where anggotaid=".$anggotaId;				
		$data=$connection->createCommand($sql)->queryRow();
		return $data["jumlah"];		
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
		if($totalSaham!="")
		{
			return $totalSaham;	
		}
		else
		{
			return 0;
		}	
		
		
	}
}
