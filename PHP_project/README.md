# IT Training Session Booking System

This repository contains the source code for a web-based application developed as part of the COMP519 Web Programming module at the University of Liverpool. The project demonstrates proficiency in server-side web development using PHP and MySQL, focusing on robust data handling, user input validation, and database transaction management.

## Project Overview

The application provides a system for university students to book IT training sessions offered during Welcome Week. It streamlines the booking process by allowing students to select topics and times, and submit their registration details. The system ensures data integrity and manages session capacities in real-time.

## Key Features

* **Session Booking:** Students can select from available IT training topics and session times.
* **Real-time Capacity Management:** Tracks and updates the number of available places for each session in the database.
* **Robust Server-Side Validation:** Implements strict PHP-only validation for student names and email addresses, ensuring data quality and security.
* **Database Integration (PDO):** Utilizes PHP Data Objects (PDO) for secure and efficient interaction with a MySQL database.
* **Concurrency Handling:** Designed to prevent overbooking issues, even with near-simultaneous booking requests for the last available place.
* **Dynamic Menu Population:** Populates session selection menus with live data from the database, showing only available topics and times.
* **Comprehensive Booking Confirmation:** Provides immediate feedback on booking success/failure and displays a table of all successful bookings.
* **Security Measures:** Includes safeguards against common web vulnerabilities like code injection.

##  Technologies Used

* **Backend:** PHP (with PDO extension)
* **Database:** MySQL
* **Frontend:** HTML5 (minimal JavaScript usage, restricted to `submit()` function as per project requirements)
* **Deployment Environment:** Departmental Apache/MySQL server (University of Liverpool)

## Testing

The application's functionality was thoroughly tested against all specified requirements, including:
* Successful booking requests with valid data and available capacity.
* Unsuccessful booking requests due to invalid input or full sessions.
* Concurrency handling for last-place bookings.
* Database integrity and real-time capacity updates.

## Code Standards & Documentation

The codebase adheres to the COMP519 Coding Standard. All external resources, language constructs, and functions not covered in lectures are properly cited and referenced within the code comments and a dedicated `references.txt` (or similar) file, ensuring academic integrity and maintainability.
