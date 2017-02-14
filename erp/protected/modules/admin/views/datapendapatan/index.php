<?php
/* @var $this BagianController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = 'Admin - Data Pendapatan';

$this->breadcrumbs=array(
	'Form Data Pendapatan',
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
                        <b>Admin - Form Data Pendapatan</b>
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
                echo $form->dropDownListGroup($model, 'pilihan',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' =>array('1'=>'Per Divisi','2'=>'All Divisi'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih ',
                                                    'onchange'=>'active();clearErr();'
                                            )
                                    )
                                )
                            );
        ?>
        <div style="display:none" id="Datapendapatan_pilihan_em_" class="help-block error"></div>
        
    <div id="tanggal1">      
    <?php     
                $criteria= new CDbCriteria;
                    $criteria->condition="kode='1' OR kode='5' OR kode='3'";
                echo $form->dropDownListGroup($model, 'divisi',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Divisi::model()->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Divisi',										
                                            )
                                    )));              
				?>
        
    <?php 
            echo $form->datePickerGroup($model, 'tanggal1', array(
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
    
    <div id="tanggal2">                
    <?php 
            echo $form->datePickerGroup($model, 'tanggal2', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenjualanbarang_tanggal2_input',
                                                                'readonly'=>'readonly',                                                               
                                                            )
                                                )
                        )
                    ); 
        ?>
    </div> 
                       
    <div style="float:left">            
        <?php 
                echo CHtml::ajaxSubmitButton('Cetak',CHtml::normalizeUrl(array('datapendapatan/submit')),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                                                      
                    if(data.result==="OK")
                    {             
                        location.href="'.Yii::app()->baseurl.'/admin/datapendapatan/view/pilihan/" + data.pilihan+"/divisi/" +data.divisi+"/tanggal/" +data.tanggal;    		             			             			             			             			             			             
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