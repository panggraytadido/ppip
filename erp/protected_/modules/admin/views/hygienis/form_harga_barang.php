<?php
$this->pageTitle = "Laporan - Slip Gaji Karyawan";

$this->breadcrumbs=array(
	'Admin',
	'Harga Barang',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Form Harga Barang</b>
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
                             'type'=>'horizontal',
                             'id'=>'form',                             
							 'clientOptions'=>array(
											 'validateOnSubmit'=>true,
											 'validateOnChange'=>false,
											 //'afterValidate'=>'js:myAfterValidateFunction'
							 )

                     )); ?>                    

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Jenis Harga Barang</label>
                        <div class="col-sm-4">
                            <?php 
                                echo CHtml::dropDownList('jenisharga', '', 
                                                array(
                                                        '1'=>'Internal',
                                                        '2'=>'Pelanggan',                                                        
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
                            echo CHtml::ajaxSubmitButton('Cetak',CHtml::normalizeUrl(array('hygienis/formhargabarang')),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       if(data.result==="OK")
                                       {          
                                          if(data.jenisharga==="1")
                                          {
												window.open("'.Yii::app()->baseurl.'/admin/hygienis/cetak/jenisharga/" + data.jenisharga, "_blank");                                                  
                                          }
                                          if(data.jenisharga==="2")
                                          {
                                                window.open("'.Yii::app()->baseurl.'/admin/hygienis/cetak/jenisharga/" + data.jenisharga, "_blank"); 
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