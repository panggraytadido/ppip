<?php
$this->pageTitle = "Data Diskon - Admin";

$this->breadcrumbs=array(
	'Admin',
        'Data Diskon',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">               
           <?php if(Yii::app()->user->hasFlash('success')):?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close close-sm" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>    
                    <?php endif; ?>     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Admin - Data Diskon</b>
                    </div>                                       
                </div>                                                
                <div class="ibox-content">
                       <?php $this->widget('booster.widgets.TbGridView', array(
                            'id'=>'grid',
                            'type' => 'striped bordered hover',
                            'dataProvider'=>$model->listDiskon(),
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
                                                    'name' => 'Datadiskon[tanggal]',
                                                    'attribute' => 'tanggal',
                                                    'htmlOptions' => array(
                                                                        'id' => 'Datadiskon_tanggal_index',
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
                                    array('name'=>'pelangganid','value'=>function($data,$row){
                                        return Pelanggan::model()->findByPk($data->pelangganid)->nama;
                                    },'header'=>'Pelanggan'),   
                                    array('name'=>'nofaktur','value'=>'$data->nofaktur','header'=>'No Faktur','filter'=>false),            
                                    array('name'=>'hargatotal','value'=>'$data->hargatotal','header'=>'TotalHarga','filter'=>false),                                                              
                                     array('name'=>'diskon','value'=>'number_format($data->diskon)','header'=>'Diskon','filter'=>false),    
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


<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Databalitunai_tanggal_index').datepicker();
}
");
?>


