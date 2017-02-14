<?php
$this->pageTitle = "Form Set Stock - Besek";

$this->breadcrumbs=array(
	'Besek',
        'Form Set Stock',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Besek - Form Set Stock</b>
                    </div>   
                    <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>
                
                <div class="ibox-content">
                  <?php   $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
               // 'action'=>Yii::app()->createAbsoluteUrl("oemmkt/manajemencustomer/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
                //'method'=>'post',
                'enableAjaxValidation'=>true,
                'type'=>'horizontal',
                'id'=>'form',
                //'htmlOptions' => array('enctype' => 'multipart/form-data'),
        		'enableClientValidation'=>true,
        		'clientOptions'=>array(
        				'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
        				//'afterValidate'=>'js:myAfterValidateFunction'
        		)
        		
        )); ?>
                      <input type="hidden" name="supplierid" id="supplierid" value="<?php echo $supplierid; ?>" >
                      <input type="hidden" name="barangid" id="barangid" value="<?php echo $barangid; ?>" >
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Supplier</label>
                            <div class="col-lg-3">
                                <input class="form-control" disabled="disabled" value="<?php echo Supplier::model()->findByPk($supplierid)->namaperusahaan; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Barang</label>
                            <div class="col-lg-3">
                                <input class="form-control" disabled="disabled" value="<?php echo Barang::model()->findByPk($barangid)->nama; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-5 control-label">Lokasi</label>
                            <div class="col-lg-3">
                                <input class="form-control" disabled="disabled" value="<?php echo Lokasipenyimpananbarang::model()->findByPk(Yii::app()->session['lokasiid'])->nama; ?>">
                            </div>
                        </div>
                        <?php 
                                echo $form->textFieldGroup($model, 'jumlah',array(
                                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-2'),
                                                    'widgetOptions' => array(
                                                            'htmlOptions' => array(
                                                                    'onkeydown'=>'validateNumber(event);',  
                                                                    'value'=>$stocksupplier->jumlah
                                                            )
                                                    ),
                                                    'hint' => '<p id="error_jumlah" style="color:red !important;"></p>',
                                            )
                                ); 
                        ?>
                      
                        
                      <?php 
                            echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('stockupdate/setstockawal','supplierid'=>$supplierid,'barangid'=>$barangid)),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    

                                       if(data.result==="OK")
                                       {             
                                          location.href="'.Yii::app()->baseurl.'/besek/stockupdate/view/supplierid/" + data.supplierid+"/barangid/" +data.barangid;
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
                                ),array('id'=>'btnSave','class'=>'btn btn-primary'));      
                        ?> 
                      
                    <?php $this->endWidget(); ?>
                </div> 
            </div>	              
        </div>
    </div>
</div>

<script>
function simpan()
{
    var jumlah = $('#jumlah').val();
    if(jumlah!='')
    {
        
    }
}   

function validateNumber(evt) {
    var e = evt || window.event;
    var key = e.keyCode || e.which;

    if (!e.shiftKey && !e.altKey && !e.ctrlKey &&
    // numbers   
    key >= 48 && key <= 57 ||
    // Numeric keypad
    key >= 96 && key <= 105 ||
    // Backspace and Tab and Enter
    key == 8 || key == 9 || key == 13 ||
    // Home and End
    key == 35 || key == 36 ||
    // left and right arrows
    key == 37 || key == 39 ||
    // Del and Ins
    key == 46 || key == 45) {
        // input is VALID
    }
    else {
        // input is INVALID
        e.returnValue = false;
        if (e.preventDefault) e.preventDefault();
    }
}


</script>    