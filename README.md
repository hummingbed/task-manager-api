# Laravel Task Manager API

A lightweight **Laravel 12 REST API** that powers a simple **task management system**.  
Users can register, log in, and manage their personal tasks — including creating, updating, viewing, and deleting them — all secured with **Laravel Sanctum** authentication.

---

## API Documentation

You can explore and test the API endpoints using the included **Postman collection** (or create one easily using the routes below).

**Base URL:**  
[View on Postman →](https://mike44-5682.postman.co/workspace/mike-Workspace~9c4d1fca-86da-45bf-8207-39b36aafa8d3/collection/14032725-351ac689-be8f-4bac-bdfb-83dce959cc95?action=share&source=copy-link&creator=14032725)

---

## Tech Stack

| Component | Description |
|------------|-------------|
| **Language** | PHP 8.2+ |
| **Framework** | Laravel 12 |
| **Authentication** | Laravel Sanctum |
| **Database** | MySQL |
| **Testing** | PHPUnit (with `RefreshDatabase`) |
| **Package Manager** | Composer |

---

## Folder Structure Overview

| Path | Description |
|------|--------------|
| `app/Http/Controllers/AuthController.php` | Handles user registration and login |
| `app/Http/Controllers/TaskController.php` | Handles CRUD operations for user tasks |
| `app/Services/AuthService.php` | Business logic for authentication |
| `app/Services/TaskService.php` | Business logic for managing tasks |
| `app/Traits/HttpResponses.php` | Unified JSON response helper |
| `routes/api.php` | Defines API endpoints |
| `tests/Feature/TaskControllerTest.php` | Feature tests for API endpoints |
| `tests/Unit/TaskServiceTest.php` | Unit tests for TaskService logic |

---

## Features

1. **User Authentication** using Laravel Sanctum  
2. **CRUD Operations** for managing personal tasks  
3. **Pagination** for listing user tasks  
4. **Filter by Status** (`pending`, `in-progress`, `completed`)  
5. **Validation** and structured **error handling**  
6. **Full Test Coverage** for core functionalities  

---

## Installation & Setup

### Clone the Repository

1. git clone https://github.com/hummingbed/task-manager-apilaravel-task-manager-api.git
2. cd laravel-task-manager-api
3. run **composer install**
4. run **php artisan migrate**
5. run **php artisan serve**
