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

<input type="hidden" name="waktuinsert" value="<?php echo date("Y-m-d H:", time()); ?>" />
<input type="hidden" name="waktuupdate" value="<?php echo date("Y-m-d H:", time()); ?>" />
<input type="hidden" id="totalJumlah" name="totalJumlah" value="<?php echo $modelPemindahan->jumlah; ?>" />

<input type="hidden" id="Besekpemindahanbarang_id" name="Besekpemindahanbarang[id]" value="<?php echo $modelPemindahan->id; ?>" />

        <?php 
            echo $form->datePickerGroup($modelPemindahan, 'tanggal', array(
                                'prepend' => '<i class="glyphicon glyphicon-calendar"></i>',
                                'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                'widgetOptions' => array(
                                                        'htmlOptions'=>array(
                                                                'id'=>'Besekpemindahanbarang_tanggal_input',
                                                                'readonly'=>'readonly',
                                                                'onchange'=>'deleteAllKaryawan();'
                                                            )
                                                )
                        )
                    ); 
        ?>

        <input type="hidden" id="Besekpemindahanbarang_barangid" name="Besekpemindahanbarang[barangid]" value="<?php echo $modelPemindahan->barangid; ?>" />
        <?php   
                echo $form->dropDownListGroup($modelPemindahan, 'barangid',array(
                                    'wrapperHtmlOptions'=>array('class'=>'col-sm-4'),
                                    'widgetOptions' => array(
                                            'data' => CHtml::listData(Barang::model()->findAllBySql('select b.id, b.nama from master.barang b inner join transaksi.stocksupplier s on b.id=s.barangid where divisiid='.Yii::app()->session['divisiid']." and lokasipenyimpananbarangid=".Yii::app()->session['lokasiid']),'id','nama'),
                                            'htmlOptions' => array(
                                                    'prompt'=>'Pilih Barang',
                                                    'onchange'=>'getSupplierBarang();',
                                                    'disabled'=>'disabled',
                                                    'name'=>'Besekpemindahanbarang_barangid_view',
                                            )
                                    )
                                )
                            );
        ?>

        <div id="inputSupplier">
			<table class="table table-bordered">
			<tr><th>No</th><th>Supplier</th><th>Stock</th><th>Jumlah</th></tr>
            <?php $no=1; foreach($modelPemindahanDetail as $detail) { ?>
                <?php $sp = Stocksupplier::model()->with('supplier')->find('barangid='.$modelPemindahan->barangid.' and lokasipenyimpananbarangid='.$modelPemindahan->lokasipenyimpananbarangid.' and supplierid='.$detail->supplierid); ?>
                    <tr>
						<td>
							<?php echo $no++; ?>							
                    <!-- <div class="form-group">
                        <label class="col-sm-5 control-label" for="karyawanid">Supplier</label>
                        <div class="col-sm-4">
                            <input type="hidden" name="supplierid[]" id="supplierid_<?php echo $detail->supplierid; ?>" class="form-control" value="<?php echo $detail->supplierid; ?>" />
                            <input type="hidden" name="stocksupplierid[]" id="stocksupplierid_<?php echo $sp->id; ?>" value="<?php echo $sp->id; ?>" />
                            <input type="text" name="namasupplier[]" id="namasupplier_<?php echo $detail->supplierid; ?>" class="form-control" value="<?php echo $sp->supplier->namaperusahaan; ?>" readonly />
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="jumlah[]" id="jumlah_<?php echo $sp->id; ?>" class="form-control" value="<?php echo $detail->jumlah; ?>" onchange="cekMaxStock(this.id,this.value,<?php echo ($sp->jumlah + $detail->jumlah); ?>)" />
                            <input type="hidden" name="stock[]" id="stock_<?php echo $sp->id; ?>" value="<?php echo $detail->jumlah; ?>" />
                        </div>
                    </div>-->
					</td>
					<td>
						<input type="hidden" name="supplierid[]" id="supplierid_<?php echo $detail->supplierid; ?>" class="form-control" value="<?php echo $detail->supplierid; ?>" />
						<input type="hidden" name="stocksupplierid[]" id="stocksupplierid_<?php echo $sp->id; ?>" value="<?php echo $sp->id; ?>" />
						<input type="text" name="namasupplier[]" id="namasupplier_<?php echo $detail->supplierid; ?>" class="form-control" value="<?php echo $sp->supplier->namaperusahaan; ?>" readonly />
					</td>
					<td>
						<input type="text" name="stocksupplier[]" id="stocksupplier_<?php echo $sp->id; ?>" class="form-control" readonly value="<?php echo $sp->jumlah; ?>" onchange="cekMaxStock(this.id,this.value,<?php echo ($sp->jumlah + $detail->jumlah); ?>)" />
						<input type="hidden" name="stock[]" id="stock_<?php echo $sp->id; ?>" value="<?php echo $sp->jumlah; ?>" />
					</td>
					<td>
						<input type="text" name="jumlah[]" id="jumlah_<?php echo $sp->id; ?>" class="form-control" value="<?php echo $detail->jumlah; ?>" onchange="cekMaxStock(this.id,this.value,<?php echo ($sp->jumlah + $detail->jumlah); ?>)" />						
					</td>
					</tr>
            <?php } ?>
			</table>
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
        echo CHtml::ajaxSubmitButton('Update',CHtml::normalizeUrl(array('pemindahanbarang/update','id'=>$modelPemindahan->id)),
            array(
                'dataType'=>'json',
                'type'=>'POST',                      
                'success'=>'js:function(data) 
                 {    

                   if(data.result==="OK")
                   {  
                        location.href="'.Yii::app()->createUrl("besek/pemindahanbarang/view", array("id"=>$modelPemindahan->id)).'";
                        selesai();
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
            ),array('id'=>'btnUpdatePemindahanBarang','class'=>'btn btn-primary'));
    ?> 
</div>

<br />

<?php $this->endWidget(); ?>

<script type="text/javascript">

    counter = 1<?php //echo count($modelBongkarmuat); ?>;
    
    function selesai()
    {
        n=1;
    }
    
    function cekJumlahAbsen()
    {
        var tgl = document.getElementById("Besekpemindahanbarang_tanggal_input").value;
        var jumlahAbsen = 0;

        if(tgl!='')
        {
            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/besek/pemindahanbarang/getjumlahabsen',
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
                            counterNext = counter + 1;
                            
                            content = '<div class="form-group"><label class="col-sm-5 control-label required" for="karyawanid">Karyawan</label><div class="col-sm-4">';
                            content = content + '<select name="karyawanid[]" id="karyawanid" class="form-control">' + data.option + '</select>';
                            content = content + '</div></div><div id="inputKaryawan_' + counterNext + '"></div>';
                            
                            document.getElementById("inputKaryawan_"+counter).innerHTML = content;
                            counter++;
                            
                            // ubah nilai jumlah karyawan
                            document.getElementById("Bongkarmuat_jumlahkaryawan").value = counter;
                            hitungRupiah();
                            
                            document.getElementById("pesanKaryawan").innerHTML = '';
                        }
                        else
                            document.getElementById("pesanKaryawan").innerHTML = '<br /><div class="label label-warning">Tidak Ada Karyawan yang Hadir</div>';

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
                            
                            var content = '';
                            var contentHidden = '';
                            for(i=0;i<data.supplierid.length;i++)
                            {
                                content = content + '<div class="form-group"><label class="col-sm-5 control-label" for="karyawanid">Supplier</label><div class="col-sm-4"><input type="hidden" name="supplierid[]" id="supplierid_' + data.supplierid[i] + '" class="form-control" value="' + data.supplierid[i] + '" /><input type="hidden" name="stocksupplierid[]" id="stocksupplierid_' + data.stocksupplierid[i] + '" value="' + data.stocksupplierid[i] + '" /><input type="text" name="namasupplier[]" id="namasupplier_' + data.supplierid[i] + '" class="form-control" value="' + data.namasupplier[i] + '" readonly /></div><div class="col-sm-3"><input type="text" name="jumlah[]" id="jumlah_' + data.stocksupplierid[i] + '" class="form-control" value="' + data.jumlah[i] + '" onchange="cekMaxStock(this.id,this.value)" /></div></div>';
                                contentHidden = contentHidden + '<input type="hidden" name="stock[]" id="stock_' + data.stocksupplierid[i] + '" value="' + data.jumlah[i] + '" />';
                            }
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
    
    function cekMaxStock(stock_id, input, jumlahAwal)
    {
        tmp = stock_id.split('_');
        id = tmp[1];
        
        if(parseInt(input) > parseInt(jumlahAwal) || input === '')
            document.getElementById("jumlah_" + id).value = jumlahAwal;
        else if(parseInt(input) < 0)
            document.getElementById("jumlah_" + id).value = 0;
        
        hitungJumlahTotal();
    }
    
    function hitungJumlahTotal()
    {
        var total = 0;
        $("input[name='jumlah[]']").each(function() {
            total = total + $(this).val();
        });

        document.getElementById("totalJumlah").value = total;
        
        hitungRupiah();
    }
    
    function hitungRupiah()
    {
        rp = 0;
        rp = (document.getElementById("totalJumlah").value * 20) / document.getElementById("Bongkarmuat_jumlahkaryawan").value;
        document.getElementById("Bongkarmuat_upah").value = rp;
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