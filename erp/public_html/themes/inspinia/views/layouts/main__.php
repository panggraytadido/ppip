<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title><?php echo CHtml::encode($this->pageTitle); ?></title>

  <!-- Bootstrap core CSS -->

  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/font-awesome/css/font-awesome.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/animate.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/style.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/custom_form_yiibooster.css" />
  
  <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery-2.1.1.js'); ?>
  <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery-ui-1.10.4.min.js'); ?>
  <?php //Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js'); ?>  
	
  <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/inspinia.js'); ?>
  <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/metisMenu/jquery.metisMenu.js'); ?>
	<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/datapicker/bootstrap-datepicker.js'); ?>
  <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/plugins/slimscroll/jquery.slimscroll.min.js'); ?>
  
  
 
  <style>
      .breadcrumb 
      {
          background-color: #d9edf7;
      }      
  </style>
  <script type="text/javascript">
    <?php date_default_timezone_set('Asia/Jakarta'); ?>
    var serverTime = new Date(<?php print date('Y, m, d, H, i, s, 0'); ?>);
    var clientTime = new Date();
    var Diff = serverTime.getTime() - clientTime.getTime();    
    function displayServerTime(){
        var clientTime = new Date();
        var time = new Date(clientTime.getTime() + Diff);
        var sh = time.getHours().toString();
        var sm = time.getMinutes().toString();
        var ss = time.getSeconds().toString();
        document.getElementById("clock").innerHTML = (sh.length==1?"0"+sh:sh) + ":" + (sm.length==1?"0"+sm:sm) + ":" + (ss.length==1?"0"+ss:ss);
    }
</script>
</head>
<?php //print("<pre>".print_r(Yii::app()->session['menus'],true)."</pre>"); ?>
<body onLoad="setInterval('displayServerTime()', 1000);">
<div id="wrapper">
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo Karyawan::model()->findByPk(1)->nama; ?></strong>
                                    <?php $lokasi = Lokasipenyimpananbarang::model()->findByPk(Yii::app()->session['lokasiid'])->nama; ?>    
                                    </span> <span class="text-muted text-xs block"><?php echo "Divisi ".Divisi::model()->findByPk(Yii::app()->session['divisiid'])->nama." <b>(".$lokasi.")</b>"; ?><b class="caret"></b></span> </span> </a>
                            <ul class="dropdown-menu animated fadeInRight m-t-xs">
                                <li><a href="#">Logout</a></li>
                            </ul>
                    </div>
                    <div class="logo-element">
                        IN+
                    </div>
                </li>
               <?php                             
                        
                        echo Yii::app()->Menus->displayMenu();                               
                  ?>
            </ul>

        </div>
    </nav>

    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom">		
            <nav class="navbar navbar-static-top white-bg" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                    <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>                    
                </div>
                <ul class="nav navbar-top-links navbar-right">
                    <li><?php
                            $array_hr= array(1=>"Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu");
                            $hr = $array_hr[date('N')];
                            $tgl= date('j');

                            $array_bln = array(1=>"Januari","Februari","Maret", "April", "Mei","Juni","Juli","Agustus","September","Oktober", "November","Desember");
                            $bln = $array_bln[date('n')];
                             $thn = date('Y');		
                            echo $hr . ", " . $tgl . " " . $bln . " " . $thn . " " 
                        ?></li>
                    <li><div id="clock" class="jamdini"><?php echo date('H:i:s'); ?></div></li>    
                    <li>
                        <?php echo CHtml::link('Logout',array(Yii::app()->baseUrl.'../../site/logout')); ?>
                    </li>
                </ul>

            </nav>
        </div>
		
				
		    <div class="row wrapper border-bottom white-bg page-heading">
                        <br>
                    <div class="col-lg-12">                       
                        <ul class="breadcrumb">                         
                            <li class="active">
                                <?php if(isset($this->breadcrumbs)):?>									
									<?php $this->renderPartial('//layouts/_breadcrumbs'); ?>									
								<?php endif?>
                            </li>
                        </ul>
                    </div>                   
            </div>
          
		
		
        <?php echo $content; ?>
		
		
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>

    </div>
</div> 
</body>

</html>
