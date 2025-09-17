
<?php if($config_error): ?>
    <div class='alert alert-danger'>
        <?php echo e(trans('langErrorConfig')); ?>

    </div>
<?php else: ?>
    <div class='alert alert-success'>
        <i class='fa-solid fa-circle-check fa-lg'></i>
        <span>
            <?php echo e(trans('langInstallSuccess')); ?>

        </span>
    </div>
    <br>

    <form action='../'>
        <input aria-label="<?php echo e(trans('langEnterFirstTime')); ?>" class='btn btn-sm btn-primary submitAdminBtn w-100 text-white' type='submit' value='<?php echo e(trans('langEnterFirstTime')); ?>'>
    </form>

    <div class="help-block pt-2">
        <?php echo trans('langProtect'); ?>

    </div>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/install/step_8.blade.php ENDPATH**/ ?>