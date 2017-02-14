<?php
$this->pageTitle = "Transfer - Transfer Kasir";

$this->breadcrumbs=array(
	'Keuangan',
        'Transfer Kasir',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Transfer Kasir</b>
                    </div>
                    
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                        <?php 
                            echo CHtml::ajaxButton('Tambah',CHtml::normalizeUrl(array('transferkasir/create')),
                                array(
                                    'type'=>'POST',                      
                                    'url'=>"$(this).attr('href')",
                                    'success'=>'js:function(data) 
                                     {    
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
                             array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal'), 
                             array('name'=>'tanggal','value'=>'$data->rekening->norekening','header'=>'No Rekening'),    
                             array('name'=>'Bank','value'=>'$data->rekening->namabank','header'=>'Bank','filter'=>false),       
                             array('name'=>'Nama Pemilik','value'=>'$data->rekening->namapemilik','header'=>'Nama Pemilik','filter'=>false), 
                             array('name'=>'jumlah','value'=>'number_format($data->jumlah)','header'=>'Jumlah','filter'=>false),  
                             array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{delete}',
							                                    'buttons' => array(							                                                                                                                                     
                                                                                                'delete' => array
                                                                                                            (
                                                                                                                'label'=>'Hapus',
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