# Course Management System – - Web Site

A Course Management System that utilizes functional and user-friendly web development skills. This system enables students to enroll in courses, instructors to manage grades, and administrators to control system operations. It closely follows the original project proposal by implementing essential features such as user authentication, CRUD operations, and role management.

---

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
- [Features Implemented](#features-implemented)
- [Database Design](#database-design)
- [User Roles](#user-roles)
- [Authentication](#authentication)
- [Course Management](#course-management)
- [Grade Management](#grade-management)
- [Admin Controls](#admin-controls)
- [UI/UX & Security](#uiux--security)
- [Dependencies](#dependencies)
- [User Login Examples](#user-login-examples)
- [Features Pending](#features-pending)
- [Credits](#credits)
- [License](#license)

---

## Overview

The **Course Management System** is a comprehensive web-based platform designed for managing academic courses. It streamlines the entire lifecycle of a course—from creation and enrollment to assignment submissions, grading, and reporting. The system caters to three primary user roles:

- **Students:** Enroll in courses and view grades.
- **Instructors:** Manage courses, assignments, and grades.
- **Administrators:** Oversee system operations and user management.

---

## Installation

### Prerequisites

- **XAMPP:** Includes PHP and MySQL.
- **Development Tools:** Visual Studio Code (or your preferred code editor).
- **Browsers:** Chrome, Firefox, or any modern browser.

### Setup Steps

1. **Install XAMPP:**
   - Download and install XAMPP from the [official website](https://www.apachefriends.org/index.html).

2. **Extract the Project:**
   - Extract the provided zip file to the `htdocs` folder inside your XAMPP installation directory.
   - **Important:** Ensure the folder name remains **`final project`** (including the space) as many directories are hardcoded.

3. **Database Setup:**
   - Open phpMyAdmin by navigating to [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Click on the **Import** tab.
   - Upload the `cms_db.sql` file to create the database schema and import initial data.

4. **Start Services:**
   - Open the XAMPP Control Panel and start **Apache** and **MySQL**.

5. **Launch the Website:**
   - Open your browser and navigate to [http://localhost/final project](http://localhost/final%20project) (make sure to include the space).

---

## Features Implemented

### User Roles
- **Admin:** Full control over course creation, user management, and viewing reports.
- **Instructor:** Ability to manage assigned courses and grades.
- **Student:** Enroll in courses and view grades on a personalized dashboard.

### Authentication
- Secure login and registration.
- Utilizes PHP functions `password_hash` and `password_verify` for secure password handling.
- Includes Forgot Password and Reset Password functionalities.

### Course Management
- **Add Courses:** Implemented via `add_course.php`.
- **Edit/Delete Courses:** Managed through `edit_course.php` and `manage_courses.php`.
- **View/Enroll Courses:** Students can view available courses and enroll via `view_courses.php`.

### Grade Management
- **Instructor Tools:** Manage grades and calculate GPA using `manage_grades2.php` and `grades2.php`.
- **Student Dashboard:** Displays enrolled courses and corresponding grades.

### Admin Controls
- Comprehensive admin panel for managing users.
- Functionalities include adding, editing, and deleting users (`manage_users.php`, `delete_user.php`).

---

## Database Design

- **Relational Schema:** Properly structured tables for Users, Courses, Enrollments, and Grades.
- **Scalability:** Designed for scalability with role-based data access and integrity.

---

## User Roles

The system distinguishes between three primary roles:

- **Admin:** Has full privileges across the system.
- **Instructor:** Can manage courses and input grades.
- **Student:** Can enroll in courses and monitor academic performance.

---

## Authentication

- Implements secure user authentication using PHP’s built-in password hashing (`password_hash`) and verification (`password_verify`).
- Provides password recovery features, including Forgot Password and Reset Password options.

---

## Course Management

- **CRUD Operations:** Enables course creation, editing, deletion, and enrollment.
- **File Structure:** Ensure the project remains in the original folder name (`final project`) to maintain hardcoded paths.

---

## Grade Management

- **Instructor Interface:** Allows instructors to manage and assign grades.
- **Student Dashboard:** Displays grades and calculates GPA for enrolled students.

---

## Admin Controls

- **User Management:** Admins can add, edit, and delete users.
- **System Oversight:** Full access to monitor courses and generate reports.

---

## UI/UX & Security

- **Responsive Design:** Built using Bootstrap 5 for a consistent and responsive layout.
- **Reusable Templates:** Common components such as `header.php`, `footer.php`, and `navbar.php` ensure a unified interface.
- **Security Measures:** Role-based access control (RBAC) along with PHP and JavaScript input validation ensure secure access to system functionalities.

---

## Dependencies

- **XAMPP:** Version 0.8 (includes PHP and MySQL).
- **MySQL / phpMyAdmin:** Version 5.2.
- **Bootstrap:** Version 5.
- **JavaScript:** Used for client-side validation and interactive elements.

---

## User Login Examples

The database includes sample user accounts for testing:

- **Student Login:**
  - **Email:** bob@example.com
  - **Password:** bob123

- **Admin Login:**
  - **Email:** admin@example.com
  - **Password:** default123

- **Instructor Login:**
  - **Email:** john@example.com
  - **Password:** default123

---

## Features Pending

- **AJAX Integration:** Partial implementation for asynchronous operations; full integration is pending.
- **Advanced Analytics:** Chart-based statistics in the admin dashboard are placeholders for future development.

---

## Credits

**Design & Programming:** Mohammed Sadiq
---

## License

This project is licensed under the [MIT License](LICENSE).
