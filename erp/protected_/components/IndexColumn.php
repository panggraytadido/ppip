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
class IndexColumn extends CGridColumn {

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
                echo $index;
        }
        
}
