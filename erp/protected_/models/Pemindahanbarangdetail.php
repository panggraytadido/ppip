<?php

/**
 * This is the model class for table "transaksi.pemindahanbarangdetail".
 *
 * The followings are the available columns in table 'transaksi.pemindahanbarangdetail':
 * @property integer $id
 * @property integer $pemindahanbarangid
 * @property integer $supplierid
 * @property integer $jumlah
 * @property string $createddate
 * @property integer $userid
 * @property string $updateddate
 * @property boolean $isdeleted
 *
 * The followings are the available model relations:
 * @property Pemindahanbarang $pemindahanbarang
 */
class Pemindahanbarangdetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.pemindahanbarangdetail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('pemindahanbarangid, supplierid, userid', 'numerical', 'integerOnly'=>true),
			array('createddate, updateddate, isdeleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, pemindahanbarangid, supplierid, jumlah, createddate, userid, updateddate, isdeleted', 'safe', 'on'=>'search'),
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
			'pemindahanbarang' => array(self::BELONGS_TO, 'Pemindahanbarang', 'pemindahanbarangid'),
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
			'pemindahanbarangid' => 'Pemindahanbarangid',
			'supplierid' => 'Supplierid',
			'jumlah' => 'Jumlah',
			'createddate' => 'Createddate',
			'userid' => 'Userid',
			'updateddate' => 'Updateddate',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('pemindahanbarangid',$this->pemindahanbarangid);
		$criteria->compare('supplierid',$this->supplierid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('updateddate',$this->updateddate,true);
                $criteria->compare('isdeleted',$this->isdeleted);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pemindahanbarangdetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
