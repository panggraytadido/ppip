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
 
class DateConvert extends CApplicationComponent {
    
    //put your code here
    
    function ConvertTanggal($tanggal)
    {
	$data = explode("/",$tanggal);
		return trim($data[2])."-".trim($data[0])."-".trim($data[1]);
    }
    
    function Convertdate($tanggal)
    {
    	$data = explode("-",$tanggal);
    	return trim($data[2])."/".trim($data[1])."/".trim($data[0]);
    }
    
    
    function ConvertTanggalInput($tanggal)
    {
		$data = explode("-",$tanggal);
		return $data[0]."-".$data[1]."-".$data[2];
    }
    
    function ConvertDatelInput($tanggal)
    {
    	$data = explode("-",$tanggal);
    	return $data[2]."-".$data[1]."-".$data[0];
    }
    
    function DateAdd($interval, $number, $date) 
    {
       $jour=substr("$date", 0, 2);
       $mois=substr("$date", 3, 2);
       $annee=substr("$date", 6, 4);
       $adate = mktime(0,0,0,$mois,$jour,$annee);

       $date_time_array = getdate($adate);
       $hours = $date_time_array['hours'];
       $minutes = $date_time_array['minutes'];
       $seconds = $date_time_array['seconds'];
       $month = $date_time_array['mon'];
       $day = $date_time_array['mday'];
       $year = $date_time_array['year'];
       switch ($interval) 
       {
           case 'yyyy':
               $year+=$number;
               break;
           case 'q':
               $year+=($number*3);
               break;
           case 'm':
               $month+=$number;
               break;
           case 'y':
           case 'd':
           case 'w':
               $day+=$number;
               break;
           case 'ww':
               $day+=($number*7);
               break;  
       }
       $timestamp= mktime(0,0,0,$month,$day,$year);
       $jourascii=strftime("%d-%m-%Y",$timestamp);
       return $jourascii;
    }
    
}
