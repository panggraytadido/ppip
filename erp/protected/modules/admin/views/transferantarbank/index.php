<?php
$this->pageTitle = "Transfer - Transfer Antar Bank";

$this->breadcrumbs=array(
	'Admin',
        'Transfer Antar Bank',
);

?>
<br>
<?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="alert alert-success fade in">
                          <button type="button" class="close close-sm" data-dismiss="alert">
                              <i class="fa fa-times"></i>
                          </button>
                          <?php echo Yii::app()->user->getFlash('success'); ?>
                      </div>    
<?php endif; ?>

<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Transfer Antar Bank</b>
                    </div>
                    
                    <div class="pull-right">
						<a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>                        
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('../admin/datatransfer'), array('class'=>'btn btn-default btn-xs')); ?>  
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('transferantarbank/create')),
                                array(
                                    'type'=>'POST',                      
                                    'url'=>"$(this).attr('href')",
                                    'beforeSend'=>'function(){  $("#AjaxLoader").show(); }',
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
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$model->search(),
                            'filter'=>$model,                            
                            'columns'=>array(
                                    array(
                                        'header'=>'No',
                                        'class'=>'IndexColumn',
                                    ),
                             array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal','filter'=>  $this->widget(
                                                'booster.widgets.TbDatePicker',
                                                array(
                                                    'name' => 'Transferantarbank[tanggal]',
                                                    'attribute' => 'tanggal',
                                                    'htmlOptions' => array(
                                                                        'id' => 'Transferantarbank_tanggal_index',
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
                             array('name'=>'rekeningid','value'=>function($data,$row){
                                return Rekening::model()->findByPk($data->rekeningid)->norekening; 
                             },'header'=>'No Rekening','filter'=>false),    
                             array('name'=>'Bank','value'=>function($data,$row){
                                return Rekening::model()->findByPk($data->rekeningid)->namabank; 
                             },'header'=>'Bank','filter'=>false),       
                             array('name'=>'Nama Pemilik','value'=>function($data,$row){
                                return Rekening::model()->findByPk($data->rekeningid)->namapemilik; 
                             },'header'=>'Nama Pemilik','filter'=>false), 
							  array('name'=>'rekeningtujuanid','value'=>function($data,$row){
                                return Rekening::model()->findByPk($data->rekeningtujuanid)->namabank; 
                             },'header'=>'Rekening Tujuan','filter'=>false), 
                             array('name'=>'jumlah','value'=>'number_format($data->jumlah)','header'=>'Jumlah','filter'=>false),  
                             array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{delete}',
							                                    'buttons' => array(							                                                                                                                                     
                                                                                                'delete' => array
                                                                                                            (
                                                                                                                'label'=>'Hapus',
																												'url'=>'Yii::app()->createUrl("admin/transferantarbank/delete", array("id"=>$data->id))', 		
                                                                                                                'icon'=>'fa fa-trash-o',
                                                                                                                'options'=>array(
                                                                                                                    'class'=>'btn btn-danger btn-xs',
                                                                                                                ),
                                                                                                            ),
							                                    ),
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
    $('#Transferantarbank_tanggal_index').datepicker();
}
");
?>

<div class="modal fade bs-example-modal-sm" id="modal-create" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Transfer Antar Bank</h4>
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