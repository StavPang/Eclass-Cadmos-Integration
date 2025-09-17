<header>
    <div id="bgr-cheat-header" class="navbar navbar-eclass py-0 fixed-top">
        <div class='<?php echo e($container); ?> header-container py-0'>


            <div class='d-none d-lg-block w-100 header-large-screen'>
                <div class='col-12 h-100 d-flex justify-content-between align-items-center gap-5'>
                    <nav class='d-flex justify-content-start align-items-center h-100'>
                        <a class='me-lg-4 me-xl-5' href="<?php if($_SESSION['provider'] !== 'lti_publish'): ?><?php echo e($urlAppend); ?><?php endif; ?>" aria-label="<?php echo e(trans('langHomePage')); ?>">
                            <img class="eclass-nav-icon m-auto d-block" src="<?php echo e($logo_img); ?>" alt="<?php echo e(trans('langLogo')); ?>"/>
                        </a>

                        <?php if($_SESSION['provider'] !== 'lti_publish'): ?>
                        <ul class="container-items nav">
                            <?php if(!get_config('hide_login_link')): ?>
                                <li class="nav-item">
                                    <a id="link-home" class="nav-link menu-item mx-lg-2 <?php if(!isset($_SESSION['uid']) && empty($pageName)): ?> active2 <?php endif; ?>" href="<?php echo e($urlServer); ?>?show_home=true">
                                        <?php echo e(trans('langHome')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(!isset($_SESSION['uid'])): ?>
                                <li class="nav-item">
                                    <a id="link-register" class="nav-link menu-item mx-lg-2 <?php if(get_config('registration_link')=='hide'): ?> d-none <?php endif; ?>" href="<?php echo e($urlServer); ?>modules/auth/registration.php">
                                        <?php echo e(trans('langRegistration')); ?>

                                    </a>
                                </li>
                                <?php if(!get_config('dont_display_courses_menu')): ?>
                                    <li class="nav-item">
                                        <a id="link-lessons" class="nav-link menu-item mx-lg-2" href="<?php echo e($urlServer); ?>modules/auth/listfaculties.php">
                                            <?php echo e(trans('langCourses')); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if(isset($_SESSION['uid'])): ?>
                                <li class="nav-item">
                                    <a id="link-portfolio" class="nav-link menu-item mx-lg-2" href="<?php echo e($urlServer); ?>main/portfolio.php">
                                        <?php echo e(trans('langPortfolio')); ?>

                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a id="link-lessons" class="nav-link menu-item mx-lg-2" href="<?php echo e($urlServer); ?>modules/auth/courses.php">
                                        <?php echo e(trans('langCourses')); ?>

                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if(!get_config('dont_display_faq_menu')): ?>
                                <?php if(faq_exist()): ?>
                                    <li class="nav-item">
                                        <a id="link-faq" class="nav-link menu-item mx-lg-2 " href="<?php echo e($urlAppend); ?>info/faq.php">
                                            <?php echo e(trans('langFaqAbbrev')); ?>

                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                        </ul>
                        <?php endif; ?>
                    </nav>
                    <div class='d-flex justify-content-end align-items-center h-100 pe-0 gap-3'>
                        <?php if(get_config('enable_search')): ?>
                            <div class='h-100 d-flex justify-content-start align-items-center'>
                                <div class='h-40px'>
                                    <?php if(isset($course_code) and $course_code): ?>
                                        <form id='submitSearch' class="d-flex justify-content-start align-items-center h-40px gap-2" action='<?php echo e($urlAppend); ?>modules/search/search_incourse.php?all=true' method='post' role='search'>
                                    <?php else: ?>
                                        <form id='submitSearch' class="d-flex justify-content-start align-items-center h-40px gap-2" action='<?php echo e($urlAppend); ?>modules/search/search.php' method='post' role='search'>
                                    <?php endif; ?>
                                    <div>
                                        <a id="btn-search" role="button" class="btn d-flex justify-content-center align-items-center bg-transparent border-0 p-0 rounded-0" name="quickSearch" aria-label="<?php echo e(trans('langSearch')); ?>">
                                            <i class="fa-solid fa-magnifying-glass fa-lg"></i>
                                        </a>
                                    </div>
                                    <input id="search_terms" type="text" class="inputSearch form-control rounded-0 px-0" placeholder='<?php echo e(trans('langSearch')); ?>...' name="search_terms" aria-label="<?php echo e(trans('langSearch')); ?>"/>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(!isset($_SESSION['uid']) && count($session->active_ui_languages) > 1): ?>
                            <div class='h-40 d-flex justify-content-start align-items-center split-left'>
                                <div class="d-flex justify-content-start align-items-center h-40px">
                                    <?php echo lang_selections_Desktop('idLangSelectionDesktop'); ?>

                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if(isset($_SESSION['uid']) && get_config('enable_search')): ?>
                            <div class='split-content'></div>
                        <?php endif; ?>
                        <div class='user-menu-content h-100 d-flex justify-content-start align-items-center'>
                            <div class='d-flex justify-content-start align-items-center h-80px'>
                                <?php if(!isset($_SESSION['uid']) and !get_config('dont_display_login_link')): ?>
                                    <div class='d-flex justify-content-center align-items-center split-left h-40px'>
                                        <?php if($authCase): ?>
                                            <?php if(!empty($authNameEnabled)): ?>
                                                <?php if($authNameEnabled == 'cas'): ?>
                                                    <a class='header-login-text' href="<?php echo e($urlServer); ?>modules/auth/cas.php">
                                                        <?php echo e(trans('langUserLogin')); ?>

                                                    </a>
                                                <?php else: ?>
                                                    <a class='header-login-text' href="<?php echo e($urlServer); ?>secure/">
                                                        <?php echo e(trans('langUserLogin')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <a class='header-login-text' href="<?php echo e($urlServer); ?>main/login_form.php">
                                                <?php echo e(trans('langUserLogin')); ?>

                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php elseif(!isset($_SESSION['uid']) and !get_config('dont_display_login_link')): ?>
                                    <?php if(!empty($authNameEnabled)): ?>
                                        <div class='d-flex justify-content-center align-items-center split-left h-40px'>
                                            <?php if($authNameEnabled == 'cas'): ?>
                                                <a class='header-login-text' href="<?php echo e($urlServer); ?>modules/auth/cas.php">
                                                    <?php echo e(trans('langUserLogin')); ?>

                                                </a>
                                            <?php else: ?>
                                                <a class='header-login-text' href="<?php echo e($urlServer); ?>secure/">
                                                    <?php echo e(trans('langUserLogin')); ?>

                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if(isset($_SESSION['uid'])): ?>
                                    <div class='d-flex justify-content-end p-0 h-80px'>
                                        <div class="btn-group" role="group" aria-label="<?php echo e(trans('langMenu')); ?>">
                                            <div class="btn-group" role="group">
                                                <?php if($_SESSION['provider'] !== 'lti_publish'): ?>
                                                <button id="btnGroupDrop1" type="button" class="btn user-menu-btn rounded-0 d-flex justify-content-center align-items-center gap-2 rounded-0" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <?php if(user_icon($_SESSION['uid'], IMAGESIZE_LARGE, true) !== false): ?>
                                                            <img class="user-icon-filename" src="<?php echo e(user_icon($_SESSION['uid'], IMAGESIZE_LARGE)); ?>" alt="<?php echo e(trans('langUser')); ?>:<?php echo e($uname); ?>">
                                                        <?php else: ?>
                                                            <span class='name-initials TextBold fs-6'>
                                                                <?php echo e(isset($_SESSION['givenname']) ? mb_strtoupper(mb_substr(trim($_SESSION['givenname']), 0, 1, 'UTF-8'), 'UTF-8') : ''); ?>

                                                                <?php echo e(isset($_SESSION['surname']) ? mb_strtoupper(mb_substr(trim($_SESSION['surname']), 0, 1, 'UTF-8'), 'UTF-8') : ''); ?>

                                                            </span>
                                                        <?php endif; ?>
                                                            <i class="fa-solid fa-chevron-down ms-1"></i>
                                                </button>
                                                <div class="m-0 p-3 dropdown-menu dropdown-menu-end contextual-menu contextual-menu-user" aria-labelledby="btnGroupDrop1">
                                                    <ul class="list-group list-group-flush">

                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-start gap-2 px-1 pe-none">
                                                                <img class="user-icon-filename" src="<?php echo e(user_icon($_SESSION['uid'], IMAGESIZE_LARGE)); ?>" alt="<?php echo e(trans('langUser')); ?>:<?php echo e($uname); ?>">
                                                                <div>
                                                                    <h4 class='truncate-text username-text mb-0'><?php echo e($_SESSION['givenname']); ?>&nbsp;<?php echo e($_SESSION['surname']); ?></h4>
                                                                    <p class='small-text username-paragraph'><?php echo e($_SESSION['uname']); ?></p>
                                                                </div>

                                                            </a>
                                                        </li>
                                                        <?php if((isset($is_admin) and $is_admin) or
                                                            (isset($is_power_user) and $is_power_user) or
                                                            (isset($is_usermanage_user) and ($is_usermanage_user)) or
                                                            (isset($is_departmentmanage_user) and $is_departmentmanage_user)): ?>
                                                            <li>
                                                                <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0"
                                                                        href="<?php echo e($urlAppend); ?>modules/admin/index.php">
                                                                        <i class="fa-solid fa-gear settings-icons"></i>
                                                                        <?php echo e(trans('langAdminTool')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if($_SESSION['status'] == USER_TEACHER or $is_power_user or $is_departmentmanage_user): ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>modules/create_course/create_course.php">
                                                                <i class="fa-solid fa-circle-plus settings-icons"></i>
                                                                <?php echo e(trans('langCourseCreate')); ?>

                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/portfolio.php">
                                                                <i class="fa-solid fa-house settings-icons"></i>
                                                                <?php echo e(trans('langMyPortfolio')); ?>

                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/profile/display_profile.php">
                                                                <i class="fa-solid fa-user settings-icons"></i>
                                                                <?php echo e(trans('langMyProfile')); ?>

                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/my_courses.php">
                                                                <i class="fa-solid fa-book-open settings-icons"></i>
                                                                <?php echo e(trans('langMyCourses')); ?>

                                                            </a>
                                                        </li>
                                                        <?php if($_SESSION['status'] == USER_STUDENT && get_config('eclass_prof_reg')): ?>
                                                            <li>
                                                                <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>modules/auth/formuser.php">
                                                                    <i class="fa-regular fa-hand"></i>
                                                                    <?php echo e(trans('langMyRequests')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>modules/announcements/myannouncements.php">
                                                                <i class="fa-regular fa-bell settings-icons"></i>
                                                                <?php echo e(trans('langMyAnnouncements')); ?>

                                                            </a>
                                                        </li>
                                                        <?php if(get_config('enable_quick_note')): ?>
                                                            <li>
                                                                <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/notes/index.php">
                                                                    <i class="fa-regular fa-file-lines settings-icons"></i>
                                                                    <?php echo e(trans('langNotes')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if(get_config('eportfolio_enable')): ?>
                                                            <li>
                                                                <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/eportfolio/index.php?id=<?php echo e($uid); ?>&token=<?php echo e(token_generate('eportfolio'.$uid)); ?>">
                                                                    <i class="fa-regular fa-address-card settings-icons"></i>
                                                                    <?php echo e(trans('langMyePortfolio')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>modules/usage/index.php?t=u">
                                                                <i class="fa-solid fa-chart-line settings-icons"></i>
                                                                <?php echo e(trans('langMyStats')); ?>

                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                                        <?php if(get_config('personal_blog')): ?>
                                                            <li>
                                                                <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>modules/blog/index.php?user_id=<?php echo e($uid); ?>&token=<?php echo e(token_generate('personal_blog'.$uid)); ?>">
                                                                    <i class="fa-solid fa-globe settings-icons"></i>
                                                                    <?php echo e(trans('langMyBlog')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <?php endif; ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>modules/message/index.php">
                                                                <i class="fa-regular fa-envelope settings-icons"></i>
                                                                <?php echo e(trans('langMyDropBox')); ?>

                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/personal_calendar/index.php">
                                                                <i class="fa-regular fa-calendar settings-icons"></i>
                                                                <?php echo e(trans('langMyAgenda')); ?>

                                                            </a>
                                                        </li>
                                                        <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/my_widgets.php">
                                                                <i class="fa-solid fa-layer-group settings-icons"></i>
                                                                <?php echo e(trans('langMyWidgets')); ?>

                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/gradebookUserTotal/index.php">
                                                                <i class="fa-solid fa-a settings-icons"></i>
                                                                <?php echo e(trans('langGradeTotal')); ?>

                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                                        <li>
                                                            <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/mycertificates.php">
                                                                <i class="fa-solid fa-award settings-icons"></i>
                                                                <?php echo e(trans('langMyCertificates')); ?>

                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if(($session->status == USER_TEACHER and get_config('mydocs_teacher_enable')) or ($session->status == USER_STUDENT and get_config('mydocs_student_enable')) or ($session->status == ADMIN_USER and get_config('mydocs_teacher_enable'))): ?>
                                                            <li>
                                                                <a class="list-group-item d-flex justify-content-start align-items-center gap-2 py-0" href="<?php echo e($urlAppend); ?>main/mydocs/index.php">
                                                                    <i class="fa-regular fa-file settings-icons"></i>
                                                                    <?php echo e(trans('langMyDocs')); ?>

                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li>
                                                            <form method='post' action='<?php echo e($urlAppend); ?>modules/auth/logout.php' style='height:40px;'>
                                                                <input type='hidden' name='token' value='<?php echo e($_SESSION['csrf_token']); ?>'>
                                                                <button class='list-group-item d-flex justify-content-start align-items-center gap-2 py-0 w-100 h-100 text-end rounded-0 logout-list-item' type='submit' name='submit'>
                                                                    <i class="fa-solid fa-arrow-right-from-bracket Accent-200-cl "></i>
                                                                    <span class='Accent-200-cl TextBold'><?php echo e(trans('langLogout2')); ?></span>
                                                                </button>
                                                            </form>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <?php else: ?>
                                                <div id="lti_menu_btn" class="rounded-0 d-flex justify-content-center align-items-center gap-2 rounded-0">
                                                    <img class="user-icon-filename" src="<?php echo e(user_icon($_SESSION['uid'], IMAGESIZE_LARGE)); ?>" alt="<?php echo e(trans('langUser')); ?>:<?php echo e($uname); ?>">
                                                    <div class="pt-1 pb-1">
                                                        <span class='TextBold user-name fs-6'>
                                                            <?php echo e(isset($_SESSION['givenname']) ? mb_strtoupper(mb_substr(trim($_SESSION['givenname']), 0, 1, 'UTF-8'), 'UTF-8') : ''); ?>

                                                            <?php echo e(isset($_SESSION['surname']) ? mb_strtoupper(mb_substr(trim($_SESSION['surname']), 0, 1, 'UTF-8'), 'UTF-8') : ''); ?>

                                                        </span>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>




            <div class='d-block d-lg-none w-100 header-small-screen'>
                <div class='col-12 h-100 d-flex justify-content-between align-items-center'>

                    <div class='d-flex justify-content-start align-items-center gap-2'>

                        <a class="p-0 small-basic-size d-flex justify-content-center align-items-center link-bars-options" type="button" data-bs-toggle="offcanvas" href="#offcanvasScrollingTools" aria-controls="offcanvasScrollingTools" aria-label="<?php echo e(trans('langCoursesAndRegistration')); ?>">
                            <i class="fa-solid fa-ellipsis-vertical fa-lg"></i>
                        </a>

                        <a class='d-flex justify-content-start align-items-center' type="button" href="<?php echo e($urlServer); ?>" aria-label="<?php echo e(trans('langHomePage')); ?>">
                            <img class="eclass-nav-icon px-2 bg-transparent" src="<?php echo e($logo_img_small); ?>" alt="<?php echo e(trans('langLogo')); ?>">
                        </a>
                    </div>

                    <?php if(!isset($_SESSION['uid'])): ?>
                        <div class='d-flex justify-content-start align-items-center gap-3'>
                            <?php echo lang_selections_Desktop('idLangSelectionMobile'); ?>

                            <?php if(!get_config('dont_display_login_link')): ?>
                                <a class='header-login-text' href="<?php echo e($urlAppend); ?>main/login_form.php">
                                    <?php echo e(trans('langUserLogin')); ?>

                                </a>
                            <?php elseif(get_config('dont_display_login_link') and !empty($authNameEnabled)): ?>
                                <a class='header-login-text' href="<?php echo e($urlAppend); ?>main/login_form.php">
                                    <?php echo e(trans('langUserLogin')); ?>

                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>


                    <?php if(isset($_SESSION['uid'])): ?>
                        <div>
                            <button class="btn btn-transparent p-0 dropdown-toogle d-flex justify-content-end align-items-center" type="button"
                                    id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <?php if(user_icon($_SESSION['uid'], IMAGESIZE_LARGE,true) !== false): ?>
                                    <img class="user-icon-filename mt-0" src="<?php echo e(user_icon($_SESSION['uid'], IMAGESIZE_LARGE)); ?>" alt="<?php echo e(trans('langUser')); ?>:<?php echo e($uname); ?>">
                                <?php else: ?>
                                    <span class='name-initials TextBold fs-6'>
                                        <?php echo e(isset($_SESSION['givenname']) ? mb_strtoupper(mb_substr(trim($_SESSION['givenname']), 0, 1, 'UTF-8'), 'UTF-8') : ''); ?>

                                        <?php echo e(isset($_SESSION['surname']) ? mb_strtoupper(mb_substr(trim($_SESSION['surname']), 0, 1, 'UTF-8'), 'UTF-8') : ''); ?>

                                    </span>
                                <?php endif; ?>
                            </button>

                            <div class="m-0 py-3 px-3 dropdown-menu dropdown-menu-end contextual-menu contextual-menu-user contextual-border" aria-labelledby="dropdownMenuButton1">
                                <ul class="list-group list-group-flush dropdown_menu_user">
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start gap-2 py-2 px-2 pe-none">
                                            <img class="user-icon-filename" src="<?php echo e(user_icon($_SESSION['uid'], IMAGESIZE_LARGE)); ?>" alt="<?php echo e(trans('langUser')); ?>:<?php echo e($uname); ?>">
                                            <div>
                                                <h4 class='truncate-text username-text mb-0'><?php echo e($_SESSION['givenname']); ?>&nbsp;<?php echo e($_SESSION['surname']); ?></h4>
                                                <p class='small-text username-paragraph'><?php echo e($_SESSION['uname']); ?></p>
                                            </div>

                                        </a>
                                    </li>
                                    <?php if((isset($is_admin) and $is_admin) or
                                        (isset($is_power_user) and $is_power_user) or
                                        (isset($is_usermanage_user) and ($is_usermanage_user)) or
                                        (isset($is_departmentmanage_user) and $is_departmentmanage_user)): ?>
                                        <li>
                                            <a type="button" class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>modules/admin/index.php"><i class="fa-solid fa-gear settings-icons"></i><?php echo e(trans('langAdminTool')); ?></a>
                                        </li>
                                    <?php endif; ?>

                                    <?php if($_SESSION['status'] == USER_TEACHER or $is_power_user or $is_departmentmanage_user): ?>
                                        <li><a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>modules/create_course/create_course.php"><i class="fa-solid fa-circle-plus settings-icons"></i><?php echo e(trans('langCourseCreate')); ?></a></li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/portfolio.php"><i class="fa-solid fa-house settings-icons"></i><?php echo e(trans('langMyPortfolio')); ?></a>
                                    </li>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/my_courses.php"><i class="fa-solid fa-book-open settings-icons"></i><?php echo e(trans('langMyCoursesSide')); ?></a>
                                    </li>
                                    <?php if($_SESSION['status'] == USER_STUDENT && get_config('eclass_prof_reg')): ?>
                                        <li>
                                            <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>modules/auth/formuser.php"><i class="fa-regular fa-hand"></i><?php echo e(trans('langMyRequests')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>modules/announcements/myannouncements.php"><i class="fa-regular fa-bell settings-icons"></i><?php echo e(trans('langMyAnnouncements')); ?></a>
                                    </li>
                                    <?php if(get_config('enable_quick_note')): ?>
                                        <li>
                                            <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/notes/index.php"><i class="fa-regular fa-file-lines settings-icons"></i><?php echo e(trans('langNotes')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(get_config('eportfolio_enable')): ?>
                                        <li>
                                            <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/eportfolio/index.php?id=<?php echo e($uid); ?>&token=<?php echo e(token_generate('eportfolio'.$uid)); ?>"><i class="fa-regular fa-address-card settings-icons"></i><?php echo e(trans('langMyePortfolio')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                        <li>
                                            <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>modules/usage/index.php?t=u"><i class="fa-solid fa-chart-line settings-icons"></i><?php echo e(trans('langMyStats')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if(get_config('personal_blog')): ?>
                                        <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                            <li>
                                                <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>modules/blog/index.php?user_id=<?php echo e($uid); ?>&token=<?php echo e(token_generate('personal_blog'.$uid)); ?>"><i class="fa-solid fa-globe settings-icons"></i><?php echo e(trans('langMyBlog')); ?></a>
                                            </li>
                                       <?php endif; ?>
                                    <?php endif; ?>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>modules/message/index.php"><i class="fa-regular fa-envelope settings-icons"></i><?php echo e(trans('langMyDropBox')); ?></a>
                                    </li>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/personal_calendar/index.php"><i class="fa-regular fa-calendar settings-icons"></i><?php echo e(trans('langMyAgenda')); ?></a>
                                    </li>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/profile/display_profile.php"><i class="fa-solid fa-user settings-icons"></i> <?php echo e(trans('langMyProfile')); ?></a>
                                    </li>
                                    <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/my_widgets.php"><i class="fa-solid fa-layer-group settings-icons"></i> <?php echo e(trans('langMyWidgets')); ?></a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/gradebookUserTotal/index.php"><i class="fa-solid fa-a settings-icons"></i> <?php echo e(trans('langGradeTotal')); ?></a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if((isset($collaboration_platform) and !$collaboration_platform) or is_null($collaboration_platform)): ?>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/mycertificates.php"><i class="fa-solid fa-award settings-icons"></i> <?php echo e(trans('langMyCertificates')); ?></a>
                                    </li>
                                    <?php endif; ?>
                                    <?php if(($session->status == USER_TEACHER and get_config('mydocs_teacher_enable')) or ($session->status == USER_STUDENT and get_config('mydocs_student_enable')) or ($session->status == ADMIN_USER and get_config('mydocs_teacher_enable'))): ?>
                                    <li>
                                        <a class="list-group-item d-flex justify-content-start align-items-start py-3 gap-2" href="<?php echo e($urlAppend); ?>main/mydocs/index.php"><i class="fa-regular fa-file settings-icons"></i> <?php echo e(trans('langMyDocs')); ?></a>
                                    </li>
                                    <?php endif; ?>
                                    <li>
                                        <form method='post' action='<?php echo e($urlAppend); ?>modules/auth/logout.php' style='height:49px;'>
                                            <input type='hidden' name='token' value='<?php echo e($_SESSION['csrf_token']); ?>'>
                                            <button type='submit' class='list-group-item d-flex justify-content-start align-items-center py-3 w-100 text-end gap-2 logout-list-item' name='submit'><i class="fa-solid fa-arrow-right-from-bracket Accent-200-cl"></i>
                                            <span class='Accent-200-cl TextBold'><?php echo e(trans('langLogout2')); ?></span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>






            <div class="offcanvas offcanvas-start d-lg-none offCanvas-Tools" tabindex="-1" id="offcanvasScrollingTools">
                <div class="offcanvas-header">
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php echo e(trans('langClose')); ?>"></button>
                </div>
                <div class="offcanvas-body px-4">
                    <div class='col-12 d-flex justify-content-center align-items-center' aria-label="<?php echo e(trans('langLogo')); ?>">
                        <img src="<?php echo e($logo_img_small); ?>" alt="<?php echo e(trans('langLogo')); ?>">
                    </div>
                    <?php if(get_config('enable_search')): ?>
                        <div class='col-12 mt-5'>
                            <?php if(isset($course_code) and $course_code): ?>
                                <form action="<?php echo e($urlAppend); ?>modules/search/search_incourse.php?all=true">
                            <?php else: ?>
                                <form action="<?php echo e($urlAppend); ?>modules/search/search.php">
                            <?php endif; ?>
                                    <div class="input-group gap-2">
                                        <input id='search-mobile' type="text" class="form-control mt-0 rounded-2"
                                                placeholder="<?php echo e(trans('langSearch')); ?>..." name="search_terms" aria-label="<?php echo e(trans('langSearch')); ?>">
                                        <button class="btn btn-primary btn-mobile-quick-search rounded-2" type="submit" id="search-btn-mobile" name="quickSearch" aria-label="<?php echo e(trans('langSearch')); ?>">
                                            <i class="fa-solid fa-magnifying-glass-arrow-right fa-lg"></i>
                                        </button>
                                    </div>

                                </form>
                        </div>
                    <?php endif; ?>
                    <div class='col-12 mt-5'>

                            <?php if(!get_config('hide_login_link')): ?>
                                <p class='py-2 px-0'>
                                    <a id='homeId' class='header-mobile-link d-flex justify-content-start align-items-start gap-2 flex-wrap TextBold' type='button' href="<?php echo e($urlServer); ?>?show_home=true" aria-label="<?php echo e(trans('langHomePage')); ?>">
                                        <i class="fa-solid fa-home"></i><?php echo e(trans('langHome')); ?>

                                    </a>
                                </p>
                            <?php endif; ?>
                            <?php if(!isset($_SESSION['uid'])): ?>
                                <?php if(get_config('registration_link')!='hide'): ?>
                                    <p class='py-2 px-0'>
                                        <a id='registrationId' type="button" class='header-mobile-link d-flex justify-content-start align-items-start gap-2 flex-wrap TextBold' href="<?php echo e($urlAppend); ?>modules/auth/registration.php" aria-label='Registration'>
                                            <i class="fa-solid fa-pencil"></i><?php echo e(trans('langRegistration')); ?>

                                        </a>
                                    </p>
                                <?php endif; ?>
                                <?php if(!get_config('dont_display_courses_menu')): ?>
                                    <p class='py-2 px-0'>
                                        <a id='coursesId' type='button' class='header-mobile-link d-flex justify-content-start align-items-start gap-2 flex-wrap TextBold' href="<?php echo e($urlAppend); ?>modules/auth/listfaculties.php" aria-label="<?php echo e(trans('langOtherCourses')); ?>">
                                            <i class="fa-solid fa-book"></i><?php echo e(trans('langCourses')); ?>

                                        </a>
                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if(isset($_SESSION['uid'])): ?>
                                <p class='py-2 px-0'>
                                    <a id='portfolioId' type="button" class='header-mobile-link d-flex justify-content-start align-items-start gap-2 flex-wrap TextBold' href="<?php echo e($urlAppend); ?>main/portfolio.php" aria-label="<?php echo e(trans('langRegistration')); ?>">
                                        <i class="fa-solid fa-pencil"></i><?php echo e(trans('langPortfolio')); ?>

                                    </a>
                                </p>
                                <p class='py-2 px-0'>
                                    <a id='coursesId' type='button' class='header-mobile-link d-flex justify-content-start align-items-start gap-2 flex-wrap TextBold' href="<?php echo e($urlAppend); ?>modules/auth/courses.php" aria-label="<?php echo e(trans('langOtherCourses')); ?>">
                                        <i class="fa-solid fa-book"></i><?php echo e(trans('langCourses')); ?>

                                    </a>
                                </p>
                            <?php endif; ?>
                            <?php if(!get_config('dont_display_faq_menu')): ?>
                                <?php if(faq_exist()): ?>
                                    <p class='py-2 px-0'>
                                        <a id='faqId' type='button' class='header-mobile-link d-flex justify-content-start align-items-start gap-2 flex-wrap TextBold' href="<?php echo e($urlAppend); ?>info/faq.php" aria-label="<?php echo e(trans('langFaq')); ?>">
                                            <i class="fa-solid fa-question-circle"></i><?php echo e(trans('langFaq')); ?>

                                        </a>
                                    </p>
                                <?php endif; ?>
                            <?php endif; ?>

                    </div>

                </div>
            </div>

        </div>
    </div>
</header>

<script>
    let current_url = document.URL;

    localStorage.setItem("menu-item", "homepage");

    if(current_url.includes('/?redirect_home')){
        localStorage.setItem("menu-item", "homepage");
    }
    if(current_url.includes('/modules/auth/registration.php')
       || current_url.includes('/modules/auth/formuser.php')
       || current_url.includes('/modules/auth/newuser.php')
       || current_url.includes('/modules/auth/altnewuser.php')){
        localStorage.setItem("menu-item", "register");
    }
    if(current_url.includes('/modules/auth/courses.php')
        || current_url.includes('/modules/auth/listfaculties.php')
        || current_url.includes('/modules/auth/courses.php')){
        localStorage.setItem("menu-item", "lessons");
    }
    if(current_url.includes('/main/portfolio.php')){
        localStorage.setItem("menu-item", "portfolio");
    }
    if(current_url.includes('/info/faq.php')){
        localStorage.setItem("menu-item", "faq");
    }
    if(!current_url.includes('/modules/auth/registration.php')
       && !current_url.includes('/modules/auth/formuser.php')
       && !current_url.includes('/modules/auth/newuser.php')
       && !current_url.includes('/modules/auth/altnewuser.php')
       && !current_url.includes('/modules/auth/courses.php')
       && !current_url.includes('/modules/auth/listfaculties.php')
       && !current_url.includes('/modules/auth/courses.php')
       && !current_url.includes('/main/portfolio.php')
       && !current_url.includes('/info/faq.php')
       && !current_url.includes('/?redirect_home')){
            localStorage.setItem("menu-item", "none");
    }



    if(localStorage.getItem("menu-item") == "homepage"){
        $('#link-home').addClass('active');
    }
    if(localStorage.getItem("menu-item") == "register"){
        $('#link-register').addClass('active');
    }
    if(localStorage.getItem("menu-item") == "portfolio"){
        $('#link-portfolio').addClass('active');
    }
    if(localStorage.getItem("menu-item") == "lessons"){
        $('#link-lessons').addClass('active');
    }
    if(localStorage.getItem("menu-item") == "faq"){
        $('#link-faq').addClass('active');
    }

    if($('#link-register').hasClass('active') || $('#link-portfolio').hasClass('active') || $('#link-lessons').hasClass('active') || $('#link-faq').hasClass('active')){
        $('#link-home').removeClass('active2');
    }

</script>



<script type='text/javascript'>
    $(document).ready(function() {

        $('.inputSearch').on('focus',function(){
            $('.container-items').addClass('d-none');
        });
        $('#btn-search').on('click',function(){
            setTimeout(function () {
                $('.container-items').removeClass('d-none');
            }, 500);
            setTimeout(function () {
                $('#submitSearch').submit();
            }, 200);
        });
        $(".inputSearch").focusout(function(){
            setTimeout(function () {
                $('.container-items').removeClass('d-none');
            }, 500);

        });
    });
</script>
<?php /**PATH /var/www/html/resources/views/layouts/partials/navheadDesktop.blade.php ENDPATH**/ ?>