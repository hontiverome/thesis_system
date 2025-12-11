-- K. ProposalApprovals: Tracks approval status of proposals.
-- Linked to Proposals(ProposalID) and Users(UserID).
CREATE TABLE ProposalApprovals (
    ApprovalID VARCHAR(10) NOT NULL PRIMARY KEY,   -- Standardized to VARCHAR(20)
    ProposalID VARCHAR(10) NOT NULL,               -- Placeholder for FK to Proposals(ProposalID)
    ApprovedUserID INT NOT NULL,                   -- Placeholder for FK to Users(UserID)
    ApprovalRole VARCHAR(50),
    Status VARCHAR(20),
    Remarks TEXT NULL
);

-- L. Defenses: Schedules for Proposal or Final defenses.
-- Linked to Enrollments(EnrollmentID) and Proposals(ProposalID).
CREATE TABLE Defenses (
    DefenseID VARCHAR(10) NOT NULL PRIMARY KEY,    -- Standardized to VARCHAR(20)
    EnrollmentID VARCHAR(10) NOT NULL,             -- Placeholder for FK to Enrollments(EnrollmentID)
    ProposalID VARCHAR(10) NOT NULL,               -- Placeholder for FK to Proposals(ProposalID)
    DefenseType VARCHAR(50),                       -- e.g., 'Proposal', 'Final'
    Schedule DATETIME,
    OverallVerdict VARCHAR(20)
);

-- M. DefensePanel: Junction table for Panelists.
-- Uses 'PanelistUserID' and links to 'Defenses'.
CREATE TABLE DefensePanel (
    DefenseID VARCHAR(10) NOT NULL,                -- Placeholder for FK to Defenses(DefenseID)
    PanelistUserID INT NOT NULL,                   -- Placeholder for FK to Users(UserID)
    Status VARCHAR(20),
    PRIMARY KEY (DefenseID, PanelistUserID)
);

-- N. DefenseEvaluations: Individual scores/verdicts.
-- Linked to Defenses and Users.
CREATE TABLE DefenseEvaluations (
    EvaluationID VARCHAR(10) NOT NULL PRIMARY KEY, -- Standardized to VARCHAR(20)
    DefenseID VARCHAR(10) NOT NULL,                -- Placeholder for FK to Defenses(DefenseID)
    PanelistUserID INT NOT NULL,                   -- Placeholder for FK to Users(UserID)
    Verdict VARCHAR(50)
);

-- O. Submissions: File uploads for proposals/defenses.
CREATE TABLE Submissions (
    FileID VARCHAR(10) NOT NULL PRIMARY KEY,       -- Standardized to VARCHAR(20)
    ProposalID VARCHAR(10),                        -- Placeholder for FK to Proposals(ProposalID)
    DefenseID VARCHAR(10),                         -- Placeholder for FK to Defenses(DefenseID)
    FileType VARCHAR(50),
    FilePath TEXT NOT NULL,
    UploadedByUserID INT NOT NULL                  -- Placeholder for FK to Users(UserID)
);

-- P. ResearchArchive: Final repository.
-- Linked to Proposals.
CREATE TABLE ResearchArchive (
    ArchiveID VARCHAR(10) NOT NULL PRIMARY KEY,    -- Standardized to VARCHAR(20)
    ProposalID VARCHAR(10) NOT NULL UNIQUE,        -- Placeholder for FK to Proposals(ProposalID)
    AbstractFilePath TEXT,
    FullManuscriptPath TEXT,
    PublishedDate DATE
);
