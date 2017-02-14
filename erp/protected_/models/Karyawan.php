<?php

/**
 * This is the model class for table "master.karyawan".
 *
 * The followings are the available columns in table 'master.karyawan':
 * @property integer $id
 * @property string $nik
 * @property string $nama
 * @property string $jeniskelamin
 * @property string $alamat
 * @property string $tanggallahir
 * @property integer $jeniskaryawanid
 * @property string $tempatlahir
 * @property integer $hp
 * @property integer $jabatanid
 * @property string $photo
 * @property string $createddate
 * @property string $updatedate
 * @property integer $userid
 * @property string $tmtmasuk
 * @property string $tmtresign
 * @property integer $pendidikan
 *
 * The followings are the available model relations:
 * @property Jabatan $jabatan
 */
class Karyawan extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'master.karyawan';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('nik, nama, jeniskelamin, jabatanid', 'required'),
			array('jeniskaryawanid, hp, jabatanid, userid, pendidikan', 'numerical', 'integerOnly'=>true),
			array('nik', 'length', 'max'=>50),
			array('nama', 'length', 'max'=>255),
			array('jeniskelamin', 'length', 'max'=>1),
			array('tempatlahir', 'length', 'max'=>100),                       
			array('alamat, tanggallahir, createddate, updatedate, tmtmasuk, tmtresign', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, nik, nama, jeniskelamin, alamat, tanggallahir, jeniskaryawanid, tempatlahir, hp, jabatanid, createddate, updatedate, userid, tmtmasuk, tmtresign, pendidikan', 'safe', 'on'=>'search'),
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
			'jabatan' => array(self::BELONGS_TO, 'Jabatan', 'jabatanid'),
                        'jeniskaryawan' => array(self::BELONGS_TO, 'Jeniskaryawan', 'jeniskaryawanid'),
                        'absensi' => array(self::HAS_MANY, 'Absensi', 'karyawanid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'nik' => 'NIK',
			'nama' => 'Nama',
			'jeniskelamin' => 'Jenis Kelamin',
			'alamat' => 'Alamat',
			'tanggallahir' => 'Tanggal Lahir',
			'jeniskaryawanid' => 'Jenis Karyawan',
			'tempatlahir' => 'Tempat Lahir',
			'hp' => 'Hp',
			'jabatanid' => 'Jabatan',
			'photo' => 'Photo',
			'createddate' => 'Createddate',
			'updatedate' => 'Updatedate',
			'userid' => 'Userid',
			'tmtmasuk' => 'TMT',
			'tmtresign' => 'Tmtresign',
			'pendidikan' => 'Pendidikan',
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

		//$criteria->compare('id',$this->id);
		$criteria->compare('upper(nik)',  strtoupper($this->nik),true);
                /*
                if($this->nama!='')
                {    
                   $criteria->condition .= " upper(nama) like '$this->nama%'";                     
                }
                 * 
                 */
                    
		$criteria->compare('upper(nama)',  strtoupper($this->nama),true);
		$criteria->compare('jeniskelamin',$this->jeniskelamin,true);
		$criteria->compare('alamat',$this->alamat,true);
		$criteria->compare('tanggallahir',$this->tanggallahir,true);
		$criteria->compare('jeniskaryawanid',$this->jeniskaryawanid);
		$criteria->compare('tempatlahir',$this->tempatlahir,true);
		$criteria->compare('hp',$this->hp);
		$criteria->compare('jabatanid',$this->jabatanid);
		$criteria->compare('photo',$this->photo,true);
		$criteria->compare('createddate',$this->createddate,true);
		$criteria->compare('updatedate',$this->updatedate,true);
		$criteria->compare('userid',$this->userid);
		$criteria->compare('tmtmasuk',$this->tmtmasuk,true);
		$criteria->compare('tmtresign',$this->tmtresign,true);
		$criteria->compare('pendidikan',$this->pendidikan);
                
		$sort = new CSort();
		  $sort->attributes = array(
				'defaultOrder'=>'a.id desc',                
				'nik'=>array(
								  'asc'=>'nik',
								  'desc'=>'nik desc',
								),
				 'nama'=>array(
								  'asc'=>'nama',
								  'desc'=>'nama desc',
								),
				'jeniskelamin'=>array(
								  'asc'=>'jeniskelamin',
								  'desc'=>'jeniskelamin desc',
								),	
				'jabatanid'=>array(
								  'asc'=>'jabatanid',
								  'desc'=>'jabatanid desc',
								),	
				'photo'=>array(
								  'asc'=>'photo',
								  'desc'=>'photo desc',
								),				
									
							 
			);
		

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
                        'sort'=>$sort,
		));
	}
        
        public function getMaxId()
        {
            $connection=Yii::app()->db;
            $sql ="select max(id) as id from master.karyawan";
            
            $data=$connection->createCommand($sql)->queryRow();
            return $data["id"];
        }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Karyawan the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
