# Test Account Credentials

## 🔐 Login Information
All test accounts use the same password: **`password123`**

---

## 👨‍💼 Admin Account
- **School ID:** 2000-00000-AD-0
- **Password:** password123
- **Name:** Admin User
- **Role:** Administrator
- **Access:** Full system access, user management, course configuration

---

## 👨‍🏫 Faculty Accounts
**Login with: School ID + Password** (NOT Faculty ID)

### Faculty 1 (Prof. Tokyo Athena)
- **School ID:** 2010-00001-FA-0 👈 **USE THIS TO LOGIN**
- **Faculty ID:** F1 (for reference only)
- **Password:** password123
- **Type:** Full-Time

### Faculty 2 (Prof. Jose Rizal)
- **School ID:** 2010-00002-FA-0 👈 **USE THIS TO LOGIN**
- **Faculty ID:** F2 (for reference only)
- **Password:** password123
- **Type:** Part-Time

### Faculty 3 (Prof. Clara Oswald)
- **School ID:** 2010-00003-FA-0 👈 **USE THIS TO LOGIN**
- **Faculty ID:** F3 (for reference only)
- **Password:** password123
- **Type:** Full-Time

### Faculty 4 (Prof. Sherlock)
- **School ID:** 2010-00004-FA-0 👈 **USE THIS TO LOGIN**
- **Faculty ID:** F4 (for reference only)
- **Password:** password123
- **Type:** Part-Time

---

## 👨‍🎓 Student Accounts
**Login with: Student Number + Birthday + Password**

### Group 1

#### Student 1 (Leader G1)
- **Student Number:** 2022-01001-MN-0
- **Birthday:** January 1, 2003 (01/01/2003)
- **Password:** password123
- **Email:** s1@gmail.com

#### Student 2 (Member G1-A)
- **Student Number:** 2022-01002-MN-0
- **Birthday:** January 2, 2003 (01/02/2003)
- **Password:** password123
- **Email:** s2@gmail.com

#### Student 3 (Member G1-B)
- **Student Number:** 2022-01003-MN-0
- **Birthday:** January 3, 2003 (01/03/2003)
- **Password:** password123
- **Email:** s3@gmail.com

#### Student 4 (Member G1-C)
- **Student Number:** 2022-01004-MN-0
- **Birthday:** January 4, 2003 (01/04/2003)
- **Password:** password123
- **Email:** s4@gmail.com

### Group 2

#### Student 5 (Leader G2)
- **Student Number:** 2022-02001-MN-0
- **Birthday:** February 1, 2003 (02/01/2003)
- **Password:** password123
- **Email:** s5@gmail.com

#### Student 6 (Member G2-A)
- **Student Number:** 2022-02002-MN-0
- **Birthday:** February 2, 2003 (02/02/2003)
- **Password:** password123
- **Email:** s6@gmail.com

#### Student 7 (Member G2-B)
- **Student Number:** 2022-02003-MN-0
- **Birthday:** February 3, 2003 (02/03/2003)
- **Password:** password123
- **Email:** s7@gmail.com

#### Student 8 (Member G2-C)
- **Student Number:** 2022-02004-MN-0
- **Birthday:** February 4, 2003 (02/04/2003)
- **Password:** password123
- **Email:** s8@gmail.com

### Group 3

#### Student 9 (Leader G3)
- **Student Number:** 2022-03001-MN-0
- **Birthday:** March 1, 2003 (03/01/2003)
- **Password:** password123
- **Email:** s9@gmail.com

#### Student 10 (Member G3-A)
- **Student Number:** 2022-03002-MN-0
- **Birthday:** March 2, 2003 (03/02/2003)
- **Password:** password123
- **Email:** s10@gmail.com

#### Student 11 (Member G3-B)
- **Student Number:** 2022-03003-MN-0
- **Birthday:** March 3, 2003 (03/03/2003)
- **Password:** password123
- **Email:** s11@gmail.com

#### Student 12 (Member G3-C)
- **Student Number:** 2022-03004-MN-0
- **Birthday:** March 4, 2003 (03/04/2003)
- **Password:** password123
- **Email:** s12@gmail.com

---

## 🚀 Quick Start

1. **Start Laravel Server:**
   ```bash
   php artisan serve
   ```
   Access at: http://127.0.0.1:8000

2. **Start Vite Dev Server:**
   ```bash
   npm run dev
   ```
   Access at: http://localhost:5173

3. **Login URL:** http://127.0.0.1:8000/portal

4. **Choose any account above and use password:** `password`

---

## 📊 Database Info

- **Total Users:** 18 (1 admin + 5 faculty + 12 students)
- **Courses Seeded:** Yes
- **Roles Seeded:** Admin, Faculty, Adviser, Student
- **Database:** MySQL (XAMPP on port 3306)

---

## 🔄 Reset Database

If you need fresh data:
```bash
php artisan migrate:fresh --seed
```
