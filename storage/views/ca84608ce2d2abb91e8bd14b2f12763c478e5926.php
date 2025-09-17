<?php if($configErrorExists): ?>
    <div class='alert alert-danger'>
        <i class='fa-solid fa-circle-xmark fa-lg'></i>
        <span>
            <?php echo $errorContent; ?>

        </span>
    </div>
    <div class='alert alert-warning'>
        <i class='fa-solid fa-triangle-exclamation fa-lg'></i>
        <span><?php echo e(trans('langWarnInstallNotice1')); ?>

            <a href='<?php echo e($install_info_file); ?>'><?php echo e(trans('langHere')); ?></a> <?php echo e(trans('langWarnInstallNotice2')); ?>

        </span>
    </div>
<?php else: ?>
    <div class='card panelCard card-default px-lg-4 py-lg-3'>
        <div class='card-body'>
            <form class='form-wrapper' action='<?php echo e($_SERVER['SCRIPT_NAME']); ?>' method='post'>
                <fieldset>
                    <legend class='mb-0' aria-label="<?php echo e(trans('langForm')); ?>"></legend>
                    <div class="text-heading-h4 mt-2">
                        <?php echo e(trans('langCheckReq')); ?>

                    </div>

                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item element'><i class="fa-solid fa-check Success-200-cl"></i> <strong>Webserver</strong>
                            <em> <?php echo e(($_SERVER['SERVER_SOFTWARE'])); ?> </em>
                        </li>
                    </ul>

                    <ul class='list-group list-group-flush'>
                        <div class="text-heading-h4 mt-2">
                            <?php echo e(trans('langPHPVersion')); ?>

                        </div>
                        <?php echo checkPHPVersion('8.0'); ?>

                    </ul>

                    <div class="text-heading-h4 mt-2">
                        <?php echo e(trans('langRequiredPHP')); ?>

                    </div>
                    <ul class='list-group list-group-flush'>
                        <?php echo warnIfExtNotLoaded('pdo_mysql'); ?>

                        <?php echo warnIfExtNotLoaded('gd'); ?>

                        <?php echo warnIfExtNotLoaded('mbstring'); ?>

                        <?php echo warnIfExtNotLoaded('xml'); ?>

                        <?php echo warnIfExtNotLoaded('zlib'); ?>

                        <?php echo warnIfExtNotLoaded('pcre'); ?>

                        <?php echo warnIfExtNotLoaded('curl'); ?>

                        <?php echo warnIfExtNotLoaded('zip'); ?>

                        <?php echo warnIfExtNotLoaded('intl'); ?>

                    </ul>

                    <div class="text-heading-h4 mt-2">
                        <?php echo e(trans('langOptionalPHP')); ?>

                    </div>
                    <ul class='list-group list-group-flush'>
                        <?php echo warnIfExtNotLoaded('soap'); ?>

                        <?php echo warnIfExtNotLoaded('ldap'); ?>

                    </ul>

                    <?php if(ini_get('register_globals')): ?>
                        <div class='caution'>
                            <?php echo e(trans('langWarningInstall1')); ?>

                        </div>
                    <?php endif; ?>

                    <?php if(ini_get('short_open_tag')): ?>
                        <div class='caution'>
                            <?php echo e(trans('langWarningInstall2')); ?>

                        </div>
                    <?php endif; ?>

                    <div class="text-heading-h3 mt-2">
                        <?php echo e(trans('langOtherReq')); ?>

                    </div>
                    <ul>
                        <li>
                            <?php echo e(trans('langInstallBullet1')); ?>

                        </li>
                        <li>
                            <?php echo e(trans('langInstallBullet3')); ?>

                        </li>
                    </ul>

                    <div class='info'><?php echo e(trans('langBeforeInstall1')); ?><a href='<?php echo e($install_info_file); ?>' target=_blank><?php echo e(trans('langInstallInstr')); ?></a>.
                    <div class='smaller'><?php echo e(trans('langBeforeInstall2')); ?><a href='<?php echo e($readme_file); ?>' target=_blank><?php echo e(trans('langHere')); ?></a>.</div></div><br>

                    <div class='col-12 d-flex justify-content-center mt-5'>
                        <input aria-label="<?php echo e(trans('langNextStep')); ?>" type='submit' class='btn w-100' name='install2' value='<?php echo e(trans('langNextStep')); ?> &raquo;' />
                    </div>
                    <?php echo hidden_vars($all_vars); ?>

                </fieldset>
            </form>
        </div>
    </div>
<?php endif; ?>
<?php /**PATH /var/www/html/resources/views/install/step_1.blade.php ENDPATH**/ ?>