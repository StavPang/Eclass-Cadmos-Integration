<!doctype html>
<html lang="<?php echo e($lang); ?>">
<head>
    <meta charset='UTF-8'>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($title); ?></title>
</head>
<body>
    <!-- jQuery -->
    <script type="text/javascript" src="../js/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap v5 -->
    <link rel="stylesheet" type="text/css" href="../template/modern/css/bootstrap.min.css"/>
    <script type="text/javascript" src="../js/bootstrap.bundle.min.js"></script>
    <link href="../template/modern/css/fonts_all/typography.css" rel="stylesheet"/>
    <link href="../template/modern/css/font-awesome-6.4.0/css/all.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../template/modern/css/default.css">
    <!-- fav icons -->
    <link rel="shortcut icon" href="../resources/favicon/favicon.ico" />
    <link rel="apple-touch-icon-precomposed" href="../resources/favicon/openeclass_128x128.png" />
    <link rel="icon" type="image/png" href="../resources/favicon/openeclass_128x128.png" />

    <div class="d-flex flex-column min-vh-100 container-lg bg-light">
        <div class="header_container bg-light d-flex justify-content-center align-items-center">
            <div id="header_section" class="row">
                <div class="col-12 nav-container pt-3 pb-4">
                    <a href='' class="navbar-brand">
                        <img style="margin-top: 25px; max-width: 350px;" class="img-responsive hidden-md hidden-lg ms-2" src="../resources/img/eclass-new-logo.svg" alt=''>
                    </a>
                </div>
            </div>
        </div>

        <div class="container-fluid main-container p-0 bg-light">
            <div class="row m-auto">
                <?php if(isset($_SESSION['step']) and isset($StepTitle)): ?>
                    <nav role="navigation" class="col-12">
                        <ol class="breadcrumb">
                            <li><a href='#'><?php echo e(trans('langStep')); ?> <?php echo e($_SESSION['step']); ?> <?php echo e(trans('langFrom2')); ?> 8</a>: </li>
                            <li class="ms-2 text-secondary"><?php echo e($StepTitle); ?></li>
                        </ol>
                    </nav>
                <?php endif; ?>
                <div class="col-12 justify-content-center bg-light col_maincontent_active_Install">
                    <div class="body_container p-lg-3 p-md-3 p-2">
                        <div id="Frame" class="row">
                            <div id="main-content" class="col-12 col-md-7 col-lg-8">
                                <div class="row row-main">

                                    <?php if(isset($_POST['install1'])): ?>
                                        <?php echo $__env->make('step_1', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif(isset($_POST['install2'])): ?>
                                        <?php echo $__env->make('step_2', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif(isset($_POST['install3'])): ?>
                                        <?php echo $__env->make('step_3', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif(isset($_POST['install4'])): ?>
                                        <?php echo $__env->make('step_4', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif(isset($_POST['install5'])): ?>
                                        <?php echo $__env->make('step_5', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif(isset($_POST['install6'])): ?>
                                        <?php echo $__env->make('step_6', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif(isset($_POST['install7'])): ?>
                                        <?php echo $__env->make('step_7', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php elseif(isset($_POST['install8'])): ?>
                                        <?php echo $__env->make('step_8', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php else: ?>
                                        <div class='col-sm-12 text-center'>
                                            <h3 class='mt-3'>
                                                <?php echo e(trans('langWelcomeWizard')); ?>

                                            </h3>
                                            <div class='col-12 col-md-6 m-auto d-block mt-3'>
                                                <div class='card panelCard card-default px-lg-4 py-lg-3'>
                                                    <div class='card-header border-0 d-flex justify-content-between align-items-center'>
                                                        <h3>
                                                            <?php echo e(trans('langThisWizard')); ?>

                                                        </h3>
                                                    </div>
                                                    <div class='card-body'>
                                                        <ul class='list-group list-group-flush'>
                                                            <li class='list-group-item element text-start'>
                                                                <i class="fa-solid fa-hand-point-right"></i>
                                                                <?php echo e(trans('langWizardHelp1')); ?>

                                                            </li>
                                                            <li class='list-group-item element text-start'>
                                                                <i class="fa-solid fa-hand-point-right"></i>
                                                                <?php echo e(trans('langWizardHelp2')); ?>

                                                            </li>
                                                            <li class='list-group-item element text-start'>
                                                                <i class="fa-solid fa-hand-point-right"></i>
                                                                <?php echo e(trans('langWizardHelp3')); ?> <em>config.php</em>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class='col-12 col-md-6 m-auto d-block mt-3'>
                                                <div class='card panelCard card-default px-lg-4 py-lg-3'>
                                                    <div class='card-body'>
                                                        <form name='langform' class='form-horizontal form-wrapper' method='post' action='<?php echo e($_SERVER['SCRIPT_NAME']); ?>'>
                                                            <div class='form-group'>
                                                                <label for='lang' class='col-sm-12 control-label-notes text-start'><?php echo e(trans('langChooseLang')); ?>:</label>
                                                                <div class='col-sm-12'>
                                                                    <select class="form-select" name="lang" onchange="document.langform.submit();">
                                                                        <?php $__currentLoopData = $lang_selection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($code); ?>" <?php if($lang == $code): ?> selected <?php endif; ?>><?php echo e($name); ?></option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class='form-group mt-4'>
                                                                <div class='col-12'>
                                                                    <input type='submit' class='btn w-100' name='install1' value='<?php echo e(trans('langNextStep')); ?> &raquo;'>
                                                                    <input type='hidden' name='welcomeScreen' value='true'>
                                                                </div>
                                                            </div>
                                                            <?php echo hidden_vars($all_vars); ?>

                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                       <?php endif; ?>
                                </div>
                            </div>

                            <?php echo $__env->make('menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php /**PATH /var/www/html/resources/views/install/index.blade.php ENDPATH**/ ?>