<?php $__env->startPush('bottom_scripts'); ?>
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/bootstrap-calendar-master/js/language/el-GR.js?v=<?php echo e(CACHE_SUFFIX); ?>"></script>
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/bootstrap-calendar-master/js/calendar.js?v=<?php echo e(CACHE_SUFFIX); ?>"></script>
    <script type="text/javascript" src="<?php echo e($urlAppend); ?>js/bootstrap-calendar-master/components/underscore/underscore-min.js?v=<?php echo e(CACHE_SUFFIX); ?>"></script>

    <script>
        var events = [];
        $(function() {
            var calendar = $("#bootstrapcalendar").calendar({
                tmpl_path: "<?php echo e($urlAppend); ?>js/bootstrap-calendar-master/tmpls/",
                events_source: function() {
                    return events;
                },
                language: "<?php echo e(js_escape(trans('langLanguageCode'))); ?>",
                views: {year:{enable: 0}, week:{enable: 0}, day:{enable: 0}},
                onAfterViewLoad: function(view) {
                    $("#current-month").text(this.getTitle());
                    $(".btn-group button").removeClass("active");
                    $("button[data-calendar-view='" + view + "']").addClass("active");
                },
                onBeforeEventsLoad: function(done) {
                    var url = "<?php echo e($urlAppend); ?>main/calendar_data.php";
                    var params = {
                        "from": this.options.position.start.getTime(),
                        "to": this.options.position.end.getTime(),
                    };
                    $.get(url, params)
                        .done(function(data) {
                            if (data.success && data.result && (data.result instanceof Array)) {
                                events = data.result;
                            }
                            done();
                            calendar._render();
                        }).fail(function() {
                            events = [];
                            done();
                            calendar._render();
                        });
                },
            });

            $(".btn-group button[data-calendar-nav]").each(function() {
                var $this = $(this);
                $this.click(function() {
                    calendar.navigate($this.data("calendar-nav"));
                });
            });

            $(".btn-group button[data-calendar-view]").each(function() {
                var $this = $(this);
                $this.click(function() {
                    calendar.view($this.data("calendar-view"));
                });
            });
        });

        function show_month(day,month,year){
            $.get("calendar_data.php",{caltype:"small", day:day, month: month, year: year}, function(data){$("#smallcal").html(data);});
        }
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('head_styles'); ?>
    <link href="<?php echo e($urlAppend); ?>js/bootstrap-calendar-master/css/calendar_small.css?v=<?php echo e(CACHE_SUFFIX); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e($urlAppend); ?>template/modern/css/new_calendar.css?v=<?php echo e(CACHE_SUFFIX); ?>" rel="stylesheet" type="text/css">
<?php $__env->stopPush(); ?>

<div class='card bg-transparent card-transparent border-0 sticky-column-course-home mb-3'>
    <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0'>
        <h3 class='mb-0'>
            <?php echo e(trans('langAgenda')); ?>

        </h3>
        <a class='text-decoration-underline vsmall-text' href="<?php echo e($urlAppend); ?>main/personal_calendar/index.php">
            <?php echo e(trans('langDetails')); ?>

        </a>
    </div>
</div>
<div class='panel panel-admin panel-admin-calendar card-transparent border-0 mt-lg-0 mt-2 sticky-column-course-home'>

    <?php echo $user_personal_calendar; ?>


</div>

<div class='card bg-transparent card-transparent border-0 sticky-column-course-home'>
    <div class='d-flex justify-content-start align-items-center flex-wrap px-0 py-3'>
        <div class='d-flex align-items-center px-2 py-1'>
            <span class='event event-important'></span>
            <span class="agenda-comment"> <?php echo e(trans('langAgendaDueDay')); ?></span>
        </div>
        <div class='d-flex align-items-center px-2 py-1'>
            <span class='event event-info'></span>
            <span class="agenda-comment"><?php echo e(trans('langAgendaCourseEvent')); ?></span>
        </div>
        <div class='d-flex align-items-center px-2 py-1'>
            <span class='event event-success'></span>
            <span class="agenda-comment"><?php echo e(trans('langAgendaSystemEvent')); ?></span>
        </div>
        <div class='d-flex align-items-center px-2 py-1'>
            <span class='event event-special'></span>
            <span class="agenda-comment"><?php echo e(trans('langAgendaPersonalEvent')); ?></span>
        </div>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/portfolio/portfolio-calendar.blade.php ENDPATH**/ ?>