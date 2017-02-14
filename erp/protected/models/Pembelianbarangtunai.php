<?php

/**
 * This is the model class for table "transaksi.pembelianbarangtunai".
 *
 * The followings are the available columns in table 'transaksi.pembelianbarangtunai':
 * @property string $id
 * @property string $tanggal
 * @property integer $pelangganid
 * @property string $nama
 * @property integer $jumlah
 * @property integer $harga
 * @property integer $total
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 *
 * The followings are the available model relations:
 * @property Pelanggan $pelanggan
 */
class Pembelianbarangtunai extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.pembelianbarangtunai';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pelangganid, jumlah, harga, total, userid', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>100),
			array('tanggal, createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tanggal, pelangganid, nama, jumlah, harga, total, createddate, updateddate, userid', 'safe', 'on'=>'search'),
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
			'pelanggan' => array(self::BELONGS_TO, 'Pelanggan', 'pelangganid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'tanggal' => 'Tanggal',
			'pelangganid' => 'Pelangganid',
			'nama' => 'Nama',
			'jumlah' => 'Jumlah',
			'harga' => 'Harga',
			'total' => 'Total',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
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
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('pelangganid',$this->pelangganid);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('harga',$this->harga);
		$criteria->compare('total',$this->total);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pembelianbarangtunai the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
