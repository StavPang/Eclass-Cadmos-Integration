# CDM to OpenEClass Mapping Logic - Complete Integration Guide

## Overview
This document provides a comprehensive explanation of how Course Design Model (CDM) files are mapped to OpenEClass Learning Management System components, including data extraction, storage, and display logic.

---

## 📁 CDM File Structure Analysis

### **CDM File Format**
```
CDM_file.cdm (ZIP archive)
├── source.json           # Main course metadata
├── activities/           # Learning activities data
├── resources/           # External resource links
└── metadata/           # Additional course information
```

### **Key JSON Structure**
```json
{
  "LessonInfo": {
    "StrategyName": "Course methodology",
    "DurationNumber": "Course duration in minutes",
    "EducationLevel": "Target education level",
    "SubjectArea": "Course subject domain"
  },
  "LessonInfoExtras": {
    "Description": "Detailed course description",
    "Goals": ["Learning objective 1", "Learning objective 2"],
    "Prerequisites": ["Prerequisite 1", "Prerequisite 2"],
    "Actors": ["Student", "Teacher", "Group"],
    "Simple_activity_types": ["Understanding", "Applying", "Creating"],
    "Resource_types": ["Video", "Document", "Quiz", "Assessment"]
  },
  "Conceptual": {
    "ConceptualBase": [
      {
        "id": "activity_1",
        "type": "activity-resource",
        "ModalData": {
          "Title": "Activity name",
          "Description": "Activity description",
          "Type": "video|quiz|document",
          "ResourceLocation": "URL or file path",
          "Actor": "Target participant",
          "Facilitator": "Activity facilitator",
          "FacilitatorRole": "Facilitator responsibilities",
          "LearningGoal": ["Goal 1", "Goal 2"],
          "Author": "Content creator",
          "Copyright": "License information"
        }
      }
    ]
  }
}
```

---

## 🔄 Mapping Logic: CDM → OpenEClass

### **1. Course Creation Mapping**

| CDM Field | OpenEClass Table | Field | Processing Logic |
|-----------|------------------|-------|------------------|
| `LessonInfo.StrategyName` | `course` | `title` | Direct mapping as course title |
| `LessonInfo.SubjectArea` | `course` | `public_code` | Processed as course code prefix |
| `LessonInfoExtras.Description` | `course` | `description` | HTML formatted description |
| `LessonInfo.EducationLevel` | `course` | `keywords` | Stored as JSON metadata |
| `LessonInfo.DurationNumber` | `course` | `keywords` | Stored in CDM metadata section |

```php
// Course creation logic
$course_data = [
    'title' => $cdm_json['LessonInfo']['StrategyName'],
    'public_code' => $this->generateCourseCode($cdm_json['LessonInfo']['SubjectArea']),
    'description' => $this->formatDescription($cdm_json['LessonInfoExtras']['Description']),
    'prof_names' => $_SESSION['givenname'] . ' ' . $_SESSION['surname'],
    'visible' => COURSE_REGISTRATION,
    'course_license' => 0
];
```

### **2. Metadata Storage Strategy**

**Storage Location**: `course.keywords` field (JSON format)

```php
// Complete metadata preservation
$metadata = array_merge(
    $cdm_json['LessonInfo'] ?? [],
    $cdm_json['LessonInfoExtras'] ?? []
);

// Enhanced formatting for display
$formatted_metadata = [
    'strategy' => $metadata['StrategyName'] ?? '',
    'duration' => trim(($metadata['DurationNumber'] ?? '') . ' ' . ($metadata['DurationType'] ?? '')),
    'education_level' => $metadata['EducationLevel'] ?? '',
    'subject_area' => $metadata['SubjectArea'] ?? '',
    'description' => $metadata['Description'] ?? '',
    'goals' => $this->processArray($metadata['Goals'] ?? []),
    'actors' => $this->processArray($metadata['Actors'] ?? []),
    'learners' => $this->processArray($metadata['Learners'] ?? []),
    'staff_roles' => $this->processArray($metadata['StaffRoles'] ?? []),
    'prerequisites' => $this->processString($metadata['Prerequisites'] ?? ''),
    'activity_types' => $this->processArray($metadata['Simple_activity_types'] ?? []),
    'resource_types' => $this->processArray($metadata['Resource_types'] ?? []),
    'resource_copyright' => $this->processArray($metadata['Resource_copyright'] ?? [])
];
```

---

## 📚 Content Mapping Logic

### **3. Activity Processing Pipeline**

```php
foreach ($cdm_data['Conceptual']['ConceptualBase'] as $activity) {
    $modal_data = $activity['ModalData'] ?? [];
    $type = $activity['type'] ?? 'activity-simple';

    // Decision tree for content type mapping
    if ($type === 'activity-resource' && isset($modal_data['Type'])) {
        switch (strtolower($modal_data['Type'])) {
            case 'video':
                $this->createVideoLink($activity);      // → videolink table
                break;
            case 'quiz':
                $this->createExercise($activity);       // → exercise table
                break;
            default:
                $this->storeCDMActivity($activity);     // → course.keywords[CDM_Activities]
                break;
        }
    } else {
        $this->storeCDMActivity($activity);             // → course.keywords[CDM_Activities]
    }
}
```

### **4. Video Content Mapping**

| CDM Field | OpenEClass Table | Field | Processing |
|-----------|------------------|-------|------------|
| `ModalData.Title` | `videolink` | `title` | Direct mapping |
| `ModalData.Description` | `videolink` | `description` | HTML formatted |
| `ModalData.ResourceLocation` | `videolink` | `url` | URL validation |
| `ModalData.Author` | `videolink` | `creator` | Author information |

```php
private function createVideoLink($activity) {
    $modal_data = $activity['ModalData'] ?? [];

    Database::get()->query("INSERT INTO videolink SET
        course_id = ?s,
        url = ?s,
        title = ?s,
        description = ?s,
        category = 0,
        creator = ?s,
        date = NOW()",
        $this->course_id,
        $modal_data['ResourceLocation'] ?? '',
        $modal_data['Title'] ?? 'Video',
        $modal_data['Description'] ?? '',
        $modal_data['Author'] ?? $_SESSION['givenname'] . ' ' . $_SESSION['surname']
    );
}
```

### **5. Exercise/Quiz Mapping**

| CDM Field | OpenEClass Table | Field | Processing |
|-----------|------------------|-------|------------|
| `ModalData.Title` | `exercise` | `title` | Direct mapping |
| `ModalData.Description` | `exercise` | `description` | Rich text formatting |
| `ModalData.LearningGoal` | `exercise` | `description` | Appended as objectives |

```php
private function createExercise($activity) {
    $modal_data = $activity['ModalData'] ?? [];
    $description = $modal_data['Description'] ?? '';

    // Append learning goals to description
    if (!empty($modal_data['LearningGoal'])) {
        $description .= "<br><strong>Learning Objectives:</strong><ul>";
        foreach ($modal_data['LearningGoal'] as $goal) {
            $description .= "<li>" . htmlspecialchars($goal) . "</li>";
        }
        $description .= "</ul>";
    }

    Database::get()->query("INSERT INTO exercise SET
        course_id = ?d,
        title = ?s,
        description = ?s,
        active = 1,
        public = 1",
        $this->course_id,
        $modal_data['Title'] ?? 'Exercise',
        $description
    );
}
```

### **6. Learning Activities Independent Storage**

**New Approach**: Activities are stored independently from OpenEClass modules

```php
private function storeCDMActivity($activity) {
    $modal_data = $activity['ModalData'] ?? [];

    // Get existing course keywords
    $existing_keywords = Database::get()->querySingle(
        "SELECT keywords FROM course WHERE id = ?d",
        $this->course_id
    );
    $keywords_data = json_decode($existing_keywords->keywords ?? '{}', true);

    // Initialize CDM_Activities array
    if (!isset($keywords_data['CDM_Activities'])) {
        $keywords_data['CDM_Activities'] = [];
    }

    // Store complete activity data
    $activity_data = [
        'title' => $modal_data['Title'] ?? 'Activity',
        'description' => $modal_data['Description'] ?? '',
        'type' => $modal_data['Type'] ?? 'Learning Activity',
        'actor' => $modal_data['Actor'] ?? '',
        'facilitator' => $modal_data['Facilitator'] ?? '',
        'facilitator_role' => $modal_data['FacilitatorRole'] ?? '',
        'learning_goals' => $modal_data['LearningGoal'] ?? [],
        'author' => $modal_data['Author'] ?? '',
        'copyright' => $modal_data['Copyright'] ?? '',
        'created_at' => date('Y-m-d H:i:s')
    ];

    $keywords_data['CDM_Activities'][] = $activity_data;

    // Update course keywords
    Database::get()->query("UPDATE course SET keywords = ?s WHERE id = ?d",
        json_encode($keywords_data), $this->course_id);
}
```

---

## 🎨 Display Logic: OpenEClass → Frontend

### **7. Course Preview Data Retrieval**

```php
// Load CDM metadata from course keywords
$cdm_data = null;
$cdm_formatted = null;

if (!empty($course->keywords)) {
    $cdm_json = json_decode($course->keywords, true);
    if (json_last_error() === JSON_ERROR_NONE && isset($cdm_json['StrategyName'])) {
        $cdm_data = $cdm_json;

        // Format for template display
        $cdm_formatted = [
            'strategy' => $cdm_json['StrategyName'] ?? '',
            'duration' => trim(($cdm_json['DurationNumber'] ?? '') . ' ' . ($cdm_json['DurationType'] ?? '')),
            'education_level' => $cdm_json['EducationLevel'] ?? '',
            'subject_area' => $cdm_json['SubjectArea'] ?? '',
            'description' => $cdm_json['Description'] ?? '',
            'goals' => $this->processGoalsArray($cdm_json['Goals'] ?? []),
            'actors' => $this->processActorsArray($cdm_json['Actors'] ?? []),
            'learners' => $this->processLearnersArray($cdm_json['Learners'] ?? []),
            'staff_roles' => $this->processStaffRoles($cdm_json['StaffRoles'] ?? []),
            'prerequisites' => $this->processPrerequisites($cdm_json['Prerequisites'] ?? ''),
            'activity_types' => $this->processActivityTypes($cdm_json['Simple_activity_types'] ?? []),
            'resource_types' => $this->processResourceTypes($cdm_json['Resource_types'] ?? []),
            'resource_copyright' => $this->processResourceCopyright($cdm_json['Resource_copyright'] ?? [])
        ];
    }
}
```

### **8. Content Aggregation Strategy**

```php
// Multi-source content gathering
$course_content = [];

if ($cdm_data) {
    // Videos from videolink table
    $videos = Database::get()->queryArray(
        "SELECT id, title, url, description FROM videolink WHERE course_id = ?d ORDER BY id",
        $courseId
    );

    // Exercises from exercise table
    $exercises = Database::get()->queryArray(
        "SELECT id, title, description FROM exercise WHERE course_id = ?d ORDER BY id",
        $courseId
    );

    // CDM Activities from keywords (new format)
    $cdm_activities = [];
    if (isset($cdm_data['CDM_Activities'])) {
        $cdm_activities = $cdm_data['CDM_Activities'];
    }

    // Backward compatibility: Legacy documents as activities
    if (empty($cdm_activities)) {
        $documents = Database::get()->queryArray(
            "SELECT id, title, filename, comment FROM document WHERE course_id = ?d ORDER BY id",
            $courseId
        );

        foreach ($documents as $doc) {
            $cdm_activities[] = [
                'title' => $doc->title,
                'description' => $doc->comment,
                'type' => 'Learning Activity',
                'actor' => '',
                'facilitator' => '',
                'facilitator_role' => '',
                'legacy' => true
            ];
        }
    }

    $course_content = [
        'videos' => $videos,
        'exercises' => $exercises,
        'cdm_activities' => $cdm_activities
    ];
}
```

---

## 🏗️ Module Integration Logic

### **9. Automatic Module Activation**

```php
private function activateCourseModules($course_id) {
    $essential_modules = [
        'ENABLE_DOCS',      // Document management
        'ENABLE_VIDEO',     // Video content
        'ENABLE_EXERCISE'   // Quizzes and exercises
    ];

    foreach ($essential_modules as $module_constant) {
        $module_id = get_module_id($module_constant);
        if ($module_id) {
            Database::get()->query(
                "INSERT IGNORE INTO course_module
                 SET module_id = ?d, course_id = ?d, visible = 1",
                $module_id, $course_id
            );
        }
    }
}
```

### **10. File System Organization**

```php
private function createCourseDirectories($course_code) {
    $base_path = $GLOBALS['webDir'] . '/courses/' . $course_code;
    $directories = ['document', 'video', 'dropbox', 'group', 'work', 'scormPackages', 'temp', 'page', 'image'];

    foreach ($directories as $dir) {
        $dir_path = $base_path . '/' . $dir;
        if (!is_dir($dir_path)) {
            mkdir($dir_path, 0755, true);
        }

        // Security: Add index.html to prevent directory listing
        file_put_contents($dir_path . '/index.html', '');
    }
}
```

---

## 🎯 Template Rendering Logic

### **11. Blade Template Data Flow**

```php
// Controller: modules/auth/info_course.php
$data = [
    'courseId' => $courseId,
    'c' => $course_object,
    'cdm_data' => $cdm_metadata,
    'cdm_formatted' => $formatted_metadata,
    'course_descriptions' => $course_descriptions,
    'course_content' => $aggregated_content
];

view('modules.auth.info_course', $data);
```

### **12. Frontend Component Mapping**

| CDM Data | Template Section | Display Logic |
|----------|------------------|---------------|
| `cdm_formatted.strategy` | Course Header | `<h1>{{ $cdm_formatted['strategy'] }}</h1>` |
| `cdm_formatted.goals` | Learning Objectives | `@foreach($cdm_formatted['goals'] as $goal)` |
| `cdm_formatted.activity_types` | Activity Types | Badge grid display |
| `cdm_formatted.resource_types` | Resource Types | Icon-based listing |
| `course_content.videos` | Videos Section | Card grid with play buttons |
| `course_content.exercises` | Quizzes Section | Interactive quiz cards |
| `course_content.cdm_activities` | Learning Activities | Modal popup display |

### **13. Modal Content Generation**

```blade
@foreach($course_content['cdm_activities'] as $activity)
<div class="modal fade" id="activityModal{{ $loop->index }}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fa fa-lightbulb text-info"></i>
                    {{ $activity['title'] }}
                </h5>
            </div>
            <div class="modal-body">
                @if(!empty($activity['type']))
                <div class="mb-3">
                    <strong>Activity Type:</strong>
                    <span class="badge bg-info ms-2">{{ $activity['type'] }}</span>
                </div>
                @endif

                @if(!empty($activity['description']))
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {!! nl2br(e($activity['description'])) !!}
                    </div>
                </div>
                @endif

                <!-- Additional activity metadata display -->
            </div>
        </div>
    </div>
</div>
@endforeach
```

---

## 🔐 Security & Data Integrity

### **14. Input Validation Pipeline**

```php
private function validateAndSanitizeCDMData($cdm_data) {
    // JSON structure validation
    if (!isset($cdm_data['LessonInfo']) || !isset($cdm_data['Conceptual'])) {
        throw new Exception('Invalid CDM structure');
    }

    // Sanitize text fields
    $sanitized = [];
    foreach ($cdm_data as $key => $value) {
        if (is_string($value)) {
            $sanitized[$key] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
        } elseif (is_array($value)) {
            $sanitized[$key] = $this->sanitizeArray($value);
        } else {
            $sanitized[$key] = $value;
        }
    }

    return $sanitized;
}

private function sanitizeArray($array) {
    $sanitized = [];
    foreach ($array as $key => $value) {
        if (is_string($value)) {
            $sanitized[$key] = htmlspecialchars(strip_tags($value), ENT_QUOTES, 'UTF-8');
        } elseif (is_array($value)) {
            $sanitized[$key] = $this->sanitizeArray($value);
        } else {
            $sanitized[$key] = $value;
        }
    }
    return $sanitized;
}
```

### **15. Database Transaction Management**

```php
public function importCDM($file_path) {
    Database::get()->begin();

    try {
        // Extract and validate CDM
        $cdm_data = $this->extractCDM($file_path);
        $sanitized_data = $this->validateAndSanitizeCDMData($cdm_data);

        // Create course
        $course_id = $this->createCourse($sanitized_data);

        // Store metadata
        $this->storeCDMMetadata($course_id, $sanitized_data);

        // Import content
        $this->importActivities($sanitized_data['Conceptual']['ConceptualBase']);

        // Activate modules
        $this->activateCourseModules($course_id);

        // Create directories
        $this->createCourseDirectories($this->course_code);

        Database::get()->commit();
        return $course_id;

    } catch (Exception $e) {
        Database::get()->rollback();
        throw $e;
    }
}
```

---

## 📊 Data Flow Summary

### **Complete Integration Pipeline**

```
CDM File (ZIP)
    ↓ [Extract & Parse]
JSON Data Structure
    ↓ [Validate & Sanitize]
Clean Data Array
    ↓ [Course Creation]
OpenEClass Course Record
    ↓ [Metadata Storage]
course.keywords (JSON)
    ↓ [Content Processing]
Multiple Tables:
├── videolink (videos)
├── exercise (quizzes)
├── course.keywords[CDM_Activities] (activities)
└── course_module (activation)
    ↓ [Template Rendering]
Frontend Display:
├── Course Preview
├── Modal Activities
├── Video Gallery
├── Quiz Section
└── Learning Materials
```

### **Key Benefits of This Architecture**

1. **🔄 Flexible Content Mapping**: Different CDM content types map to appropriate OpenEClass modules
2. **📚 Comprehensive Metadata Preservation**: Complete CDM information stored and accessible
3. **🎨 Rich Frontend Experience**: Multiple display formats with interactive modals
4. **🔙 Backward Compatibility**: Legacy courses continue to work seamlessly
5. **🔒 Security First**: Input validation and sanitization throughout the pipeline
6. **🏗️ Modular Architecture**: Independent storage for different content types
7. **📱 Responsive Design**: Bootstrap-based UI components for all devices

This mapping logic ensures that CDM files are comprehensively integrated into OpenEClass while maintaining system integrity and providing an excellent user experience for both instructors and students.