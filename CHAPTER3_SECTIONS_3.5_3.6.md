# Chapter 3 — Sections 3.5 and 3.6
## AI-Assisted OJT Monitoring System with LLM-Powered Chatbot and Decision Support for Internship Management
### Makati Science Technological Institute of the Philippines (MSTIP)

---

## 3.5 Ethical Consideration

Ethical standards must be upheld throughout the development of the AI-Assisted OJT Monitoring System. Proper ethical priorities were observed across all phases of the software development life cycle — from requirements specification and system design, down to implementation and deployment. The researchers considered the ethical dimensions of utilizing open-source frameworks, third-party libraries, and cloud-based AI services, ensuring that all tools and services used are properly licensed and applied within their intended scope of use. The integration of OpenAI's language model API was conducted in compliance with OpenAI's published usage policies, and the API key is managed securely to prevent unauthorized access or misuse.

To avoid ethical shortcomings between stakeholders, the researchers engaged in close collaboration with the involved entities throughout the development process. Representatives from the student, faculty, and administrative perspectives were considered in the design of the system's modules and workflows to ensure that the platform addresses real needs without imposing unnecessary burden on any party. Once the system is fully developed, the researchers are committed to delivering the completed application along with its full technical documentation, a system manual, and a scheduled demonstration and orientation for its prospective users — including students, faculty OJT coordinators, and system administrators. However, the researchers retain ownership of the source code and intellectual contributions of the project.

The use of the AI-Assisted OJT Monitoring System will not be imposed upon its users. Participation in using the platform remains the decision of the institution and the individuals within it. The system will not collect, process, or transmit data beyond what the user explicitly initiates through the platform's interface. All personally identifiable information — including student records, submission documents, and performance data — is stored within the institution's own server environment and is accessible only to authorized users based on their assigned role. No student data is shared with external parties, and data submitted through the AI Chatbot is transmitted to OpenAI's API only for the purpose of generating a response, in accordance with OpenAI's data handling and privacy policies. The researchers ensured that no sensitive institutional data is retained by third-party services beyond the scope of each individual API request.

Furthermore, the AI-generated outputs produced by the Decision Support module and the AI Chatbot are treated as recommendations and informational aids only. All final decisions regarding student OJT status, document approval, and academic records remain the responsibility of the faculty and the administration. The system is designed to assist and support human judgment, not to replace it. The researchers acknowledge that AI language models may produce outputs that are imprecise or context-dependent, and users are advised to exercise critical judgment when interpreting AI-generated content.

---

## 3.6 Testing and Evaluation Procedure

### Measurable Objectives

In line with the project's testing procedure as presented in Table 5, the following criteria are defined to determine whether the research objectives of the AI-Assisted OJT Monitoring System are properly achieved. The evaluation is structured around the internationally recognized ISO/IEC 25010 standard for software product quality, which provides a comprehensive framework for assessing software systems across multiple quality characteristics. Each characteristic and its corresponding sub-characteristics serve as measurable dimensions against which the system's performance, reliability, usability, and overall quality are evaluated by the designated respondents.

---

**Table 5. Testing Procedure**

| Problem Statements | Procedure |
|---|---|
| What are the features and functionalities needed by students, faculty, and administrators to effectively manage the OJT internship monitoring process? | Review of OJT process requirements and existing institutional workflows |
| What methodologies and technologies will be utilized to develop the AI-Assisted OJT Monitoring System with LLM-powered chatbot and decision support? | Application of Agile Methodology with Laravel framework and OpenAI API integration |
| How will the entire software system be tested for quality assurance? | Evaluation using ISO 25010 criteria |
| Will the proposed AI-Assisted OJT Monitoring System be accepted for academic and institutional use by its target users? | Acceptability testing through structured survey evaluation |
| What are the improvements or recommendations to be considered in incorporating this proposed system into the institution's OJT management process? | User recommendation and system enhancement based on evaluation results |

---

**ISO 25010 standard for software quality assurance**

**Functional Suitability** - This characteristic represents the degree to which the AI-Assisted OJT Monitoring System provides functions that meet the stated and implied needs of students, faculty, and administrators when used under specified conditions. This characteristic is composed of the following sub-characteristics:

- **Functional completeness** - Degree to which the set of system functions covers all specified OJT monitoring tasks and user objectives across all three roles, including checklist submission, DTR tracking, faculty review, AI chatbot, decision support, announcement management, and incident reporting.
- **Functional correctness** - Degree to which the system provides correct results — including checklist status updates, DTR hour calculations, AI chatbot responses, and faculty review outcomes — with the needed degree of precision.
- **Functional appropriateness** - Degree to which the system's functions facilitate the accomplishment of OJT management tasks effectively for students, faculty, and administrators without unnecessary steps or complexity.

**Performance Efficiency** - This characteristic represents the performance of the AI-Assisted OJT Monitoring System relative to the number of resources used under stated conditions. This characteristic is composed of the following sub-characteristics:

- **Time behavior** - Degree to which the response and processing times for page loads, form submissions, file uploads, and AI chatbot responses meet acceptable performance thresholds under normal usage conditions.
- **Resource utilization** - Degree to which the system uses server resources — including memory, processing, and storage — efficiently when handling concurrent user requests and database operations.
- **Capacity** - Degree to which the system can accommodate the expected number of simultaneous users, file uploads, and database records without degradation in performance.

**Compatibility** - Degree to which the AI-Assisted OJT Monitoring System can exchange information with other products, systems, or components, and perform its required functions while sharing the same hardware or software environment. This characteristic is composed of the following sub-characteristics:

- **Co-existence** - Degree to which the system operates correctly and efficiently alongside other applications or services sharing the same server or browser environment without causing detrimental impact on any other product.
- **Interoperability** - Degree to which the system can exchange information with external services — specifically the OpenAI API — and correctly use the returned data to generate chatbot responses and decision support recommendations.

**Usability** - Degree to which the AI-Assisted OJT Monitoring System can be used by students, faculty, and administrators to achieve specified OJT management goals with effectiveness, efficiency, and satisfaction in a specified context of use. This characteristic is composed of the following sub-characteristics:

- **Appropriateness recognizability** - Degree to which users can recognize whether the system is appropriate for managing their OJT-related tasks based on the interface design, navigation labels, and available features presented upon first use.
- **Learnability** - Degree to which students, faculty, and administrators can learn to use the system effectively and efficiently within a short orientation period, without requiring extensive technical training.
- **Operability** - Degree to which the system has attributes that make it easy to operate and control, including form submission flows, checklist navigation, file uploads, and faculty review workflows.
- **User error protection** - Degree to which the system protects users against making errors — such as submitting incomplete forms, uploading unsupported file types, or accessing unauthorized routes — through validation messages, warnings, and confirmation prompts.
- **User interface aesthetics** - Degree to which the system's user interface enables a visually organized, consistent, and satisfying interaction for users across all three role dashboards.
- **Accessibility** - Degree to which the system can be accessed and used effectively by users with varying levels of technical proficiency using standard web browsers on different devices.

**Reliability** - Degree to which the AI-Assisted OJT Monitoring System performs its specified functions under specified conditions for a specified period of time. This characteristic is composed of the following sub-characteristics:

- **Maturity** - Degree to which the system meets needs for reliability under normal operation, performing consistently without unexpected failures during sustained use by students, faculty, and administrators.
- **Availability** - Degree to which the system is operational and accessible to authorized users when required, with minimal unplanned downtime throughout the OJT period.
- **Fault tolerance** - Degree to which the system continues to operate as intended despite the presence of errors — such as an unavailable AI API, a failed file upload, or an invalid form input — by handling exceptions gracefully and displaying appropriate error messages.
- **Recoverability** - Degree to which, in the event of an interruption or failure, the system can recover the data directly affected and re-establish the desired state — including restoration of archived users, resubmission of declined checklist entries, and session recovery after timeout.

**Security** - Degree to which the AI-Assisted OJT Monitoring System protects information and data so that persons or other products or systems have the degree of data access appropriate to their types and levels of authorization. This characteristic is composed of the following sub-characteristics:

- **Confidentiality** - Degree to which the system ensures that student records, OJT documents, and account credentials are accessible only to users with the appropriate role-based authorization.
- **Integrity** - Degree to which the system prevents unauthorized modification of student submissions, checklist statuses, DTR records, and user account data throughout its operation.
- **Non-repudiation** - Degree to which actions performed in the system — such as document submissions, faculty approvals or declines, and announcement postings — can be traced and proven to have occurred.
- **Accountability** - Degree to which the actions of each user — student, faculty, and admin — can be uniquely attributed to their account through session-based authentication and role-specific activity tracking.
- **Authenticity** - Degree to which the system verifies the identity of users before granting access, ensuring that only registered and active accounts can log in and interact with the platform.

**Maintainability** - Degree to which the AI-Assisted OJT Monitoring System can be modified effectively and efficiently by the developers to improve it, correct it, or adapt it to changes in environment and requirements. This characteristic is composed of the following sub-characteristics:

- **Modularity** - Degree to which the system is composed of distinct components — including route groups, models, Blade views, middleware, and seeders — such that changes to one component have minimal impact on others.
- **Reusability** - Degree to which existing system components, such as shared Blade layouts, custom middleware, and database seeders, can be reused across different parts of the application without redundancy.
- **Analysability** - Degree to which it is possible to assess the impact of a change or diagnose deficiencies and causes of failures within the system, aided by Laravel's centralized routing and structured codebase organization.
- **Modifiability** - Degree to which the system can be effectively modified — such as adding new checklist items, updating CMS settings, or extending AI prompt logic — without introducing defects or degrading existing functionality.
- **Testability** - Degree to which the system's components can be tested effectively, including the use of database seeders for reproducible test states and role-based access verification across all user roles.

**Portability** - Degree to which the AI-Assisted OJT Monitoring System can be transferred from one hardware, software, or other operational or usage environment to another. This characteristic is composed of the following sub-characteristics:

- **Adaptability** - Degree to which the system can be successfully adapted for deployment across different server environments — including local development, shared hosting, and cloud-based servers — without requiring structural changes to the codebase.
- **Installability** - Degree to which the system can be successfully installed and configured in a specified environment using the provided setup procedure, including database migration, environment configuration, and storage linking.
- **Replaceability** - Degree to which the system can replace or be replaced by another OJT management tool in the same environment, with data and configurations transferable through the existing migration and seeder infrastructure.

---

### Statistical Treatment of Data

The data gathered from the evaluation respondents will be analyzed using the weighted mean formula to quantify the level of agreement or satisfaction expressed for each quality characteristic of the system. The weighted mean is computed by multiplying each response value by its corresponding frequency, summing the products, and dividing by the total number of responses. This formula allows the researchers to derive a single representative score per criterion that reflects the collective evaluation of all respondents.

The resulting weighted mean scores are then interpreted using the four-point Likert Scale presented in Table 6, which assigns a qualitative descriptor to each numerical range. A score falling between 3.26 and 4.00 is interpreted as "Strongly Agree" or "Excellent," indicating that the system fully satisfies the evaluated characteristic. A score between 2.51 and 3.25 is interpreted as "Agree" or "Very Good," reflecting general satisfaction with minor areas for improvement. A score between 1.76 and 2.50 is interpreted as "Disagree" or "Fair," indicating that the characteristic requires significant improvement. Finally, a score between 1.00 and 1.75 is interpreted as "Strongly Disagree" or "Poor," signifying that the evaluated characteristic does not meet the expected standard and requires major revision.

---

**Table 6. Likert's Scale**

| Scale | Range | Qualitative Description |
|---|---|---|
| 4 | 3.26 – 4.00 | Strongly Agree / Excellent |
| 3 | 2.51 – 3.25 | Agree / Very Good |
| 2 | 1.76 – 2.50 | Disagree / Fair |
| 1 | 1.00 – 1.75 | Strongly Disagree / Poor |

---

### Types of Respondents

The evaluation of the AI-Assisted OJT Monitoring System was conducted with three groups of respondents, each representing one of the three user roles supported by the system. These respondents were selected from within the institution to ensure that the evaluation reflects actual use cases and real-world interaction with the platform. Their feedback was gathered through a structured survey questionnaire aligned with the ISO 25010 quality characteristics defined in Table 5.

**Respondents 1 — System Administrator.** The first group of respondents consists of the designated system administrator of the institution. This respondent is responsible for managing user accounts, configuring system settings through the CMS panel, managing sections, and overseeing the overall operation of the platform. The administrator evaluates the system from a management and configuration perspective, assessing the completeness of administrative functions, the reliability of user and section management features, and the clarity of the system's configuration options. Their feedback is particularly relevant to the Functional Suitability and Reliability characteristics of the ISO 25010 framework.

**Respondents 2 — Faculty OJT Coordinators.** The second group of respondents is composed of faculty members who serve as OJT coordinators and are assigned to handle sections within the system. These respondents interact with the platform primarily through the faculty dashboard, where they review and evaluate student checklist submissions, monitor DTR progress, manage FAQs and announcements, and utilize the AI Decision Support module. Their evaluation focuses on the system's effectiveness in streamlining the monitoring and review workflow, the accuracy and usefulness of the AI-generated decision support output, and the overall usability of the faculty interface. Their responses inform the Usability, Performance Efficiency, and Compatibility characteristics of the evaluation framework.

**Respondents 3 — Student OJT Interns.** The third and primary group of respondents consists of fourth-year Bachelor of Science in Information Technology students who are currently enrolled in or have recently completed their OJT program. These respondents represent the most frequent users of the system, interacting with it through the student dashboard to submit checklist documents, encode DTR entries, submit weekly and monthly reports, file incident reports, and use the AI Chatbot for guidance. Their evaluation covers the full range of student-facing features and focuses on the system's ease of use, responsiveness, clarity of the submission workflow, and the helpfulness of the AI Chatbot in answering OJT-related questions. Given that this group represents the majority of the system's user base, their responses carry the highest weight in assessing the overall usability and user experience of the platform.
