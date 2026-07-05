# XAMPP Setup Guide

## Prerequisites

- [XAMPP](https://www.apachefriends.org/) installed

## Step 1 — Copy the Project

```
C:\xampp\htdocs\kwarapoly-php\
```

## Step 2 — Import the Database

- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Click **Import** → choose `database.sql` from the project folder
- Click **Go**

This creates the `kwarapoly_fees` database with all tables.

## Step 3 — Configure Database (if needed)

Open `config.php` — defaults work for XAMPP:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'kwarapoly_fees');
define('DB_USER', 'root');
define('DB_PASS', '');
```

Only change these if your MySQL has different credentials.

## Step 4 — Run It

```
http://localhost/kwarapoly-php/
```

Default login: `admin` / `admin123`

## Troubleshooting

- **MySQL not running**: Start MySQL in XAMPP Control Panel
- **Port conflict**: If Apache port 80 is in use, change to 8080 in XAMPP and visit `http://localhost:8080/kwarapoly-php/`
- **404 / blank page**: Make sure the folder is directly under `htdocs`
- **PDO errors**: Ensure `php_pdo_mysql` extension is enabled in `php.ini`
