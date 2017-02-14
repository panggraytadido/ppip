<?php

/**
 * This is the model class for table "transaksi.pengeluaran".
 *
 * The followings are the available columns in table 'transaksi.pengeluaran':
 * @property string $id
 * @property integer $divisiid
 * @property string $tanggal
 * @property string $nama
 * @property integer $hargasatuan
 * @property integer $jumlah
 * @property integer $total
 * @property string $createddaate
 * @property string $updateddate
 * @property integer $userid
 *
 * The followings are the available model relations:
 * @property Divisi $divisi
 */
class Pengeluaran extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.pengeluaran';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('divisiid, hargasatuan, jumlah, total, userid', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>255),
			array('tanggal, createddaate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, divisiid, tanggal, nama, hargasatuan, jumlah, total, createddaate, updateddate, userid', 'safe', 'on'=>'search'),
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
			'divisi' => array(self::BELONGS_TO, 'Divisi', 'divisiid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'divisiid' => 'Divisiid',
			'tanggal' => 'Tanggal',
			'nama' => 'Nama',
			'hargasatuan' => 'Hargasatuan',
			'jumlah' => 'Jumlah',
			'total' => 'Total',
			'createddaate' => 'Createddaate',
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
		$criteria->compare('divisiid',$this->divisiid);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('hargasatuan',$this->hargasatuan);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('total',$this->total);
		$criteria->compare('createddaate',$this->createddaate,true);
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
	 * @return Pengeluaran the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
