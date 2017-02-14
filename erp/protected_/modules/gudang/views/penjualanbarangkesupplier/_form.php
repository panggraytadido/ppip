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

        <?php 
            echo $form->datePickerGroup($modelPenjualan, 'tanggal', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Gudangpenjualanbarangkesupplier_tanggal_input',
                                                                'readonly'=>'readonly',
                                                                
                                                            )
                                                )
                        )
                    ); 
        ?>

        <?php     
				$criteria = new  CDbCriteria;				
				$criteria->order='namaperusahaan asc';
                echo $form->dropDownListGroup($modelPenjualan, 'supplierpembeliid',array(
                        'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                        'widgetOptions' => array(
                                            'data' => CHtml::listData(Supplier::model()->findAll($criteria),'id','namaperusahaan'),
                                            'htmlOptions' => array(
                                                                    'prompt'=>'Pilih Supplier Pembeli',
                                                            )
                                            )
                        )
                    );
        ?>
        
        <?php   
				$criteria = new  CDbCriteria;
				$criteria->condition = 'divisiid='.Yii::app()->session["divisiid"].' and lokasipenyimpananbarangid='.Yii::app()->session["lokasiid"];
				$criteria->order='nama asc';
                echo $form->dropDownListGroup($modelPenjualan, 'barangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->with('stocksupplier')->findAll($criteria),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',
                                                    'onchange'=>'getLokasiBarang();getSupplierBarang();'
                                            )
                                    )
                                )
                            );
        ?>

        <?php
                echo $form->dropDownListGroup($modelPenjualan, 'supplierid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => array(),
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
                                            'data' => array(),
                                            'htmlOptions' => array(
                                                    'onchange'=>'cekStock();'
                                            )
                                    )
                                )
                            );
        ?>
        
        <?php 
                echo $form->textFieldGroup($modelPenjualan, 'jumlah',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'htmlOptions' => array(
                                                    'onchange'=>'cekStock();hitungHargaTotal();hitungRupiah();',
                                                    'value'=>'0'
                                            )
                                    ),
                                    'hint' => '<p id="error_jumlah" style="color:red !important;"></p>',
                            )
                ); 
        ?>
            
        <?php echo $form->textFieldGroup($modelPenjualan, 'box',array('wrapperHtmlOptions'=>array('class'=>'col-sm-4'))); ?> 
        
        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Gudangpenjualanbarangkesupplier_kategori">Kategori</label>
            <div class="col-sm-4">
                <input type="radio" name="Gudangpenjualanbarangkesupplier[kategori]" id="kategori1" onchange="hitungHargaBarang();" value="1" checked="checked" /> Eceran 
                <input type="radio" name="Gudangpenjualanbarangkesupplier[kategori]" id="kategori2" onchange="hitungHargaBarang();" value="2" /> Grosir 
            </div>
        </div>
	
       <?php echo $form->textFieldGroup($modelPenjualan, 'hargasatuan', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Gudangpenjualanbarangkesupplier_hargasatuan_input',
                                                                'value'=>'0',
                                                            )
                                                )
                                        )
            ); ?> 

       <?php echo $form->textFieldGroup($modelPenjualan, 'hargatotal', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Gudangpenjualanbarangkesupplier_hargatotal_input',
                                                                'value'=>'0',
                                                            )
                                                )
                                        )); ?>  

       

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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('penjualanbarangkesupplier/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    

                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/gudang/penjualanbarangkesupplier/view/id/" + data.penjualanid;
                   }
				   else if(data.result==="FALSE")
				   {
					   document.getElementById("loadingProses").style.visibility = "hidden";
					   alert("JUMLAH MELEBIHI STOCK, JUMLAH PENJUALAN :"+data.jumlahpenjualan+", JUMLAH STOCK :"+data.stock);					   
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
            ),array('id'=>'btnSave','class'=>'btn btn-primary'));      
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">

    function getLokasiBarang()
    {
        var e = document.getElementById("Gudangpenjualanbarangkesupplier_barangid");
        var barangid= e.options[e.selectedIndex].value;

        if(barangid!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarangkesupplier/getlokasibarang',
                        data: { barangid : barangid},
                        success: function (data) {
                            
                            var x = document.getElementById("Gudangpenjualanbarangkesupplier_lokasipenyimpananbarangid");

                            // hapus option
                            $('#Gudangpenjualanbarangkesupplier_lokasipenyimpananbarangid option[value!=""]').remove();
                            
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
            $('#Gudangpenjualanbarangkesupplier_lokasipenyimpananbarangid option[value!=""]').remove();
        }
    }
    
    function getSupplierBarang()
    {
        var e = document.getElementById("Gudangpenjualanbarangkesupplier_barangid");
        var barangid= e.options[e.selectedIndex].value;

        if(barangid!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarangkesupplier/getsupplierbarang',
                        data: { barangid : barangid},
                        success: function (data) {
                            
                            var x = document.getElementById("Gudangpenjualanbarangkesupplier_supplierid");

                            // hapus option
                            $('#Gudangpenjualanbarangkesupplier_supplierid option[value!=""]').remove();
                            
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
            $('#Gudangpenjualanbarangkesupplier_supplierid option[value!=""]').remove();
        }
    }
    
    function cekStock()
    {
        var jumlah = document.getElementById("Gudangpenjualanbarangkesupplier_jumlah").value;
        
        var e = document.getElementById("Gudangpenjualanbarangkesupplier_barangid");
        var barangid= e.options[e.selectedIndex].value;

        var f = document.getElementById("Gudangpenjualanbarangkesupplier_lokasipenyimpananbarangid");
        var lokasiid= f.options[f.selectedIndex].value;
        
        var g = document.getElementById("Gudangpenjualanbarangkesupplier_supplierid");
        var supplierid= g.options[g.selectedIndex].value;
        
        if(barangid!='' && lokasiid!='' && supplierid!='' && jumlah.trim()!='' && isNormalInteger(jumlah))
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarangkesupplier/cekstock',
                        data: { barangid : barangid, lokasiid : lokasiid, supplierid : supplierid, aksi : 'create', id : ''},
                        success: function (data) {
                            if(parseFloat(jumlah) > parseFloat(data.stockTersedia))
                            {
                                document.getElementById("Gudangpenjualanbarangkesupplier_jumlah").value = '';

                                document.getElementById("error_jumlah").innerHTML = 'Stock Tersedia : <b>' + data.stockTersedia + '</b><br /> Stock Gudang : ' + data.stockGudang;
                                
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
    
    function hitungHargaBarang()
    {
        if(document.getElementById("Gudangpenjualanbarangkesupplier_barangid").value!='')
        {
            hargaBarang = 0;
            
            if(document.getElementById('kategori1').checked)
                kategori = document.getElementById('kategori1').value;
            else
                kategori = document.getElementById('kategori2').value;
            
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarangkesupplier/hargabarang',
                    data: { barangid : document.getElementById("Gudangpenjualanbarangkesupplier_barangid").value, kategori : kategori, supplierid : document.getElementById("Gudangpenjualanbarangkesupplier_supplierid").value},
                    success: function (data) {
                        document.getElementById("Gudangpenjualanbarangkesupplier_hargasatuan_input").value = data;
                        hitungHargaTotal();
                    },
                    error: function () {
                        alert("Error occured. Please try again (hitungHargaBarang)");
                    }
            });
        }
        else
        {
            document.getElementById("Gudangpenjualanbarangkesupplier_hargasatuan_input").value = '0';
            hitungHargaTotal();
        }
    }
    
    function hitungHargaTotal()
    {
        rp = 0;
        rp = document.getElementById("Gudangpenjualanbarangkesupplier_jumlah").value * document.getElementById("Gudangpenjualanbarangkesupplier_hargasatuan_input").value;
        document.getElementById("Gudangpenjualanbarangkesupplier_hargatotal_input").value = rp;
    }
    
    function hitungRupiah()
    {
        rp = 0;
        rp = (document.getElementById("Gudangpenjualanbarangkesupplier_jumlah").value * 30) / document.getElementById("Bongkarmuat_jumlahkaryawan").value;
        document.getElementById("Bongkarmuat_upah").value = rp;
    }
    
    function addKaryawan()
    {
        var tgl = document.getElementById("Gudangpenjualanbarangkesupplier_tanggal_input").value;
        
        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarangkesupplier/getkaryawanabsen',
                    data: { tanggal : tgl},
                    success: function (data) {
                        
                        if(data.option!='')
                        {
                            var jmlInput = document.getElementById("Bongkarmuat_jumlahkaryawan").value;
                            var content = '';
                            for(i=1;i<=jmlInput;i++)
                            {
                                content = content + '<div class="form-group"><label class="col-sm-5 control-label" for="karyawanid">Karyawan</label><div class="col-sm-4"><select name="karyawanid[]" id="karyawanid_' + i + '" class="form-control">' + data.option + '</select></div></div>';
                            }
                            document.getElementById("inputKaryawan").innerHTML = content;
                        }
                        else
                        {
                            $('#Bongkarmuat_jumlahkaryawan option[value!=""]').remove();
                            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Tidak Ada Karyawan yang Hadir</div><br /><br />';
                        }

                    },
                    error: function () {
                        alert("Error occured. Please try again (getkaryawanabsen)");
                    }
            });
        }
        else
        {
            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
    }
    
    function addJumlahDropdownKaryawan()
    {
        var tgl = document.getElementById("Gudangpenjualanbarangkesupplier_tanggal_input").value;
        var jmlItem = $('#Bongkarmuat_jumlahkaryawan').children('option').length;
        
        if(tgl!='' && jmlItem <= 1)
        {
            $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/gudang/penjualanbarangkesupplier/getkaryawanabsen',
                    data: { tanggal : tgl},
                    success: function (data) {
                        
                        if(data.option!='')
                        {
                            var x = document.getElementById("Bongkarmuat_jumlahkaryawan");
                            $('#Bongkarmuat_jumlahkaryawan option[value!=""]').remove();
                            for(i=0;i<data.jml;i++)
                            {
                                var option = document.createElement("option");
                                option.value = (i+1);
                                option.text = (i+1);
                                x.add(option);
                            }
                        }
                        else
                        {
                            $('#Bongkarmuat_jumlahkaryawan option[value!=""]').remove();
                            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Tidak Ada Karyawan yang Hadir.</div><br /><br />';
                        }

                    },
                    error: function () {
                        alert("Error occured. Please try again (addJumlahDropdownKaryawan)");
                    }
            });
        }
        else
        {
            document.getElementById("inputKaryawan").innerHTML = '<div class="label label-warning">Pilih Tanggal Dahulu</div><br /><br />';
        }
    }
</script>