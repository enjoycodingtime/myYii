<?php
/**
 * Created by PhpStorm.
 * User: kezhang
 * Date: 16/3/31
 * Time: 下午11:10
 */
$loginModel = new LoginForm();
$im = imagecreatetruecolor(640, 578);
?>
<!--<img src="images/beijing.jpg" alt="">-->
<h1>hello , my firsrt weixin controller</h1>
<div class="form">
<?php echo CHtml::errorSummary($loginModel) ;?>
<?php echo CHtml::beginForm(); ?>
 <div class="row">
     <?php echo CHtml::activeLabel($loginModel,'username'); ?>
     <?php echo CHtml::activeTextField($loginModel,'username'); ?>
 </div>
<div class="row">
    <?php echo CHtml::activeLabel($loginModel,'password') ;?>
    <?php echo CHtml::activePasswordField($loginModel,'password') ;?>
</div>
<div class="row rememberMe">
    <?php echo CHtml::activeCheckBox($loginModel,'rememberMe');?>
    <?php echo CHtml::activeLabel($loginModel,'rememberMe') ;?>
</div>
<div class="row submit">
    <?php echo CHtml::submitButton("Login"); ?>
</div>
<?php echo CHtml::endForm();?>
</div>
