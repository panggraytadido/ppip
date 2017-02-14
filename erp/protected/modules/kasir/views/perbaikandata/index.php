<?php
/* @var $this InboxController */
$this->pageTitle = "Perbaikan Data - Kasir";

$this->breadcrumbs=array(
	'Kasir / Perbaikan Data'
);
?>
<br>
<?php if(Yii::app()->user->hasFlash('success')):?>
                        <div class="alert alert-success fade in">
                            <button type="button" class="close close-sm" data-dismiss="alert">
                                <i class="fa fa-times"></i>
                            </button>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>    
                    <?php endif; ?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Perbaikan Data</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
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
                                        )

                        )); ?>
	
        <div id="hideCont">            
        <?php 
            echo $form->datePickerGroup($model, 'tanggalPembelian', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'tanggalPembelian',
                                                                'readonly'=>'readonly',
                                                               
                                                            )
                                                )
                        )
                    ); 
        ?>    
            

        <?php     
                echo $form->select2Group($model, 'pelangganId',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Pelanggan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pelanggan',
                                                    'onchange'=>'pembelianKe(this);'
                                            )
                                    )));              
	?>
            
        <?php
                echo $form->dropDownListGroup($model, 'pembelianKe',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => array(),
                                            'htmlOptions' => array(
                                                   
                                            )
                                    )
                                )
                            );
        ?>    
        </div>
                
        <div id="jenisPembayaran">            
        <?php     
                echo $form->select2Group($model, 'jenisPembayaran',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'),'widgetOptions' => array(
                                            'data' => array('1'=>'INBOX','2'=>'Data Setoran'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Jenis Pembayaran',
                                                    'onchange'=>'enableButtonProses();'
                                            )
                                    )));              
	?>     
        </div>    
                    
                    

                   
    <span id="loadingProses" style="visibility: hidden; margin-top: -5px;">
        <h5><b>Silahkan Tunggu Proses ...</b></h5>
        <div class="progress progress-striped active">
            <div style="width: 100%" aria-valuemax="100" aria-valuemin="0" aria-valuenow="100" role="progressbar" class="progress-bar progress-bar-danger">
                <span class="sr-only">Silahkan Tunggu..</span>
            </div>
        </div>
    </span>                           

	<div style="float:left">            
		<?php 
                echo CHtml::ajaxSubmitButton('Lihat Data',CHtml::normalizeUrl(array('perbaikandata/view')),
             array(
                 //'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  { 
                    $("#detail-data #detail-data-content").val("");
                    $("#btnSavePerbaikan").prop( "disabled", false);	
                    document.getElementById("loadingProses").style.visibility = "hidden";
                    $("#detail-data #detail-data-content").html(data);                        
                    $("#detail-data").show();
                    scrollDown();
                    $("#btnSavePerbaikan").hide();
                    $("#btnProses").show();
                    $("#jenisPembayaran").show();
                    $("#tanggalPembelian").prop( "disabled", true);	
                    $("#Perbaikandata_pelangganId").prop( "disabled", true);	
                                        
                }'               
                 ,                                    
                 'beforeSend'=>'function()
                  {                        
                       document.getElementById("loadingProses").style.visibility = "visible";
                        $("#btnSavePerbaikan").prop( "disabled", true);	
                  }'
                 ),array('id'=>'btnSavePerbaikan','class'=>'btn btn-primary'));                                                         
         ?> 
	</div>
<br>
<?php $this->endWidget(); ?>
<button  id="btnProses" class="btn btn-primary" onclick="proses();" disabled="disabled">Proses</button>
<br>
                </div>
            </div>	              
        </div>
        
        <div id="detail-data">
             <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Detail Data</b>
                    </div>
                    <div class="pull-right">
                        
                    </div>
                </div>
                <div class="ibox-content" id="detail-data-content">      
                        
                </div>
            </div>	              
        </div>
        </div>
        
    </div>
</div>

<script>
    $("#btnProses").hide();
    $('#detail-data').hide();
    $('#jenisPembayaran').hide();
    function scrollDown()
    {
        $('html, body').animate({ scrollTop: $('#detail-data-content').offset().top }, 'slow');
    } 
    
    function proses()
    {
        var tanggal = $('#tanggalPembelian').val();
        var pelangganid = $('#Perbaikandata_pelangganId').val();        
        var jenis = $('#Perbaikandata_jenisPembayaran').val();
        var pembelianKe = $('#Perbaikandata_pembelianKe').val();
        
        if(tanggal!='' && pelangganid!='' && jenis!='' && pembelianKe!='')
	{
            $.ajax({
            //dataType: 'json',
            beforeSend:function(){
                document.getElementById("loadingProses").style.visibility = "visible";
            },
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/kasir/perbaikandata/proses',
            data: 'tanggal='+tanggal+'&pelangganid='+pelangganid+'&jenis='+jenis+'&pembelianke='+pembelianKe,
            success: function (row) {                                                      
                   location.href='<?php echo Yii::app()->baseurl; ?>/kasir/perbaikandata';
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
		});
	}        
    }
    
    function pembelianKe()
    {
        var tanggal = $('#tanggalPembelian').val();
        var pelangganid = $('#Perbaikandata_pelangganId').val();        
        
        
        if(tanggal!='' && pelangganid!='')
	{
            $.ajax({
            dataType: 'json',
            type: 'POST',
            url: '<?php echo Yii::app()->baseurl; ?>/kasir/perbaikandata/pembelianke',
            data: 'tanggal='+tanggal+'&pelangganid='+pelangganid,
            success: function (data) {                                                      
               // hapus option
                //$('#Gudangpenjualanbarang_lokasipenyimpananbarangid option[value!=""]').remove();
                var x = document.getElementById("Perbaikandata_pembelianKe");           
                // buat option
                for(i=0;i<data.id.length;i++)
                {
                    var option = document.createElement("option");
                    option.value = data.id[i];
                    option.text = data.text[i];
                    x.add(option);
                }
            },
            error: function () {
                alert("Error occured. Please try again (getLokasiBarang)");
            }
		});
	}
    }    
    
    function enableButtonProses()
    {
        $("#btnProses").prop( "disabled", false);
    }    
</script>
    