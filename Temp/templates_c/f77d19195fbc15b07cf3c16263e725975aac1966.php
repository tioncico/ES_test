<html>
<head>
    <title>应用程序名称 - <?php echo $__env->yieldContent('title'); ?></title>
</head>
<body>
<?php $__env->startSection('sidebar'); ?>
    这是主要的侧边栏。
<?php echo $__env->yieldSection(); ?>

<div class="container">
    <?php echo $__env->yieldContent('content'); ?>
</div>
</body>
</html>