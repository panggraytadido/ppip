<?php
$this->pageTitle = "Laporan - Slip Gaji Karyawan Gudang";

$this->breadcrumbs=array(
	'Keuangan',
        'Slip Gaji Karyawan Gudang',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-6">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b>Slip Gaji Karyawan Gudang</b>
                    </div>
					
					 <div class="pull-right">
                        <?php echo CHtml::link('<li class="fa fa-mail-reply"></li> Kembali',array('index'), array('class'=>'btn btn-default btn-xs')); ?>
                    </div>
                </div>                
                <div class="ibox-content">
                    <form class="form-horizontal" id="form1">
                    <input type="hidden" name="bulan" id="bulan" value="<?php echo $bulan ?>">
                    <input type="hidden" name="tahun" id="tahun" value="<?php echo $tahun ?>">
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Tanggal</label>
                        <div class="col-sm-4">
                            <?php
                                echo "<b>".date('d-m-Y')."</b>";
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">NIP</label>
                        <div class="col-sm-4">
                            <?php echo Karyawan::model()->findByPk($karyawanid)->nik; ?>
                            <input type="hidden" name="karyawanid" id="karyawanid" value="<?php echo $karyawanid; ?>" >
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Nama</label>
                        <div class="col-sm-4">
                            <?php echo Karyawan::model()->findByPk($karyawanid)->nama; ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Jabatan</label>
                        <div class="col-sm-4">
                            <?php 
                                    $jabatanid = Karyawan::model()->findByPk($karyawanid)->jabatanid; 
                                    echo Jabatan::model()->findByPk($jabatanid)->nama;
                             ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Jumlah Gaji</label>
                        <div class="col-sm-4">
                            <?php 
                                if($totalGaji!="")
                                {
                                    echo number_format($totalGaji);
                                    echo '<input type="hidden" name="totalgaji" value="'.$totalGaji.'">';
                                }   
                                else 
                                {
                                    echo '-';
                                    echo '<input type="hidden" name="totalgaji" value="0">';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Jumlah Kasbon</label>
                        <div class="col-sm-4">
                            <?php 
                                if($totalKasbon!="")
                                {
                                    echo number_format($totalKasbon);
                                    echo '<input type="hidden" name="totalkasbon" value="'.$totalKasbon.'">';
                                }                                
                                else 
                                {
                                    echo '-';
                                    echo '<input type="hidden" name="totalkasbon" value="0">';
                                }
                            ?>
                        </div>
                    </div>                            
                    </form>    
                </div>
            </div>	              
        </div>
        <div class="col-lg-6">                     
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="pull-left">
                        <b></b>
                    </div>
                </div>                
                <div class="ibox-content">
                    <form class="form-horizontal" id="form2">
                          <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Bayar Kasbon</label>
                        <div class="col-sm-4">
                            <?php 
                                if($totalBayarKasbon!='')
                                {
                                    echo number_format($totalBayarKasbon);
                                    echo '<input type="hidden" name="bayarkasbon" value="'.$totalBayarKasbon.'">';
                                }
                                else
                                {
                                    echo '-';
                                    echo '<input type="hidden" name="bayarkasbon" value="0">';
                                }    
                                
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Sisa Kasbon</label>
                        <div class="col-sm-4">
                            <?php
                                $sisaKasbon = $totalKasbon-$totalBayarKasbon;
                                echo number_format($sisaKasbon);
                                echo '<input type="hidden" name="sisakasbon" value="'.$sisaKasbon.'">';
                            ?>
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Uang Makan</label>
                        <div class="col-sm-4">
                            
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Transport</label>
                        <div class="col-sm-4">
                            
                        </div>
                    </div>
                    -->
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Insentive</label>
                        <div class="col-sm-4">
                            <input type="text" name="insentive" class="form-control" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-5 control-label required" for="Slipgajikaryawan_jeniskaryawan">Bonus</label>
                        <div class="col-sm-4">
                            <input type="text" name="bonus" class="form-control" value="0">
                        </div>
                    </div>                  
                    </form> 
                </div>
            </div>	              
        </div>
    </div>
    
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox-content">
                <button class="btn btn-success" onclick="print();">Cetak</button> &nbsp;<button class="btn btn-default">Batal</button>
            </div>
        </div>    
    </div>
</div>

<script>
    function print()
    {      
        var data = $('#form1,#form2').serialize();

        $.ajax({
                dataType: 'json',
                type: 'POST',
                url: '<?php echo Yii::app()->baseurl; ?>/keuangan/slipgajikaryawangudang/checkprint',
                data: data,
                success: function (data) {                                                                          
                 window.open("<?php echo Yii::app()->baseurl ?>/keuangan/slipgajikaryawangudang/print/karyawanid/"+data.karyawanid+"/tahun/"+data.tahun+"/bulan/"+data.bulan);
                   location.href= "<?php echo Yii::app()->baseurl ?>/keuangan/slipgajikaryawan";
                },
                error: function () {
                    alert("Error occured. Please try again");
                }
        });
    }
</script>    