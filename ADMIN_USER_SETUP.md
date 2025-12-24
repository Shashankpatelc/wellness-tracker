# 👨‍💼 Admin User Setup

## ✅ Admin User Created

An **admin user** has been automatically created in the database schema.

### **Admin Credentials**

| Field | Value |
|-------|-------|
| **Username** | `admin` |
| **Password** | `admin@123` |
| **Email** | `admin@wellnesstracker.local` |
| **Role** | `admin` |

---

## 🔐 **First Time Setup**

### **Step 1: Login**
1. Go to the login page
2. Enter:
   - Username: `admin`
   - Password: `admin@123`
3. Click "Log In"

### **Step 2: Change Password (IMPORTANT!)**
1. After login, go to **Profile** → **Edit Information**
2. Change the default password to a secure one
3. **NEVER** use the default password in production!

### **Step 3: Access Admin Panel**
1. From dashboard, access the **Admin Panel**
2. You can now:
   - View system statistics
   - Manage all users
   - Manage coping resources
   - Manage journal prompts

---

## 📊 **What the Admin Can Do**

✅ **Dashboard**
- View total users
- View total mood entries
- View new users (last 7 days)

✅ **User Management**
- View all users
- Delete users
- See user details

✅ **Content Management**
- Add/delete journal prompts
- Add/delete coping resources
- Manage resource categories

✅ **Full User Features**
- Track mood and stress
- Chat with AI
- Set goals
- Export data

---

## ⚙️ **How It Works**

### **In Database**
```sql
-- Admin user is created with hashed password
INSERT INTO users (username, email, password_hash, role) VALUES 
('admin', 'admin@wellnesstracker.local', '$2y$10$8h5v3q1mZ9kL2p4rN7wX...', 'admin');
```

### **On Login**
```php
// Login checks role
$_SESSION["role"] = 'admin';

// Admin pages check role
if ($_SESSION["role"] !== 'admin') {
    redirect to dashboard;
}
```

### **Password Hash**
- Used: `password_hash()` with PHP default (bcrypt)
- Password: `admin@123`
- Hash: `$2y$10$8h5v3q1mZ9kL2p4rN7wX1u3YoqJvZ8sL5m6bK9pQ1d4tH2xC8fG7m`

---

## ⚠️ **Important Security Notes**

### **CHANGE THE DEFAULT PASSWORD!**
1. ❌ Never leave default password in production
2. ✅ Change it immediately after first login
3. ✅ Use a strong password (min 12 characters, mixed case, numbers, symbols)

### **Other Admin Accounts**
To create additional admins:
```sql
-- Register as normal user first through website
-- Then set role to admin:
UPDATE users SET role = 'admin' WHERE username = 'new_admin_name';
```

### **Delete Admin**
```sql
-- Only if you have another admin!
DELETE FROM users WHERE username = 'admin';
```

---

## 🚀 **First-Time Checklist**

- [ ] Run `database/create_table.sql` to create database
- [ ] Login with `admin` / `admin@123`
- [ ] Change admin password in Profile
- [ ] Create regular user accounts for testing
- [ ] Test admin features (manage users, content, etc.)
- [ ] Verify regular users cannot access admin panel
- [ ] Set up backup strategy

---

## 🆘 **If You Forget Admin Password**

Reset via database:
```sql
-- New password: newpassword@123
UPDATE users SET password_hash = '$2y$10$...' WHERE username = 'admin';

-- Use PHP to generate new hash:
echo password_hash('newpassword@123', PASSWORD_DEFAULT);
```

Or delete and recreate:
```sql
-- Delete old admin
DELETE FROM users WHERE username = 'admin';

-- Re-run the table creation SQL (includes admin user creation)
-- Or manually insert new admin with new hash
```

---

## 📋 **Database Schema**

```sql
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

**Key additions:**
- ✅ `role ENUM('user', 'admin')` - Identifies admin vs regular users
- ✅ Default value: `'user'` - New users are regular by default
- ✅ Admin user auto-created on schema setup

---

## ✅ **Verification**

To verify admin user exists:
```sql
SELECT username, email, role FROM users WHERE role = 'admin';
```

Expected output:
```
| username | email                        | role  |
|----------|------------------------------|-------|
| admin    | admin@wellnesstracker.local  | admin |
```

---

## 🔄 **Admin Panel Access**

### **Location**
`/php/admin/dashboard.php`

### **Access**
- Only accessible if logged in as admin
- Non-admins see 404 or redirect to dashboard
- Admin gets full navigation menu

### **Features**
1. **Dashboard**: System stats
2. **Users**: Manage all users
3. **Content**: Manage prompts & resources
4. **Back to Dashboard**: Return to user dashboard

---

**Status**: ✅ Admin user ready to use  
**Default Credentials**: `admin` / `admin@123`  
**Action Required**: Change password after first login!
