<?php
$this->pageTitle = "Pegawai - Absensi";

$this->breadcrumbs=array(
	'Pegawai',
        'Absensi',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        
        <div class="col-lg-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Form Absen</h5>
                </div>
                <div class="ibox-content">
                    
                    <?php if(Yii::app()->user->hasFlash('success')):?>
                            <div class="alert alert-success fade in">
                                              <button type="button" class="close close-sm" data-dismiss="alert">
                                                  <i class="fa fa-times"></i>
                                              </button>
                                              <?php echo Yii::app()->user->getFlash('success'); ?>
                                          </div>    
                    <?php endif; ?>
                    
                    <div class="row">
                        <div class="col-sm-12 b-r">
                            <form role="form" class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Tanggal</label>
                                    <div class="col-lg-9">
                                        <span id="tanggal" class="form-control">
                                            <?php echo date("d-m-Y"); ?>
                                        </span>
                                        <input id="tanggal_input" name="tanggal_input" value="<?php echo date("Y-m-d"); ?>" class="form-control" type="hidden">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Jam Masuk</label>
                                    <div class="col-lg-9">
                                        <span id="jammasuk" class="form-control" title="" data-original-title="" type="button" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="right" data-content="ABSEN MASUK BELUM DIMULAI">
                                            <span id="hours"></span> : <span id="minutes"></span> : <span id="seconds"></span>
                                        </span>
                                        <input type="hidden" id="jammasuk_input" name="jammasuk_input" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Jam Keluar</label>
                                    <div class="col-lg-9">
                                        <span id="jamkeluar" class="form-control" title="" data-original-title="" type="button" class="btn btn-primary" data-container="body" data-toggle="popover" data-placement="right" data-content="ABSEN KELUAR BELUM DIMULAI">
                                            <span id="hours2"></span> : <span id="minutes2"></span> : <span id="seconds2"></span> <span id="AMPM2"></span>
                                        </span>
                                        <input type="hidden" id="jamkeluar_input" name="jamkeluar_input" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-3 control-label">Status</label>
                                    <div class="col-lg-9">
                                        <select id="status_input" name="status_input" class="form-control">
                                            <option value="1">Hadir</option>
                                            <option value="2">Izin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-offset-3 col-lg-9">
                                        <button id="btnAbsen" class="ladda-button btn btn-primary" data-style="expand-left" type="button">
                                            <span class="ladda-label">Absen</span>
                                            <span class="ladda-spinner"></span>
                                            <div style="width: 0px;" class="ladda-progress"></div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-12">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Daftar Absensi Bulan ini</b>
                    </div>
                    <div class="pull-right">
                        <a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
                    </div>
                </div>
                
                <div class="ibox-content">
                    
<?php $this->widget('booster.widgets.TbGridView', array(
	'id'=>'absensipegawai-grid',
        'type' => 'striped bordered hover',
	'dataProvider'=>$model->searchAbsensi(),
	'filter'=>$model,
        'afterAjaxUpdate' => 'reinstallDatePicker', 
	'columns'=>array(
		array(
                    'header'=>'No',
                    'class'=>'IndexColumn',
                ),
                array('name'=>'tanggal','value'=>'date("d-m-Y", strtotime($data->tanggal))','header'=>'Tanggal',
                        'filter'=>  $this->widget(
                            'booster.widgets.TbDatePicker',
                            array(
                                'name' => 'Absensipegawai[tanggal]',
                                'attribute' => 'tanggal',
                                'htmlOptions' => array(
                                                    'id' => 'Absensipegawai_tanggal_index',
                                                    'class'=>'form-control ct-form-control',
                                                ),
                                'options' => array(
                                    'format' => 'yyyy-mm-dd',
                                    'language' => 'en',
                                    'class'=>'form-controls form-control-inline input-medium default-date-picker',
                                    'size'=>"16"
                                )
                            ),true
                        )
                ),
		array('name'=>'jammasuk',
                    'value'=>function($data,$row){
                        if($data->jammasuk!='' || $data->jammasuk!=0)
                        {
                            return date("h:i:s",  strtotime($data->jammasuk));
                        }                        
                        else 
                        {
                            return '-';
                        }
                    },
                        'header'=>'Jam Masuk Lembur','filter'=>''),
                array('name'=>'jamkeluar',
                        'value'=>function($data,$row){
                        if($data->jamkeluar!='' || $data->jamkeluar!=0)
                        {
                            return date("h:i:s",  strtotime($data->jamkeluar));
                        }                        
                        else 
                        {
                            return '-';
                        }
                    },
                    'header'=>'Jam Keluar Lembur','filter'=>''),
                array('name'=>'jumlahjam','value'=>function($data,$row){
                    if($data->jumlahjam!='' || $data->jumlahjam!=0)
                    {
                        return $data->jumlahjam.' Jam';
                    }                    
                    else
                    {
                        return '0 Jam';
                    }
                },'header'=>'Jumlah Jam Lembur','filter'=>''),
                array('name'=>'status','value'=>'($data->status==1) ? "Hadir" : "Izin"','header'=>'Status','type'=>'raw',
                        'filter'=>  CHtml::dropDownList('Lemburpegawai[status]','status',
                                    array('1'=>'Hadir','2'=>'Izin'),
                                    array(
                                        'id'=>'Lemburpegawai_status',
                                        'class'=>"form-control",
                                        'prompt'=>'Status',
                                    )),
                ),
	),
)); ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#Absensipegawai_tanggal_index').datepicker();
}
");
?>

<!-- JAVASCRIPT -->
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/plugins/ladda/ladda-themeless.min.css" />
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/ladda/spin.min.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/ladda/ladda.min.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/ladda/ladda.jquery.min.js'); ?>

<script type="text/javascript">
    
    $( window ).load(function() {
        setInterval(function() {
            GetClockJamMasuk();
            GetClockJamKeluar();
        }, 1000);
        
        var l = $('#btnAbsen').ladda();
        l.click(function(){
            
            l.ladda('start');

            $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->baseurl; ?>/pegawai/absensipegawai/proses',
                    data: { tanggal : document.getElementById("tanggal_input").value, jammasuk : document.getElementById("jammasuk_input").value, jamkeluar : document.getElementById("jamkeluar_input").value, status : document.getElementById("status_input").value},
                    success: function (data) {
                        //$("#success").html(data);
                        //document.getElementById("success_content").style.display = 'block';
                        location.href="<?php echo Yii::app()->baseUrl; ?>/pegawai/absensipegawai";
                        //l.ladda('stop');
                    },
                    error: function () {
                        alert("Error occured. Please try again");
                        l.ladda('stop');
                        document.getElementById("success_content").style.display = 'none';
                    }
            });

        });
    });
    
    function GetClockJamMasuk()
    {
        var GetTime = new Date ( );
        var HoursVal = GetTime.getHours ( );
        var MinutesVal = GetTime.getMinutes ( );
        var SecondsVal = GetTime.getSeconds ( );

        // Add zero if time value less than 10
        HoursVal = ( HoursVal < 10 ? "0" : "" ) + HoursVal;
        MinutesVal = ( MinutesVal < 10 ? "0" : "" ) + MinutesVal;
        SecondsVal = ( SecondsVal < 10 ? "0" : "" ) + SecondsVal;

        document.getElementById("hours").innerHTML = HoursVal;
        document.getElementById("minutes").innerHTML = MinutesVal;
        document.getElementById("seconds").innerHTML = SecondsVal;
            
        //if(HoursVal==7 && (MinutesVal>=30 && MinutesVal<=59)) // SET WAKTU ABSENSI MASUK
        //{
            
            $('#jammasuk').popover('hide'); 
            $("#jammasuk").css("background-color", "white");
            $("#jammasuk_input").val(HoursVal + ':' + MinutesVal + ':' + SecondsVal);
        //}
        //else
        //{
            //$('#jammasuk').popover('show'); 
            //$("#jammasuk").css("background-color", "#f8ac59");
            //$("#jammasuk_input").val("");
        //}

    }
    
    function GetClockJamKeluar()
    {
        var GetTime = new Date ( );
        var HoursVal = GetTime.getHours ( );
        var MinutesVal = GetTime.getMinutes ( );
        var SecondsVal = GetTime.getSeconds ( );

        // Add zero if time value less than 10
        HoursVal = ( HoursVal < 10 ? "0" : "" ) + HoursVal;
        MinutesVal = ( MinutesVal < 10 ? "0" : "" ) + MinutesVal;
        SecondsVal = ( SecondsVal < 10 ? "0" : "" ) + SecondsVal;

        document.getElementById("hours2").innerHTML = HoursVal;
        document.getElementById("minutes2").innerHTML = MinutesVal;
        document.getElementById("seconds2").innerHTML = SecondsVal;
            
         $('#jamkeluar').popover('hide'); 
            $("#jamkeluar").css("background-color", "white");
            $("#jamkeluar_input").val(HoursVal + ':' + MinutesVal + ':' + SecondsVal);    
            
        /*    
        if(HoursVal>=17) // SET WAKTU ABSENSI KELUAR
        {
            
            $('#jamkeluar').popover('hide'); 
            $("#jamkeluar").css("background-color", "white");
            $("#jamkeluar_input").val(HoursVal + ':' + MinutesVal + ':' + SecondsVal);
        }
        else
        {
            $('#jamkeluar').popover('show'); 
            $("#jamkeluar").css("background-color", "#f8ac59");
            $("#jamkeluar_input").val("");
        }
        */

    }
</script>