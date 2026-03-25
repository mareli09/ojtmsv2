# System Manual
## AI-Assisted OJT Monitoring System with LLM-Powered Chatbot and Decision Support for Internship Management
### Makati Science Technological Institute of the Philippines (MSTIP)

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [System Requirements](#2-system-requirements)
3. [Installation and Setup](#3-installation-and-setup)
4. [Database Seeders](#4-database-seeders)
5. [Demo Accounts](#5-demo-accounts)
6. [Accessing the System](#6-accessing-the-system)
7. [User Roles](#7-user-roles)
8. [Admin Module](#8-admin-module)
   - 8.1 [Dashboard](#81-admin-dashboard)
   - 8.2 [User Management](#82-user-management)
   - 8.3 [Section Management](#83-section-management)
   - 8.4 [Announcements](#84-announcements-admin)
   - 8.5 [CMS Settings](#85-cms-settings)
   - 8.6 [Reports](#86-reports-admin)
9. [Faculty Module](#9-faculty-module)
   - 9.1 [Dashboard](#91-faculty-dashboard)
   - 9.2 [Handled Section](#92-handled-section)
   - 9.3 [Reviewing Student Checklist Items](#93-reviewing-student-checklist-items)
   - 9.4 [Incident Reports](#94-incident-reports-faculty)
   - 9.5 [Reports](#95-reports-faculty)
   - 9.6 [FAQ Management](#96-faq-management)
   - 9.7 [Announcements](#97-announcements-faculty)
   - 9.8 [AI Chatbot](#98-ai-chatbot-faculty)
   - 9.9 [Decision Support](#99-decision-support)
10. [Student Module](#10-student-module)
    - 10.1 [Dashboard](#101-student-dashboard)
    - 10.2 [OJT Checklist](#102-ojt-checklist)
    - 10.3 [Daily Time Record (DTR)](#103-daily-time-record-dtr)
    - 10.4 [Weekly Report](#104-weekly-report)
    - 10.5 [Monthly Appraisal](#105-monthly-appraisal)
    - 10.6 [Supervisor Evaluation](#106-supervisor-evaluation)
    - 10.7 [Certificate of Completion (COC)](#107-certificate-of-completion-coc)
    - 10.8 [Incident Report](#108-incident-report)
    - 10.9 [Announcements](#109-announcements-student)
    - 10.10 [FAQ](#1010-faq)
    - 10.11 [AI Chatbot](#1011-ai-chatbot-student)
11. [Checklist Item Reference](#11-checklist-item-reference)
12. [Troubleshooting](#12-troubleshooting)

---

## 1. System Overview

The **AI-Assisted OJT Monitoring System (OJTMS)** is a web-based platform designed to streamline and digitize the On-the-Job Training (OJT) internship management process at MSTIP. The system supports three user roles — Admin, Faculty, and Student — each with dedicated modules for their respective responsibilities.

Key features include:
- Step-by-step checklist-based document submission and approval workflow
- Daily Time Record (DTR) tracking with hour progress monitoring
- Weekly and monthly submission management (reports, appraisals, evaluations)
- Incident report submission and faculty response
- AI-powered chatbot for student and faculty queries (OpenAI GPT)
- AI Decision Support for faculty to assess student progress
- Announcement and FAQ management
- Role-based access control

---

## 2. System Requirements

| Requirement | Details |
|---|---|
| Web Browser | Google Chrome (recommended), Mozilla Firefox, Microsoft Edge |
| Internet Connection | Required for AI Chatbot features |
| Screen Resolution | Minimum 1024 x 768 |
| JavaScript | Must be enabled |

**Server Requirements (for deployment):**

| Requirement | Details |
|---|---|
| PHP | 8.1 or higher |
| Laravel | 10.x |
| Database | MySQL 8.0 or higher |
| Composer | 2.x |
| Web Server | Apache or Nginx |

---

## 3. Installation and Setup

Follow these steps to set up the system on a local or production server.

**Step 1 — Clone the repository**
```bash
git clone <repository-url>
cd OJTMS
```

**Step 2 — Install PHP dependencies**
```bash
composer install
```

**Step 3 — Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` and set your database credentials:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ojtms
DB_USERNAME=root
DB_PASSWORD=your_password
```

**Step 4 — Run database migrations**
```bash
php artisan migrate
```

**Step 5 — Seed the database**

For a fresh demo-ready setup:
```bash
php artisan db:seed --class=DemoSeeder
```

For default database seed only:
```bash
php artisan db:seed
```

**Step 6 — Set up file storage**
```bash
php artisan storage:link
```

**Step 7 — Start the development server**
```bash
php artisan serve
```

Access the system at `http://localhost:8000`

---

## 4. Database Seeders

The system includes multiple seeders for different purposes. All seeders are located in `database/seeders/`.

---

### 4.1 DemoSeeder

**Purpose:** Wipes all existing transactional data and seeds clean, realistic demo accounts for testing and presentation.

**Command:**
```bash
php artisan db:seed --class=DemoSeeder
```

**What it does:**

1. **Clears all transactional data** — truncates the following tables:
   - `announcement_reads`
   - `student_checklists`
   - `incident_reports`
   - `announcements`
   - `faqs`
   - `users`
   - `sections`

2. **Re-seeds CMS settings** via `CMSSeeder`

3. **Creates 2 sections:**
   - `BSIT-4A` — Monday/Wednesday, 8:00 AM–12:00 PM, Room 301
   - `BSIT-4B` — Tuesday/Thursday, 1:00 PM–5:00 PM, Room 302

4. **Creates 1 admin account** (`admin`)

5. **Creates 2 faculty accounts** (`mreyes`, `jsantos`)

6. **Creates 4 student accounts** with different checklist states (`acruz`, `mdelatorre`, `svillanueva`, `cmendoza`)

7. **Seeds pre-filled checklist data** for `mdelatorre` (In Progress) and `svillanueva` (Advanced)

8. **Seeds 3 sample announcements**

9. **Seeds FAQs** via `FaqSeeder`

> **Warning:** This seeder wipes ALL users and submissions. Do not run on a production system with live data.

---

### 4.2 DatabaseSeeder

**Purpose:** Default Laravel seeder. Seeds the database with base configuration and optional sample data.

**Command:**
```bash
php artisan db:seed
```

Calls the following seeders in order:
- `CMSSeeder` — system settings
- `FaqSeeder` — default FAQs

---

### 4.3 CMSSeeder

**Purpose:** Seeds the `cms_data` table with default system configuration settings.

**Command:**
```bash
php artisan db:seed --class=CMSSeeder
```

Sets default values for:
- System name
- Contact email
- OpenAI API key placeholder
- Other configurable system options

> The admin can update all CMS settings from the Admin panel under **CMS Settings**.

---

### 4.4 FaqSeeder

**Purpose:** Seeds the `faqs` table with default frequently asked questions about the OJT process.

**Command:**
```bash
php artisan db:seed --class=FaqSeeder
```

This is automatically called by `DemoSeeder` and `DatabaseSeeder`.

---

## 5. Demo Accounts

After running `DemoSeeder`, the following accounts are available. All accounts use the password: **`password`**

---

### Admin Account

| Field | Value |
|---|---|
| Username | `admin` |
| Password | `password` |
| Email | admin@ojtms.edu.ph |
| Employee ID | ADM-001 |
| Role | Admin |
| Name | System Administrator |
| Department | Administration |

**Access:** Full system access — user management, section management, announcements, CMS settings, and reports.

> The admin account **cannot be deleted** from the system. The Archive button is hidden for admin accounts in User Management.

---

### Faculty Accounts

#### Faculty 1 — Maria Reyes

| Field | Value |
|---|---|
| Username | `mreyes` |
| Password | `password` |
| Email | mreyes@ojtms.edu.ph |
| Employee ID | FAC-001 |
| Role | Faculty |
| Handled Section | BSIT-4A |
| Department | IT Department |

**Students under mreyes:** `acruz` (clean), `mdelatorre` (in progress)

---

#### Faculty 2 — Jose Santos

| Field | Value |
|---|---|
| Username | `jsantos` |
| Password | `password` |
| Email | jsantos@ojtms.edu.ph |
| Employee ID | FAC-002 |
| Role | Faculty |
| Handled Section | BSIT-4B |
| Department | IT Department |

**Students under jsantos:** `svillanueva` (advanced), `cmendoza` (clean)

---

### Student Accounts

#### Student 1 — Anna Cruz (`acruz`) — CLEAN

| Field | Value |
|---|---|
| Username | `acruz` |
| Password | `password` |
| Email | acruz@student.ojtms.edu.ph |
| Student ID | STU-2526-001 |
| Section | BSIT-4A (under mreyes) |
| Checklist State | No submissions yet |

**Use case:** Demonstrate the full submission workflow from scratch. This student has no submissions, so you can use this account to show how a student starts and submits each checklist item step by step.

---

#### Student 2 — Miguel Dela Torre (`mdelatorre`) — IN PROGRESS

| Field | Value |
|---|---|
| Username | `mdelatorre` |
| Password | `password` |
| Email | mdelatorre@student.ojtms.edu.ph |
| Student ID | STU-2526-002 |
| Section | BSIT-4A (under mreyes) |
| Checklist State | Partially completed |

**Pre-seeded checklist data:**

| Item | Status | Notes |
|---|---|---|
| Medical Record | Approved | Clinic: MSTIP Clinic, Makati City |
| Receipt of OJT Kit | Approved | OR No. OR-2526-0042 |
| Waiver | Pending | Guardian: Ricardo Dela Torre |
| Endorsement Letter | Pending | Company: ABC Tech Company, Makati City |
| DTR — Week 1 | Approved | 40 hours, validated by Mr. John Supervisor |
| DTR — Week 2 | Pending | 40 hours, awaiting faculty review |
| Weekly Report — Week 1 | Pending | Front-end development tasks |

**Use case:** Demonstrate the faculty review workflow. Log in as `mreyes` to see these pending submissions and approve or decline them.

---

#### Student 3 — Sofia Villanueva (`svillanueva`) — ADVANCED

| Field | Value |
|---|---|
| Username | `svillanueva` |
| Password | `password` |
| Email | svillanueva@student.ojtms.edu.ph |
| Student ID | STU-2526-003 |
| Section | BSIT-4B (under jsantos) |
| Checklist State | Most items completed |

**Pre-seeded checklist data:**

| Item | Status | Notes |
|---|---|---|
| Medical Record | Approved | Clinic: City Health Center, Makati City |
| Receipt of OJT Kit | Approved | OR No. OR-2526-0017 |
| Waiver | Approved | Guardian: Luisa Villanueva |
| Endorsement Letter | Approved | OJT started ~45 days ago |
| MOA | Approved | Signed by XYZ Solutions Inc. |
| DTR — Week 1 (Nov 2025) | Approved | 40 hours |
| DTR — Week 2 (Nov 2025) | Approved | 40 hours |
| DTR — Week 3 (Dec 2025) | Approved | 40 hours |
| DTR — Week 4 (Jan 2026) | Pending | 40 hours, awaiting review |
| Weekly Report — Week 1 | Approved | Tasks completed |
| Weekly Report — Week 2 | Approved | Tasks completed |
| Weekly Report — Week 3 | Pending | Awaiting review |
| Monthly Appraisal — Nov 2025 | Pending | Grade: 90, evaluated by Ms. Ana Torres |
| Supervisor Evaluation | Pending | Grade: 88 |

**Total approved DTR hours:** 120 out of 720 target hours (3 weeks approved)

**Use case:** Demonstrate the Decision Support and AI analysis features. Log in as `jsantos` to see a student with advanced progress and use Decision Support to generate recommendations.

---

#### Student 4 — Carlos Mendoza (`cmendoza`) — CLEAN

| Field | Value |
|---|---|
| Username | `cmendoza` |
| Password | `password` |
| Email | cmendoza@student.ojtms.edu.ph |
| Student ID | STU-2526-004 |
| Section | BSIT-4B (under jsantos) |
| Checklist State | No submissions yet |

**Use case:** Second clean student in Section B for comparison. Can be used to show an empty checklist state alongside `svillanueva`'s advanced state.

---

### Recommended Demo Flow

| Step | Action | Account |
|---|---|---|
| 1 | Start a new submission from scratch | Login as `acruz` |
| 2 | View partially completed checklist and pending items | Login as `mdelatorre` |
| 3 | Review and approve/decline `mdelatorre`'s submissions | Login as `mreyes` |
| 4 | View an advanced student with most items done | Login as `svillanueva` |
| 5 | Review advanced submissions and use Decision Support | Login as `jsantos` |
| 6 | Manage users, sections, CMS settings, announcements | Login as `admin` |

---

## 6. Accessing the System

1. Open your web browser.
2. Navigate to the system URL provided by your administrator.
3. You will be directed to the **Login Page**.
4. Enter your **Username** and **Password**.
5. Click **Login**.

Each role is redirected to its own dashboard after login:
- Admin → `/admin/dashboard`
- Faculty → `/faculty/dashboard`
- Student → `/student/dashboard`

> **Note:** If you forget your password, contact the system administrator to reset it.

---

## 7. User Roles

| Role | Description |
|---|---|
| **Admin** | Manages users, sections, announcements, and system settings |
| **Faculty** | Reviews student submissions, manages FAQ and announcements, uses AI decision support |
| **Student** | Submits OJT documents, DTR, reports, appraisals, and incident reports |

---

## 8. Admin Module

### 8.1 Admin Dashboard

The Admin Dashboard provides a system-wide overview including:
- Total number of students, faculty, and sections
- Submission statistics (approved, pending, declined)
- Recent system activity

---

### 8.2 User Management

Admins can create, view, edit, and manage all user accounts (Admin, Faculty, Student).

**To create a new user:**
1. Go to **User Management** from the sidebar.
2. Click **Add User**.
3. Fill in the required fields:
   - Role (Admin / Faculty / Student)
   - First Name, Last Name
   - Username, Email, Password
   - Contact Number, Department
   - Section (for students)
4. Click **Save**.

**To import students via CSV:**
1. Go to **User Management**.
2. Click **Bulk Import**.
3. Download the CSV template if needed.
4. Fill in student data following the template format.
5. Upload the completed CSV file and click **Import**.

**To edit a user:**
1. Find the user in the list.
2. Click **Edit**.
3. Modify the fields and click **Save**.

**To archive (soft-delete) a user:**
1. Find the user in the list.
2. Click the **Archive** (trash) button.
3. Confirm the action.

> **Note:** The Admin account cannot be archived or deleted. The Archive button is hidden for admin-role users.

**To restore an archived user:**
1. Click **Archived Users** from the User Management page.
2. Find the user and click **Restore**.

---

### 8.3 Section Management

Sections represent OJT class groups assigned to a faculty member.

**To create a section:**
1. Go to **Section Management** from the sidebar.
2. Click **Add Section**.
3. Fill in:
   - Section Name (e.g., BSIT-4A)
   - School Year
   - Term
   - Schedule (Day, Start Time, End Time)
   - Room
   - Capacity
   - Assigned Faculty
4. Click **Save**.

**To assign or change faculty for a section:**
1. Click **Edit** on the section.
2. Update the **Faculty** field.
3. Click **Save**.

---

### 8.4 Announcements (Admin)

**To post an announcement:**
1. Go to **Announcements** from the sidebar.
2. Click **New Announcement**.
3. Enter a **Title** and **Content**.
4. Toggle the status to **Active** to make it visible.
5. Click **Save**.

**To edit or deactivate an announcement:**
1. Find the announcement in the list.
2. Click **Edit** to modify, or toggle the status to **Inactive** to hide it.

---

### 8.5 CMS Settings

CMS (Content Management System) allows the admin to configure system-wide settings including the OpenAI API key for the chatbot.

**To update CMS settings:**
1. Go to **CMS Settings** from the sidebar.
2. Update the relevant fields:
   - **OpenAI API Key** — Required for AI Chatbot and Decision Support features
   - **System Name**, **Contact Email**, and other settings
3. Click **Save**.

> **Important:** Without a valid OpenAI API Key, the AI Chatbot and Decision Support features will not function.

---

### 8.6 Reports (Admin)

The Reports module provides system-wide data exports.

1. Go to **Reports** from the sidebar.
2. Select the report type and filters (section, date range, etc.).
3. Click **Generate** or **Export** to download the report.

---

## 9. Faculty Module

### 9.1 Faculty Dashboard

Displays an overview of the faculty's handled section(s):
- Total students, submission counts (approved / pending / declined)
- Per-item submission statistics across all checklist items
- Student progress ranking (by checklist completion %)
- Recent submission activity

---

### 9.2 Handled Section

**To view students in your section:**
1. Go to **Handled Section** from the sidebar.
2. Click on your section name.
3. A list of enrolled students will appear with their overall checklist progress.

**To view a specific student's full checklist:**
1. Click on the student's name or the **View Checklist** button.
2. You will see all checklist items with their current status (Not Submitted / Pending / Approved / Declined).
3. Click on any item to review its details.

---

### 9.3 Reviewing Student Checklist Items

**One-time items** (Medical Record, Receipt of OJT Kit, Waiver, Endorsement Letter, MOA):

1. Click on the checklist item for a student.
2. Review the submitted information and uploaded file(s).
3. In the **Faculty Review** section:
   - Select **Status**: Approved or Declined
   - Add **Remarks** (required if declining)
4. Click **Save Review**.

**Recurring items** (DTR, Weekly Report, Monthly Appraisal, Supervisor Evaluation, Certificate of Completion):

1. Click on the checklist item (e.g., DTR).
2. A table of all submissions for that item appears.
3. Click **Review** next to any entry.
4. A modal will open showing submission details.
5. Set the **Status** and add remarks if needed.
6. For DTR: you may also set the **Target Hours** (default 720 hours).
7. Click **Save Review**.

> **Declined submissions** require a remark/reason. The form will alert you if remarks are missing.

---

### 9.4 Incident Reports (Faculty)

Students can submit incident reports about workplace issues. Faculty are responsible for responding.

**To review an incident report:**
1. Go to **Incident Reports** from the sidebar.
2. Click **Review** on any report.
3. Review the details (type, date, location, description, evidence).
4. Set the **Status**: Pending / Reviewing / Action Taken / Resolved / Declined
5. Add **Faculty Remarks** describing the action taken or response.
6. Click **Save**.

---

### 9.5 Reports (Faculty)

1. Go to **Reports** from the sidebar.
2. Select your section and desired filters.
3. Generate or export the report (PDF / CSV).

---

### 9.6 FAQ Management

**To add an FAQ:**
1. Go to **FAQ Management** from the sidebar.
2. Click **Add FAQ**.
3. Enter the **Question** and **Answer**.
4. Set the **Category** if applicable.
5. Click **Save**.

**To edit or delete an FAQ:**
1. Find the FAQ entry.
2. Click **Edit** to update, or **Delete** to remove.

---

### 9.7 Announcements (Faculty)

1. Go to **Announcements** from the sidebar.
2. Click **New Announcement**.
3. Fill in the Title and Content.
4. Set to Active and click **Save**.

---

### 9.8 AI Chatbot (Faculty)

The AI Chatbot is powered by OpenAI GPT and has access to your section's student data for context-aware responses.

**To use the chatbot:**
1. Go to **AI Chatbot** from the sidebar.
2. Type your question in the input box and press **Send** or hit Enter.

**Example queries:**
- "Which students have not yet submitted their DTR this week?"
- "What is the overall checklist completion rate of my section?"
- "How do I approve a student's waiver submission?"

---

### 9.9 Decision Support

The Decision Support module uses AI to analyze student performance data and provide recommendations.

**To use Decision Support:**
1. Go to **Decision Support** from the sidebar.
2. The system analyzes all students based on:
   - Checklist completion percentage
   - Approved DTR hours vs. target hours
   - Pending/declined submission rates
   - Incident reports
3. Review the AI-generated rankings and recommendations.

---

## 10. Student Module

### 10.1 Student Dashboard

After login, students see their personal OJT progress dashboard:
- Checklist completion (X out of 10 items completed)
- Total approved DTR hours vs. target hours (progress bar)
- Submission statistics (total / approved / pending / declined)
- Weekly and monthly submission counts
- Recent activity feed

---

### 10.2 OJT Checklist

The checklist contains items that must be completed in order.

**Status indicators:**
- **Locked** — Cannot submit yet; complete the previous step first
- **Not Submitted** — Ready to submit
- **Pending Review** — Submitted, waiting for faculty approval
- **Declined — Resubmit** — Faculty declined; review the reason and resubmit
- **Approved** — Completed and approved

**To submit a checklist item:**
1. Click the **Submit** button on an unlocked item.
2. Fill in the required fields (see Section 11).
3. Upload any required files.
4. Click **Submit**.

**To resubmit a declined item:**
1. Click **Resubmit** on the declined item.
2. Review the faculty's reason for declining.
3. Correct the information and/or upload a new file.
4. Click **Submit**.

---

### 10.3 Daily Time Record (DTR)

**To submit a new DTR:**
1. Go to **DTR** from the sidebar.
2. Click **Submit New DTR**.
3. Fill in: Week, Hours Rendered, Validated By, Remarks (optional), and upload the DTR file.
4. Click **Submit**.

**Hours Summary:** Tracks total approved hours vs. target (default 720 hours). A progress bar shows your completion percentage.

> **Prerequisite:** Receipt of OJT Kit must be submitted before DTR submissions are allowed.

---

### 10.4 Weekly Report

**To submit a weekly report:**
1. Go to **Weekly Report** from the sidebar.
2. Click **Submit New Report**.
3. Fill in: Week, Task Description, Supervisor Feedback, and upload optional file(s).
4. Click **Submit**.

> **Prerequisite:** Receipt of OJT Kit must be submitted.

---

### 10.5 Monthly Appraisal

**To submit a monthly appraisal:**
1. Go to **Monthly Appraisal** from the sidebar.
2. Click **Submit New Appraisal**.
3. Fill in: Month, Grade/Rating, Evaluated By, and upload the appraisal file.
4. Click **Submit**.

> **Prerequisite:** Receipt of OJT Kit must be submitted.

---

### 10.6 Supervisor Evaluation

**To submit a supervisor evaluation:**
1. Go to **Supervisor Evaluation** from the sidebar.
2. Click **Submit Evaluation**.
3. Fill in: Grade/Rating and upload the signed evaluation form.
4. Click **Submit**.

> **Prerequisite:** Receipt of OJT Kit must be submitted.

---

### 10.7 Certificate of Completion (COC)

**To submit a COC:**
1. Go to **COC** from the sidebar.
2. Click **Submit Certificate**.
3. Fill in: Company Name, Signed By, Date Issued, Date Received, and upload the COC file.
4. Click **Submit**.

> **Prerequisite:** Receipt of OJT Kit must be submitted.

---

### 10.8 Incident Report

**To submit an incident report:**
1. Go to **Incident Report** from the sidebar.
2. Fill in: Incident Type, Date, Location, Description, Action Taken (optional), and Attachment (optional, max 10 MB).
3. Click **Submit Report**.

**To edit a pending report:** Click **Edit** (only available while status is "Pending").

**To delete a pending report:** Click **Delete** and confirm. This action cannot be undone.

---

### 10.9 Announcements (Student)

View announcements posted by your faculty or the admin.

1. Go to **Announcements** from the sidebar.
2. Click on an announcement to read the full content.

---

### 10.10 FAQ

View frequently asked questions about the OJT process.

1. Go to **FAQ** from the sidebar.
2. Browse the list by category.
3. Click on a question to expand the answer.

---

### 10.11 AI Chatbot (Student)

**To use the chatbot:**
1. Go to **AI Chatbot** from the sidebar.
2. Type your question and press **Send** or hit Enter.

**Example queries:**
- "How many DTR hours have I completed so far?"
- "What documents do I still need to submit?"
- "My supervisor evaluation was declined. What should I do?"

> **Note:** Requires an active internet connection and a valid OpenAI API key configured by the admin.

---

## 11. Checklist Item Reference

| # | Item | Type | Required Fields | File Required |
|---|---|---|---|---|
| 1 | Medical Record | One-time | Clinic/Hospital Name, Address | Yes (PDF/image, multiple) |
| 2 | Receipt of OJT Kit | One-time | Date Paid, OR/Reference Number | Yes (PDF/image) |
| 3 | Waiver | One-time | Guardian Name, Contact Number | Yes (PDF/image) |
| 4 | Endorsement Letter | One-time | Endorsement Date, OJT Start Date, Signed By | Yes (PDF/image) |
| 5 | MOA | One-time | Remarks (optional) | Yes (PDF/image) |
| 6 | DTR | Recurring (weekly) | Week, Hours, Validated By | Yes (PDF/image) |
| 7 | Weekly Report | Recurring (weekly) | Week, Task Description, Supervisor Feedback | Optional |
| 8 | Monthly Appraisal | Recurring (monthly) | Month, Grade/Rating, Evaluated By | Optional |
| 9 | Supervisor Evaluation | Recurring | Grade/Rating | Yes (signed form) |
| 10 | Certificate of Completion | Recurring | Company, Signed By, Date Issued | Yes (PDF/image) |

**Accepted file formats:** PDF, DOC, DOCX, JPG, JPEG, PNG
**Maximum file size:** 5 MB per file (10 MB for incident report attachments)

---

## 12. Troubleshooting

**I cannot log in.**
- Verify your username and password are correct.
- Ensure Caps Lock is not on.
- Contact the admin to confirm your account is active.

**I cannot submit a checklist item (it shows "Locked").**
- Complete the previous checklist step first.
- For DTR and report submissions, the Receipt of OJT Kit must be submitted (even if still pending approval).

**DTR / Weekly Report says "Submit your Receipt of OJT Kit first."**
- Go to the Checklist and submit your Receipt of OJT Kit.
- Once submitted (pending review is fine), DTR and report submissions will unlock.

**The AI Chatbot is not responding.**
- Check your internet connection.
- The OpenAI API key may be missing or invalid — contact the admin to update it under CMS Settings.

**My file upload fails.**
- Ensure the file is under 5 MB.
- Use a supported format: PDF, DOC, DOCX, JPG, JPEG, or PNG.

**A submitted item disappeared from my list.**
- It may have been archived. Scroll to the bottom of the page to find the "Archived" section and restore it.

**Faculty does not see my submission.**
- Confirm you clicked the final **Submit** button and the status shows "Pending Review."

**Demo data needs to be reset.**
- Run the DemoSeeder again:
  ```bash
  php artisan db:seed --class=DemoSeeder
  ```
- This will wipe all existing data and re-seed all demo accounts and checklist states.

---

*For technical support, contact your system administrator.*
*System Version: 1.0 | Academic Year 2025–2026*
*Developed by: D.R. Bignayan, J.R. Claveria, D.F. Cura, J.L. Tapel, K.V. Valeroso*
*Makati Science Technological Institute of the Philippines*
