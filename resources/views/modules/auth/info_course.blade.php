@extends('layouts.default')

@section('content')

<div class="col-12 main-section">
<div class='{{ $container }} main-container'>
        <div class="row m-auto">

            @if(isset($_SESSION['uid']))
                @include('layouts.common.breadcrumbs', ['breadcrumbs' => $breadcrumbs])
            @endif

            <div class='col-12 my-4'>
                <h1>{{ $pageName }}</h1>
            </div>

            <div class="col-12">

                    <div class="card card-course-info px-lg-2 py-lg-1 p-1 mb-2">
                        <div class="row row-cols-1 row-cols-md-2 g-1">
                            <div class="col-md-4 col d-flex justify-content-center justify-content-md-start">
                                @if($c->course_image == NULL)
                                    @if($c->is_collaborative)
                                        <img class='img-fluid rounded-start course_info_img' src="{{ $urlAppend }}template/modern/images/default-collaboration.jpg" alt="{{ trans('langImageSelected') }}" />
                                    @else
                                        <img class='img-fluid rounded-start course_info_img' src="{{ $urlAppend }}resources/img/ph1.jpg" alt="{{ trans('langImageSelected') }}" />
                                    @endif
                                @else
                                    <img class='img-fluid rounded-start course_info_img' src="{{ $urlAppend }}courses/{{ $c->code }}/image/{{ $c->course_image }}" alt="{{ trans('langImageSelected') }}" />
                                @endif
                            </div>
                            <div class="col-md-8 col">
                                <div class="card-body p-0">
                                    <div class='d-flex justify-content-start align-items-center gap-2 flex-wrap'>
                                        <h2 class="mb-0">{{ $c->title }}</h2>
                                        {!! course_access_icon($c->visible) !!}
                                        @if($c->course_license > 0)
                                            {!! copyright_info($c->id) !!}
                                        @endif
                                        @if($cdm_data)
                                            <span class="badge bg-info text-white">
                                                <i class="fa fa-graduation-cap"></i> CDM Import
                                            </span>
                                            @if(str_contains($cdm_data['Description'] ?? '', 'Think Pair Share'))
                                                <span class="badge bg-success">Think-Pair-Share</span>
                                            @endif
                                            @if(str_contains($cdm_data['Description'] ?? '', 'Problem Based Learning'))
                                                <span class="badge bg-warning">Problem-Based Learning</span>
                                            @endif
                                        @endif
                                    </div>
                                    <p class="card-text mt-0 mb-0">({{ $c->public_code }})&nbsp;- &nbsp;{{ $c->prof_names }}</p>
                                    <!-- Enhanced Course Description Section -->
                                    @if($cdm_formatted)
                                        <!-- CDM-Enhanced Course Description -->
                                        <div class="enhanced-course-description">
                                            @if(!$c->is_collaborative)
                                            <p class='form-label mb-1 text-primary'><i class="fa fa-graduation-cap"></i> {{ trans('langCourseProgram')}} & Educational Design</p>
                                            @else
                                            <p class='form-label mb-1 text-primary'><i class="fa fa-users"></i> {{ trans('langCollabDes')}}</p>
                                            @endif

                                            <!-- Course Overview Cards -->
                                            <div class="row mb-2">
                                                @if(!empty($cdm_formatted['education_level']))
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="card h-100 border-primary">
                                                        <div class="card-body text-center p-2">
                                                            <i class="fa fa-school text-primary fa-lg mb-1"></i>
                                                            <h6 class="card-title mb-1">Education Level</h6>
                                                            <p class="card-text small mb-0">{{ $cdm_formatted['education_level'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if(!empty($cdm_formatted['duration']))
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="card h-100 border-info">
                                                        <div class="card-body text-center p-2">
                                                            <i class="fa fa-clock text-info fa-lg mb-1"></i>
                                                            <h6 class="card-title mb-1">Duration</h6>
                                                            <p class="card-text small mb-0">{{ $cdm_formatted['duration'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if(!empty($cdm_formatted['subject_area']))
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="card h-100 border-success">
                                                        <div class="card-body text-center p-2">
                                                            <i class="fa fa-book text-success fa-lg mb-1"></i>
                                                            <h6 class="card-title mb-1">Subject Area</h6>
                                                            <p class="card-text small mb-0">{{ $cdm_formatted['subject_area'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif

                                                @if(!empty($cdm_formatted['strategy']))
                                                <div class="col-md-3 col-sm-6 mb-2">
                                                    <div class="card h-100 border-warning">
                                                        <div class="card-body text-center p-2">
                                                            <i class="fa fa-lightbulb text-warning fa-lg mb-1"></i>
                                                            <h6 class="card-title mb-1">Teaching Strategy</h6>
                                                            <p class="card-text small mb-0">{{ $cdm_formatted['strategy'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>





                                        </div>

                                        <!-- Custom CSS for Enhanced Description -->
                                        <style>
                                        .enhanced-course-description .card {
                                            transition: all 0.3s ease;
                                        }
                                        .enhanced-course-description .card:hover {
                                            transform: translateY(-5px);
                                            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
                                        }
                                        .enhanced-course-description .fa-lg {
                                            opacity: 0.8;
                                        }
                                        .enhanced-course-description .badge {
                                            font-size: 0.75em;
                                            padding: 0.5em 0.75em;
                                        }
                                        .enhanced-course-description .border-start {
                                            border-left-width: 4px !important;
                                        }
                                        .enhanced-course-description h6 {
                                            font-weight: 600;
                                            margin-bottom: 0.75rem;
                                        }
                                        </style>
                                    @else
                                        <!-- Fallback to original description for non-CDM courses -->
                                        @if(empty($c->description))
                                            @if(!$c->is_collaborative)
                                            <p class='form-label mb-1'>{{ trans('langCourseProgram')}}</p>
                                            @else
                                            <p class='form-label mb-1'>{{ trans('langCollabDes')}}</p>
                                            @endif
                                            <p>{{ trans('langThisCourseDescriptionIsEmpty') }}</p>
                                        @else
                                            @if(!$c->is_collaborative)
                                            <p class='form-label mb-1'>{{ trans('langCourseProgram')}}</p>
                                            @else
                                            <p class='form-label mb-1'>{{ trans('langCollabDes')}}</p>
                                            @endif
                                            <p>{!! $c->description !!}</p>
                                        @endif
                                    @endif

                                    <p class='form-label mb-1 mt-4'>{{ trans('langCreationDate')}}</p>
                                    <p>{{ format_locale_date(strtotime($c->created), null, false) }}</p>

                                    <div class='col-12 mt-4 d-flex justify-content-md-start justify-content-center'>
                                        @if(isset($_SESSION['uid']))
                                        <a class='btn submitAdminBtnDefault d-flex jystify-content-start align-items-center gap-2' href='{{ $urlServer }}modules/course_home/course_home.php?course={{ $c->code }}'>
                                            @if($c->is_collaborative)
                                                {{ trans('langPageCollaboration')}}
                                            @else
                                                {{ trans('langCoursePage')}}
                                            @endif
                                            <i class="fa-solid fa-circle-right"></i>
                                        </a>
                                        @else
                                        <a class='btn submitAdminBtnDefault d-flex jystify-content-start align-items-center gap-2' href='{{ $urlServer }}main/login_form.php?next={{ urlencode($urlServer . 'modules/course_home/course_home.php?course=' . $c->code) }}'>
                                            <i class="fa fa-sign-in"></i> Login to Access Course
                                            <i class="fa-solid fa-circle-right"></i>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            @if($cdm_data)
            <!-- Comprehensive CDM Information Section -->
            <div class='col-12 mt-4'>
                <div class="card border-info">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">
                            <i class="fa fa-graduation-cap"></i> Course Design Model (CDM) Complete Analysis
                        </h4>
                        <small>Comprehensive educational metadata and learning design information</small>
                    </div>
                    <div class="card-body">

                        <!-- Basic Information Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                @if(isset($cdm_data['EducationLevel']))
                                <div class="mb-3 p-3 bg-primary bg-opacity-10 rounded border-start border-primary border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-school text-primary fs-5"></i>
                                        <div>
                                            <strong>Education Level</strong><br>
                                            <span class="badge bg-primary">{{ $cdm_data['EducationLevel'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(isset($cdm_data['SubjectArea']))
                                <div class="mb-3 p-3 bg-success bg-opacity-10 rounded border-start border-success border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-book text-success fs-5"></i>
                                        <div>
                                            <strong>Subject Area</strong><br>
                                            <span class="text-success fw-bold">{{ $cdm_data['SubjectArea'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if(isset($cdm_data['DurationNumber']) && isset($cdm_data['DurationType']))
                                <div class="mb-3 p-3 bg-warning bg-opacity-10 rounded border-start border-warning border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-clock text-warning fs-5"></i>
                                        <div>
                                            <strong>Course Duration</strong><br>
                                            <span class="badge bg-warning text-dark">{{ $cdm_data['DurationNumber'] }} {{ $cdm_data['DurationType'] }}</span>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                @if(isset($cdm_data['Prerequisites']) && is_array($cdm_data['Prerequisites']))
                                <div class="mb-3 p-3 bg-secondary bg-opacity-10 rounded border-start border-secondary border-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fa fa-list-check text-secondary fs-5"></i>
                                        <div>
                                            <strong>Prerequisites</strong><br>
                                            @if(empty($cdm_data['Prerequisites']))
                                                <span class="text-muted">None specified</span>
                                            @else
                                                @foreach($cdm_data['Prerequisites'] as $prereq)
                                                    <span class="badge bg-secondary me-1">{{ $prereq }}</span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Actors and Roles Row -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                @if(isset($cdm_data['Actors']) && is_array($cdm_data['Actors']))
                                <div class="mb-3">
                                    <h5><i class="fa fa-users text-info"></i> Course Participants</h5>
                                    <div class="p-3 bg-info bg-opacity-10 rounded">
                                        @foreach($cdm_data['Actors'] as $actor)
                                            @if($actor === 'Student')
                                                <span class="badge bg-info me-1 mb-1"><i class="fa fa-user-graduate"></i> {{ $actor }}</span>
                                            @elseif($actor === 'Teacher')
                                                <span class="badge bg-success me-1 mb-1"><i class="fa fa-chalkboard-teacher"></i> {{ $actor }}</span>
                                            @elseif($actor === 'Group')
                                                <span class="badge bg-primary me-1 mb-1"><i class="fa fa-users"></i> {{ $actor }}</span>
                                            @else
                                                <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-user"></i> {{ $actor }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if(isset($cdm_data['Learners']) && is_array($cdm_data['Learners']))
                                <div class="mb-3">
                                    <h5><i class="fa fa-user-graduate text-primary"></i> Target Learners</h5>
                                    <div class="p-3 bg-primary bg-opacity-10 rounded">
                                        @foreach($cdm_data['Learners'] as $learner)
                                            <span class="badge bg-primary me-1 mb-1">{{ $learner }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                            <div class="col-md-6">
                                @if(isset($cdm_data['StaffRoles']) && is_array($cdm_data['StaffRoles']))
                                <div class="mb-3">
                                    <h5><i class="fa fa-user-tie text-secondary"></i> Staff Roles</h5>
                                    <div class="p-3 bg-secondary bg-opacity-10 rounded">
                                        @foreach($cdm_data['StaffRoles'] as $role)
                                            <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-briefcase"></i> {{ $role }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                @if(isset($cdm_data['Simple_activity_types']) && is_array($cdm_data['Simple_activity_types']))
                                <div class="mb-3">
                                    <h5><i class="fa fa-tasks text-warning"></i> Activity Types Available</h5>
                                    <div class="p-3 bg-warning bg-opacity-10 rounded">
                                        @foreach($cdm_data['Simple_activity_types'] as $type)
                                            @switch($type)
                                                @case('Creating')
                                                    <span class="badge bg-success me-1 mb-1"><i class="fa fa-plus-circle"></i> {{ $type }}</span>
                                                    @break
                                                @case('Evaluating')
                                                    <span class="badge bg-danger me-1 mb-1"><i class="fa fa-check-circle"></i> {{ $type }}</span>
                                                    @break
                                                @case('Analyzing')
                                                    <span class="badge bg-info me-1 mb-1"><i class="fa fa-search"></i> {{ $type }}</span>
                                                    @break
                                                @case('Applying')
                                                    <span class="badge bg-primary me-1 mb-1"><i class="fa fa-cogs"></i> {{ $type }}</span>
                                                    @break
                                                @case('Understanding')
                                                    <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-lightbulb"></i> {{ $type }}</span>
                                                    @break
                                                @case('Remembering')
                                                    <span class="badge bg-dark me-1 mb-1"><i class="fa fa-brain"></i> {{ $type }}</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-warning me-1 mb-1">{{ $type }}</span>
                                            @endswitch
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Resource Information -->
                        @if(isset($cdm_data['Resource_types']) && is_array($cdm_data['Resource_types']))
                        <div class="mb-4">
                            <h5><i class="fa fa-folder-open text-info"></i> Resource Types Available</h5>
                            <div class="p-3 bg-light rounded">
                                @foreach($cdm_data['Resource_types'] as $resource)
                                    @switch($resource)
                                        @case('Video')
                                            <span class="badge bg-danger me-1 mb-1"><i class="fa fa-video"></i> {{ $resource }}</span>
                                            @break
                                        @case('Quiz')
                                            <span class="badge bg-warning me-1 mb-1"><i class="fa fa-question-circle"></i> {{ $resource }}</span>
                                            @break
                                        @case('Hypertext')
                                            <span class="badge bg-primary me-1 mb-1"><i class="fa fa-link"></i> {{ $resource }}</span>
                                            @break
                                        @case('Document')
                                            <span class="badge bg-success me-1 mb-1"><i class="fa fa-file-text"></i> {{ $resource }}</span>
                                            @break
                                        @case('Assessment')
                                            <span class="badge bg-info me-1 mb-1"><i class="fa fa-clipboard-check"></i> {{ $resource }}</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary me-1 mb-1"><i class="fa fa-file"></i> {{ $resource }}</span>
                                    @endswitch
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <!-- Learning Goals Section -->
                        @if(isset($cdm_data['Goals']) && is_array($cdm_data['Goals']))
                        <div class="mb-4">
                            <h5><i class="fa fa-bullseye text-success"></i> Learning Goals & Objectives</h5>
                            <div class="p-4 bg-success bg-opacity-10 rounded border border-success">
                                <div class="row">
                                    @foreach($cdm_data['Goals'] as $index => $goal)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start gap-2">
                                            <span class="badge bg-success rounded-circle">{{ $index + 1 }}</span>
                                            <span>{{ $goal }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Course Description with Methodology -->
                        @if(isset($cdm_data['Description']) && !empty($cdm_data['Description']))
                        <div class="mb-4">
                            <h5><i class="fa fa-info-circle text-primary"></i> Educational Methodology & Approach</h5>
                            <div class="p-4 bg-primary bg-opacity-10 rounded border border-primary">
                                <div class="mb-3">
                                    {!! nl2br(e($cdm_data['Description'])) !!}
                                </div>

                                @if(str_contains($cdm_data['Description'], 'Think Pair Share'))
                                <div class="mt-3 p-3 bg-white rounded border border-success">
                                    <h6><i class="fa fa-users text-success"></i> Think-Pair-Share Methodology</h6>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <span class="badge bg-info">1. Think (Individual)</span>
                                        <span class="badge bg-warning">2. Pair (Collaborative)</span>
                                        <span class="badge bg-success">3. Share (Group)</span>
                                    </div>
                                </div>
                                @endif

                                @if(str_contains($cdm_data['Description'], 'Problem Based Learning'))
                                <div class="mt-3 p-3 bg-white rounded border border-warning">
                                    <h6><i class="fa fa-puzzle-piece text-warning"></i> Problem-Based Learning Approach</h6>
                                    <small class="text-muted">Students learn through solving authentic, real-world problems</small>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endif

                        <!-- Copyright and Licensing Information -->
                        @if(isset($cdm_data['Resource_copyright']) && is_array($cdm_data['Resource_copyright']))
                        <div class="mb-4">
                            <h5><i class="fa fa-copyright text-secondary"></i> Resource Licensing</h5>
                            <div class="p-3 bg-light rounded">
                                <div class="row">
                                    @foreach($cdm_data['Resource_copyright'] as $license)
                                    <div class="col-auto">
                                        @if($license === 'free')
                                            <span class="badge bg-success me-2"><i class="fa fa-unlock"></i> Free Resources Available</span>
                                        @elseif($license === 'proprietary')
                                            <span class="badge bg-warning me-2"><i class="fa fa-lock"></i> Proprietary Resources Included</span>
                                        @else
                                            <span class="badge bg-secondary me-2"><i class="fa fa-info-circle"></i> {{ $license }}</span>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Technical Information -->
                        <div class="mt-5 pt-4 border-top">
                            <div class="row text-center">
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-graduation-cap fa-2x text-info mb-2"></i>
                                        <h6>CDM Import</h6>
                                        <small class="text-muted">Course Design Model</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-tasks fa-2x text-success mb-2"></i>
                                        <h6>Structured Learning</h6>
                                        <small class="text-muted">Activity-Based Design</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-users fa-2x text-primary mb-2"></i>
                                        <h6>Collaborative</h6>
                                        <small class="text-muted">Group & Individual Work</small>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <div class="p-3">
                                        <i class="fa fa-chart-line fa-2x text-warning mb-2"></i>
                                        <h6>Scaffolded</h6>
                                        <small class="text-muted">Progressive Difficulty</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-center">
                            <small class="text-muted">
                                <i class="fa fa-info-circle"></i> This course was imported from a Course Design Model (CDM) file with complete educational metadata, learning objectives, activity structures, and pedagogical methodology preserved.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if($course_content)
            <!-- CDM Course Content Summary -->
            <div class='col-12 mt-4'>
                <div class="card border-success">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0">
                            <i class="fa fa-play-circle"></i> Course Content & Resources
                        </h4>
                        <small>Videos, Quizzes, and Learning Materials from CDM Import</small>
                    </div>
                    <div class="card-body">

                        @if(count($course_content['videos']) > 0)
                        <!-- Videos Section -->
                        <div class="mb-4">
                            <h5><i class="fa fa-video text-danger"></i> Educational Videos</h5>
                            <div class="mb-2 text-end">
                                <a href="{{ $urlServer }}modules/video/index.php?course={{ $c->code }}" class="btn btn-outline-danger btn-sm">
                                    <i class="fa fa-list"></i> View All Videos
                                </a>
                            </div>
                            <div class="row">
                                @foreach($course_content['videos'] as $video)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fa fa-play-circle text-danger"></i>
                                                {{ $video->title }}
                                            </h6>
                                            <p class="card-text">{{ $video->description }}</p>
                                            <div class="d-flex gap-2 flex-wrap">
                                                <a href="{{ $video->url }}" target="_blank" class="btn btn-danger btn-sm">
                                                    <i class="fa fa-external-link"></i> Watch Now
                                                </a>
                                                <a href="{{ $urlServer }}modules/video/index.php?course={{ $c->code }}" class="btn btn-outline-secondary btn-sm">
                                                    <i class="fa fa-list"></i> Videos Page
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(count($course_content['exercises']) > 0)
                        <!-- Quizzes/Exercises Section -->
                        <div class="mb-4">
                            <h5><i class="fa fa-question-circle text-warning"></i> Quizzes & Exercises</h5>
                            <div class="row">
                                @foreach($course_content['exercises'] as $exercise)
                                <div class="col-md-6 mb-3">
                                    <div class="card h-100 border-warning">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fa fa-quiz text-warning"></i>
                                                {{ $exercise->title }}
                                            </h6>
                                            <p class="card-text">{{ $exercise->description }}</p>
                                            <a href="{{ $urlServer }}modules/exercise/index.php?course={{ $c->code }}" class="btn btn-warning btn-sm">
                                                <i class="fa fa-play"></i> Take Quiz
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(count($course_content['documents']) > 0)
                        <!-- Learning Materials Section -->
                        <div class="mb-4">
                            <h5><i class="fa fa-file-text text-info"></i> Learning Materials & Activities</h5>
                            <div class="row">
                                @foreach($course_content['documents'] as $document)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 border-info">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="fa fa-document text-info"></i>
                                                {{ $document->title }}
                                            </h6>
                                            @if(strlen($document->comment) > 100)
                                            <p class="card-text small">{{ substr($document->comment, 0, 100) }}...</p>
                                            @else
                                            <p class="card-text small">{{ $document->comment }}</p>
                                            @endif
                                            <a href="{{ $urlServer }}modules/document/index.php?course={{ $c->code }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i> View Materials
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="text-center mt-4">
                            @if(isset($_SESSION['uid']))
                            <a href="{{ $urlServer }}modules/course_home/course_home.php?course={{ $c->code }}" class="btn btn-primary btn-lg">
                                <i class="fa fa-graduation-cap"></i> Enter Course Homepage
                            </a>
                            @else
                            <a href="{{ $urlServer }}main/login_form.php?next={{ urlencode($urlServer . 'modules/course_home/course_home.php?course=' . $c->code) }}" class="btn btn-primary btn-lg">
                                <i class="fa fa-sign-in"></i> Login to Access Course
                            </a>
                            @endif
                            <p class="text-muted mt-2 small">Access all quizzes, documents, videos, and learning materials inside the course</p>
                        </div>

                    </div>
                </div>
            </div>
            @endif

            @if (!$c->is_collaborative)
                <div class='col-12 mt-4'>
                    <div class='row'>
                        <div class='panel'>
                            <div class='panel-group group-section mt-2 px-0' id='accordionDesC'>
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item px-0 mb-4 bg-transparent">

                                        <div class='d-flex justify-content-between border-bottom-default'>
                                            <a class='accordion-btn d-flex justify-content-start align-items-start gap-2 py-2' role='button' id='btn-syllabus' data-bs-toggle='collapse' href='#collapseDescriptionc' aria-expanded='true' aria-controls='collapseDescriptionc'>
                                                <i class='fa-solid fa-chevron-down settings-icon'></i>
                                                {{ trans('langSyllabus') }}
                                            </a>
                                        </div>
                                        <div class='panel-collapse accordion-collapse collapse border-0 rounded-0 mt-3 show' id='collapseDescriptionc' data-bs-parent='#accordionDesC'>
                                            @if(count($course_descriptions) == 0)
                                                <div class='col-12 mb-4'>
                                                    <p>{{ trans('langNoSyllabus')}}</p>
                                                </div>
                                            @else
                                                @foreach ($course_descriptions as $row)
                                                    <div class='col-12 mb-4'>
                                                        <p class='form-label text-start'>{{ $row->title }}</p>
                                                        {!! standard_text_escape($row->comments) !!}
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>
</div>

@endsection