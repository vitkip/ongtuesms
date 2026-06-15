# Prompt ພັດທະນາລະບົບ: ລະບົບຈັດການນັກສຶກສາ ວິທະຍາໄລຄູສົງ ອົງຕື້ (Ongtue SMS)

> ໃຊ້ prompt ນີ້ກັບເຄື່ອງມືຂຽນໂຄດ AI (Claude Code, Cursor, ແລະ ອື່ນໆ) ຫຼື ເປັນ spec ໃຫ້ທີມພັດທະນາ.
> **ໝາຍເຫດ UI:** ບ່ອນທີ່ຂຽນວ່າ `[ອ້າງອິງ Stitch]` ໃຫ້ປ່ຽນເປັນລາຍລະອຽດ layout/ສີ/component ຈິງຈາກ Stitch (https://stitch.withgoogle.com/projects/1245389055458594939) ໂດຍ paste screenshot ຫຼື code ທີ່ export ມາ.

---

## 1. ພາບລວມໂຄງການ (Project Overview)

ສ້າງ **ລະບົບຈັດການນັກສຶກສາ (Student Management System)** ສຳລັບ *ວິທະຍາໄລຄູສົງ ອົງຕື້* — ສະຖາບັນຝຶກຫັດຄູ ສາຍພຸດທະສາສະໜາ. ນັກສຶກສາສ່ວນໃຫຍ່ແມ່ນ **ພຣະ ແລະ ສ.ນ (ສາມະເນນ)** ສະນັ້ນ UI ແລະ ຄຳສັບຕ້ອງເໝາະສົມກັບບໍລິບົດສົງ ແລະ ໃຊ້ **ພາສາລາວ** ເປັນຫຼັກ.

ລະບົບຈັດການ: ຂໍ້ມູນນັກສຶກສາ, ສາຂາ/ຫຼັກສູດ/ວິຊາ, ການລົງທະບຽນຮຽນ, ການບັນທຶກຄະແນນ ແລະ ຄຳນວນ GPA, ໃບບິນເກັບເງິນ (ພ້ອມ QR code), ບົດລາຍງານ/ໃບຄະແນນ, ການຕັ້ງຄ່າລະບົບ ແລະ ການຈັດການຜູ້ໃຊ້.

---

## 2. Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8 / MariaDB (charset `utf8mb4`, collation `utf8mb4_unicode_ci` — ບັງຄັບ ເພາະຂໍ້ມູນເປັນພາສາລາວ)
- **Frontend:** Blade + **Livewire 3** (ສຳລັບ table ກອງ/ຄົ້ນຫາ/paginate ແບບ reactive) + **Alpine.js** ສຳລັບ interaction ນ້ອຍໆ
- **CSS:** **Tailwind CSS 3** (ຜ່ານ Vite)
- **Auth:** Laravel built-in (session-based, ບໍ່ໃຊ້ Breeze starter UI — ສ້າງ UI ເອງຕາມ Stitch)
- **PDF/ໃບຄະແນນ:** `barryvdh/laravel-dompdf`
- **QR code:** `simplesoftwareio/simple-qrcode` ຫຼື `endroid/qr-code`
- **Excel/Export:** `maatwebsite/excel` (ທາງເລືອກ)
- **ຟອນ:** ຟອນລາວ (ເຊັ່ນ `Noto Sans Lao` ຫຼື `Phetsarath OT`) ໂຫຼດຜ່ານ CSS, ຕັ້ງເປັນ font ຫຼັກ.

> ຖ້າຕ້ອງການ SPA ເຕັມຮູບແບບ ໃຫ້ໃຊ້ Inertia.js + Vue 3 ແທນ Livewire ໄດ້ — ແຕ່ default ຂອງ prompt ນີ້ແມ່ນ Blade + Livewire ເພາະງ່າຍ ແລະ ດູແລງ່າຍກວ່າ.

---

## 3. ໂຄງສ້າງຖານຂໍ້ມູນ ແລະ ຄວາມສຳພັນ (Schema & Relationships)

ສ້າງ migration ໃຫ້ກົງກັບ schema ລຸ່ມນີ້ (ໃຊ້ໄຟລ໌ `ongtue-db.sql` ທີ່ມີຢູ່ເປັນຈິງ — ສາມາດ import ໂດຍກົງ ຫຼື ສ້າງ migration ໃໝ່ໃຫ້ກົງ).

### ຕາຕະລາງ ແລະ ຄີຫຼັກ

| ຕາຕະລາງ | ໜ້າທີ່ |
|---|---|
| `users` | ຜູ້ໃຊ້ລະບົບ (role: `admin`/`user`), `is_active`, `last_login` |
| `students` | ນັກສຶກສາ — `student_id` (ລະຫັດ 14 ໂຕ), `gender` enum(`ພຣະ`,`ສ.ນ`,`ຊາຍ`,`ຍິງ`,`ອຶ່ນໆ`), `accommodation_type` enum(`ຫາວັດໃຫ້`,`ມີວັດຢູ່ແລ້ວ`), `major_id`, `academic_year_id`, `year_level`, `photo`, `password` |
| `academic_years` | ປີການສຶກສາ (ເຊັ່ນ `2025-2026`), `status` |
| `majors` | ສາຂາ — `name`, `code`, `description`, `status` |
| `curriculum` | ຫຼັກສູດ — `major_id`, `academic_year_id`, `curriculum_code` (unique), `total_credits`, `minimum_gpa`, `duration_years`, `status` enum(`active`,`inactive`,`draft`) |
| `curriculum_subjects` | ວິຊາໃນຫຼັກສູດ — `curriculum_id`, `subject_id`, `subject_type` enum(`required`,`elective`,`free_elective`), `year_level`, `semester`, `sort_order` |
| `subjects` | ວິຊາ — `subject_code`, `subject_name`, `subject_name_en`, `credits`, `theory_hours`, `practical_hours`, `major_id`, `semester`, `year_level`, `prerequisite_subject_id` (self-FK) |
| `enrollments` | ການລົງທະບຽນ — `student_id`, `subject_id`, `academic_year_id`, `semester`, `enrollment_date`, `status` enum(`enrolled`,`dropped`,`completed`,`failed`), `enrolled_by`→users |
| `grades` | ຄະແນນ — `enrollment_id`, `student_id`, `subject_id`, `academic_year_id`, `semester`, ຄະແນນຍ່ອຍ (`midterm_score`,`final_score`,`assignment_score`,`participation_score`,`practical_score`,`project_score`), `total_score`, `percentage`, `letter_grade`, `grade_point`, `credits_earned`, `status`, `graded_by`→users |
| `invoices` | ໃບບິນ — `invoice_number`, `student_id`, `card_fee`, `photo_fee`, `email_fee`, `tuition_fee`, `total_amount`, `bank_account_number`, `qr_code_path`, `payment_status` enum(`unpaid`,`paid`,`cancelled`), `created_by`→users |
| `settings` | ການຕັ້ງຄ່າ key/value (app_name, school_name, school_address, ...) |
| `system_logs` | ບັນທຶກເຫດການ — `level`, `message`, `user_id`, `ip_address`, `user_agent`, `context`(JSON) |

### ຄວາມສຳພັນ (Eloquent relationships)

- `Major` hasMany `Student`, `Subject`, `Curriculum`
- `AcademicYear` hasMany `Student`, `Curriculum`, `Enrollment`, `Grade`
- `Curriculum` belongsTo `Major`, `AcademicYear`; belongsToMany `Subject` ຜ່ານ `curriculum_subjects` (pivot ມີ `subject_type`, `year_level`, `semester`, `sort_order`)
- `Subject` belongsTo `Major`; belongsTo `Subject` (prerequisite, self-relation); hasMany `Enrollment`, `Grade`
- `Student` belongsTo `Major`, `AcademicYear`; hasMany `Enrollment`, `Grade`, `Invoice`
- `Enrollment` belongsTo `Student`, `Subject`, `AcademicYear`, `User`(enrolled_by); hasOne `Grade`
- `Grade` belongsTo `Enrollment`, `Student`, `Subject`, `AcademicYear`, `User`(graded_by)
- `Invoice` belongsTo `Student`, `User`(created_by)
- `User` hasMany `Enrollment`(enrolled_by), `Grade`(graded_by), `Invoice`(created_by), `SystemLog`

> **ສຳຄັນ:** ກຳນົດ foreign key constraints ໃຫ້ກົງກັບ SQL ຕົ້ນສະບັບ (`ON DELETE SET NULL` ສຳລັບ `enrolled_by`/`graded_by`/`created_by`, `ON DELETE CASCADE` ສຳລັບ `invoices.student_id`).

---

## 4. Modules ແລະ ໜ້າທີ່ການເຮັດວຽກ (Feature Modules)

### 4.1 Authentication & ການຈັດການຜູ້ໃຊ້
- ໜ້າ Login (username + password). Password ໃນ DB ເປັນ bcrypt (`$2y$...`) ຢູ່ແລ້ວ → ໃຊ້ `Hash::check`/`bcrypt` ໄດ້ໂດຍກົງ.
- Role-based access: `admin` (ເຂົ້າເຖິງທຸກຢ່າງ ລວມ users/settings/logs), `user` (ບໍ່ເຫັນການຈັດການຜູ້ໃຊ້/ການຕັ້ງຄ່າລະບົບ).
- ໃຊ້ Laravel Policies/Gates ຫຼື middleware ກວດ role.
- ບັນທຶກ `last_login` ທຸກຄັ້ງທີ່ login ສຳເລັດ; ກວດ `is_active` ກ່ອນອະນຸຍາດ.
- CRUD ຜູ້ໃຊ້ (admin ເທົ່ານັ້ນ).

### 4.2 Dashboard
- ສະຫຼຸບສະຖິຕິ: ຈຳນວນນັກສຶກສາທັງໝົດ / ແຍກຕາມສາຂາ / ແຍກຕາມເພດ (ພຣະ, ສ.ນ, ...), ຈຳນວນວິຊາ, ການລົງທະບຽນ semester ປະຈຸບັນ, ໃບບິນທີ່ຍັງບໍ່ຈ່າຍ.
- ກຣາຟ (ໃຊ້ Chart.js/ApexCharts): ນັກສຶກສາໃໝ່ຕາມປີການສຶກສາ, ການກະຈາຍ GPA.
- `[ອ້າງອິງ Stitch]` ສຳລັບ layout ຂອງ stat cards ແລະ charts.

### 4.3 ນັກສຶກສາ (Students)
- CRUD ເຕັມຮູບແບບ + ອັບໂຫຼດຮູບ (`photo`, ເກັບໃນ `storage/app/public/students`).
- ຟອມມີ field ສະເພາະສົງ: `gender` (dropdown ພຣະ/ສ.ນ/ຊາຍ/ຍິງ/ອຶ່ນໆ), `accommodation_type` (ຫາວັດໃຫ້/ມີວັດຢູ່ແລ້ວ), ທີ່ຢູ່ (village/district/province), ໂຮງຮຽນເກົ່າ (`previous_school`).
- **ການອອກ `student_id` ອັດຕະໂນມັດ:** ຮູບແບບ `0145` + ປີ (4 ໂຕ) + ລຳດັບ 6 ໂຕ (ເຊັ່ນ `01452026000001`). ໃຫ້ສ້າງ helper ຄຳນວນເລກລຳດັບຖັດໄປຕາມປີ.
- ໜ້າຕາຕະລາງ: ຄົ້ນຫາ (ຊື່/ລະຫັດ), ກອງຕາມ major/academic_year/year_level/gender, paginate.
- ໜ້າ profile ນັກສຶກສາ: ສະແດງປະຫວັດ, ວິຊາທີ່ລົງທະບຽນ, ຄະແນນ, GPA, ໃບບິນ.

### 4.4 ສາຂາ (Majors)
- CRUD ງ່າຍໆ: name, code, description, status.

### 4.5 ຫຼັກສູດ (Curriculum) + ວິຊາໃນຫຼັກສູດ
- CRUD ຫຼັກສູດ (ຜູກກັບ major + academic_year).
- ໜ້າຈັດການວິຊາໃນຫຼັກສູດ: ເພີ່ມ/ລຶບ subject ເຂົ້າຫຼັກສູດ, ກຳນົດ `subject_type` (ບັງຄັບ/ເລືອກ/ເລືອກເສລີ), `year_level`, `semester`, ລຳດັບ.
- ສະແດງໂຄງສ້າງຫຼັກສູດແບບ matrix: ຊັ້ນປີ × ພາກຮຽນ + ສະຫຼຸບໜ່ວຍກິດລວມ ທຽບກັບ `total_credits`.

### 4.6 ວິຊາ (Subjects)
- CRUD: subject_code (unique ຕໍ່ major), ຊື່ລາວ/ອັງກິດ, credits, theory/practical hours, semester, year_level, prerequisite (dropdown ຈາກ subjects ອື່ນ), description, learning_outcomes.

### 4.7 ການລົງທະບຽນຮຽນ (Enrollments)
- ລົງທະບຽນນັກສຶກສາ ເຂົ້າວິຊາ ຕາມ academic_year + semester.
- ກວດກາ: ບໍ່ໃຫ້ລົງຊ້ຳ (student+subject+year+semester), ກວດ prerequisite (ຖ້າມີ), ບັນທຶກ `enrolled_by` = user ປະຈຸບັນ.
- ໂໝດລົງທະບຽນເປັນກຸ່ມ (bulk): ເລືອກນັກສຶກສາຫຼາຍຄົນ → ລົງວິຊາໃຫ້ພ້ອມກັນ.
- ປ່ຽນ status: enrolled → dropped/completed/failed.

### 4.8 ຄະແນນ (Grades) — ⭐ ໂມດູນຫຼັກ
- ບັນທຶກຄະແນນຍ່ອຍ: midterm, final, assignment, participation, practical, project.
- **ຄຳນວນອັດຕະໂນມັດ:**
  - `total_score` = ຜົນບວກຄະແນນຍ່ອຍ (ກຳນົດ weight ໄດ້ໃນ settings; ຄ່າ default ໃຫ້ບວກກົງໆ ໃຫ້ໄດ້ເຕັມ 100).
  - `percentage` = ຄິດເປັນ %.
  - `letter_grade` ແລະ `grade_point` ຕາມຕາຕະລາງລຸ່ມນີ້.
  - `credits_earned` = credits ຂອງວິຊາ ຖ້າ ผ่าน (grade_point > 0), ບໍ່ຜ່ານ = 0.
- ບັນທຶກ `graded_by`, `graded_at`.
- ໜ້າ "ປ້ອນຄະແນນຕາມວິຊາ": ເລືອກ subject + year + semester → ສະແດງລາຍຊື່ນັກສຶກສາທີ່ລົງທະບຽນ → ປ້ອນຄະແນນເປັນຕາຕະລາງ (Livewire, ບັນທຶກໄດ້ທີລະຫຼາຍຄົນ).
- ຄຳນວນ **GPA** ຂອງນັກສຶກສາ = Σ(grade_point × credits) / Σ(credits).

#### ຕາຕະລາງເກຣດ (Grade Scale)
| ຊ່ວງ % | Letter | Grade Point |
|---|---|---|
| 90–100 | A | 4.00 |
| 85–89 | B+ | 3.50 |
| 80–84 | B | 3.00 |
| 75–79 | C+ | 2.50 |
| 70–74 | C | 2.00 |
| 65–69 | D+ | 1.50 |
| 60–64 | D | 1.00 |
| < 60 | F | 0.00 |

> ໃຫ້ເກັບຊ່ວງເກຣດໄວ້ໃນ config/settings ເພື່ອປັບໄດ້ພາຍຫຼັງ. (ສາມາດປັບໃຫ້ກົງກັບລະບຽບຈິງຂອງວິທະຍາໄລ.)

### 4.9 ໃບບິນ / ການເງິນ (Invoices)
- ສ້າງໃບບິນໃຫ້ນັກສຶກສາ: `card_fee` (ຄ່າເຮັດບັດ, default 20,000), `photo_fee` (default 20,000), `email_fee` (default 50,000), `tuition_fee` (ຄ່າເທີມ).
- `total_amount` = ຜົນບວກ. ສະກຸນເງິນ **ກີບ (LAK)**, format ມີຈຸດຄັ່ນພັນ.
- ອອກ `invoice_number` ອັດຕະໂນມັດ.
- **ສ້າງ QR code** ສຳລັບການໂອນ (ໃຊ້ `bank_account_number`, default `01452026000028`) → ບັນທຶກ path ໃນ `qr_code_path`.
- ສະຖານະຈ່າຍ: unpaid/paid/cancelled + `payment_date`.
- ພິມໃບບິນ PDF (dompdf) ພ້ອມ QR ແລະ ໂລໂກ້/ຫົວໜ່ວຍງານ (ດຶງຈາກ settings: school_name, school_address, school_phone).
- ສົ່ງໃບບິນທາງ email (`student_email`/`parent_email`) — ໃຊ້ Laravel Mail (ເປີດ/ປິດໄດ້ຜ່ານ setting `enable_email_notifications`).

### 4.10 ບົດລາຍງານ (Reports)
- **ໃບຄະແນນ/Transcript** ລາຍບຸກຄົນ (PDF): ລາຍວິຊາ + ເກຣດ + GPA ສະສົມ, ມີຫົວງານ ແລະ ບ່ອນເຊັນ.
- ລາຍຊື່ນັກສຶກສາຕາມສາຂາ/ປີ (export Excel/PDF).
- ສະຫຼຸບຄະແນນຕາມວິຊາ/ພາກຮຽນ.

### 4.11 ການຕັ້ງຄ່າລະບົບ (Settings) — admin ເທົ່ານັ້ນ
- ແກ້ໄຂ key/value: app_name, app_description, school_name, school_address, school_phone, school_email, academic_year_start_month, max_file_upload_size, session_timeout, enable_email_notifications, enable_sms_notifications, maintenance_mode, show_debug_info.
- ໃຊ້ caching ສຳລັບ settings (helper `setting('key')`).

### 4.12 System Logs — admin ເທົ່ານັ້ນ
- ບັນທຶກເຫດການສຳຄັນ (login, ສ້າງ/ແກ້/ລຶບ, backup) ໂດຍອັດຕະໂນມັດຜ່ານ middleware/observer.
- ໜ້າເບິ່ງ log: ກອງຕາມ level (info/warning/error/critical/debug) + user + ວັນທີ.

---

## 5. UI / UX

- **Layout:** Sidebar ຊ້າຍ (ເມນູ modules) + topbar (ຊື່ຜູ້ໃຊ້, ປຸ່ມ logout, ສະຫຼັບພາສາ ຖ້າຕ້ອງການ). ເນື້ອໃນຫຼັກຢູ່ກາງ. **Responsive** (mobile: sidebar ຫຍຸບໄດ້).
- **Style:** Tailwind CSS. ໃຊ້ design tokens (ສີຫຼັກ, radius, spacing) ໃຫ້ສະໝ່ຳສະເໝີ. `[ອ້າງອິງ Stitch ສຳລັບ palette, typography, component style, ໄລຍະຫ່າງ]`.
- ໃຊ້ component ຊ້ຳ: card, table (ມີ search/filter/pagination), modal ຟອມ, badge ສະຖານະ (active/inactive, paid/unpaid, ເກຣດ), toast notification.
- **ຟອນລາວ** ເປັນຄ່າເລີ່ມຕົ້ນທົ່ວທັງລະບົບ, ໃຫ້ render ພາສາລາວ (ໂດຍສະເພາະ ສະຫຼະ/ໂຕກ້ຳ) ໄດ້ຖືກຕ້ອງ.
- ທຸກ label, ປຸ່ມ, ຂໍ້ຄວາມ error ເປັນ **ພາສາລາວ** (ໃຊ້ Laravel localization `lang/lo/`).
- ບໍ່ບັງຄັບ dark mode (ເພີ່ມໄດ້ຖ້າ Stitch ມີ).

---

## 6. Business Rules / Logic ສຳຄັນ

1. **ລະຫັດນັກສຶກສາ** auto: `0145` + ປີ + ລຳດັບ 6 ໂຕ (zero-padded).
2. **GPA** = Σ(grade_point × credits) / Σ(credits), ປັດ 2 ຕຳແໜ່ງ.
3. **ການເລື່ອນຊັ້ນ:** ກວດ GPA ≥ `minimum_gpa` ຂອງຫຼັກສູດ (ໃຊ້ໃນບົດລາຍງານ/ການແຈ້ງເຕືອນ).
4. **ການ enroll** ຕ້ອງບໍ່ຊ້ຳ ແລະ ກວດ prerequisite.
5. **invoice total** = card + photo + email + tuition.
6. **Soft delete** ສຳລັບ students/subjects/users (ໃຊ້ `SoftDeletes`) ເພື່ອຮັກສາ FK integrity (ທາງເລືອກ — ຖ້າ schema ບໍ່ມີ deleted_at ໃຫ້ເພີ່ມ migration).

---

## 7. ໂຄງສ້າງໂຄດ ແລະ ມາດຕະຖານ

- ໃຊ້ໂຄງສ້າງ Laravel ມາດຕະຖານ: `app/Models`, `app/Http/Controllers`, `app/Livewire`, `app/Services` (ໃສ່ logic ຄຳນວນ GPA/grade/invoice ໃນ Service class), `app/Policies`.
- Form Request validation ສຳລັບທຸກຟອມ.
- Eloquent relationships ຄົບຕາມຂໍ້ 3, ໃຊ້ eager loading (`with()`) ກັນ N+1.
- Seeder/Factory ສຳລັບ test data; ສ້າງ Seeder ນຳເຂົ້າ majors/academic_years/settings ເລີ່ມຕົ້ນ.
- Migration ກົງກັບ schema; ຮັກສາ collation `utf8mb4_unicode_ci`.

---

## 8. ຄວາມປອດໄພ (Security)

- Password hashing ດ້ວຍ bcrypt (ກົງກັບ hash `$2y$` ທີ່ມີຢູ່ — login ໄດ້ເລີຍ).
- CSRF protection (Laravel default), input validation/sanitization.
- Authorization ທຸກ route ດ້ວຍ middleware + Policy ຕາມ role.
- Rate limiting ໜ້າ login.
- ບໍ່ເປີດເຜີຍ password/hash ໃນ API/response.
- ກວດ file upload (ປະເພດ + ຂະໜາດຕາມ setting `max_file_upload_size`).

---

## 9. ສິ່ງທີ່ຕ້ອງສົ່ງມອບ (Deliverables)

1. Laravel project ທີ່ run ໄດ້ (ມີ `composer install`, `npm install && npm run build`, `.env.example`).
2. Migration + Seeder ຄົບ (ຫຼື ຄຳແນະນຳ import `ongtue-db.sql`).
3. Models + relationships + Services (GPA/Grade/Invoice).
4. Livewire components + Blade views ຕາມ modules.
5. RBAC (admin/user).
6. PDF (transcript + invoice) ແລະ QR code ໃຊ້ໄດ້.
7. Localization ພາສາລາວ.
8. README ວິທີຕິດຕັ້ງ ແລະ login ເລີ່ມຕົ້ນ.

---

## 10. ຄຳສັ່ງເລີ່ມຕົ້ນ (ໃຫ້ AI/ນັກພັດທະນາ)

> "ສ້າງລະບົບຕາມ spec ຂ້າງເທິງເປັນຂັ້ນຕອນ: (1) ຕັ້ງ project + Tailwind + Livewire, (2) migrations + models + relationships, (3) auth + RBAC + layout (sidebar/topbar ຕາມ Stitch), (4) modules ນັກສຶກສາ → ສາຂາ → ວິຊາ → ຫຼັກສູດ → ລົງທະບຽນ → ຄະແນນ → ໃບບິນ → ບົດລາຍງານ → ຕັ້ງຄ່າ → logs, (5) PDF + QR, (6) localization ລາວ. ສະແດງໂຄດເຕັມຂອງແຕ່ລະໄຟລ໌ ແລະ ອະທິບາຍສັ້ນໆ. ໃຊ້ພາສາລາວສຳລັບ UI ທັງໝົດ."