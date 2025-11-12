# School Management System

A clean, minimal, and elegant web application built with PHP, MySQL, HTML, CSS, and JavaScript. Features an Apple-inspired design with smooth animations and intuitive user experience.

## Features

### Authentication System
- **User Registration** with role selection (Teacher/Student)
- **Secure Login** with session management
- **Password hashing** using PHP's `password_hash()`
- **Session-based authentication** with automatic redirects

### Role-Based Access Control
- **Teachers**: Full CRUD operations on student data
  - Create new student records
  - Read/view all student information
  - Update existing student records
  - Delete student records

- **Students**: View-only access to their own data
  - Personal profile viewing
  - No edit or delete permissions

### User Interface
- **Apple-inspired design** with clean aesthetics
- **Responsive layout** for desktop and mobile devices
- **Smooth animations** and transitions
- **Interactive elements** with hover effects
- **Form validation** with real-time feedback
- **Auto-dismissible alerts** with close buttons

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (ES6+)
- **Typography**: Inter font family
- **Design**: Custom CSS with CSS variables

## File Structure

```
kelompokdb/
├── config.php          # Database configuration and connection
├── register.php        # User registration page
├── login.php           # User login page
├── dashboard.php       # Main dashboard (role-based)
├── index.php           # Redirect ke login.php
├── crud_siswa.php      # Student CRUD management (teachers only)
├── logout.php          # Session destruction and logout
├── style.css           # Apple-inspired styles
├── script.js           # Interactive JavaScript features
├── schema.sql          # Database schema and initialization
└── README.md           # Documentation
```

## Installation

### 1. Database Setup

Import the database schema:

```bash
mysql -u root -p < schema.sql
```

Or manually execute the SQL commands in `schema.sql` through phpMyAdmin or MySQL Workbench.

### 2. Configure Database Connection

Edit `config.php` and update the database credentials:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'school_system');
```

### 3. Deploy to Web Server

Copy all files to your web server directory:

- **XAMPP**: `C:\xampp\htdocs\kelompok12\`
- **WAMP**: `C:\wamp64\www\kelompok12\`
- **MAMP**: `/Applications/MAMP/htdocs/kelompok12/`
- **Linux**: `/var/www/html/kelompok12/`

### 4. Access the Application

Open your browser and navigate to:

```
http://localhost/kelompok12
```

## Usage

### Registration

1. Navigate to `register.php`
2. Fill in the registration form
3. Select your role (Teacher or Student)
4. Submit the form
5. You'll be redirected to login

### Login

1. Navigate to `login.php`
2. Enter your username/email and password
3. Click "Sign In"
4. You'll be redirected to the dashboard

### Teacher Dashboard

- View statistics of all students
- Access the full student list
- Create new student records
- Edit existing student information
- Delete student records
- View detailed student profiles

### Student Dashboard

- View personal information
- See enrollment details
- Access read-only profile data

## Database Schema

### Users Table
- `id`: Primary key
- `username`: Unique username
- `email`: Unique email address
- `password`: Hashed password
- `role`: User role (teacher/student)
- `created_at`: Account creation timestamp

### Students Table
- `id`: Primary key
- `user_id`: Foreign key to users table (nullable)
- `name`: Student full name
- `student_id`: Unique student identifier
- `email`: Student email
- `grade`: Current grade/class
- `phone`: Contact number
- `address`: Physical address
- `created_at`: Record creation timestamp
- `updated_at`: Last update timestamp

## Security Features

- Password hashing with `password_hash()`
- SQL injection prevention with prepared statements
- Session-based authentication
- Role-based access control
- Input validation and sanitization
- XSS prevention with `htmlspecialchars()`

## Design Principles

### Color Palette
- **Primary**: #007AFF (Apple Blue)
- **Background**: #F9FAFB (Light Gray)
- **Text**: #1C1C1E (Dark Gray)
- **Success**: #34C759 (Green)
- **Danger**: #FF3B30 (Red)

### Typography
- **Font**: Inter (fallback to system fonts)
- **Weights**: 300, 400, 500, 600, 700
- **Line Height**: 1.5 for body, 1.2 for headings

### Spacing
- **System**: 8px base unit
- **Scales**: xs(4px), sm(8px), md(16px), lg(24px), xl(32px), 2xl(48px)

### Border Radius
- **Small**: 8px
- **Medium**: 12px
- **Large**: 16px
- **Extra Large**: 20px, 24px

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

## Future Enhancements

- Email verification for registration
- Password reset functionality
- Profile picture uploads
- Advanced search and filtering
- Export student data (CSV/PDF)
- Student attendance tracking
- Grade management system
- Parent/guardian accounts
- Multi-language support

## License

This project is open source and available for educational purposes.

## Support

For issues or questions, please refer to the code comments or create an issue in the repository.
