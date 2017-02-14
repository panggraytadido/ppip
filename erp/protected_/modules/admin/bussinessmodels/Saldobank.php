<?php

class Saldobank extends Transfer
{                       

        public $bank;
        public $supplier;
        public $tanggal;
        public $jenisTransfer;
        public $rekening;
        public $jumlah;
    
		public function rules()
		{
				// NOTE: you should only define rules for those attributes that
				// will receive user inputs.
				return array(

						array('jumlah,supplier,tanggal,jenisTransfer', 'required'),		                       
				);
		}    
		
		public function attributeLabels()
		{
			return array(
				'id' => 'ID',
				'jumlah' => 'Jumlah Pembayaran',				
			);
		}
      
        public function listSaldo($rekid)
        {
			//select saldo terakhir berdasarkan tanggal rekening dan tanggal terakhir
			$connection=Yii::app()->db;
			$sql ="select max(id) as id from transaksi.transfer where rekeningid=".intval($rekid);           
			$data=$connection->createCommand($sql)->queryRow();
			$id = $data["id"];                        
			if($id!="")
			{
				$saldo = Transfer::model()->findByPk($data["id"])->saldo;												
				if($saldo!="" || $saldo!=0)
				{
					$saldoTerakhir = $saldo;
				}
				else 
				{
					$saldoTerakhir = 0;
				}														
			}														
			else
			{
				$saldoTerakhir = 0;
			}	
			
			return $saldoTerakhir;
        }
	                           
        
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
