<?php
$this->pageTitle = "Laporan - Data Pengeluaran";

$this->breadcrumbs=array(
	'Admin',
        'Cetak Rincian Data Pengeluaran',
);

?>

<div class="col-lg-12">
    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Cetak Rincian Data Pengeluaran</b>
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
                        
                    <?php 
						echo $form->datePickerGroup($model, 'tanggal', array(
											'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
											'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
											'widgetOptions' => array(
																	'htmlOptions'=>array(
																			'id'=>'Gudangpenjualanbarang_tanggal_input',
																			'readonly'=>'readonly',																			
																		)
															)
									)
								); 
					?>   

                    <?php
					echo $form->dropDownListGroup($model, 'divisi',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Divisi::model()->findAll("kode='3' OR kode='1' OR kode='5'"),'id','nama'),
                                            'htmlOptions' => array(
                                                    
                                            )
                                    )
                                )
                            );
        ?>                                        

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_btnCetak"></label>
                        <div class="col-sm-4">
                            <?php 
                            echo CHtml::ajaxSubmitButton('Cetak',CHtml::normalizeUrl(array('rincianpengeluaran/submit')),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       if(data.result==="OK")
                                       {  
											$("#AjaxLoader").hide();
                                          window.open("'.Yii::app()->baseurl.'/admin/rincianpengeluaran/cetak/tanggal/"+data.tanggal+"/divisi/"+data.divisi, "_blank");  
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
                            <!-- <button type="submit" id="btnCetak" name="btnCetak" class="btn btn-success"><li class="fa fa-print"></li> Cetak</button> -->
                        </div>
                    </div>
                    <?php $this->endWidget(); ?>    

                </div>
            </div>	              
        </div>
    </div>
</div>