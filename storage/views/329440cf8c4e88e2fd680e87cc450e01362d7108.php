

<?php $__env->startSection('content'); ?>

    <div class="col-12 main-section">
        <div class='<?php echo e($container); ?> main-container'>

            <?php echo $__env->make('layouts.partials.show_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <?php if(Session::has('login_error')): ?>
                <div class='modal show' id='warning-modal' tabindex='-1'>
                    <div class='modal-dialog modal-dialog-centered'>
                        <div class='modal-content border-0 p-0'>
                            <div class='modal-header d-flex justify-content-between align-items-center'>
                                <div class='modal-title'><?php echo e(trans('langError')); ?></div>
                                <button aria-label="<?php echo e(trans('langClose')); ?>" type='button' class='close' data-bs-dismiss='modal'></button>
                            </div>
                            <div class='modal-body'>
                                <div class='alert alert-warning'>
                                    <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                    <span><?php echo Session::get('login_error'); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $__env->startPush('bottom_scripts'); ?>
                    <script>
                        var warningModal = new bootstrap.Modal(document.getElementById('warning-modal'), {});
                        warningModal.toggle();
                        document.body.addEventListener('keydown', function(e) {
                          if (e.key == "Escape") {
                            warningModal.hide();
                          }
                        });
                    </script>
                <?php $__env->stopPush(); ?>
            <?php endif; ?>

            <div class='row m-auto'>
                <h1><?php echo e(trans('langUserLogin')); ?></h1>
                <div class='padding-default mt-4'>
                    <div class='row row-cols-1 <?php if(count($authLink) > 0): ?> row-cols-lg-2 <?php else: ?> row-cols-lg-1 <?php endif; ?> g-4'>
                        <div class='col <?php echo $Position; ?>'>
                            <?php if($auth_enabled_method == 1): ?>
                                <?php if(count($authLink) > 0): ?>
                                    <div class='card form-homepage-login border-card h-100 px-lg-4 py-lg-3 p-3'>
                                        <div class='card-body d-flex justify-content-center align-items-center p-1 p-md-2'>
                                            <div class='w-100 h-100'>
                                                <div class='col-12 container-pages d-flex align-items-center h-100'>

                                                    <?php $__currentLoopData = $authLink; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $authInfo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($loop->first): ?>
                                                            <div class='col-12 page slide-page h-100'>
                                                        <?php else: ?>
                                                            <?php if($primary_method >= 2 && count($authLink) == 2): ?>
                                                                <?php break; ?>
                                                            <?php endif; ?>
                                                            <div class='col-12 page next-page-<?php echo e($loop->iteration-1); ?> h-100'>
                                                        <?php endif; ?>

                                                        <div class='row h-100'>
                                                            <div class='col-12 align-self-start'>
                                                                <div class='d-flex justify-content-between align-items-center flex-wrap gap-2'>
                                                                    <h2 class='mb-3'>
                                                                        <?php echo e($authInfo[2]); ?>

                                                                    </h2>
                                                                    <?php if(!empty($authInfo[3])): ?> 
                                                                        <a href='#' class='text-decoration-underline mb-3' data-bs-toggle='modal' data-bs-target='#authInstruction<?php echo e($loop->index); ?>'>
                                                                            <?php echo e(trans('langInstructionsAuth')); ?>

                                                                        </a>
                                                                        <div class='modal fade' id='authInstruction<?php echo e($loop->index); ?>' tabindex='-1' role='dialog' aria-labelledby='authInstructionLabel' aria-hidden='true'>
                                                                            <div class='modal-dialog'>
                                                                                <div class='modal-content'>
                                                                                    <div class='modal-header'>
                                                                                        <div class='modal-title' id='authInstructionLabel'><?php echo e(trans('langInstructionsAuth')); ?></div>
                                                                                        <button type='button' class='close' data-bs-dismiss='modal' aria-label="<?php echo e(trans('langClose')); ?>"></button>
                                                                                    </div>
                                                                                    <div class='modal-body'>
                                                                                        <div class='col-12'>
                                                                                            <div class='alert alert-info'>
                                                                                                <i class='fa-solid fa-circle-info fa-lg'></i>
                                                                                                <span><?php echo e($authInfo[3]); ?></span>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                            <div class='col-12 align-self-center' <?php if($primary_method >= 3 && count($authLink) == 2): ?> style="height: 100px; display: flex; align-items:center; justify-content: center;" <?php endif; ?>>
                                                                <div class='text-center w-100'><?php echo $authInfo[1]; ?></div>
                                                            </div>

                                                            <div class='col-12 align-self-end'>
                                                                <?php if(count($authLink) == 2): ?>
                                                                    <div id='or' class='ms-auto me-auto mb-2' >
                                                                        <?php echo e(trans('langOr')); ?>

                                                                    </div>
                                                                    <div class='d-flex justify-content-between align-items-center flex-wrap gap-2'>
                                                                        <h2 class='mb-3'>
                                                                            <?php echo e($authLink[1][2]); ?>

                                                                        </h2>
                                                                        <?php if(!empty($authLink[1][3])): ?> 
                                                                            <a href='#' class='text-decoration-underline mb-3' data-bs-toggle='modal' data-bs-target='#authInstruction<?php echo e($loop->index+1); ?>'>
                                                                                <?php echo e(trans('langInstructionsAuth')); ?>

                                                                            </a>
                                                                            <div class='modal fade' id='authInstruction<?php echo e($loop->index+1); ?>' tabindex='-1' role='dialog' aria-labelledby='authInstructionLabel' aria-hidden='true'>
                                                                                <div class='modal-dialog'>
                                                                                    <div class='modal-content'>
                                                                                        <div class='modal-header'>
                                                                                            <div class='modal-title' id='authInstructionLabel'><?php echo e(trans('langInstructionsAuth')); ?></div>
                                                                                            <button type='button' class='close' data-bs-dismiss='modal' aria-label="<?php echo e(trans('langClose')); ?>"></button>
                                                                                        </div>
                                                                                        <div class='modal-body'>
                                                                                            <div class='col-12'>
                                                                                                <div class='alert alert-info'>
                                                                                                    <i class='fa-solid fa-circle-info fa-lg'></i>
                                                                                                    <span><?php echo e($authLink[1][3]); ?></span>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                        <?php echo $authLink[1][1]; ?>

                                                                    </div>
                                                                <?php elseif(count($authLink) > 2): ?>
                                                                    <div id='or' class='ms-auto me-auto mb-2'>
                                                                        <?php echo e(trans('langOr')); ?>

                                                                    </div>
                                                                    <div class='d-flex justify-content-md-between justify-content-center align-items-center gap-3 flex-wrap'>
                                                                        <?php if($loop->first): ?>
                                                                            <button class='btn submitAdminBtn firstNext next'>
                                                                                <?php echo $authLink[1][2]; ?>

                                                                            </button>
                                                                            <button class='btn submitAdminBtn next-1 next'>
                                                                                <?php echo $authLink[2][2]; ?>

                                                                            </button>
                                                                        <?php elseif($loop->index == 1): ?>
                                                                            <button class='btn submitAdminBtn prev-<?php echo e($loop->index); ?> next'>
                                                                                <?php echo $authLink[$loop->index-1][2]; ?>

                                                                            </button>
                                                                            <button class='btn submitAdminBtn next-<?php echo e($loop->index+1); ?> next'>
                                                                                <?php echo $authLink[$loop->index+1][2]; ?>

                                                                            </button>
                                                                        <?php elseif($loop->index == 2): ?>
                                                                            <button class='btn submitAdminBtn prev-<?php echo e($loop->index); ?> next'>
                                                                                <?php echo $authLink[$loop->index-1][2]; ?>

                                                                            </button>
                                                                            <button class='btn submitAdminBtn next-<?php echo e($loop->index+1); ?> next'>
                                                                                <?php echo $authLink[$loop->index-2][2]; ?>

                                                                            </button>
                                                                        <?php endif; ?>
                                                                        <?php if(count($authLink) > 3): ?>
                                                                            <div class='col-12 d-flex justify-content-center align-items-center'>
                                                                                <div class='modal fade' id='LoginFormAnotherOption-<?php echo e($loop->index); ?>' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='LoginFormAnotherOptionLabel-<?php echo e($loop->index); ?>' aria-hidden='true'>
                                                                                    <div class='modal-dialog'>
                                                                                        <div class='modal-content'>
                                                                                            <div class='modal-header'>
                                                                                                <div class='modal-title' id='LoginFormAnotherOptionLabel-<?php echo e($loop->index); ?>'>
                                                                                                    <?php echo e($authLink[count($authLink)-1][2]); ?>

                                                                                                </div>
                                                                                                <button type='button' class='close' data-bs-dismiss='modal' aria-label="<?php echo e(trans('langClose')); ?>"></button>
                                                                                            </div>
                                                                                            <div class='modal-body d-flex justify-content-center align-items-center'>
                                                                                                <div>
                                                                                                    <?php echo e($authLink[count($authLink)-1][1]); ?>

                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>

                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class='col-12 mt-3'>
                                        <div class='alert alert-danger'>
                                            <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                            <span><?php echo e(trans('langAllAuthMethodsAreDisabled')); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <div class='card cardLogin h-100 p-3'>
                                    <div class='card-body py-1'>
                                        <h2><?php echo e(trans('langUserLogin')); ?></h2>
                                        <div class='col-12 mt-3'>
                                            <div class='alert alert-danger'>
                                                <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                                <span><?php echo e(trans('langAllAuthMethodsAreDisabled')); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if(count($authLink) > 0): ?>
                            <div class='col card-login-img d-none <?php echo $PositionForm; ?>' role="img" aria-label="<?php echo e(trans('langLoginImg')); ?>" style="background: url(<?php echo e($login_img); ?>);"></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type='text/javascript'>
    $(document).ready(function() {
        $('#revealPass').mousedown(function () {
            $('#password_id').attr('type', 'text');
        }).mouseup(function () {
            $('#password_id').attr('type', 'password');
        })
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/main/login_form.blade.php ENDPATH**/ ?>