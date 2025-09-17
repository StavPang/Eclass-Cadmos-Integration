

<?php $__env->startSection('content'); ?>

<div class="col-12 main-section">

        <?php if($warning): ?>
            <input id='showWarningModal' type='hidden' value='1'>
            <div class="modal fade" id="WarningModal" aria-hidden="true" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content shadow-sm border-0 p-0">
                        <div class="modal-header d-flex justify-content-between align-items-center">
                            <div class="modal-title"><?php echo e(trans('langError')); ?></div>
                            <button aria-label="<?php echo e(trans('langClose')); ?>" type='button' class='close border-0 bg-transparent' data-bs-dismiss='modal'>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?php echo $warning; ?>

                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <input id='showWarningModal' type='hidden' value='0'>
        <?php endif; ?>

        <?php echo $__env->make('layouts.partials.show_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <?php if($display_login_form != 1): ?>
            <div class='row m-auto row-jumbotron'>
                <div class="col-12 jumbotron jumbotron-login">

                    <?php if($VideoUploadedInJumbotron): ?>
                        <video preload="auto" autoplay="" playsinline="" loop="" muted="">
                            <source type="video/mp4" src="<?php echo e($urlAppend); ?>courses/theme_data/<?php echo e($theme_id); ?>/video.mp4">
                        </video>
                        <div class='radial-gradient-video'></div>
                        <div class='<?php echo e($container); ?> padding-default overlay-video-container'>
                            <div class='row row-cols-1 g-4'>
                                <div class='col'>
                                    <div class='card bg-transparent card-transparent border-0'>
                                        <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0 gap-3 flex-wrap'>
                                            <div class='jumbotron-intro-text'>
                                                <?php if(get_config('homepage_title_'.$language_code)): ?>
                                                    <h1 class='eclass-title' aria-label="<?php echo e(trans('langEclass')); ?>"><?php echo e(get_config('homepage_title_'.$language_code)); ?></h1>
                                                <?php endif; ?>

                                                <?php if(get_config('homepage_intro_'.$language_code)): ?>
                                                    <p class='eclassInfo mb-0' aria-label="<?php echo e(trans('langInfo')); ?>"><?php echo get_config('homepage_intro_'.$language_code); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class='card-body px-0'>
                                            <?php if(get_config('enable_mobileapi') || $eclass_banner_value == 1): ?>
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                                                    <?php if(get_config('enable_mobileapi')): ?>
                                                        <div class='d-flex gap-3 pe-3'>
                                                            <a href='https://play.google.com/store/apps/details?id=gr.gunet.eclass3' target='_blank' aria-label='Google Play'>
                                                                <img style='width:150px;' src='resources/img/GooglePlay.svg' class='img-responsive center-block m-auto d-block' alt='Get it on Google Play'>
                                                            </a>
                                                            <a href='https://itunes.apple.com/us/app/open-eclass-mobile/id1398319489' target='_blank' aria-label='App Store'>
                                                                <img style='width:150px;' src='resources/img/AppStore.svg' class='img-responsive center-block m-auto d-block' alt='Download on the App Store'>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if($eclass_banner_value == 1): ?>
                                                        <div>
                                                            <a class='banner-link' href="<?php echo get_config('banner_link'); ?>" target="_blank" aria-label='Banner'>
                                                                <img style='width:134px;' src="<?php echo e($logo_img); ?>" alt="This is the banner of platform">
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class='<?php echo e($container); ?> padding-default'>
                            <div class='row row-cols-1 g-4'>
                                <div class='col'>
                                    <div class='card bg-transparent card-transparent border-0'>
                                        <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0 gap-3 flex-wrap'>
                                            <div class='jumbotron-intro-text'>
                                                <?php if(get_config('homepage_title_'.$language_code)): ?>
                                                    <h1 class='eclass-title' aria-label="<?php echo e(trans('langEclass')); ?>"><?php echo e(get_config('homepage_title_'.$language_code)); ?></h1>
                                                <?php endif; ?>

                                                <?php if(get_config('homepage_intro_'.$language_code)): ?>
                                                    <p class='eclassInfo mb-0' aria-label="<?php echo e(trans('langInfo')); ?>"><?php echo get_config('homepage_intro_'.$language_code); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class='card-body px-0'>
                                            <?php if(get_config('enable_mobileapi') || $eclass_banner_value == 1): ?>
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-4">
                                                    <?php if(get_config('enable_mobileapi')): ?>
                                                        <div class='d-flex gap-3 pe-3'>
                                                            <a href='https://play.google.com/store/apps/details?id=gr.gunet.eclass3' target='_blank' aria-label='Google Play'>
                                                                <img style='width:150px;' src='resources/img/GooglePlay.svg' class='img-responsive center-block m-auto d-block' alt='Get it on Google Play'>
                                                            </a>
                                                            <a href='https://itunes.apple.com/us/app/open-eclass-mobile/id1398319489' target='_blank' aria-label='App Store'>
                                                                <img style='width:150px;' src='resources/img/AppStore.svg' class='img-responsive center-block m-auto d-block' alt='Download on the App Store'>
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php if($eclass_banner_value == 1): ?>
                                                        <div>
                                                            <a class='banner-link' href="<?php echo get_config('banner_link'); ?>" target="_blank" aria-label='Banner'>
                                                                <img style='width:134px;' src="<?php echo e($logo_img); ?>" alt="This is the banner of platform">
                                                            </a>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        <?php endif; ?>

        
        <div class='row m-auto'>
            <?php if(!isset($_GET['redirect_home'])): ?>
                <?php if($display_login_form != 0): ?>
                    <!-- only one auth_method is enabled and this method is not eclass -->
                    <?php if(!$authCase): ?>
                        <div class="col-12 order-first homepage-login-container">
                            <div class='<?php echo e($container); ?> padding-default padding-default-form-login'>
                                <div class='row row-cols-1 row-cols-lg-2 g-4'>
                                    <div class="col <?php if($PositionFormLogin or $display_login_form == 1): ?> ms-auto me-auto <?php endif; ?>">

                                        <?php if($auth_enabled_method == 1): ?>
                                            <?php if(count($authLinks) > 0): ?>
                                                <div class='card form-homepage-login border-card h-100 px-lg-4 py-lg-3 p-3'>
                                                    <div class='card-body d-flex justify-content-center align-items-center p-1 p-md-2'>
                                                        <?php $i = 0; ?>
                                                        <div class='w-100 h-100'>
                                                            <div class='col-12 container-pages d-flex align-items-center h-100'>

                                                                <?php $__currentLoopData = $authLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $auth => $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                                    <?php if($i > 0 && $primary_method >= 2 && count($authLinks) == 2): ?>
                                                                        <?php break; ?>
                                                                    <?php endif; ?>

                                                                    <div class="col-12 page <?php if($i == 0): ?> slide-page <?php elseif($i == 1): ?> next-page-1 <?php else: ?> next-page-2 <?php endif; ?> h-100">
                                                                        <div class="row h-100">
                                                                            <div class='col-12 align-self-start'>
                                                                                <div class='d-flex justify-content-between align-items-center flex-wrap gap-2'>
                                                                                    <h2 class='mb-3'>
                                                                                        <?php if(!empty($key['title'])): ?>
                                                                                            <?php echo $key['title']; ?>

                                                                                        <?php else: ?>
                                                                                            <?php echo e(trans('langLogin')); ?>

                                                                                        <?php endif; ?>
                                                                                    </h2>
                                                                                    <?php if(!empty($key['authInstructions'])): ?>
                                                                                        <a href='#' class='text-decoration-underline vsmall-text mb-3' data-bs-toggle='modal' data-bs-target="#authInstruction<?php echo e($key['authId']); ?>">
                                                                                            <?php echo e(trans('langInstructions')); ?>

                                                                                        </a>
                                                                                        <div class='modal fade' id="authInstruction<?php echo e($key['authId']); ?>" tabindex='-1' role='dialog' aria-labelledby='authInstructionLabel' aria-hidden='true'>
                                                                                            <div class='modal-dialog'>
                                                                                                <div class='modal-content'>
                                                                                                    <div class='modal-header'>
                                                                                                        <div class='modal-title' id='authInstructionLabel'><?php echo e(trans('langInstructionsAuth')); ?></div>
                                                                                                        <button type='button' class='close' data-bs-dismiss='modal' aria-label="<?php echo e(trans('langClose')); ?>">
                                                                                                        </button>
                                                                                                    </div>
                                                                                                    <div class='modal-body'>
                                                                                                        <div class='col-12'>
                                                                                                            <div class='alert alert-info'>
                                                                                                                <i class='fa-solid fa-circle-info fa-lg'></i>
                                                                                                                <span><?php echo $key['authInstructions']; ?></span>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    <?php endif; ?>
                                                                                </div>
                                                                            </div>

                                                                            <div class='col-12 align-self-center' <?php if($primary_method >= 3 && count($authLinks) == 2): ?> style="height: 100px; display: flex; align-items:center; justify-content: center;" <?php endif; ?>>
                                                                                <div class='text-center w-100'><?php echo $key['html']; ?></div>
                                                                            </div>



                                                                            <div class='col-12 align-self-end'>
                                                                                <?php if(count($authLinks) > 1): ?>
                                                                                    <div id="or" class='ms-auto me-auto mb-2'><?php echo e(trans('langOr')); ?></div>
                                                                                <?php endif; ?>
                                                                                <?php if(count($authLinks) == 2): ?>
                                                                                    <div class="d-flex justify-content-center align-items-center gap-3 flex-wrap">
                                                                                        <?php echo $authLinks[1]['html']; ?>

                                                                                    </div>
                                                                                <?php endif; ?>

                                                                                <?php if(count($authLinks) >= 3): ?>
                                                                                    <div class="d-flex justify-content-md-between justify-content-center align-items-center gap-3 flex-wrap">

                                                                                            <?php if($i==0): ?>
                                                                                                <button class="btn submitAdminBtn firstNext next">
                                                                                                    <?php if(!empty($authLinks[$i+1]['title'])): ?>
                                                                                                        <?php echo $authLinks[$i+1]['title']; ?>

                                                                                                    <?php else: ?>
                                                                                                        <?php echo e(trans('langLogin')); ?>

                                                                                                    <?php endif; ?>
                                                                                                </button>
                                                                                                <button class="btn submitAdminBtn next-1 next">
                                                                                                    <?php if(!empty($authLinks[$i+2]['title'])): ?>
                                                                                                        <?php echo $authLinks[$i+2]['title']; ?>

                                                                                                    <?php else: ?>
                                                                                                        <?php echo e(trans('langLogin')); ?>

                                                                                                    <?php endif; ?>
                                                                                                </button>

                                                                                            <?php endif; ?>

                                                                                            <?php if($i==1): ?>
                                                                                                <button class="btn submitAdminBtn prev-1 next">
                                                                                                    <?php if(!empty($authLinks[$i-1]['title'])): ?>
                                                                                                        <?php echo $authLinks[$i-1]['title']; ?>

                                                                                                    <?php else: ?>
                                                                                                        <?php echo e(trans('langLogin')); ?>

                                                                                                    <?php endif; ?>
                                                                                                </button>
                                                                                                <button class="btn submitAdminBtn next-2 next">
                                                                                                    <?php if(!empty($authLinks[$i+1]['title'])): ?>
                                                                                                        <?php echo $authLinks[$i+1]['title']; ?>

                                                                                                    <?php else: ?>
                                                                                                        <?php echo e(trans('langLogin')); ?>

                                                                                                    <?php endif; ?>
                                                                                                </button>

                                                                                            <?php endif; ?>

                                                                                            <?php if($i==2): ?>
                                                                                                <button class="btn submitAdminBtn prev-2 next">
                                                                                                    <?php if(!empty($authLinks[$i-1]['title'])): ?>
                                                                                                        <?php echo $authLinks[$i-1]['title']; ?>

                                                                                                    <?php else: ?>
                                                                                                        <?php echo e(trans('langLogin')); ?>

                                                                                                    <?php endif; ?>
                                                                                                </button>
                                                                                                <button class="btn submitAdminBtn next-3 next">
                                                                                                    <?php if(!empty($authLinks[$i-2]['title'])): ?>
                                                                                                        <?php echo $authLinks[$i-2]['title']; ?>

                                                                                                    <?php else: ?>
                                                                                                        <?php echo e(trans('langLogin')); ?>

                                                                                                    <?php endif; ?>
                                                                                                </button>

                                                                                            <?php endif; ?>


                                                                                            <?php if(count($authLinks) > 3): ?>
                                                                                                <div class='col-12'>
                                                                                                    <div id='oreven' class='ms-auto me-auto mb-2'><?php echo e(trans('langOrYet')); ?></div>
                                                                                                </div>

                                                                                                <div class='col-12 d-flex justify-content-center align-items-center'>
                                                                                                    <button type='button' class='btn submitAdminBtn border-0 text-decoration-underline bg-transparent' data-bs-toggle='modal' data-bs-target='#LoginAnotherOption-<?php echo e($i); ?>'>
                                                                                                        <?php if(!empty($authLinks[count($authLinks)-1]['title'])): ?>
                                                                                                            <?php echo $authLinks[count($authLinks)-1]['title']; ?>

                                                                                                        <?php else: ?>
                                                                                                            <?php echo e(trans('langLogin')); ?>

                                                                                                        <?php endif; ?>
                                                                                                    </button>


                                                                                                    <div class='modal fade' id='LoginAnotherOption-<?php echo e($i); ?>' data-bs-backdrop='static' data-bs-keyboard='false' tabindex='-1' aria-labelledby='LoginAnotherOptionLabel-<?php echo e($i); ?>' aria-hidden='true'>
                                                                                                        <div class='modal-dialog'>
                                                                                                            <div class='modal-content'>
                                                                                                                <div class='modal-header'>
                                                                                                                    <div class='modal-title' id='LoginAnotherOptionLabel-<?php echo e($i); ?>'>
                                                                                                                        <?php if(!empty($authLinks[count($authLinks)-1]['title'])): ?>
                                                                                                                            <?php echo $authLinks[count($authLinks)-1]['title']; ?>

                                                                                                                        <?php else: ?>
                                                                                                                            <?php echo e(trans('langLogin')); ?>

                                                                                                                        <?php endif; ?>
                                                                                                                    </div>
                                                                                                                    <button type='button' class='close' data-bs-dismiss='modal' aria-label="<?php echo e(trans('langClose')); ?>">
                                                                                                                    </button>
                                                                                                                </div>
                                                                                                                <div class='modal-body d-flex justify-content-center align-items-center'>
                                                                                                                    <div>
                                                                                                                        <?php echo $authLinks[count($authLinks)-1]['html']; ?>

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
                                                                    <?php $i++; ?>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>

                                        <?php else: ?>
                                            <div class='card cardLogin h-100 p-3'>
                                                <div class='card-body py-1'>
                                                    <h2><?php echo e(trans('langUserLogin')); ?></h2>
                                                    <div class='col-12 mt-3'>
                                                        <div class='alert alert-warning'>
                                                            <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
                                                            <span><?php echo e(trans('langAllAuthMethodsAreDisabled')); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>

                                    </div>

                                    <?php if($display_login_form != 1): ?>
                                        <div class="col card-login-img d-none <?php if($PositionFormLogin): ?> d-lg-none <?php else: ?> d-lg-block <?php endif; ?>"
                                        role="img" aria-label="<?php echo e(trans('langLoginImg')); ?>" style="background: url(<?php echo e($loginIMG); ?>);"></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            
            <?php if($display_login_form != 1): ?>
                <?php if(!get_config('dont_display_announcements')): ?>
                    <div class="col-12 order-<?php echo e($announcements_priority); ?> homepage-annnouncements-container <?php if(get_config('dont_display_login_form')): ?> drop-shadow <?php endif; ?>">
                        <div class='<?php echo e($container); ?> padding-default'>
                            <div class='row row-cols-1 g-4'>
                                <div class='col'>
                                    <div class='card card-transparent bg-transparent border-0'>
                                        <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0 gap-2 flex-wrap'>
                                            <div class='d-flex justify-content-start align-items-center gap-2 flex-wrap'>
                                                <h2 class='text-heading-h3 mb-0'><?php echo e(trans('langAnnouncements')); ?></h2>
                                                <h3 class='mb-0' aria-label='Rss'><a href='<?php echo e($urlServer); ?>rss.php' aria-label='Rss'><i class="fa-solid fa-rss"></i></a></h3>
                                        </div>
                                            <?php if(count($announcements) > 0): ?>
                                                <div class='d-flex justify-content-end align-items-center'>
                                                    <h3 class='mb-0'><a class='TextRegular text-decoration-underline msmall-text mb-2' href="<?php echo e($urlAppend); ?>main/system_announcements.php"><?php echo e(trans('langAllAnnouncements')); ?>...</a></h3>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class='card-body px-0 py-0'>
                                            <?php $counterAn = 0; ?>
                                            <?php if(count($announcements) > 0): ?>

                                                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($counterAn < 6): ?>
                                                            <div class='row mt-4'>
                                                                <div class='col-md-2'>
                                                                    <div class='card card-announcement-date text-center'>
                                                                        <p class='TextBold largest-text'><?php echo date('j', strtotime($announcement->date)); ?></p>
                                                                        <p class='mt-2'>
                                                                            <?php
                                                                                $lg = $_GET['localize'] ?? $language;
                                                                                $string_date = datefmt_format(datefmt_create($lg, IntlDateFormatter::LONG, IntlDateFormatter::NONE, 'Europe/Athens', IntlDateFormatter::TRADITIONAL), strtotime($announcement->date));
                                                                                $finalDate = preg_replace('/^\d+\s+/', '', $string_date);
                                                                            ?>
                                                                            <?php echo $finalDate; ?>

                                                                        </p>
                                                                    </div>
                                                                </div>
                                                                <div class='col-md-10 mt-md-0 mt-2'>
                                                                    <h3 class='mb-0'><a class='TextBold' style='font-size: 16px;' href='<?php echo e($urlAppend); ?>main/system_announcements.php?an_id=<?php echo e($announcement->id); ?>'>
                                                                        <?php echo $announcement->title; ?>

                                                                    </a></h3>
                                                                    <div class='truncate-announcement'><?php echo $announcement->body; ?></div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php $counterAn++; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            <?php else: ?>
                                                <ul class='list-group list-group-flush'>
                                                    <li class='list-group-item element'>
                                                        <div class='TextRegular msmall-text text-content'><?php echo e(trans('langNoAnnouncementsExist')); ?></div>
                                                    </li>
                                                </ul>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>


            
            <?php if($display_login_form != 1): ?>
                <?php if(!get_config('dont_display_popular_courses')): ?>
                    <?php if($popular_courses): ?>
                        <div class='col-12 order-<?php echo e($popular_courses_priority); ?> homepage-popoular-courses-container'>
                            <div class='<?php echo e($container); ?> padding-default'>
                                <div class="row row-cols-1 g-4">
                                    <div class='col'>
                                        <div class='card card-transparent bg-transparent border-0'>
                                            <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0 mb-3'>
                                                <div class='d-flex justify-content-start align-items-center'>
                                                    <h2 class='text-heading-h3 mb-0'>
                                                        <?php echo e(trans('langPopularCourse')); ?>

                                                    </h2>
                                                </div>
                                            </div>
                                            <div class='card-body px-0 py-0'>
                                                <div class='row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-lg-5 g-4'>
                                                    <?php $__currentLoopData = $popular_courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pop_course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="col mb-lg-0 mb-4">
                                                            <div class='card border-card h-100 card-default'>
                                                                <a href='<?php echo e($urlAppend); ?>courses/<?php echo e($pop_course->code); ?>/index.php'>
                                                                    <?php if($pop_course->course_image): ?>
                                                                        <img class='card-img-top popular_course_img' src='<?php echo e($urlAppend); ?>courses/<?php echo e($pop_course->code); ?>/image/<?php echo e($pop_course->course_image); ?>' alt="<?php echo e($pop_course->title); ?>" />
                                                                    <?php else: ?>
                                                                        <?php if($pop_course->is_collaborative): ?>
                                                                            <img class='card-img-top popular_course_img' src='<?php echo e($urlAppend); ?>template/modern/images/default-collaboration.jpg' alt="<?php echo e($pop_course->title); ?>" />
                                                                        <?php else: ?>
                                                                            <img class='card-img-top popular_course_img' src='<?php echo e($urlAppend); ?>resources/img/ph1.jpg' alt="<?php echo e($pop_course->title); ?>" />
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </a>
                                                                <div class='card-body'>
                                                                    <div class="col-12 text-center mt-2 line-height-default">
                                                                        <h3 class='mb-0'>
                                                                            <div class='line-height-default'>
                                                                                <a class='TextBold msmall-text' href='<?php echo e($urlAppend); ?>courses/<?php echo e($pop_course->code); ?>/index.php'>
                                                                                    <?php echo e($pop_course->title); ?> (<?php echo e($pop_course->public_code); ?>)
                                                                                </a>
                                                                            </div>
                                                                        </h3>
                                                                        <p class='TextRegular msmall-text Neutral-900-cl mt-2'><?php echo e($pop_course->prof_names); ?></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>


            
            <?php if($display_login_form != 1): ?>
                <?php if(!get_config('dont_display_texts')): ?>
                    <?php if($texts): ?>
                        <div class='col-12 order-<?php echo e($texts_priority); ?> homepage-texts-container'>
                            <div class='<?php echo e($container); ?> padding-default'>
                                <div class="row row-cols-1 <?php if(count($texts) > 1): ?> row-cols-lg-2 <?php endif; ?> g-4">
                                    <?php $__currentLoopData = $texts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $text): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class='col'>
                                            <div class='card card-transparent bg-transparent border-0'>
                                                <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0'>
                                                    <div class='d-flex justify-content-start align-items-center'>
                                                        <h2 class='text-heading-h3 mb-0'>
                                                            <?php echo $text->title; ?>

                                                        </h2>
                                                    </div>
                                                </div>
                                                <div class='card-body px-0 py-0'>
                                                    <div class='TextRegular msmall-text mt-3'><?php echo $text->body; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

            
            <?php if($display_login_form != 1): ?>
                <?php if(!get_config('dont_display_testimonials') && count($testimonials) > 0): ?>
                    <div class='col-12 order-<?php echo e($testimonials_priority); ?> homepage-testimonials-container'>
                        <div class='<?php echo e($container); ?> padding-default'>
                            <div class="row row-cols-1 g-4">
                                <div class='col'>
                                    <div class='card card-transparent bg-transparent border-0'>
                                        <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0'>
                                            <h2 class='text-heading-h3 mb-0'>
                                                <?php echo get_config('homepage_testimonial_title_'.$language_code); ?>

                                            </h2>
                                        </div>
                                        <div class='card-body px-3'>
                                            <div class="d-flex justify-content-center">
                                                <div class="col-12 testimonials my-0">
                                                    <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="d-flex align-items-start flex-column testimonial">
                                                            <div class="testimonial-body mb-auto">
                                                                <p><?php echo $t->body; ?></p>
                                                            </div>
                                                            <div class="testimonial-person w-100">
                                                                <div class="form-label text-end mt-4"><?php echo $t->title; ?></div>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            
            <?php if($display_login_form != 1): ?>
                <?php if(!get_config('dont_display_statistics')): ?>
                    <div class='col-12 order-<?php echo e($statistics_priority); ?> homepage-statistics-container'>
                        <div class='<?php echo e($container); ?> padding-default'>
                            <div class="row row-cols-1 g-4">
                                <div class='col'>
                                    <div class='card card-transparent bg-transparent border-0'>
                                        <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0'>
                                            <div class='d-flex justify-content-start align-items-center'>
                                                <h2 class='text-heading-h3 mb-0'><?php echo e(trans('langViewStatics')); ?></h2>
                                            </div>
                                        </div>
                                        <div class='card-body px-0 py-3'>
                                            <div class='col-12'>
                                                <div class='row row-cols-1 row-cols-md-3 g-lg-5 g-3'>
                                                    <div class='col mb-lg-0 mb-4'>
                                                        <div class='card statistics-card drop-shadow card-default'>
                                                            <div class='card-body d-flex justify-content-center align-items-center'>
                                                                <div>
                                                                    <div class='d-flex justify-content-center'>
                                                                        <i class="fa-solid fa-book-open fa-xl mt-4 pt-1" role="presentation"></i>
                                                                        <div class='TextBold largest-text mb-0 ms-2'>
                                                                            <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                                                                <?php echo e(number_format(intval(get_config('total_courses')), 0, '', $digit_separator)); ?>

                                                                            <?php else: ?>
                                                                                <?php echo e(number_format(intval($total_collaboration_courses), 0 ,'', $digit_separator)); ?>

                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <p class='form-label text-center'><?php echo e(trans('langCourses')); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='col mb-lg-0 mb-4'>
                                                        <div class='card statistics-card drop-shadow card-default'>
                                                            <div class='card-body d-flex justify-content-center align-items-center'>
                                                                <div>
                                                                    <div class='d-flex justify-content-center'>
                                                                        <i class="fa-solid fa-globe fa-xl mt-4 pt-1" role="presentation"></i>
                                                                        <div class='TextBold largest-text mb-0 ms-2'><?php echo e(number_format(intval(get_config('visits_per_week')), 0, '', $digit_separator)); ?></div>
                                                                    </div>
                                                                    <p class='form-label text-center'><?php echo e(trans('langUserLogins')); ?> <?php echo e(trans('langPerMonth')); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class='col mb-lg-0 mb-4'>
                                                        <div class='card statistics-card drop-shadow card-default'>
                                                            <div class='card-body d-flex justify-content-center align-items-center'>
                                                                <div>
                                                                    <div class='d-flex justify-content-center'>
                                                                        <i class="fa-solid fa-user fa-xl mt-4 pt-1" role="presentation"></i>
                                                                        <div class='TextBold largest-text mb-0 ms-2'><?php echo e(number_format(intval(get_config('users_registered')), 0, '', $digit_separator)); ?></div>
                                                                    </div>
                                                                    <p class='form-label text-center'><?php echo e(trans('langRegisteredUsers')); ?></p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>


            
            <?php if($display_login_form != 1): ?>
                <?php if(!get_config('dont_display_open_courses')): ?>
                    <?php if(get_config('opencourses_enable') && ((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform))): ?>
                        <div class='col-12 order-<?php echo e($open_courses_priority); ?> homepage-opencourses-container'>
                            <div class='<?php echo e($container); ?> padding-default'>
                                <div class='row row-cols-1 g-4'>
                                    <div class='col'>
                                        <?php if($openCoursesExtraHTML): ?>
                                            <?php echo $openCoursesExtraHTML; ?>

                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>

        </div>
</div>


<script>

    $('.testimonials').slick({
		autoplay:true,
		autoplaySpeed:4000,
		centerMode: true,
		slidesToShow: 1,
		responsive: [
            {
                breakpoint: 768,
                settings: { centerPadding: '0vw' }
		    },
            {
                breakpoint: 2561,
                settings: { centerPadding: '15vw' }
		    },
            {
                breakpoint: 3561,
                settings: { centerPadding: '10vw' }
		    },
            {
                breakpoint: 4561,
                settings: { centerPadding: '7vw' }
		    },
            {
                breakpoint: 5561,
                settings: { centerPadding: '5vw' }
		    },
            {
                breakpoint: 10000,
                settings: { centerPadding: '3vw' }
		    }
        ]
	});


    if($('#showWarningModal').val() == 1){
        var myModal = new bootstrap.Modal(document.getElementById('WarningModal'));
        myModal.show();
    }


</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/home/index.blade.php ENDPATH**/ ?>