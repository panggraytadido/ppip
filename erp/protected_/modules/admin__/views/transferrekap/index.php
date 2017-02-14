<?php
$this->pageTitle = "Admin - Transfer Rekap";

$this->breadcrumbs=array(
	'Admin',
        'Transfer Rekap',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Transfer Rekap</b>
                    </div>
                    
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>       
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('../admin/datatransfer'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>
                
                <div class="ibox-content">
                        <?php $this->widget('booster.widgets.TbGridView', array(
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$model->search(),
                            'filter'=>$model,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),
                             array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                                            'filter'=>  $this->widget(
                                                'booster.widgets.TbDatePicker',
                                                array(
                                                    'name' => 'Transferrekap[tanggal]',
                                                    'attribute' => 'tanggal',
                                                    'htmlOptions' => array(
                                                                        'id' => 'Transferrekap_tanggal_index',
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
                             array('name'=>'Bank','value'=>function($data,$row)
                                 {
                                    if($data->rekeningid!=0)
                                    {
                                        return Rekening::model()->findByPk($data->rekeningid)->namabank; 
                                    }                                        
                                    else
                                    {
                                        return '-';                                        
                                    }
                                                                              
                                 },'header'=>'Bank','filter'=>false),          
                             array('name'=>'rekeningid','value'=>function($data,$row){
                                    if($data->rekeningid!=0)
                                    {
                                        return Rekening::model()->findByPk($data->rekeningid)->norekening;
                                    }                                        
                                    else
                                    {
                                        return '-';                                        
                                    }
                                        
                             },'header'=>'No Rekening','filter'=>false),                                 
                             array('name'=>'Nama Pemilik','value'=>function($data,$row)
                                 {
                                    if($data->rekeningid!=0)
                                    {
                                        return Rekening::model()->findByPk($data->rekeningid)->namapemilik;                                    
                                    }                                        
                                    else
                                    {
                                        return '-';                                        
                                    }                                    
                                 },'header'=>'Nama Pemilik','filter'=>false), 
                                array('name'=>'debit','value'=>function($data,$row){
                                if($data->supplierid!=0)
                                 {
                                    //transfer ke supplier
                                     return Supplier::model()->findByPk($data->supplierid)->namaperusahaan;
                                 }
                                 elseif($data->pelangganid!=0)
                                 {
                                    //transfer dari pelanggang
                                     return Pelanggan::model()->findByPk($data->pelangganid)->nama;
                                 }
                                 elseif($data->nama!='')
                                 {
                                     //transfer lain
                                     return $data->nama;
                                 }
                                
                             },
                                'header'=>'Penyetor','filter'=>false),
                                 
                             array('name'=>'debit','value'=>'number_format($data->debit)','header'=>'Mutasi Debit','filter'=>false),
                             array('name'=>'kredit','value'=>'number_format($data->kredit)','header'=>'Mutasi Kredit','filter'=>false),
                             array('name'=>'saldo','value'=>'number_format($data->saldo)','header'=>'Saldo','filter'=>false),
                             array('name'=>'jenistransferid','value'=>function($data,$row){return Jenistransfer::model()->findByPk($data->jenistransferid)->nama;},'header'=>'Keterangan','filter'=>false),                             
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
    $('#Transferrekap_tanggal_index').datepicker();
}
");
?>


<div class="modal fade bs-example-modal-sm" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Transfer Kasir</h4>
        </div>
        <div class="modal-body" id="body-create">
             
        </div>
        <div class="modal-footer">
          &nbsp;
        </div>
      </div>
    </div>
  </div>
<!-- -->