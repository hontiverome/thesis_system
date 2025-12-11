-- A. Roles: Defines the available roles (e.g., 'Student', 'Faculty').
CREATE TABLE Roles (
    RoleID INT NOT NULL AUTO_INCREMENT PRIMARY KEY, -- Using INT and AUTO_INCREMENT for SERIAL equivalent in MySQL
    RoleName VARCHAR(50) NOT NULL UNIQUE
);

-- B. Users: Central table for all system users.
CREATE TABLE Users (
    UserID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,   -- Internal DB ID (Links tables together)
    SchoolID VARCHAR(30) NOT NULL UNIQUE,             -- Official Student/Faculty No. (Used for Login)
    FullName VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL UNIQUE,
    BirthDate DATE NULL,                              -- Optional: Required for Students, Empty for Faculty
    PasswordHash VARCHAR(255) NOT NULL
);
-- C. UserRoles: Junction table to assign roles to users (Many-to-Many).
-- Uses a Composite Primary Key (UserID, RoleID).
CREATE TABLE UserRoles (
    UserID INT NOT NULL,     -- Placeholder for FK to Users(UserID)
    RoleID INT NOT NULL,     -- Placeholder for FK to Roles(RoleID)
    PRIMARY KEY (UserID, RoleID)
);

-- D. FacultyDetails: Specific details for Faculty users.
CREATE TABLE FacultyDetails (
    FacultyDetailID VARCHAR(10) PRIMARY KEY,
    UserID INT NOT NULL UNIQUE,     -- Placeholder for unique FK to Users(UserID).
    FacultyType VARCHAR(20)
);

-- E. Courses: List of academic courses/thesis stages.
CREATE TABLE Courses (
    CourseID VARCHAR(10) PRIMARY KEY,
    CourseName VARCHAR(50) NOT NULL,
    PrerequisiteCourseID VARCHAR(10) -- Placeholder for self-referencing FK
);

-- Q. PasswordResets: Tracks password reset requests for security auditing.
-- NEW TABLE ADDED
CREATE TABLE PasswordResets (
    ResetID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    UserID INT NOT NULL,                -- Placeholder for FK to Users(UserID)
    ResetToken VARCHAR(255) NOT NULL,
    CreatedAt DATETIME NOT NULL,
    ExpiresAt DATETIME NOT NULL,
    IsUsed BOOLEAN DEFAULT FALSE
);

