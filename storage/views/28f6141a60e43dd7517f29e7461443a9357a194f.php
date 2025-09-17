

<?php $__env->startSection('content'); ?>

<div class="col-12 main-section">
    <div class='<?php echo e($container); ?> main-container'>
        <div class="row m-auto">

            <div class='col-12'>
                <h1><?php echo e(trans('langRegistration')); ?></h1>
            </div>

            <?php echo $__env->make('layouts.partials.show_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 

            <?php if($user_registration): ?>
                <div class='col-12 mt-4'>
                    <div class='row row-cols-1 row-cols-lg-2 m-auto g-4'>
                        <div class='col-lg-6 col-12 ps-0'>
                            <?php if($eclass_stud_reg != FALSE or $alt_auth_stud_reg != FALSE): ?>
                                <div class="col-12">
                                    <ul class="list-group list-group-flush">
                                        <?php if($eclass_stud_reg == 2): ?> <!--  allow student registration via eclass -->
                                            <li class="list-group-item element"><a class='TextBold' href='newuser.php<?php echo e($provider); ?><?php echo e($provider_user_data); ?>'><?php echo e(trans('langUserAccountInfo2')); ?></a></li>
                                        <?php elseif($eclass_stud_reg == 1): ?> <!-- allow student registration via request -->
                                            <li class="list-group-item element"><a class='TextBold' href='newuser.php<?php echo e($provider); ?><?php echo e($provider_user_data); ?>'><?php echo e(trans('langUserAccountInfo1')); ?></a></li>
                                        <?php endif; ?>
                                        <?php if(count($auth) > 1 and $alt_auth_stud_reg != FALSE): ?> <!-- allow user registration via alt auth methods -->
                                            <?php $__currentLoopData = $auth; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php if($v != 1): ?>  <!--  bypass the eclass auth method -->
                                                    <?php if($v < 8): ?>
                                                        <li class="list-group-item element"><a class='TextBold' href='altnewuser.php?auth=<?php echo e($v); ?>'><?php echo e(get_auth_info($v)); ?></a></li>
                                                    <?php else: ?>
                                                        <?php if($eclass_stud_reg == 1): ?>
                                                            <li class="list-group-item element"><a class='TextBold' href='newuser.php?auth=<?php echo e($v); ?>'><?php echo e(get_auth_info($v)); ?></a></li>
                                                        <?php else: ?>
                                                            <li class="list-group-item element"><a class='TextBold' href='newuser.php?auth=<?php echo e($v); ?>'><?php echo e(get_auth_info($v)); ?></a></li>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            <?php else: ?>
                                <div class='col-12'>
                                    <p class='TextRegular'><?php echo e(trans('langStudentCannotRegister')); ?></p>
                                </div>
                            <?php endif; ?>
                            <?php if($registration_info): ?>
                                <div class='alert alert-info'><i class='fa-solid fa-circle-info fa-lg'></i><span><?php echo $registration_info; ?></span></div>
                            <?php endif; ?>
                        </div>
                        <div class='col-lg-6 col-12 d-none d-lg-block'>
                            <img class='form-image-modules form-image-registration' src='<?php echo get_registration_form_image(); ?>' alt='<?php echo e(trans('langRegistration')); ?>'>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class='col-12 mt-4'>
                    <div class='alert alert-info'><i class='fa-solid fa-circle-info fa-lg'></i><span><?php echo e(trans('langCannotRegister')); ?></span></div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/modules/auth/registration.blade.php ENDPATH**/ ?>