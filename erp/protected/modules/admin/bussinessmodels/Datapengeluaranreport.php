<?php

class Datapengeluaranreport extends CFormModel
{       
       
    public $divisi;
    public $bulan;
    
    
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('divisi,bulan', 'required'),		                       
		);
	}    
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'divisi' => 'Divisi',
                        'bulan' => 'Bulan',			
		);
	}                        
}
