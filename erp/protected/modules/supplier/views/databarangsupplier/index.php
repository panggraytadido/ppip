<?php
$this->pageTitle = "Data Barang - Supplier";

$this->breadcrumbs=array(
    'Supplier',
    'Data Barang',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Supplier - Data Barang</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>                        
                    </div>
                </div>
                
                <div class="ibox-content">
                        
    <?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'gudangpenjualanbarang-grid',
        'type' => 'striped bordered hover',
        'responsiveTable'=>true,
	'dataProvider'=>$model->search(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),		
                 array(
            				'class'=>'booster.widgets.TbRelationalColumn',
            				//'name' => 'firstLetter',
            				'header'=>'Detail Lokasi Barang',
            				'url' =>$this->createUrl("databarangsupplier/detail"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
            }'
            		),
		array('name'=>'divisi','value'=>'$data->divisi','header'=>'Divisi'),
		array('name'=>'barang','value'=>'$data->barang','header'=>'Nama Barang'),    
                array('name'=>'stockkeseluruhan','value'=>function($data,$row)
                {
                    $criteria = new CDbCriteria;
                    $criteria->select = 'sum(jumlah) as jumlah';
                    $criteria->condition = 'barangid='.$data->id;
                    //$criteria->condition .= ' AND barangid='.$data->id;
                    
                    $result = Stock::model()->find($criteria)->jumlah;
                    if($result!='')
                    {
                        echo $result;
                    }
                    else
                    {
                        echo '-';
                    }
                }
                ,'header'=>'Stock Keseluruhan','filter'=>false),
                        
                array('name'=>'terjual','value'=>function($data,$row){
                    $criteria = new CDbCriteria;
                    $criteria->select = 'sum(jumlah) as jumlah';
                    $criteria->condition = 'barangid='.$data->id.' AND supplierid='.Yii::app()->session['supplierid'];
                    $result = Penjualanbarang::model()->find($criteria)->jumlah;
                    if($result!='')
                    {
                        echo $result;
                    }
                    else
                    {
                        echo '-';
                    }
                            
                },'header'=>'Terjual','filter'=>false),
                array('name'=>'sisa','value'=>function($data,$row){                    
                    
                },'header'=>'Sisa','filter'=>false),
                /*
		array(
                    'header'=>'Action',
                    'class'=>'booster.widgets.TbButtonColumn',
                    'template'=>'{view} {update}',
                                    'buttons' => array(
                                        'view' =>   array(
                                                       'label'=>'detail',
                                                       'icon'=>'fa fa-search-plus',
                                                      'url'=>'Yii::app()->createUrl("gudang/penjualanbarang/view", array("id"=>$data->id))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                        ),
                                                    ),
                                        'update' =>   array(
                                                       'label'=>'edit',
                                                       'icon'=>'fa fa-edit',
                                                       'url'=>'Yii::app()->createUrl("gudang/penjualanbarang/update", array("id"=>$data->id))',
                                                        'options'=>array(
                                                            'class'=>'btn btn-success btn-xs',
                                                            'ajax'=>array(                                                                                    
                                                                'type'=>'POST',
                                                                'url'=>"js:$(this).attr('href')",
                                                                'success'=>'function(data) {
                                                                        $("#modal-update #body-update").html(data); 
                                                                        $("#modal-update").modal({backdrop: "static", keyboard: false});
                                                                }'
                                                            ),
                                                        ),
                                                    ),
                                        ),
                        ),
                        */
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
    $('#Gudangpenjualanbarang_tanggal').datepicker();
}
");
?>
