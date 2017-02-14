<?php

/**
 * This is the model class for table "mkmppg.tlu_divisi".
 *
 * The followings are the available columns in table 'mkmppg.tlu_divisi':
 * @property integer $id
 * @property string $nama
 * @property integer $perusahaanid
 * @property string $createddate
 * @property integer $createdby
 * @property string $updateddate
 * @property integer $updatedby
 * @property string $deleteddate
 * @property integer $deletedby
 * @property boolean $isdeleted
 *
 * The followings are the available model relations:
 * @property TluPerusahaan $perusahaan
 * @property TluProsesproduksi[] $tluProsesproduksis
 * @property TluDepartemen[] $tluDepartemens
 */
class TluDivisi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'master.tlu_divisi';
	}
    public $perusahaan_search;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('perusahaanid', 'required'),
			array('perusahaanid, createdby, deletedby', 'numerical', 'integerOnly'=>true),
			array('nama', 'length', 'max'=>100),
			array('createddate','default', 'value'=>new CDbExpression('NOW()'),'setOnEmpty'=>false,'on'=>'insert'),
			array('createdby','default', 'value'=>Yii::app()->user->id, 'setOnEmpty'=>false,'on'=>'insert'),
            array('id, nama, perusahaan.nama, perusahaan_search', 'safe', 'on'=>'search'),
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
			'perusahaan' => array(self::BELONGS_TO, 'TluPerusahaan', 'perusahaanid'),
			'tluProsesproduksis' => array(self::HAS_MANY, 'TluProsesproduksi', 'divisiid'),
			'tluDepartemens' => array(self::HAS_MANY, 'TluDepartemen', 'diivisiid'),
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
			'perusahaanid' => 'Perusahaan',
			'perusahaan.nama' => 'Perusahaan',
			'createddate' => 'Created Date',
			'createdby' => 'Created By',
			'deleteddate' => 'Deleted Date',
			'deletedby' => 'Deleted By',
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
		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
        $criteria->compare('LOWER(t.nama)',strtolower($this->nama),true);
        $criteria->with=array('perusahaan');
        $criteria->compare('LOWER(perusahaan.nama)',strtolower($this->perusahaan_search), true);

        $criteria->addCondition("t.isdeleted=false OR t.isdeleted is null");

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
			'sort'=>array(
                'defaultOrder'=>'t.id ASC',
            ),
            'pagination'=>array(
                'pageSize'=>Yii::app()->params['pageSize'],
            ),
        ));
	}

    public function setDelete()
    {
        $this->deleteddate = new CDbExpression("NOW()");
        $this->deletedby = Yii::app()->user->id;
        $this->isdeleted = true;
        $this->update(array('isdeleted','deleteddate','deletedby'));
    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TluDivisi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
