<?php

/**
 * This is the model class for table "transaksi.penjualanbarangkesupplier".
 *
 * The followings are the available columns in table 'transaksi.penjualanbarangkesupplier':
 * @property string $id
 * @property integer $supplierid
 * @property integer $supplierpembeliid
 * @property integer $barangid
 * @property integer $lokasipenyimpananbarangid
 * @property string $tanggal
 * @property integer $divisiid
 * @property integer $jumlah
 * @property integer $hargatotal
 * @property integer $kategori
 * @property integer $box
 * @property integer $labarugi
 * @property integer $hargasatuan
 * @property integer $netto
 * @property integer $diskon
 * @property boolean $issendtokasir
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 */
class Penjualanbarangkesupplier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.penjualanbarangkesupplier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
                        array('barangid, pelangganid, tanggal, lokasipenyimpananbarangid, jumlah, hargatotal, kategori, box, hargasatuan, statuspenjualan, supplierid, supplierpembeliid, statuspenjualan', 'required'),
			array('supplierid, supplierpembeliid, barangid, lokasipenyimpananbarangid, divisiid, kategori, box, labarugi, hargasatuan, netto, diskon, userid', 'numerical', 'integerOnly'=>true),
			array('tanggal, issendtokasir, createddate, updateddate, isdeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, supplierid, supplierpembeliid, barangid, lokasipenyimpananbarangid, tanggal, divisiid, jumlah, hargatotal, kategori, box, labarugi, hargasatuan, netto, diskon, issendtokasir, createddate, updateddate, userid, statuspenjualan, isdeleted', 'safe', 'on'=>'search'),
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
                    'supplierpembeli' => array(self::BELONGS_TO, 'Supplier', 'supplierpembeliid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'supplierid' => 'Supplier',
			'supplierpembeliid' => 'Supplier Pembeli',
			'barangid' => 'Barang',
			'lokasipenyimpananbarangid' => 'Lokasi penyimpananbarang',
			'tanggal' => 'Tanggal',
			'divisiid' => 'Divisi',
			'jumlah' => 'Jumlah',
			'hargatotal' => 'Harga Total',
			'kategori' => 'Kategori',
			'box' => 'Box',
			'labarugi' => 'Laba Rugi',
			'hargasatuan' => 'Harga Satuan',
			'netto' => 'Netto',
			'diskon' => 'Diskon',
			'issendtokasir' => 'Issendtokasir',
			'createddate' => 'Created Date',
			'updateddate' => 'Updated Date',
			'userid' => 'Userid',
                        'statuspenjualan' => 'Status Penjualan',
                        'isdeleted' => 'Is Deleted'
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
		$criteria->compare('supplierid',$this->supplierid);
		$criteria->compare('supplierpembeliid',$this->supplierpembeliid);
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('lokasipenyimpananbarangid',$this->lokasipenyimpananbarangid);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('divisiid',$this->divisiid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('hargatotal',$this->hargatotal);
		$criteria->compare('kategori',$this->kategori);
		$criteria->compare('box',$this->box);
		$criteria->compare('labarugi',$this->labarugi);
		$criteria->compare('hargasatuan',$this->hargasatuan);
		$criteria->compare('netto',$this->netto);
		$criteria->compare('diskon',$this->diskon);
		$criteria->compare('issendtokasir',$this->issendtokasir);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
                $criteria->compare('statuspenjualan',$this->statuspenjualan);
                $criteria->compare('isdeleted',$this->isdeleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Penjualanbarangkesupplier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
