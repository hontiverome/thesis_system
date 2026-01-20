# 🚀 QUICK LOGIN GUIDE

**All passwords are:** `password`

---

## 📋 FACULTY LOGIN (4 accounts)

Login at: http://127.0.0.1:8000/login/faculty

| Name | School ID (use this) | Password | Groups Assigned |
|------|---------------------|----------|-----------------|
| Prof. Tokyo Athena | `2010-00001-FA-0` | `password` | G001, G003 (2 groups) |
| Prof. Jose Rizal | `2010-00002-FA-0` | `password` | G002 (1 group) |
| Prof. Clara Oswald | `2010-00003-FA-0` | `password` | None yet |
| Prof. Sherlock | `2010-00004-FA-0` | `password` | None yet |

---

## 👨‍🎓 STUDENT LOGIN (12 accounts)

Login at: http://127.0.0.1:8000/login/student

**Login Format:**
- Student Number field
- Birth Month (01-12)
- Birth Day (1-31)
- Birth Year (2003)
- Password

| Student | Student Number | Birthday | Password | Group | Role |
|---------|----------------|----------|----------|-------|------|
| Leader G1 | `2022-01001-MN-0` | 01/01/2003 | `password` | G001 | Leader |
| Member G1-A | `2022-01002-MN-0` | 01/02/2003 | `password` | G001 | Member |
| Member G1-B | `2022-01003-MN-0` | 01/03/2003 | `password` | G001 | Member |
| Member G1-C | `2022-01004-MN-0` | 01/04/2003 | `password` | G001 | Member |
| Leader G2 | `2022-02001-MN-0` | 02/01/2003 | `password` | G002 | Leader |
| Member G2-A | `2022-02002-MN-0` | 02/02/2003 | `password` | G002 | Member |
| Member G2-B | `2022-02003-MN-0` | 02/03/2003 | `password` | G002 | Member |
| Member G2-C | `2022-02004-MN-0` | 02/04/2003 | `password` | G002 | Member |
| Leader G3 | `2022-03001-MN-0` | 03/01/2003 | `password` | G003 | Leader |
| Member G3-A | `2022-03002-MN-0` | 03/02/2003 | `password` | G003 | Member |
| Member G3-B | `2022-03003-MN-0` | 03/03/2003 | `password` | G003 | Member |
| Member G3-C | `2022-03004-MN-0` | 03/04/2003 | `password` | G003 | Member |

---

## 👨‍💼 ADMIN LOGIN

School ID: `2000-00000-AD-0`
Password: `password`

---

## 📊 Database Summary

**Groups Created:** 3 groups (G001, G002, G003)
- **G001** (THESIS-2024-G01): 4 students, Adviser: Prof. Tokyo Athena, Proposal: AI-Powered Student Information System (Pending)
- **G002** (THESIS-2024-G02): 4 students, Adviser: Prof. Jose Rizal, Proposal: Smart Campus Navigation System (Approved)
- **G003** (THESIS-2024-G03): 4 students, Adviser: Prof. Tokyo Athena, Proposal: Online Thesis Management System (Pending)

**Proposals Created:** 3 proposals with different statuses
**Enrollments Created:** All groups enrolled in courses (C1 or C2)

---

## ⚠️ COMMON ISSUES

**If login still fails:**
1. Check browser console (F12) for errors
2. Verify Laravel server is running: http://127.0.0.1:8000
3. Verify Vite is running: http://localhost:5173
4. Clear browser cookies/cache
5. Check database has data: `php artisan db:show`

**Student Login Tips:**
- Enter birth month as TWO digits (01, 02, 03, not 1, 2, 3)
- Birth year must be 4 digits (2003)
- Birth day can be 1-31

**Faculty Login Tips:**
- Use the FULL School ID (2010-00001-FA-0)
- NOT the Faculty ID (F1, F2, etc.)

---

## 🧪 Testing the Frontend

**For Adviser Dashboard (AdviserClassDashboard.vue):**
1. Login as Prof. Tokyo Athena (`2010-00001-FA-0`)
2. You should see 2 groups (G001 and G003)
3. Each group should display 4 members
4. Navigate to proposals section to see pending/approved proposals

**For Student Dashboard:**
1. Login as any student
2. You should see your group information
3. Group leaders can submit proposals
4. View proposal status and defense schedule

**For Admin Dashboard:**
1. Login as Admin (`2000-00000-AD-0`)
2. View all users, groups, and proposals
3. Manage user roles and course assignments
