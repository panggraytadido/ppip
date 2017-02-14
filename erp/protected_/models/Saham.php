<?php

/**
 * This is the model class for table "master.saham".
 *
 * The followings are the available columns in table 'master.saham':
 * @property integer $id
 * @property string $nama
 * @property integer $hargasahamperlembar
 * @property string $nosurat
 */
class Saham extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Saham the static model class
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
		return 'master.saham';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('hargasahamperlembar', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>255),
			array('nosurat', 'length', 'max'=>30),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, nama, hargasahamperlembar, nosurat', 'safe', 'on'=>'search'),
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
			'hargasahamperlembar' => 'Harga Saham per Lembar',
			'nosurat' => 'No Surat',
			'tanggalpenetapan' => 'Tanggal Penetapan',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('nama',$this->nama,true);
		$criteria->compare('hargasahamperlembar',$this->hargasahamperlembar);
		$criteria->compare('nosurat',$this->nosurat,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}