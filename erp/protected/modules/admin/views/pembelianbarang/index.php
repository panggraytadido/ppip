<?php
$this->pageTitle = "Admin - Pembelian Barang";

$this->breadcrumbs=array(
	'Admin',
        'Pembelian Barang',
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
                        <b>Laporan Pembelian Barang</b>
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

                    <?php                         
                    echo $form->dropDownListGroup($model, 'pilihan',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => array(1=>'Rincian Pembelian',2=>'Rincian Per Supplier',3=>'Total Per Supplier'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jenis',	
                                                    'onchange'=>'getValue(this);'
                                            )
                                    )));              
                ?>
                    
                <div id="supplier">    
                    <?php 
						 $criteria=new CDbCriteria;
						 $criteria->select="distinct s.id as id,s.namaperusahaan as nama";
						 $criteria->alias="t";						 
						 $criteria->join="INNER JOIN master.supplier s on s.id=t.supplierid";
						 $criteria->condition="supplierid!=0";
                        echo $form->dropDownListGroup($model, 'supplier',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                                'data' =>  CHtml::listData(Transfer::model()->findAll($criteria),'id','nama'),
                                                'htmlOptions' => array(
                                                        'prompt'=>'Pilih Supllier',										
                                                )
                                        )));              
                    ?>    
                </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_btnCetak"></label>
                        <div class="col-sm-4">
                            <?php 
                            echo CHtml::ajaxSubmitButton('Cetak',CHtml::normalizeUrl(array('pembelianbarang/submit')),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       if(data.result==="OK")
                                       {          
                                            if(data.pilihan=="1")
                                            {
                                                window.open("'.Yii::app()->baseurl.'/admin/pembelianbarang/printrincianpembelian", "_blank");  
                                            }                                          
                                            if(data.pilihan=="2")
                                            {
                                                window.open("'.Yii::app()->baseurl.'/admin/pembelianbarang/printrincianpersupplier/supplierid/"+data.supplierid, "_blank");  
                                            }
                                            if(data.pilihan=="3")
                                            {
                                                window.open("'.Yii::app()->baseurl.'/admin/pembelianbarang/printrincianallsupplier", "_blank");  
                                            }
											
											$("#AjaxLoader").hide();
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

<script>
    $('#supplier').hide();
    function getValue(opt)
    {
        if(opt.value==2)
        {
            $('#supplier').show();
        }
        else
        {
            $('#supplier').hide();
        }
    }
</script>    