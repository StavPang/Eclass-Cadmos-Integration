<?php if($db_error_db_exists): ?>
    <div class='alert alert-warning'>
        <i class='fa-solid fa-circle-info fa-lg'></i>
        <span> <?php echo (sprintf(trans('langDatabaseExists'), "<strong>$dbNameForm</strong>")); ?></span>
    </div>
<?php endif; ?>

<form class='form-horizontal form-wrapper form-edit p-3 rounded' role='form' action='<?php echo e($_SERVER['SCRIPT_NAME']); ?>' method='post'>
    <fieldset>
        <legend class='mb-0' aria-label='<?php echo e(trans('langForm')); ?>'></legend>

        <div class='form-group mt-3'>
            <label for='urlForm' class='col-sm-12 control-label-notes'> <?php echo e(trans('langSiteUrl')); ?> (*)</label>
                <div class='col-sm-12'>
                    <input id='urlForm' class='form-control' type='text' size='40' name='urlForm' value='<?php echo e($GLOBALS['urlForm']); ?>'>
                </div>
        </div>

        <div class='form-group mt-3'>
            <label for='campusForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langCampusName')); ?></label>
            <div class='col-sm-12'>
                <input id='campusForm' class='form-control' type='text' size='40' name='campusForm' value='<?php echo e($GLOBALS['campusForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='nameForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langAdminName')); ?> (*)</label>
            <div class='col-sm-12'>
                <input id='nameForm' class='form-control' type='text' size='40' name='nameForm' value='<?php echo e($GLOBALS['nameForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='emailForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langAdminEmail')); ?> (*)</label>
            <div class='col-sm-12'>
                <input id='emailForm' class='form-control' type='text' size='40' name='emailForm' value='<?php echo e($GLOBALS['emailForm']); ?>'>
                <div class="form-text"><?php echo e(trans('langWarnAdminEmail')); ?></div>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='loginForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langAdminLogin')); ?> (*)</label>
            <div class='col-sm-12'>
                <input id='loginForm' class='form-control' type='text' size='40' name='loginForm' value='<?php echo e($GLOBALS['loginForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='passForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langAdminPass')); ?> (*)</label>
            <div class='col-sm-12'>
                <input id='passForm' class='form-control' type='text' size='40' name='passForm' value='<?php echo e($GLOBALS['passForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='helpdeskForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langHelpDeskPhone')); ?></label>
            <div class='col-sm-12'>
                <input id='helpdeskForm' class='form-control' type='text' size='40' name='helpdeskForm' value='<?php echo e($GLOBALS['helpdeskForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='helpdeskmail' class='col-sm-12 control-label-notes'><?php echo e(trans('langHelpDeskEmail')); ?> </label>
            <div class='col-sm-12'>
                <input id='helpdeskmail' class='form-control' type='text' size='40' name='helpdeskmail' value='<?php echo e($GLOBALS['helpdeskmail']); ?>'>
                <div class="form-text"><?php echo e(trans('langWarnHelpDesk')); ?></div>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='institutionForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langInstituteShortName')); ?></label>
            <div class='col-sm-12'>
                <input id='institutionForm' class='form-control' type='text' size='40' name='institutionForm' value='<?php echo e($GLOBALS['institutionForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='institutionUrlForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langInstituteName')); ?></label>
            <div class='col-sm-12'>
                <input id='institutionUrlForm' class='form-control' type='text' size='40' name='institutionUrlForm' value='<?php echo e($GLOBALS['institutionUrlForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='postaddressForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langInstitutePostAddress')); ?></label>
            <div class='col-sm-12'>
                <textarea id='postaddressForm' class='form-control' rows='3' cols='40' name='postaddressForm'><?php echo e($GLOBALS['postaddressForm']); ?></textarea>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='eclass_stud_reg' class='col-sm-12 control-label-notes'><?php echo e(trans('langUsersAccount')); ?></label>
            <div class='col-sm-12'>
                <?php echo $user_registration_selection; ?>

            </div>
        </div>

        <div class='form-group mt-3 help-block'>
            <?php echo e(trans('langRequiredFields')); ?>

        </div>

        <div class='form-group mt-3'>
            <div class='col-12'>
                <div class='row'>
                    <div class='col-lg-6 col-12'>
                        <input aria-label="<?php echo e(trans('langPreviousStep')); ?>" type='submit' class='btn cancelAdminBtn w-100' name='install3' value='&laquo; <?php echo e(trans('langPreviousStep')); ?>'>
                    </div>
                    <div class='col-lg-6 col-12 mt-lg-0 mt-3'>
                        <input aria-label="<?php echo e(trans('langNextStep')); ?>" type='submit' class='btn w-100' name='install5' id='install5' value='<?php echo e(trans('langNextStep')); ?> &raquo;'>
                    </div>
                </div>
            </div>
        </div>

    <?php echo hidden_vars($all_vars, [ 'urlForm',
                                'nameForm',
                                'emailForm',
                                'loginForm',
                                'passForm',
                                'campusForm',
                                'helpdeskForm',
                                'helpdeskmail',
                                'institutionForm',
                                'institutionUrlForm',
                                'postaddressForm' ]); ?>

    </fieldset>
</form>
<?php /**PATH /var/www/html/resources/views/install/step_4.blade.php ENDPATH**/ ?>