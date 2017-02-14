<?php
$this->pageTitle = "Data Setoran - Kasir";

$this->breadcrumbs = array(
    'Kasir', 'Data Setoran'
);
?>

<br>
<?php if (Yii::app()->user->hasFlash('success')): ?>
    <div class="alert alert-success fade in">
        <button type="button" class="close close-sm" data-dismiss="alert">
            <i class="fa fa-times"></i>
        </button>
        <?php echo Yii::app()->user->getFlash('success'); ?>
    </div>    
<?php endif; ?>

<div class="col-lg-12">
    <!-- loading -->
    <div id="AjaxLoader" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(102, 102, 102); z-index: 30001; opacity: 0.7;">
        <p style="position: absolute; color: White; top: 40%; left: 45%;">
            <img src="<?php echo Yii::app()->request->baseUrl; ?>/themes/inspinia/img/ajax-loader-big.gif"></img>
        </p>
    </div>
    <!-- -->      
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Kasir - Data Setoran</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                <div class="ibox-content">      
                    <?php
                    $this->widget('booster.widgets.TbGridView', array(
                        'id' => 'grid',
                        'type' => 'striped bordered hover',
                        'dataProvider' => $model->listDataSetoran(),
                        'filter' => $model,
                        'beforeAjaxUpdate' => 'js: function() {
                            $("#AjaxLoader").show();
                        }',
                        'afterAjaxUpdate' => 'js: function() {                                        
                            $("#AjaxLoader").hide();                  
                            reinstallDatePicker();
                        }',
                        'columns' => array(
                            array(
                                'header' => 'No',
                                'class' => 'IndexColumn',
                            ),
                            array(
                                'class' => 'booster.widgets.TbRelationalColumn',
                                //'name' => 'firstLetter',
                                'header' => 'Detail Barang',
                                'url' => $this->createUrl("datasetoran/childdatasetoran"),
                                'value' => '"<span class=\"fa fa-plus\"></span>"',
                                'htmlOptions' => array('width' => '100px'),
                                'type' => 'html',
                                'afterAjaxUpdate' => 'js:function(tr,rowid,data){
            		
            }'
                            ),
                            array('name' => 'nofaktur', 'value' => function($data, $row) {
                                    //echo $data->id;
                                    $id = explode("--", $data->id);

                                    $pelangganid = $id[0];
                                    $tanggal = $id[1];
                                    $pembelianke = $id[2];

                                    $cekFaktur = Faktur::model()->find("cast(tanggal  as date)='$tanggal' AND pelangganid=" . $pelangganid . " AND jenispembayaran='kredit' AND pembelianke=" . $pembelianke . " AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                                    if (count($cekFaktur) != 0) {
                                        return $cekFaktur->nofaktur;
                                    } else {
                                        return '-';
                                    }
                                }, 'header' => 'No Faktur', 'filter' => false),
                            array('name' => 'tanggal', 'value' => function($data, $row) {
                                    $id = explode("--", $data->id);

                                    $pelangganid = $id[0];
                                    $tanggal = $id[1];
                                    $pembelianke = $id[2];

                                    $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' AND pelangganid=" . $pelangganid . " AND jenispembayaran='kredit' AND pembelianke=" . $pembelianke . " AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                                    //echo count($cekFaktur);
                                    if (count($cekFaktur) != 0) {
                                        return date("d-m-Y", strtotime($cekFaktur->tanggalcetak));
                                    } else {
                                        return '-';
                                    }
                                }, 'header' => 'Tanggal Cetak',
                                'filter' => $this->widget(
                                        'booster.widgets.TbDatePicker', array(
                                    'name' => 'Datasetoran[tanggalcetak]',
                                    'attribute' => 'tanggalcetak',
                                    'htmlOptions' => array(
                                        'class' => 'form-control ct-form-control',
                                         'id' => 'Datasetoran_tanggalcetak',
                                    ),
                                    'options' => array(
                                        'format' => 'yyyy-mm-dd',
                                        'language' => 'en',
                                        'class' => 'form-controls form-control-inline input-medium default-date-picker',
                                        'size' => "16"
                                    )
                                        ), true
                                )
                            ),
                            array('name' => 'tanggal', 'value' => 'date("d-m-Y", strtotime($data->tanggal))', 'header' => 'Tanggal Pembelian',
                                'filter' => $this->widget(
                                        'booster.widgets.TbDatePicker', array(
                                    'name' => 'Datasetoran[tanggal]',
                                    'attribute' => 'tanggal',
                                    'htmlOptions' => array(
                                        'class' => 'form-control ct-form-control',
                                         'id' => 'Datasetoran_tanggal',
                                    ),
                                    'options' => array(
                                        'format' => 'yyyy-mm-dd',
                                        'language' => 'en',
                                        'class' => 'form-controls form-control-inline input-medium default-date-picker',
                                        'size' => "16"
                                    )
                                        ), true
                                )
                            ),
                            array('name' => 'pelanggan', 'value' => '$data->pelanggan', 'header' => 'Pelanggan'),
                            array(
                                'header' => 'Total Belanja',
                                'name' => 'tanggal',
                                'value' => function($data, $row) {

                                    $id = explode("--", $data->id);

                                    $pelangganid = $id[0];
                                    $tanggal = $id[1];
                                    $pembelianke = $id[2];

                                    $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' 
											AND pelangganid=" . $pelangganid . " AND jenispembayaran='kredit' 
												AND pembelianke=" . $pembelianke . " AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                                    if (count($cekFaktur) != 0) {
                                        return number_format($cekFaktur->hargatotal);
                                    } else {
                                        return number_format(Datasetoran::model()->getTotalBelanja($pelangganid, $tanggal, $pembelianke));
                                    }
                                },
                                'filter' => false
                            ),
                            array(
                                'header' => 'Diskon',
                                'name' => 'tanggal',
                                'value' => function($data, $row) {

                                    $id = explode("--", $data->id);

                                    $pelangganid = $id[0];
                                    $tanggal = $id[1];
                                    $pembelianke = $id[2];

                                    $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' 
											AND pelangganid=" . $pelangganid . " AND jenispembayaran='kredit' 
												AND pembelianke=" . $pembelianke . " AND 
													lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                                    if (count($cekFaktur) != 0) {
                                        return number_format($cekFaktur->diskon);
                                    } else {
                                        return '-';
                                    }
                                },
                                'filter' => false
                            ),
                            array(
                                'header' => 'Total Bayar',
                                'name' => 'tanggal',
                                'value' => function($data, $row) {

                                    $id = explode("--", $data->id);

                                    $pelangganid = $id[0];
                                    $tanggal = $id[1];
                                    $pembelianke = $id[2];

                                    $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' 
												AND pelangganid=" . $pelangganid . " AND jenispembayaran='kredit' 
													AND pembelianke=" . $pembelianke . " 
														AND lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                                    if (count($cekFaktur) != 0) {
                                        return number_format($cekFaktur->bayar);
                                    } else {
                                        return '-';
                                    }
                                },
                                'filter' => false
                            ),
                            array(
                                'header' => 'Sisa Tagihan',
                                'name' => 'tanggal',
                                'value' => function($data, $row) {

                                    $id = explode("--", $data->id);

                                    $pelangganid = $id[0];
                                    $tanggal = $id[1];
                                    $pembelianke = $id[2];

                                    $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal' 
											AND pelangganid=" . $pelangganid . " AND jenispembayaran='kredit' 
													AND pembelianke=" . $pembelianke . " AND 
														lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                                    if (count($cekFaktur) != 0) {

                                        return number_format(($cekFaktur->sisa));
                                    } else {
                                        return '-';
                                    }
                                },
                                'filter' => false
                            ),
                            array(
                                'header' => 'Status',
                                'value' => function($data, $row) {

                                    $id = explode("--", $data->id);

                                    $pelangganid = $id[0];
                                    $tanggal = $id[1];
                                    $pembelianke = $id[2];
                                    $cekFaktur = Faktur::model()->find("cast(tanggal as date)='$tanggal'
										AND pelangganid=" . $pelangganid . " AND jenispembayaran='kredit' 
												AND pembelianke=" . $pembelianke . " AND 
													lokasipenyimpananbarangid=" . Yii::app()->session['lokasiid']);
                                    if (count($cekFaktur) != 0) {
                                        if ($cekFaktur->sisa == 0) {
                                            echo '<div class="btn btn-warning">LUNAS</div>';
                                        } else {
                                            echo '<div class="btn btn-primary">BELUM LUNAS</div>';
                                        }
                                    } else {
                                        return '-';
                                    }
                                },
                                'filter' => false
                            ),
                            array(
                                'header' => 'Action',
                                'class' => 'booster.widgets.TbButtonColumn',
                                'template' => '{setor} {print}',
                                'buttons' => array(
                                    'setor' => array(
                                        'label' => 'Setor',
                                        'icon' => 'fa fa-pencil',
                                        'url' => 'Yii::app()->createUrl("kasir/datasetoran/popupsetor", array("id"=>$data->id))',
                                        'options' => array(
                                            'class' => 'btn btn-success btn-xs',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                //'dataType'=>'json',
                                                'url' => "js:$(this).attr('href')",
                                                'beforeSend' => 'function(){  $("#AjaxLoader").show(); }',
                                                'success' => 'function(data) {
							                                                            		$("#AjaxLoader").hide();							                                                            									                                                            								                                                        
							                                                            		$("#modal-setoran .modal-body").html(data); 
							                                                            		$("#modal-setoran").modal();
							                                                            		
																								}'
                                            ),
                                        ),
                                    ),
                                    'print' => array(
                                        'label' => 'Print Faktur',
                                        'icon' => 'fa fa-print',
                                        // 'url'=>'Yii::app()->createUrl("kasir/datasetoran/cetak", array("id"=>$data->id))', 
                                        'url' => 'Yii::app()->createUrl("kasir/datasetoran/checkformfaktur", array("id"=>$data->id))',
                                        'options' => array(
                                            'class' => 'btn btn-success btn-xs',
                                            //'onclick'=>'cetakFaktur(this);',
                                            'ajax' => array(
                                                'type' => 'POST',
                                                'dataType' => 'json',
                                                'url' => "js:$(this).attr('href')",
                                                'beforeSend' => 'function(){  $("#AjaxLoader").show(); }',
                                                'success' => 'js:function(data) 
																														{
																															$("#AjaxLoader").hide();
																															if(data.status=="true")
																															{
																																//location.href="' . Yii::app()->baseurl . '/kasir/datasetoran/printfaktur/pelangganid/" + data.pelangganid+"/tanggal/" +data.tanggal+"/pembelianke/" +data.pembelianke;
																																window.open("' . Yii::app()->baseurl . '/kasir/datasetoran/printfaktur/pelangganid/" + data.pelangganid+"/tanggalcetak/" +data.tanggalcetak+"/pembelianke/" +data.pembelianke+"/tanggalpembelian/" +data.tanggalpembelian, "_blank");  
																															}
																															else 
																															{
																																location.href="' . Yii::app()->baseurl . '/kasir/datasetoran/formfaktur/pelangganid/" + data.pelangganid+"/tanggalpembelian/" +data.tanggalpembelian+"/pembelianke/" +data.pembelianke+"/tanggalcetak/" +data.tanggalcetak;
																															}
																																														
																														}'
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ));
                    ?>
                    <!-- content -->
                </div>
            </div>	              
        </div>
    </div>
</div>


<!-- modal update -->
<div class="modal fade" id="modal-setoran" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Data Setoran</h4>
            </div>
            <div class="modal-body">        
                <br>    

            </div>
            <div class="modal-footer">         
            </div>

        </div>
    </div>
</div>
<!--  -->

                    <?php
                    Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Datasetoran_tanggal').datepicker();
    $('#Datasetoran_tanggalcetak').datepicker();
}
");
                    ?>

<script>
    $("#AjaxLoader").hide();    
</script>    
