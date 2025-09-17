<div class='alert alert-info'>
    <i class='fa-solid fa-circle-info fa-lg'></i>
    <?php echo e(trans('langReviewSettings')); ?>

</div>

<form class='form-horizontal form-wrapper form-edit p-3 rounded' role='form' action='<?php echo e($_SERVER['SCRIPT_NAME']); ?>' method='post'>
    <fieldset>
        <legend class='mb-0' aria-label='<?php echo e(trans('langForm')); ?>'></legend>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langdbhost')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['dbHostForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langDBLogin')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['dbUsernameForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langMainDB')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['dbNameForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langSiteUrl')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['urlForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langAdminEmail')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['emailForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langAdminName')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['nameForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langAdminLogin')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['loginForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langAdminPass')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['passForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langCampusName')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['campusForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langHelpDeskPhone')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['helpdeskForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langHelpDeskEmail')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['helpdeskmail']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langInstituteShortName')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['institutionForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langInstituteName')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['institutionUrlForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langDisableEclassStudRegType')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['disable_eclass_stud_reg_info']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langInstitutePostAddress')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['postaddressForm']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langHomePageIntroTextHelp')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($GLOBALS['homepage_intro']); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-3'>
            <div class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langActiveTheme')); ?>

            </div>
            <div class='col-sm-12'>
                <p class='form-control-static'>
                    <?php echo e($available_theme); ?>

                </p>
            </div>
        </div>

        <div class='form-group mt-5'>
            <div class='col-12'>
                <div class='row'>
                    <div class='col-lg-5 col-12'>
                        <input aria-label="<?php echo e(trans('langPreviousStep')); ?>" type='submit' class='btn cancelAdminBtn w-100' name='install6' value='&laquo; <?php echo e(trans('langPreviousStep')); ?>'>
                    </div>
                    <div class='col-lg-7 col-12 mt-lg-0 mt-3'>
                        <input aria-label="<?php echo e(trans('langInstall')); ?>" type='submit' class='btn w-100' name='install8' id='install8' value='<?php echo e(trans('langInstall')); ?> &raquo;'>
                    </div>
                </div>
            </div>
        </div>

        <?php echo hidden_vars($all_vars); ?>

    </fieldset>

</form>

<script type='text/javascript'>
    $(function() {
        $('#install6').on( 'click', function() {
            bootbox.dialog({
                closeButton: false,
                message:  '<div><p><?php echo e(js_escape(trans('langInstallMsg'))); ?></p></div>'+
                    '<div class=\"progress\">'+
                    '<div class=\"progress-bar progress-bar-striped active\" role=\"progressbar\" aria-valuenow=\"100\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: 100%\">'+
                    '<span class=\"sr-only\"><?php echo e(js_escape(trans('langCheckNotOk1'))); ?></span>'+
                    '</div>'+
                    '</div>',
                title: '<?php echo e(js_escape(trans('langCheckNotOk1'))); ?>'
            });
        });
    });
</script>
<?php /**PATH /var/www/html/resources/views/install/step_7.blade.php ENDPATH**/ ?>