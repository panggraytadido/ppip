<?php
$this->pageTitle = "Form Ubah Harga Barang - Admin";

$this->breadcrumbs=array(
	'Admin','Form Ubah Harga Barang'
);
?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Admin - Data Barang</b>
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
                   
                    <input type="hidden" id="supplierlamaid" name="supplierid" value="<?php echo $supplierid ?>" >
                    <input type="hidden" id="barangid" name="barangid" value="<?php echo $barangid ?>" >
                    
                    		 </div>
				</div>	              
                </div>
                
        
        
                
                <div class="col-lg-6">                     
            <div class="ibox float-e-margins">
                 <div class="ibox-title">
                    <div class="pull-left">
                        <b>Admin - Data Barang</b>
                    </div>   
                     
                     <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>
                <div class="ibox-content">    
                     
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Supplier</label>
                            <div class="col-lg-3">
                                <?php
                                    $connection=Yii::app()->db;
                                    $sql ="SELECT id,namaperusahaan FROM master.supplier";

                                    $data=$connection->createCommand($sql)->queryAll();
                                    //print("<pre>".print_r($data,true)."</pre>");
                                    
                                    echo '<select id="supplier" name="supplier[]" class="form-control col-lg-6">';
					for($i=0;$i<count($data);$i++)
                                        {
                                          $val = $data[$i]["namaperusahaan"];  
                                          $key = $data[$i]["id"];  
                                          //echo "<option value=$key>$val</option>";
                                            if($key==$supplierid)
                                            {
                                                  echo "<option value=$key selected>$val</option>";
                                            }
                                            else
                                            {
                                                  echo "<option value=$key>$val</option>";
                                            }
                                          
                                        }
                                    echo '</select';	                                    
                            ?>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Barang</label>
                            <div class="col-lg-6">
                                <input class="form-control" disabled="disabled" value="<?php echo Barang::model()->findByPk($barangid)->nama; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Harga Modal</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="hargamodalinput" name="hargamodalinput" value="<?php echo Hargabarang::model()->find('barangid='.$barangid.' AND supplierid='.$supplierid)->hargamodal ?>">
                            </div>
                        </div>                       
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Harga Grosir</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="hargagrosirinput" name="hargagrosirinput" value="<?php echo Hargabarang::model()->find('barangid='.$barangid.' AND supplierid='.$supplierid)->hargagrosir ?>">
                            </div>
                        </div>     
                        <div class="form-group">
                            <label class="col-lg-2 control-label">Harga Eceran</label>
                            <div class="col-lg-8">
                                <input class="form-control" id="hargaeceraninput" name="hargaeceraninput" value="<?php echo Hargabarang::model()->find('barangid='.$barangid.' AND supplierid='.$supplierid)->hargaeceran ?>">
                            </div>
                        </div>                       
                    </form>    
                    <br>                                   
                    		 </div>
                <button class="btn btn-warning btn-sm" onclick="updateHarga();">Update</button>
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
                        url: '<?php echo Yii::app()->baseurl; ?>/admin/crudbarang/updatebarang',
                        data: { barangid : $('#barangid').val(),supplierlamaid : $('#supplierlamaid').val(), supplierid : $('#supplier option:selected').val(),
                                hargamodal:$('#hargamodalinput').val(),hargagrosir:$('#hargagrosirinput').val(),hargaeceran:$('#hargaeceraninput').val()},
                        success: function (data) {
                            if(data.result==="OK")
                            {
                                location.href='<?php echo Yii::app()->baseurl ?>/admin/crudbarang';
                            }    
                        },
                        error: function () {
                           // alert("Error occured. Please try again");
                        }
            });
        }
        else
        {
            alert('masukan semua inputan');
        }
    }
 </script>   