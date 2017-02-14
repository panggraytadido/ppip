<!DOCTYPE html>
<html lang="id">
    <head>     
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
      <title><?php echo CHtml::encode(Yii::app()->name); ?></title>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="author" content="sintaxindo-indonesia" />
      <link rel="icon" type="image/png" href="/images/cargloss.png"/>
      <?php //Yii::app()->clientScript->registerCoreScript('bootstrap'); ?>

    </head>
    <body>
        <!-- MAIN CONTENT -->        	
              <?php echo $content;?>
        <!-- END MAIN CONTENT -->

    </body>
</html>
