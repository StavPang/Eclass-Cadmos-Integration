

<?php $__env->startSection('content'); ?>

<div class="col-12 main-section">
<div class='<?php echo e($container); ?> main-container'>
        <div class="row m-auto">

            <?php if(isset($_SESSION['uid'])): ?>
                <?php echo $__env->make('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endif; ?>

            <div class='col-12 my-4'>
                <h1><?php echo e($pageName); ?></h1>
            </div>

            <div class="col-12">

                    <div class="card card-course-info px-lg-4 py-lg-4 p-3 mb-3">
                        <div class="row row-cols-1 row-cols-md-2 g-3">
                            <div class="col-md-4 col d-flex justify-content-center justify-content-md-start">
                                <?php if($c->course_image == NULL): ?>
                                    <?php if($c->is_collaborative): ?>
                                        <img class='img-fluid rounded-start course_info_img' src="<?php echo e($urlAppend); ?>template/modern/images/default-collaboration.jpg" alt="<?php echo e(trans('langImageSelected')); ?>" />
                                    <?php else: ?>
                                        <img class='img-fluid rounded-start course_info_img' src="<?php echo e($urlAppend); ?>resources/img/ph1.jpg" alt="<?php echo e(trans('langImageSelected')); ?>" />
                                    <?php endif; ?>
                                <?php else: ?>
                                    <img class='img-fluid rounded-start course_info_img' src="<?php echo e($urlAppend); ?>courses/<?php echo e($c->code); ?>/image/<?php echo e($c->course_image); ?>" alt="<?php echo e(trans('langImageSelected')); ?>" />
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8 col">
                                <div class="card-body py-0">

                                    <div class='d-flex justify-content-start align-items-center gap-2 flex-wrap'>
                                        <h2 class="mb-0"><?php echo e($c->title); ?></h2>
                                        <?php echo course_access_icon($c->visible); ?>

                                        <?php if($c->course_license > 0): ?>
                                            <?php echo copyright_info($c->id); ?>

                                        <?php endif; ?>
                                        <?php if($cdm_data): ?>
                                            <span class="badge bg-info text-white">
                                                <i class="fa fa-graduation-cap"></i> CDM Import
                                            </span>
                                            <?php if(str_contains($cdm_data['Description'] ?? '', 'Think Pair Share')): ?>
                                                <span class="badge bg-success">Think-Pair-Share</span>
                                            <?php endif; ?>
                                            <?php if(str_contains($cdm_data['Description'] ?? '', 'Problem Based Learning')): ?>
                                                <span class="badge bg-warning">Problem-Based Learning</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>

                                    <p class="card-text mt-2 mb-4">(<?php echo e($c->public_code); ?>)&nbsp;- &nbsp;<?php echo e($c->prof_names); ?></p>

                                    <?php if(empty($c->description)): ?>
                                        <?php if(!$c->is_collaborative): ?>
                                        <p class='form-label mb-1'><?php echo e(trans('langCourseProgram')); ?></p>
                                        <?php else: ?>
                                        <p class='form-label mb-1'><?php echo e(trans('langCollabDes')); ?></p>
                                        <?php endif; ?>
                                        <p><?php echo e(trans('langThisCourseDescriptionIsEmpty')); ?></p>
                                    <?php else: ?>
                                        <?php if(!$c->is_collaborative): ?>
                                        <p class='form-label mb-1'><?php echo e(trans('langCourseProgram')); ?></p>
                                        <?php else: ?>
                                        <p class='form-label mb-1'><?php echo e(trans('langCollabDes')); ?></p>
                                        <?php endif; ?>
                                        <p><?php echo $c->description; ?></p>
                                    <?php endif; ?>

                                    <p class='form-label mb-1 mt-4'><?php echo e(trans('langCreationDate')); ?></p>
                                    <p><?php echo e(format_locale_date(strtotime($c->created), null, false)); ?></p>

                                    <div class='col-12 mt-4 d-flex justify-content-md-start justify-content-center'>
                                        <a class='btn submitAdminBtnDefault d-flex jystify-content-start align-items-center gap-2' href='<?php echo e($urlServer); ?>courses/<?php echo e($c->code); ?>/'>

                                            <?php if($c->is_collaborative): ?>
                                                <?php echo e(trans('langPageCollaboration')); ?>

                                            <?php else: ?>
                                                <?php echo e(trans('langCoursePage')); ?>

                                            <?php endif; ?>
                                            <i class="fa-solid fa-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <?php if($cdm_data): ?>
            <!-- Comprehensive CDM Information Section -->
            <div class='col-12 mt-4'>
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fa fa-graduation-cap"></i> Course Design Model (CDM) Complete Analysis
                        </h4>
                        <small>Comprehensive educational metadata and learning design information</small>
                    </div>
                    <div class="card-body">

                        <!-- Basic Information Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <?php if(isset($cdm_data['EducationLevel'])): ?>
                                <div class="mb-3 p-3 bg-primary bg-opacity-10 rounded border-start border-primary border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-school text-primary fs-5"></i>
                                        <div>
                                            <strong>Education Level</strong><br>
                                            <span class="badge bg-primary"><?php echo e($cdm_data['EducationLevel']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if(isset($cdm_data['SubjectArea'])): ?>
                                <div class="mb-3 p-3 bg-success bg-opacity-10 rounded border-start border-success border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-book text-success fs-5"></i>
                                        <div>
                                            <strong>Subject Area</strong><br>
                                            <span class="text-success fw-bold"><?php echo e($cdm_data['SubjectArea']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <?php if(isset($cdm_data['DurationNumber']) && isset($cdm_data['DurationType'])): ?>
                                <div class="mb-3 p-3 bg-warning bg-opacity-10 rounded border-start border-warning border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-clock text-warning fs-5"></i>
                                        <div>
                                            <strong>Course Duration</strong><br>
                                            <span class="badge bg-warning text-dark"><?php echo e($cdm_data['DurationNumber']); ?> <?php echo e($cdm_data['DurationType']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if(isset($cdm_data['Prerequisites']) && is_array($cdm_data['Prerequisites'])): ?>
                                <div class="mb-3 p-3 bg-secondary bg-opacity-10 rounded border-start border-secondary border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-list-check text-secondary fs-5"></i>
                                        <div>
                                            <strong>Prerequisites</strong><br>
                                            <?php if(empty($cdm_data['Prerequisites'])): ?>
                                                <span class="text-muted">None specified</span>
                                            <?php else: ?>
                                                <?php $__currentLoopData = $cdm_data['Prerequisites']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prereq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="badge bg-secondary me-1"><?php echo e($prereq); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Actors and Roles Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <?php if(isset($cdm_data['Actors']) && is_array($cdm_data['Actors'])): ?>
                                <div class="mb-3">
                                    <h5><i class="fa fa-users text-info"></i> Course Participants</h5>
                                    <div class="p-3 bg-info bg-opacity-10 rounded">
                                        <?php $__currentLoopData = $cdm_data['Actors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $actor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($actor === 'Student'): ?>
                                                <span class="badge bg-info me-1 mb-1"><i class="fa fa-user-graduate"></i> <?php echo e($actor); ?></span>
                                            <?php elseif($actor === 'Teacher'): ?>
                                                <span class="badge bg-success me-1 mb-1"><i class="fa fa-chalkboard-teacher"></i> <?php echo e($actor); ?></span>
                                            <?php elseif($actor === 'Group'): ?>
                                                <span class="badge bg-primary me-1 mb-1"><i class="fa fa-users"></i> <?php echo e($actor); ?></span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-user"></i> <?php echo e($actor); ?></span>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if(isset($cdm_data['Learners']) && is_array($cdm_data['Learners'])): ?>
                                <div class="mb-3">
                                    <h5><i class="fa fa-user-graduate text-primary"></i> Target Learners</h5>
                                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                                        <?php $__currentLoopData = $cdm_data['Learners']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $learner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-primary me-1 mb-1"><?php echo e($learner); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <?php if(isset($cdm_data['StaffRoles']) && is_array($cdm_data['StaffRoles'])): ?>
                                <div class="mb-3">
                                    <h5><i class="fa fa-user-tie text-secondary"></i> Staff Roles</h5>
                                    <div class="p-3 bg-secondary bg-opacity-10 rounded">
                                        <?php $__currentLoopData = $cdm_data['StaffRoles']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-briefcase"></i> <?php echo e($role); ?></span>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if(isset($cdm_data['Simple_activity_types']) && is_array($cdm_data['Simple_activity_types'])): ?>
                                <div class="mb-3">
                                    <h5><i class="fa fa-tasks text-warning"></i> Activity Types Available</h5>
                                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                                        <?php $__currentLoopData = $cdm_data['Simple_activity_types']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php switch($type):
                                                case ('Creating'): ?>
                                                    <span class="badge bg-success me-1 mb-1"><i class="fa fa-plus-circle"></i> <?php echo e($type); ?></span>
                                                    <?php break; ?>
                                                <?php case ('Evaluating'): ?>
                                                    <span class="badge bg-danger me-1 mb-1"><i class="fa fa-check-circle"></i> <?php echo e($type); ?></span>
                                                    <?php break; ?>
                                                <?php case ('Analyzing'): ?>
                                                    <span class="badge bg-info me-1 mb-1"><i class="fa fa-search"></i> <?php echo e($type); ?></span>
                                                    <?php break; ?>
                                                <?php case ('Applying'): ?>
                                                    <span class="badge bg-primary me-1 mb-1"><i class="fa fa-cogs"></i> <?php echo e($type); ?></span>
                                                    <?php break; ?>
                                                <?php case ('Understanding'): ?>
                                                    <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-lightbulb"></i> <?php echo e($type); ?></span>
                                                    <?php break; ?>
                                                <?php case ('Remembering'): ?>
                                                    <span class="badge bg-dark me-1 mb-1"><i class="fa fa-brain"></i> <?php echo e($type); ?></span>
                                                    <?php break; ?>
                                                <?php default: ?>
                                                    <span class="badge bg-warning me-1 mb-1"><?php echo e($type); ?></span>
                                            <?php endswitch; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Resource Information -->
                        <?php if(isset($cdm_data['Resource_types']) && is_array($cdm_data['Resource_types'])): ?>
                        <div class="mb-4">
                            <h5><i class="fa fa-folder-open text-info"></i> Resource Types Available</h5>
                            <div class="p-3 bg-light rounded">
                                <?php $__currentLoopData = $cdm_data['Resource_types']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php switch($resource):
                                        case ('Video'): ?>
                                            <span class="badge bg-danger me-1 mb-1"><i class="fa fa-video"></i> <?php echo e($resource); ?></span>
                                            <?php break; ?>
                                        <?php case ('Quiz'): ?>
                                            <span class="badge bg-warning me-1 mb-1"><i class="fa fa-question-circle"></i> <?php echo e($resource); ?></span>
                                            <?php break; ?>
                                        <?php case ('Hypertext'): ?>
                                            <span class="badge bg-primary me-1 mb-1"><i class="fa fa-link"></i> <?php echo e($resource); ?></span>
                                            <?php break; ?>
                                        <?php case ('Document'): ?>
                                            <span class="badge bg-success me-1 mb-1"><i class="fa fa-file-text"></i> <?php echo e($resource); ?></span>
                                            <?php break; ?>
                                        <?php case ('Assessment'): ?>
                                            <span class="badge bg-info me-1 mb-1"><i class="fa fa-clipboard-check"></i> <?php echo e($resource); ?></span>
                                            <?php break; ?>
                                        <?php default: ?>
                                            <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-file"></i> <?php echo e($resource); ?></span>
                                    <?php endswitch; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Learning Goals Section -->
                        <?php if(isset($cdm_data['Goals']) && is_array($cdm_data['Goals'])): ?>
                        <div class="mb-4">
                            <h5><i class="fa fa-bullseye text-success"></i> Learning Goals & Objectives</h5>
                            <div class="p-4 bg-success bg-opacity-10 rounded border border-success">
                                <div class="row">
                                    <?php $__currentLoopData = $cdm_data['Goals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <span class="badge bg-success rounded-circle"><?php echo e($index + 1); ?></span>
                                            <span><?php echo e($goal); ?></span>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Course Description with Methodology -->
                        <?php if(isset($cdm_data['Description']) && !empty($cdm_data['Description'])): ?>
                        <div class="mb-4">
                            <h5><i class="fa fa-info-circle text-primary"></i> Educational Methodology & Approach</h5>
                            <div class="p-4 bg-primary bg-opacity-10 rounded border border-primary">
                                <div class="mb-3">
                                    <?php echo nl2br(e($cdm_data['Description'])); ?>

                                </div>

                                <?php if(str_contains($cdm_data['Description'], 'Think Pair Share')): ?>
                                <div class="mt-3 p-3 bg-white rounded border border-success">
                                    <h6><i class="fa fa-users text-success"></i> Think-Pair-Share Methodology</h6>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="badge bg-info">1. Think (Individual)</span>
                                        <span class="badge bg-warning">2. Pair (Collaborative)</span>
                                        <span class="badge bg-success">3. Share (Group)</span>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if(str_contains($cdm_data['Description'], 'Problem Based Learning')): ?>
                                <div class="mt-3 p-3 bg-white rounded border border-warning">
                                    <h6><i class="fa fa-puzzle-piece text-warning"></i> Problem-Based Learning Approach</h6>
                                    <small class="text-muted">Students learn through solving authentic, real-world problems</small>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Copyright and Licensing Information -->
                        <?php if(isset($cdm_data['Resource_copyright']) && is_array($cdm_data['Resource_copyright'])): ?>
                        <div class="mb-4">
                            <h5><i class="fa fa-copyright text-secondary"></i> Resource Licensing</h5>
                            <div class="p-3 bg-light rounded">
                                <div class="row">
                                    <?php $__currentLoopData = $cdm_data['Resource_copyright']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $license): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-auto">
                                        <?php if($license === 'free'): ?>
                                            <span class="badge bg-success me-2"><i class="fa fa-unlock"></i> Free Resources Available</span>
                                        <?php elseif($license === 'proprietary'): ?>
                                            <span class="badge bg-warning me-2"><i class="fa fa-lock"></i> Proprietary Resources Included</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary me-2"><i class="fa fa-info-circle"></i> <?php echo e($license); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Technical Information -->
                        <div class="mt-5 pt-4 border-top">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-graduation-cap fa-2x text-info mb-2"></i>
                                        <h6>CDM Import</h6>
                                        <small class="text-muted">Course Design Model</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-tasks fa-2x text-success mb-2"></i>
                                        <h6>Structured Learning</h6>
                                        <small class="text-muted">Activity-Based Design</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                        <h6>Collaborative</h6>
                                        <small class="text-muted">Group & Individual Work</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-chart-line fa-2x text-warning mb-2"></i>
                                        <h6>Scaffolded</h6>
                                        <small class="text-muted">Progressive Difficulty</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> This course was imported from a Course Design Model (CDM) file with complete educational metadata, learning objectives, activity structures, and pedagogical methodology preserved.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if(!$c->is_collaborative): ?>
                <div class='col-12 mt-4'>
                    <div class='row'>
                        <div class='panel'>
                            <div class='panel-group group-section mt-2 px-0' id='accordionDesC'>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 mb-4 bg-transparent">

                                        <div class='d-flex justify-content-between border-bottom-default'>
                                            <a class='accordion-btn d-flex justify-content-start align-items-start gap-2 py-2' role='button' id='btn-syllabus' data-bs-toggle='collapse' href='#collapseDescriptionc' aria-expanded='true' aria-controls='collapseDescriptionc'>
                                                <i class='fa-solid fa-chevron-down settings-icon'></i>
                                                <?php echo e(trans('langSyllabus')); ?>

                                            </a>
                                        </div>
                                        <div class='panel-collapse accordion-collapse collapse border-0 rounded-0 mt-3 show' id='collapseDescriptionc' data-bs-parent='#accordionDesC'>
                                            <?php if(count($course_descriptions) == 0): ?>
                                                <div class='col-12 mb-4'>
                                                    <p><?php echo e(trans('langNoSyllabus')); ?></p>
                                                </div>
                                            <?php else: ?>
                                                <?php $__currentLoopData = $course_descriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class='col-12 mb-4'>
                                                        <p class='form-label text-start'><?php echo e($row->title); ?></p>
                                                        <?php echo standard_text_escape($row->comments); ?>

                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/modules/auth/info_course.blade.php ENDPATH**/ ?>