

<?php $__env->startPush('head_styles'); ?>
    <link href="<?php echo e($urlAppend); ?>js/jstree3/themes/proton/style.min.css" type='text/css' rel='stylesheet'>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('head_scripts'); ?>
    <script type='text/javascript' src='<?php echo e($urlAppend); ?>js/jstree3/jstree.min.js'></script>
    <script type='text/javascript' src='<?php echo e($urlAppend); ?>js/pwstrength.js'></script>
    <script type='text/javascript' src='<?php echo e($urlAppend); ?>js/tools.js'></script>

    <script type='text/javascript'>
        var lang = {
            pwStrengthTooShort: "<?php echo e(js_escape(trans('langPwStrengthTooShort'))); ?>",
            pwStrengthWeak: "<?php echo e(js_escape(trans('langPwStrengthWeak'))); ?>",
            pwStrengthGood: "<?php echo e(js_escape(trans('langPwStrengthGood'))); ?>",
            pwStrengthStrong: "<?php echo e(js_escape(trans('langPwStrengthStrong'))); ?>"
        }

        function deactivate_input_password () {
            $('#coursepassword, #faculty_users_registration').attr('disabled', 'disabled');
            $('#coursepassword').closest('div.form-group').addClass('invisible');
        }

        function activate_input_password () {
            $('#coursepassword, #faculty_users_registration').removeAttr('disabled', 'disabled');
            $('#coursepassword').closest('div.form-group').removeClass('invisible');
        }

        function displayCoursePassword() {
            if ($('#courseclose, #courseiactive').is(":checked")) {
                deactivate_input_password ();
            } else {
                activate_input_password ();
            }
        }

        $(document).ready(function() {

            $('#coursepassword').keyup(function() {
                $('#result').html(checkStrength($('#coursepassword').val()))
            });

            displayCoursePassword();

            $('#courseopen, #coursewithregistration').click(function(event) {
                activate_input_password();
            });

            $('#courseclose, #courseinactive').click(function(event) {
                deactivate_input_password();
            });

            $('input[name=l_radio]').change(function () {
                if ($('#cc_license').is(":checked")) {
                    $('#cc').show();
                } else {
                    $('#cc').hide();
                }
            }).change();

            $('.chooseCourseImage').on('click',function(){
                var id_img = this.id;
                alert('<?php echo e(js_escape(trans('langImageSelected'))); ?>!');
                document.getElementById('choose_from_list').value = id_img;
                $('#CoursesImagesModal').modal('hide');
                document.getElementById('selectedImage').value = '<?php echo e(trans('langSelect')); ?>:'+id_img;
            });

            if ($("#radio_collaborative_helper").length > 0) {
                if(document.getElementById("radio_collaborative_helper").value == 0){
                    document.getElementById("radio_collaborative").style.display="none";
                }
            }
            $('#type_collab').on('click',function(){
                if($('#type_collab').is(":checked")){
                    document.getElementById("radio_flippedclassroom").style.display="none";
                    document.getElementById("radio_activity").style.display="none";
                    document.getElementById("radio_wall").style.display="none";
                    document.getElementById("radio_collaborative").style.display="block";
                }else{
                    document.getElementById("radio_flippedclassroom").style.display="block";
                    document.getElementById("radio_activity").style.display="block";
                    document.getElementById("radio_wall").style.display="block";
                    document.getElementById("radio_collaborative").style.display="none";
                }
            });

        });
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="col-12 main-section">
    <div class='<?php echo e($container); ?> main-container'>
        <div class="row m-auto">

              <?php echo $__env->make('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

              <?php echo $__env->make('layouts.partials.legend_view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

              <?php echo $action_bar; ?>


              <?php echo $__env->make('layouts.partials.show_alert', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

             <div class='col-12'>
                <div class='alert alert-info'>
                    <i class='fa-solid fa-circle-info fa-lg'></i>
                    <span><?php echo e(trans('langFieldsOptionalNote')); ?></span>
                </div>
             </div>

              <div class='col-lg-8 col-12'>
                <div class='form-wrapper form-edit border-0 px-0'>
                  <form class='form-horizontal' role='form' method='post' name='createform' action="<?php echo e($_SERVER['SCRIPT_NAME']); ?>" enctype="multipart/form-data" onsubmit=\"return validateNodePickerForm();\">
                    <fieldset>
                    <legend class='mb-0' aria-label="<?php echo e(trans('langForm')); ?>"></legend>
                    <div class='form-group'>
                        <label for='title' class='col-12 control-label-notes'><?php echo e(trans('langTitle')); ?> <span class='asterisk Accent-200-cl'>(*)</span></label>
                        <div class='col-12'>
                          <input name='title' id='title' type='text' class='form-control' value="<?php echo e($title); ?>" placeholder="<?php echo e(trans('langCourseTitle')); ?>">
                            <span class='help-block Accent-200-cl'><?php echo e(Session::getError('title')); ?></span>
                        </div>
                    </div>
                    <div class='form-group mt-4'>
                        <label for='public_code' class='col-12 control-label-notes'><?php echo e(trans('langCode')); ?></label>
                        <div class='col-sm-12'>
                          <input name='public_code' id='public_code' type='text' class='form-control' value = "<?php echo e($public_code); ?>"  placeholder="<?php echo e(trans('langOptional')); ?>">
                        </div>
                    </div>
                    <div class='form-group mt-4'>
                        <label for='dialog-set-value' class='col-sm-12 control-label-notes'><?php echo e(trans('langFaculty')); ?> <span class='asterisk Accent-200-cl'>(*)</span></label>
                        <div class='col-sm-12'>
                          <?php echo $buildusernode; ?>

                        </div>
                    </div>
                    <div class='form-group mt-4'>
                        <label for='prof_names' class='col-sm-12 control-label-notes'><?php echo e(trans('langTeachers')); ?></label>
                        <div class='col-sm-12'>
                              <input class='form-control' type='text' name='prof_names' id='prof_names' value= "<?php echo e($prof_names); ?>">
                        </div>
                    </div>
                    <div class='form-group mt-4'>
                        <label for='lang_selected' class='col-sm-12 control-label-notes'><?php echo e(trans('langLanguage')); ?></label>
                        <div class='col-sm-12'>
                              <?php echo $lang_select_options; ?>

                        </div>
                    </div>

                    <div id='image_field' class='row form-group mt-4'>
                        <label for='course_image' class='col-12 control-label-notes'><?php echo e(trans('langCourseImage')); ?></label>
                        <div class='col-12'>
                            <?php echo fileSizeHidenInput(); ?>

                            <ul class='nav nav-tabs' id='nav-tab' role='tablist'>
                                <li class='nav-item' role='presentation'>
                                    <button class='nav-link active' id='tabs-upload-tab' data-bs-toggle='tab' data-bs-target='#tabs-upload' type='button' role='tab' aria-controls='tabs-upload' aria-selected='true'> <?php echo e(trans('langUpload')); ?></button>
                                </li>
                                <li class='nav-item' role='presentation'>
                                    <button class='nav-link' id='tabs-selectImage-tab' data-bs-toggle='tab' data-bs-target='#tabs-selectImage' type='button' role='tab' aria-controls='tabs-selectImage' aria-selected='false'><?php echo e(trans('langAddPicture')); ?></button>
                                </li>
                            </ul>
                            <div class='tab-content mt-3' id='tabs-tabContent'>
                                <div class='tab-pane fade show active' id='tabs-upload' role='tabpanel' aria-labelledby='tabs-upload-tab'>
                                    <input type='file' name='course_image' id='course_image'>
                                </div>
                                <div class='tab-pane fade' id='tabs-selectImage' role='tabpanel' aria-labelledby='tabs-selectImage-tab'>
                                    <button type='button' class='btn submitAdminBtn' data-bs-toggle='modal' data-bs-target='#CoursesImagesModal'>
                                        <i class='fa-solid fa-image settings-icons'></i>&nbsp;<?php echo e(trans('langSelect')); ?>

                                    </button>
                                    <input type='hidden' id='choose_from_list' name='choose_from_list'>
                                    <label for='selectedImage'><?php echo e(trans('langImageSelected')); ?>:</label>
                                    <input type='text'class='form-control border-0 pe-none px-0' id='selectedImage'>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class='form-group mt-4'>
                        <label for='description' class='col-sm-12 control-label-notes'>
                            <?php echo e(trans('langDescrInfo')); ?>

                            <small><?php echo e(trans('langOptional')); ?></small>
                        </label>
                        <div class='col-sm-12'>
                              <?php echo $rich_text_editor; ?>

                        </div>
                    </div>

                    <?php if(get_config('show_collaboration') && !get_config('show_always_collaboration')): ?>
                        <div class='form-group mt-4'>
                            <div class='col-sm-12'>
                                <label class='control-label-notes' for='type_collab'><?php echo trans('langWhatTypeOfCourse'); ?></label>
                                <div class='checkbox'>
                                    <label class='label-container' aria-label="<?php echo e(trans('langSelect')); ?>">
                                        <input type='checkbox' id='type_collab' name='is_type_collaborative'>
                                        <span class='checkmark'></span>
                                        <?php echo trans('langTypeCollaboration'); ?>

                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class='form-group mt-4'>
                       <div class='col-sm-12 control-label-notes mb-2'><?php echo e(trans('langCourseFormat')); ?></div>
                        <div class="radio mb-2">
                          <label>
                              <input type='radio' name='view_type' value='simple' id='simple'>
                              <?php echo e(trans('langCourseSimpleFormat')); ?>

                          </label>
                        </div>
                        <div class="radio mb-2">
                          <label>
                            <input type='radio' name='view_type' value='units' id='units' checked>
                            <?php echo e(trans('langWithCourseUnits')); ?>

                            </label>
                        </div>
                        <div class="radio mb-2 <?php if(get_config('show_collaboration') and get_config('show_always_collaboration')): ?> d-none <?php endif; ?>" id="radio_activity">
                          <label>
                            <input type="radio" name="view_type" value="activity" id="activity">
                            <?php echo e(trans('langCourseActivityFormat')); ?>

                          </label>
                        </div>
                        <div class="radio mb-2 <?php if(get_config('show_collaboration') and get_config('show_always_collaboration')): ?> d-none <?php endif; ?>" id="radio_wall">
                          <label>
                            <input type='radio' name='view_type' value='wall' id='wall'>
                            <?php echo e(trans('langCourseWallFormat')); ?>

                          </label>
                        </div>
                        <div class="radio mb-2 <?php if(get_config('show_collaboration') and get_config('show_always_collaboration')): ?> d-none <?php endif; ?>" id="radio_flippedclassroom">
                            <label>
                                <input type='radio' name='view_type' value='flippedclassroom' id='flippedclassroom'>
                                <?php echo e(trans('langFlippedClassroom')); ?>

                            </label>
                        </div>

                        <div class="radio
                            <?php if(!get_config('show_collaboration') and !get_config('show_always_collaboration')): ?>
                                d-none
                            <?php elseif(is_module_disable(MODULE_ID_SESSION)): ?>
                                d-none
                            <?php endif; ?>" id="radio_collaborative">
                            <label>
                                <input type='radio' name='view_type' value='sessions' id='sessions'>
                                <?php echo e(trans('langSessionType')); ?>

                            </label>
                        </div>

                        <?php if(get_config('show_collaboration') and !get_config('show_always_collaboration')): ?>
                            <input type="hidden" id="radio_collaborative_helper" value="0">
                        <?php endif; ?>
                    </div>

                    <div class='form-group mt-4'>
                      <div class='col-sm-12 control-label-notes mb-2'><?php echo e(trans('langOpenCoursesLicense')); ?></div>

                      <div class='radio mb-2'>
                        <label>
                          <input type='radio' name='l_radio' value='0' checked>
                          <?php echo e($license_0); ?>

                        </label>
                      </div>

                      <div class='radio mb-2'>
                        <label>
                          <input type='radio' name='l_radio' value='10'>
                          <?php echo e($license_10); ?>

                        </label>
                      </div>

                      <div class='radio'>
                        <label>
                          <input id='cc_license' type='radio' name='l_radio' value='cc'>
                          <?php echo e(trans("langCMeta['course_license']")); ?>

                        </label>
                      </div>

                    </div>

                    <div class='form-group mt-4' id='cc'>
                        <div class='col-sm-12 col-sm-offset-2'>
                            <label class='mb-0' for='course_license_id' aria-label="<?php echo e(trans('langOpenCoursesLicense')); ?>"></label>
                              <?php echo $selection_license; ?>

                        </div>
                    </div>

                    <div class='form-group mt-4'>

                           <div class='col-sm-12 control-label-notes mb-2'><?php echo e(trans('langAvailableTypes')); ?></div>

                            <div class='radio mb-3'>
                              <label>
                                <input class='input-StatusCourse' id='courseopen' type='radio' name='formvisible' value='2'
                                    <?php if($default_access === COURSE_OPEN): ?> checked <?php endif; ?>>
                                <label for="courseopen" aria-label="<?php echo e(trans('langOpenCourse')); ?>"><?php echo $icon_course_open; ?></label>
                                <?php echo e(trans('langOpenCourse')); ?>

                              </label>
                              <div class='help-block'><?php echo e(trans('langPublic')); ?></div>
                            </div>

                            <div class='radio mb-3'>
                              <label>
                                <input class='input-StatusCourse' id='coursewithregistration' type='radio' name='formvisible' value='1'
                                    <?php if($default_access === COURSE_REGISTRATION): ?> checked <?php endif; ?>>
                                <label for="coursewithregistration" aria-label="<?php echo e(trans('langRegCourse')); ?>"><?php echo $icon_course_registration; ?></label>
                                <?php echo e(trans('langRegCourse')); ?>

                              </label>
                              <div class='help-block'><?php echo e(trans('langPrivOpen')); ?></div>
                            </div>

                            <div class='radio mb-3'>
                              <label>
                                <input class='input-StatusCourse' id='courseclose' type='radio' name='formvisible' value='0'
                                  <?php if($default_access === COURSE_CLOSED): ?> checked <?php endif; ?>>
                                <label for="courseclose" aria-label="<?php echo e(trans('langClosedCourse')); ?>"><?php echo $icon_course_closed; ?></label>
                                <?php echo e(trans('langClosedCourse')); ?>

                              </label>
                              <div class='help-block'><?php echo e(trans('langClosedCourseShort')); ?></div>
                            </div>

                            <div class='radio'>
                              <label>
                                  <input class='input-StatusCourse' id='courseinactive' type='radio' name='formvisible' value='3'
                                    <?php if($default_access === COURSE_INACTIVE): ?> checked <?php endif; ?>>
                                  <label for="courseinactive" aria-label="<?php echo e(trans('langInactiveCourse')); ?>"><?php echo $icon_course_inactive; ?></label>
                                  <?php echo e(trans('langInactiveCourse')); ?>

                              </label>
                              <div class='help-block'><?php echo e(trans('langCourseInactive')); ?></div>
                            </div>
                      </div>

                     <div class='form-group mt-3'>
                         <div class='checkbox mb-2 mt-4'>
                             <label class='label-container' aria-label="<?php echo e(trans('langSelect')); ?>">
                                 <input type='checkbox' id='faculty_users_registration' name='faculty_users_registration'>
                                 <span class='checkmark'></span><?php echo e(trans('langFacultyUsersRegistrationLegend')); ?>

                             </label>
                         </div>
                        <label for='coursepassword' class='col-sm-12 control-label-notes'><?php echo e(trans('langOptPassword')); ?></label>
                        <div class='col-sm-12'>
                              <input class='form-control' id='coursepassword' type='text' name='password' value='<?php echo e(trans('password')); ?>' autocomplete='off'>
                        </div>
                        <div class='col-sm-12' text-center padding-thin>
                            <span id='result'></span>
                        </div>
                     </div>

                     <div class='form-group mt-5 d-flex justify-content-end align-items-center gap-2 flex-wrap'>
                          <input class='btn submitAdminBtn text-nowrap' type='submit' name='create_course' value='<?php echo e(trans('langCourseCreate')); ?>'>
                          <a href='<?php echo e($cancel_link); ?>' class='btn cancelAdminBtn text-nowrap'><?php echo e(trans('langCancel')); ?></a>
                      </div>

                    <div class='modal fade' id='CoursesImagesModal' tabindex='-1' aria-labelledby='CoursesImagesModalLabel' aria-hidden='true'>
                        <div class='modal-dialog modal-lg'>
                            <div class='modal-content'>
                                <div class='modal-header'>
                                    <div class='modal-title' id='CoursesImagesModalLabel'><?php echo e(trans('langCourseImage')); ?></div>
                                    <button type='button' class='close' data-bs-dismiss='modal' aria-label="<?php echo e(trans('langClose')); ?>"></button>
                                </div>
                                <div class='modal-body'>
                                    <div class='row row-cols-1 row-cols-md-2 g-4'>
                                        <?php echo $image_content; ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php echo generate_csrf_token_form_field(); ?>

                </fieldset>
              </form>
            </div>
          </div>
          <div class='col-lg-4 col-12 d-none d-md-none d-lg-block text-end'>
            <img class='form-image-modules' src='<?php echo get_form_image(); ?>' alt="<?php echo e(trans('langImgFormsDes')); ?>">
          </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/resources/views/modules/create_course/index.blade.php ENDPATH**/ ?>