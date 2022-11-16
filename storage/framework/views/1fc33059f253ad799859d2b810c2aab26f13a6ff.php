<!DOCTYPE html>
<!-- Template Name: Clip-Two - Responsive Admin Template build with Twitter Bootstrap 3.x | Author: ClipTheme -->
<!--[if IE 8]><html class="ie8" lang="en"><![endif]-->
<!--[if IE 9]><html class="ie9" lang="en"><![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<!-- start: HEAD -->
<head>
    <title><?php echo $__env->yieldContent('title', app_name()); ?></title>
    <!-- start: META -->
    <!--[if IE]><meta http-equiv='X-UA-Compatible' content="IE=edge,IE=9,IE=8,chrome=1" /><![endif]-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta content="" name="description" />
    <meta content="" name="author" />

    <meta name="_token" content="<?php echo e(csrf_token()); ?>" />
    <!-- end: META -->
    <!-- start: GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,400,500,600,700" rel="stylesheet">
    <!-- end: GOOGLE FONTS -->

    <!-- Styles -->
    <?php echo $__env->yieldContent('before-styles-end'); ?>
    <!-- start: MAIN CSS -->
    <?php echo Html::style('result/plugins/bootstrap/css/bootstrap.min.css'); ?>

    <?php echo Html::style('result/plugins/fontawesome/css/font-awesome.min.css'); ?>

    <?php echo Html::style('result/plugins/fonts/style.css'); ?>

    <?php echo Html::style('result/plugins/themify-icons/themify-icons.min.css'); ?>

    <?php echo Html::style('result/plugins/animate.css/animate.min.css', ['media' => 'screen']); ?>

    <?php echo Html::style('result/plugins/perfect-scrollbar/perfect-scrollbar.min.css', ['media' => 'screen']); ?>

    <?php echo Html::style('result/plugins/switchery/switchery.min.css', ['media' => 'screen']); ?>

    <!-- end: MAIN CSS -->

    <!-- start: CLIP-TWO CSS -->
    <?php echo Html::style('result/css/styles-orange.css?v='.time()); ?>

    <?php echo Html::style('result/plugins/clip-two/main-navigation.css'); ?>

    <?php echo Html::style('result/css/plugins.css'); ?>

    <?php echo Html::style('result/css/custom-style.css?v='.time()); ?>


    <?php echo Html::style('result/plugins/themes/theme-orange.css'); ?>

    <!-- end: CLIP-TWO CSS -->
    <!-- start: CSS REQUIRED FOR THIS PAGE ONLY -->
    <?php echo $__env->yieldContent('after-styles-end'); ?>

    <!-- end: CSS REQUIRED FOR THIS PAGE ONLY -->
</head>
<!-- end: HEAD -->
<body class="login">

    <?php echo $__env->yieldContent('content'); ?>

<!-- start: MAIN JAVASCRIPTS -->
<?php echo Html::script('result/plugins/jquery/jquery.min.js'); ?>

<?php echo Html::script('result/plugins/bootstrap/js/bootstrap.min.js'); ?>

<?php echo Html::script('result/plugins/modernizr/modernizr.js'); ?>

<?php echo Html::script('result/plugins/jquery-cookie/jquery.cookie.js'); ?>

<?php echo Html::script('result/plugins/perfect-scrollbar/perfect-scrollbar.min.js'); ?>

<?php echo Html::script('result/plugins/switchery/switchery.min.js'); ?>

<!-- end: MAIN JAVASCRIPTS -->

<!-- start: CLIP-TWO JAVASCRIPTS -->
<?php echo Html::script('result/js/main.js?v='.time()); ?>

<!-- end: CLIP-TWO JAVASCRIPTS -->

<!-- start: JavaScript required for this page -->
<?php echo Html::script('result/js/index.js?v='.time()); ?>

<!-- end: JavaScript required for this page -->

<?php echo $__env->yieldContent('scripts'); ?>
<!-- end: JavaScript Event Handlers for this page -->
<!-- end: CLIP-TWO JAVASCRIPTS -->
</body>
<!-- end: FOOTER -->
</html><?php /**PATH /Users/mahendra/Documents/projects/epicFit/epicfitlaravelv6/resources/views/Result/masters/login.blade.php ENDPATH**/ ?>