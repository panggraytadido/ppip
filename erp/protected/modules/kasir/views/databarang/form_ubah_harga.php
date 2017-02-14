<?php
$this->pageTitle = "Form Ubah Harga Barang - Kasir";

$this->breadcrumbs=array(
	'Kasir','Form Ubah Harga Barang'
);
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Data Barang</b>
                    </div>                    
                </div>
                <div class="ibox-content">    
                     
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Supplier</label>
                            <div class="col-lg-3">
                                <input class="form-control" disabled="disabled" value="<?php echo Supplier::model()->findByPk($supplierid)->namaperusahaan; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Barang</label>
                            <div class="col-lg-6">
                                <input class="form-control" disabled="disabled" value="<?php echo Barang::model()->findByPk($barangid)->nama; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Harga Modal</label>
                            <div class="col-lg-8">
                                <input class="form-control" disabled="disabled" value="<?php echo number_format(Hargabarang::model()->find('barangid='.$barangid.' AND supplierid='.$supplierid)->hargamodal); ?>">
                            </div>
                        </div>                       
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Harga Grosir</label>
                            <div class="col-lg-8">
                                <input class="form-control" disabled="disabled" value="<?php echo number_format(Hargabarang::model()->find('barangid='.$barangid.' AND supplierid='.$supplierid)->hargagrosir); ?>">
                            </div>
                        </div>     
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Harga Eceran</label>
                            <div class="col-lg-8">
                                <input class="form-control col-lg-4" disabled="disabled" value="<?php echo number_format(Hargabarang::model()->find('barangid='.$barangid.' AND supplierid='.$supplierid)->hargaeceran); ?>">                                
                            </div>
                        </div>                       
                    </form>    
                    <br>
                   
                    <input type="hidden" id="supplierid" name="supplierid" value="<?php echo $supplierid ?>" >
                    <input type="hidden" id="barangid" name="barangid" value="<?php echo $barangid ?>" >
                    
                    		 </div>
				</div>	              
                </div>
                
        
                <div class="col-lg-6">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Masukan Harga Baru</b>
                    </div>                    
                     <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>
                
                <div class="ibox-content">    
                     
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-3">
                                &nbsp;
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"></label>
                            <div class="col-lg-6">
                                &nbsp;
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Harga Modal Baru</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="hargamodalinput">
                            </div>
                        </div>                       
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Harga Grosir Baru</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="hargagrosirinput">
                            </div>
                        </div>     
                        <div class="form-group">
                            <label class="col-lg-3 control-label">Harga Eceran Baru</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="hargaeceraninput">
                            </div>
                        </div>                       
                    </form>    
                    <br>
                   
                    <input type="hidden" name="pelangganid" value="<?php //echo $pelangganid ?>" >
                    <input type="hidden" name="tanggal" value="<?php //echo $tanggal ?>" >
                    
                    <br>
                    <div class="pull-left">
                        <button class="btn btn-primary btn-xs" onclick="updateHarga();">Simpan</button>
                    </div>  
                    <br>
                    		 </div>
				</div>	              
                </div>
            </div>
            </div>    
</div>
<script>
    function updateHarga()    
    {                
        if($('#hargamodalinput').val()!='' && $('#hargaeceraninput').val()!='' && $('#hargagrosirinput').val()!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/kasir/databarang/updatebarang',
                        data: { barangid : $('#barangid').val(), supplierid : $('#supplierid').val(),
                                hargamodal:$('#hargamodalinput').val(),hargagrosir:$('#hargagrosirinput').val(),hargaeceran:$('#hargaeceraninput').val()},
                        success: function (data) {
                            if(data.result==="OK")
                            {
                                location.href='<?php echo Yii::app()->baseurl ?>/kasir/databarang';
                            }    
                        },
                        error: function () {
                            alert("Error occured. Please try again (cekStock)");
                        }
            });
        }
        else
        {
            alert('masukan semua inputan');
        }
    }
 </script>   