# Postman API Testing Guide - Faculty MOR Backend APIs

## 🚀 Quick Setup

**Base URL:** `http://127.0.0.1:8000/api/v1`

**Test Credentials:**
- **Faculty 1 (Adviser):** `faculty1@test.com` / `password123`
- **Faculty 2 (Adviser):** `faculty2@test.com` / `password123`  
- **Coordinator:** `coordinator@test.com` / `password123`

---

## Step 1: Authenticate (Get Token)

### Request: Login
```
POST http://127.0.0.1:8000/api/v1/auth/login/faculty
Content-Type: application/json

{
  "email": "faculty1@test.com",
  "password": "password123"
}
```

### Response:
```json
{
  "success": true,
  "token": "1|abcd1234...",
  "user": { ... }
}
```

**Copy the token** - you'll use it in all subsequent requests!

---

## Step 2: Set Authorization Header

In Postman, for ALL requests below:
1. Go to **Authorization** tab
2. Type: **Bearer Token**
3. Token: Paste the token from login response

---

## 📋 F-106: Title Proposal Approval API

### 1. GET All Proposals (Grouped by Course)
```
GET http://127.0.0.1:8000/api/v1/faculty/proposals
Authorization: Bearer YOUR_TOKEN_HERE
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    {
      "course_name": "Computer Science Research",
      "total_proposals": 2,
      "approved": 0,
      "pending": 2,
      "disapproved": 0,
      "proposals": [
        {
          "ProposalID": 1,
          "ResearchTitle": "Machine Learning for Predictive Analytics",
          "Status": "Pending",
          "group": {...}
        }
      ]
    }
  ]
}
```

### 2. GET Specific Proposal Details
```
GET http://127.0.0.1:8000/api/v1/faculty/proposals/{proposalId}
Authorization: Bearer YOUR_TOKEN_HERE
```

Replace `{proposalId}` with actual ID (e.g., `1` or `2`)

### 3. PATCH Submit Approval Verdict
```
PATCH http://127.0.0.1:8000/api/v1/proposals/{proposalId}/verdict
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json

{
  "verdict": "Approved",
  "remarks": "Good research topic with solid methodology"
}
```

**Options:**
- `verdict`: "Approved" or "Disapproved"
- `remarks`: Optional comment

**Expected Response:**
```json
{
  "success": true,
  "message": "Verdict submitted successfully",
  "data": {
    "approval": {...},
    "proposal_status": "Approved"
  }
}
```

---

## 📨 F-111: Panel Invitation API

### 1. GET All My Panel Invitations
```
GET http://127.0.0.1:8000/api/v1/faculty/me/invitations
Authorization: Bearer YOUR_TOKEN_HERE
```

**Expected Response:**
```json
{
  "success": true,
  "data": [
    {
      "DefenseID": "DEF-001",
      "Status": "Pending",
      "defense": {
        "DefenseType": "Proposal Defense",
        "Schedule": "2026-02-15 10:00:00"
      },
      "proposal": {
        "ResearchTitle": "Machine Learning..."
      },
      "group": {...}
    }
  ]
}
```

### 2. GET Specific Invitation Details
```
GET http://127.0.0.1:8000/api/v1/faculty/me/invitations/{defenseId}
Authorization: Bearer YOUR_TOKEN_HERE
```

Replace `{defenseId}` with actual ID (e.g., `DEF-001`)

### 3. POST Accept/Decline Invitation
```
POST http://127.0.0.1:8000/api/v1/invitations/{defenseId}/response
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: application/json

{
  "response": "Accepted"
}
```

**Options:**
- `response`: "Accepted" or "Declined"

**Expected Response:**
```json
{
  "success": true,
  "message": "Invitation response recorded successfully",
  "data": {
    "DefenseID": "DEF-001",
    "Status": "Accepted"
  }
}
```

---

## 📄 F-112: Defense Evaluation Document Upload API

### 1. POST Upload Evaluation Documents
```
POST http://127.0.0.1:8000/api/v1/defenses/{defenseId}/documents
Authorization: Bearer YOUR_TOKEN_HERE
Content-Type: multipart/form-data

Form Data:
- evaluation_form: [SELECT FILE - PDF/DOC/DOCX]
- grading_sheet: [SELECT FILE - PDF/DOC/DOCX/XLS/XLSX]
```

**In Postman:**
1. Select **Body** tab
2. Choose **form-data**
3. Add keys: `evaluation_form` and/or `grading_sheet`
4. Change type to **File** for each
5. Click **Select Files** to upload

**Expected Response:**
```json
{
  "success": true,
  "message": "Evaluation documents uploaded successfully",
  "data": {
    "DefenseID": "DEF-001",
    "files": [
      {
        "FileID": "EVAL-ABC123",
        "FileType": "Evaluation Form",
        "url": "http://...storage/defenses/DEF-001/evaluations/EVAL-ABC123.pdf"
      }
    ]
  }
}
```

### 2. GET All Uploaded Documents
```
GET http://127.0.0.1:8000/api/v1/defenses/{defenseId}/documents
Authorization: Bearer YOUR_TOKEN_HERE
```

---

## 🧪 Testing Scenarios

### Scenario 1: Faculty Approves Proposal
1. Login as `faculty1@test.com`
2. GET `/api/v1/faculty/proposals` - See your assigned proposals
3. PATCH `/api/v1/proposals/1/verdict` with `{"verdict": "Approved"}`
4. GET `/api/v1/faculty/proposals/1` - Verify status changed

### Scenario 2: Faculty Accepts Panel Invitation
1. Login as `faculty2@test.com`
2. GET `/api/v1/faculty/me/invitations` - See your invitations
3. POST `/api/v1/invitations/DEF-001/response` with `{"response": "Accepted"}`
4. GET `/api/v1/faculty/me/invitations/DEF-001` - Verify status is "Accepted"

### Scenario 3: Upload Evaluation Documents
1. Login as `faculty2@test.com` (who accepted DEF-002)
2. POST `/api/v1/defenses/DEF-002/documents` - Upload files
3. GET `/api/v1/defenses/DEF-002/documents` - Verify files are listed

---

## 🔍 Common Issues & Solutions

**401 Unauthorized**
- Token expired or invalid - Login again to get new token
- Forgot to add Bearer token in Authorization header

**403 Forbidden**
- Faculty trying to access proposal they're not assigned to
- Trying to upload documents without accepting panel invitation first

**404 Not Found**
- Using wrong ID (ProposalID, DefenseID)
- Check IDs by calling GET endpoints first

**422 Validation Error**
- Wrong field names or values in request body
- Check spelling: "verdict" not "status", "response" not "answer"

---

## 📊 Test Data Summary

Run this SQL to see all IDs:
```sql
SELECT ProposalID, ResearchTitle FROM Proposals;
SELECT DefenseID, DefenseType, Schedule FROM Defenses;
SELECT * FROM DefensePanel;
```

Or query via Postman after getting proposals/invitations!

---

**✅ Ready to test! Start with Step 1 (Login) and work through each API.**
