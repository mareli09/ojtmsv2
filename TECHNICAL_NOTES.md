# Technical Notes
## AI-Assisted OJT Monitoring System with LLM-Powered Chatbot and Decision Support for Internship Management
### Makati Science Technological Institute of the Philippines (MSTIP)

---

## Table of Contents

1. [OpenAI API Integration](#1-openai-api-integration)
2. [Laravel as the Development Framework](#2-laravel-as-the-development-framework)
3. [System Security](#3-system-security)
4. [System Testing](#4-system-testing)

---

## 1. OpenAI API Integration

### 1.1 Overview

The AI-Assisted OJT Monitoring System integrates the OpenAI API to power two core intelligent features: the AI Chatbot and the Decision Support module. The integration is built on top of HTTP-based communication between the Laravel backend and OpenAI's cloud-hosted language model endpoints. Rather than running a language model locally, the system sends structured requests to OpenAI's servers over the internet and receives generated text responses in real time. This approach removes the burden of maintaining heavy AI infrastructure while allowing the system to benefit from one of the most capable and well-documented language models available.

### 1.2 How the API Works in the System

The OpenAI API operates on a request-response model using the REST architecture. When a user submits a query — either through the AI Chatbot or the Decision Support module — the system constructs an API request composed of two key parts: a system prompt and a user prompt. The system prompt establishes the context and behavioral boundaries of the AI, instructing it to behave as an OJT coordinator assistant with access to specific student or section data. The user prompt carries the actual question or request submitted by the user.

This request is sent as a POST request to OpenAI's Chat Completions endpoint (`/v1/chat/completions`), using the `Authorization: Bearer <API_KEY>` header to authenticate the request. The API key used for authentication is stored securely in the system's Content Management System (CMS) database table, managed exclusively by the system administrator. No API key is hardcoded in the source code to prevent accidental exposure.

The system uses the `gpt-3.5-turbo` or `gpt-4` model depending on the configuration. Once the request is received by OpenAI's servers, the language model processes the combined prompt and generates a relevant, context-aware response. This response is returned as a JSON object, and the Laravel backend extracts the generated message from the `choices[0].message.content` field before returning it to the front-end view for display to the user.

### 1.3 Context Injection

One of the key design decisions in this integration is context injection — the practice of embedding relevant system data directly into the AI prompt before sending it to OpenAI. For the student-facing chatbot, the prompt is enriched with the student's current checklist status, total approved DTR hours, pending submissions, and recent activity. For the faculty-facing Decision Support module, the prompt includes aggregated data for all students in the faculty's handled section, including their submission counts, approval rates, DTR progress, and incident reports.

By injecting this structured data into the system prompt, the AI is able to generate answers that are personalized and directly relevant to the user's situation, rather than providing generic OJT guidance. This transforms the chatbot from a static FAQ tool into a dynamic assistant that understands the student's specific OJT journey.

### 1.4 Token Management and Rate Limits

Because OpenAI charges based on the number of tokens (roughly equivalent to words) processed per request, the system is designed to include only the most relevant and concise data in each prompt. Unnecessary fields and verbose descriptions are excluded from the injected context to reduce token usage and keep API costs manageable. The system is designed to keep individual requests well within the token limits of the selected model. OpenAI also enforces rate limits on the number of requests per minute; the system handles API errors gracefully by catching exceptions and displaying a user-friendly error message when the API is unavailable or the request fails.

### 1.5 API Key Management

The OpenAI API key is stored in the `cms_data` table and retrieved at runtime whenever an AI-powered request is initiated. The admin manages the key through the CMS Settings panel in the admin dashboard. This design ensures that the API key can be updated, rotated, or revoked without modifying any source code or redeploying the application. If the API key is missing or invalid, the AI features are gracefully disabled and the user is shown an appropriate notification.

---

## 2. Laravel as the Development Framework

### 2.1 Overview

The AI-Assisted OJT Monitoring System is built entirely on Laravel, a PHP web application framework following the Model-View-Controller (MVC) architectural pattern. Laravel was chosen as the primary development framework because of its rich feature set, strong community support, and its suitability for building data-intensive, role-based web applications of the scale and complexity required by this system. Its conventions reduce boilerplate code and allow the development team to focus on implementing business logic rather than infrastructure concerns.

### 2.2 Routing and Request Handling

Laravel's routing system provides a clean and expressive way to define all HTTP endpoints in a centralized location. The system uses a single `routes/web.php` file to define all routes for the Admin, Faculty, and Student modules, organized using route prefixes and grouped middleware for authentication and role enforcement. This centralized routing approach makes the codebase easy to navigate and maintain, as any developer can trace the entire application's URL structure from a single file.

### 2.3 Eloquent ORM and Database Interaction

Laravel's Eloquent ORM (Object-Relational Mapper) abstracts the complexity of raw SQL queries into intuitive, chainable PHP methods. Each database table in the system — users, sections, student_checklists, announcements, faqs, and incident_reports — is represented by an Eloquent model that defines relationships, fillable fields, and casting rules. For example, JSON fields such as `student_files` (an array of uploaded file paths) are automatically cast to and from PHP arrays by Eloquent, eliminating the need for manual serialization logic. SoftDeletes are also implemented on key models, allowing records to be archived without permanent deletion, which supports the system's archive and restore functionality for users and submissions.

### 2.4 Blade Templating Engine

All user interface views in the system are built using Laravel's Blade templating engine. Blade provides server-side rendering with clean, readable syntax for conditionals, loops, and component reuse. The use of a shared layout file with named sections allows the Admin, Faculty, and Student dashboards to share a consistent visual structure — including the sidebar navigation and header — while rendering role-specific content in the main content area. Blade's `@if`, `@foreach`, and `@include` directives make it straightforward to render dynamic checklist states, submission tables, modal dialogs, and progress indicators without mixing PHP and HTML in an unstructured way.

### 2.5 Database Migrations and Seeders

Laravel's migration system allows the database schema to be version-controlled alongside the source code. Every table in the system was created through a migration file, ensuring that the database structure can be reproduced exactly on any new environment by running `php artisan migrate`. This is especially useful for deployment and testing purposes. Complementing migrations, Laravel's seeder system provides a structured way to populate the database with initial or test data. The system includes a `DemoSeeder` that fully resets the database to a known, demo-ready state — creating sections, admin accounts, faculty accounts, and students with varied checklist states — which is invaluable for demonstrations, testing, and capstone presentations.

### 2.6 Session-Based Authentication

Rather than relying on external authentication packages, the system implements a lightweight custom session-based authentication mechanism. Upon a successful login, the authenticated user object is stored in the Laravel session store. A custom `checkauth` middleware is applied to all protected routes, verifying the presence and validity of the session on every request. If the session is absent or expired, the middleware redirects the user to the login page. Role-specific access control is enforced inline within route handlers, where the user's role (`admin`, `faculty`, or `student`) is checked before rendering role-specific views or performing role-specific operations.

### 2.7 File Storage

Laravel's filesystem abstraction, backed by the `Storage` facade, handles all file uploads in the system. Student-uploaded documents — such as medical records, DTR sheets, and endorsement letters — are stored in the local disk under the `storage/app/private` directory, outside the publicly accessible web root. A dedicated download route streams these files securely to authorized users after verifying their session and role, preventing unauthorized direct access to uploaded documents. This design ensures that sensitive OJT documents are not exposed to unauthenticated users through direct URL access.

---

## 3. System Security

### 3.1 Overview

Security is a foundational concern in the design of the AI-Assisted OJT Monitoring System, given that it stores and processes sensitive student and faculty information including personal data, OJT documents, and performance records. The system implements multiple layers of security to protect data integrity, prevent unauthorized access, and guard against common web application vulnerabilities.

### 3.2 Authentication and Session Management

All system routes except the login page are protected by the `checkauth` middleware, which verifies that a valid authenticated session exists before processing any request. Sessions are managed by Laravel's built-in session handler, which stores session data server-side and issues only a session identifier cookie to the browser. This prevents session data from being tampered with on the client side. Session cookies are configured with the `HttpOnly` attribute, which prevents JavaScript from reading the cookie and mitigates the risk of session hijacking through cross-site scripting attacks.

### 3.3 Role-Based Access Control (RBAC)

The system enforces strict role-based access control across all modules. Each of the three user roles — Admin, Faculty, and Student — has a distinct URL namespace (`/admin`, `/faculty`, `/student`) and all routes within each namespace explicitly verify the authenticated user's role before proceeding. A student cannot access faculty routes, a faculty member cannot access admin routes, and vice versa. Attempts to access unauthorized routes result in a redirect to the user's appropriate dashboard. This layered enforcement ensures that even if a user guesses a URL, they cannot access data or functionality outside their role.

### 3.4 Cross-Site Request Forgery (CSRF) Protection

All HTML forms in the system include Laravel's `@csrf` Blade directive, which embeds a hidden CSRF token field in every form. Laravel's middleware automatically validates this token on every POST, PUT, PATCH, and DELETE request. Any request that arrives without a valid CSRF token — such as a request forged by a malicious third-party website — is rejected with a 419 HTTP error before any data is processed. This protects all form-based operations, including login, checklist submissions, faculty reviews, user management, and CMS updates, from cross-site request forgery attacks.

### 3.5 Password Hashing

User passwords are never stored in plaintext. All passwords are hashed using the `bcrypt` algorithm via Laravel's `Hash::make()` method before being persisted to the database. Bcrypt is a computationally expensive, salted hashing algorithm specifically designed for password storage, making brute-force and rainbow table attacks impractical. During login, the submitted password is verified against the stored hash using `Hash::check()`, which compares the hashes without ever decrypting the stored value.

### 3.6 File Upload Security

The system applies strict validation to all file upload inputs before storing them. Only specific MIME types are accepted — PDF, DOC, DOCX, JPG, JPEG, and PNG for checklist documents, with additional MP4 and MOV support for incident report attachments. File size limits are enforced (5 MB for standard uploads, 10 MB for incident attachments). Uploaded files are stored outside the public web root in a private storage directory, meaning they cannot be accessed directly through a browser URL. All file downloads are routed through a protected Laravel endpoint that verifies the user's authentication and authorization before streaming the file response.

### 3.7 Protection Against SQL Injection

Because all database queries are executed through Laravel's Eloquent ORM and the Query Builder, user-supplied input is never concatenated directly into SQL strings. Laravel uses PDO prepared statements internally, which parameterize all query inputs and prevent SQL injection attacks. Even search filters and dynamic query conditions used in the user management and report generation features are passed through Laravel's query builder methods, ensuring safe parameterization throughout.

### 3.8 Protection Against Cross-Site Scripting (XSS)

All user-generated content rendered in Blade views is passed through Laravel's `{{ }}` syntax, which automatically applies PHP's `htmlspecialchars()` function to escape HTML entities. This prevents malicious scripts injected through form fields — such as announcement content, remarks, or task descriptions — from being executed in the browser. Only trusted, developer-authored content uses the unescaped `{!! !!}` syntax where explicitly necessary.

### 3.9 Soft Deletion and Data Preservation

Rather than permanently deleting records, the system uses Laravel's SoftDeletes feature for users and checklist records. Deleted records are marked with a `deleted_at` timestamp and excluded from normal queries while remaining in the database for audit and recovery purposes. This prevents accidental data loss and allows administrators to restore archived users or submission records when needed.

### 3.10 API Key Security

The OpenAI API key is stored in the database and managed through the CMS Settings panel rather than in environment variables or source files. This prevents the key from being accidentally committed to a version control repository or exposed through server configuration file access. The key is only loaded into memory at runtime, when an AI-powered request is initiated, and is never returned to the front-end or included in any client-facing response.

---

## 4. System Testing

### 4.1 Overview

Testing is a critical phase in the development of the AI-Assisted OJT Monitoring System to ensure that all modules function correctly, the user experience is reliable, and the system is stable under realistic usage conditions. The testing strategy for this system covers multiple levels — from individual feature verification to end-to-end workflow simulation — reflecting the Agile development methodology adopted throughout the project.

### 4.2 Functional Testing

Functional testing verifies that each feature of the system performs its intended behavior. For this system, functional tests are conducted for every user-facing operation across all three roles. On the student side, this includes testing the submission of each of the ten checklist items — verifying that forms accept valid inputs, reject invalid inputs, correctly save data to the database, and update the checklist status accordingly. For the faculty side, functional testing covers the review and approval/decline workflow for both one-time and recurring checklist items, verifying that status changes are persisted, remarks are saved, and the student's checklist view reflects the updated status in real time. Admin functional tests cover user creation, editing, archiving, restoration, section assignment, announcement management, and CMS configuration updates.

### 4.3 Role-Based Access Testing

Because the system serves three distinct user roles, access control testing is conducted to verify that role boundaries are properly enforced. This involves attempting to access admin routes while authenticated as a student or faculty member, attempting to access another student's submission data, and verifying that the system redirects unauthorized requests to the appropriate dashboard rather than exposing restricted content. These tests confirm that the RBAC implementation is consistent across all route groups and cannot be bypassed through direct URL manipulation.

### 4.4 Checklist Workflow Testing

The checklist submission and unlocking logic represents one of the most critical flows in the system, and it is tested end-to-end to ensure correctness. Testing verifies that the Medical Record item is locked until the Registration Card is approved, that the Receipt of OJT Kit is locked until the Medical Record is approved, and that all subsequent items — DTR, weekly reports, monthly appraisals, and others — unlock once the Receipt of OJT Kit has been submitted. Testing also covers resubmission scenarios where a faculty member declines a submission and the student is required to resubmit with corrections, verifying that the system correctly prompts the student and replaces the declined entry.

### 4.5 File Upload Testing

File upload functionality is tested with both valid and invalid inputs to verify that the system enforces its acceptance rules. Tests include uploading files within the allowed size limits and supported MIME types to confirm successful storage, and uploading files that exceed the size limit or use unsupported formats to confirm that validation errors are returned and no file is stored. Download functionality is also tested by verifying that authenticated users can access their own uploaded files while unauthenticated requests to the download route are redirected to the login page.

### 4.6 AI Chatbot and Decision Support Testing

The AI-powered features are tested both for functional correctness and for response quality. Functional testing verifies that the chatbot sends a well-formed request to the OpenAI API, correctly injects the user's OJT context data into the prompt, and displays the response without errors. Edge cases tested include scenarios where the API key is missing or invalid, where the user has no submissions yet, and where the OpenAI API is temporarily unreachable — verifying that the system displays a graceful error message rather than crashing. Response quality is assessed manually by reviewing a set of sample queries and evaluating whether the AI's answers are relevant, accurate based on the injected context, and appropriately scoped to OJT-related topics.

### 4.7 Database Seeder Testing

The `DemoSeeder` is tested by running it against a clean database instance and verifying that all expected records are created correctly: two sections with assigned faculty, one admin account, two faculty accounts, four student accounts, and the expected pre-seeded checklist states for `mdelatorre` and `svillanueva`. The seeder is also tested for idempotency — verifying that running it multiple times in succession consistently produces the same final database state, with previously existing data fully cleared before re-seeding. This is important for demonstration and reset scenarios where the seeder may be run repeatedly.

### 4.8 User Acceptance Testing (UAT)

User Acceptance Testing is conducted with actual end users representing each role — a student, a faculty member, and an administrator — to evaluate whether the system meets real-world usability expectations. Participants are given a set of tasks to complete without assistance, such as submitting a DTR, reviewing a student's checklist, posting an announcement, or updating CMS settings. Their interactions are observed and any points of confusion, unexpected behavior, or usability issues are recorded. Feedback gathered during UAT informs final adjustments to the user interface, error messages, and navigation structure before the system is deployed.

### 4.9 Security Testing

Basic security testing is performed to verify the integrity of the system's protective mechanisms. CSRF protection is tested by constructing a form submission without a valid CSRF token and verifying that the request is rejected. SQL injection resilience is verified by submitting known SQL injection patterns through form inputs and confirming that the inputs are treated as literal strings rather than executed as query fragments. XSS resistance is tested by entering JavaScript tags in text fields such as remarks and announcement content, and verifying that the submitted content is rendered as escaped text in the view rather than executed as a script.

### 4.10 Browser Compatibility Testing

The system's front-end interface is tested across major modern browsers — Google Chrome, Mozilla Firefox, and Microsoft Edge — to ensure consistent rendering, modal behavior, form validation, and responsive layout. Critical UI components such as the checklist table, DTR progress bar, review modals, and the AI Chatbot message interface are verified to function correctly across all tested browsers. Any browser-specific inconsistencies in JavaScript behavior or CSS rendering are identified and corrected during this phase.

---

*Makati Science Technological Institute of the Philippines*
*Academic Year 2025–2026*
