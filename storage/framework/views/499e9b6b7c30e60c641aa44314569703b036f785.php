<?php echo e(Form::open(['url' => '#', 'method' => 'delete', 'id' => 'deleteForm'])); ?>

    <?php if(isset($extraFields)): ?>
      <?php $__currentLoopData = $extraFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
           <input type="hidden" name="<?php echo e($key); ?>" value="<?php echo e($value); ?>">
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php echo e(Form::close()); ?><?php /**PATH /Users/mahendra/Documents/projects/epicFit/epicfitlaravelv6/resources/views/includes/partials/delete_form.blade.php ENDPATH**/ ?>