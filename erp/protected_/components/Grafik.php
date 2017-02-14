<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DateConvert
 *
 * @author dido
 */
 
class Grafik extends CApplicationComponent {
    
    //put your code here
    
    function PenjualanBarangGudang()
    {
        
        $grafikPenjualanBarang = Penjualanbaranggudang::model()->grafikPenjualanBarang();	
        $dataChartPenjualanBarang = array();
        $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            

        if(count($grafikPenjualanBarang)>0)
        {
            for($i=0;$i<count($grafikPenjualanBarang);$i++)
            {                           
                foreach($dataBulan as $keyBulan=>$valueBulan)
                {
                    if($keyBulan==$grafikPenjualanBarang[$i]["bulan"])
                    {
                         $dataChartPenjualanBarang[] = array(
                            "name"=>$valueBulan,
                            "data"=>intval($grafikPenjualanBarang[$i]["hargasatuan"]),
                            //"stack"=>$valueBulan 
                        ); 
                    }
                }                                                                           
            }

            return $dataChartPenjualanBarang;
        }   
        else
        {
            return array();
        }
    }
    
    function PenjualanBarangBesek()
    {
        
        $grafikPenjualanBarang = Penjualanbarangbesek::model()->grafikPenjualanBarang();	
        $dataChartPenjualanBarang = array();
        $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            

        if(count($grafikPenjualanBarang)>0)
        {
            for($i=0;$i<count($grafikPenjualanBarang);$i++)
            {                           
                foreach($dataBulan as $keyBulan=>$valueBulan)
                {
                    if($keyBulan==$grafikPenjualanBarang[$i]["bulan"])
                    {
                         $dataChartPenjualanBarang[] = array(
                            "name"=>$valueBulan,
                            "data"=>array(intval($grafikPenjualanBarang[$i]["hargasatuan"])),
                            //"stack"=>$valueBulan 
                        ); 
                    }
                }                                                                           
            }

            return $dataChartPenjualanBarang;
        }   
        else
        {
            return array();
        }
    }
    
     function PenjualanBarangHygienis()
    {
        
        $grafikPenjualanBarang = Penjualanbaranghygienis::model()->grafikPenjualanBarang();	
        $dataChartPenjualanBarang = array();
        $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            

        if(count($grafikPenjualanBarang)>0)
        {
            for($i=0;$i<count($grafikPenjualanBarang);$i++)
            {                           
                foreach($dataBulan as $keyBulan=>$valueBulan)
                {
                    if($keyBulan==$grafikPenjualanBarang[$i]["bulan"])
                    {
                         $dataChartPenjualanBarang[] = array(
                            "name"=>$valueBulan,
                            "data"=>array(intval($grafikPenjualanBarang[$i]["hargasatuan"])),
                            //"stack"=>$valueBulan 
                        ); 
                    }
                }                                                                           
            }

            return $dataChartPenjualanBarang;
        }   
        else
        {
            return array();
        }
    }
    
    function PenerimaanBarangGudang()
    {
       $dataPenerimaanBarang = Penerimaanbaranggudang::model()->grafikPenerimaanBarangGudang();
         
       $total = array();
       foreach($dataPenerimaanBarang as $data) {
           $index = $this->CheckArr($data['bulan'], $total);
           if ($index < 0) {
               $total[] = $data;
           }
           else {
               $total[$index]['total'] +=  $data['total'];
           }
       }
       
       if(count($total)>0)
       {
           $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            
            for($i=0;$i<count($total);$i++)
             {                           
                 foreach($dataBulan as $keyBulan=>$valueBulan)
                 {
                     if($keyBulan==$total[$i]["bulan"])
                     {
                          $dataChartPenerimaanBarang[] = array(
                             "name"=>$valueBulan,
                             "data"=>intval($total[$i]["total"]),
                             //"stack"=>$valueBulan 
                         ); 
                     }
                 }                                                                           
             }     
            //print("<pre>".print_r($dataChartPenerimaanBarang,true)."</pre>");
            return $dataChartPenerimaanBarang;
       }
       else
       {
           return array();
       }
    }
    
    function PenerimaanBarangBesek()
    {
       $dataPenerimaanBarang = Penerimaanbarangbesek::model()->grafikPenerimaanBarangBesek();
         
       $total = array();
       foreach($dataPenerimaanBarang as $data) {
           $index = $this->CheckArr($data['bulan'], $total);
           if ($index < 0) {
               $total[] = $data;
           }
           else {
               $total[$index]['total'] +=  $data['total'];
           }
       }
       
       if(count($total)>0)
       {
           $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            
            for($i=0;$i<count($total);$i++)
             {                           
                 foreach($dataBulan as $keyBulan=>$valueBulan)
                 {
                     if($keyBulan==$total[$i]["bulan"])
                     {
                          $dataChartPenerimaanBarang[] = array(
                             "name"=>$valueBulan,
                             "data"=>array(intval($total[$i]["total"])),
                             //"stack"=>$valueBulan 
                         ); 
                     }
                 }                                                                           
             }     
            //print("<pre>".print_r($dataChartPenerimaanBarang,true)."</pre>");
            return $dataChartPenerimaanBarang;
       }
       else
       {
           return array();
       }                 
    }
    
    function PenerimaanBarangHygienis()
    {
       $dataPenerimaanBarang = Penerimaanbaranghygienis::model()->grafikPenerimaanBarangHygienis();
         
       $total = array();
       foreach($dataPenerimaanBarang as $data) {
           $index = $this->CheckArr($data['bulan'], $total);
           if ($index < 0) {
               $total[] = $data;
           }
           else {
               $total[$index]['total'] +=  $data['total'];
           }
       }
       
       if(count($total)>0)
       {
           $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            
            for($i=0;$i<count($total);$i++)
             {                           
                 foreach($dataBulan as $keyBulan=>$valueBulan)
                 {
                     if($keyBulan==$total[$i]["bulan"])
                     {
                          $dataChartPenerimaanBarang[] = array(
                             "name"=>$valueBulan,
                             "data"=>array(intval($total[$i]["total"])),
                             //"stack"=>$valueBulan 
                         ); 
                     }
                 }                                                                           
             }     
            //print("<pre>".print_r($dataChartPenerimaanBarang,true)."</pre>");
            return $dataChartPenerimaanBarang;
       }
       else
       {
           return array();
       }
    }
    
    public function Total()
    {
        //penjualan 
        $tahun = date('Y');        
        $connection=Yii::app()->db;
        $sql="select sum(hargatotal) as hargatotal,EXTRACT(MONTH FROM tanggal) as bulan 
                     from transaksi.penjualanbarang  group by EXTRACT(MONTH FROM tanggal),EXTRACT(YEAR FROM tanggal)
			having EXTRACT(YEAR FROM tanggal)='$tahun'
                      order by EXTRACT(MONTH FROM tanggal) asc";					
        $totalPenjualan=$connection->createCommand($sql)->queryAll();                
                
        $dataChartPenjualanBarang = array();
        $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            

       
        if(count($totalPenjualan)>0)
        {
            for($i=0;$i<count($totalPenjualan);$i++)
            {                           
                foreach($dataBulan as $keyBulan=>$valueBulan)
                {
                    if($keyBulan==$totalPenjualan[$i]["bulan"])
                    {
                         $dataChartPenjualanBarang[] = array(                                
                            "data"=>$totalPenjualan[$i]["hargatotal"]                            
                        ); 
                    }
                }                                                                           
            }
            
            $kategori = array('name'=>'penjualan');
            $totalPenjualan = array();
            for($i=0;$i<count($dataChartPenjualanBarang);$i++)
            {
                $totalPenjualan[$i]=intval($dataChartPenjualanBarang[$i]['data']);
            }
                        
            $dataTotalPenjualan = array_merge($kategori,array('data'=>$totalPenjualan));            
          
        }   
        else
        {
            return array();
        }
        //end penjualan 
        
        //penerimaan                
        $connection=Yii::app()->db;
        $sql="select sum(pb.jumlah)*sum(hb.hargamodal) as total,EXTRACT(MONTH FROM pb.tanggal) as bulan from transaksi.penerimaanbarang pb inner join transaksi.hargabarang hb on hb.barangid=pb.barangid
                group by EXTRACT(MONTH FROM pb.tanggal), EXTRACT(YEAR FROM pb.tanggal),pb.supplierid,hb.supplierid
                    having pb.supplierid=hb.supplierid and EXTRACT(YEAR FROM pb.tanggal)='$tahun'";					
        $totalPenerimaan=$connection->createCommand($sql)->queryAll();
        
        $total = array();
        foreach($totalPenerimaan as $data) {
           $index = $this->CheckArr($data['bulan'], $total);
           if ($index < 0) {
               $total[] = $data;
           }
           else {
               $total[$index]['total'] +=  $data['total'];
           }
        }
        
        if(count($total)>0)
        {
            $dataBulan = array(1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember');            
            for($i=0;$i<count($total);$i++)
             {                           
                 foreach($dataBulan as $keyBulan=>$valueBulan)
                 {
                     if($keyBulan==$total[$i]["bulan"])
                     {
                          $dataChartPenerimaanBarang[] = array(                          
                             "data"=>intval($total[$i]["total"]),
                             //"stack"=>$valueBulan 
                         ); 
                     }
                 }                                                                           
             }     
             
            $kategori = array('name'=>'penerimaan');
            $totalPenenerimaan = array();
            for($i=0;$i<count($dataChartPenerimaanBarang);$i++)
            {
                $totalPenenerimaan[$i]=intval($dataChartPenerimaanBarang[$i]['data']);
            }
                        
            $dataTotalPenerimaan = array_merge($kategori,array('data'=>$totalPenenerimaan));               
        }            
        //end penerimaan
        
        //keuntungan 
        $connection=Yii::app()->db;
        $sql="select sum(labarugi) as keuntungan,EXTRACT(MONTH FROM tanggal) as bulan from transaksi.penjualanbarang group by EXTRACT(MONTH FROM tanggal),EXTRACT(YEAR FROM tanggal) having EXTRACT(YEAR FROM tanggal)='$tahun'";					
        $totalKeuntungan=$connection->createCommand($sql)->queryAll();
        $kategori = array('name'=>'keuntungan');
        for($i=0;$i<count($totalKeuntungan);$i++)
        {
            $dataTotalKeuntungan[$i]=intval($totalKeuntungan[$i]['keuntungan']);
        }
        
        //
        $dataTotalKeuntungan = array_merge($kategori,array('data'=>$dataTotalKeuntungan));
       
        
        return array_merge(array($dataTotalPenjualan,$dataTotalPenerimaan,$dataTotalKeuntungan));        
    }
    
    public function TotalModal()
    {
        $tahun = date('Y');        
        $connection=Yii::app()->db;
        $sql="select sum(hargatotal) as hargatotal,EXTRACT(MONTH FROM tanggal) as bulan 
                     from transaksi.penjualanbarang  group by EXTRACT(MONTH FROM tanggal),EXTRACT(YEAR FROM tanggal)
			having EXTRACT(YEAR FROM tanggal)='$tahun'
                      order by EXTRACT(MONTH FROM tanggal) asc";					
        $data=$connection->createCommand($sql)->queryAll();
        return $data;
    }
    
    public function CheckArr($bulan,$array)
    {        
        $result = -1;
        for($i=0; $i<sizeof($array); $i++) {
            if ($array[$i]['bulan'] == $bulan) {
                $result = $i;
                break;
            }
        }
        return $result;
    }
        
    
}
