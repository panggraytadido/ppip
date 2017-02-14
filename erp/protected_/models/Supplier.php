<?php

/**
 * This is the model class for table "master.supplier".
 *
 * The followings are the available columns in table 'master.supplier':
 * @property integer $id
 * @property string $kode
 * @property string $namaperusahaan
 * @property integer $status
 * @property string $alamat
 * @property integer $telp
 * @property integer $fax
 * @property integer $hp
 * @property string $namapemilik
 * @property string $tanggalbermitra
 */
class Supplier extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Supplier the static model class
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
		return 'master.supplier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('kode, namaperusahaan, namapemilik, tanggalbermitra', 'required'),
			array('status, telp, fax, hp', 'numerical', 'integerOnly'=>true),
			array('kode', 'length', 'max'=>20),
			array('namaperusahaan', 'length', 'max'=>200),
			array('namapemilik', 'length', 'max'=>100),
			array('alamat', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, kode, namaperusahaan, status, alamat, telp, fax, hp, namapemilik, tanggalbermitra', 'safe', 'on'=>'search'),
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
                    'stocksupplier' => array(self::HAS_MANY, 'Stocksupplier', 'supplierid'),
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
			'namaperusahaan' => 'Namaperusahaan',
			'status' => 'Status',
			'alamat' => 'Alamat',
			'telp' => 'Telp',
			'fax' => 'Fax',
			'hp' => 'Hp',
			'namapemilik' => 'Namapemilik',
			'tanggalbermitra' => 'Tanggalbermitra',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		//$criteria->compare('id',$this->id);
		$criteria->compare('upper(kode)',  strtoupper($this->kode),true);
		$criteria->compare('upper(namaperusahaan)',  strtoupper($this->namaperusahaan),true);
                $criteria->compare('upper(namapemilik)',  strtoupper($this->namapemilik),true);
		//$criteria->compare('status',$this->status);
		//$criteria->compare('alamat',$this->alamat,true);
		//$criteria->compare('telp',$this->telp);
		//$criteria->compare('fax',$this->fax);
		//$criteria->compare('hp',$this->hp);
		//$criteria->compare('namapemilik',$this->namapemilik,true);
		//$criteria->compare('tanggalbermitra',$this->tanggalbermitra,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}