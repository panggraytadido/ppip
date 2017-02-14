<?php  Yii::app()->clientScript->registerCoreScript('yiiactiveform') ?>
<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'gudangpenerimaanbarang-form',
                'enableAjaxValidation'=>true,
                'clientOptions'=>array(
                                'validateOnChange'=>true,
                )
        )); 
?>
	
<?php echo $form->errorSummary($modelPenerimaan); ?>
<?php echo $form->errorSummary($modelBongkarmuat); ?>

<input type="hidden" name="waktuinsert" value="<?php echo date("Y-m-d H:", time()); ?>" />
<input type="hidden" name="waktuupdate" value="<?php echo date("Y-m-d H:", time()); ?>" />

        <?php echo $form->datePickerGroup($modelPenerimaan, 'tanggal', array(
                                            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenerimaanbarang_tanggal_input',
                                                                'readonly'=>'readonly',
                                                                'onchange'=>'deleteAllKaryawan();'
                                                            )
                                                )
                                        )
                ); ?>

        <?php 
		
			$criteria = new  CDbCriteria;
			$criteria->condition = 'divisiid='.Yii::app()->session["divisiid"].' and lokasipenyimpananbarangid='.Yii::app()->session["lokasiid"];
			$criteria->order='nama asc';
						  
                echo $form->dropDownListGroup($modelPenerimaan, 'barangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->with('stock')->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'-- Pilih Barang --',
                                                    'ajax' => array(
                                                        'type'=>'POST', 
                                                        'dataType'=>'json', 
                                                        'url'=>Yii::app()->createUrl('gudang/penerimaanbarang/getsupplierbarang'),
                                                        'success' => "function(data){ 
                                                                $(\"#Gudangpenerimaanbarang_supplierid\").html(data.cmbSupplier);
                                                                $(\"#Gudangpenerimaanbarang_lokasipenyimpananbarangid\").html(data.cmbLokasi);
                                                        }",
                                                        'error' => "function () {
                                                            alert(\"Error occured. Please try again (getSupplierBarang)\");
                                                        }",
                                                        'data'=>array('barangid'=>'js:this.value'),
                                                      )
                                            )
                                    )));
									
        ?>

	<?php     
                echo $form->dropDownListGroup($modelPenerimaan, 'supplierid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Supplier::model()->with('stocksupplier')->findAll('status=1 and barangid='.$modelPenerimaan->barangid),'id','namaperusahaan'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'-- Pilih Supplier --',
                                                    'ajax' => array(
                                                        'type'=>'POST', 
                                                        'url'=>Yii::app()->createUrl('gudang/penerimaanbarang/getlokasibarang'),
                                                        'update'=>'#Gudangpenerimaanbarang_lokasipenyimpananbarangid',
                                                        'error' => "function () {
                                                            alert(\"Error occured. Please try again (getlokasibarang)\");
                                                        }",
                                                        'data'=>array('barangid'=>'js:document.getElementById("Gudangpenerimaanbarang_barangid").value','supplierid'=>'js:this.value'),
                                                      )
                                            )
                                    )));
        ?>
        
        <?php     
                echo $form->dropDownListGroup($modelPenerimaan, 'lokasipenyimpananbarangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->with('stocksupplier')->findAll('barangid='.$modelPenerimaan->barangid.' and supplierid='.$modelPenerimaan->supplierid.' and lokasipenyimpananbarangid='.$modelPenerimaan->lokasipenyimpananbarangid),'id','nama'),
                                    )));
        ?>
    
        <?php echo $form->textFieldGroup($modelPenerimaan, 'jumlah', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
            ); ?> 
	
       <?php echo $form->textFieldGroup($modelPenerimaan, 'beratlori', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'onchange'=>'hitungTotalBarang();'
                                                            )
                                                )
                                        )
            ); ?> 

       <?php echo $form->textFieldGroup($modelPenerimaan, 'totalbarang', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly'
                                                            )
                                                )
                                        )
            ); ?>  
    
        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Jumlah Karyawan</label>
            <div class="col-sm-4">
                <input class="form-control" placeholder="Jumlah Karyawan" name="Bongkarmuat[jumlahkaryawan]" id="Bongkarmuat_jumlahkaryawan" type="text" onchange="hitungRupiah()" value="<?php echo count($modelBongkarmuat); ?>" readonly="readonly">
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>
    
    
    <div class="form-group">
        <label class="col-sm-5 control-label required" for="Bongkarmuat_upah">Upah</label>
        <div class="col-sm-4">
            <input class="form-control" placeholder="Upah" name="Bongkarmuat[upah]" id="Bongkarmuat_upah" type="text" value="<?php echo $modelBongkarmuat[0]->upah; ?>" readonly="readonly">
            <div class="help-block error" id="Bongkarmuat_upah_em_" style="display: block;"></div>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-sm-5 control-label">Data Karyawan</label>
        <div class="col-sm-4">
            <button type="button" onclick="tambahKaryawan();" class="btn btn-success"><li class="fa fa-plus"></li></button> 
            <button type="button" onclick="deleteKaryawan();" class="btn btn-danger"><li class="fa fa-minus"></li></button> 
            <div id="pesanKaryawan"></div>
        </div>
    </div>
        
    <?php for($i=0;$i<count($modelBongkarmuat);$i++) { ?>

        <div id="inputKaryawan_<?php echo $i; ?>">
            <div class="form-group">
                <label class="col-sm-5 control-label required" for="Bongkarmuat_karyawanid">Karyawan</label>
                <div class="col-sm-4">
                    <?php 
							
							/*
                            echo CHtml::dropDownList('karyawanid[]', $modelBongkarmuat[$i]->karyawanid, 
                                            CHtml::listData(Karyawan::model()->with('absensi')->findAll("jabatanid=11 and tanggal::text like '".date("Y-m-d", strtotime($modelPenerimaan->tanggal))."%' and jammasuk is not null"),'id','nama'),
                                            array('class'=>'form-control')
                                        ); 
										*/
										
							echo CHtml::dropDownList('karyawanid[]', $modelBongkarmuat[$i]->karyawanid, 
                                            CHtml::listData(Karyawan::model()->findAll("jabatanid=11"),'id','nama'),
                                            array('class'=>'form-control','id'=>'karyawanid_'.$i)
                                        ); 				
                    ?>														
                </div>
				
				<input type="hidden" class="karyawanid" value="<?php echo $i; ?>">
				<button type="button" onclick="deleteOneKaryawan(<?php echo $modelBongkarmuat[$i]->karyawanid ?>,<?php echo $i ?>);" class="btn btn-danger"><li class="fa fa-minus"></li></button> 
            </div>
        </div>

    <?php } ?>
        
    <div id="inputKaryawan"></div>

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
        echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('penerimaanbarang/update','id'=>$modelPenerimaan->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    

                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->createUrl("gudang/penerimaanbarang/view", array("id"=>$modelPenerimaan->id)).'";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#gudangpenerimaanbarang-form #"+key+"_em_").text(val);                                                    
                           $("#gudangpenerimaanbarang-form #"+key+"_em_").show();
                       });
                   }       
                   
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      document.getElementById("loadingProses").style.visibility = "visible";
                 }'
            ),array('id'=>'btnUpdatePenerimaanBarang','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">
       
    counter = <?php echo count($modelBongkarmuat); ?>;
	
	function deleteOneKaryawan(karyawanid,baris)
	{				
		if(karyawanid!="")
		{
			$("#inputKaryawan_"+baris+"").html("");			
			$.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penerimaanbarang/deleteonekaryawan',
                    data: { penerimaanbarangid : <?php echo $modelPenerimaan->id;  ?>,karyawanid:karyawanid},
					//dataType:'json',
                    success: function (succ) {						
						if(succ.result=="OK")
						{
							$("#inputKaryawan_"+baris+"").html("");
						}
                    },
                    error: function () {
                        alert("Error occured. Please try again (getDropdownkaryawan)");
                    }
            });			
		}		
	}
	
	function delKaryawanNew(baris)
	{		
		$("#inputKaryawan_"+baris+"").html("");
	}
	
    function tambahKaryawan()
    {
        var tgl = document.getElementById("Gudangpenerimaanbarang_tanggal_input").value;
		
		var max = 0;
		$('.karyawanid').each(function() 
		{
			var value = $(this).val();		
			max = Math.max(value, max);
		});
		
		var maxKaryawanid = max+1;		       
        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penerimaanbarang/getkaryawanabsenupdate',
                    data: { tanggal : tgl},
					dataType:'json',
                    success: function (row) {
					
                        if(row!='')
                        {
                            counterNext = counter + 1;
                            
                            content = '<div id="inputKaryawan_'+maxKaryawanid+'"><div class="form-group" ><label class="col-sm-5 control-label required" for="karyawanid">Karyawan</label><div class="col-sm-4">';
                            content += '<select name="karyawanid[]" id="karyawanid_'+maxKaryawanid+'" class="form-control"></select>';
                            content +='</div><button type="button" onclick="delKaryawanNew('+maxKaryawanid+');" class="btn btn-danger"><li class="fa fa-minus"></li></button></div><input type="hidden" class="karyawanid" value="'+maxKaryawanid+'"></div>';
                            
							$('#inputKaryawan').append(content);
                            //document.getElementById("inputKaryawan_"+counter).innerHTML = content;
                            //counter++;
                            
                            // ubah nilai jumlah karyawan
                            document.getElementById("Bongkarmuat_jumlahkaryawan").value = counter;
                            hitungRupiah();
                            
                            document.getElementById("pesanKaryawan").innerHTML = '';
							
							$.each(row, function () {       
								
								$('#karyawanid_'+maxKaryawanid+'').append($("<option></option>").val(this['id']).html(this['nama']));
							});  
                        }
                        else
                            document.getElementById("pesanKaryawan/").innerHTML = '<br /><div class="label label-warning">Tidak Ada Karyawan yang Hadir</div>';
						

                    },
                    error: function () {
                        alert("Error occured. Please try again (getDropdownkaryawan)");
                    }
            });
        }
        else
        {
            document.getElementById("pesanKaryawan").innerHTML = '<br /><div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
		
    }

    function deleteKaryawan()
    {
        no=counter-1;
        document.getElementById("inputKaryawan_"+no).innerHTML = "&nbsp;";

        counter--;
        
        // ubah nilai jumlah karyawan
        document.getElementById("Bongkarmuat_jumlahkaryawan").value = counter;
        hitungRupiah();
    }
    
    function deleteAllKaryawan()
    {
        while(counter>0)
        {
            no=counter-1;
            document.getElementById("inputKaryawan_"+no).innerHTML = "&nbsp;";

            counter--;

            // ubah nilai jumlah karyawan
            document.getElementById("Bongkarmuat_jumlahkaryawan").value = counter;
            hitungRupiah();
        }
    }
    
    function hitungTotalBarang()
    {
        totalBarang = 0;
        totalBarang = (document.getElementById("Gudangpenerimaanbarang_jumlah").value) - (document.getElementById("Gudangpenerimaanbarang_beratlori").value);
        document.getElementById("Gudangpenerimaanbarang_totalbarang").value = totalBarang;
        
        hitungRupiah();
    }
    
    function hitungRupiah()
    {
        rp = 0;
        rp = (document.getElementById("Gudangpenerimaanbarang_totalbarang").value * 20) / document.getElementById("Bongkarmuat_jumlahkaryawan").value;
        document.getElementById("Bongkarmuat_upah").value = rp;
    }

</script>