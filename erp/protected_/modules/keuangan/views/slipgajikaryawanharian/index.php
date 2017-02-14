<?php
$this->pageTitle = "Laporan - Slip Gaji Karyawan Harian";

$this->breadcrumbs=array(
	'Keuangan',
        'Slip Gaji Karyawan Harian',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Slip Gaji Karyawan Harian</b>
                    </div>
					
					 <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('../keuangan/slipgajikaryawan'), array('class'=>'btn btn-default btn-xs')); ?>
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
                             'action'=>Yii::app()->createAbsoluteUrl("keuangan/slipgajikaryawanharian/"),//Yii::app()->createUrl($this->route),
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
                        <label class="col-sm-5 control-label required" for="jeniskaryawan">Jenis Karyawan</label>
                        <div class="col-sm-4">
                            <?php 
                                echo CHtml::dropDownList('jeniskaryawan', '', 
                                                CHtml::listData(Karyawan::model()->findAll("jeniskaryawanid=2"),'id','nama'),
                                                array('class'=>'form-control')
                                            ); 
                            ?>
                        </div>
                    </div>
                        
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="bulan">Bulan</label>
                        <div class="col-sm-4">
                            <?php 
                                echo CHtml::dropDownList('bulan', '', 
                                                array(
                                                        '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
                                                        '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
                                                        '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember',
                                                    ),
                                                array('class'=>'form-control')
                                            ); 
                            ?>
                        </div>
                    </div>
                        
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="tahun">Tahun</label>
                        <div class="col-sm-4">
                            <?php 
                                for($i=date("Y");$i>=2015;$i--)
                                    $thn[$i] = $i;
                                echo CHtml::dropDownList('tahun', '', 
                                                $thn,
                                                array('class'=>'form-control')
                                            ); 
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_btnCetak"></label>
                        <div class="col-sm-4">
                            <?php echo CHtml::submitButton('Cetak',array('class' => 'btn btn-success')); ?>
                            <!-- <button type="submit" id="btnCetak" name="btnCetak" class="btn btn-success"><li class="fa fa-print"></li> Cetak</button> -->
                        </div>
                    </div>
                        
                    <?php $this->endWidget(); ?>

                </div>
            </div>	              
        </div>
    </div>
</div>