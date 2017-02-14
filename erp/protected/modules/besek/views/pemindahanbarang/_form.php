<?php   
        $form=$this->beginWidget('booster.widgets.TbActiveForm',array(
                'type'=>'horizontal',
                'id'=>'besekpemindahanbarang-form',
                'enableAjaxValidation'=>true,
                'clientOptions'=>array(
                                'validateOnChange'=>true,
                )
        )); 
?>

<?php echo $form->errorSummary($modelPemindahan); ?>

<input type="hidden" id="totalJumlah" name="totalJumlah" value="0" />

        <?php 
            echo $form->datePickerGroup($modelPemindahan, 'tanggal', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Besekpemindahanbarang_tanggal_input',
                                                                'readonly'=>'readonly',
                                                                //'onchange'=>'addKaryawan();addJumlahDropdownKaryawan();'
                                                            )
                                                )
                        )
                    ); 
        ?>

        <?php   
                echo $form->dropDownListGroup($modelPemindahan, 'barangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->findAllBySql('select b.id, b.nama from master.barang b inner join transaksi.stocksupplier s on b.id=s.barangid where divisiid='.Yii::app()->session['divisiid']." and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',
                                                    'onchange'=>'getSupplierBarang();'
                                            )
                                    )
                                )
                            );
        ?>

        <div id="inputSupplier">
            &nbsp;
        </div>

        <?php     
                echo $form->dropDownListGroup($modelPemindahan, 'lokasipenyimpananbarangtujuanid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Lokasipenyimpananbarang::model()->findAll('id!='.Yii::app()->session['lokasiid']),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Lokasi',
                                            )
                                    )
                                )
                            );
        ?>
        
		<!-- 
        <div class="form-group">
            <label class="col-sm-5 control-label required" for="Bongkarmuat_jumlahkaryawan">Jumlah Karyawan</label>
            <div class="col-sm-4">
                <select name="Bongkarmuat[jumlahkaryawan]" id="Bongkarmuat_jumlahkaryawan" onchange="hitungRupiah()" class="form-control">
                    <option value="">-- Pilih Tanggal Dahulu --</option>
                </select> 
                <div class="help-block error" id="Bongkarmuat_jumlahkaryawan_em_" style="display: block;"></div>
            </div>
        </div>
		-->
    
    
        <?php /* echo $form->textFieldGroup($modelBongkarmuat, 'upah', array(
                                            'append' => '<input id="btnSet" class="btn btn-info" name="btnSet" value="Set" type="button" onclick="addKaryawan()" />',
                                            'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                            'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'value'=>'0',
                                                                'readonly'=>'readonly',
                                                            )
                                                )
                                        )
        ); 
		*/
		?>  
    <!--
    <div id="inputKaryawan">
        &nbsp;
    </div>
	-->

    <div id="hiddenStock">
        &nbsp;
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
        echo CHtml::ajaxSubmitButton('Simpan',CHtml::normalizeUrl(array('pemindahanbarang/create','render'=>true)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    

                   if(data.result==="OK")
                   {             
                      location.href="'.Yii::app()->baseurl.'/besek/pemindahanbarang/view/id/" + data.pemindahanid;
                   }
                   else
                   {                        
                       $.each(data, function(key, val) 
                       {
                           $("#besekpemindahanbarang-form #"+key+"_em_").text(val);                                                    
                           $("#besekpemindahanbarang-form #"+key+"_em_").show();
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

    function getSupplierBarang()
    {
        var e = document.getElementById("Besekpemindahanbarang_barangid");
        var barangid= e.options[e.selectedIndex].value;

        if(barangid!='')
        {
            $.ajax({
                        dataType: 'json',
                        type: 'POST',
                        url: '<?php echo Yii::app()->baseurl; ?>/besek/pemindahanbarang/getsupplierbarang',
                        data: { barangid : barangid},
                        success: function (data) {
                            
							var content = '<table class="table table-bordered"><tr><th>No</th><th>Supplier</th><th>Stock</th><td>Jumlah</td></tr>';
                            var contentHidden = '';
                            var no=1;
                            for(i=0;i<data.supplierid.length;i++)
                            {
                                content = content + '<tr>\n\
                                                        <td>'+no+'</td>\n\
                                                        <td><input type="text" name="namasupplier[]" id="namasupplier_' + data.supplierid[i] + '" class="form-control" value="' + data.namasupplier[i] + '" readonly />\n\
                                                        <input type="hidden" name="supplierid[]" id="supplierid_' + data.supplierid[i] + '" class="form-control" value="' + data.supplierid[i] + '" />\n\
                                                        <input type="hidden" name="stocksupplierid[]" id="stocksupplierid_' + data.stocksupplierid[i] + '" value="' + data.stocksupplierid[i] + '" />\n\</td>\n\
                                                        \n\
                                                        <td><input type="text" name="stock[]" id="stock_' + data.stocksupplierid[i] + '" class="form-control" value="' + data.jumlah[i] + '" onchange="cekMaxStock(this.id,this.value)" readonly/></td>\n\
\n\                                                     <td><input type="text" name="jumlah[]" id="jumlah_' + data.stocksupplierid[i] + '" class="jumlah form-control"  onchange="cekMaxStock(this.id,this.value)" /></td>\n\
                                                        </tr>';
                                contentHidden = contentHidden + '<input type="hidden" name="stock[]" id="stock_' + data.stocksupplierid[i] + '" value="' + data.jumlah[i] + '" />';
                                no++;
                            }
                            content+='<tr><td></td><td></td><td>Total</td><td><div id="total_jumlah"></div></td></tr></table>';
                            document.getElementById("inputSupplier").innerHTML = content;
                            document.getElementById("hiddenStock").innerHTML = contentHidden;  
                            
                            hitungJumlahTotal();
                        },
                        error: function () {
                            alert("Error occured. Please try again (getSupplierBarang)");
                        }
                });
        }
        else
        {
            document.getElementById("inputSupplier").innerHTML = '';
        }
    }
    
    function cekMaxStock(stock_id, input)
    {
        tmp = stock_id.split('_');
        id = tmp[1];
        
        var jumlahMax = document.getElementById("stock_" + id).value;
        
        if(parseInt(input) > parseInt(jumlahMax) || parseInt(input) < 0 || input === '')
            document.getElementById("jumlah_" + id).value = jumlahMax;
        
        hitungJumlahTotal();
    }
    
    function hitungJumlahTotal()
    {
        var total = 0;
        $(".jumlah").each(function() {
            total = parseInt(total) + parseInt($(this).val());
        });

        document.getElementById("totalJumlah").value = total;
        $('#total_jumlah').html(total);
        
        /*
        var total = 0;
        $("input[name='jumlah[]']").each(function() {
            total = total + $(this).val();
        });

        document.getElementById("totalJumlah").value = total;
        */
        
        //hitungRupiah();
    }
    
    function hitungRupiah()
    {
        rp = 0;
        rp = (parseInt(document.getElementById("totalJumlah").value) * 20) / parseInt(document.getElementById("Bongkarmuat_jumlahkaryawan").value);
        document.getElementById("Bongkarmuat_upah").value = rp;
    }
    
    function addKaryawan()
    {
        var tgl = document.getElementById("Besekpemindahanbarang_tanggal_input").value;
        
        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/besek/pemindahanbarang/getkaryawanabsen',
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
        var tgl = document.getElementById("Besekpemindahanbarang_tanggal_input").value;
        var jmlItem = $('#Bongkarmuat_jumlahkaryawan').children('option').length;
        
        if(tgl!='' && jmlItem <= 1)
        {
            $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url: '<?php echo Yii::app()->baseurl; ?>/besek/pemindahanbarang/getkaryawanabsen',
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
    
    function isNormalInteger(str)
    {
        var n = ~~Number(str);
        return String(n) === str && n > 0;
    }
</script>