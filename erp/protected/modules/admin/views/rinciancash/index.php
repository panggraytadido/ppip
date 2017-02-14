<?php
$this->pageTitle = "Laporan - Rincian Cash";

$this->breadcrumbs=array(
	'Laporan',
        'Rincian Cash',
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Cetak Rincian Cash</b>
                    </div>
                </div>
                
                <div class="ibox-content">

                    <?php if(Yii::app()->user->hasFlash('success')):?>
                            <div class="alert alert-success fade in">
                                <button type="button" class="close close-sm" data-dismiss="alert">
                                    <i class="fa fa-times"></i>
                                </button>
                                <?php echo Yii::app()->user->getFlash('success'); ?>
                            </div>    
                    <?php endif; ?>

                    <?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                //		'action'=>Yii::app()->baseUrl.'/admin/hargabarang/print',
                //		'method'=>'post',
                        'type'=>'horizontal',
                        'id'=>'form',
                )); ?>

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="tanggal">Tanggal</label>
                        <div class="col-sm-4">
                            <?php
                            $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                                'name'=>'tanggal',
                                'options'=>array(
                                    'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
                                    
                                ),
                                'htmlOptions'=>array(
                                    'id'=>'tanggal',
                                    'class'=>'form-control',
                                    'style'=>'z-index:99999999999999;',
                                ),
                            ));
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_btnCetak"></label>
                        <div class="col-sm-4">
                            <?php 
                            echo CHtml::ajaxSubmitButton('Cetak',CHtml::normalizeUrl(array('rinciancash/submit')),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       if(data.result==="OK")
                                       {          
                                          window.open("'.Yii::app()->baseurl.'/admin/rinciancash/cetak/tanggal/"+data.tanggal, "_blank");  
                                       }
                                       else
                                       {                        
                                           $.each(data, function(key, val) 
                                            {
                                                $("#form #"+key+"_em_").text(val);                                                    
                                                $("#form #"+key+"_em_").show();
                                            });
                                       }       
                                   }'               
                                    ,                                    
                                    'beforeSend'=>'function()
                                     {                        
                                          $("#AjaxLoader").show();

                                     }'
                                ),array('id'=>'btnSave','class'=>'btn btn-success'));      
                        ?>    
                        </div>
                    </div>
                        
                   <?php $this->endWidget(); ?>   

                </div>
            </div>	              
        </div>
    </div>
</div>


<style type='text/css'>

#ui-datepicker-div {
    z-index: 99999999999999 !important;
}

</style>

<script>
    $('#ui-datepicker-div').load(function() {
        $('#ui-datepicker-div').css('z-index', '99999999999999 !important');
    });
</script>