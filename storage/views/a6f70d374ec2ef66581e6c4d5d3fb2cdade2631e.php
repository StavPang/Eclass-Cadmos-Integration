<div class='col-12'>
    <div class='form-wrapper form-edit p-3 rounded'>
        <form class='form-horizontal form-wrapper form-edit p-3 rounded' role='form' action='<?php echo e($_SERVER['SCRIPT_NAME']); ?>' method='post'>
            <fieldset>
                <div class='card panelCard card-default px-lg-4 py-lg-3'>
                    <div class='card-header border-0 d-flex justify-content-between align-items-center'>
                        <h3><?php echo e(trans('langThemeSettings')); ?></h3>
                    </div>
                    <div class='card-body'>
                        <legend class='mb-0' aria-label='<?php echo e(trans('langForm')); ?>'></legend>
                        <div class='form-group'>

                            <div class='form-group mt-3'>
                                <label for='homepage_intro' class='col-sm-12 control-label-notes'><?php echo e(trans('langHomePageIntroTextHelp')); ?></label>
                                <div class='col-sm-12'>
                                    <textarea id='homepage_intro' class='form-control' rows='3' cols='40' name='homepage_intro'><?php echo e($GLOBALS['homepage_intro']); ?></textarea>
                                </div>
                            </div>

                            <div class='form-group mt-4'>
                                <div class='col-sm-12'>
                                    <a class='link-color TextBold' type='button' href='#view_themes_screens' data-bs-toggle='modal'><?php echo e(trans('langViewScreensThemes')); ?></a></br></br>
                                    <label for='themeSelection' class='control-label-notes'><?php echo e(trans('langAvailableThemes')); ?>:</label>
                                    <?php echo $theme_selection; ?>

                                </div>
                            </div>

                            <div class='form-group mt-4'>
                                <div class='col-12 d-flex justify-content-between'>
                                    <input aria-label="<?php echo e(trans('langBack')); ?>" class='btn btn-primary' name='install4' value='&laquo; <?php echo e(trans('langBack')); ?>' type='submit'>
                                    <input aria-label="<?php echo e(trans('langContinue')); ?>" class='btn btn-primary' name='install6' value='<?php echo e(trans('langContinue')); ?> &raquo;' type='submit'>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <?php echo hidden_vars($all_vars, [ 'homepage_intro', 'theme_selection' ]); ?>

            </fieldset>
        </form>
    </div>
</div>

<div class='modal fade' id='view_themes_screens' tabindex='-1' aria-labelledby='view_themes_screensLabel' aria-hidden='true'>
    <div class='modal-dialog modal-fullscreen' style='margin-top:0px;'>
        <div class='modal-content'>
            <div class='modal-header'>
                <div class='modal-title' id='view_themes_screensLabel'><?php echo e(trans('langAvailableThemes')); ?></div>
                <button type='button' class='close' data-bs-dismiss='modal' aria-label="<?php echo e(trans('langClose')); ?>"></button>
            </div>
            <div class='modal-body'>
                <div class='row row-cols-1 g-4'>
                    <?php echo $theme_images; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php /**PATH /var/www/html/resources/views/install/step_5.blade.php ENDPATH**/ ?>