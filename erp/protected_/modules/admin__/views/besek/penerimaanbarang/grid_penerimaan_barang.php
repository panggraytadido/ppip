<script>
    function refreshGridView()
    {
        $.fn.yiiGridView.update("grid-penerimaan");
    }
</script>
<br>
<?php 
    $this->widget('booster.widgets.TbGridView', array(
                                    'id'=>'grid-penerimaan',
                                    'type' => 'striped bordered hover',
                                    'dataProvider'=>$modelPenerimaanBarangBesek->listBarang(),
                                    'filter'=>$modelPenerimaanBarangBesek,                                    
                                    'columns'=>array(
                                            array(
                                                'header'=>'No',
                                                'class'=>'IndexColumn',
                                                'htmlOptions'=>array('width'=>'5px'),
                                            ),
                                            array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal Penerimaan',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Penerimaanbarangbesek[tanggal]',
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
                        )
                ),
                                            array('name'=>'supplierid','value'=>'$data->supplier->namaperusahaan','header'=>'Supplier','filter' => CHtml::listData(Supplier::model()->findAll(), 'id', 'namaperusahaan'),),
                                            array('name'=>'barang.kode','value'=>'$data->barang->kode','header'=>'Kode'),  
                                            array('name'=>'barang.nama','value'=>'$data->barang->nama','header'=>'Nama Barang'),
                                            array('name'=>'jumlah','value'=>'$data->jumlah','header'=>'Jumlah/Kg','filter'=>false),                         
                                    ),
)); ?>
