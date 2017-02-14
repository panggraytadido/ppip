<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Stock Update - Hygienis";

$this->breadcrumbs=array(
	'Laporan Stcok Update',
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
                        <b>Hygienis - Stock Update Barang</b>
                    </div>                   
                </div>
                
                <div class="ibox-content"> 
                <?php $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                //		'action'=>Yii::app()->baseUrl.'/admin/hargabarang/print',
                //		'method'=>'post',
                        'type'=>'horizontal',
                        'id'=>'form',
                )); ?>
          
   
    <div style="float:left">            
        <?php 
                echo CHtml::ajaxSubmitButton('Print',CHtml::normalizeUrl(array('lapstockupdate/submit')),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                                                
                    if(data.result==="OK")
                    {             
                        //location.href="'.Yii::app()->baseurl.'/hygienis/lapstockupdate/print";    	             			             			             			             			             			             
						window.open("'.Yii::app()->baseurl.'/hygienis/lapstockupdate/print" , "_blank");  
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
    var option = $('#Lappenerimaanbaranghygienis_pilih option:selected').val();    
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
    $('#Lappenerimaanbaranghygienis_pilih_em_').empty();
}
</script>