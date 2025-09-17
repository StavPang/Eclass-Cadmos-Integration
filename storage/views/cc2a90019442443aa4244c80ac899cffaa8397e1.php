<?php if($db_error_connection): ?>
    <div class='alert alert-danger'>
        <i class='fa-solid fa-circle-xmark fa-lg'></i>
            <span><p><?php echo e(trans('langErrorConnectDatabase')); ?></p>
                <p class="pt-2">
                    <strong><?php echo e($db_error_message); ?></strong>
                </p>
                <p class="pt-2">
                    <?php echo e(trans('langCheckDatabaseSettings')); ?>

                </p>
            </span>
    </div>
<?php endif; ?>

<?php if($db_error_db_engine): ?>
    <div class='alert alert-warning'>
        <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
        <span><?php echo e(trans('langInnoDBMissing')); ?></span>
    </div>
<?php endif; ?>

<div class='alert alert-info'>
    <i class='fa-solid fa-circle-info fa-lg'></i>
    <span><?php echo e(trans('langWillWrite')); ?> <strong>config/config.php</strong>
        <?php echo e(trans('langDBSettingIntro')); ?>

    </span>
</div>
<form class='form-horizontal form-wrapper form-edit p-3 rounded' role='form' action='<?php echo e($_SERVER['SCRIPT_NAME']); ?>' method='post'>
    <fieldset>
        <legend class='mb-0' aria-label='<?php echo e(trans('langForm')); ?>'></legend>
        <div class='form-group'>
            <label for='dbHostForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langdbhost')); ?> (*)</label>
            <div class='row'>
                <div class='col-sm-12'>
                    <input id='dbHostForm' class='form-control' type='text' size='25' name='dbHostForm' value='<?php echo e($GLOBALS['dbHostForm']); ?>'>
                </div>
                <div class='col-sm-12 help-block'><?php echo e(trans('langEG')); ?> localhost</div>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='dbUsernameForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langDBLogin')); ?> (*)</label>
            <div class='row'>
                <div class='col-sm-12'>
                    <input id='dbUsernameForm' class='form-control' type='text' size='25' name='dbUsernameForm' value='<?php echo e($GLOBALS['dbUsernameForm']); ?>'>
                </div>
                <div class='col-sm-12 help-block'><?php echo e(trans('langEG')); ?> root</div>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='dbPassForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langDBPassword')); ?> (*)</label>
            <div class='col-sm-12'>
                <input id='dbPassForm' class='form-control' type='text' size='25' name='dbPassForm' value='<?php echo e($GLOBALS['dbPassForm']); ?>'>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='dbNameForm' class='col-sm-12 control-label-notes'><?php echo e(trans('langMainDB')); ?> (*)</label>
            <div class='row'>
                <div class='col-sm-12'>
                    <input id='dbNameForm' class='form-control' type='text' size='25' name='dbNameForm' value='<?php echo e($GLOBALS['dbNameForm']); ?>'>
                </div>
                <div class='col-sm-12 help-block'><?php echo e(trans('langNeedChangeDB')); ?></div>
            </div>
        </div>

        <div class='form-group mt-3'>
            <label for='dbMyAdmin' class='col-sm-12 control-label-notes'>
                <?php echo e(trans('langphpMyAdminURL')); ?><span class="help-block p-2"><?php echo e(trans('langOptional')); ?></span>
            </label>
            <div class='row'>
                <div class='col-sm-12'>
                    <input id='dbMyAdmin' class='form-control' type='text' size='25' name='dbMyAdmin' value='<?php echo e($GLOBALS['dbMyAdmin']); ?>'>
                </div>
            </div>
        </div>

        <div class='form-group mt-3 help-block'>
            <?php echo e(trans('langRequiredFields')); ?>

        </div>

        <div class='form-group mt-4'>
            <div class='col-12'>
                <div class='row'>
                    <div class='col-lg-6 col-12'>
                        <input aria-label="<?php echo e(trans('langPreviousStep')); ?>" type='submit' class='btn cancelAdminBtn w-100' name='install2' value='&laquo; <?php echo e(trans('langPreviousStep')); ?>'>
                    </div>
                    <div class='col-lg-6 col-12 mt-lg-0 mt-3'>
                        <input aria-label="<?php echo e(trans('langNextStep')); ?>" type='submit' class='btn w-100' name='install4' value='<?php echo e(trans('langNextStep')); ?> &raquo;'>
                    </div>
                </div>
            </div>

        </div>
        <?php echo hidden_vars($all_vars, [ 'dbHostForm', 'dbUsernameForm', 'dbPassForm', 'dbNameForm', 'dbMyAdmin' ]); ?>

    </fieldset>
</form>

<?php /**PATH /var/www/html/resources/views/install/step_3.blade.php ENDPATH**/ ?>