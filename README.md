# Student Accommodation Website

A responsive PG/student accommodation platform built from the supplied project specification.

## Stack
- HTML5, CSS3, Bootstrap 5
- JavaScript + Fetch API (AJAX)
- PHP 8+
- MySQL 8+
- React 18 (CDN component for the shortlist/dashboard feature)
- XAMPP/WAMP/LAMP compatible

## Features
- Responsive property listing
- City, budget and gender filters without page refresh
- Property detail pages with gallery, rating and amenities
- Signup/login/logout
- Password hashing with PHP `password_hash`
- AJAX shortlist/interest toggle
- React shortlist widget on the home page
- Normalized MySQL schema
- Sample PG/property data
- Basic validation and prepared SQL statements

## Folder structure
```
student_accommodation/
├── api/
│   ├── properties.php
│   └── interest.php
├── assets/
│   ├── css/style.css
│   └── js/app.js
├── config/
│   └── database.php
├── includes/
│   ├── header.php
│   └── footer.php
├── auth/
│   ├── login.php
│   ├── signup.php
│   └── logout.php
├── database/
│   └── student_accommodation.sql
├── index.php
├── property.php
├── shortlist.php
└── README.md
```

## XAMPP setup
1. Install/start Apache and MySQL.
2. Copy the `student_accommodation` folder into `C:/xampp/htdocs/`.
3. Open phpMyAdmin.
4. Create/import `database/student_accommodation.sql`.
5. Check `config/database.php`:
   - host: localhost
   - database: student_accommodation
   - user: root
   - password: empty by default in XAMPP
6. Visit:
   `http://localhost/student_accommodation/`

## Demo account
The SQL includes a demo user:
- Email: demo@example.com
- Password: password

For security, change/remove demo credentials before production deployment.

## Hosting
For a PHP/MySQL host:
1. Create a MySQL database and user.
2. Import the SQL file.
3. Upload the project files.
4. Update `config/database.php`.
5. Enable PHP 8+ and HTTPS.
6. Update the base URLs if the project is installed in a subdirectory.

## Notes
The React requirement is implemented as a React 18 component loaded from CDN. The rest of the application remains PHP-rendered and uses AJAX for dynamic operations, making it straightforward to run on ordinary PHP hosting without Node.js.
