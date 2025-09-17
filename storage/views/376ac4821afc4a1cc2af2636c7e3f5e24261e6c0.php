

<?php if($isInOpenCoursesMode): ?>
    <?php $__env->startPush('head_styles'); ?>
        <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>modules/course_metadata/course_metadata.css">
    <?php $__env->stopPush(); ?>
    <?php $__env->startPush('head_scripts'); ?>
        <script type="text/javascript">
            var dialog;
            var showMetadata = function(course) {
                $('.modal-body', dialog).load('anoninfo.php', {course: course}, function(response, status, xhr) {
                    if (status === "error") {
                        $('.modal-body', dialog).html("Sorry but there was an error, please try again");
                        //console.debug("jqxhr Request Failed, status: " + xhr.status + ", statusText: " + xhr.statusText);
                    }
                });
                dialog.modal('show');
            };

            $(document).ready(function() {
                dialog = $('<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modal-label" aria-hidden="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><div class="modal-title" id="modal-label"><?php echo e(trans('langCourseMetadata')); ?></div><button type="button" class="close" data-bs-dismiss="modal"></button></div><div class="modal-body">body</div></div></div></div>');
            });

        </script>
    <?php $__env->stopPush(); ?>
<?php endif; ?>

<?php $__env->startSection('content'); ?>

<div class="col-12 main-section">
    <div class='<?php echo e($container); ?> main-container'>
        <div class="row m-auto">

            <?php if(isset($_SESSION['uid'])): ?>
                <?php echo $__env->make('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <?php echo $__env->make('layouts.partials.show_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="col-12 <?php if(isset($_SESSION['uid'])): ?> mt-4 <?php endif; ?>">
                <h1><?php echo e($toolName); ?></h1>
            </div>

            <div class='col-12 mt-4'>
                <?php if(isset($buildRoots)): ?>
                    <?php echo $buildRoots; ?>

                <?php endif; ?>
                <div class='col-12'>
                    <ul class='list-group list-group-flush'>
                        <li class="list-group-item list-group-item-action d-flex justify-content-start align-items-center flex-wrap gap-2">
                            <?php echo $tree->getFullPath($fc, false, $_SERVER['SCRIPT_NAME'] . '?fc='); ?>

                        </li>
                        <?php echo $childHTML; ?>

                    </ul>
                </div>
            </div>

            <?php if(count($courses) > 0): ?>
                <div class='col-12 mt-4'>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item list-group-item-action d-flex justify-content-between align-items-center'>
                            <div><?php echo e(trans('langCourse')); ?></div>
                            <div><?php echo e(trans('langGroupAccess')); ?></div>
                        </li>
                        <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mycourse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="list-group-item element d-flex justify-content-between align-items-center gap-5">
                                <div class='d-flex justify-content-start align-items-start gap-3'>
                                    <?php if(isset($_SESSION['uid'])): ?> 
                                        <div class="d-flex justify-content-center align-items-center gap-3" style="min-width: 30px;">
                                            <?php if(isset($myCourses[$mycourse->id])): ?>
                                                <?php if($myCourses[$mycourse->id]->status != 1): ?> 
                                                    <label class='label-container' aria-label='<?php echo e(trans('langSelect')); ?>'>
                                                        <input type='checkbox' name='selectCourse[]' value='<?php echo e($mycourse->id); ?>' checked='checked' <?php if($mycourse->visible == COURSE_CLOSED): ?> class='reg_closed' <?php endif; ?> <?php if(get_config('disable_student_unregister_cours')): ?> 'disabled' <?php endif; ?>>
                                                        <span class='checkmark'></span>
                                                    </label>
                                                <?php else: ?>
                                                    <i class='fa-solid fa-user fa-lg mt-3'></i>
                                                <?php endif; ?>
                                            <?php else: ?> 
                                                    <label class='label-container gap-0' aria-label='<?php echo e(trans('langSelect')); ?>'>
                                                        <input type='checkbox' name='selectCourse[]' value='<?php echo e($mycourse->id); ?>'
                                                               <?php if((($mycourse->visible == COURSE_REGISTRATION or $mycourse->visible == COURSE_OPEN)
                                                                        and setting_get(SETTING_FACULTY_USERS_REGISTRATION, $mycourse->id) == 1
                                                                        and !in_array($fc, $user_faculty_ids))
                                                                    or (!is_enabled_course_registration($_SESSION['uid']))
                                                                    or $mycourse->visible == COURSE_CLOSED): ?>
                                                                   disabled
                                                                <?php endif; ?>>
                                                        <span class='checkmark'></span>
                                                    </label>
                                            <?php endif; ?>
                                            <input type='hidden' name='changeCourse[]' value='<?php echo e($mycourse->id); ?>'>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div class='d-flex justify-content-start align-items-start gap-3 flex-wrap'>
                                            <div>
                                                <?php if($mycourse->visible == COURSE_OPEN or $unlock_all_courses or isset($myCourses[$mycourse->id])): ?> 
                                                    <a class='TextBold' href="../../courses/<?php echo e(urlencode($mycourse->k)); ?>/"><?php echo e($mycourse->i); ?></a>
                                                    &nbsp;<small>(<?php echo e($mycourse->c); ?>)</small>
                                                <?php else: ?>
                                                    <span <?php if(isset($_SESSION['uid'])): ?> id='cid<?php echo e($mycourse->id); ?>' <?php endif; ?> class='TextBold'>
                                                        <?php echo e($mycourse->i); ?>

                                                    </span>
                                                    &nbsp;<small>(<?php echo e($mycourse->c); ?>)</small>
                                                <?php endif; ?>
                                                <div>
                                                    <small class='vsmall-text TextRegular'><?php echo e($mycourse->t); ?></small>
                                                    <?php if(isset($_SESSION['uid'])): ?>           
                                                        <?php if($mycourse->visible == COURSE_CLOSED and !setting_get(SETTING_COURSE_USER_REQUESTS_DISABLE, $mycourse->id) and !isset($myCourses[$mycourse->id])): ?> 
                                                            <br><small><em>
                                                            <a class='text-decoration-underline' href='../contact/index.php?course_id=<?php echo e($mycourse->id); ?>'>
                                                                <?php if($mycourse->clb): ?>
                                                                    <?php echo e(trans('langLabelCollabUserReques')); ?>

                                                                <?php else: ?>
                                                                    <?php echo e(trans('langLabelCourseUserRequest')); ?>

                                                                <?php endif; ?>
                                                            </a>
                                                            </em></small>
                                                        <?php endif; ?>
                                                        
                                                        <?php if(isset($myCourses[$mycourse->id])): ?>
                                                            <?php if($myCourses[$mycourse->id]->status != 1 and (!empty($mycourse->password))): ?>
                                                                <span class='badge Warning-200-bg'><?php echo e(trans('langPassword')); ?></span>
                                                                <input class='form-control' type='password' name='pass<?php echo e($mycourse->id); ?>' value='<?php echo e($mycourse->password); ?>' autocomplete='off' />
                                                            <?php endif; ?>
                                                        <?php else: ?>
                                                            <?php if(!empty($mycourse->password) and ($mycourse->visible == COURSE_REGISTRATION or $mycourse->visible == COURSE_OPEN)): ?>
                                                                <span class='badge Warning-200-bg'><?php echo e(trans('langPassword')); ?></span>
                                                                <input class='form-control' type='password' name='pass<?php echo e($mycourse->id); ?>' autocomplete='off' />
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                        
                                                        <?php echo getCoursePrerequisites($mycourse->id); ?>

                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <?php if($displayGuestLoginLinks): ?>
                                                <?php if($course_data[$mycourse->id]['userguest']): ?>
                                                    <div>
                                                        <?php if($course_data[$mycourse->id]['userguest']->password === ''): ?>
                                                            <form method='post' action='<?php echo e($urlAppend); ?>'>
                                                                <input type='hidden' name='uname' value='<?php echo e($course_data[$mycourse->id]['userguest']->username); ?>'>
                                                                <input type='hidden' name='pass' value=''>
                                                                <input type='hidden' name='next' value='/courses/<?php echo e($mycourse->k); ?>/'>
                                                                <button style='height:30px;' type='submit' title='<?php echo e(trans('langGuestLogin')); ?>' name='submit' data-bs-toggle='tooltip' data-bs-placement='top' aria-label="<?php echo e(trans('langGuestLogin')); ?>">
                                                                    <i class="fa-solid fa-right-to-bracket fa-lg"></i>
                                                                </button>
                                                            </form>
                                                        <?php else: ?>
                                                            <a role='button' href="<?php echo e($urlAppend); ?>main/login_form.php?user=<?php echo e(urlencode($course_data[$mycourse->id]['userguest']->username)); ?>&amp;next=%2Fcourses%2F<?php echo e($mycourse->k); ?>%2F" title='<?php echo e(trans('langGuestLogin')); ?>' data-bs-placement='top' data-bs-toggle='tooltip' aria-label='<?php echo e(trans('langGuestLogin')); ?>'>
                                                                <i class="fa-solid fa-right-to-bracket fa-lg"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>


                                <div class="d-flex justify-content-start align-items-center gap-3" style='min-width:65px;'>
                                    <?php if(!isset($_SESSION['uid']) and $mycourse->visible == COURSE_CLOSED): ?>
                                        <div>
                                            &mdash;
                                        </div>
                                    <?php else: ?>
                                        <div>
                                            <?php if(!get_config('show_modal_openCourses')): ?>
                                                <a href='<?php echo e($urlAppend); ?>modules/auth/info_course.php?c=<?php echo e($mycourse->k); ?>' data-bs-toggle='tooltip' data-bs-placement='top' title="<?php echo e(trans('langPreview')); ?>" aria-label="<?php echo e(trans('langPreview')); ?>">
                                                    <i class="fa-solid fa-display"></i>
                                                </a>
                                            <?php else: ?>
                                                <button class="ClickCourse border-0 rounded-pill bg-transparent" id="<?php echo e($mycourse->k); ?>" type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(trans('langPreview')); ?>" aria-label="<?php echo e(trans('langPreview')); ?>">
                                                    <i class='fa-solid fa-display'></i>
                                                </button>
                                                <div id="myModal<?php echo e($mycourse->k); ?>" class="modal">
                                                    <div class="modal-content modal-content-opencourses px-lg-5 py-lg-5">
                                                        <div class='col-12 d-flex justify-content-between align-items-start modal-display'>
                                                            <div>
                                                                <div class='d-flex justify-content-start align-items-center gap-2 flex-wrap'>
                                                                    <h2 class='mb-0'><?php echo e($mycourse->i); ?></h2>
                                                                    <?php echo course_access_icon($mycourse->visible); ?>

                                                                    <?php if($mycourse->cls > 0): ?>
                                                                        <?php echo copyright_info($mycourse->id); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                                <div class='mt-2'><?php echo e($mycourse->c); ?>&nbsp; - &nbsp;<?php echo e($mycourse->t); ?></div>
                                                            </div>
                                                            <div>
                                                                <button type='button' class="close" aria-label="<?php echo e(trans('langClose')); ?>"></button>
                                                            </div>
                                                        </div>
                                                        <div class='course-content mt-4'>
                                                            <div class='col-12 d-flex justify-content-center align-items-start'>
                                                                <?php if($mycourse->img == NULL): ?>
                                                                    <?php if($mycourse->clb): ?>
                                                                        <img class='openCourseImg' src="<?php echo e($urlAppend); ?>template/modern/images/default-collaboration.jpg" alt="<?php echo e(trans('langCourseImage')); ?>" /></a>
                                                                    <?php else: ?>
                                                                        <img class='openCourseImg' src="<?php echo e($urlAppend); ?>resources/img/ph1.jpg" alt="<?php echo e(trans('langCourseImage')); ?>" /></a>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <img class='openCourseImg' src="<?php echo e($urlAppend); ?>courses/<?php echo e($mycourse->k); ?>/image/<?php echo e($mycourse->img); ?>" alt="<?php echo e(trans('langCourseImage')); ?>" /></a>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class='col-12 openCourseDes mt-3 Neutral-900-cl pb-3'>
                                                                <?php if(empty($mycourse->de)): ?>
                                                                    <?php if($mycourse->clb): ?>
                                                                        <p class='text-center'><?php echo e(trans('langThisCollabDescriptionIsEmpty')); ?></p>
                                                                    <?php else: ?>
                                                                        <p class='text-center'><?php echo e(trans('langThisCourseDescriptionIsEmpty')); ?></p>
                                                                    <?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php echo $mycourse->de; ?>

                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <?php if($isInOpenCoursesMode): ?>
                                            <?php echo CourseXMLElement::getLevel($mycourse->level); ?>&nbsp;
                                                <a href='javascript:showMetadata("<?php echo e($mycourse->k); ?>");' data-bs-toggle='tooltip' data-bs-original-title="<?php echo e(trans('langCourseMetadata')); ?>">
                                                    <img alt="<?php echo e(trans('langCourseMetadata')); ?>" src='<?php echo e($urlAppend); ?>resources/icons/lom.png'/>
                                                </a>
                                        <?php else: ?>
                                            <?php echo course_access_icon($mycourse->visible); ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(course_list_init);
    var urlAppend = '<?php echo e($urlAppend); ?>';
    var lang = {
        unCourse: '<?php echo e(js_escape(trans('langUnCourse'))); ?>',
        cancel: '<?php echo e(js_escape(trans('langCancel'))); ?>',
        close: '<?php echo e(js_escape(trans('langClose'))); ?>',
        unregCourse: '<?php echo e(js_escape(trans('langDeleteUser'))); ?>',
        reregisterImpossible: '<?php echo e(js_escape(trans('langConfirmUnregCours'))); ?>',
        invalidCode: '<?php echo e(js_escape(trans('langWrongPassCourse'))); ?> ',
        prereqsNotComplete: '<?php echo e(js_escape(trans('langPrerequisitesNotComplete'))); ?>',
    };
    var courses = <?php echo json_encode($courses_list); ?>;

    var idCourse = '';
    var btn = '';
    var modal = '';
    $(".ClickCourse").click(function() {
        idCourse = this.id;
        modal = document.getElementById("myModal"+idCourse);
        btn = document.getElementById(idCourse);
        modal.style.display = "block";
        $('[data-bs-toggle="tooltip"]').tooltip("hide");
        var $div = $('<div />').appendTo('body');
        $div.attr('class', 'modal-backdrop fade show');
    });

    $(".close").click(function() {
        modal.style.display = "none";
        $(".modal-backdrop").remove();
    });

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
            $(".modal-backdrop").remove();
        }
        $('[data-bs-toggle="tooltip"]').tooltip("hide");
    }

</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/modules/auth/courses.blade.php ENDPATH**/ ?>