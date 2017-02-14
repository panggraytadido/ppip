<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="row">
            <div class="col-sm-12">
                <div class="text-center error-box">
                    <h1 class="error-text tada animated"><i class="fa fa-times-circle text-danger error-icon-shadow"></i> <?php echo isset($code) ? $code : 'Error code';?></h1>
                    <h2 class="font-xl"><strong>Oooops</strong></h2>
                    <br>
                    <p class="lead semi-bold">
                    <strong>Error..</strong><br><br>
                    <small><?php echo isset($message) ? $message : 'Error'?></small>
                    </p>
                    <ul class="error-search text-left font-md">
                    <li><a href="/main"><small>Kembali</small></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>