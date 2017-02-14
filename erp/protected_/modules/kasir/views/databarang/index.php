<?php
/* @var $this InboxController */
$this->pageTitle = "Data Barang - Kasir";

$this->breadcrumbs=array(
	'Kasir / Data Barang'
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

<!-- modal add -->
<div class="modal fade bs-example-modal-sm" id="modal-bagian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Tambah Data Barang</h4>
        </div>
        <div class="modal-body">           
           <br>
         <?php $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>
        <div class="modal-footer">
          <!--<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>-->
          <!--<button type="button" class="btn btn-primary">Save changes</button>-->
        </div>

      </div>
    </div>
 </div>
<!-- -->

<!-- modal add -->
<div class="modal fade bs-example-modal-sm" id="modal-update-bagian" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
            
          <button type="button" class="close" data-dismiss="modal">
              <span aria-hidden="true">×</span>
          </button>
            
          <h4 class="modal-title" id="myModalLabel">Update Data Barang</h4>
        </div>
        <div class="modal-body">                      
         
        </div>
          
        <div class="modal-footer">
          
        </div>          
      </div>
    </div>
  </div>
<!-- -->