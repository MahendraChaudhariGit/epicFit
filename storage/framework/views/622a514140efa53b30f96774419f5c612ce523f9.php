<?php if($errors->any()): ?>
    <div class="alert alert-danger">
        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo $error; ?><br/>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
<?php elseif(Session::get('flash_success')): ?>
    <div class="alert alert-success">
        <?php if(is_array(json_decode(Session::get('flash_success'), true))): ?>
            <?php echo implode('', Session::get('flash_success')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo Session::get('flash_success'); ?>

        <?php endif; ?>
    </div>
<?php elseif(Session::get('flash_warning')): ?>
    <div class="alert alert-warning">
        <?php if(is_array(json_decode(Session::get('flash_warning'), true))): ?>
            <?php echo implode('', Session::get('flash_warning')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo Session::get('flash_warning'); ?>

        <?php endif; ?>
    </div>
<?php elseif(Session::get('flash_info')): ?>
    <div class="alert alert-info">
        <?php if(is_array(json_decode(Session::get('flash_info'), true))): ?>
            <?php echo implode('', Session::get('flash_info')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo Session::get('flash_info'); ?>

        <?php endif; ?>
    </div>
<?php elseif(Session::get('flash_danger')): ?>
    <div class="alert alert-danger">
        <?php if(is_array(json_decode(Session::get('flash_danger'), true))): ?>
            <?php echo implode('', Session::get('flash_danger')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo Session::get('flash_danger'); ?>

        <?php endif; ?>
    </div>
<?php elseif(Session::get('flash_message')): ?>
    <div class="alert alert-info">
        <?php if(is_array(json_decode(Session::get('flash_message'), true))): ?>
            <?php echo implode('', Session::get('flash_message')->all(':message<br/>')); ?>

        <?php else: ?>
            <?php echo Session::get('flash_message'); ?>

        <?php endif; ?>
    </div>
<?php endif; ?><?php /**PATH /Users/mahendra/Documents/projects/epicFit/epicfitlaravelv6/resources/views/Result/partials/messages.blade.php ENDPATH**/ ?>