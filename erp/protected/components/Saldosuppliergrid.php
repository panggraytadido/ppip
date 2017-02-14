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
class Saldosuppliergrid extends CGridColumn {

        public $sortable = false;

        public function init()
        {
                parent::init();
        }

        protected function renderDataCellContent($row,$data)
        {
                $pagination = $this->grid->dataProvider->getPagination();
                //$index = $pagination->pageSize * $pagination->currentPage + $row + 1;
                $index = $pagination->pageSize * $pagination->currentPage + $row +1;               
                
                $criteria = new CDbCriteria;
                $criteria->condition = 'supplierid=' . $data->supplierid . ' and isdeleted=false';
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
                } else {
                    $saldoAkhir = 0;
                }

                $count = count($saldoAkhir);
                for($i=$count; $i>0; $i--){
                    $saldoAkhir[$i] = $saldoAkhir[$i-1];
                }
                unset($saldoAkhir[0]);     
                echo number_format($saldoAkhir[$index]);

            }
}
        
               
        
