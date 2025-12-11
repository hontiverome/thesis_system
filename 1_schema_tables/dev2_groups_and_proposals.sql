-- F. Groups: Manages student thesis groups.
-- Based on ERD: ID is string, includes YearLevel and specific AdviserUserID.
CREATE TABLE `Groups` (
    GroupID VARCHAR(10) NOT NULL PRIMARY KEY, -- Changed to VARCHAR to match 'string [pk]'
    GroupCode VARCHAR(20) UNIQUE,             -- Matches 'GroupCode string [unique]'
    YearLevel INT,                            -- Matches 'YearLevel int'
    AdviserUserID INT NOT NULL                -- Placeholder for FK to Users (Faculty)
);

-- G. GroupMembers: Junction table for Students in Groups.
-- Based on ERD: Uses 'StudentUserID' and 'GroupRole'.
CREATE TABLE GroupMembers (
    GroupID VARCHAR(10) NOT NULL,    -- Placeholder for FK to Groups(GroupID)
    StudentUserID INT NOT NULL,      -- Placeholder for FK to Users(UserID) - specific name 'StudentUserID'
    GroupRole VARCHAR(20),           -- Matches 'GroupRole string'
    PRIMARY KEY (GroupID, StudentUserID)
);

-- H. GroupAdvisers: Junction table for Faculty assigning to Groups.
-- Based on ERD: Uses 'AdviserUserID'.
CREATE TABLE GroupAdvisers (
    GroupID VARCHAR(10) NOT NULL,    -- Placeholder for FK to Groups(GroupID)
    AdviserUserID INT NOT NULL,      -- Placeholder for FK to Users(UserID) - specific name 'AdviserUserID'
    PRIMARY KEY (GroupID, AdviserUserID)
);

-- I. Enrollments: Tracks which group is in which course/term.
-- Based on ERD: Links to GroupID and CourseID.
CREATE TABLE Enrollments (
    EnrollmentID VARCHAR(10) NOT NULL PRIMARY KEY, -- Changed to VARCHAR to match 'string [pk]'
    GroupID VARCHAR(10) NOT NULL,                  -- Placeholder for FK to Groups(GroupID)
    CourseID VARCHAR(10) NOT NULL,                 -- Placeholder for FK to Courses(CourseID)
    SchoolYear VARCHAR(10),                        -- Matches 'SchoolYear string'
    Semester VARCHAR(10)                           -- Matches 'Semester string'
);

-- J. Proposals: The proposal details.
-- Based on ERD, this links to 'Enrollments', not 'Groups'.
CREATE TABLE Proposals (
    ProposalID VARCHAR(10) NOT NULL PRIMARY KEY,   -- Changed to VARCHAR to match 'string [pk]'
    EnrollmentID VARCHAR(10) NOT NULL,             -- Placeholder for FK to Enrollments(EnrollmentID)
    ResearchTitle VARCHAR(500) UNIQUE,                    -- Matches 'ResearchTitle string'
    SubmissionDate DATE,                           -- Matches 'SubmissionDate date'
    Deadline DATE,                                 -- Matches 'Deadline date'
    Status VARCHAR(20)                             -- Matches 'Status string'
);






