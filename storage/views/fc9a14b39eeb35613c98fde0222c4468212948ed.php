<!DOCTYPE HTML>
<html lang="<?php echo e($language); ?>">
<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

    <title><?php echo e($pageTitle); ?></title>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    
    <link rel="shortcut icon" href="<?php echo e($favicon_img); ?>" />
    <link rel="apple-touch-icon-precomposed" href="<?php echo e($favicon_img); ?>" />
    <link rel="icon" type="image/png" href="<?php echo e($favicon_img); ?>" />

    
    <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>template/modern/css/bootstrap.min.css?v=<?php echo e($cache_suffix); ?>"/>

    
    
    <link href="<?php echo e($urlAppend); ?>template/modern/css/font-awesome-6.4.0/css/all.css?v=<?php echo e($cache_suffix); ?>" rel="stylesheet"/>

    
    <link href="<?php echo e($urlAppend); ?>template/modern/css/fonts_all/typography.css?v=<?php echo e($cache_suffix); ?>" rel="stylesheet"/>

    
    <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>js/fullcalendar/fullcalendar.css?v=<?php echo e($cache_suffix); ?>"/>

    
    <link rel="stylesheet" href="<?php echo e($urlAppend); ?>template/modern/css/jquery.dataTables.min.css?v=<?php echo e($cache_suffix); ?>"/>

    
    <link rel="stylesheet" href="<?php echo e($urlAppend); ?>template/modern/css/owl-carousel.css?v=<?php echo e($cache_suffix); ?>"/>
    <link rel="stylesheet" href="<?php echo e($urlAppend); ?>template/modern/css/owl-theme-default.css?v=<?php echo e($cache_suffix); ?>"/>

    
    <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>template/modern/css/slick.css?v=<?php echo e($cache_suffix); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>template/modern/css/slick-theme.css?v=<?php echo e($cache_suffix); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>template/modern/css/sidebar.css?v=<?php echo e($cache_suffix); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>template/modern/css/new_calendar.css?v=<?php echo e($cache_suffix); ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>template/modern/css/default.css?v=<?php echo e($cache_suffix); ?>"/>

    <?php echo $__env->yieldPushContent('head_styles'); ?>

    
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/jquery-3.6.0.min.js"></script>

    
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/bootstrap.bundle.min.js?v=<?php echo e($cache_suffix); ?>"></script>

    
    <script src="<?php echo e($urlAppend); ?>js/jquery.dataTables.min.js"></script>
    <script src="<?php echo e($urlAppend); ?>js/classic-ckeditor.js"></script>

    
    <script src="<?php echo e($urlAppend); ?>js/bootbox/bootboxV6.min.js"></script>
    
    <script src="<?php echo e($urlAppend); ?>js/jquery.slimscroll.min.js"></script>
    
    <script src="<?php echo e($urlAppend); ?>js/blockui-master/jquery.blockUI.js"></script>
    
    <script src="<?php echo e($urlAppend); ?>js/tinymce/tinymce.min.js"></script>
    
    <script src="<?php echo e($urlAppend); ?>js/screenfull/screenfull.min.js"></script>
    
    <script src="<?php echo e($urlAppend); ?>js/clipboard.js/clipboard.min.js"></script>
    
    <script src="<?php echo e($urlAppend); ?>js/fullcalendar/moment.min.js"></script>
    <script src="<?php echo e($urlAppend); ?>js/fullcalendar/fullcalendar.min.js"></script>
    <script src="<?php echo e($urlAppend); ?>js/fullcalendar/locales/fullcalendar.<?php echo e($language); ?>.js"></script>

    <script>
        $(function() {
            $('.blockUI').click(function() {
                $.blockUI({ message: "<div class='card'><h4><span class='fa fa-refresh fa-spin'></span> <?php echo e(trans('langPleaseWait')); ?></h4></div>" });
            });
        });
    </script>

    <script>
        bootbox.setDefaults({
            locale: "<?php echo e($language); ?>"
        });
        var notificationsCourses = { getNotifications: '<?php echo e($urlAppend); ?>main/notifications.php' };
    </script>

    
    <script src="<?php echo e($urlAppend); ?>js/owl-carousel.min.js"></script>

    
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/slick.min.js"></script>
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/custom.js?v=<?php echo e($cache_suffix); ?>"></script>
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/viewStudentTeacher.js?v=<?php echo e($cache_suffix); ?>"></script>
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/sidebar_slider_action.js?v=<?php echo e($cache_suffix); ?>"></script>
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/notification_bar.js?v=<?php echo e($cache_suffix); ?>"></script>

    <?php echo $head_content; ?>


    <?php echo $__env->yieldPushContent('head_scripts'); ?>

    <?php if(get_config('ext_analytics_enabled') and get_config('ext_analytics_code')): ?>
        <?php echo get_config('ext_analytics_code'); ?>

    <?php endif; ?>

    <?php if(get_config('ext_userway_code') and get_config('ext_userway_enabled')): ?>
        <?php echo get_config('ext_userway_code'); ?>

    <?php endif; ?>

    <?php if(file_exists('js/mathjax/tex-chtml.js')): ?>
        <script type="text/javascript" id="MathJax-script" async src="<?php echo e($urlAppend); ?>js/mathjax/tex-chtml.js"></script>
    <?php endif; ?>

    
    <?php if($theme_id && file_exists($theme_css)): ?>
        <link rel="stylesheet" type="text/css" href="<?php echo e($urlAppend); ?>courses/theme_data/<?php echo e($theme_id); ?>/style_str.css?v=<?php echo e($cache_suffix); ?>"/>
    <?php endif; ?>

</head>

<body>
    <div class="ContentEclass d-flex flex-column min-vh-100 <?php if($pinned_announce_id > 0 && !isset($_COOKIE['CookieNotification'])): ?> fixed-announcement <?php endif; ?>">
        <?php if($pinned_announce_id > 0 && !empty($pinned_announce_title) && !empty($pinned_announce_body)): ?>
            <?php if(!isset($_COOKIE['CookieNotification'])): ?>
                <div class="notification-top-bar d-flex justify-content-center align-items-center px-3">
                    <div class='<?php echo e($container); ?> padding-default'>
                        <div class='d-flex justify-content-center align-items-center gap-2'>
                            <button class='btn hide-notification-bar' id='closeNotificationBar' data-bs-toggle='tooltip' data-bs-placement='bottom' title="<?php echo e(trans('langDontDisplayAgain')); ?>" aria-label="<?php echo e(trans('langDontDisplayAgain')); ?>">
                                <i class='fa-solid fa-xmark link-delete fa-lg me-2'></i>
                            </button>
                            <i class='fa-regular fa-bell fa-xl d-block'></i>
                            <span class='d-inline-block text-truncate TextBold title-announcement' style="max-width: auto;">
                                <?php echo strip_tags($pinned_announce_title); ?>
                            </span>
                            <a class='link-color TextBold msmall-text text-decoration-underline ps-1 text-nowrap' href="<?php echo e($urlAppend); ?>main/system_announcements.php?an_id=<?php echo e($pinned_announce_id); ?>"><?php echo trans('langDisplayAnnouncement'); ?></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php echo $__env->make('layouts.partials.navheadDesktop',['logo_img' => $logo_img], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <main id="main"><?php echo $__env->yieldContent('content'); ?></main>
        <?php echo $__env->make('layouts.partials.footerDesktop', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    <?php if(isset($_SESSION['uid']) && get_config('enable_quick_note')): ?>
        <a type="button" class="btn btn-quick-note submitAdminBtnDefault" data-bs-toggle="modal" href="#quickNote" aria-label="<?php echo e(trans('langQuickNotesSide')); ?>">
            <span class="fa-solid fa-paperclip" data-bs-toggle='tooltip'
                    data-bs-placement='bottom' data-bs-title="<?php echo e(trans('langQuickNotesSide')); ?>"></span>
        </a>
        <div class="modal fade" id="quickNote" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class='modal-title'>
                            <div class='icon-modal-default'><i class='fa-solid fa-cloud-arrow-up fa-xl Neutral-500-cl'></i></div>
                            <div class='modal-title-default text-center mb-0'><?php echo e(trans('langQuickNotesSide')); ?></div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class='form-wrapper form-edit'>
                            <form action='<?php echo e($urlAppend); ?>main/notes/index.php' method='post'>
                                <div class="mb-3">
                                    <label for="title-note" class="control-label-notes"><?php echo e(trans('langTitle')); ?>&nbsp<span class='text-danger'>(*)</span></label>
                                    <input type="text" class="form-control" name='newTitle' id="title-note">
                                </div>
                                <div class="mb-3">
                                    <label for="content-note" class="control-label-notes"><?php echo e(trans('langContent')); ?></label>
                                    <textarea class="form-control" id="content-note" name='newContent'></textarea>
                                </div>
                                <div class="mb-5">
                                    <a class='small-text text-decoration-underline' href='<?php echo e($urlAppend); ?>main/notes/index.php'><?php echo e(trans('langAllNotes')); ?></a>
                                </div>
                                <?php echo generate_csrf_token_form_field(); ?>

                                <div class='d-flex justify-content-end align-items-center gap-2 flex-wrap'>
                                    <button type="button" class="btn cancelAdminBtn" data-bs-dismiss="modal"><?php echo e(trans('langClose')); ?></button>
                                    <button type="submit" class="btn submitAdminBtn" name='submitNote'><?php echo e(trans('langSubmit')); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <button class="btnScrollToTop" data-bs-scroll="up" aria-label="<?php echo e(trans('langScrollToTop')); ?>">
        <i class="fa-solid fa-arrow-up-from-bracket"></i>
    </button>
    <script>
        $(function() {
            $(".datetimepicker table > thead > tr").find("th.prev").each(function() {
                $(this).attr("aria-label", "<?php echo e(trans('langPrevious')); ?>");
            });
            $(".datetimepicker table > thead > tr").find("th.next").each(function() {
                $(this).attr("aria-label", "<?php echo e(trans('langNext')); ?>");
            });
            $(".datepicker table > thead > tr").find("th.prev").each(function() {
                $(this).attr("aria-label", "<?php echo e(trans('langPrevious')); ?>");
            });
            $(".datepicker table > thead > tr").find("th.next").each(function() {
                $(this).attr("aria-label", "<?php echo e(trans('langNext')); ?>");
            });
            $("#cboxPrevious").attr("aria-label","<?php echo e(trans('langPrevious')); ?>");
            $("#cboxNext").attr("aria-label","<?php echo e(trans('langNext')); ?>");
            $("#cboxSlideshow").attr("aria-label","<?php echo e(trans('langShowTo')); ?>");
            $(".table-default thead tr th:last-child:has(.fa-gears)").attr("aria-label","<?php echo e(trans('langCommands')); ?>");
            $(".table-default thead tr th:last-child:has(.fa-cogs)").attr("aria-label","<?php echo e(trans('langCommands')); ?>");
            $(".table-default thead tr th:last-child:not(:has(.fa-gears))").attr("aria-label","<?php echo e(trans('langCommands')); ?> / <?php echo e(trans('langResults')); ?>");
            $(".table-default thead tr th:last-child:not(:has(.fa-cogs))").attr("aria-label","<?php echo e(trans('langCommands')); ?> / <?php echo e(trans('langResults')); ?>");
            $(".sp-input-container .sp-input").attr("aria-label","<?php echo e(trans('langOptForColor')); ?>");
            $("ul").find(".select2-search__field").attr("aria-label","<?php echo e(trans('langSearch')); ?>");
            $("#cal-slide-content ul li .event-item").attr("aria-label","<?php echo e(trans('langEvent')); ?>");
            $("#cal-day-box .event-item").attr("aria-label","<?php echo e(trans('langEvent')); ?>");
        });
    </script>
    <?php echo $__env->yieldPushContent('bottom_scripts'); ?>
 </body>
</html>
<?php /**PATH /var/www/html/resources/views/layouts/default.blade.php ENDPATH**/ ?>