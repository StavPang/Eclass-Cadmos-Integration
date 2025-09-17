<?php $__env->startPush('head_scripts'); ?>
    <script type='text/javascript'>
        var msg = {
            langSearch: '<?php echo e(js_escape(trans('langSearch'))); ?>',
            langDisplay: '<?php echo e(js_escape(trans('langDisplay'))); ?>',
            langResults2: '<?php echo e(js_escape(trans('langResults2'))); ?>',
            langNoResult: '<?php echo e(js_escape(trans('langNoResult'))); ?>',
            langDisplayed: '<?php echo e(js_escape(trans('langDisplayed'))); ?>',
            langNoResult: '<?php echo e(js_escape(trans('langNoResult'))); ?>',
            langTill: '<?php echo e(js_escape(trans('langTill'))); ?>',
            langFrom2: '<?php echo e(js_escape(trans('langFrom2'))); ?>',
            langTotalResults: '<?php echo e(js_escape(trans('langTotalResults'))); ?>',
            dataTablesDomParam: <?php if(get_config('show_always_collaboration')): ?> '<"all_courses float-end px-0">frtip' <?php else: ?> '' <?php endif; ?> };

        $(function() {

            $('#consentModal').modal('show');

            var pages = parseInt("<?php echo e($pages); ?>");

            initialize_lesson_display(pages);

            $('.all_courses').html("<div class='d-flex justify-content-end align-items-center flex-wrap gap-2'>" +
                "<a href='<?php echo e($urlAppend); ?>main/portfolio.php?countPages=-1' class='btn submitAdminBtn submitAdminBtnAllCourses'><?php echo e(js_escape(trans('langListAll'))); ?></a>" +
                "<a class='btn showCoursesBars active' role='button' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='bottom' title='<?php echo e(js_escape(trans('langShowCoursesInTable'))); ?>' aria-label='<?php echo e(js_escape(trans('langShowCoursesInTable'))); ?>'>" +
                "<i class='fa-solid fa-table-list'></i>" +
                "</a>" +
                "<a class='btn showCoursesPics' role='button' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='bottom' title='<?php echo e(js_escape(trans('langShowCoursesInPics'))); ?>' aria-label='<?php echo e(js_escape(trans('langShowCoursesInPics'))); ?>'><i class='fa-solid fa-table-cells-large'></i></a>" +
                "</div>");

            jQuery('.panel_title').click(function()
            {
                var mypanel = $(this).next();
                mypanel.slideToggle(100);
                if($(this).hasClass('active')) {
                    $(this).removeClass('active');
                } else {
                    $(this).addClass('active');
                }
            });

        var idCoursePortfolio = '';
        var btnPortfolio = '';
        var modal_portfolio = '';
        $("#portfolio_lessons, #cources-pics, #portfolio_collaborations").on('click','.ClickCoursePortfolio',function() {
            // Get the btn id
            idCourse = this.id;
            if(idCourse.includes('CourseTable_')){
                idCoursePortfolio = idCourse.replace('CourseTable_', '');
            }else if(idCourse.includes('CoursePic_')){
                idCoursePortfolio = idCourse.replace('CoursePic_', '');
            }

            // Get the modal
            modal_portfolio = document.getElementById("PortfolioModal"+idCoursePortfolio);

            // Get the button that opens the modal
            btnPortfolio = document.getElementById(idCoursePortfolio);

            // When the user clicks the button, open the modal
            modal_portfolio.style.display = "block";

            $('[data-bs-toggle="tooltip"]').tooltip("hide");


            var $div = $('<div />').appendTo('body');
            $div.attr('class', 'modal-backdrop fade show');
        });

        $(".close").click(function() {
            modal_portfolio.style.display = "none";
            $(".modal-backdrop").remove();
        });

        // When the user clicks anywhere outside the modal, close it
        window.onclick = function(event) {
            if (event.target === modal_portfolio) {
                modal_portfolio.style.display = "none";
                $(".modal-backdrop").remove();
            }
            $('[data-bs-toggle="tooltip"]').tooltip("hide");
        }

        $('#cources-pics').css('display','none');
        $('.showCoursesBars').on('click',function(){
            $('#cources-bars').css('display','block');
            $('#cources-pics').css('display','none');
            $('.showCoursesBars').addClass('active');
            $('.showCoursesPics').removeClass('active');
        });
        $('.showCoursesPics').on('click',function(){
            $('#cources-bars').css('display','none');
            $('#cources-pics').css('display','block');
            $('.showCoursesBars').removeClass('active');
            $('.showCoursesPics').addClass('active');
        });

        var arrayLeftRight = [];

        // init page1
        if(arrayLeftRight.length == 0){
            var totalCourses = $('#KeyallCourse').val();

            for(j=1; j<=totalCourses; j++){
                if(j!=1){
                    $('.cardCourse'+j).removeClass('d-block');
                    $('.cardCourse'+j).addClass('d-none');
                }else{
                    $('.page-item-previous').addClass('disabled');
                    $('.cardCourse'+j).removeClass('d-none');
                    $('.cardCourse'+j).addClass('d-block');
                    $('#Keypage1').addClass('active');
                }
            }
            var totalPages = $('#KeypagesCourse').val();
            if(totalPages == 1){
                $('.page-item-previous').addClass('disabled');
                $('.page-item-next').addClass('disabled');
            }
        }


        // prev-button
        $('.page-item-previous .page-link').on('click',function(){

            var prevPage;

            $('.page-item-pages .page-link.active').each(function(index, value){
                var IDCARD = this.id;
                var number = parseInt(IDCARD.match(/\d+/g));
                prevPage = number-1;

                arrayLeftRight.push(number);

                var totalCourses = $('#KeyallCourse').val();
                var totalPages = $('#KeypagesCourse').val();
                for(i=1; i<=totalCourses; i++){
                    if(i == prevPage){
                        $('.cardCourse'+i).removeClass('d-none');
                        $('.cardCourse'+i).addClass('d-block');
                        $('#Keypage'+prevPage).addClass('active');
                    }else{
                        $('.cardCourse'+i).removeClass('d-block');
                        $('.cardCourse'+i).addClass('d-none');
                        $('#Keypage'+i).removeClass('active');
                    }
                }

                if(prevPage == 1){
                    $('.page-item-previous').addClass('disabled');
                }else{
                    if(prevPage < totalPages){
                        $('.page-item-next').removeClass('disabled');
                    }
                    $('.page-item-previous').removeClass('disabled');
                }


                //create page-link in center
                if(number <= totalPages-3 && number >= 6 && totalPages>=12){

                    $('#KeystartLi').removeClass('d-none');
                    $('#KeystartLi').addClass('d-block');

                    for(i=2; i<=totalPages-1; i++){
                        $('#KeypageCenter'+i).removeClass('d-block');
                        $('#KeypageCenter'+i).addClass('d-none');
                    }

                    $('#KeypageCenter'+arrayLeftRight[arrayLeftRight.length-1]).removeClass('d-none');
                    $('#KeypageCenter'+arrayLeftRight[arrayLeftRight.length-1]).removeClass('d-block');

                    var currentPage = number-1;
                    $('#KeypageCenter'+currentPage).removeClass('d-none');
                    $('#KeypageCenter'+currentPage).addClass('d-block');

                    var prevPage = number-2;
                    $('#KeypageCenter'+prevPage).removeClass('d-none');
                    $('#KeypageCenter'+prevPage).addClass('d-block');

                    $('#KeycloseLi').removeClass('d-none');
                    $('#KeycloseLi').addClass('d-block');

                }else if(number <= 5 && totalPages>=12){

                    $('#KeystartLi').removeClass('d-block');
                    $('#KeystartLi').addClass('d-none');

                    for(i=6; i<=totalPages-1; i++){
                        $('#KeypageCenter'+i).removeClass('d-block');
                        $('#KeypageCenter'+i).addClass('d-none');
                    }

                    $('#KeycloseLi').removeClass('d-none');
                    $('#KeycloseLi').addClass('d-block');


                    for(i=1; i<=number; i++){
                        $('#KeypageCenter'+i).removeClass('d-none');
                        $('#KeypageCenter'+i).addClass('d-block');
                    }

                }
            });
        });

        // next-button
        $('.page-item-next .page-link').on('click',function(){

            $('.page-item-pages .page-link.active').each(function(index, value){
                var IDCARD = this.id;
                var number = parseInt(IDCARD.match(/\d+/g));
                arrayLeftRight.push(number);
                var nextPage = number+1;

                var delPageActive = nextPage-1;
                $('#Keypage'+delPageActive).removeClass('active');
                $('#Keypage'+nextPage).addClass('active');

                var totalCourses = $('#KeyallCourse').val();
                var totalPages = $('#KeypagesCourse').val();

                for(i=1; i<=totalCourses; i++){
                    if(i == nextPage){
                        $('.cardCourse'+i).removeClass('d-none');
                        $('.cardCourse'+i).addClass('d-block');
                        // $('#Keypage'+nextPage).addClass('active');
                    }else{
                        $('.cardCourse'+i).removeClass('d-block');
                        $('.cardCourse'+i).addClass('d-none');
                        //$('#Keypage'+i).removeClass('active');
                    }
                }

                if(totalPages > 1){
                    $('.page-item-previous').removeClass('disabled');
                }
                if(nextPage == totalPages){
                    $('.page-item-next').addClass('disabled');
                }else{
                    $('.page-item-next').removeClass('disabled');
                }


                //create page-link in center
                if(number >= 4 && number < totalPages-5 && totalPages>=12){//5-7

                    $('#KeystartLi').removeClass('d-none');
                    $('#KeystartLi').addClass('d-block');

                    for(i=2; i<=totalPages-1; i++){
                        $('#KeypageCenter'+i).removeClass('d-block');
                        $('#KeypageCenter'+i).addClass('d-none');
                    }

                    $('#KeypageCenter'+arrayLeftRight[arrayLeftRight.length-1]).removeClass('d-none');
                    $('#KeypageCenter'+arrayLeftRight[arrayLeftRight.length-1]).removeClass('d-block');

                    var currentPage = number+1;
                    $('#KeypageCenter'+currentPage).removeClass('d-none');
                    $('#KeypageCenter'+currentPage).addClass('d-block');

                    var nextPage = number+2;
                    $('#KeypageCenter'+nextPage).removeClass('d-none');
                    $('#KeypageCenter'+nextPage).addClass('d-block');

                    $('#KeycloseLi').removeClass('d-none');
                    $('#KeycloseLi').addClass('d-block');

                }else if(arrayLeftRight[arrayLeftRight.length-1] >= totalPages-5 && totalPages>=12){//>=8

                    $('#KeystartLi').removeClass('d-none');
                    $('#KeystartLi').addClass('d-block');

                    for(i=2; i<=totalPages-5; i++){
                        $('#KeypageCenter'+i).removeClass('d-block');
                        $('#KeypageCenter'+i).addClass('d-none');
                    }

                    $('#KeycloseLi').removeClass('d-block');
                    $('#KeycloseLi').addClass('d-none');

                    var nextPage = arrayLeftRight[arrayLeftRight.length-1] + 1;
                    console.log('nextPage:'+nextPage);
                    for(i=nextPage; i<=totalPages; i++){
                        $('#KeypageCenter'+i).removeClass('d-none');
                        $('#KeypageCenter'+i).addClass('d-block');
                    }

                }else if(number>=1 && number<=4 && totalPages>=12){
                    $('#KeystartLi').removeClass('d-block');
                    $('#KeystartLi').addClass('d-none');

                    for(i=1; i<=4; i++){
                        $('#KeypageCenter'+i).removeClass('d-none');
                        $('#KeypageCenter'+i).addClass('d-block');
                    }
                }


            });
        });

        // page-link except prev-next button
        $('.page-item-pages .page-link').on('click',function(){

            var IDCARD = this.id;
            var number = parseInt(IDCARD.match(/\d+/g));

            arrayLeftRight.push(number);

            var totalPages = $('#KeypagesCourse').val();
            var totalCourses = $('#KeyallCourse').val();
            for(i=1; i<=totalCourses; i++){
                if(i!=number){
                    $('.cardCourse'+i).removeClass('d-block');
                    $('.cardCourse'+i).addClass('d-none');
                }else{
                    $('.cardCourse'+i).removeClass('d-none');
                    $('.cardCourse'+i).addClass('d-block');
                }

                // about prev-next button
                if(number>1){
                    $('.page-item-previous').removeClass('disabled');
                    $('.page-item-next').removeClass('disabled');
                }if(number == 1){
                    if(totalPages == 1){
                        $('.page-item-previous').addClass('disabled');
                        $('.page-item-next').addClass('disabled');
                    }
                    if(totalPages > 1){
                        $('.page-item-previous').addClass('disabled');
                        $('.page-item-next').removeClass('disabled');
                    }
                }if(number == totalPages){
                    $('.page-item-next').addClass('disabled');
                }if(number < totalPages-1){
                    $('.page-item-next').removeClass('disabled');
                }
            }


            if(number>=1 && number<=4 && totalPages>=12){

                $('#KeystartLi').removeClass('d-block');
                $('#KeystartLi').addClass('d-none');

                for(i=1; i<=5; i++){
                    $('#KeypageCenter'+i).removeClass('d-none');
                    $('#KeypageCenter'+i).addClass('d-block');
                }
                for(i=6; i<=totalPages-1; i++){
                    $('#KeypageCenter'+i).removeClass('d-block');
                    $('#KeypageCenter'+i).addClass('d-none');
                }

                $('#KeycloseLi').removeClass('d-none');
                $('#KeycloseLi').addClass('d-block');
            }
            if(number>=5 && number<=totalPages-5 && totalPages>=12){

                for(i=5; i<=totalPages-1; i++){
                    $('#KeypageCenter'+i).removeClass('d-block');
                    $('#KeypageCenter'+i).addClass('d-none');
                }

                var prevPage = number-1;
                var nextPage = number+1;
                var currentPage = number;

                $('#KeystartLi').removeClass('d-none');
                $('#KeystartLi').addClass('d-block');

                for(i=2; i<=4; i++){
                    $('#KeypageCenter'+i).removeClass('d-block');
                    $('#KeypageCenter'+i).addClass('d-none');
                }

                $('#KeypageCenter'+prevPage).removeClass('d-none');
                $('#KeypageCenter'+prevPage).addClass('d-block');

                $('#KeypageCenter'+currentPage).removeClass('d-none');
                $('#KeypageCenter'+currentPage).addClass('d-block');

                $('#KeypageCenter'+nextPage).removeClass('d-none');
                $('#KeypageCenter'+nextPage).addClass('d-block');

                $('#KeycloseLi').removeClass('d-none');
                $('#KeycloseLi').addClass('d-block');

            }
            if(number>=totalPages-4 && totalPages>=12){

                $('#KeystartLi').removeClass('d-none');
                $('#KeystartLi').addClass('d-block');

                for(i=2; i<=totalPages-5; i++){
                    $('#KeypageCenter'+i).removeClass('d-block');
                    $('#KeypageCenter'+i).addClass('d-none');
                }

                for(i=totalPages-4; i<=totalPages; i++){
                    $('#KeypageCenter'+i).removeClass('d-none');
                    $('#KeypageCenter'+i).addClass('d-block');
                }


                $('#KeycloseLi').removeClass('d-block');
                $('#KeycloseLi').addClass('d-none');
            }
            if(number==totalPages-4 && arrayLeftRight[arrayLeftRight.length-2]>number && totalPages>=12){

                $('#KeystartLi').removeClass('d-none');
                $('#KeystartLi').addClass('d-block');

                for(i=2; i<=totalPages-1; i++){
                    $('#KeypageCenter'+i).removeClass('d-block');
                    $('#KeypageCenter'+i).addClass('d-none');
                }

                var prevPage = number+1;
                var nextPage = number-1;
                var currentPage = number;

                $('#KeypageCenter'+prevPage).removeClass('d-none');
                $('#KeypageCenter'+prevPage).addClass('d-block');

                $('#KeypageCenter'+currentPage).removeClass('d-none');
                $('#KeypageCenter'+currentPage).addClass('d-block');

                $('#KeypageCenter'+nextPage).removeClass('d-none');
                $('#KeypageCenter'+nextPage).addClass('d-block');

                $('#KeycloseLi').removeClass('d-none');
                $('#KeycloseLi').addClass('d-block');
            }


            // about active page-item
            $('.page-item-pages .page-link').each(function(index, value){
                $('.page-item-pages .page-link').removeClass('active');
            });
            $(this).addClass('active');

        });
    });
    </script>

<?php $__env->stopPush(); ?>




<?php $__env->startSection('content'); ?>

<?php if($_SESSION['status'] == USER_STUDENT and get_config('activate_privacy_policy_consent') and !isset($_SESSION['accept_policy_later']) and !user_has_accepted_policy($uid)): ?>
    <?php echo $__env->make('portfolio.privacy_policy_modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>

<div class="col-12 main-section">
    <div class='row m-auto'>
        <div class='col-12 portfolio-profile-container'>
            <div class='<?php echo e($container); ?> padding-default'>
            <h1 aria-label="<?php echo e(trans('langPortfolio')); ?>"></h1>
                <div class='row row-cols-xl-3 row-cols-md-2 row-cols-1'>
                    <div class='col-xl-4 col-md-6 d-flex col-12 justify-content-md-start justify-content-center align-items-center'>
                        <div class='d-flex justify-content-md-start justify-content-center align-items-center flex-wrap gap-3'>
                            <img class="user-detals-photo ms-auto me-auto" src="<?php echo e(user_icon($uid, IMAGESIZE_LARGE)); ?>" alt="<?php echo e(trans('langUser')); ?>: <?php echo e($_SESSION['surname']); ?> <?php echo e($_SESSION['givenname']); ?>">
                            <div>
                                <div class='mb-0 portofolio-text-intro portfolio-username TextBold normal-text'> <?php echo e($_SESSION['surname']); ?> <?php echo e($_SESSION['givenname']); ?> </div>
                                <p class='small-text Neutral-900-cl mb-0 portofolio-text-intro'>
                                    <?php echo $_SESSION['uname']; ?>

                                </p>
                            </div>
                        </div>
                    </div>

                    <div class='col-xl-3 col-md-6 col-12 d-flex justify-content-xl-center justify-content-md-end justify-content-center align-items-center mt-md-0 mt-4'>
                        <div>
                            <?php if(!get_config('show_always_collaboration')): ?>
                            <div class='d-flex justify-content-start align-items-center gap-2 portfolio-texts mb-0'>
                                <div><?php echo trans('langSumCoursesEnrolled'); ?>: <?php echo e($num_of_courses); ?> </div>
                            </div>
                            <?php endif; ?>
                            <?php if(get_config('show_collaboration')): ?>
                            <div class='d-flex justify-content-start align-items-center gap-2 portfolio-texts mb-0'>
                                <div><?php echo trans('langSumCollaborationEnrolled'); ?>: <?php echo e($num_of_collaborations); ?> </div>
                            </div>
                            <?php endif; ?>
                            <p class='small-text Neutral-900-cl mb-0 portofolio-text-intro'>
                                <?php echo e(trans('langProfileLastVisit')); ?>&nbsp;:&nbsp;<span class='TextBold small-text'><?php echo e(format_locale_date(strtotime($last_login))); ?></span>
                            </p>
                        </div>
                    </div>

                    <div class='col-xl-5 col-md-12 col-12 d-flex justify-content-xl-end justify-content-md-start justify-content-center align-items-center gap-2 flex-wrap mt-xl-0 mt-4'>
                        <a class='btn myProfileBtn' type='button' href='<?php echo e($urlAppend); ?>main/profile/display_profile.php'>
                            <?php echo e(trans('langMyProfile')); ?>

                        </a>
                        <a class='btn myProfileBtn' type='button' href='<?php echo e($urlAppend); ?>modules/usage/index.php?t=u'>
                            <?php echo e(trans('langMyStats')); ?>

                        </a>
                        <?php if((isset($is_admin) and $is_admin) or
                            (isset($is_power_user) and $is_power_user) or
                            (isset($is_usermanage_user) and ($is_usermanage_user)) or
                            (isset($is_departmentmanage_user) and $is_departmentmanage_user)): ?>
                                <a class="btn myProfileBtn" type="button" href="<?php echo e($urlAppend); ?>modules/admin/index.php">
                                    <?php echo e(trans('langAdminTool')); ?>

                                </a>
                        <?php elseif($_SESSION['status'] == USER_STUDENT): ?>
                            <a class="btn myProfileBtn" type="button" href="<?php echo e($urlAppend); ?>modules/auth/formuser.php">
                                <?php echo e(trans('langMyRequests')); ?>

                            </a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class='col-12 main-section-courses'>
        <div class='row m-auto'>
            <div class='col-12 portfolio-courses-container'>
                <div class='<?php echo e($container); ?> padding-default'>
                    <div class='row row-cols-1 g-4'>
                        <div class='col portfolio-content'>
                            <div class='d-xl-flex gap-5'>
                                <div class='flex-grow-1'>
                                    <div class='card card-transparent border-0 bg-transparent'>
                                        <div class='card-header d-md-flex justify-content-md-between align-items-md-center px-0 bg-transparent border-0'>
                                            <h2><?php echo e(trans('langMyCoursesSide')); ?>&nbsp;
                                                <?php if(!get_config('show_always_collaboration')): ?>
                                                    (<?php echo e($num_of_courses); ?>)
                                                <?php else: ?>
                                                    (<?php echo e($num_of_collaborations); ?>)
                                                <?php endif; ?>

                                            </h2>
                                            <div class='d-flex mt-md-0 mt-3'>
                                                <a class="btn submitAdminBtn <?php if($_SESSION['status'] == USER_TEACHER or $is_power_user or $is_departmentmanage_user): ?> me-2 <?php endif; ?>" href="<?php echo e($urlAppend); ?>modules/auth/courses.php">
                                                    <i class="fa-regular fa-pen-to-square"></i>&nbsp
                                                    <?php echo e(trans('langRegister')); ?>

                                                </a>
                                                <?php if($_SESSION['status'] == USER_TEACHER or $is_power_user or $is_departmentmanage_user): ?>
                                                    <a id="btn_create_course" class="btn submitAdminBtnDefault" href="<?php echo e($urlAppend); ?>modules/create_course/create_course.php">
                                                        <i class="fa-solid fa-plus"></i>&nbsp;<?php echo e(trans('langCreate')); ?>

                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <div class='card-body px-0'>

                                            <?php if(Session::has('message')): ?>
                                                <div class='col-12 mt-3 px-0'>
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


                                            <div id="cources-bars">
                                                <?php echo $perso_tool_content['lessons_content']; ?>

                                            </div>


                                            <div id="cources-pics">

                                                <div class="d-flex justify-content-end flex-wrap gap-2 mb-4">
                                                    <a class="btn showCoursesBars" role='button' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='bottom' title="<?php echo e(js_escape(trans('langShowCoursesInTable'))); ?>" aria-label="<?php echo e(js_escape(trans('langShowCoursesInTable'))); ?>">
                                                        <i class="fa-solid fa-table-list"></i>
                                                    </a>
                                                    <a class="btn showCoursesPics" role='button' data-bs-toggle='tooltip' data-bs-html='true' data-bs-placement='bottom' title="<?php echo e(js_escape(trans('langShowCoursesInPics'))); ?>" aria-label="<?php echo e(js_escape(trans('langShowCoursesInPics'))); ?>">
                                                        <i class="fa-solid fa-table-cells-large"></i>
                                                    </a>
                                                </div>

                                                <div class="row row-cols-1 row-cols-md-2 row-cols-xxl-3 g-4">
                                                    <?php
                                                        $pagesPag = 0;
                                                        $allCourses = 0;
                                                        $temp_pages = 0;
                                                        $countCards = 1;
                                                        if($countCards == 1){
                                                            $pagesPag++;
                                                        }
                                                    ?>

                                                    <?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                            <?php
                                                                $temp_pages++;
                                                                if (isset($course->favorite)) {
                                                                    $favorite_icon = 'fa-star Primary-500-cl';
                                                                    $fav_status = 0;
                                                                    $fav_message = '';
                                                                } else {
                                                                    $favorite_icon = 'fa-regular fa-star';
                                                                    $fav_status = 1;
                                                                    $fav_message = trans('langFavorite');
                                                                }
                                                            ?>

                                                            <div class="col cardCourse<?php echo e($pagesPag); ?>">
                                                                <div class="card h-100 card<?php echo e($pagesPag); ?> Borders border-card card-default">
                                                                    <?php
                                                                        $courseImage = '';
                                                                        if(!empty($course->course_image)){
                                                                            $courseImage = "{$urlServer}courses/$course->code/image/$course->course_image";
                                                                        }else{
                                                                            if($course->is_collaborative){
                                                                                $courseImage = "{$urlServer}template/modern/images/default-collaboration.jpg";
                                                                            }else{
                                                                                $courseImage = "{$urlServer}resources/img/ph1.jpg";
                                                                            }
                                                                        }
                                                                    ?>

                                                                    <?php if($course->course_image == NULL): ?>
                                                                        <?php if($course->is_collaborative): ?>
                                                                            <img class="card-img-top cardImgCourse <?php if($course->visible == 3): ?> InvisibleCourse <?php endif; ?>" src="<?php echo e($urlAppend); ?>template/modern/images/default-collaboration.jpg" alt="<?php echo e(trans('langCourseImage')); ?> : <?php echo e($course->title); ?>" />
                                                                        <?php else: ?>
                                                                            <img class="card-img-top cardImgCourse <?php if($course->visible == 3): ?> InvisibleCourse <?php endif; ?>" src="<?php echo e($urlAppend); ?>resources/img/ph1.jpg" alt="<?php echo e(trans('langCourseImage')); ?> : <?php echo e($course->title); ?>" />
                                                                        <?php endif; ?>
                                                                    <?php else: ?>
                                                                        <img class="card-img-top cardImgCourse <?php if($course->visible == 3): ?> InvisibleCourse <?php endif; ?>" src="<?php echo e($urlAppend); ?>courses/<?php echo e($course->code); ?>/image/<?php echo e($course->course_image); ?>" alt="<?php echo e(trans('langCourseImage')); ?> : <?php echo e($course->title); ?>" />
                                                                    <?php endif; ?>

                                                                    <div class='card-body'>

                                                                        <div class="lesson-title line-height-default">
                                                                            <a class='TextBold' href="<?php echo e($urlServer); ?>courses/<?php echo e($course->code); ?>/">
                                                                                <?php echo e($course->title); ?>&nbsp;(<?php echo e($course->public_code); ?>)
                                                                            </a>
                                                                        </div>

                                                                        <div class="vsmall-text Neutral-900-cl TextRegular mt-1"><?php echo e($course->professor); ?></div>

                                                                    </div>

                                                                    <div class='card-footer d-flex justyfy-content-start align-items-center gap-3 flex-wrap border-0'>
                                                                        <a id='btnNotificationCards_<?php echo e($course->course_id); ?>' class='d-none btn btn-notification-course-card text-decoration-none'
                                                                            data-bs-toggle='modal' href='#notificationCard<?php echo e($course->course_id); ?>' aria-label="<?php echo e(trans('langNotificationsExist')); ?>">
                                                                            <i class='fa-solid fa-bell link-color' data-bs-toggle='tooltip' data-bs-placement='bottom' data-bs-original-title="<?php echo e(trans('langNotificationsExist')); ?>"></i>
                                                                        </a>

                                                                        <a class='ClickCoursePortfolio' href='javascript:void(0);' id='CoursePic_<?php echo e($course->code); ?>' type="button" data-bs-toggle='tooltip' data-bs-placement='top'
                                                                            title="<?php echo e(trans('langPreview')); ?>&nbsp;<?php echo e(trans('langOfCourse')); ?>" aria-label="<?php echo e(trans('langPreview')); ?>&nbsp;<?php echo e(trans('langOfCourse')); ?>">
                                                                            <i class='fa-solid fa-display'></i>
                                                                        </a>

                                                                        <?php echo icon($favorite_icon, $fav_message, "course_favorite.php?course=" . $course->code . "&amp;fav=$fav_status"); ?>


                                                                        <?php if($course->status == USER_STUDENT): ?>
                                                                            <?php if(get_config('disable_student_unregister_cours') == 0): ?>
                                                                                <?php echo icon('fa-minus-circle', trans('langUnregCourse'), "{$urlServer}main/unregcours.php?cid=$course->course_id&amp;uid=$uid"); ?>


                                                                            <?php endif; ?>
                                                                        <?php elseif($course->status == USER_TEACHER): ?>
                                                                            <?php echo icon('fa-wrench', trans('langAdm'), "{$urlServer}modules/course_info/index.php?from_home=true&amp;course=" . $course->code, '', true, true); ?>

                                                                        <?php endif; ?>


                                                                        <div class="modal fade" id="notificationCard<?php echo e($course->course_id); ?>" tabindex="-1" aria-labelledby="notificationCardLabel<?php echo e($course->course_id); ?>" aria-hidden="true">
                                                                            <div class="modal-dialog">
                                                                                <div class="modal-content">
                                                                                    <div class="modal-header">
                                                                                        <div class='modal-title'>
                                                                                            <div class='icon-modal-default'><i class='fa-solid fa-cloud-arrow-up fa-xl Neutral-500-cl'></i></div>
                                                                                            <div class='modal-title-default text-center mb-0' id="notificationCardLabel<?php echo e($course->course_id); ?>"><?php echo e(trans('langNotesNotifications')); ?></div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <p class='text-center mb-3'>
                                                                                            <a class='TextBold' href="<?php echo e($urlServer); ?>courses/<?php echo e($course->code); ?>/index.php">
                                                                                                <?php echo e($course->title); ?>

                                                                                            </a>
                                                                                        </p>
                                                                                        <div class='lesson-notifications' data-id='<?php echo e($course->course_id); ?>'></div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                            </div>

                                                            <?php
                                                                if($countCards == 6 and $temp_pages < count($courses)){
                                                                    $pagesPag++;
                                                                    $countCards = 0;
                                                                }
                                                                $countCards++;
                                                                $allCourses++;
                                                            ?>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                </div>

                                                <input type='hidden' id='KeyallCourse' value='<?php echo e($allCourses); ?>'>
                                                <input type='hidden' id='KeypagesCourse' value='<?php echo e($pagesPag); ?>'>

                                                <div class='col-12 d-flex justify-content-center Borders p-0 bg-transparent solidPanel mt-4'>
                                                    <nav aria-label="<?php echo e(trans('langPagination')); ?>">
                                                        <ul class='pagination mycourses-pagination w-100 mb-0'>
                                                            <li class='page-item page-item-previous'>
                                                                <a class='page-link' aria-label="<?php echo e(trans('langPreviousPage')); ?>"><span class='fa-solid fa-chevron-left'></span></a>
                                                            </li>
                                                            <?php if($pagesPag >=12 ): ?>
                                                                <?php for($i=1; $i<=$pagesPag; $i++): ?>

                                                                    <?php if($i>=1 && $i<=5): ?>
                                                                        <?php if($i==1): ?>
                                                                            <li id='KeypageCenter<?php echo e($i); ?>' class='page-item page-item-pages'>
                                                                                <a id='Keypage<?php echo e($i); ?>' class='page-link' aria-label="<?php echo e(trans('langWikiNumberOfPages')); ?>"><?php echo e($i); ?></a>
                                                                            </li>

                                                                            <li id='KeystartLi' class='page-item page-item-pages d-flex justify-content-center align-items-end d-none'>
                                                                                <a aria-label="<?php echo e(trans('langWikiNumberOfPages')); ?>">...</a>
                                                                            </li>
                                                                        <?php else: ?>
                                                                            <?php if($i<$pagesPag): ?>
                                                                                <li id='KeypageCenter<?php echo e($i); ?>' class='page-item page-item-pages'>
                                                                                    <a id='Keypage<?php echo e($i); ?>' class='page-link' aria-label="<?php echo e(trans('langWikiNumberOfPages')); ?>"><?php echo e($i); ?></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>

                                                                    <?php if($i>=6 && $i<=$pagesPag-1): ?>
                                                                        <li id='KeypageCenter<?php echo e($i); ?>' class='page-item page-item-pages d-none'>
                                                                            <a id='Keypage<?php echo e($i); ?>' class='page-link' aria-label="<?php echo e(trans('langWikiNumberOfPages')); ?> <?php echo e($i); ?>"><?php echo e($i); ?></a>
                                                                        </li>

                                                                        <?php if($i==$pagesPag-1): ?>
                                                                            <li id='KeycloseLi' class='page-item page-item-pages d-flex justify-content-center align-items-end d-block'>
                                                                                <a aria-label="<?php echo e(trans('langWikiNumberOfPages')); ?>">...</a>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>

                                                                    <?php if($i==$pagesPag): ?>
                                                                        <li id='KeypageCenter<?php echo e($i); ?>' class='page-item page-item-pages'>
                                                                            <a id='Keypage<?php echo e($i); ?>' class='page-link' aria-label="<?php echo e(trans('langWikiNumberOfPages')); ?> <?php echo e($i); ?>"><?php echo e($i); ?></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                <?php endfor; ?>

                                                            <?php else: ?>
                                                                <?php for($i=1; $i<=$pagesPag; $i++): ?>
                                                                    <li id='KeypageCenter<?php echo e($i); ?>' class='page-item page-item-pages'>
                                                                        <a id='Keypage<?php echo e($i); ?>' class='page-link' aria-label="<?php echo e(trans('langWikiNumberOfPages')); ?> <?php echo e($i); ?>"><?php echo e($i); ?></a>
                                                                    </li>
                                                                <?php endfor; ?>
                                                            <?php endif; ?>

                                                            <li class='page-item page-item-next'>
                                                                <a class='page-link' aria-label="<?php echo e(trans('langNextPage')); ?>"><span class='fa-solid fa-chevron-right'></span></a>
                                                            </li>
                                                        </ul>
                                                    </nav>
                                                </div>

                                            </div>


                                            <?php if($portfolio_page_main_widgets): ?>
                                                <div class='col-12 mt-4'>
                                                    <?php echo html_entity_decode($portfolio_page_main_widgets); ?>

                                                </div>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                                <div>

                                    <?php echo $__env->make('portfolio.portfolio-calendar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                    <div class='card bg-transparent card-transparent border-0 mt-5 sticky-column-course-home'>
                                        <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0'>
                                            <h3 class='mb-0'><?php echo e(trans('langAnnouncements')); ?></h3>
                                            <a class='text-decoration-underline vsmall-text' href="<?php echo e($urlAppend); ?>modules/announcements/myannouncements.php">
                                                <?php echo e(trans('langAllAnnouncements')); ?>

                                            </a>
                                        </div>
                                        <div class='card-body px-0'>
                                            <?php if(empty($user_announcements)): ?>
                                                <div class='text-start mb-3'><span class='text-title not_visible'><?php echo e(trans('langNoRecentAnnounce')); ?></span></div>
                                            <?php else: ?>
                                                <?php echo $user_announcements; ?>

                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class='card bg-transparent card-transparent border-0 mt-5 sticky-column-course-home'>
                                        <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0'>

                                            <h3 class='mb-0'><?php echo e(trans('langMessages')); ?></h3>
                                            <a class='text-decoration-underline vsmall-text' href="<?php echo e($urlAppend); ?>modules/message/index.php">
                                                <?php echo e(trans('langAllMessages')); ?>

                                            </a>

                                        </div>
                                        <div class='card-body px-0'>
                                            <?php if(empty($user_messages)): ?>
                                                <div class='text-start mb-3'><span class='text-title not_visible'><?php echo e(trans('langDropboxNoMessage')); ?></span></div>
                                            <?php else: ?>
                                                <?php echo $user_messages; ?>

                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <?php if($portfolio_page_sidebar_widgets): ?>
                                        <div class='card bg-transparent card-transparent border-0 mt-5 sticky-column-course-home'>
                                            <div class='card-header border-0 bg-transparent d-flex justify-content-between align-items-center px-0 py-0'>
                                                <h3><?php echo e(trans('langMyWidgets')); ?></h3>
                                            </div>
                                            <div class='card-body px-0'>
                                                <?php echo html_entity_decode($portfolio_page_sidebar_widgets); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/portfolio/index.blade.php ENDPATH**/ ?>