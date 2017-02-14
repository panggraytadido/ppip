<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IndexColumn
 *
 * @author dido
 */
Yii::import('zii.widgets.grid.CGridColumn');

//component class untuk membuat nomor 
class Saldorekeninggrid extends CGridColumn {

    public $sortable = false;

    public function init() {
        parent::init();
    }

    protected function renderDataCellContent($row, $data) {
        if (isset($_GET['Saldobank'])) {           
            if ($_GET['Saldobank']['tanggal1'] != '' AND $_GET['Saldobank']['tanggal2'] != '') {
                                      
                    $tanggal1 = Yii::app()->DateConvert->ConvertTanggal($_GET['Saldobank']['tanggal1']);
                    $tanggal2 = Yii::app()->DateConvert->ConvertTanggal($_GET['Saldobank']['tanggal2']);
                    $rekeningId = $_GET['Saldobank']['rekeningid'];
            
                    $pagination = $this->grid->dataProvider->getPagination();                    
                    $index = $pagination->pageSize * $pagination->currentPage + $row + 1;

                    //saldo sebelumnya
                    $criteria = new CDbCriteria;
                    $criteria->condition = "rekeningid=".$rekeningId." and isdeleted=false and supplierid=0 "
                            . "and cast(t.tanggal as date)<= '$tanggal1' ";                
                    $criteria->order = 'id asc';
                    $datanya = Transfer::model()->findAll($criteria);
                    
                    $saldo = 0;
                    if (count($datanya) > 0) {
                        for ($i = 0; $i < count($datanya); $i++) {
                            if ($datanya[$i]["debit"] == 0) {
                                $saldo = $saldo + $datanya[$i]["debit"] - $datanya[$i]["kredit"];
                            } else {
                                $saldo = $saldo + $datanya[$i]["debit"];
                            }

                            $saldonya[] = $saldo;
                        }
                        //$data[] = $saldo;
                        //$saldo = end($saldoAkhir);
                    } else {
                        $saldonya = 0;
                    }
                    $saldoSblumnya = end($saldonya);
                    //
                      
                 
                    $criteria1 = new CDbCriteria;
                    $criteria1->condition = "rekeningid=" . $rekeningId . " and isdeleted=false "
                            . "and supplierid=0 and cast(t.tanggal as date)>= '$tanggal1' "
                            . "and cast(t.tanggal as date)<= '$tanggal2'";
                    $criteria1->order = 'id asc';
                    $data = Transfer::model()->findAll($criteria1);

                    $saldo = 0;
                    if (count($data) > 0) {
                        for ($i = 0; $i < count($data); $i++) {
                            if($i==0)
                            {
                                if ($data[$i]["debit"] == 0) {
                                $saldo = $saldo + $data[$i]["debit"]+$saldoSblumnya - $data[$i]["kredit"];
                                } else {
                                    $saldo = $saldo + $data[$i]["debit"]+$saldoSblumnya;
                                }
                            }
                            else{
                                if ($data[$i]["debit"] == 0) {
                                    $saldo = $saldo + $data[$i]["debit"] - $data[$i]["kredit"];
                                } else {
                                    $saldo = $saldo + $data[$i]["debit"];
                                }
                            }

                            $saldonya1[] = $saldo;
                        }
                        //$data[] = $saldo;
                        //$saldo = end($saldoAkhir);
                    } else {
                        $saldonya1 = 0;
                    }                    
                    
                    
                    $count = count($saldonya1);
                    for ($i = $count; $i > 0; $i--) {
                        $saldonya1[$i] = $saldonya1[$i - 1];
                    }
                    //unset($saldonya[0]);                                                          
                    echo number_format($saldonya1[$index]);
            }
                       
        } else {
            $pagination = $this->grid->dataProvider->getPagination();
            //$index = $pagination->pageSize * $pagination->currentPage + $row + 1;
            $index = $pagination->pageSize * $pagination->currentPage + $row + 1;

            $criteria = new CDbCriteria;
            $criteria->condition = 'rekeningid=' . $data->rekeningid . ' and isdeleted=false and supplierid=0';
            $criteria->order = 'id asc';
            $datanya = Transfer::model()->findAll($criteria);

            $saldo = 0;
            if (count($datanya) > 0) {
                for ($i = 0; $i < count($datanya); $i++) {
                    if ($datanya[$i]["debit"] == 0) {
                        $saldo = $saldo + $datanya[$i]["debit"] - $datanya[$i]["kredit"];
                    } else {
                        $saldo = $saldo + $datanya[$i]["debit"];
                    }

                    $saldonya[] = $saldo;
                }
                //$data[] = $saldo;
                //$saldo = end($saldoAkhir);
            } else {
                $saldonya = 0;
            }
            $count = count($saldonya);
            for ($i = $count; $i > 0; $i--) {
                $saldonya[$i] = $saldonya[$i - 1];
            }
            unset($saldonya[0]);
            echo number_format($saldonya[$index]);
        }
    }

}
