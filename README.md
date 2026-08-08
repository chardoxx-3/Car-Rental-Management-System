# 🚗 Car Rental Management System

A web-based **Car Rental Management System** designed to simplify vehicle rental operations by allowing customers to browse available cars, make reservations, and manage their rentals, while administrators can manage vehicles, customers, reservations, and payments.

## 🚀 Project Overview

The application is built with **PHP**, **CodeIgniter 4**, and **MySQL**, following the MVC architecture. It provides separate experiences for **Customers** and **Administrators**, with each role having access to features relevant to their responsibilities.

## 👥 User Roles

### 1. Customer

Customers can:

* Browse available vehicles.
* View detailed vehicle information.
* Check vehicle availability.
* Create rental reservations.
* Calculate rental costs based on rental duration.
* Make payments.
* View and manage their reservations.
* Manage their account profile.

### 2. Administrator

Administrators can:

* View an overview through the dashboard.
* Add, edit, and manage vehicles.
* Manage customer accounts.
* Monitor and update reservations.
* Manage payment records.
* View rental and revenue reports.

## 🚘 Key Features

| **Feature**               | **Description**                                                         |
| ------------------------- | ----------------------------------------------------------------------- |
| **Vehicle Management**    | Add, edit, delete, and manage vehicle information and availability.     |
| **Customer Management**   | Manage registered customer accounts and information.                    |
| **Reservations**          | Create and manage vehicle rental reservations.                          |
| **Availability Checking** | Prevent overlapping reservations for the same vehicle.                  |
| **Payments**              | Record and manage rental payment transactions.                          |
| **Dashboard**             | Provides an overview of vehicles, customers, reservations, and revenue. |
| **Reports**               | View rental activity, revenue, and customer statistics.                 |
| **Authentication**        | Secure login and registration with role-based access.                   |

## 🏗️ System Architecture

The project follows the **Model-View-Controller (MVC)** architecture provided by CodeIgniter 4.

* **Controllers** – Handle application logic and user requests.
* **Models** – Manage database operations and business data.
* **Views** – Provide the user interface for customers and administrators.
* **Routes** – Define how users access different system features.

## 🗄️ Database

The system uses **MySQL/MariaDB** to manage its core data, including:

* Users
* Cars
* Reservations
* Payments

## 🔐 Demo Credentials

Use the following account to access the administrator features:

| **Account**  | **Credentials** |
| ------------ | --------------- |
| **Username** | `admin`         |
| **Password** | `password`      |
| **Role**     | Administrator   |

> **Note:** These credentials are intended for local/demo use only.

## 🛠️ Technologies Used

* **PHP**
* **CodeIgniter 4**
* **MySQL / MariaDB**
* **HTML**
* **CSS**
* **JavaScript**
* **Composer**

## 💻 How to Install & Run

### 1. Install the Requirements

Before running the project, install:

* **PHP 8.1 or higher**
* **Composer**
* **MySQL / MariaDB**
* **XAMPP** or another local PHP development environment

### 2. Download the Project

Clone the repository:

```bash
git clone https://github.com/chardoxx-3/Car-Rental-Management-System.git
```

Then enter the project directory:

```bash
cd Car-Rental-Management-System
```

You can also download the repository as a **ZIP** from GitHub and extract it to your local development folder.

### 3. Install CodeIgniter Dependencies

Inside the project folder, run:

```bash
composer install
```

This installs the PHP dependencies required by the CodeIgniter 4 application.

### 4. Configure the Environment

Copy the example environment file:

```bash
copy env .env
```

Then open `.env` and configure your database connection.

Example:

```env
database.default.hostname = localhost
database.default.database = car_rental
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Adjust the database name, username, and password according to your local MySQL configuration.

### 5. Create the Database

Open **phpMyAdmin** or MySQL and create a database for the project.

For example:

```text
car_rental
```

Import the SQL database file included in the project:

```text
car_rental.sql
```

This will create the required tables and sample data.

### 6. Start the CodeIgniter Development Server

From the project directory, run:

```bash
php spark serve
```

The application will normally be available at:

```text
http://localhost:8080
```

Open the address in your browser.

### 7. Login

Use the demo administrator account:

```text
Username: admin
Password: password
```

## 🔄 Rental Workflow

**Browse Cars → Check Availability → Select Rental Dates → Create Reservation → Make Payment → Confirmed Reservation**

## 🎯 Project Purpose

This project was developed to demonstrate practical skills in **web development, database management, MVC architecture, CRUD operations, authentication, reservation systems, and payment management**.
