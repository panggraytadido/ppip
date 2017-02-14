<?php

/**
 * This is the model class for table "transaksi.penjualanbarang".
 *
 * The followings are the available columns in table 'transaksi.penjualanbarang':
 * @property string $id
 * @property integer $barangid
 * @property integer $divisiid
 * @property integer $pelangganid
 * @property string $tanggal
 * @property integer $jumlah
 * @property integer $hargatotal
 * @property integer $kategori
 * @property string $createddate

 * @property integer $lokasipenyimpananbarangid
 * @property integer $statuspenjualan
 *
 * The followings are the available model relations:
 * @property Barang $barang
 * @property Divisi $divisi
 * @property Pelanggan $pelanggan
 */
class Penjualanbarang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.penjualanbarang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('barangid, pelangganid, tanggal, lokasipenyimpananbarangid, jumlah, hargatotal, kategori, box, hargasatuan, statuspenjualan, supplierid', 'required'),
			array('barangid, divisiid, pelangganid, lokasipenyimpananbarangid, hargatotal, kategori, userid, box, labarugi, hargasatuan, netto', 'numerical', 'integerOnly'=>true),
                        array('jumlah', 'numerical', 'min'=>1),
                        array('tanggal, createddate, updateddate, isdeleted', 'safe'),
                    
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, barangid, divisiid, pelangganid, tanggal, jumlah, hargatotal, kategori, createddate, updateddate, userid, box, labarugi, hargasatuan, lokasipenyimpananbarangid, statuspenjualan, netto, jenispembayaran, supplierid, isdeleted', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'barang' => array(self::BELONGS_TO, 'Barang', 'barangid'),
			'divisi' => array(self::BELONGS_TO, 'Divisi', 'divisiid'),
			'pelanggan' => array(self::BELONGS_TO, 'Pelanggan', 'pelangganid'),
			'lokasipenyimpananbarang' => array(self::BELONGS_TO, 'Lokasipenyimpananbarang', 'lokasipenyimpananbarangid'),
                        'supplier' => array(self::BELONGS_TO, 'Supplier', 'supplierid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'barangid' => 'Barang',
			'divisiid' => 'Divisi',
			'pelangganid' => 'Pelanggan',
			'tanggal' => 'Tanggal',
			'jumlah' => 'Jumlah',
			'hargatotal' => 'Harga Total',
			'kategori' => 'Kategori',
			'createddate' => 'Created date',
			'updateddate' => 'Updated date',
			'userid' => 'Userid',
			'box' => 'Box',
			'labarugi' => 'Laba Rugi',
			'hargasatuan' => 'Harga Satuan',
                        'lokasipenyimpananbarangid' => 'Lokasi Barang',
                        'statuspenjualan' => 'Status Penjualan',
                        'netto' => 'Netto',
                        'jenispembayaran' => 'Jenis Pembayaran',
                        'supplierid' => 'Supplier',
                        'isdeleted' => 'Is Deleted',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('divisiid',$this->divisiid);
		$criteria->compare('pelangganid',$this->pelangganid);
		$criteria->compare('tanggal',$this->tanggal);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('hargatotal',$this->hargatotal);
		$criteria->compare('kategori',$this->kategori);
		$criteria->compare('createddate',$this->createddate,true);
                $criteria->compare('updateddate',$this->updateddate,true);
                $criteria->compare('userid',$this->userid);
                $criteria->compare('box',$this->box);
                $criteria->compare('labarugi',$this->labarugi,true);
                $criteria->compare('hargasatuan',$this->hargasatuan);
                $criteria->compare('lokasipenyimpananbarangid',$this->lokasipenyimpananbarangid);
                $criteria->compare('statuspenjualan',$this->statuspenjualan);
                $criteria->compare('netto',$this->netto);
                $criteria->compare('jenispembayaran',$this->jenispembayaran);
                $criteria->compare('supplierid',$this->supplierid);
                $criteria->compare('isdeleted',$this->isdeleted);
                
		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

        /**
         * Cek Stock yang tersedia, stock gudang, dan stock antrian (yg belum diproses divisi kasir)
         * 
         * @param type $attribute
         * @param type $params
         */
//        public function cekStock($attribute, $params)
//	{
//            if($this->barangid!='')
//            {
//                $stockGudang = Stock::model()->find('barangid='.$this->barangid)->jumlah;
//                
//                $criteria=new CDbCriteria;
//                $criteria->select = 'sum(jumlah) as jumlah';
//                $criteria->condition = 'statuspenjualan = false';
//                $criteria->condition .= ' and barangid = '.$this->barangid;
//                if($this->isNewRecord==false) // jika form update
//                    $criteria->condition .= ' and id != '.$this->id;
//                $stockAntrian = Penjualanbarang::model()->find($criteria)->jumlah;
//                
//                $stockTersedia = ($stockGudang - $stockAntrian);
//                
//		if ($this->$attribute > $stockTersedia)
//		{
//                    $this->addError($attribute, 'Stock Tersedia : <b>'.$stockTersedia.'</b>.<br />Stock Gudang : '.$stockGudang.'.<br />Stock Antrian : '.$stockAntrian);
//		}
//            }
//            
////            $condition="!value.match({$pattern})";
//// 
////            return "
////                        if(".$this->jumlah." > ".$stockTersedia.")
////                        {
////                            messages.push(".CJSON::encode('your password is too weak, you fool!').");
////                        }
////                        ";
//	}
        
	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Penjualanbarang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
