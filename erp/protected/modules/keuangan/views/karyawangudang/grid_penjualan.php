<?php 
$this->widget('booster.widgets.TbGridView', array(
	'id'=>'hygienispenerimaanbarang-grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$modelPenjualan->listKaryawanPenjualanBarang(),
	'filter'=>$modelPenjualan,
        //'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),
                 array('name'=>'divisi','value'=>'$data->divisi','header'=>'Divisi'),                
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal Penerimaan',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Hygienispenerimaanbarang[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'id' => 'Hygienispenerimaanbarang_tanggal_index',
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
		array('name'=>'karyawan','value'=>'$data->karyawan','header'=>'Karyawan'),                           
                array('name'=>'barang','value'=>'$data->barang','header'=>'Barang'),     
                array('name'=>'jumlah','value'=>'$data->jumlah','header'=>'Netto'),     
                array('name'=>'upah','value'=>'$data->upah','header'=>'Upah'),     		
	),
)); 
?>