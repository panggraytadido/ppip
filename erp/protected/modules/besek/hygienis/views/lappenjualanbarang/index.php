<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Laporan Penjualan Barang - Hygienis";

$this->breadcrumbs=array(
	'Laporan Penjualan Barang',
);

Yii::app()->clientScript->registerScript('search', "

$('.search-form form').submit(function(){
    $.fn.yiiGridView.update('bagian-grid', {
        data: $(this).serialize()
    });
    return false;
});
");

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
                        <b>Hygienis - Laporan Penjualan Barang</b>
                    </div>                   
                </div>
                
                <div class="ibox-content"> 
                <?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                //		'action'=>Yii::app()->baseUrl.'/admin/hargabarang/print',
                //		'method'=>'post',
                        'type'=>'horizontal',
                        'id'=>'form',
                )); ?>
    
                <?php   
                echo $form->dropDownListGroup($model, 'pilih',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' =>array('1'=>'Per Tanggal','2'=>'Per Bulan'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih ',
                                                    'onchange'=>'active();clearErr();'
                                            )
                                    )
                                )
                            );
        ?>
        <div style="display:none" id="Lappenjualanbaranghygienis_pilih_em_" class="help-block error"></div>
    
    <div id="tanggal">                
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
    </div>

                    
    <div id="bulan">                
    <?php   
                echo $form->dropDownListGroup($model, 'bulan',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' =>array('1'=>'Januari','2'=>'Februari','3'=>'Maret','4'=>'April','5'=>'Mei','6'=>'Juni','7'=>'Juli','8'=>'Agustus',
                                                '9'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
                                                ),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Bulan',                                                    
                                            )
                                    )
                                )
                            );
        ?>
    </div>                    
        
    <div style="float:left">            
        <?php 
                echo CHtml::ajaxSubmitButton('Print',CHtml::normalizeUrl(array('lappenjualanbarang/submit')),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                                                      
                    if(data.result==="OK")
                    {             
                        //location.href="'.Yii::app()->baseurl.'/hygienis/lappenjualanbarang/print/pilihan/" + data.pilihan+"/status/" +data.status    		             			             			             			             			             			             
						window.open("'.Yii::app()->baseurl.'/hygienis/lappenjualanbarang/print/pilihan/" + data.pilihan+"/status/" +data.status   , "_blank");  
                    }                                      
                    else
                    {                        
                        $.each(data, function(key, val) 
                        {
                            $("#form #"+key+"_em_").text(val);                                                    
                            $("#form #"+key+"_em_").show();
                        });
                    }       
                    $("#AjaxLoader").hide();
                }'               
                 ,                                    
                 'beforeSend'=>'function()
                  {                        
                       $("#AjaxLoader").show();           
             			
                  }'
                 ),array('id'=>'btnPrint','class'=>'btn btn-primary'));                                                         
         ?> 
	</div>
    
                    <br>
    

 </div>
				</div>	              
                </div>
            </div>
        </div>
<?php $this->endWidget(); ?>    

<script>
$('#tanggal').hide();    
$('#bulan').hide();    

function active()
{
    var option = $('#Lappenjualanbaranghygienis_pilih option:selected').val();    
    if(option==1)
    {
        $('#tanggal').show();    
        $('#bulan').hide();    
    }
    if(option==2)
    {
        $('#tanggal').hide();    
        $('#bulan').show();    
    }
}

function clearErr()
{
    $('#Lappenjualanbaranghygienis_pilih_em_').empty();
}
</script>