
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
	

	

	<div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Divisi</label>
            <div class="col-sm-2 col-sm-9"><b>Gudang</b>
                    <div style="display:none" id="Penerimaanbarang_beratlori_em_" class="help-block error"></div>                        
                </div>                
        </div>

        <?php     
                echo $form->datePickerGroup($modelPenjualanBarang, 'tanggal',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                    ));
              
	?>
        
        
        <?php     
        echo $form->dropDownListGroup($modelPenjualanBarang, 'pelangganid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Pelanggan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pelanggan',										
                                            )
                                    )));
        /*
                echo $form->select2Group($modelPenjualanBarang, 'pelangganid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Pelanggan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pelanggan',
                                                    'onchange'=>'showHideValidation(this);'
                                            )
                                    )));       
         * 
         */       
	?>

        <?php     
        echo $form->dropDownListGroup($modelPenjualanBarang, 'barangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',										
                                            )
                                    )));
        /*
                $divisiid = Divisi::model()->find("kode='1'")->id;
                $criteria = new CDbCriteria;
                $criteria->condition="divisiid=".$divisiid;
                echo $form->select2Group($modelPenjualanBarang, 'barangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',
                                                   //'onchange'=>'checkStockPenjualanBarang(this);'
                                            )
                                    )));      
         * 
         */        
	?>

        <?php     
        echo $form->dropDownListGroup($modelPenjualanBarang, 'lokasipenyimpananbarangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Lokasi',										
                                            )
                                    )));
        /*
                echo $form->select2Group($modelPenjualanBarang, 'lokasipenyimpananbarangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Lokasi',
                                                    'onchange'=>'checkStockPenjualanBarang(this);'
                                            )
                                    )));              
         * 
         */
	?>

        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Jumlah(Kg)</label>
            <div class="col-sm-2 col-sm-9"><input type="text" id="Penjualanbaranggudang_jumlah"  name="Penjualanbaranggudang[jumlah]" value="<?php echo $modelPenjualanBarang->jumlah; ?>" placeholder="Jumlah(Kg)" class="form-control">
                    <div style="display:none" id="Penerimaanbarang_beratlori_em_" class="help-block error"></div>                        
                </div> 
             <div class="col-sm-2 col-sm-9">
                    <input type="text" class="form-control"  value="<?php echo $modelPenjualanBarang->box; ?>"  id="box" name="Penjualanbaranggudang[box]" placeholder="Box">                    
                </div>         
        </div>

        <?php
        if($modelPenjualanBarang->kategori==1)
        {
        ?>
        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Kategori</label>
                <div class="col-sm-2 col-sm-9">
                    <input type="radio" name="kategori" checked="checked" onclick="getHarga();" id="kategori" value="1">&nbsp;Eceran <input type="radio" name="kategori" onclick="getHarga();" id="kategori" value="2">&nbsp;Grosir 
                    <div style="display:none" id="Penjualannbaranggudang_beratlori_em_" class="help-block error"></div>                        
                </div> 
             <div class="col-sm-3 col-sm-9">
                 <input type="text" class="form-control" id="harga" placeholder="Harga" value="<?php echo number_format($modelPenjualanBarang->hargatotal) ?>">                    
                    <input type="hidden" name="hargainput" class="form-control" id="hargainput" value="<?php echo $modelPenjualanBarang->hargatotal ?>">                    
             </div>         
        </div>
        <?php 
        }
        else
        {    
        ?>
            <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Kategori</label>
                <div class="col-sm-2 col-sm-9">
                    <input type="radio" name="kategori"  onclick="getHarga();" id="kategori" value="1">&nbsp;Eceran <input type="radio" checked="checked" name="kategori" onclick="getHarga();" id="kategori" value="2">&nbsp;Grosir 
                    <div style="display:none" id="Penjualannbaranggudang_beratlori_em_" class="help-block error"></div>                        
                </div> 
             <div class="col-sm-3 col-sm-9">
                 <input type="text" class="form-control" id="harga" placeholder="Harga" value="<?php echo number_format($modelPenjualanBarang->hargatotal) ?>">                    
                    <input type="hidden" name="hargainput" class="form-control" id="hargainput" value="<?php echo $modelPenjualanBarang->hargatotal ?>">                    
             </div>         
        </div>
        <?php
        }
        ?>

        <?php echo $form->textFieldGroup($modelPenjualanBarang, 'hargatotal',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'))); ?>
        <input type="hidden" name="Penjualanbaranggudang[hargatotalinput]" id="Penjualanbaranggudang_hargatotalinput" >
        
        
        <div class="form-group">
            <label class="col-sm-5 control-label" for="Penerimaanbarang_barangid">Jumlah Karyawan</label>
                <div class="col-sm-1 col-sm-9">
                    <input type="text" class="form-control" id="jumlahkaryawanpenjualan" oninput="getUpahPenjualan(this);" value="<?php echo $jumlahBongkarMuat; ?>">                    
                </div>                                                    
                <div class="col-sm-2 col-sm-9">
                    <input type="text" class="form-control" id="upahkaryawanpenjualan" name="upahkaryawanpenjualan" placeholder="RP." value="<?php echo $upahBongkarMuat ?>">      
                    <input type="hidden" class="form-control" id="upahkaryawanpenjualaninput" name="upahkaryawanpenjualaninput" placeholder="RP.">      
                </div>                                                    
                <button type="button" class="btn btn-warning" onclick="createPegawaipenjualan();">Set</button>
        </div>

        <div id="tablekaryawanpenerimaan">
            <table class="table table-striped" id="table-karyawan-update-karyawan">
                <th>No</th><th>Aksi</th><th>Karyawan</th><th></th>           
            <?php 
                $no=1;
                for($i=0;$i<count($modelBongkarMuat);$i++) 
                {   
            ?>
            <tr id="tr_<?php echo $modelBongkarMuat[$i]["id"] ?>">
                <td width="1%" id="<?php echo $no ?>" class="no"><?php echo $no; ?></td>
                <td width="1%">
                    <a class="deleteLinkUpdatePenerimaan btn btn-danger btn-xs" onclick="deleteRowKaryawanUpdatePenerimaan(this);" style="cursor:pointer;" id="<?php echo "del_".$modelBongkarMuat[$i]["id"] ?>">-</a>
                </td>
                <td width="20%"><?php                                                 
                        $karyawan = Karyawan::model()->findAll();
                        //print("<pre>".print_r($karyawan,true)."</pre>");
                        echo '<select name="updateKaryawanPenerimaanBarang['.$i.']" style="width: 100px !important; min-width: 200px; max-width: 300px;"  id="updatekarpenerimaangudang_'.$i.'">';
                        foreach($karyawan as $data)
                        {
                            if($modelBongkarMuat[$i]["karyawanid"]==$data["id"])
                            {
                                echo "<option value=$data[id] selected>$data[nama]</option>";                                 
                            }
                            else 
                            {
                                echo "<option value=$data[id]>$data[nama]</option>";                                
                            }
                            
                            //print("<pre>".print_r($data,true)."</pre>");
                        }
                         echo '</select';                       
                          ?>
                         
                </td>
            </tr>    
            <?php 
                    $no++;
                }
            ?>
            </table>
            <button type="button" class="btn btn-warning" onclick="updatePenjualanBarangCreatePegawai();">Add</button>            
        </div>
        <br>
	<div style="float:left">            
           
		<?php 
                echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('gudang/updatepenjualanbarang',$modelPenjualanBarang->id)),
             array(
                 'dataType'=>'json',
                 'type'=>'POST',                      
                 'success'=>'js:function(data) 
                  {                   
                    $("#AjaxLoader").hide();                    
                    if(data.result==="OK")
                    {             
                        window.location.reload();           		             			             			             			             			             			             
                    }
                    else if(data.result==="NotOK")
                    {
                         $("#stockMessage").show();
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
                 ),array('id'=>'btnUpdatePenjualan','class'=>'btn btn-warning'));                                                         
         ?> 
	</div>
        <br>
  

<?php $this->endWidget(); ?>

<script>
    $('#stockMessage').hide();
    
    function checkStockPenjualanBarang()
    {
        var barangid = $('#Penjualanbaranggudang_barangid option:selected').val();
        var lokasipenyimpananbarangid = $('#Penjualanbaranggudang_lokasipenyimpananbarangid option:selected').val();
                
                
         $.ajax({
	        url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/checkstockbarang' ?>',
	        type: 'post',
		dataType:'json',			
	        data: 'barangid='+barangid+'&lokasipenyimpananbarangid='+lokasipenyimpananbarangid,
	        success: function (row) 
	        {         
                    if(row.stockPending==null)
                    {
                        alert('Stock Tersedia : '+row.stock+', Pending Stock 0');                						 		
                    }    
                    else
                    {
                        alert('Stock Tersedia : '+row.stock+', Pending Stock '+row.stockPending);        
                    }    
	            
	        }
            });
                        
    }
    
    function updatePenjualanBarangCreatePegawai()
    {
        var max = 0;
	$('.no').each(function() {
            max = Math.max(this.id, max);
	});                
        
        var jumlahkaryawan = parseInt($('#jumlahupdatekaryawanpenerimaan').val())+1;        
        var upah = (parseInt($('#Penerimaanbarang_updatetotalbarang').val())*20)/parseInt(jumlahkaryawan);
                
        $('#jumlahupdatekaryawanpenerimaan').val(parseInt(jumlahkaryawan));
        $('#upahupdatekaryawanpenerimaan').val(parseInt(upah));
        
        var no = parseInt(max)+1;
        var maxId= parseInt(max)+1;
                              
        getJsonKaryawanUpdatePenerimaan(maxId);
        
        var button ='<a class="deleteLinkUpdatePenerimaan btn btn-danger btn-xs" style="cursor:pointer;" id="<?php echo "del_".$no ?>">-</a>';
        var karyawanUpdatePenerimaan = "<select style='width: 100px !important; min-width: 200px; max-width: 300px;' name='updateKaryawanPenerimaanBarang["+maxId+"]' id='karyawanupdatepenerimaan_"+maxId+"'></select>";									
      
        var rowAdded = '<tr>';	
        
        rowAdded += '<td id='+maxId+' class="no">';
	rowAdded += no;				
	rowAdded += '</td>';

        rowAdded += '<td>';
	rowAdded += button;				
	rowAdded += '</td>';
        
        rowAdded += '<td width="20%">';
	rowAdded += karyawanUpdatePenerimaan;				
	rowAdded += '</td>';

        rowAdded += '</tr>';	
        
        $('#table-karyawan-update-karyawan').append(rowAdded);
    }
    
    function getJsonKaryawan()
    {
                $.ajax({
	        url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/getkaryawangudang' ?>',
	        type: 'post',
		dataType:'json',			
	        //data: 'type=manajemen',
	        success: function (row) 
	        {                  
	            	//$('#kodeakun_'+p+' option:nth-child(1)').attr('hidden','hidden');
	            	$.each(row, function () {
	   	             $('.karyawan').append($("<option></option>").val(this['id']).html(this['nama']));
	   	         	});		                						 		
	        }
	   		});					
			
    }
    
    function getTotalBarang()
    {
        var jumlah = $('#Penjualanbaranggudang_jumlah').val();
        var hargasatuan = $('#hargainput').val();
        
        var total = jumlah-beratlori;
        
        $('#Penerimaanbarang_totalbarang').val(total);                
    } 
    
    function getHarga()
    {
        var barangid = $('#Penjualanbaranggudang_barangid :selected').val();
        if(barangid!='')
        {
            var kategori =  $('input[name="kategori"]:checked').val();      
            var jumlah = $('#Penjualanbaranggudang_jumlah').val();

            $.ajax({
                    url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/getharga'?>',
                    type: 'post',
                    dataType:'json',			
                    data: 'barangid='+barangid+'&kategori='+kategori,
                    success: function (row) 
                    {                      
                        var harga=formatCurrency(row);
                        $('#harga').val(harga);
                        $('#hargainput').val(row);
                        var hargatotal = jumlah* $('#hargainput').val();
                        $('#Penjualanbaranggudang_hargatotal').val(formatCurrency(hargatotal));
                        $('#Penjualanbaranggudang_hargatotalinput').val(hargatotal);
                        
                    }
               });
        }
        else 
        {
            alert('Pilih Barang Terlebih dahulu');
        }
        
    }    
    
    function getUpahPenjualan()
    {
        var jumlah = $('#Penjualanbaranggudang_jumlah').val();
        var jumlahKaryawanPenjualan= $('#jumlahkaryawanpenjualan').val(); 
        var upah = (jumlah*30)/jumlahKaryawanPenjualan;
        $('#upahkaryawanpenjualan').val(upah);
        $('#upahkaryawanpenjualaninput').val(upah);
        
    }    
    
    function formatCurrency(total) {
    return total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");;
}
</script>        