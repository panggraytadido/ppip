<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class GrafikController extends Controller
{
    /**
	 * Lists all models.
	 */
	public function actionIndex()
	{
            $connection=Yii::app()->db;
            $sql="select sum(hargatotal) as hargatotal,EXTRACT(MONTH FROM tanggal) as bulan 
                     from transaksi.penjualanbarang  group by EXTRACT(MONTH FROM tanggal),EXTRACT(YEAR FROM tanggal)
			having EXTRACT(YEAR FROM tanggal)='2016'
                      order by EXTRACT(MONTH FROM tanggal) asc";					
            $totalPenjualan=$connection->createCommand($sql)->queryAll();                
                
            $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            
            
            for($i=0;$i<count($totalPenjualan);$i++)
            {                           
                foreach($dataBulan as $keyBulan=>$valueBulan)
                {
                    if($keyBulan==$totalPenjualan[$i]["bulan"])
                    {
                         $bulan[$i] = $valueBulan;                                               
                    }
                }                                                                           
            }
            
            
            $data = Yii::app()->Grafik->Total();                       
            $this->render('index2',array(
			'data'=>$data,       
                        'bulan'=>$bulan
		));
	}
    
}