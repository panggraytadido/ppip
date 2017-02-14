<?php
/* @var $this InboxController */
$this->pageTitle = "Data Barang - Kasir";

$this->breadcrumbs=array(
	'Kasir / Data Barang'
);
?>


 <!-- loading -->
<div id="divLoading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.7;">
        <p style="position: absolute; color: White; top: 40%; left: 45%;">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/ajax-loader-big.gif"></img>
        </p>
</div>
<!-- -->

<br>
<?php if(Yii::app()->user->hasFlash('success')):?>
        <div class="alert alert-success fade in">
            <button type="button" class="close close-sm" data-dismiss="alert">
                <i class="fa fa-times"></i>
            </button>
            <?php echo Yii::app()->user->getFlash('success'); ?>
        </div>    
<?php endif; ?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Data Barang</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                <div class="ibox-content">      
<?php
//print("<pre>".print_r($model->listDataBarang(),true)."</pre>");	
?>                     
<?php $this->widget('booster.widgets.TbGridView', array(
                                    'id'=>'grid',
                                    'type' => 'striped bordered hover',
                                    'dataProvider'=>$model->listDataBarang(),
                                    'beforeAjaxUpdate' => 'js: function() {
                                        $("#divLoading").show();
                                    }',
                                    'afterAjaxUpdate' => 'js: function() {                                        
                                        $("#divLoading").hide();                                                                                  
                                    }',
                                    'filter'=>$model,                                    
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
            				'url' =>$this->createUrl("databarang/detailbarang"),
            				'value'=> '"<span class=\"fa fa-plus\"></span>"',
            				'htmlOptions'=>array('width'=>'100px'),            				
            				'type'=>'html',
            				'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
            }'
            		),
                                            array('name'=>'barang',
                                            'value'=>function($data,$row)
                                            {
                                                return Barang::model()->findByPk($data->id)->nama;
                                            }    ,
                                            'header'=>'Barang'),                                              
                                            //array('name'=>'totalstock','value'=>'$data->totalstock','header'=>'Total Stock','filter'=>FALSE), 
                                            //array('name'=>'harga','value'=>'number_format($data->harga)','header'=>'Harga','filter'=>FALSE),
                                            //array('name'=>'total','value'=>'number_format($data->total)','header'=>'Total','filter'=>FALSE),
                                    ),
    
)); 

?>
<!-- content -->
				 </div>
				</div>	              
                </div>
            </div>
        </div>

<script>
$("#divLoading").hide();  
</script>