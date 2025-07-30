# 🧑‍💼 Online Job Apply Portal (Indeed Clone)

This is a full-featured Online Job Application Portal inspired by platforms like **Indeed**. It includes role-based access with powerful features for Job Seekers, Recruiters, Recruiter Assistants, and Admins.

---

## 🚀 Features by User Role

### 👨‍💻 Job Seeker

- Register, login, manage profile, and reset password  
  (If mail server is disabled, default OTP is `123456`)
- Search for jobs with filters
- Apply to jobs, save jobs locally
- View job application status, receive notifications (on-site + email)
- Messaging with recruiters after applying
- View, like, dislike, and comment on blogs
- Interview management (reschedule & cancel)

---

### 🧑‍💼 Recruiter

- **Recruiter Dashboard**
- **Post Jobs** with optional pre-application questions:
  - Short Text, MCQ (Radio), Checkbox, Dropdown
- Manage all jobs:
  - View, Edit, Delete, Activate/Deactivate
- View applications with resume preview
- Filter & Export Applications (by date, status)
- Update application status, send messages, schedule interviews (live resume view)
- Interview reschedule and revoke options
- **Team Management**:
  - Add/Edit/Delete sub-users (Recruiter Assistants) with granular permissions
- **Blog Management**:
  - Create/Edit/Delete blogs using TinyMCE editor
- Manage recruiter profile, change password, manage devices & notification settings

---

### 🧑‍💼 Recruiter Assistant

- Works under Recruiter with specific permissions
- Can assist in job and applicant management based on allowed access

---

### 🛠️ Admin Panel

- Manage profile and system-wide settings
- **Job Management**:
  - Approve/Reject jobs, Edit/Delete jobs
- **Job Application Monitoring**:
  - Search by candidate or job
  - View who posted and who applied
- **User Reports**:
  - View reports with reason, reporter, and target
  - Ban users as needed
- **Static Pages**:
  - Create/Edit/Delete static content using TinyMCE
- **Blog Management**:
  - View/Edit/Delete all blogs
- **IP Ban Management**:
  - Block specific IPs with reason
- **Company Directory**:
  - View all registered company profiles
- **User Management**:
  - Manage all users (Job Seeker, Recruiter, Assistant, Admin)
  - Edit, Delete, Activate/Deactivate
- **Analytics Dashboard**:
  - Graphs for Users, Jobs, Applications, Traffic & System Health
  - Export all data to CSV

---

## ⚙️ System Settings

### 🔧 General Settings
- Site name, Contact email
- Enable/Disable Maintenance mode

### 🎨 Branding
- Upload/Change site Logo and Favicon

### 🛠️ Application Configuration
- Set App URL, Enable/Disable Debug & Mail

### 📧 Mail Server Setup
- Configure mail driver, host, port, credentials

### 💾 Database Settings
- Database host, name, username, password

### 🧪 Advanced Actions
- Run migrations
- Seed database with sample data

---

## 📁 Technologies Used

- Laravel 12 (Backend)
- Blade Templates (Frontend)
- MySQL (Database)
- Laravel Echo + Pusher (Real-time Messaging)
- TinyMCE (WYSIWYG Blog/Static Page Editor)

---

## 📦 Installation
add pusher credentials in .env for setup
```bash
git clone https://github.com/your-username/job-portal.git
cd job-portal
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link

## Credentials 
For Testing Purpose Only
This Site is now undermaintain
live Site Url
https://test-project-cm.wuaze.com/

admin Url 
https://test-project-cm.wuaze.com/admin/login

Admin 
admin@admin.com
Admin@123

Recruiter ( Company )
chandanmondal0021@gmail.com
Recruiter@123

User (Job Seeker )
chandanmondal521@gmail.com
User@123
