# XAMPP Setup Guide for kwarapoly-php

## Prerequisites

- [XAMPP](https://www.apachefriends.org/) installed on Windows

## Step 1 — Copy the Project to XAMPP

Move or copy the `kwarapoly-php` folder into:

```
C:\xampp\htdocs\
```

The final path should be:

```
C:\xampp\htdocs\kwarapoly-php\
```

## Step 2 — Import the Database

- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Click **Databases** → create a new database (e.g., `kwarapoly`)
- Click **Import** → choose the `database.sql` file from the project folder
- Click **Go**

## Step 3 — Configure the Database Connection

Open `config.php` and update the database values to match your XAMPP setup:

```php
$host     = '127.0.0.1';
$port     = '3306';
$database = 'kwarapoly';
$username = 'root';
$password = '';  // Default XAMPP has no password
```

## Step 4 — Run It

Open your browser and go to:

```
http://localhost/kwarapoly-php/
```

## Troubleshooting

- **Error connecting to database**: Make sure MySQL is running in the XAMPP Control Panel.
- **Blank page**: Check `php.ini` to ensure `display_errors = On` for debugging.
- **Port conflict**: If port 80 is in use, change Apache to use port 8080 and visit `http://localhost:8080/kwarapoly-php/`.