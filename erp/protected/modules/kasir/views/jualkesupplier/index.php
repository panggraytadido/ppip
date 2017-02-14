<?php
$this->pageTitle = "Jual Ke Supplier - Kasir";

$this->breadcrumbs=array(
	'Kasir','Jual Ke Supplier'
);
?>

 <!-- loading -->
<div id="divLoading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.7;">
        <p style="position: absolute; color: White; top: 40%; left: 45%;">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/ajax-loader-big.gif"></img>
        </p>
</div>
<!-- -->


<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Penjualan Ke Supplier</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                <div class="ibox-content">      
<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->listData(),
	'filter'=>$model,
        'beforeAjaxUpdate' => 'js: function() {
                            $("#divLoading").show();
                        }',
                        'afterAjaxUpdate' => 'js: function() {                                        
                            $("#divLoading").hide();           
                            reinstallDatePicker();
                        }',
        'responsiveTable'=>TRUE,
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
                array(
            				'class'=>'booster.widgets.TbRelationalColumn',
            				//'name' => 'firstLetter',
            				'header'=>'Detail Barang',
            				'url' =>$this->createUrl("jualkesupplier/childinbox"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
                                    }'
            		),            
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Jualkesupplier[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'class'=>'form-control ct-form-control',
                                                     'id' => 'Jualkesupplier_tanggal',
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
                array('name'=>'supplier','value'=>'$data->supplier','header'=>'Supplier Pembeli',),		
		array(
							                    'header'=>'Action',
							                    'class'=>'booster.widgets.TbButtonColumn',
							                    'template'=>'{update} {delete}',
							                                    'buttons' => array(
							                                        'update' =>   array(
							                                                       'label'=>'Print Faktur',
                                                                                                               'icon'=>'fa fa-print',
                                                                                                                'url'=>'Yii::app()->createUrl("kasir/jualkesupplier/formfaktur", array("id"=>$data->id))', 							                                                       
							                                                        'options'=>array(
                                                                                                                    'class'=>'btn btn-success btn-xs',							                                                          
							                                                        ),
							                                                    ),
                                                                                               
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
<!-- content -->
				 </div>
				</div>	              
                </div>
            </div>
        </div>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Jualkesupplier_tanggal').datepicker();
}
");
?>


<!-- modal update -->
<div class="modal fade" id="modal-jenis-pembayaran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Set Jenis Pembayaran</h4>
        </div>
        <div class="modal-body">        
           <br>    
         
        </div>
          
        <div class="modal-footer">
          
        </div>

      </div>
    </div>
  </div>
<!--  -->

<script>
    $("#divLoading").hide();    
</script>    
