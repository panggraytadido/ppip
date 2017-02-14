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
	

	<?php echo $form->errorSummary($modelPenerimaanBarang); ?>

	<?php     
                echo $form->dropDownListGroup($modelPenerimaanBarang, 'divisiid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Divisi::model()->findAll("kode='1'"),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Divisi',										
                                            )
                                    )));
              
	?>

        <?php     
                echo $form->datePickerGroup($modelPenerimaanBarang, 'tanggal',array('wrapperHtmlOptions'=>array('class'=>'col-sm-3'),
                                    ));
              
	?>
        
        
        <?php     
                echo $form->dropDownListGroup($modelPenerimaanBarang, 'supplierid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Supplier::model()->findAll(),'id','namaperusahaan'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Suplier',										
                                            )
                                    )));
              
	?>

        <?php     
                echo $form->dropDownListGroup($modelPenerimaanBarang, 'barangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',										
                                            )
                                    )));              
	?>

        <?php     
                echo $form->dropDownListGroup($modelPenerimaanBarang, 'lokasipenyimpananbarangid',array('wrapperHtmlOptions'=>array('class'=>'col-sm-8'),'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Lokasi',										
                                            )
                                    )));              
	?>

        <?php echo $form->textFieldGroup($modelPenerimaanBarang, 'jumlah',array('wrapperHtmlOptions'=>array('class'=>'col-sm-5'))); ?>

        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Berat lori</label>
            <div class="col-sm-5 col-sm-9"><input type="text" id="Penerimaanbarang_beratlori" oninput="getTotalBarang(this);" name="Penerimaanbarang[beratlori]" value="<?php echo $modelPenerimaanBarang->beratlori ?>" placeholder="Berat lori" class="form-control">
                    <div style="display:none" id="Penerimaanbarang_beratlori_em_" class="help-block error"></div>                        
                </div>                   
        </div>

         
        <div class="form-group">
            <label for="Penerimaanbarang_beratlori" class="col-sm-5 control-label">Total Barang</label>
            <div class="col-sm-5 col-sm-9"><input type="text" id="Penerimaanbarang_updatetotalbarang" oninput="getTotalBarang(this);" name="Penerimaanbarang[totalbarang]" value="<?php echo $modelPenerimaanBarang->totalbarang ?>" placeholder="Total Barang" class="form-control">
                    <div style="display:none" id="Penerimaanbarang_beratlori_em_" class="help-block error"></div>                        
                </div>                   
        </div>


        <div class="form-group">
            <label class="col-sm-5 control-label" for="Penerimaanbarang_barangid">Jumlah Karyawan</label>
                <div class="col-sm-1 col-sm-9">
                    <input type="text" class="form-control" id="jumlahupdatekaryawanpenerimaan" oninput="getUpahPenerimaan(this);" value="<?php echo $jumlahBongkarMuat; ?>">                    
                </div>                                                    
                <div class="col-sm-2 col-sm-9">
                    <input type="text" class="form-control" id="upahupdatekaryawanpenerimaan" name="upahkaryawanpenerimaan" placeholder="RP." value="<?php echo $upahBongkarMuat; ?>">                    ;
                </div>                                                    
                <button type="button" class="btn btn-warning" onclick="createPegawai();">Set</button>
        </div>
        
        <div id="tablekaryawanpenerimaan">
            <table class="table table-striped" id="table-karyawan-update-karyawan">
                <th>No</th><th>Aksi</th><th>Karyawan</th>            
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
            <button type="button" class="btn btn-warning" onclick="updatePenerimaanBarangCreatePegawai();">Add</button>
        </div>
        <br>
        
        
	<div style="float:left">            
		<?php 
                echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('gudang/updatepenerimaanbarang','id'=>$modelPenerimaanBarang->id)),
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
                 ),array('id'=>'btnUpdatePenerimaan','class'=>'btn btn-success'));                                                         
         ?> 
	</div>          
<?php $this->endWidget(); ?>

<script>
    function updatePenerimaanBarangCreatePegawai()
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
    
    function getJsonKaryawanUpdatePenerimaan(p)
    {
                $.ajax({
	        url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/getkaryawangudang' ?>',
	        type: 'post',
		dataType:'json',			
	        //data: 'type=manajemen',
	        success: function (row) 
	        {                  
	            	$('#karyawanupdatepenerimaan_'+p+'').append($("<option></option>").val(0).html('Pilih Karyawan'));
	            	$.each(row, function () {                            
	   	             $('#karyawanupdatepenerimaan_'+p+'').append($("<option></option>").val(this['id']).html(this['nama']));
	   	         	});		                						 		
	        }
	   });								
    }
    
    function deleteRowKaryawanUpdatePenerimaan(txt)
    {
        var tmp = txt.id.split("_");
        var penerimaanbarangid=tmp[1];                
        
        $.ajax({
	        url: '<?php echo Yii::app()->baseUrl. '/admin/gudang/deletekaryawangudang' ?>',
	        type: 'post',
		dataType:'json',			
	        data: 'penerimaanbarangid='+penerimaanbarangid,
	        success: function (row) 
	        {                  
	            if(row.result=='OK')
                    {
                         var jumlahkaryawan = parseInt($('#jumlahupdatekaryawanpenerimaan').val())-1;        
                        var upah = (parseInt($('#Penerimaanbarang_updatetotalbarang').val())*20)/parseInt(jumlahkaryawan);

                        $('#jumlahupdatekaryawanpenerimaan').val(parseInt(jumlahkaryawan));
                        $('#upahupdatekaryawanpenerimaan').val(parseInt(upah));   
                        
                         $('#tr_'+penerimaanbarangid+'').remove();                         
     
                         return false;
                    }    
	        }
	   });
        
    }    
    
</script>        