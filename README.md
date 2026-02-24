
# CRM Ticket Module

A simple CRM (Customer Relationship Management) Ticket Module built using **PHP** and **MySQL**, with role-based access for **Admin**, **Author**, and **Assignee**.

---

## Tech Stack

The CRM project is built using the following technologies:

- **Frontend:** HTML, CSS
- **Backend:** PHP 
- **Database:** MySQL  
- **Server Environment:** WAMP Server (Windows, Apache, MySQL, PHP)  
- **Version Control:** Git (for managing project code)  
- **File Uploads:** Handled via PHP to `uploads/` directory  
- **Session Management:** PHP sessions for user authentication and role-based access 


## ⚙️ Features

- **Admin**
  - View, edit, and reassign tickets
  - Manage users
  - Dashboard with ticket statistics

- **Author**
  - Create and manage tickets
  - Assign tickets to assignees

- **Assignee**
  - View tickets assigned to them
  - Update ticket status

- **File Upload**
  - Optional file attachments for tickets

- **Role-based Access**
  - Admin, Author, and Assignee roles with separate dashboards

---


## 🛠 Installation Manual


1. **Install WAMP Server**  
Download and install WAMP Server
Clone the repository:
```bash
git clone <https://github.com/Gauri0710/CRM-TICKET-MODULE.git>
```
2. **Create Database**  
   - Open `phpMyAdmin`
   - Create a new database named `crm_db`
3. **Configure Database**  
   - Open `config/db.php`  
   - Update the database credentials as per your WAMP setup:
     
4. **Copy Project to WAMP www Directory**
   - Copy the crm_project folder to `C:\wamp64\www\`
   
6. **Run the Project**
   - Open your browser and navigate to:
   http://localhost/crm-ticket-system/index.php
   - Login with your credentials to start using the CRM.
     





# CRM-TICKET-MODULE
 818f67039d8a28a825571b42aa1e74efcafcde18
