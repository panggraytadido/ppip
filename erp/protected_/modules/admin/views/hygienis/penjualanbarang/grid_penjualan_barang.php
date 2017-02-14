
<br>
<?php 
    $this->widget('booster.widgets.TbGridView', array(
                                    'id'=>'grid-penjualan',
                                    'type' => 'striped bordered hover',
                                    'dataProvider'=>$modelPenjualanBarangHygienis->listBarang(),
                                    'filter'=>$modelPenjualanBarangHygienis,                                    
                                    'columns'=>array(
                                            array(
                                                'header'=>'No',
                                                'class'=>'IndexColumn',
                                                'htmlOptions'=>array('width'=>'5px'),
                                            ),
                                            array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal Penjualan','filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Penjualanbaranggudangs[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'class'=>'form-control ct-form-control',
                                                ),
                                'options' => array(
                                    'format' => 'yyyy-mm-dd',
                                    'language' => 'en',
                                    'class'=>'form-controls form-control-inline input-medium default-date-picker',
                                    'size'=>"16"
                                )
                            ),true
                        )),
                                            array('name'=>'pelangganid','value'=>'$data->pelanggan->nama','header'=>'Pelanggan','filter' => CHtml::listData(Pelanggan::model()->findAll(), 'id', 'nama')),
                                            //array('name'=>'barang.kode','value'=>'$data->barang->kode','header'=>'Kode'),
                                            array('name'=>'barangid','value'=>'$data->barang->nama','header'=>'Barang','filter'=>false/*'filter' => CHtml::listData(Barang::model()->findAll(), 'id', 'nama')*/),        
                                            array('name'=>'barang.nama','value'=>'$data->box','header'=>'Box',), 
                                            array('name'=>'netto','value'=>'$data->netto','header'=>'Netto','filter'=>false),
                                            array('name'=>'harga','header'=>'Harga Satuan','filter'=>false,'value'=>'number_format($data->hargasatuan)'),
                                            array('name'=>'jumlah','value'=>'$data->jumlah','header'=>'Jumlah/Kg','filter'=>false),
                                            array('name'=>'hargatotal','value'=>'number_format($data->hargatotal)','header'=>'Harga Total','filter'=>false),                                                   
                                    ),
)); ?>

