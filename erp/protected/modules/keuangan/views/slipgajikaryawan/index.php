<?php
$this->pageTitle = "Laporan - Slip Gaji Karyawan";

$this->breadcrumbs=array(
	'Keuangan',
        'Slip Gaji Karyawan',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Slip Gaji Karyawan</b>
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
                    
                    <?php   $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                             'action'=>Yii::app()->createAbsoluteUrl("keuangan/slipgajikaryawan/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
                             //'method'=>'post',
                             //'enableAjaxValidation'=>true,
                             'type'=>'horizontal',
                             'id'=>'form',
                             //'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                     //'enableClientValidation'=>true,
                                     'clientOptions'=>array(
                                                     'validateOnSubmit'=>true,
                                                     'validateOnChange'=>false,
                                                     //'afterValidate'=>'js:myAfterValidateFunction'
                                     )

                     )); ?>                    

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Jenis Karyawan</label>
                        <div class="col-sm-4">
                            <?php 
                                echo CHtml::dropDownList('jeniskaryawan', '', 
                                                array(
                                                        'Karyawan Gudang'=>'Karyawan Gudang',
                                                        'Karyawan Harian'=>'Karyawan Harian',
                                                        'Karyawan Bulanan'=>'Karyawan Bulanan',
                                                        'Karyawan Sopir'=>'Karyawan Sopir',
                                                    ),
                                                array('class'=>'form-control')
                                            ); 
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_btnCetak"></label>
                        <div class="col-sm-4">
                            <?php 
                            echo CHtml::ajaxSubmitButton('Cetak',CHtml::normalizeUrl(array('slipgajikaryawan/index')),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       if(data.result==="OK")
                                       {          
                                          if(data.jeniskaryawan==="Karyawan Gudang")
                                          {
                                                location.href="'.Yii::app()->baseurl.'/keuangan/slipgajikaryawangudang";
                                          }
                                          if(data.jeniskaryawan==="Karyawan Harian")
                                          {
                                                location.href="'.Yii::app()->baseurl.'/keuangan/slipgajikaryawanharian";
                                          }
                                          if(data.jeniskaryawan==="Karyawan Bulanan")
                                          {
                                                location.href="'.Yii::app()->baseurl.'/keuangan/slipgajikaryawanbulanan";
                                          }
                                          if(data.jeniskaryawan==="Karyawan Sopir")
                                          {
                                                location.href="'.Yii::app()->baseurl.'/keuangan/slipgajikaryawansopir";
                                          }
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