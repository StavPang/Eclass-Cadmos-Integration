<?php
    $go_back_url = $_SERVER['REQUEST_URI'];
    if (!$module_visibility) {
        $visible_module = 0;
    } else {
        $visible_module = 1;
    }
?>

<?php if(!isset($_GET['fromFlipped'])): ?>
    <h1 class='sr-only'>
        <?php if($course_code): ?>
            <?php echo e(trans('langCourse')); ?> : <?php echo e($currentCourseName); ?>

        <?php elseif($pageTitle): ?>
            <?php echo e($pageTitle); ?>

        <?php endif; ?>
    </h1>
    <h2 class='sr-only'>
        <?php if($course_code): ?>
            <?php echo e(trans('langCode')); ?> : <?php echo e($course_code); ?>

        <?php elseif($pageName): ?>
            <?php echo e(trans('langThePageIs')); ?> <?php echo e($pageName); ?>

        <?php elseif($toolName): ?> <?php echo e(trans('langThePageIs')); ?> <?php echo e($toolName); ?>

        <?php endif; ?>
    </h2>
    <?php if($course_code or $require_help or $breadcrumbs): ?>
        <div class='col-12 mt-4 <?php if(!isset($action_bar) or empty($action_bar)): ?> mb-3 <?php endif; ?>'>
    <?php else: ?>
        <div class='col-12 <?php if(!isset($action_bar) or empty($action_bar)): ?> mb-3 <?php endif; ?>'>
    <?php endif; ?>
        <div class='d-flex gap-lg-5 gap-4'>
            <div class='flex-grow-1'>
                <?php if($course_code): ?> 
                    <div class='col-12 mb-2'>
                        <div class='d-flex justify-content-start align-items-center gap-2 flex-wrap'>
                            <?php if(isset($course_code)): ?>
                                <a href="<?php echo e($urlAppend); ?>courses/<?php echo e($course_code); ?>/"><h2 class='mb-0'><?php echo e($currentCourseName); ?></h2></a>
                            <?php else: ?>
                                <h2 class='mb-0'><?php echo e($currentCourseName); ?></h2>
                            <?php endif; ?>
                        </div>
                        <div class='d-flex justify-content-start align-items-center gap-2 mt-2 flex-wrap'>
                            <p><?php echo e(course_id_to_public_code($course_id)); ?>&nbsp; - &nbsp;<?php echo e(course_id_to_prof($course_id)); ?></p>
                            <div class='course-title-icons d-flex justify-content-start align-items-center gap-2'>
                                <?php echo course_access_icon(course_status($course_id)); ?>

                                <?php if($courseLicense > 0): ?>
                                    <?php echo copyright_info($course_id); ?>

                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if(!isset($action_bar) or empty($action_bar)): ?>
                            <div class="col-12 d-md-flex justify-content-md-between align-items-lg-start my-3">
                                <div class='col-12 d-inline-flex'>
                                    <div class="action-bar-title mb-0">
                                        <?php echo e($toolName); ?>

                                        <?php if($pageName and ($pageName != $toolName)): ?>
                                            - <?php echo e($pageName); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <?php if($toolName): ?>
                        <div class='col-12 d-inline-flex'>
                            <h2>
                                <?php echo e($toolName); ?>

                            </h2>
                        </div>
                        <?php if(!isset($action_bar) or empty($action_bar)): ?>
                            <div class='col-12 d-inline-flex mt-2'>
                                <?php if($pageName and ($pageName != $toolName)): ?>
                                    <h3>
                                        <?php echo e($pageName); ?>

                                    </h3>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class='d-flex flex-column'>
                <!-- course admin menu -->
                <?php if($is_editor): ?>
                    <?php echo $__env->make('layouts.partials.manageCourse',[$urlAppend => $urlAppend,'coursePrivateCode' => $course_code], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endif; ?>
                <?php if($course_code): ?> 
                    <div class='d-flex justify-content-end align-items-end gap-2 mt-3'>
                <?php else: ?>
                    <div class='d-flex justify-content-end align-items-end gap-2'>
                <?php endif; ?>
                    <!-- active - inactive module_id -->
                    <?php if($module_id != MODULE_ID_COURSEINFO and $module_id != MODULE_ID_USERS
                        and $module_id != MODULE_ID_USAGE and $module_id != MODULE_ID_TOOLADMIN
                        and $module_id != MODULE_ID_ABUSE_REPORT and $module_id != MODULE_ID_COURSE_WIDGETS
                        and $module_id != MODULE_ID_UNITS and !empty($module_id)): ?>
                            <form id="form_id" action="<?php echo e($urlAppend); ?>main/module_toggle.php?course=<?php echo e($course_code); ?>&module_id=<?php echo e($module_id); ?>" method="post">
                                <input type="hidden" name="hide" value="<?php echo e($visible_module); ?>">
                                <input type="hidden" name="Active_Deactive_Btn">
                                <input type="hidden" name="prev_url" value="<?php echo e($go_back_url); ?>">
                                <?php if(display_activation_link($module_id)): ?>
                                    <?php if($visible_module == 0): ?>
                                        <a class='btn deleteAdminBtn text-decoration-none' href="javascript:$('#form_id').submit();"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="<?php echo e(trans('langActivate')); ?>" aria-label="<?php echo e(trans('langActivate')); ?>">
                                            <i class="fa-regular fa-eye-slash"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class='btn successAdminBtn text-decoration-none' href="javascript:$('#form_id').submit();"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="<?php echo e(trans('langDeactivate')); ?>" aria-label="<?php echo e(trans('langDeactivate')); ?>">
                                            <i class="fa-regular fa-eye"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </form>
                    <?php endif; ?>

                    <?php if(defined('RSS')): ?> 
                        <a class='btn btn-default text-decoration-none tiny-icon-rss' href="<?php echo e(RSS); ?>"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="<?php echo e(defined('RSS_TITLE')? RSS_TITLE: trans('langRSSFeed')); ?>"
                           aria-label="<?php echo e(defined('RSS_TITLE')? RSS_TITLE: trans('langRSSFeed')); ?>">
                           <span class="fa-solid <?php echo e(defined('RSS_ICON')? RSS_ICON: 'fa-rss'); ?>"></span>
                        </a>
                    <?php endif; ?>
                    <?php if($require_help): ?> 
                        <a id='help-btn' href='<?php echo e($urlServer); ?>modules/help/help.php?language=<?php echo e($language); ?>&topic=<?php echo e($helpTopic); ?>&subtopic=<?php echo e($helpSubTopic); ?>'
                            class='btn helpAdminBtn text-decoration-none' data-bs-toggle='tooltip' data-bs-placement='bottom'
                            title data-bs-original-title="<?php echo e(trans('langHelp')); ?>" aria-label="<?php echo e(trans('langHelp')); ?>" tabindex="-1" role="button">
                            <i class="fas fa-question-circle"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/layouts/partials/legend_view.blade.php ENDPATH**/ ?>