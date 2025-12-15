# Wellness Tracker

A web application for tracking mood and stress levels to promote mental well-being.

## Description

Wellness Tracker is a private and supportive tool that allows users to monitor their mood and stress levels over time. Users can register for an account, log in, and record their daily feelings. The application provides a visual representation of the data in a chart, helping users to identify patterns and trends in their well-being.

## Features

- **User Authentication:** Secure user registration and login system.
- **Mood and Stress Tracking:** Users can log their daily mood and stress scores (on a scale of 0-10) and add optional notes.
- **Data Visualization:** A line chart displays the user's mood and stress trends over the last 7 days.
- **Data Persistence:** All user data is securely stored in a database.
- **Coping Resources:** The application includes a page with quick access to coping mechanisms and crisis contact information.

## Technology Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Database:** MySQL / MariaDB
- **Charting Library:** Chart.js

## How to Run

1.  **Database Setup:**
    *   Create a new database named `wellness_tracker_db`.
    *   Import the `database/create_table.sql` file to create the necessary tables and populate the `coping_resources` table.

2.  **Database Connection:**
    *   Update the database connection credentials in `php/connect_db.php` to match your local environment.

3.  **Web Server:**
    *   Deploy the project files to a web server that supports PHP (e.g., Apache, Nginx).
    *   Access the project through the web server's URL.

## Database Schema

The database consists of three tables:

-   `users`: Stores user information, including username, email, and hashed password.
-   `mood_entries`: Stores the daily mood and stress entries for each user.
-   `coping_resources`: Stores information about coping mechanisms and crisis contacts.
