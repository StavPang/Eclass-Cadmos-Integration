 <div class='alert alert-info'>
    <i class='fa-solid fa-circle-info fa-lg'></i>
    <span>
        <?php echo trans('langInfoLicence'); ?>

    </span>
</div>
<div class='card panelCard card-default px-lg-4 py-lg-3'>
    <div class='card-body'>
        <form class='form-horizontal form-wrapper' role='form' action='<?php echo e($_SERVER['SCRIPT_NAME']); ?>' method='post'>
            <fieldset>
                <legend class='mb-0' aria-label='<?php echo e(trans('langForm')); ?>'></legend>

                <div class='form-group step2-form'>
                    <pre class='pre-scrollable' style='col-sm-12'>
                        <?php echo q(wordwrap(file_get_contents('info/license/gpl.txt'))); ?>

                    </pre>
                </div>
                <div class='form-group mt-3'>
                    <div class='col-sm-12'><i class="fa-solid fa-print"></i>
                        <a href='../info/license/gpl_print.txt' target="_blank"><?php echo e(trans('langPrintVers')); ?></a>
                    </div>
                </div>
                <div class='form-group mt-5'>
                    <div class='col-12'>
                        <div class='row'>
                            <div class='col-lg-6 col-12'>
                                <input aria-label="<?php echo e(trans('langPreviousStep')); ?>" type='submit' class='btn cancelAdminBtn w-100' name='install1' value='&laquo; <?php echo e(trans('langPreviousStep')); ?>'>
                            </div>
                            <div class='col-lg-6 col-12 mt-lg-0 mt-3'>
                                <input aria-label="<?php echo e(trans('langAccept')); ?>" type='submit' class='btn w-100' name='install3' value='<?php echo e(trans('langAccept')); ?>'>
                            </div>
                        </div>
                    </div>
                </div>
                <?php echo hidden_vars($all_vars); ?>

            </fieldset>
        </form>
    </div>
</div>
<?php /**PATH /var/www/html/resources/views/install/step_2.blade.php ENDPATH**/ ?>