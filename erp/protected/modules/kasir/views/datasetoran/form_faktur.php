<?php
$this->pageTitle = "Form Faktur - Kasir";

$this->breadcrumbs=array(
	'Kasir','Form Faktur'
);
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Form Faktur</b>
                    </div>                    
                </div>
                <?php $faktur = Faktur::model()->find("pelangganid=".$pelangganid." "
                                    . "AND cast(tanggalcetak as date)='$tanggalcetak' "
                                    . "AND pembelianke=".$pembelianke." AND cast(tanggal as date)='$tanggalpembelian' AND lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']);                                                               
                ?>
                <div class="ibox-content">                          
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">No Faktur</label>
                            <div class="col-lg-3">
                            <input class="form-control" disabled="disabled" value="<?php echo $faktur->nofaktur;  ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Tanggal Pembelian</label>
                            <div class="col-lg-3">
                                <input class="form-control" disabled="disabled" value="<?php echo $tanggalpembelian ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Pelanggan</label>
                            <div class="col-lg-3">
                                <input class="form-control" disabled="disabled" value="<?php echo Pelanggan::model()->findByPk($pelangganid)->nama; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Alamat</label>
                            <div class="col-lg-3">
                            <input class="form-control" disabled="disabled" value="<?php echo Pelanggan::model()->findByPk($pelangganid)->alamat; ?>">
                            </div>
                        </div>
                    </form>    
                    <br>
                     <div class="ibox-title">
                        <div class="pull-left">
                            <b>Detail Barang</b>
                        </div>                    
                    </div>
                    <?php   $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
               // 'action'=>Yii::app()->createAbsoluteUrl("oemmkt/manajemencustomer/Ajaxvalidatecustomerprofile"),//Yii::app()->createUrl($this->route),
                //'method'=>'post',
                'enableAjaxValidation'=>true,
                'type'=>'horizontal',
                'id'=>'customer-form',
                //'htmlOptions' => array('enctype' => 'multipart/form-data'),
        		'enableClientValidation'=>true,
        		'clientOptions'=>array(
        				'validateOnSubmit'=>true,
        				'validateOnChange'=>false,
        				//'afterValidate'=>'js:myAfterValidateFunction'
        		)
        		
                )); ?>
                    <input type="hidden" name="pelangganid" value="<?php echo $pelangganid ?>" >
                    <input type="hidden" name="tanggalpembelian" value="<?php echo $tanggalpembelian ?>" >
                    <input type="hidden" name="tanggalcetak" value="<?php echo $tanggalcetak ?>" >
                    <input type="hidden" name="pembelianke" value="<?php echo $pembelianke ?>" >
                    <input type="hidden" name="nofaktur" value="<?php echo $faktur->nofaktur;  ?>" >
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>No</th>
                            <th>Barang</th>
                            <th>Box</th>                           
                            <th>Jumlah</th>
                            <th>Harga</th>                            
                            <th>Harga Total</th>
                            <th>Diskon</th>
                            <th>Harga Setelah Diskon</th>
                        </tr>
                        <?php 
                        $no=1;
                        for($i=0;$i<count($data);$i++)
                        {
                        ?>
                        <tr>
                            <input type="hidden" name="barangid[]" value="<?php echo $data[$i]["barangid"] ?>" >
                            <input type="hidden" name="jumlah[]" value="<?php echo $data[$i]["jumlah"] ?>" >
                            <td><?php echo $no; ?><input type="hidden" name="penjualanbarangid[]" value="<?php echo $data[$i]["id"] ?>"</td>
                            <td><?php echo $data[$i]["barang"] ?></td>
                            <td><?php echo $data[$i]["box"] ?></td>                            
                            <td><?php echo $data[$i]["jumlah"] ?></td>
                            <td><?php echo number_format($data[$i]["hargasatuan"]) ?></td>                            
                            <td><?php echo number_format($data[$i]["hargatotal"])?><input type="hidden" class="hargatotal" name="hargatotalhidden" value="<?php echo $data[$i]["hargatotal"] ?>" id="hargatotalhidden_<?php echo $i?>"></td>
                            <td><input type="text" name="diskon[]" id="diskon_<?php echo $i ?>" class="diskon" oninput="hitungDiskon(this);"><input type="hidden" name="diskoninput[]" id="diskoninput_<?php echo $i ?>" class="diskoninput"></td>
                            <td><input type="text" id="hargasetelahdiskon_<?php echo $i ?>" name="hargasetelahdiskon" class="hargasetelahdiskon" id="" disabled="disabled"><td>
                        </tr>
                        <?php 
                        $no++;
                        }
                        ?>                      
                        <tr>
                            <td rowspan="3" colspan="6"></td>
                            <td><b>Total</b></td>
                            <td><input type="hidden" id="hargatotalinput" name="hargatotalinput"><div id="hargatotal"></div></td>                            
                        </tr>
                        <tr>
                            
                            <td><b>Total Diskon</b></td>
                            <td><input type="hidden" id="totaldiskoninput" name="totaldiskoninput"><div id="totaldiskon"></div></div></td>                            
                        </tr>
                        <tr>
                            
                            <td><b>Bayar = Total - Total Diskon</b></td>
                            <td><input type="hidden" id="bayar" name="bayar"><div id="hargatotaldiskon"></div></div></td>                            
                        </tr>
                        
                    </table>
                    <br>
                    <div id="AjaxLoader" style="display: none"><img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/loader.gif"></img></div>    
                    <div class="pull-right">
                         <?php 
                            echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('datasetoran/simpan','pelangganid'=>$pelangganid,'tanggalpembelian'=>$tanggalpembelian,'pembelianke'=>$pembelianke,'tanggalcetak'=>$tanggalcetak)),
                                array(
                                    'dataType'=>'json',
                                    'type'=>'POST',                      
                                    'success'=>'js:function(data) 
                                     {    
                                       if(data.result==="OK")
                                       {          
                                          window.open("'.Yii::app()->baseurl.'/kasir/datasetoran/printfaktur/pelangganid/"+data.pelangganid+"/tanggalpembelian/"+data.tanggalpembelian+"/pembelianke/"+data.pembelianke+"/tanggalcetak/"+data.tanggalcetak, "_blank");  
                                          location.href="'.Yii::app()->baseurl.'/kasir/datasetoran";  
                                          //location.href="'.Yii::app()->baseurl.'/kasir/datasetoran/printfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/pembelianke/"+data.pembelianke;
                                       }
                                       else
                                       {                    
                                           $("#btnSaveSetoran").prop( "disabled", false); 
                                           $.each(data, function(key, val) 
                                           {
                                               $("#gudangpenjualanbarang-form #"+key+"_em_").text(val);                                                    
                                               $("#gudangpenjualanbarang-form #"+key+"_em_").show();
                                           });
                                       }       
                                   }'               
                                    ,                                    
                                    'beforeSend'=>'function()
                                     {          
                                          $("#btnSaveSetoran").prop( "disabled", true);   
                                          $("#AjaxLoader").show();
                                     }'
                                ),array('id'=>'btnSaveSetoran','class'=>'btn btn-primary'));      
                        ?> 
                        
                        <?php 
                        $this->widget('booster.widgets.TbButton', array(
										    		'label'=>'Kembali',
										    		'url'=>'#',
										    		//'visible'=>$visible==true,
										    		'htmlOptions'=>array(
										    				'id'=>"btnNext",
										    				'class'=>"btn btn-default",										    				
										    				'onclick'=>'approve();',
										    		),
										    ));
                        ?>
                        
                        <?php $this->endWidget(); ?>
                        
                    </div>  
                    <br>
                </div>               
                
            </div>	              
                </div>
            </div>
        </div>
                
<script>
    function hitungDiskon(p)
    {
        var tmp = p.id.split("_");
        var id1 = tmp[1];
        
        
        var hargatotal = $('#hargatotalhidden_'+id1+'').val();
        var diskon = $('#diskon_'+id1+'').val();
        var totalDiskon = parseInt(hargatotal)-parseInt(diskon);
        $('#hargasetelahdiskon_'+id1+'').val(totalDiskon); 
        $('#diskoninput_'+id1+'').val(diskon);
        
        totalDiskons(id1);
        totalDiskonAllItem();
        hargaTotal();
    }
    
    function totalDiskonAllItem()
    {
        var total = 0;	
	$('.diskon').each(function()
	{		
		total += Number($(this).val());		
	});
        
        $('#totaldiskon').html(formatCurrency(total));
	$('#totaldiskoninput').val(total);
    }
    
    function totalDiskons(p)
    {
        var total = 0;	
	$('.hargasetelahdiskon').each(function()
	{		
		total += Number($(this).val());		
	});
        
        $('#hargatotaldiskon').html(formatCurrency(total));
	//$('#bayar').val(total);
    }
    
    function hargaTotal()
    {
        var total = 0;	
	$('.hargatotal').each(function()
	{		
		total += Number($(this).val());		
	});
        
        $('#hargatotal').html(formatCurrency(total));
        $('#hargatotalinput').val(total);
    }
    
    function formatCurrency(total) {
    return total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");;
}
</script>    