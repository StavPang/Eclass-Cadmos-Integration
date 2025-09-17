<?php if(Session::has('message')): ?>
    <div class='col-12 all-alerts'>
        <div class="alert <?php echo e(Session::get('alert-class', 'alert-info')); ?> alert-dismissible fade show" role="alert">
            <?php
                $alert_type = '';
                if(Session::get('alert-class', 'alert-info') == 'alert-success'){
                    $alert_type = "<i class='fa-solid fa-circle-check fa-lg'></i>";
                }elseif(Session::get('alert-class', 'alert-info') == 'alert-info'){
                    $alert_type = "<i class='fa-solid fa-circle-info fa-lg'></i>";
                }elseif(Session::get('alert-class', 'alert-info') == 'alert-warning'){
                    $alert_type = "<i class='fa-solid fa-triangle-exclamation fa-lg'></i>";
                }else{
                    $alert_type = "<i class='fa-solid fa-circle-xmark fa-lg'></i>";
                }
            ?>

            <?php if(is_array(Session::get('message'))): ?>
                <?php $messageArray = array(); $messageArray = Session::get('message'); ?>
                <?php echo $alert_type; ?><span>
                <?php $__currentLoopData = $messageArray; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $message; ?>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></span>
            <?php else: ?>
                <?php echo $alert_type; ?><span><?php echo Session::get('message'); ?></span>
            <?php endif; ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo e(trans('langClose')); ?>"></button>
        </div>
    </div>
<?php endif; ?>

<?php if(Session::hasMessages()): ?>
  <div class='col-12 all-alerts'>
    <?php $__currentLoopData = Session::getMessages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert_class => $alert_messages): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="alert <?php echo e($alert_class); ?> alert-dismissible fade show" role="alert">
        <i class='fa-solid <?php switch($alert_class):
          case ('alert-success'): ?> fa-circle-check <?php break; ?>
          <?php case ('alert-info'): ?> fa-circle-info <?php break; ?>
          <?php case ('alert-warning'): ?> fa-triangle-exclamation <?php break; ?>
          <?php default: ?> fa-circle-xmark <?php endswitch; ?> fa-circle-check fa-lg'></i>
        <?php if(count($alert_messages) > 1): ?>
          <ul>
            <?php $__currentLoopData = $alert_messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $alert_message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <li><?php echo $alert_message; ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </ul>
        <?php else: ?>
          <?php echo $alert_messages[0]; ?>

        <?php endif; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="<?php echo e(trans('langClose')); ?>"></button>
      </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/layouts/partials/show_alert.blade.php ENDPATH**/ ?>