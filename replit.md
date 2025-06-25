# Project Documentation

## Overview

Bloggua is a complete PHP blog system with MySQL database, featuring a red and white themed design. The project includes a full-featured admin panel with CRUD operations for posts, categories, and comments management. Built with security best practices including prepared statements, password hashing, and input validation.

## System Architecture

### Current Architecture
- **Frontend**: Responsive blog interface with red/white theme
- **Backend**: PHP 8.2 with MVC-style architecture
- **Database**: MySQL with structured schema for posts, categories, comments, and admin users
- **Web Server**: PHP built-in development server on port 5000
- **Admin Panel**: Complete dashboard with authentication and content management

### Technology Stack
- PHP 8.2 (main backend language)
- MySQL (database with PDO connections)
- HTML5/CSS3/JavaScript (frontend)
- Security: PDO prepared statements, password hashing, session management

## Key Components

### Web Server Configuration
- **Server**: PHP built-in server (`php -S 0.0.0.0:5000`)
- **Port**: Internal/external port 5000
- **Workflow**: Automated PHP server startup

### File Structure
- `index.php`: Main blog homepage with post listing and search
- `post.php`: Single post page with comments system
- `admin/`: Complete admin panel with dashboard, CRUD operations
- `models/`: Database models (Post, Category, Comment)
- `config/`: Database and session configuration
- `assets/`: CSS/JS files for frontend and admin styling
- `uploads/`: Image upload directory with security restrictions

## Data Flow

Complete blog system with database integration:
1. HTTP requests arrive at port 5000
2. PHP server processes requests with routing based on file structure
3. Database queries via PDO with prepared statements
4. Admin authentication via sessions with 30-minute timeout
5. File uploads processed with security validation
6. Content rendered with XSS protection and input sanitization

## External Dependencies

### Runtime Dependencies
- PHP 8.2 with PDO MySQL extension
- MySQL database server
- GD or Imagick extension for image processing

### Infrastructure Dependencies
- PHP built-in development server
- MySQL database with utf8mb4 charset
- File system permissions for uploads directory

## Deployment Strategy

### Current Deployment
- **Platform**: Designed for local XAMPP/LAMP environments
- **Server**: PHP built-in development server for testing
- **Database**: MySQL with complete schema and sample data
- **Security**: Production-ready security measures implemented

### Scalability Considerations
- Uses PHP development server (suitable for local development)
- Complete database integration with optimized queries
- Session-based authentication with timeout management
- Comprehensive error handling and input validation

## Changelog

```
Changelog:
- June 25, 2025: Complete Bloggua blog system implemented
  - Full PHP blog with red/white theme
  - MySQL database with posts, categories, comments
  - Admin panel with CRUD operations
  - Security features: prepared statements, password hashing
  - Image upload system with validation
  - Responsive design for mobile/desktop
  - Search functionality and pagination
  - Comment moderation system
```

## User Preferences

```
Preferred communication style: Simple, everyday language.
Project requirements: PHP and MySQL only (for local deployment, not Replit)
```

## Development Notes

### Completed Features
1. ✅ Complete PHP blog system with MVC architecture
2. ✅ MySQL database with full schema and relationships
3. ✅ Admin authentication and session management
4. ✅ CRUD operations for posts, categories, comments
5. ✅ Image upload system with security validation
6. ✅ Responsive red/white themed design
7. ✅ Search functionality and pagination
8. ✅ Comment moderation system
9. ✅ Security measures (prepared statements, password hashing)

### Architecture Decisions
- **PHP Server**: PHP built-in server for development, designed for local LAMP deployment
- **MySQL Database**: Structured relational database with proper foreign keys
- **MVC Pattern**: Models for data access, separation of concerns
- **Security First**: All inputs sanitized, prepared statements used throughout
- **Local Deployment**: Optimized for user's local computer environment