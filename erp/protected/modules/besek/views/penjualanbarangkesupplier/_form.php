<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'besekpenjualanbarang-form',
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
                                                                'id'=>'Besekpenjualanbarangkesupplier_tanggal',
                                                                'readonly'=>'readonly',
                                                            )
                                                )
                        )
                    ); 
        ?>

        <?php    
				$criteria=new CDbCriteria;				
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
				$criteria=new CDbCriteria;				
				$criteria->condition='divisiid='.Yii::app()->session['divisiid']." and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid'];
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
//                                                    'prompt'=>'Pilih Lokasi',
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
                                                    'onchange'=>'cekStock();hitungHargaTotal();',
                                                    'value'=>'0',
                                                    'id'=>'Besekpenjualanbarangkesupplier_jumlah'
                                            )
                                    ),
                                    'hint' => '<p id="error_jumlah" style="color:red !important;"></p>',
                            )
                ); 
        ?>
            
        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Besekpenjualanbarangkesupplier_kategori">Kategori</label>
            <div class="col-sm-4">
                <input type="radio" name="Besekpenjualanbarangkesupplier[kategori]" id="kategori1" onchange="hitungHargaBarang();" value="1" checked="checked" /> R.Ikan 
                <input type="radio" name="Besekpenjualanbarangkesupplier[kategori]" id="kategori2" onchange="hitungHargaBarang();" value="2" /> Grosir 
            </div>
        </div>
	
       <?php echo $form->textFieldGroup($modelPenjualan, 'hargasatuan', array(
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'readonly'=>'readonly',
                                                                'id'=>'Besekpenjualanbarangkesupplier_hargasatuan_input',
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
                                                                'id'=>'Besekpenjualanbarangkesupplier_hargatotal_input',
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
                      location.href="'.Yii::app()->baseurl.'/besek/penjualanbarangkesupplier/view/id/" + data.penjualanid;
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
                           $("#besekpenjualanbarang-form #"+key+"_em_").text(val);                                                    
                           $("#besekpenjualanbarang-form #"+key+"_em_").show();
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
        var e = document.getElementById("Besekpenjualanbarangkesupplier_barangid");
        var barangid= e.options[e.selectedIndex].value;

        if(barangid!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/besek/penjualanbarangkesupplier/getlokasibarang',
                        data: { barangid : barangid},
                        success: function (data) {
                            
                            var x = document.getElementById("Besekpenjualanbarangkesupplier_lokasipenyimpananbarangid");

                            // hapus option
                            $('#Besekpenjualanbarangkesupplier_lokasipenyimpananbarangid option[value!=""]').remove();
                            
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
            $('#Besekpenjualanbarangkesupplier_lokasipenyimpananbarangid option[value!=""]').remove();
        }
    }
    
    function getSupplierBarang()
    {
        var e = document.getElementById("Besekpenjualanbarangkesupplier_barangid");
        var barangid= e.options[e.selectedIndex].value;

        if(barangid!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/besek/penjualanbarangkesupplier/getsupplierbarang',
                        data: { barangid : barangid},
                        success: function (data) {
                            
                            var x = document.getElementById("Besekpenjualanbarangkesupplier_supplierid");

                            // hapus option
                            $('#Besekpenjualanbarangkesupplier_supplierid option[value!=""]').remove();
                            
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
            $('#Besekpenjualanbarangkesupplier_supplierid option[value!=""]').remove();
        }
    }
    
    function cekStock()
    {
        var jumlah = document.getElementById("Besekpenjualanbarangkesupplier_jumlah").value;
        
        var e = document.getElementById("Besekpenjualanbarangkesupplier_barangid");
        var barangid= e.options[e.selectedIndex].value;

        var f = document.getElementById("Besekpenjualanbarangkesupplier_lokasipenyimpananbarangid");
        var lokasiid= f.options[f.selectedIndex].value;
        
        var g = document.getElementById("Besekpenjualanbarangkesupplier_supplierid");
        var supplierid= g.options[g.selectedIndex].value;
        
        if(barangid!='' && lokasiid!='' && supplierid!='' && jumlah.trim()!='' && isNormalInteger(jumlah))
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/besek/penjualanbarangkesupplier/cekstock',
                        data: { barangid : barangid, lokasiid : lokasiid, supplierid : supplierid, aksi : 'create', id : ''},
                        success: function (data) {
                            if(parseInt(jumlah) > parseInt(data.stockTersedia))
                            {
                                document.getElementById("Besekpenjualanbarangkesupplier_jumlah").value = '';

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
        if(document.getElementById("Besekpenjualanbarangkesupplier_barangid").value!='')
        {
            hargaBarang = 0;
            
            if(document.getElementById('kategori1').checked)
                kategori = document.getElementById('kategori1').value;
            else
                kategori = document.getElementById('kategori2').value;
            
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/besek/penjualanbarangkesupplier/hargabarang',
                    data: { barangid : document.getElementById("Besekpenjualanbarangkesupplier_barangid").value, kategori : kategori, supplierid : document.getElementById("Besekpenjualanbarangkesupplier_supplierid").value},
                    success: function (data) {
                        document.getElementById("Besekpenjualanbarangkesupplier_hargasatuan_input").value = data;
                        hitungHargaTotal();
                    },
                    error: function () {
                        alert("Error occured. Please try again (hitungHargaBarang)");
                    }
            });
        }
        else
        {
            document.getElementById("Besekpenjualanbarangkesupplier_hargasatuan_input").value = '0';
            hitungHargaTotal();
        }
    }
    
    function hitungHargaTotal()
    {
        rp = 0;
        rp = document.getElementById("Besekpenjualanbarangkesupplier_jumlah").value * document.getElementById("Besekpenjualanbarangkesupplier_hargasatuan_input").value;
        document.getElementById("Besekpenjualanbarangkesupplier_hargatotal_input").value = rp;
    }
</script>