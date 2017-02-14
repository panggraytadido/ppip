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
        
    <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">                           
                            <h1><img width="100" src="<?php echo Yii::app()->baseUrl; ?>/themes/gentelala/images/logo2.png"></h1>                           
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Login Sistem</h3>
                            		<!--<p>Enter your username and password to log on:</p>-->
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-key"></i>
                        		</div>
                            </div>
                            <div class="form-bottom">
			                    <?php echo CHtml::beginForm('','post',array('class'=>'login-form')); ?>
			                    	<div class="form-group">
			                    		<label for="username">Username* &nbsp; <?php echo $model->getError('username') ? '<span class="alert-danger">'.$model->getError('username').'</span>' : ''; ?></label>
			                        	<?php echo CHtml::activeTextField($model,'username', array('class'=>'form-control', 'Placeholder'=>'Username')); ?>
			                        </div>
			                        <div class="form-group">
			                        	<label for="password">Password* &nbsp; <?php echo $model->getError('password') ? '<span class="alert-danger">'.$model->getError('password').'</span>' : ''; ?></label>
			                        	<?php echo CHtml::activePasswordField($model,'password', array('class'=>'form-control', 'Placeholder'=>'Password')); ?>
			                        </div>
			                        <?php $this->widget('booster.widgets.TbButton', array(
                                                'htmlOptions'=> array('type'=>'submit'),
                                                'label'=>'LOGIN',
                                            )); ?>

                <?php echo CHtml::endForm(); ?>      
		                    </div>
                        </div>
                    </div>
                
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 social-login">
                        	<h3>Penyalur ikan pindang hygienis dan frozen fish</h3>
                        	
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>

    <!--footer start-->
<?php $this->renderPartial('//layouts/_footer'); ?>
    <!--footer end-->
<?php
$form = new CForm(array(
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
        'rememberMe'=>array(
            'type'=>'checkbox',
        )
    ),

    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Login',
        ),
    ),
), $model);
?>