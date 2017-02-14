<?php

/**
 * This is the model class for table "report.gajikaryawanharian".
 *
 * The followings are the available columns in table 'report.gajikaryawanharian':
 * @property string $id
 * @property integer $tahun
 * @property string $tanggal
 * @property integer $totalgaji
 * @property integer $totalkasbon
 * @property integer $totalbayarkasbon
 * @property integer $sisakasbon
 * @property integer $uangmakan
 * @property integer $uangtransport
 * @property integer $insentive
 * @property integer $bonus
 * @property integer $karyawanid
 * @property string $createddate
 * @property string $updateddate
 * @property integer $userid
 * @property integer $gaji
 * @property string $bulan
 */
class Gajikaryawanharian extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'report.gajikaryawanharian';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('tahun, totalgaji, totalkasbon, totalbayarkasbon, sisakasbon, uangmakan, uangtransport, insentive, bonus, karyawanid, userid, gaji', 'numerical', 'integerOnly'=>true),
			array('bulan', 'length', 'max'=>2),
			array('tanggal, createddate, updateddate', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, tahun, tanggal, totalgaji, totalkasbon, totalbayarkasbon, sisakasbon, uangmakan, uangtransport, insentive, bonus, karyawanid, createddate, updateddate, userid, gaji, bulan', 'safe', 'on'=>'search'),
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
			'tahun' => 'Tahun',
			'tanggal' => 'Tanggal',
			'totalgaji' => 'Totalgaji',
			'totalkasbon' => 'Totalkasbon',
			'totalbayarkasbon' => 'Totalbayarkasbon',
			'sisakasbon' => 'Sisakasbon',
			'uangmakan' => 'Uangmakan',
			'uangtransport' => 'Uangtransport',
			'insentive' => 'Insentive',
			'bonus' => 'Bonus',
			'karyawanid' => 'Karyawanid',
			'createddate' => 'Createddate',
			'updateddate' => 'Updateddate',
			'userid' => 'Userid',
			'gaji' => 'Gaji',
			'bulan' => 'Bulan',
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
		$criteria->compare('tahun',$this->tahun);
		$criteria->compare('tanggal',$this->tanggal,true);
		$criteria->compare('totalgaji',$this->totalgaji);
		$criteria->compare('totalkasbon',$this->totalkasbon);
		$criteria->compare('totalbayarkasbon',$this->totalbayarkasbon);
		$criteria->compare('sisakasbon',$this->sisakasbon);
		$criteria->compare('uangmakan',$this->uangmakan);
		$criteria->compare('uangtransport',$this->uangtransport);
		$criteria->compare('insentive',$this->insentive);
		$criteria->compare('bonus',$this->bonus);
		$criteria->compare('karyawanid',$this->karyawanid);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updateddate',$this->updateddate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('gaji',$this->gaji);
		$criteria->compare('bulan',$this->bulan,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Gajikaryawanharian the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
