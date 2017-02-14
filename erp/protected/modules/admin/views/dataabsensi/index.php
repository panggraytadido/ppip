<?php
$this->pageTitle = "Admin - Absensi";

$this->breadcrumbs=array(
	'Admin',
        'Absensi',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">        
        
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Daftar Absensi</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
						<?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('databelitunai/create')),
                                array(
                                    'type'=>'POST',                      
                                    'url'=>"$(this).attr('href')",
                                    'beforeSend'=>'function()
                                    {                        
                                        $("#AjaxLoader").show();
                                    }',
                                    'success'=>'js:function(data) 
                                     {    
                                         $("#AjaxLoader").hide();
                                        $("#modal-create #body-create").html(data); 
                                        $("#modal-create").modal({backdrop: "static", keyboard: false});
                                    }'               
                                ),array('id'=>'btnCreate','class'=>'btn btn-primary btn-xs'));
                        ?> 
                    </div>
                </div>
                
                <div class="ibox-content">
                    
<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'absensipegawai-grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->searchAbsensi(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Absensipegawai[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'id' => 'Absensipegawai_tanggal_index',
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
		array('name'=>'karyawanid',
                    'value'=>function($data,$row){
                        return Karyawan::model()->findByPk($data->karyawanid)->nama;
                    },
                        'header'=>'Jam Masuk Lembur','filter'=>false),		
		 array('name'=>'jammasuk',
                    'value'=>function($data,$row){
                        if($data->jammasuk!='' || $data->jammasuk!=0)
                        {
                            return date("h:i:s",  strtotime($data->jammasuk));
                        }                        
                        else 
                        {
                            return '-';
                        }
                    },
                        'header'=>'Jam Masuk','filter'=>false),
                array('name'=>'jamkeluar',
                        'value'=>function($data,$row){
                        if($data->jamkeluar!='' || $data->jamkeluar!=0)
                        {
                            return date("h:i:s",  strtotime($data->jamkeluar));
                        }                        
                        else 
                        {
                            return '-';
                        }
                    },
                    'header'=>'Jam Keluar','filter'=>false),
                array('name'=>'jumlahjam','value'=>function($data,$row){
                    if($data->jumlahjam!='' || $data->jumlahjam!=0)
                    {
                        return $data->jumlahjam.' Jam';
                    }                    
                    else
                    {
                        return '0 Jam';
                    }
                },'header'=>'Jumlah Jam','filter'=>''),            
                array('name'=>'status','value'=>'($data->status==1) ? "Hadir" : "Izin"','header'=>'Status','type'=>'raw',
                        'filter'=>  CHtml::dropDownList('Absensipegawai[status]','status',
                                    array('1'=>'Hadir','2'=>'Izin'),
                                    array(
                                        'id'=>'Absensipegawai_status',
                                        'class'=>"form-control",
                                        'prompt'=>'Status',
                                    )),
                ),
                array('name'=>'jenisabsensiid','value'=>function($data,$row)
                            {
                                if($data->jenisabsensiid==1)
                                {
                                    return 'Jam Kerja';
                                }
                                else
                                {
                                    return 'Lembur';
                                }
                            },
                        'header'=>'Jenis Absensi','type'=>'raw',
                        'filter'=>  CHtml::dropDownList('Absensipegawai[jenisabsensiid]','jenisabsensi',
                                    array('1'=>'Jam Kerja','2'=>'Lembur'),
                                    array(
                                        'id'=>'Absensipegawai_status',
                                        'class'=>"form-control",
                                        'prompt'=>'Status',
                                    )),
                ),
	),
)); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Absensipegawai_tanggal_index').datepicker();
}
");
?>