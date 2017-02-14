<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
$this->breadcrumbs=array(
    UserModule::t("Login"),
);
?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<style>
    body{
        background-color: #333B30;        
        /* background-image: url("/images/1.jpg"); */
    }
</style>    
        
 <body class="gray-bg">
    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6">
                <h2 class="font-bold">Selamat Datang di Sistem Informasi CIndygroup</h2>

                <p>
                    Penyalur Ikan Pindang dan Frozen Fish
                </p>

                
            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <?php echo CHtml::beginForm('','post',array('class'=>'m-t')); ?>
			                    	<div class="form-group">
			                    		<label for="username">Username* &nbsp; <?php echo $model->getError('username') ? '<span class="alert-danger">'.$model->getError('username').'</span>' : ''; ?></label>
			                        	<?php echo CHtml::activeTextField($model,'username', array('class'=>'form-control', 'Placeholder'=>'Username')); ?>
			                        </div>
			                        <div class="form-group">
			                        	<label for="password">Password* &nbsp; <?php echo $model->getError('password') ? '<span class="alert-danger">'.$model->getError('password').'</span>' : ''; ?></label>
			                        	<?php echo CHtml::activePasswordField($model,'password', array('class'=>'form-control', 'Placeholder'=>'Password')); ?>
			                        </div>
			                        <?php $this->widget('booster.widgets.TbButton', array(
                                                'htmlOptions'=> array('type'=>'submit','class'=>"btn btn-primary block full-width m-b"),
                                                'label'=>'LOGIN',
                                            )); ?>

                <?php echo CHtml::endForm(); ?>      
                    
                    <p class="m-t">
                        <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright Cindygroup
            </div>
            <div class="col-md-6 text-right">
               <small>© 2016</small>
            </div>
        </div>
    </div>

</body>

</html>

        