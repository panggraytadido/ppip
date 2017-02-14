
<?php 
    /*
    $this->widget('booster.widgets.TbGridView', array(
                                    'id'=>'grid',
                                    'type' => 'striped bordered hover',
                                    'dataProvider'=>$modelStockupdate->listStock(),
                                    //'filter'=>$modelPenerimaanBarangGudang,                                    
                                    'columns'=>array(
                                            array(
                                                'header'=>'No',
                                                'class'=>'IndexColumn',
                                                'htmlOptions'=>array('width'=>'5px'),
                                            ),     
                                            array('name'=>'divisi','value'=>'$data->barang->divisi->nama','header'=>'Divisi'),
                                            array('name'=>'barang.kode','value'=>'$data->barang->kode','header'=>'Kode'),  
                                            array('name'=>'barang.nama','value'=>'$data->barang->nama','header'=>'Nama'),
                                            array('name'=>'lokasipenyimpananbarang.nama','value'=>'$data->lokasipenyimpananbarang->nama','header'=>'Lokasi Gudang'),
                                            array('name'=>'jumlah','value'=>'$data->jumlah','header'=>'Jumlah/Kg'),
                                            array('name'=>'hargamodal','value'=>'number_format($data->barang->hargamodal)','header'=>'Harga Modal'),
                                            array('name'=>'hargatotal','value'=>'number_format($data->jumlah*$data->barang->hargamodal)','header'=>'Harga Total'),
                                    ),
    
)); 
     * 
     */

$this->widget('booster.widgets.TbGridView', array(
                                    'id'=>'grid',
                                    'type' => 'striped bordered hover',
                                    'dataProvider'=>$modelStockupdate->listStockUpdate(),
                                    'filter'=>$modelStockupdate,                                    
                                    'columns'=>array(
                                            array(
                                                'header'=>'No',
                                                'class'=>'IndexColumn',
                                                'htmlOptions'=>array('width'=>'5px'),
                                            ),     
                                         array(
            				'class'=>'booster.widgets.TbRelationalColumn',
            				//'name' => 'firstLetter',
            				'header'=>'Detail Barang',
            				'url' =>$this->createUrl("gudang/detailstockupdate"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
            }'
            		),
                                            array('name'=>'barang','value'=>function($data,$row){
                                            return Barang::model()->findByPk($data->id)->nama;
                                            },'header'=>'Barang'), 
                                            array('name'=>'totalstock','value'=>'$data->totalstock','header'=>'Total Stock','filter'=>false), 
                                            //array('name'=>'harga','value'=>'number_format($data->harga)','header'=>'Harga','filter'=>false),
                                            //array('name'=>'total','value'=>'number_format($data->total)','header'=>'Total','filter'=>false),
                                    ),
    
)); 
    ?>

