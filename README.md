🎓 Student Doubt Management System

A web-based Student Doubt Management System developed using PHP, MySQL, HTML, and CSS.
This system allows students to submit academic doubts and faculty members to answer them efficiently.

📌 Project Description

The Student Doubt Management System is designed to simplify communication between students and faculty.

Students can:

Register/Login

Submit doubts

View their submitted doubts

View answers provided by faculty

Faculty can:

Login securely

View pending doubts

Answer student doubts

Update doubt status

The system ensures proper session handling and role-based access control.

🎯 Objectives

To create a centralized platform for student doubt submission

To allow faculty to respond efficiently to student queries

To maintain structured records of questions and answers

To implement role-based login (Student & Faculty)

To provide a clean and user-friendly interface

🚀 Features
👨‍🎓 Student Panel

Secure login

Ask a doubt (Subject + Question)

View all submitted doubts

View answer status

Logout functionality

👩‍🏫 Faculty Panel

Secure login

View pending doubts

Answer doubts

Change status to "Answered"

Logout functionality

🔐 Security Features

Session management

Role-based authentication

SQL query validation

Basic input sanitization

🛠️ Technologies Used

PHP

MySQL

HTML5

CSS3

XAMPP (Apache + MySQL)

🗂️ Project Structure
student-doubt-system/
│
├── css/
│   └── style.css
│
├── student/
│   ├── dashboard.php
│   ├── ask_doubt.php
│   ├── my_doubts.php
│
├── faculty/
│   ├── dashboard.php
│   ├── answer.php
│
├── config.php
├── login.php
├── logout.php
├── index.php
├── database.sql
