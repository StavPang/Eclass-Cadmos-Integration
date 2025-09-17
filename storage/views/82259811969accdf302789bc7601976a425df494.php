
<footer id="bgr-cheat-footer" class="site-footer mt-auto d-flex justify-content-start align-items-center">
    <div class='<?php echo e($container); ?> footer-container d-flex align-items-center flex-wrap gap-3'>
        <div class='d-none d-lg-block w-100'>
            <?php if($image_footer): ?>
                <div class='col-12 d-flex justify-content-center align-items-center gap-3 pt-3'>
                    <?php if(get_config('link_footer_image')): ?>
                    <a href="<?php echo get_config('link_footer_image'); ?>" target="_blank">
                        <img class='footer-image' src='<?php echo e($image_footer); ?>?<?php echo time(); ?>' alt="<?php echo e(trans('langMetaImage')); ?>">
                    </a>
                    <?php else: ?>
                    <img class='footer-image' src='<?php echo e($image_footer); ?>?<?php echo time(); ?>' alt="<?php echo e(trans('langMetaImage')); ?>">
                    <?php endif; ?>
                </div>
                <?php if(get_config('footer_intro')): ?>
                    <div class='col-lg-8 col-12 d-flex justify-content-center align-items-center gap-3 p-3 footer-text m-auto'>
                        <?php echo get_config('footer_intro'); ?>

                    </div>
                    <div class='col-lg-8 col-12 m-auto border-bottom-footer-text mb-3'></div>
                <?php endif; ?>
                <div class='col-12 d-flex d-flex justify-content-center align-items-center gap-3 flex-wrap mt-3'>
                    <?php if(!get_config('dont_display_about_menu')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/about.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langPlatformIdentity')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if(!get_config('dont_display_contact_menu')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/contact.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langContact')); ?>

                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!get_config('dont_display_manual_menu')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/manual.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langManuals')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                    <div>
                        <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/terms.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                            <?php echo e(trans('langUsageTerms')); ?>

                        </a>
                    </div>
                    <?php if(get_config('activate_privacy_policy_text')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/privacy_policy.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langPrivacyPolicy')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-12 d-flex justify-content-center align-items-center flex-wrap gap-5 mt-3 pb-3">
                    <a class="copyright" href='<?php echo e($urlAppend); ?>info/copyright.php' <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>Copyright © <?php echo e(date('Y')); ?> All rights reserved</a>
                    <?php if(get_config('enable_social_sharing_links')): ?>
                        <div class='d-flex gap-3 justify-content-end'>
                            <?php if(get_config('link_fb')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_fb'); ?>" target="_blank" aria-label="Facebook: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-facebook-f social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                            <?php if(get_config('link_tw')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_tw'); ?>" target="_blank" aria-label="Twitter: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-twitter social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                            <?php if(get_config('link_ln')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_ln'); ?>" target="_blank" aria-label="Linkedin: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-linkedin-in social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <?php if(get_config('footer_intro')): ?>
                    <div class='col-lg-8 col-12 d-flex justify-content-center align-items-center p-3 footer-text m-auto'>
                        <?php echo get_config('footer_intro'); ?>

                    </div>
                    <div class='col-lg-8 col-12 m-auto border-bottom-footer-text mb-3'></div>
                <?php endif; ?>
                <nav class='col-12 d-flex justify-content-between align-items-center'>
                    <ul class="container-items-footer nav">
                        <?php if(!get_config('dont_display_about_menu')): ?>
                            <li class="nav-item"><a class="nav-link menu-item a_tools_site_footer ps-2 pe-3" href="<?php echo e($urlAppend); ?>info/about.php"  <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>><?php echo e(trans('langPlatformIdentity')); ?></a></li>
                        <?php endif; ?>
                        <?php if(!get_config('dont_display_contact_menu')): ?>
                            <li class="nav-item"><a class="nav-link menu-item a_tools_site_footer px-3" href="<?php echo e($urlAppend); ?>info/contact.php"  <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>><?php echo e(trans('langContact')); ?></a></li>
                        <?php endif; ?>
                        <?php if(!get_config('dont_display_manual_menu')): ?>
                            <li class="nav-item"><a class="nav-link menu-item a_tools_site_footer px-3" href="<?php echo e($urlAppend); ?>info/manual.php"  <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>><?php echo e(trans('langManuals')); ?></a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link menu-item a_tools_site_footer px-3" href="<?php echo e($urlAppend); ?>info/terms.php"  <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>><?php echo e(trans('langUsageTerms')); ?></a></li>
                        <?php if(get_config('activate_privacy_policy_text')): ?>
                            <li class="nav-item"><a class="nav-link menu-item a_tools_site_footer px-3" href="<?php echo e($urlAppend); ?>info/privacy_policy.php"  <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>><?php echo e(trans('langPrivacyPolicy')); ?></a>
                        <?php endif; ?>
                    </ul>
                    <div class='d-flex justify-content-start align-items-center'>
                        <a class="copyright px-2" href='<?php echo e($urlAppend); ?>info/copyright.php' <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>Copyright © <?php echo e(date('Y')); ?> All rights reserved</a>
                        <?php if(get_config('enable_social_sharing_links')): ?>
                            <?php if(get_config('link_fb')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_fb'); ?>" target="_blank" aria-label="Facebook: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-facebook-f social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                            <?php if(get_config('link_tw')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_tw'); ?>" target="_blank" aria-label="Twitter: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-twitter social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                            <?php if(get_config('link_ln')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_ln'); ?>" target="_blank" aria-label="Linkedin: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-linkedin-in social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </nav>
            <?php endif; ?>
        </div>




        <div class='d-block d-lg-none w-100'>
            <div class="d-flex align-items-start flex-column h-100">
                <?php if($image_footer): ?>
                    <div class='col-12 d-flex justify-content-center align-items-center pb-3 gap-3'>
                        <?php if(get_config('link_footer_image')): ?>
                        <a href="<?php echo get_config('link_footer_image'); ?>" target="_blank">
                            <img class='footer-image' src='<?php echo e($image_footer); ?>?<?php echo time(); ?>' alt="<?php echo e(trans('langMetaImage')); ?>">
                        </a>
                        <?php else: ?>
                        <img class='footer-image' src='<?php echo e($image_footer); ?>?<?php echo time(); ?>' alt="<?php echo e(trans('langMetaImage')); ?>">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if(get_config('footer_intro')): ?>
                    <div class='col-12 d-flex justify-content-center align-items-center gap-3 p-3 footer-text m-auto'>
                        <?php echo get_config('footer_intro'); ?>

                    </div>
                    <div class='col-12 m-auto border-bottom-footer-text mb-3'></div>
                <?php endif; ?>
                <div class='col-12 d-flex d-flex justify-content-center align-items-center pb-3 gap-3 flex-wrap'>
                    <?php if(!get_config('dont_display_about_menu')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/about.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langPlatformIdentity')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                    <?php if(!get_config('dont_display_contact_menu')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/contact.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langContact')); ?>

                            </a>
                        </div>
                    <?php endif; ?>

                    <?php if(!get_config('dont_display_manual_menu')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/manual.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langManuals')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                    <div>
                        <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/terms.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                            <?php echo e(trans('langUsageTerms')); ?>

                        </a>
                    </div>
                    <?php if(get_config('activate_privacy_policy_text')): ?>
                        <div>
                            <a class="a_tools_site_footer" href="<?php echo e($urlAppend); ?>info/privacy_policy.php" <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>
                                <?php echo e(trans('langPrivacyPolicy')); ?>

                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class='col-12 border-bottom-footer'></div>
                <div class="col-12 mt-auto d-flex justify-content-between align-items-center flex-wrap gap-3 pt-3">
                    <a class="copyright" href='<?php echo e($urlAppend); ?>info/copyright.php' <?php if($_SESSION['provider'] == 'lti_publish'): ?> target="_blank" <?php endif; ?>>Copyright © <?php echo e(date('Y')); ?> All rights reserved</a>
                    <?php if(get_config('enable_social_sharing_links')): ?>
                        <div class='d-flex gap-3 justify-content-end'>
                            <?php if(get_config('link_fb')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_fb'); ?>" target="_blank" aria-label="Facebook: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-facebook-f social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                            <?php if(get_config('link_tw')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_tw'); ?>" target="_blank" aria-label="Twitter: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-twitter social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                            <?php if(get_config('link_ln')): ?>
                                <a class='a_tools_site_footer' href="<?php echo get_config('link_ln'); ?>" target="_blank" aria-label="Linkedin: <?php echo e(trans('langOpenNewTab')); ?>">
                                    <i class="fab fa-linkedin-in social-icon-tool"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</footer>
<?php /**PATH /var/www/html/resources/views/layouts/partials/footerDesktop.blade.php ENDPATH**/ ?>