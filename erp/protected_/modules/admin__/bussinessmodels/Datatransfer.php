<?php

class Datatransfer extends CFormModel
{       
       
    public $nama;    
    
    
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('nama', 'required'),		                       
		);
	}    
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'nama' => 'Pilih Transfer',                        		
		);
	}                        
}
