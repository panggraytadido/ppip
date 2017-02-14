<?php

class Perbaikandata extends Penjualanbarang
{            
        public $pelangganId;
        public $tanggalPembelian;
        public $jenisPembayaran;
        public $pembelianKe;
       

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        /**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(

			array('pelangganId, tanggalPembelian,pembelianKe', 'required'),			
		);
	}    
        
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(			
			'pelangganId' => 'Pelanggan',
			'tanggalPembelian' => 'Tanggal Pembelian',
			'jenisPembayaran' => 'Jenis Pembayaran',
                        'pembelianKe' => 'Pembelian Ke',
		);
	}          
        
        public function updatePerbaikanData($pelangganId,$tanggal,$jenis)
        {
            
        }
}
