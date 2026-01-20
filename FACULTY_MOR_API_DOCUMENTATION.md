# Faculty MOR Backend - API Documentation

## Overview
This document provides comprehensive documentation for all Faculty Methods of Research (MOR) Backend APIs. These APIs support faculty workflows including proposal approval, panel invitations, document management, adviser assignment, and course navigation.

**Base URL**: `http://127.0.0.1:8000/api/v1`

**Authentication**: All endpoints require Bearer token authentication via Sanctum (`Authorization: Bearer <token>`)

---

## Table of Contents
1. [Authentication](#authentication)
2. [F-106: Title Proposal Approval API](#f-106-title-proposal-approval-api)
3. [F-111: Panel Invitation API](#f-111-panel-invitation-api)
4. [F-112: Document Upload API](#f-112-document-upload-api)
5. [F-113: Adviser Assignment API](#f-113-adviser-assignment-api)
6. [F-114: Panel Proposals List API](#f-114-panel-proposals-list-api)
7. [F-115: Course Navigation API](#f-115-course-navigation-api)

---

## Functional Specification Documents (FSD)

### F-106: Title Proposal Approval API

**Feature ID**: F-106  
**Feature Name**: Title Proposal Approval Management  
**Priority**: High  
**Status**: Implemented

**Description**: 
Enables faculty members to view and approve/disapprove title proposals submitted by student groups they advise. Coordinators and chairpersons can approve all proposals. The system automatically calculates overall proposal status based on all approval verdicts.

**Business Rules**:
- Advisers can only view proposals for groups they advise
- Coordinators/Chairpersons can view all proposals
- Panelists can view proposals for defenses they're assigned to
- Proposal status is "Disapproved" if any approval is disapproved
- Proposal status is "Approved" only when all required approvals are approved
- Proposal status remains "Pending" for partial approvals
- Faculty can change their verdict (idempotent operation)

**Endpoints**: 3

---

### F-111: Panel Invitation API

**Feature ID**: F-111  
**Feature Name**: Defense Panel Invitation Management  
**Priority**: High  
**Status**: Implemented

**Description**:
Manages panel invitations for thesis defenses. Faculty members can view their pending invitations, see defense details including the research proposal and student group information, and respond by accepting or declining the invitation.

**Business Rules**:
- Only invited panelists can view specific invitations
- Faculty can view all their invitations (pending, accepted, declined)
- Responses are idempotent - faculty can change their response
- Invitation details include full defense, proposal, and group information
- Panelists can only upload documents after accepting invitation

**Endpoints**: 3

---

### F-112: Document Upload API

**Feature ID**: F-112  
**Feature Name**: Defense Evaluation Document Management  
**Priority**: High  
**Status**: Implemented

**Description**:
Allows accepted panelists to upload evaluation forms and grading sheets for thesis defenses. Documents are securely stored in defense-specific directories with file validation and access control.

**Business Rules**:
- Only accepted panelists can upload documents
- Supports PDF, DOC, DOCX for evaluation forms
- Supports PDF, DOC, DOCX, XLS, XLSX for grading sheets
- Maximum file size: 10MB per file
- At least one document type must be uploaded
- Panelists, group members, and coordinators can view documents
- Files stored in defense-specific directories

**Endpoints**: 2

---

### F-113: Adviser Assignment API

**Feature ID**: F-113  
**Feature Name**: Research Adviser Assignment Management  
**Priority**: Medium  
**Status**: Implemented

**Description**:
Enables research coordinators and chairpersons to assign and manage faculty advisers for student thesis groups. Provides functionality to view available faculty members, assign advisers to groups, view current assignments, and remove advisers when needed.

**Business Rules**:
- Only coordinators and chairpersons can assign/remove advisers
- Only users with "faculty" role can be assigned as advisers
- Multiple advisers can be assigned to a single group
- Duplicate assignments are prevented
- Faculty workload (number of assigned groups) is tracked
- Assignment history is maintained

**Endpoints**: 4

**API Specifications**:

#### 1. GET /api/v1/faculty/available
**Purpose**: Retrieve list of all faculty members for assignment selection

**Authorization**: Coordinator or Chairperson role required

**Request**: None

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "UserID": 1,
      "FullName": "Dr. John Smith",
      "SchoolID": "FAC-001",
      "Email": "john.smith@pup.edu.ph",
      "assigned_groups_count": 2
    }
  ]
}
```

#### 2. GET /api/v1/groups/{groupId}/adviser
**Purpose**: View current adviser assignments for a specific group

**Path Parameters**:
- `groupId`: The GroupID (e.g., GRP-001)

**Response**:
```json
{
  "success": true,
  "data": {
    "GroupID": "GRP-001",
    "GroupName": "Group Alpha",
    "advisers": [
      {
        "UserID": 1,
        "FullName": "Dr. John Smith",
        "SchoolID": "FAC-001",
        "Email": "john.smith@pup.edu.ph",
        "assigned_at": "2026-01-10T10:00:00.000000Z"
      }
    ]
  }
}
```

#### 3. POST /api/v1/groups/{groupId}/adviser
**Purpose**: Assign a faculty member as adviser to a group

**Authorization**: Coordinator or Chairperson role required

**Path Parameters**:
- `groupId`: The GroupID

**Request Body**:
```json
{
  "adviser_id": 1
}
```

**Validation**:
- `adviser_id`: Required, integer, must exist in Users table, must have "faculty" role
- Prevents duplicate assignments

**Response**:
```json
{
  "success": true,
  "message": "Adviser assigned successfully",
  "data": {
    "GroupID": "GRP-001",
    "GroupName": "Group Alpha",
    "adviser": {
      "UserID": 1,
      "FullName": "Dr. John Smith",
      "SchoolID": "FAC-001",
      "Email": "john.smith@pup.edu.ph"
    }
  }
}
```

#### 4. DELETE /api/v1/groups/{groupId}/adviser/{adviserId}
**Purpose**: Remove an adviser assignment from a group

**Authorization**: Coordinator or Chairperson role required

**Path Parameters**:
- `groupId`: The GroupID
- `adviserId`: The UserID of the adviser

**Response**:
```json
{
  "success": true,
  "message": "Adviser removed successfully"
}
```

---

### F-114: Panel Proposals List API

**Feature ID**: F-114  
**Feature Name**: Panel Member Proposals Dashboard  
**Priority**: Medium  
**Status**: Implemented

**Description**:
Provides faculty panel members with a comprehensive view of all thesis proposals they are assigned to evaluate, regardless of invitation status. Includes detailed proposal information, defense schedules, panel composition, and personal assignment statistics.

**Business Rules**:
- Shows all defenses where faculty is a panelist (any status)
- Includes pending, accepted, and declined assignments
- Displays user's specific panel status for each defense
- Provides statistics for workload management
- Different from F-111 (invitations) - this is for active evaluation tracking

**Endpoints**: 2

**API Specifications**:

#### 1. GET /api/v1/faculty/panel/proposals
**Purpose**: Retrieve all thesis proposals where faculty is assigned as panelist

**Authorization**: Faculty role required

**Request**: None

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "DefenseID": "DEF-001",
      "DefenseType": "Proposal Defense",
      "Schedule": "2026-02-15 10:00:00",
      "OverallVerdict": "Pending",
      "my_panel_status": "Accepted",
      "proposal": {
        "ProposalID": "PROP-001",
        "ResearchTitle": "Machine Learning for Predictive Analytics",
        "Status": "Pending",
        "SubmissionDate": "2026-01-01"
      },
      "group": {
        "GroupID": "GRP-001",
        "GroupName": "Group Alpha",
        "members": [
          {
            "UserID": 4,
            "FullName": "Student One",
            "SchoolID": "2021-00001-MN-0"
          }
        ]
      },
      "course": {
        "CourseID": "CRS-001",
        "CourseName": "Computer Science Research",
        "CourseCode": "CPE-3-3"
      },
      "panel_members": [
        {
          "UserID": 1,
          "FullName": "Dr. John Smith",
          "Status": "Pending"
        },
        {
          "UserID": 2,
          "FullName": "Prof. Jane Doe",
          "Status": "Accepted"
        }
      ]
    }
  ]
}
```

#### 2. GET /api/v1/faculty/panel/proposals/stats
**Purpose**: Get statistical summary of faculty member's panel assignments

**Authorization**: Faculty role required

**Request**: None

**Response**:
```json
{
  "success": true,
  "data": {
    "total_assignments": 4,
    "accepted": 2,
    "pending_invitations": 1,
    "completed_defenses": 1
  }
}
```

**Statistics Explanation**:
- `total_assignments`: Total number of defense panels assigned to
- `accepted`: Number of invitations accepted
- `pending_invitations`: Number of invitations awaiting response
- `completed_defenses`: Number of defenses with final verdicts (non-pending)

---

### F-115: Course Navigation API

**Feature ID**: F-115  
**Feature Name**: Course and Section Navigation System  
**Priority**: Medium  
**Status**: Implemented

**Description**:
Enables faculty members and coordinators to browse and navigate through courses, view enrolled groups (sections), and access course-specific information. Supports filtering by year level, semester, and search functionality. Faculty can view their assigned courses separately.

**Business Rules**:
- All authenticated users can view courses
- Course list shows enrollment statistics
- Sections display includes group details, members, and advisers
- Faculty can filter to see only courses where they are advisers
- Search supports course name and course code
- Year level and semester filters available

**Endpoints**: 3

**API Specifications**:

#### 1. GET /api/v1/courses
**Purpose**: Retrieve all courses with optional filtering

**Authorization**: Authenticated user

**Query Parameters** (all optional):
- `year_level` (integer): Filter by year level (e.g., 3)
- `semester` (integer): Filter by semester (e.g., 3)
- `search` (string): Search by course name or code

**Example**: `GET /api/v1/courses?year_level=3&semester=3&search=Computer`

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research",
      "CourseCode": "CPE-3-3",
      "YearLevel": 3,
      "Semester": 3,
      "enrolled_groups_count": 2
    }
  ]
}
```

#### 2. GET /api/v1/courses/{courseId}/sections
**Purpose**: View all groups (sections) enrolled in a specific course

**Path Parameters**:
- `courseId`: The CourseID (e.g., CRS-001)

**Authorization**: Authenticated user

**Response**:
```json
{
  "success": true,
  "data": {
    "course": {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research",
      "CourseCode": "CPE-3-3",
      "YearLevel": 3,
      "Semester": 3
    },
    "sections": [
      {
        "GroupID": "GRP-001",
        "GroupName": "Group Alpha",
        "EnrollmentID": "ENR-001",
        "EnrollmentDate": "2026-01-01",
        "members_count": 3,
        "members": [
          {
            "UserID": 4,
            "FullName": "Student One",
            "SchoolID": "2021-00001-MN-0"
          }
        ],
        "advisers": [
          {
            "UserID": 1,
            "FullName": "Dr. John Smith",
            "SchoolID": "FAC-001"
          }
        ]
      }
    ]
  }
}
```

#### 3. GET /api/v1/faculty/my-courses
**Purpose**: Retrieve courses where authenticated faculty is assigned as adviser

**Authorization**: Faculty role required

**Request**: None

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research",
      "CourseCode": "CPE-3-3",
      "YearLevel": 3,
      "Semester": 3,
      "my_groups_count": 2
    }
  ]
}
```

**Use Cases**:
- Faculty homepage showing "My Courses"
- Course selection interface for coordinators
- Navigation menu for accessing specific sections
- Workload distribution visualization

---

## Authentication

### Faculty Login
**Endpoint**: `POST /api/v1/auth/faculty/login`

**Description**: Authenticate faculty members using SchoolID and password

**Request Body**:
```json
{
  "SchoolID": "FAC-001",
  "password": "password123"
}
```

**Response**:
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "UserID": 1,
      "FullName": "Dr. John Smith",
      "Email": "john.smith@pup.edu.ph",
      "SchoolID": "FAC-001",
      "roles": ["faculty"]
    },
    "token": "2|7v1A8dRJwfgAv3yGbWB9surKp2oUOPnvX7SqEo5e3fb160"
  }
}
```

---

## F-106: Title Proposal Approval API

### 1. Get All Proposals (Advised Groups)

**Endpoint**: `GET /api/v1/faculty/proposals`

**Description**: Retrieve all title proposals for groups advised by the authenticated faculty member, grouped by course

**Authorization**: Faculty role required

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "course_name": "Computer Science Research",
      "course_code": "CPE-3-3",
      "course_id": "CRS-001",
      "total_proposals": 1,
      "approved": 0,
      "pending": 1,
      "disapproved": 0,
      "proposals": [
        {
          "ProposalID": "PROP-001",
          "ResearchTitle": "Machine Learning for Predictive Analytics",
          "Status": "Pending",
          "SubmissionDate": "2026-01-01",
          "Deadline": "2026-02-01",
          "group": {
            "GroupID": "GRP-001",
            "GroupName": "Group Alpha",
            "member_count": 3
          },
          "approval_count": 0,
          "approved_count": 0,
          "disapproved_count": 0
        }
      ]
    }
  ]
}
```

### 2. Get Proposal Details

**Endpoint**: `GET /api/v1/faculty/proposals/{proposalId}`

**Description**: Retrieve detailed information about a specific proposal including all approvals

**Path Parameters**:
- `proposalId` (string): The ProposalID (e.g., PROP-001)

**Authorization**: 
- Must be adviser of the group, OR
- Panelist for a defense of this proposal, OR
- Coordinator/Chairperson

**Response**:
```json
{
  "success": true,
  "data": {
    "proposal": {
      "ProposalID": "PROP-001",
      "ResearchTitle": "Machine Learning for Predictive Analytics",
      "Status": "Pending",
      "SubmissionDate": "2026-01-01",
      "Deadline": "2026-02-01"
    },
    "group": {
      "GroupID": "GRP-001",
      "GroupName": "Group Alpha",
      "members": [
        {
          "UserID": 4,
          "FullName": "Student One",
          "SchoolID": "2021-00001-MN-0"
        }
      ]
    },
    "course": {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research",
      "CourseCode": "CPE-3-3"
    },
    "approvals": [
      {
        "ApprovalID": "APPR-001",
        "approver": {
          "UserID": 1,
          "FullName": "Dr. John Smith",
          "roles": ["faculty", "adviser"]
        },
        "ApprovalRole": "Adviser",
        "Status": "Approved",
        "Remarks": "Excellent research topic!",
        "created_at": "2026-01-19"
      }
    ]
  }
}
```

### 3. Submit/Update Approval Verdict

**Endpoint**: `PATCH /api/v1/proposals/{proposalId}/verdict`

**Description**: Submit or update approval verdict for a proposal. Idempotent - allows changing verdict.

**Path Parameters**:
- `proposalId` (string): The ProposalID

**Request Body**:
```json
{
  "verdict": "Approved",
  "remarks": "Excellent research topic!"
}
```

**Validation**:
- `verdict`: Required, must be "Approved" or "Disapproved"
- `remarks`: Optional, max 500 characters

**Business Logic**:
- Status set to "Disapproved" if any approval is disapproved
- Status set to "Approved" only when all approvals are approved
- Status remains "Pending" for partial approvals
- Idempotent: allows faculty to change their verdict

**Response**:
```json
{
  "success": true,
  "message": "Verdict submitted successfully",
  "data": {
    "approval": {
      "ApprovalID": "APPR-001",
      "ProposalID": "PROP-001",
      "Status": "Approved",
      "Remarks": "Excellent research topic!"
    },
    "proposal_status": "Pending"
  }
}
```

---

## F-111: Panel Invitation API

### 1. Get All Panel Invitations

**Endpoint**: `GET /api/v1/faculty/me/invitations`

**Description**: Retrieve all defense panel invitations for the authenticated faculty member

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "DefenseID": "DEF-001",
      "Status": "Pending",
      "defense": {
        "DefenseID": "DEF-001",
        "DefenseType": "Proposal Defense",
        "Schedule": "2026-02-15 10:00:00",
        "OverallVerdict": "Pending"
      },
      "proposal": {
        "ProposalID": "PROP-001",
        "ResearchTitle": "Machine Learning for Predictive Analytics",
        "Status": "Pending"
      },
      "group": {
        "GroupID": "GRP-001",
        "GroupName": "Group Alpha",
        "members": [
          {
            "UserID": 4,
            "name": "Student One",
            "id_number": "2021-00001-MN-0"
          }
        ]
      },
      "course": {
        "CourseID": "CRS-001",
        "CourseName": "Computer Science Research"
      }
    }
  ]
}
```

### 2. Get Specific Panel Invitation

**Endpoint**: `GET /api/v1/faculty/me/invitations/{defenseId}`

**Description**: Retrieve detailed information about a specific panel invitation

**Path Parameters**:
- `defenseId` (string): The DefenseID (e.g., DEF-001)

**Response**:
```json
{
  "success": true,
  "data": {
    "DefenseID": "DEF-001",
    "Status": "Pending",
    "defense": {
      "DefenseID": "DEF-001",
      "DefenseType": "Proposal Defense",
      "Schedule": "2026-02-15 10:00:00",
      "OverallVerdict": "Pending"
    },
    "proposal": {
      "ProposalID": "PROP-001",
      "ResearchTitle": "Machine Learning for Predictive Analytics",
      "Status": "Pending",
      "SubmissionDate": "2026-01-01"
    },
    "group": {
      "GroupID": "GRP-001",
      "GroupName": "Group Alpha",
      "members": [
        {
          "UserID": 4,
          "name": "Student One",
          "id_number": "2021-00001-MN-0",
          "email": "student1@iskolarngbayan.pup.edu.ph"
        }
      ]
    },
    "course": {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research"
    },
    "all_panelists": [
      {
        "UserID": 1,
        "name": "Dr. John Smith",
        "email": "john.smith@pup.edu.ph",
        "Status": "Pending"
      },
      {
        "UserID": 2,
        "name": "Prof. Jane Doe",
        "email": "jane.doe@pup.edu.ph",
        "Status": "Accepted"
      }
    ]
  }
}
```

### 3. Respond to Panel Invitation

**Endpoint**: `POST /api/v1/invitations/{defenseId}/response`

**Description**: Accept or decline a panel invitation. Idempotent - allows changing response.

**Path Parameters**:
- `defenseId` (string): The DefenseID

**Request Body**:
```json
{
  "response": "Accepted"
}
```

**Validation**:
- `response`: Required, must be "Accepted" or "Declined"

**Response**:
```json
{
  "success": true,
  "message": "Invitation response recorded successfully",
  "data": {
    "DefenseID": "DEF-001",
    "PanelistUserID": 2,
    "Status": "Accepted"
  }
}
```

---

## F-112: Document Upload API

### 1. Upload Evaluation Documents

**Endpoint**: `POST /api/v1/defenses/{defenseId}/documents`

**Description**: Upload evaluation form and/or grading sheet for a defense. Only accepted panelists can upload.

**Path Parameters**:
- `defenseId` (string): The DefenseID

**Request Body**: `multipart/form-data`
- `evaluation_form` (file): Optional. PDF, DOC, or DOCX. Max 10MB.
- `grading_sheet` (file): Optional. PDF, DOC, DOCX, XLS, or XLSX. Max 10MB.

**Note**: At least one file is required

**Authorization**: Must be an accepted panelist for this defense

**Response**:
```json
{
  "success": true,
  "message": "Documents uploaded successfully",
  "data": [
    {
      "FileID": "FILE-001",
      "FileType": "Evaluation Form",
      "FilePath": "defenses/DEF-001/evaluations/FILE-001.pdf",
      "url": "/storage/defenses/DEF-001/evaluations/FILE-001.pdf"
    },
    {
      "FileID": "FILE-002",
      "FileType": "Grading Sheet",
      "FilePath": "defenses/DEF-001/grading/FILE-002.xlsx",
      "url": "/storage/defenses/DEF-001/grading/FILE-002.xlsx"
    }
  ]
}
```

### 2. Get Defense Documents

**Endpoint**: `GET /api/v1/defenses/{defenseId}/documents`

**Description**: Retrieve all uploaded documents for a defense

**Path Parameters**:
- `defenseId` (string): The DefenseID

**Authorization**: 
- Must be a panelist for this defense, OR
- Member of the group being evaluated, OR
- Coordinator/Chairperson

**Response**:
```json
{
  "success": true,
  "data": {
    "DefenseID": "DEF-001",
    "documents": [
      {
        "FileID": "FILE-001",
        "FileType": "Evaluation Form",
        "FilePath": "defenses/DEF-001/evaluations/FILE-001.pdf",
        "url": "/storage/defenses/DEF-001/evaluations/FILE-001.pdf",
        "uploaded_by": {
          "UserID": 2,
          "name": "Prof. Jane Doe"
        }
      }
    ]
  }
}
```

---

## Adviser Assignment API

### 1. Get Available Faculty

**Endpoint**: `GET /api/v1/faculty/available`

**Description**: Get list of all faculty members available for adviser assignment

**Authorization**: Coordinator or Chairperson role required

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "UserID": 1,
      "FullName": "Dr. John Smith",
      "SchoolID": "FAC-001",
      "Email": "john.smith@pup.edu.ph",
      "assigned_groups_count": 2
    },
    {
      "UserID": 2,
      "FullName": "Prof. Jane Doe",
      "SchoolID": "FAC-002",
      "Email": "jane.doe@pup.edu.ph",
      "assigned_groups_count": 1
    }
  ]
}
```

### 2. Get Group's Advisers

**Endpoint**: `GET /api/v1/groups/{groupId}/adviser`

**Description**: Get current adviser assignments for a specific group

**Path Parameters**:
- `groupId` (string): The GroupID (e.g., GRP-001)

**Response**:
```json
{
  "success": true,
  "data": {
    "GroupID": "GRP-001",
    "GroupName": "Group Alpha",
    "advisers": [
      {
        "UserID": 1,
        "FullName": "Dr. John Smith",
        "SchoolID": "FAC-001",
        "Email": "john.smith@pup.edu.ph",
        "assigned_at": "2026-01-10T10:00:00.000000Z"
      }
    ]
  }
}
```

### 3. Assign Adviser to Group

**Endpoint**: `POST /api/v1/groups/{groupId}/adviser`

**Description**: Assign a faculty member as adviser to a group

**Path Parameters**:
- `groupId` (string): The GroupID

**Authorization**: Coordinator or Chairperson role required

**Request Body**:
```json
{
  "adviser_id": 1
}
```

**Validation**:
- `adviser_id`: Required, must be a valid UserID with faculty role

**Response**:
```json
{
  "success": true,
  "message": "Adviser assigned successfully",
  "data": {
    "GroupID": "GRP-001",
    "GroupName": "Group Alpha",
    "adviser": {
      "UserID": 1,
      "FullName": "Dr. John Smith",
      "SchoolID": "FAC-001",
      "Email": "john.smith@pup.edu.ph"
    }
  }
}
```

### 4. Remove Adviser from Group

**Endpoint**: `DELETE /api/v1/groups/{groupId}/adviser/{adviserId}`

**Description**: Remove an adviser assignment from a group

**Path Parameters**:
- `groupId` (string): The GroupID
- `adviserId` (integer): The UserID of the adviser to remove

**Authorization**: Coordinator or Chairperson role required

**Response**:
```json
{
  "success": true,
  "message": "Adviser removed successfully"
}
```

---

## Panel Proposals List API

### 1. Get All Panel Proposals

**Endpoint**: `GET /api/v1/faculty/panel/proposals`

**Description**: Get all thesis proposals where the faculty member is assigned as a panelist (different from invitations - shows all proposals they're evaluating)

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "DefenseID": "DEF-001",
      "DefenseType": "Proposal Defense",
      "Schedule": "2026-02-15 10:00:00",
      "OverallVerdict": "Pending",
      "my_panel_status": "Accepted",
      "proposal": {
        "ProposalID": "PROP-001",
        "ResearchTitle": "Machine Learning for Predictive Analytics",
        "Status": "Pending",
        "SubmissionDate": "2026-01-01"
      },
      "group": {
        "GroupID": "GRP-001",
        "GroupName": "Group Alpha",
        "members": [
          {
            "UserID": 4,
            "FullName": "Student One",
            "SchoolID": "2021-00001-MN-0"
          }
        ]
      },
      "course": {
        "CourseID": "CRS-001",
        "CourseName": "Computer Science Research",
        "CourseCode": "CPE-3-3"
      },
      "panel_members": [
        {
          "UserID": 1,
          "FullName": "Dr. John Smith",
          "Status": "Pending"
        },
        {
          "UserID": 2,
          "FullName": "Prof. Jane Doe",
          "Status": "Accepted"
        }
      ]
    }
  ]
}
```

### 2. Get Panel Statistics

**Endpoint**: `GET /api/v1/faculty/panel/proposals/stats`

**Description**: Get statistics about the faculty member's panel assignments

**Response**:
```json
{
  "success": true,
  "data": {
    "total_assignments": 4,
    "accepted": 2,
    "pending_invitations": 1,
    "completed_defenses": 1
  }
}
```

---

## Course Navigation API

### 1. Get All Courses

**Endpoint**: `GET /api/v1/courses`

**Description**: Get all courses with optional filtering

**Query Parameters**:
- `year_level` (integer): Optional. Filter by year level
- `semester` (integer): Optional. Filter by semester
- `search` (string): Optional. Search by course name or code

**Example**: `GET /api/v1/courses?year_level=3&semester=3`

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research",
      "CourseCode": "CPE-3-3",
      "YearLevel": 3,
      "Semester": 3,
      "enrolled_groups_count": 2
    }
  ]
}
```

### 2. Get Course Sections

**Endpoint**: `GET /api/v1/courses/{courseId}/sections`

**Description**: Get all sections (groups) enrolled in a specific course

**Path Parameters**:
- `courseId` (string): The CourseID

**Response**:
```json
{
  "success": true,
  "data": {
    "course": {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research",
      "CourseCode": "CPE-3-3",
      "YearLevel": 3,
      "Semester": 3
    },
    "sections": [
      {
        "GroupID": "GRP-001",
        "GroupName": "Group Alpha",
        "EnrollmentID": "ENR-001",
        "EnrollmentDate": "2026-01-01",
        "members_count": 3,
        "members": [
          {
            "UserID": 4,
            "FullName": "Student One",
            "SchoolID": "2021-00001-MN-0"
          }
        ],
        "advisers": [
          {
            "UserID": 1,
            "FullName": "Dr. John Smith",
            "SchoolID": "FAC-001"
          }
        ]
      }
    ]
  }
}
```

### 3. Get My Courses

**Endpoint**: `GET /api/v1/faculty/my-courses`

**Description**: Get all courses where the faculty member is an adviser to at least one group

**Response**:
```json
{
  "success": true,
  "data": [
    {
      "CourseID": "CRS-001",
      "CourseName": "Computer Science Research",
      "CourseCode": "CPE-3-3",
      "YearLevel": 3,
      "Semester": 3,
      "my_groups_count": 2
    }
  ]
}
```

---

## Error Responses

All endpoints return consistent error responses:

### 400 Bad Request
```json
{
  "success": false,
  "message": "Validation error message",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

### 401 Unauthorized
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Unauthorized to perform this action"
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 500 Internal Server Error
```json
{
  "success": false,
  "message": "An error occurred",
  "error": "Detailed error message"
}
```

---

## Test Credentials

**Faculty Adviser** (for F-106, F-112):
- SchoolID: `FAC-001`
- Password: `password123`
- Advised Groups: GRP-001

**Faculty Panelist** (for F-111, F-112):
- SchoolID: `FAC-002`
- Password: `password123`
- Panel Assignments: DEF-001, DEF-002

**Research Coordinator** (for all APIs + assignment):
- SchoolID: `COORD-001`
- Password: `password123`
- Full access to all features

---

## Implementation Notes

1. **Authentication**: All endpoints use Laravel Sanctum for token-based authentication
2. **Authorization**: Role-based access control using Laravel's middleware
3. **Validation**: Laravel Form Request validation for all input data
4. **Database**: String-based primary keys (e.g., PROP-001, DEF-001) for better traceability
5. **File Storage**: Laravel Storage with public disk for document uploads
6. **Transactions**: Database transactions used for critical operations
7. **Idempotency**: Verdict updates and invitation responses are idempotent

---

## API Summary

| Feature ID | Category | Endpoints | Description | Priority | Status |
|-----------|----------|-----------|-------------|----------|--------|
| **F-106** | Title Proposal Approval | 3 | Faculty approve/disapprove proposals | High | ✅ Implemented |
| **F-111** | Panel Invitation | 3 | Manage defense panel invitations | High | ✅ Implemented |
| **F-112** | Document Upload | 2 | Upload/retrieve evaluation documents | High | ✅ Implemented |
| **F-113** | Adviser Assignment | 4 | Assign faculty advisers to groups | Medium | ✅ Implemented |
| **F-114** | Panel Proposals List | 2 | View panel member's assigned proposals | Medium | ✅ Implemented |
| **F-115** | Course Navigation | 3 | Browse courses and sections | Medium | ✅ Implemented |
| **Total** | **6 Features** | **17** | **Complete Faculty MOR Backend** | - | ✅ |

### Endpoint Distribution

**High Priority APIs (Core Workflow)**: 8 endpoints
- F-106: 3 endpoints
- F-111: 3 endpoints
- F-112: 2 endpoints

**Medium Priority APIs (Management & Navigation)**: 9 endpoints
- F-113: 4 endpoints
- F-114: 2 endpoints
- F-115: 3 endpoints

---

**Document Version**: 1.0  
**Last Updated**: January 19, 2026  
**API Version**: v1
