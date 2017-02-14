<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'gudangpenjualanbarang-form',
                'enableAjaxValidation'=>true,
                'clientOptions'=>array(
                                'validateOnChange'=>true,
                )
        )); 
?>
	
<?php echo $form->errorSummary($modelPenjualan); ?>

<input type="hidden" name="waktuinsert" value="<?php echo date("Y-m-d H:", time()); ?>" />
<input type="hidden" name="waktuupdate" value="<?php echo date("Y-m-d H:", time()); ?>" />

        <?php echo $form->datePickerGroup($modelPenjualan, 'tanggal', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenjualanbarang_tanggal_input',
                                                                'readonly'=>'readonly',
                                                                
                                                            )
                                                )
                                        )
                ); 
        ?>

        <?php     
                echo $form->dropDownListGroup($modelPenjualan, 'pelangganid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Pelanggan::model()->findAll(),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Pelanggan',
                                            )
                                    )
                                )
                            );
        ?>
        
        <?php     
                echo $form->dropDownListGroup($modelPenjualan, 'barangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->with('stocksupplier')->findAll('divisiid='.Yii::app()->session['divisiid']." and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',
                                                    'onchange'=>'getLokasiBarang(this.value);getSupplierBarang();'
                                            )
                                    )
                                )
                            );
        ?>

        <?php
                echo $form->dropDownListGroup($modelPenjualan, 'supplierid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            //'data' => CHtml::listData(Hargabarang::model()->with('supplier')->findAll('barangid='.$modelPenjualan->barangid),'supplierid','supplier.namaperusahaan'),
											'data' => CHtml::listData(Supplier::model()->with('stocksupplier')->findAll('status=1 and barangid='.$modelPenjualan->barangid),'id','namaperusahaan'),
                                            'htmlOptions' => array(
                                                    'onchange'=>'hitungHargaBarang();cekStock();'
                                            )
                                    )
                                )
                            );
        ?>

        <?php     
                echo $form->dropDownListGroup($modelPenjualan, 'lokasipenyimpananbarangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->with('stocks')->findAll('barangid='.$modelPenjualan->barangid),'id','nama'),
                                            'htmlOptions' => array(
                                                    'onchange'=>'cekStock();'
                                            )
                                    )
                                )
                            );
        ?>
        
    
        <?php echo $form->textFieldGroup($modelPenjualan, 'jumlah',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'htmlOptions' => array(
                                                    'onchange'=>'cekStock();hitungHargaTotal();hitungRupiah();',
                                            )
                                    ),
                                    'hint' => '<p id="error_jumlah" style="color:red !important;"></p>',
                            )
        ); ?> 
        
        <?php echo $form->textFieldGroup($modelPenjualan, 'box',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'))); ?> 
        
        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Gudangpenjualanbarang_kategori">Kategori</label>
            <div class="col-sm-4">
                <input type="radio" name="Gudangpenjualanbarang[kategori]" id="kategori1" onchange="hitungHargaBarang();" value="1" <?php echo ($modelPenjualan->kategori==1) ? 'checked="checked"' : ''; ?> /> Eceran 
                <input type="radio" name="Gudangpenjualanbarang[kategori]" id="kategori2" onchange="hitungHargaBarang();" value="2" <?php echo ($modelPenjualan->kategori==2) ? 'checked="checked"' : ''; ?> /> Grosir 
            </div>
        </div>
	
       <?php echo $form->textFieldGroup($modelPenjualan, 'hargasatuan', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Gudangpenjualanbarang_hargasatuan_input',
                                                            )
                                                )
                                        )
            ); 
        ?> 

       <?php echo $form->textFieldGroup($modelPenjualan, 'hargatotal', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Gudangpenjualanbarang_hargatotal_input',
                                                            )
                                                )
                                            )
                                        ); 
        ?>  

  
    
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
        echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('penjualanbarang/update','id'=>$modelPenjualan->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    
                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->createUrl("gudang/penjualanbarang/view", array("id"=>$modelPenjualan->id)).'";
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#gudangpenjualanbarang-form #"+key+"_em_").text(val);                                                    
                           $("#gudangpenjualanbarang-form #"+key+"_em_").show();
                       });
                   }       
                   
                   document.getElementById("loadingProses").style.visibility = "hidden";
               }'               
                ,                                    
                'beforeSend'=>'function()
                 {                        
                      document.getElementById("loadingProses").style.visibility = "visible";
                 }'
            ),array('id'=>'btnUpdatePenjualan','class'=>'btn btn-primary'));                                                         
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">
    
	function deleteOneKaryawan(karyawanid,baris)
	{				
		if(karyawanid!="")
		{
			$("#inputKaryawan_"+baris+"").html("");			
			$.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarang/deleteonekaryawan',
                    data: { penjualanbarangid : <?php echo $modelPenjualan->id;  ?>,karyawanid:karyawanid},
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
	
    function getLokasiBarang(barangid)
    {
        if(barangid!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarang/getlokasibarang',
                        data: { barangid : barangid},
                        success: function (data) {
                            
                            var x = document.getElementById('Gudangpenjualanbarang_lokasipenyimpananbarangid');

                            $('#Gudangpenjualanbarang_lokasipenyimpananbarangid').children('option:not(:first)').remove();;
                            
                            // buat option
                            for(i=0;i<data.id.length;i++)
                            {
                                var option = document.createElement("option");
                                option.value = data.id[i];
                                option.text = data.text[i];
                                x.add(option);
                            }
                            
                            cekStock();
                        },
                        error: function () {
                            alert("Error occured. Please try again (getLokasiBarang)");
                        }
            });
        }
        else
        {
            $('#Gudangpenjualanbarang_lokasipenyimpananbarangid option[value!=""]').remove();
        }
    }

    function getSupplierBarang()
    {
        var e = document.getElementById("Gudangpenjualanbarang_barangid");
        var barangid= e.options[e.selectedIndex].value;

        if(barangid!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarang/getsupplierbarang',
                        data: { barangid : barangid},
                        success: function (data) {
                            
                            var x = document.getElementById("Gudangpenjualanbarang_supplierid");

                            // hapus option
                            $('#Gudangpenjualanbarang_supplierid option[value!=""]').remove();
                            
                            // buat option
                            for(i=0;i<data.id.length;i++)
                            {
                                var option = document.createElement("option");
                                option.value = data.id[i];
                                option.text = data.text[i];
                                x.add(option);
                            }

                            hitungHargaBarang();
                            hitungHargaTotal();
                            cekStock();
                        },
                        error: function () {
                            alert("Error occured. Please try again (getSupplierBarang)");
                        }
                });
        }
        else
        {
            $('#Gudangpenjualanbarang_supplierid option[value!=""]').remove();
        }
    }
    
    function cekStock()
    {
        var jumlah = document.getElementById("Gudangpenjualanbarang_jumlah").value;
        
        var e = document.getElementById("Gudangpenjualanbarang_barangid");
        var barangid= e.options[e.selectedIndex].value;

        var f = document.getElementById("Gudangpenjualanbarang_lokasipenyimpananbarangid");
        var lokasiid= f.options[f.selectedIndex].value;
        
        var g = document.getElementById("Gudangpenjualanbarang_supplierid");
        var supplierid= g.options[g.selectedIndex].value;
        
        if(barangid!='' && lokasiid!='' && supplierid!='' && jumlah.trim()!='' && isNormalInteger(jumlah))
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarang/cekstock',
                        data: { barangid : barangid, lokasiid : lokasiid, supplierid : supplierid, aksi : 'update', id : <?php echo $modelPenjualan->id; ?>},
                        success: function (data) {
                            if(jumlah > data.stockTersedia)
                            {
                                document.getElementById("Gudangpenjualanbarang_jumlah").value = '';

                                document.getElementById("error_jumlah").innerHTML = 'Stock Tersedia : <b>' + data.stockTersedia + '</b><br /> Stock Gudang : ' + data.stockGudang + '<br /> Stock Antrian : ' + data.stockAntrian;
                                
                                $('#btnSave').prop( "disabled", true);
                            }
                            else
                            {
                                document.getElementById("error_jumlah").innerHTML = '';
                                
                                $('#btnSave').prop( "disabled", false);
                            }
                        },
                        error: function () {
                            alert("Error occured. Please try again (cekStock)");
                        }
            });
        }
        else
        {
            document.getElementById("error_jumlah").innerHTML = '';
            
            $('#btnSave').prop( "disabled", false);
        }
    }
    
    function isNormalInteger(str) {
        var n = ~~Number(str);
        return String(n) === str && n > 0;
    }
    
    counter = <?php echo count($modelBongkarmuat); ?>;

    function cekJumlahAbsen()
    {
        var tgl = document.getElementById("Gudangpenjualanbarang_tanggal_input").value;
        var jumlahAbsen = 0;

        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarang/getjumlahabsen',
                    data: { tanggal : tgl},
                    success: function (jumlah) {

                        if(jumlah>0)
                        {
                            jumlahAbsen = jumlah;
                            if(counter < jumlahAbsen)
                                tambahKaryawan();
                        }
                        else
                            document.getElementById("pesanKaryawan").innerHTML = '<br /><div class="label label-warning">Tidak Ada Karyawan yang Hadir</div>';

                    },
                    error: function () {
                        alert("Error occured. Please try again (cekJumlahAbsen)");
                    }
            });
        }
        else
        {
            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
    }
    
    function tambahKaryawan()
    {
        var tgl = $('#Gudangpenjualanbarang_tanggal_input').val();
		
		
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
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarang/getkaryawanabsenupdate',
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
    
    function hitungHargaBarang()
    {
        if(document.getElementById("Gudangpenjualanbarang_barangid").value!='')
        {
            hargaBarang = 0;
            
            if(document.getElementById('kategori1').checked)
                kategori = document.getElementById('kategori1').value;
            else
                kategori = document.getElementById('kategori2').value;
            
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarang/hargabarang',
                    data: { barangid : document.getElementById("Gudangpenjualanbarang_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarang_supplierid").value},
                    success: function (data) {
                        document.getElementById("Gudangpenjualanbarang_hargasatuan_input").value = data;
                        hitungHargaTotal();
                    },
                    error: function () {
                        alert("Error occured. Please try again");
                    }
            });
        }
        else
        {
            document.getElementById("Gudangpenjualanbarang_hargasatuan_input").value = '0';
            hitungHargaTotal();
        }
    }
    
    function hitungHargaTotal()
    {
        rp = 0;
        rp = document.getElementById("Gudangpenjualanbarang_jumlah").value * document.getElementById("Gudangpenjualanbarang_hargasatuan_input").value;
        document.getElementById("Gudangpenjualanbarang_hargatotal_input").value = rp;
    }
    
    function hitungRupiah()
    {
        rp = 0;
        rp = (document.getElementById("Gudangpenjualanbarang_jumlah").value * 30) / document.getElementById("Bongkarmuat_jumlahkaryawan").value;
        document.getElementById("Bongkarmuat_upah").value = rp;
    }

</script>