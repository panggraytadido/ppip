<?php

/**
 * This is the model class for table "transaksi.lokasipenyimpananbarang".
 *
 * The followings are the available columns in table 'transaksi.lokasipenyimpananbarang':
 * @property string $id
 * @property string $nama
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 * @property boolean $isdeleted
 *
 * The followings are the available model relations:
 * @property Penerimaanbarang[] $penerimaanbarangs
 * @property Penjualanbarang[] $penjualanbarangs
 * @property Stock[] $stocks
 */

class Lokasipenyimpananbarang extends CActiveRecord
{
	/*
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Lokasipenyimpananbarang the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.lokasipenyimpananbarang';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nama, createddate, userid', 'required'),
			array('userid', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>255),
			array('updateddate, isdeleted', 'safe'),
			// The following rule is used by search().

			// @todo Please remove those attributes that should not be searched.
			// Please remove those attributes that should not be searched.
			array('id, nama, createddate, updateddate, userid, isdeleted', 'safe', 'on'=>'search'),
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
			'penerimaanbarang' => array(self::HAS_MANY, 'Penerimaanbarang', 'lokasipenyimpananbarangid'),
			'penjualanbarang' => array(self::HAS_MANY, 'Penjualanbarang', 'lokasipenyimpananbarangid'),
			'stocks' => array(self::HAS_MANY, 'Stock', 'lokasipenyimpananbarangid'),
                        'stocksupplier' => array(self::HAS_MANY, 'Stocksupplier', 'lokasipenyimpananbarangid'),

		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nama' => 'Nama',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
			'isdeleted' => 'Isdeleted',
		);
	}

	/**
<<<<<<< HEAD
=======
	 * Retrieves a list of models based on the current search/filter conditions.
>>>>>>> 725c4002799df0cd92a99b3500d7750c143b3eb3
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


		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('isdeleted',$this->isdeleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}


        /**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Lokasipenyimpananbarang the static model class
	 */

}

