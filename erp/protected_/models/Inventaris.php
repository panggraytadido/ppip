<?php

/**
 * This is the model class for table "master.inventaris".
 *
 * The followings are the available columns in table 'master.inventaris':
 * @property integer $id
 * @property string $kode
 * @property integer $bagianid
 * @property integer $jumlah
 * @property integer $hargasatuan
 *
 * The followings are the available model relations:
 * @property Authmenu $bagian
 */
class Inventaris extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'master.inventaris';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('bagianid, jumlah, hargasatuan', 'numerical', 'integerOnly'=>true),
			array('kode', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, kode, bagianid, jumlah, hargasatuan', 'safe', 'on'=>'search'),
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
			'bagian' => array(self::BELONGS_TO, 'Bagian', 'bagianid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'kode' => 'Kode',
			'bagianid' => 'Bagian',
			'jumlah' => 'Jumlah',
			'hargasatuan' => 'Harga Satuan',
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
		$criteria->compare('kode',$this->kode,true);
		$criteria->compare('bagianid',$this->bagianid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('hargasatuan',$this->hargasatuan);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Inventaris the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
