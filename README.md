# Lanka Recipe Book
ICT 1209 Web Technologies mini project: a Sri Lankan recipe web app built with HTML, CSS, Bootstrap 5, JavaScript, PHP and MySQL.

## Features
- Pages: Home, Recipes, Contact, Dashboard, Register, Login
- JavaScript: live search and filter, image slider, form validation, smooth scrolling, modal/tooltips, fade-in animation
- PHP/MySQL: registration (`password_hash`), login with sessions, logout, add/delete own recipes, contact form saved to `messages`
- Prepared statements for all queries and `htmlspecialchars` for output

## Setup (XAMPP / WAMP)
1. Install and start **Apache** and **MySQL** in XAMPP.
2. Copy this folder into `C:\xampp\htdocs\` (for example `C:\xampp\htdocs\lanka_recipe_book`).
3. Open `http://localhost/phpmyadmin` > **Import** > choose `database.sql` > **Go**. This creates the `recipe_book` database with tables and sample recipes.
4. If your MySQL root user has a password, edit `includes/db.php`.
5. Open `http://localhost/lanka_recipe_book/`.

## Folder structure
```
css/ js/ images/
includes/  db.php, functions.php, header.php, footer.php
auth/      register.php, login.php, logout.php
index.php  recipes.php  dashboard.php  contact.php
database.sql
```

## Push to GitHub
```
git init
git add .
git commit -m "Initial commit: Lanka Recipe Book"
git branch -M main
git remote add origin https://github.com/YOUR-USERNAME/lanka-recipe-book.git
git push -u origin main
```
