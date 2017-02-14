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
class Saldo extends CApplicationComponent {

    //put your code here
    function getSaldoSupplier($supplierId) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'supplierid=' . $supplierId . ' and isdeleted=false';
        $criteria->order = 'id asc';
        $data = Transfer::model()->findAll($criteria);
        $saldo = 0;
        if (count($data) > 0) {
            for ($i = 0; $i < count($data); $i++) {
                if ($data[$i]["debit"] == 0) {
                    $saldo = $saldo + $data[$i]["debit"] - $data[$i]["kredit"];
                } else {
                    $saldo = $saldo + $data[$i]["debit"];
                }

                $saldoAkhir[] = $saldo;
            }
            $saldo = end($saldoAkhir);
        } else {
            $saldo = 0;
        }

        return $saldo;
    }
    
   
    function getSaldoRekening($rekeningId) {
        $criteria = new CDbCriteria;
        $criteria->condition = 'rekeningid=' . $rekeningId . ' and isdeleted=false and supplierid=0';
        $criteria->order = 'id asc';
        $data = Transfer::model()->findAll($criteria);
        /* $no = 1;
        echo '<table border=1><tr><th>No</th><th>Debit</th><th>Kredit</th><th>Saldo</th></tr>';
        $saldo = 0;
        for ($i = 0; $i < count($data); $i++) {
                    
            if ($data[$i]["debit"] == 0) {
                $saldo = $saldo + $data[$i]["debit"] - $data[$i]["kredit"];
            } else {
                $saldo = $saldo + $data[$i]["debit"];
            }
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $data[$i]["debit"] . '</td>';
            echo '<td>' . $data[$i]["kredit"] . '</td>';
            echo '<td>' . $saldo . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        //print("<pre>".print_r($data,true)."</pre>");
        die;
         * 
         */
        
        
        $saldo = 0;
        if (count($data) > 0) {
            for($i=0;$i<count($data);$i++)
            {
                if ($data[$i]["debit"] == 0) {
                    $saldo = $saldo + $data[$i]["debit"] - $data[$i]["kredit"];
                } else {
                    $saldo = $saldo + $data[$i]["debit"];
                }
                
                 $saldoAkhir[] = $saldo;
            }
            
            $saldo = end($saldoAkhir);
        } else {
            $saldo = 0;
        }
        
        return $saldo;        
    }

    function listSaldoSupplier($supplierid) {
        
        $criteria = new CDbCriteria;
        $criteria->condition = 'supplierid=' . $supplierid . ' and isdeleted=false';
        $criteria->order = 'id asc';
        $data = Transfer::model()->findAll($criteria);
        return $data;
        
        //print("<pre>".print_r($data,true)."</pre>");
        
        /*
        $no = 1;
        echo '<table border=1><tr><th>No</th><th>Debit</th><th>Kredit</th><th>Saldo</th></tr>';
        $saldo = 0;
        for ($i = 0; $i < count($data); $i++) {
            
            if($data[$i]["penerimaanbarangid"]!=0)
            {
                
            }
            
            if ($data[$i]["debit"] == 0) {
                $saldo = $saldo + $data[$i]["debit"] - $data[$i]["kredit"];
            } else {
                $saldo = $saldo + $data[$i]["debit"];
            }
            echo '<tr>';
            echo '<td>' . $no++ . '</td>';
            echo '<td>' . $data[$i]["debit"] . '</td>';
            echo '<td>' . $data[$i]["kredit"] . '</td>';
            echo '<td>' . $saldo . '</td>';
            echo '</tr>';
        }
        echo '</table>';
         * 
         */
        
    }

}
