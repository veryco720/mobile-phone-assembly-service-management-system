# 📱 Mobile Phone Assembly Service Management System

A web-based information system designed to manage and monitor the mobile phone assembly process within a manufacturing environment. The system supports component inventory, warehouse management, production activities, quality control, supplier management, and user administration through role-based access control.

Built using the **CodeIgniter 3** framework with the **Model-View-Controller (MVC)** architecture, the application provides a structured, maintainable, and scalable solution for managing manufacturing operations.

---

# 🚀 Features

- Secure Authentication & Login
- User Management
- Role-Based Access Control (RBAC)
- Product Management
- Component Management
- Supplier Management
- Warehouse Inventory Management
- Employee Management
- Production Management
- Production Detail Management
- Quality Control Management
- Dashboard Monitoring
- CRUD Operations
- AJAX-Based Data Processing
- Responsive User Interface

---

# 👥 User Roles

The application provides four user roles with different access privileges.

| Role | Description |
|------|-------------|
| **Admin** | Manages all master data, users, production, warehouse, suppliers, and quality control. |
| **Operator** | Handles production processes and component usage during assembly. |
| **QC (Quality Control)** | Inspects finished products and records quality control results. |
| **Manager** | Monitors production activities, warehouse inventory, reports, and quality control results. |

---

# 🛠️ Technology Stack

| Technology | Description |
|------------|-------------|
| **Framework** | CodeIgniter 3 |
| **Backend** | PHP |
| **Database** | MySQL |
| **Frontend** | Bootstrap |
| **JavaScript Library** | KnockoutJS |
| **AJAX Library** | jQuery AJAX |
| **Programming Language** | JavaScript |

---

# 📂 Project Structure

```
application/
    controllers/
    models/
    views/
    libraries/
    helpers/

assets/
    css/
    js/
    images/
    plugins/

system/

database/
```

---

# 🗄️ Database

Database Name

```
pabrik_hp
```

## Main Tables

| Table | Description |
|--------|-------------|
| users | User account information |
| karyawan | Employee data |
| produk_hp | Mobile phone product information |
| komponen | Component inventory |
| supplier | Supplier information |
| gudang | Warehouse inventory |
| produksi | Production records |
| detail_produksi | Components used in each production process |
| quality_control | Product quality inspection records |

---

# 🔄 Business Workflow

```text
Supplier
      │
      ▼
Warehouse
      │
      ▼
Production
      │
      ▼
Finished Product
      │
      ▼
Quality Control
      │
      ▼
Manager Monitoring
```

Workflow Explanation:

1. Admin manages all master data.
2. Suppliers provide production components.
3. Components are stored in the warehouse.
4. Operators initiate production processes.
5. Components are used during mobile phone assembly.
6. Finished products are submitted for inspection.
7. Quality Control verifies product quality.
8. Managers monitor production performance and reports.

---

# 📋 Modules

## Authentication

- Login
- Logout
- Session Management

---

## Master Data

- Mobile Phone Products
- Components
- Suppliers
- Warehouse
- Employees
- Users

---

## Production

- Create Production Orders
- Production Details
- Component Usage
- Production Monitoring

---

## Quality Control

- Product Inspection
- Pass / Fail Status
- Inspection Notes

---

## Reports

- Production Reports
- Warehouse Reports
- Quality Control Reports
- Production Monitoring Dashboard

---

# 💻 Installation

## Clone Repository

```bash
git clone https://github.com/username/mobile-phone-assembly-service-management-system.git
```

Move into the project directory.

```bash
cd mobile-phone-assembly-service-management-system
```

---

## Database Setup

Create a new database.

```sql
CREATE DATABASE pabrik_hp;
```

Import the provided SQL file.

```
pabrik_hp.sql
```

---

## Configure Database

Edit:

```
application/config/database.php
```

Example configuration:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'pabrik_hp',
    'dbdriver' => 'mysqli'
);
```

---

## Configure Base URL

Edit:

```
application/config/config.php
```

```php
$config['base_url'] = 'http://localhost/mobile-phone-assembly-service-management-system/';
```

---

## Run the Application

Run the project using Apache (XAMPP or Laragon).

```
http://localhost/mobile-phone-assembly-service-management-system
```

---

# 🔐 Role Permissions

| Module | Admin | Operator | QC | Manager |
|---------|:----:|:--------:|:--:|:------:|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Users | ✅ | ❌ | ❌ | ❌ |
| Products | ✅ | ✅ | ❌ | 👁️ |
| Components | ✅ | ✅ | ❌ | 👁️ |
| Suppliers | ✅ | 👁️ | ❌ | 👁️ |
| Warehouse | ✅ | ✅ | ❌ | 👁️ |
| Production | ✅ | ✅ | 👁️ | 👁️ |
| Quality Control | ✅ | ❌ | ✅ | 👁️ |
| Reports | ✅ | 👁️ | 👁️ | ✅ |

Legend:

- ✅ Full Access
- 👁️ Read Only
- ❌ No Access

---

# 📊 Application Flow

```text
Login
   │
   ▼
Dashboard
   │
   ├── Master Data
   ├── Warehouse
   ├── Production
   ├── Quality Control
   └── Reports
```

---

# 🎯 Future Enhancements

- PDF Export
- Excel Export
- Interactive Dashboard & Analytics
- Barcode / QR Code Integration
- Purchase Order Module
- Multi-Warehouse Support
- Notification System
- REST API
- JWT Authentication
- Mobile Device Optimization

---

# 📄 License

This project is intended for educational and learning purposes. It may be modified and extended according to project requirements.

---

# 👨‍💻 Developed With

- CodeIgniter 3
- PHP
- MySQL
- Bootstrap
- KnockoutJS
- jQuery AJAX
- JavaScript

---

## ⭐ Repository

If you find this project useful, feel free to **Star ⭐** this repository and contribute through pull requests or suggestions.
