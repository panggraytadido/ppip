<?php
$this->pageTitle = "Pegawai";

$this->breadcrumbs=array(
	'',
);

?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row m-b-lg m-t-lg">
        <div class="col-md-6">

            <div class="profile-image">
                <img src="<?php echo Yii::app()->baseurl; ?>/foto/profile_small.jpg" class="img-circle circle-border m-b-md" alt="profile">
            </div>
            <div class="profile-info">
                <div class="">
                    <div>
                        <h2 class="no-margins">
                            <?php echo $model->nama; ?>
                        </h2>
                        <h4><?php echo $model->nik; ?></h4>
                        <small>
                            <?php echo $model->alamat; ?>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <table class="table small m-b-xs">
                <tbody>
                <tr>
                    <td>
                        <strong>Jabatan</strong> <?php echo $model->jabatan->nama; ?>
                    </td>

                </tr>
                <tr>
                    <td>
                        <strong>Status Karyawan</strong> <?php echo $model->jeniskaryawan->nama; ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <strong>Gender</strong> <?php echo ($model->jeniskelamin==1) ? "Laki-laki" : 'Perempuan'; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
<!--            <small>Sales in last 24h</small>
            <h2 class="no-margins">206 480</h2>
            <div id="sparkline1"><canvas height="50" width="247" style="display: inline-block; width: 247px; height: 50px; vertical-align: top;"></canvas></div>-->
        </div>

    </div>
</div>