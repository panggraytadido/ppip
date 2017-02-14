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
class Dashboard extends CApplicationComponent {

    //put your code here
    function getPengeluaran($tahun,$bulan) {
       $criteria = new CDbCriteria;
       $criteria->select="sum(total) as total";
       $criteria->condition = "EXTRACT(YEAR FROM tanggal)='$tahun' AND EXTRACT(MONTH FROM tanggal)='$bulan'";
       $data = Pengeluaran::model()->find($criteria);
       if(count($data)>0)
       {
           $data= number_format($data->total);
       }
       else
       {
           $data = 0;
       }
       
       return $data;
    }    
    
    
    function getPenjualan($tahun,$bulan)
    {
       $criteria = new CDbCriteria;
       $criteria->select="sum(jumlah) as jumlah";
       $criteria->condition = "EXTRACT(YEAR FROM tanggal)='$tahun' "
               . "AND EXTRACT(MONTH FROM tanggal)='$bulan' AND issendtokasir=true AND tanggalcetak is not null";
       $data = Penjualanbarang::model()->find($criteria);
       if(count($data)>0)
       {
           $data= $data->jumlah;
       }
       else
       {
           $data = 0;
       }
       
       return $data;
    }
    
    function getKeuntungan($tahun,$bulan)
    {
       $criteria = new CDbCriteria;
       $criteria->select="sum(labarugi) as labarugi";
       $criteria->condition = "EXTRACT(YEAR FROM tanggal)='$tahun' AND EXTRACT(MONTH FROM tanggal)='$bulan' "
               . "AND issendtokasir=true AND tanggalcetak is not null";
       $data = Penjualanbarang::model()->find($criteria);
       if(count($data)>0)
       {
           $data= number_format($data->labarugi);
       }
       else
       {
           $data = 0;
       }
       
       return $data;
    }
    
    function getPenerimaan($tahun,$bulan)
    {
       $criteria = new CDbCriteria;
       $criteria->select="sum(jumlah) as jumlah";
       $criteria->condition = "EXTRACT(YEAR FROM tanggal)='$tahun' AND EXTRACT(MONTH FROM tanggal)='$bulan'";
       $data = Penerimaanbarang::model()->find($criteria);
       if(count($data)>0)
       {
           $data= $data->jumlah;
       }
       else
       {
           $data = 0;
       }
       
       return $data;
    }
    
    
}
