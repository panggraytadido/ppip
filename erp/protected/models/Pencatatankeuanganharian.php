<?php

/**
 * This is the model class for table "transaksi.pencatatankeuanganharian".
 *
 * The followings are the available columns in table 'transaksi.pencatatankeuanganharian':
 * @property string $id
 * @property string $tanggal
 * @property integer $seratusribu
 * @property integer $limapuluhribu
 * @property integer $duapuluhribu
 * @property integer $sepuluhribu
 * @property integer $limaribu
 * @property integer $duaribu
 * @property integer $seribu
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 * @property integer $jumlah
 * @property integer $totalkeuanganharian
 * @property integer $kekurangan
 * @property integer $kelebihan
 */
class Pencatatankeuanganharian extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'transaksi.pencatatankeuanganharian';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('seratusribu, limapuluhribu, duapuluhribu, sepuluhribu, limaribu, duaribu, seribu, userid, jumlah, totalkeuanganharian, kekurangan, kelebihan', 'numerical', 'integerOnly'=>true),
                        //array('seratusribu, limapuluhribu, duapuluhribu, sepuluhribu, limaribu, duaribu, seribu, jumlah, totalkeuanganharian', 'required'),
			array('tanggal, createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tanggal, seratusribu, limapuluhribu, duapuluhribu, sepuluhribu, limaribu, duaribu, seribu, createddate, updateddate, userid, jumlah, totalkeuanganharian, kekurangan, kelebihan', 'safe', 'on'=>'search'),
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
			'tanggal' => 'Tanggal',
			'seratusribu' => '100.000,-',
			'limapuluhribu' => '50.000,-',
			'duapuluhribu' => '20.000,-',
			'sepuluhribu' => '10.000,-',
			'limaribu' => '5.000,-',
			'duaribu' => '2.000,-',
			'seribu' => '1.000,-',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'User',
			'jumlah' => 'Total Uang Perhitungan',
			'totalkeuanganharian' => 'Total Keuangan Harian',
			'kekurangan' => 'Kekurangan',
			'kelebihan' => 'Kelebihan',
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
		$criteria->compare('seratusribu',$this->seratusribu);
		$criteria->compare('limapuluhribu',$this->limapuluhribu);
		$criteria->compare('duapuluhribu',$this->duapuluhribu);
		$criteria->compare('sepuluhribu',$this->sepuluhribu);
		$criteria->compare('limaribu',$this->limaribu);
		$criteria->compare('duaribu',$this->duaribu);
		$criteria->compare('seribu',$this->seribu);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('jumlah',$this->jumlah);
		$criteria->compare('totalkeuanganharian',$this->totalkeuanganharian);
		$criteria->compare('kekurangan',$this->kekurangan);
		$criteria->compare('kelebihan',$this->kelebihan);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Pencatatankeuanganharian the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
