<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Admin - Data Transfer';

$this->breadcrumbs=array(
	'Data Transfer',
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
                        <b>Admin - Data Transfer</b>
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
                    $criteria= new CDbCriteria;                    
                    echo $form->dropDownListGroup($model, 'nama',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => array('1'=>'Transfer Rekap','2'=>'Transfer Masuk','3'=>'Transfer Keluar','4'=>'Transfer Lain','5'=>'Transfer Kasir','6'=>'Transfer Antar Bank'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Data Transfer',										
                                            )
                                    )));              
                ?>
                                                                                                             
   
    <div style="float:left">            
        <?php 
                echo CHtml::ajaxSubmitButton('Submit',CHtml::normalizeUrl(array('datatransfer/submit')),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                                                      
                    if(data.result==="1")
                    {             
                        location.href="'.Yii::app()->baseurl.'/admin/transferrekap";    		             			             			             			             			             			             
                    }                                      
                    if(data.result==="2")
                    {
                        location.href="'.Yii::app()->baseurl.'/admin/transfermasuk"; 
                    }
                    if(data.result==="3")
                    {
                        location.href="'.Yii::app()->baseurl.'/admin/transferkeluar"; 
                    }
                    if(data.result==="4")
                    {
                        location.href="'.Yii::app()->baseurl.'/admin/transferlain"; 
                    }
                    if(data.result==="5")
                    {
                        location.href="'.Yii::app()->baseurl.'/admin/transferkasir"; 
                    }
                    if(data.result==="6")
                    {
                        location.href="'.Yii::app()->baseurl.'/admin/transferantarbank"; 
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
$('#tanggal1').hide();    
$('#tanggal2').hide();    

function active()
{
    var option = $('#Datapendapatan_pilihan option:selected').val();    
    if(option==1)
    {
        $('#tanggal1').show();           
         $('#tanggal2').hide();  
    }
    if(option==2)
    {
         $('#tanggal2').show();     
          $('#tanggal1').hide();  

    }
}

function clearErr()
{
    $('#Datapendapatan_pilihan_em_').empty();
}
</script>