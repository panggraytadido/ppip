<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
$this->breadcrumbs=array(
    UserModule::t("Login"),
);
?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
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
  <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/font-awesome/css/font-awesome.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/animate.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl;?>/css/style.css" />
<style>
    .submit{
       background-color: #2f4050;
        color:#FFF;
    }
    body {

    background-color: #2f4050;
    -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
}
</style> 

</head>

<body class="blue-bg ">
 <div class="loginColumns " ><div style="background-color: #0e9aef; padding-bottom: 30px;border-radius: 5px;" >
   
<div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <center> <img alt="image" class="img-circle animated rotateIn" src="<?php echo Yii::app()->request->baseUrl; ?>/img/logoppip.png" /></center>

            </div>
             <h3>Perkumpulan Pengolahan Ikan Pindang</h3>
            <p>Login - ERP Sistem PPIP
                <!--Continually expanded and constantly improved Inspinia Admin Them (IN+)-->
            </p>
           
            <div class="ibox-content">
           <?php echo CHtml::beginForm('','post',array('class'=>'m-t')); ?>
					<div class="form-group">
									
			                    		<!-- <label for="username">Username* &nbsp; <?php //echo $model->getError('username') ? '<span class="alert-danger">'.$model->getError('username').'</span>' : ''; ?></label>-->
			                        	
			                        </div>
			                    	<div class="form-group">
									
			                    		<!-- <label for="username">Username* &nbsp; <?php //echo $model->getError('username') ? '<span class="alert-danger">'.$model->getError('username').'</span>' : ''; ?></label>-->
			                        	<?php echo CHtml::activeTextField($model,'username', array('class'=>'form-control', 'Placeholder'=>'Username')); ?>
			                        </div>
			                        <div class="form-group">
			                        	<!--<label for="password">Password* &nbsp; <?php //echo $model->getError('password') ? '<span class="alert-danger">'.$model->getError('password').'</span>' : ''; ?></label>-->
			                        	<?php echo CHtml::activePasswordField($model,'password', array('class'=>'form-control', 'Placeholder'=>'Password')); ?>
			                        </div>
			                        <?php 
                                                
                                                $this->widget('booster.widgets.TbButton', array(
                                                'htmlOptions'=> array('type'=>'submit','class'=>"btn btn-primary block full-width m-b submit animated flash"),
                                                'label'=>'LOGIN',
                                                    ));     
                                                 
                                                ?>

                <?php echo CHtml::endForm(); ?>   
                 </div>   
                 <div class="row animated bounceInRight">
        
        <hr style="margin-top: 20px;
  margin-bottom: 0px;
  border: 0;
  border-top: 1px solid white;"></div>
            <p class="m-t"> <small>Copyright PPIP &copy; 2016-2017</small> </p>
            
        </div>
    </div></div></div>
    
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery-2.1.1.js'); ?>
  <?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap.min.js'); ?>
    

</body>

</html>
 