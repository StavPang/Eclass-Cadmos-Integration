<?php

/*
 *  ========================================================================
 *  * Open eClass
 *  * E-learning and Course Management System
 *  * ========================================================================
 *  * Copyright 2003-2024, Greek Universities Network - GUnet
 *  *
 *  * Open eClass is an open platform distributed in the hope that it will
 *  * be useful (without any warranty), under the terms of the GNU (General
 *  * Public License) as published by the Free Software Foundation.
 *  * The full license can be read in "/info/license/license_gpl.txt".
 *  *
 *  * Contact address: GUnet Asynchronous eLearning Group
 *  *                  e-mail: info@openeclass.org
 *  * ========================================================================
 *
 */

include '../../include/baseTheme.php';

$toolName = $langPreview;
$navigation[] = array('url' => 'listfaculties.php', 'name' => $langSelectFac);

$data['courseId'] = $courseId = course_code_to_id($_GET['c']);
$data['c'] = $c = Database::get()->querySingle("SELECT * FROM course WHERE id = ?d",$courseId);

if ($c->visible == COURSE_INACTIVE) {
    redirect_to_home_page();
}

if (!isset($_SESSION['uid']) and $c->visible == COURSE_CLOSED) {
    redirect_to_home_page();
}

$data['course_descriptions'] = Database::get()->queryArray("SELECT cd.id, cd.title, cd.comments, cd.type, cdt.icon FROM course_description cd
                                    LEFT JOIN course_description_type cdt ON (cd.type = cdt.id)
                                    WHERE cd.course_id = ?d AND cd.visible = 1 ORDER BY cd.order", $courseId);

// Check for CDM metadata in course keywords field
$data['cdm_data'] = null;
$data['cdm_formatted'] = null;
if (!empty($c->keywords)) {
    $cdm_json = json_decode($c->keywords, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($cdm_json['StrategyName'])) {
        $data['cdm_data'] = $cdm_json;

        // Format CDM data for better display
        $data['cdm_formatted'] = [
            'strategy' => $cdm_json['StrategyName'] ?? '',
            'duration' => trim(($cdm_json['DurationNumber'] ?? '') . ' ' . ($cdm_json['DurationType'] ?? '')),
            'education_level' => $cdm_json['EducationLevel'] ?? '',
            'subject_area' => $cdm_json['SubjectArea'] ?? '',
            'description' => $cdm_json['Description'] ?? '',
            'goals' => isset($cdm_json['Goals']) ? (is_array($cdm_json['Goals']) ? $cdm_json['Goals'] : explode('•', $cdm_json['Goals'])) : [],
            'actors' => isset($cdm_json['Actors']) ? (is_array($cdm_json['Actors']) ? $cdm_json['Actors'] : explode(',', $cdm_json['Actors'])) : [],
            'learners' => isset($cdm_json['Learners']) ? (is_array($cdm_json['Learners']) ? $cdm_json['Learners'] : explode(',', $cdm_json['Learners'])) : [],
            'staff_roles' => isset($cdm_json['StaffRoles']) ? (is_array($cdm_json['StaffRoles']) ? $cdm_json['StaffRoles'] : explode(',', $cdm_json['StaffRoles'])) : [],
            'prerequisites' => isset($cdm_json['Prerequisites']) ? (is_array($cdm_json['Prerequisites']) ? implode(', ', $cdm_json['Prerequisites']) : $cdm_json['Prerequisites']) : '',
            'activity_types' => isset($cdm_json['Simple_activity_types']) ? (is_array($cdm_json['Simple_activity_types']) ? $cdm_json['Simple_activity_types'] : explode(',', $cdm_json['Simple_activity_types'])) : [],
            'resource_types' => isset($cdm_json['Resource_types']) ? (is_array($cdm_json['Resource_types']) ? $cdm_json['Resource_types'] : explode(',', $cdm_json['Resource_types'])) : [],
            'resource_copyright' => isset($cdm_json['Resource_copyright']) ? (is_array($cdm_json['Resource_copyright']) ? $cdm_json['Resource_copyright'] : explode(',', $cdm_json['Resource_copyright'])) : []
        ];

        // Clean up arrays by removing empty elements and trimming
        foreach ($data['cdm_formatted'] as $key => $value) {
            if (is_array($value)) {
                $data['cdm_formatted'][$key] = array_filter(array_map('trim', $value), function($item) {
                    return !empty($item);
                });
            }
        }
    }
}

// Get course content summary for CDM courses
$data['course_content'] = null;
if ($data['cdm_data']) {
    // Get videos from the course
    $videos = Database::get()->queryArray("SELECT id, title, url, description FROM videolink WHERE course_id = ?d ORDER BY id", $courseId);

    // Get exercises/quizzes
    $exercises = Database::get()->queryArray("SELECT id, title, description FROM exercise WHERE course_id = ?d ORDER BY id", $courseId);

    // Get CDM activities from keywords (new format) or documents (legacy format)
    $cdm_activities = [];

    // First try new format from keywords
    if (isset($data['cdm_data']['CDM_Activities'])) {
        $cdm_activities = $data['cdm_data']['CDM_Activities'];
    } else if (!empty($c->keywords)) {
        $all_keywords = json_decode($c->keywords, true);
        if (isset($all_keywords['CDM_Activities'])) {
            $cdm_activities = $all_keywords['CDM_Activities'];
        }
    }

    // If no activities found in keywords, fall back to documents for legacy CDM courses
    if (empty($cdm_activities)) {
        $documents = Database::get()->queryArray("SELECT id, title, filename, comment FROM document WHERE course_id = ?d ORDER BY id", $courseId);
        // Convert documents to activity format for backward compatibility
        foreach ($documents as $doc) {
            $cdm_activities[] = [
                'title' => $doc->title,
                'description' => $doc->comment,
                'type' => 'Learning Activity',
                'actor' => '',
                'facilitator' => '',
                'facilitator_role' => '',
                'legacy' => true // Mark as legacy format
            ];
        }
    }

    $data['course_content'] = [
        'videos' => $videos,
        'exercises' => $exercises,
        'cdm_activities' => $cdm_activities
    ];
}

// Auto-enroll logged-in users in CDM courses for testing/demo purposes
if ($data['cdm_data'] && isset($_SESSION['uid'])) {
    $user_enrolled = Database::get()->querySingle("SELECT * FROM course_user WHERE course_id = ?d AND user_id = ?d", $courseId, $_SESSION['uid']);

    if (!$user_enrolled) {
        try {
            Database::get()->query("INSERT INTO course_user SET course_id = ?d, user_id = ?d, status = ?d, reg_date = NOW()",
                $courseId, $_SESSION['uid'], USER_STUDENT);
        } catch (Exception $e) {
            // Enrollment failed, but continue anyway
        }
    }
}

view('modules.auth.info_course', $data);