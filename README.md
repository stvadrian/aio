## Project Overview: All in One (AIO)
<p align="center">
    <img src="./public/img/logo.png" width="200">
</p>

### Introduction
AIO is a web-based application designed to serve as a centralized platform for managing various aspects of a company's operations and communication. This comprehensive portal provides a range of features and tools to streamline administrative tasks, enhance communication, and facilitate collaboration within the organization.

### Installation and Technology Stack
- Framework: Laravel 10 (PHP 8.1 or Higher)
- Database: SQL Server & SQLite
- Deployment: Extract public folder to htdocs in XAMPP, open index.php and match the directory structure
- Dependencies: Directory public/plugins for details

### User Core Menu
##### Dashboard:
- A centralized overview of key performance indicators (KPIs), announcements, and important updates.
##### Menu Manager:
- Easy-to-use interface for creating and managing the navigation menu.
- Customizable menus for different user roles and departments.
##### User Manager:
- User authentication and access control, including roles and permissions. 
##### Department Manager:
- Create, edit, and manage company departments.
##### Icon Manager:
- Central repository for managing icons used throughout the portal.
##### Form Generator:
- Create dynamic forms for data collection and processing.
- Customize form fields and validation rules.
##### QR Code Generator:
- Generate QR codes for various purposes, such as asset tracking, event registration, or contactless check-in.
##### Forum:
- Collaborative platform for discussions, knowledge sharing, and problem-solving.
- Can be used for tracking project progression.
##### Profile Page:
- User profiles with personal information and settings.
##### Messaging:
- Private messaging for team collaboration.


### Codebase Structure
Project's directory structure is following the default of Laravel structure. Common directory for troubleshooting:
- app/Http/Controllers
- app/Http/Middleware
- app/Models
- app/Providers
- config
- database/migrations
- resources/views
- routes

All of the assets stored in storage/app directory, for example:
- User profile picture
- Generated QR Code
- Automated Form Assets