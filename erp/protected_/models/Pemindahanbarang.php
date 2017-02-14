<?php

/**
 * This is the model class for table "transaksi.pemindahanbarang".
 *
 * The followings are the available columns in table 'transaksi.pemindahanbarang':
 * @property integer $id
 * @property integer $barangid
 * @property integer $jumlah
 * @property integer $lokasipemyinpananbarangid
 * @property integer $lokasipenyimpananbarangtujuanid
 * @property string $tanggal
 * @property integer $userid
 * @property string $createddate
 * @property string $updateddate
 *
 * The followings are the available model relations:
 * @property Pemindahanbarangdetail[] $pemindahanbarangdetails
 */
class Pemindahanbarang extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.pemindahanbarang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('barangid, lokasipemyinpananbarangid, lokasipenyimpananbarangtujuanid, userid, divisiid', 'numerical', 'integerOnly'=>true),
			array('tanggal, createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, barangid, jumlah, lokasipemyinpananbarangid, lokasipenyimpananbarangtujuanid, tanggal, userid, createddate, updateddate, divisiid', 'safe', 'on'=>'search'),
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
			'pemindahanbarangdetail' => array(self::HAS_MANY, 'Pemindahanbarangdetail', 'pemindahanbarangid'),
			'lokasipemyinpananbarang' => array(self::BELONGS_TO, 'Lokasipenyimpananbarang', 'lokasipemyinpananbarangid'),
			'barang' => array(self::BELONGS_TO, 'Barang', 'barangid'),
			'lokasipenyimpananbarangtujuan' => array(self::BELONGS_TO, 'Lokasipenyimpananbarang', 'lokasipenyimpananbarangtujuanid'),
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
			'jumlah' => 'Jumlah',
			'lokasipemyinpananbarangid' => 'Lokasi Pemyinpanan Barang',
			'lokasipenyimpananbarangtujuanid' => 'Lokasi Penyimpanan Barang Tujuan',
			'tanggal' => 'Tanggal',
			'userid' => 'User',
			'createddate' => 'Created date',
			'updateddate' => 'Updated date',
                        'divisiid' => 'divisiid'
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

		$criteria->compare('id',$this->id);
		$criteria->compare('barangid',$this->barangid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('lokasipemyinpananbarangid',$this->lokasipemyinpananbarangid);
		$criteria->compare('lokasipenyimpananbarangtujuanid',$this->lokasipenyimpananbarangtujuanid);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pemindahanbarang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
