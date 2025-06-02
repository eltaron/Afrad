# Hospital Personnel Management System

## Overview

The Hospital Personnel Management System is a comprehensive web application designed to manage personnel data, departmental assignments, violations, leaves, and reporting for a hospital environment. It supports distinct user roles for administrative staff, military affairs officers, and civilian affairs officers, ensuring data segregation and appropriate access levels. The application features a primarily Arabic interface with a dark blue theme and includes a RESTful API for potential external integrations.

## Features

*   **Personnel Management:**
    *   Detailed registration for military and civilian personnel.
    *   Tracking of military ID, national ID, contact information, recruitment/termination dates.
    *   Assignment of job titles (for civilians) and ranks (for military).
*   **Department Management:**
    *   Creation and management of hospital departments.
    *   History of personnel assignments to various departments with start and end dates.
*   **Violation Tracking:**
    *   Definition of violation types with descriptions.
    *   Recording of personnel violations, including date, penalty type (confinement, detention, salary deduction), penalty duration, and notes.
*   **Leave Management System:**
    *   Customizable leave types with default durations and applicability rules (e.g., for all, military only, civilian only, specific ranks/titles).
    *   Special handling for "permissions" (اذن) as a type of leave.
    *   Workflow for leave requests: requested, approved, rejected, taken.
    *   Recording of approver for each leave.
*   **User Roles & Authorization:**
    *   **Admin:** Full system control, manages core data (Hospital Forces, Departments, Violation Types, Leave Types), all personnel records, and can oversee all system operations.
    *   **Military Affairs Officer:** Manages military personnel (e.g., 'جنود', 'صف ضباط'), their leaves, violations, and related reports.
    *   **Civilian Affairs Officer:** Manages civilian personnel (e.g., 'مدنين'), their leaves, violations, and related reports.
*   **Reporting System:**
    *   Daily report of personnel eligible for leave.
    *   Generation of printable leave permits for approved leaves.
    *   Periodical reports for leaves and violations, filterable by date range and other criteria.
*   **User Interface:**
    *   Primarily Arabic language interface.
    *   Custom dark blue theme for a modern look and feel.
    *   Responsive design elements.
*   **API:**
    *   RESTful API available under `/api/v1/` for programmatic access.
    *   Authentication via Laravel Sanctum.
    *   Standardized JSON responses using API Resources.

## Setup Instructions

1.  **Clone the repository:**
    ```bash
    git clone <repository_url> hospital_management
    cd hospital_management
    ```
2.  **Create Environment File:**
    ```bash
    cp .env.example .env
    ```
3.  **Update Environment Variables:**
    Open the `.env` file and set your database credentials:
    ```env
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=hospital_db # Or your preferred database name
    DB_USERNAME=user # Your database username
    DB_PASSWORD=password # Your database password

    APP_URL=http://localhost # Or your application's URL
    ```
4.  **Install Dependencies:**
    ```bash
    composer install
    ```
5.  **Generate Application Key:**
    ```bash
    php artisan key:generate
    ```
6.  **Run Database Migrations and Seeders:**
    (Ensure your database server is running and accessible with the credentials provided in `.env`)
    ```bash
    php artisan migrate --seed
    ```
    This will create all necessary tables and seed the initial admin user.

7.  **Install Frontend Dependencies & Build Assets:**
    ```bash
    npm install && npm run build
    ```
    (Note: `npm run dev` can be used for development to automatically rebuild assets on change).

8.  **Default Admin Login:**
    *   **Email:** `admin@example.com`
    *   **Password:** `password`
    (These are set in the `AdminUserSeeder`).

9.  **Serve the Application:**
    ```bash
    php artisan serve
    ```
    Or configure a web server like Nginx or Apache to point to the `public` directory.

## User Roles

*   **Admin:** Has full access to all system features and data. Manages foundational data such as Hospital Forces, Departments, Leave Types, and Violation Types. Can manage all personnel records and view all reports.
*   **Military Affairs Officer:** Responsible for managing personnel belonging to military hospital forces (e.g., 'جنود', 'صف ضباط'). Can view, create, edit military personnel, manage their leave requests, and view reports relevant to them.
*   **Civilian Affairs Officer:** Responsible for managing personnel belonging to civilian hospital forces (e.g., 'مدنين'). Can view, create, edit civilian personnel, manage their leave requests, and view reports relevant to them.

## API Overview

A RESTful API is available for programmatic interaction with the system.

*   **Base URL:** `/api/v1/`
*   **Authentication:** Laravel Sanctum (token-based for external clients, SPA cookie-based for same-domain frontends). Ensure you send an `Authorization: Bearer <token>` header for token-based auth or use Sanctum's SPA mechanisms.
*   **Content Type:** `application/json` for requests and responses.

### Key Endpoints (Examples):

*   `GET /api/v1/user`: Retrieves the authenticated user's details.
*   `GET /api/v1/personnel`: Retrieves a paginated list of personnel (scoped by role if not admin).
*   `GET /api/v1/personnel/{personnel}`: Retrieves details for a specific personnel.
*   `GET /api/v1/personnel-leaves`: Retrieves a list of leave requests (scoped by role).
*   `POST /api/v1/personnel-leaves`: Submits a new leave request.
*   `POST /api/v1/personnel-leaves/{personnel_leave}/approve`: Approves a leave request.
*   `POST /api/v1/personnel-leaves/{personnel_leave}/reject`: Rejects a leave request.
*   `GET /api/v1/reports/daily-eligible-for-leave`: Gets a report of personnel eligible for leave.
*   `GET /api/v1/reports/leave-permit/{personnel_leave}`: Gets data for a specific leave permit.
*   `GET /api/v1/reports/period-leave-report?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD`: Gets a report of leaves within a period.
*   `GET /api/v1/reports/period-violation-report?start_date=YYYY-MM-DD&end_date=YYYY-MM-DD`: Gets a report of violations within a period.

Responses are structured using Laravel API Resources for consistency. Refer to controller methods and resource classes for detailed request/response formats.

## Theme & Font

The application uses a custom dark blue theme implemented with Tailwind CSS. The primary font used throughout the interface is "Cairo" for optimal Arabic readability.

## Localization

The primary language for the user interface is Arabic (`ar`). Core Laravel translation files and application-specific translations are located in `lang/ar/`.

---

This README provides a guide to understanding, setting up, and using the Hospital Personnel Management System. For more detailed information on specific Laravel features, please refer to the official [Laravel documentation](https://laravel.com/docs).
