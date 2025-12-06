# CDM Feature - Files for Modification

**Project Backup Location:** `/home/stav/Desktop/eclass_project_backup_YYYYMMDD/`
**Last Updated:** 2025-12-06

---

## 🔥 Core CDM Files (Primary Development)

### 1. **CDM Import Handler**
**File:** `modules/create_course/cdm_import.php`
**Purpose:** Main CDM extraction and course creation logic
**Key Functions:**
- `extractCDM()` - Parse CDM ZIP files
- `createCourse()` - Create eClass course from CDM
- `storeCDMMetadata()` - Store metadata in course.keywords
- `createVideoLink()` - Video resource mapping
- `createExercise()` - Quiz resource mapping
- `createAssignment()` - Assessment resource mapping
- `createForumTopic()` - Forum resource mapping
- `createWikiPage()` - Wiki resource mapping
- `storeCDMActivity()` - Store unmapped activities
- `importFlowUnits()` - Think-Pair-Share phases

**Modify Here For:**
- Adding new resource type handlers (H5P, Audio, Glossary, etc.)
- Enhancing existing mappings
- Changing metadata storage logic
- Adding export functionality

---

### 2. **Course Info Display Page**
**File:** `modules/auth/info_course.php`
**Purpose:** Display course information with CDM metadata
**Key Sections:**
- CDM metadata extraction from course.keywords
- Course description display
- Activity data aggregation
- Video/exercise content display

**Modify Here For:**
- Displaying CDM-specific fields
- Showing CDM_Activities array
- Customizing metadata presentation
- Adding new UI components for CDM data

---

### 3. **Course Info Blade Template**
**File:** `resources/views/modules/auth/info_course.blade.php`
**Purpose:** Frontend template for course information
**Key Elements:**
- CDM metadata display sections
- Activity modals
- Resource listings
- Learning objectives display

**Modify Here For:**
- UI/UX improvements for CDM data
- Adding new display sections
- Styling CDM metadata
- Creating interactive elements

---

## 📋 Supporting Files (Secondary Development)

### 4. **Portfolio Functions**
**File:** `main/portfolio_functions.php`
**Purpose:** Portfolio-level course operations
**CDM Usage:**
- Course listing with CDM metadata
- Course filtering/searching
- Bulk operations

**Modify Here For:**
- Course management features
- CDM metadata in course cards
- Filtering by CDM properties

---

### 5. **Portfolio Index Template**
**File:** `resources/views/portfolio/index.blade.php`
**Purpose:** Portfolio main page display
**CDM Usage:**
- Course cards with CDM info
- Course navigation

**Modify Here For:**
- Displaying CDM metadata in portfolio
- Course card enhancements
- Search/filter UI

---

### 6. **Courses List Template**
**File:** `resources/views/modules/auth/courses.blade.php`
**Purpose:** Courses listing page
**CDM Usage:**
- Course list display with metadata

**Modify Here For:**
- Course listing enhancements
- CDM badge/tags display
- Course preview features

---

## 🔄 CDM Import Variants (Reference/Backup Files)

These are alternate versions created during development:

### 7. **Complete Import Version**
**File:** `modules/create_course/cdm_import_complete.php`
**Status:** Reference/Backup
**Use:** Compare implementations or rollback

### 8. **Enhanced Import Version**
**File:** `modules/create_course/cdm_import_enhanced.php`
**Status:** Reference/Backup
**Use:** Alternative implementation reference

### 9. **Final Import Version**
**File:** `modules/create_course/cdm_import_final.php`
**Status:** Reference/Backup
**Use:** Previous stable version

---

## 📝 Documentation Files

### 10. **CDM Mapping Reference**
**File:** `CDM_ECLASS_MAPPING.md`
**Purpose:** Complete mapping documentation
**Update When:** New mappings added or changed

### 11. **CDM Mapping Logic**
**File:** `CDM_OpenEClass_Mapping_Logic.md`
**Purpose:** Detailed integration guide
**Update When:** Implementation logic changes

### 12. **Development Steps**
**File:** `CDM_Development_Steps.md`
**Purpose:** Development workflow documentation
**Update When:** New features added

---

## 🎯 Common Modification Scenarios

### Scenario 1: Add New Resource Type (e.g., H5P)

**Files to Modify:**
1. `modules/create_course/cdm_import.php`
   - Add case in `createCourseModule()` switch statement
   - Create new handler function (e.g., `createH5PContent()`)
   - Add database insert logic

2. `modules/auth/info_course.php`
   - Add H5P content query
   - Include H5P data in aggregation

3. `resources/views/modules/auth/info_course.blade.php`
   - Add H5P display section
   - Create H5P card/modal UI

---

### Scenario 2: Enhance Metadata Display

**Files to Modify:**
1. `modules/auth/info_course.php`
   - Extract additional fields from course.keywords
   - Format data for template

2. `resources/views/modules/auth/info_course.blade.php`
   - Add new display sections
   - Create UI components for new fields

---

### Scenario 3: Implement CDM Export

**Files to Modify:**
1. `modules/create_course/cdm_export.php` (NEW FILE)
   - Create export class
   - Query all modules (videolink, exercise, assignment, etc.)
   - Read course.keywords metadata
   - Reconstruct CDM JSON structure
   - Create ZIP file with source.json

2. `modules/auth/info_course.php`
   - Add export button
   - Handle export request

3. `resources/views/modules/auth/info_course.blade.php`
   - Add export button UI

---

### Scenario 4: Add Activity Type Filtering

**Files to Modify:**
1. `modules/auth/info_course.php`
   - Parse CDM_Activities array
   - Group by activity type
   - Add filtering logic

2. `resources/views/modules/auth/info_course.blade.php`
   - Add filter buttons/dropdown
   - Add JavaScript for filtering
   - Update display logic

---

## 📊 Recent CDM-Related Commits

```
f4c5a35da - Fix .html bug on documents module creation when cdm extracted and inserted
abddad4c0 - Create a modal for Learning Materials & Activities
bd09c45ad - Fix errors in buttons about cdm extraction
cda76eb6f - Fix course redirection errors
f6f060130 - Fix Coure Page buttons into course preview
9d8c3476b - Remove Duplicate elements from course desrcription
7c4891ccd - Integrate Modules functionality to cdm extraction
34db659e5 - Add functionality for metaData insert/preview
0de2310d4 - Integrate services for cdm extraction/Create UI for cdm upload
```

---

## 🗂️ Database Tables Used by CDM

| Table | Purpose | Modified By |
|-------|---------|-------------|
| `course` | Course metadata, keywords (JSON) | cdm_import.php |
| `course_units` | Think-Pair-Share phases | cdm_import.php |
| `videolink` | Video resources | cdm_import.php |
| `exercise` | Quizzes | cdm_import.php |
| `assignment` | Assessments | cdm_import.php |
| `forum`, `forum_topic`, `forum_post`, `forum_category` | Forums | cdm_import.php |
| `wiki_properties`, `wiki_pages`, `wiki_pages_content` | Wikis | cdm_import.php |
| `document` | Documents/Files | cdm_import.php |

---

## 🔧 Quick Reference: Where to Add Features

| Feature | Primary File | Secondary Files |
|---------|--------------|-----------------|
| New resource type handler | `cdm_import.php` | `info_course.php`, `info_course.blade.php` |
| Metadata display | `info_course.php` | `info_course.blade.php` |
| Export functionality | `cdm_export.php` (new) | `info_course.php`, `info_course.blade.php` |
| UI improvements | `info_course.blade.php` | CSS files |
| Course listing features | `portfolio_functions.php` | `portfolio/index.blade.php` |
| Activity filtering | `info_course.php` | `info_course.blade.php` + JS |

---

## ✅ Testing Files

**Sample CDM File:** `modules/create_course/Omada1.cdm`
**Use:** Test CDM import functionality

**Test Courses Created:**
- Course 113, 114, 115, 116 - Think-Pair-Share examples
- Course 117 - Full CDM with multiple resources
- Course 118, 119, 120 - Recent tests

---

**Next Steps:**
1. Backup project is in `/home/stav/Desktop/eclass_project_backup_YYYYMMDD/`
2. Main development file: `modules/create_course/cdm_import.php`
3. UI display: `modules/auth/info_course.php` + `resources/views/modules/auth/info_course.blade.php`
4. Always test with `Omada1.cdm` sample file
5. Check `course.keywords` field in database for stored CDM metadata
