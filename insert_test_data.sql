-- Create Groups
INSERT IGNORE INTO `Groups` (GroupID, GroupCode, YearLevel) VALUES
('GRP-001', 'CS-G1', 4),
('GRP-002', 'CS-G2', 4);

-- Assign advisers
INSERT IGNORE INTO GroupAdvisers (GroupID, AdviserUserID) VALUES
('GRP-001', 1),
('GRP-002', 2);

-- Add group members
INSERT IGNORE INTO GroupMembers (GroupID, StudentUserID, GroupRole) VALUES
('GRP-001', 4, 'Leader'),
('GRP-001', 5, 'Member'),
('GRP-001', 6, 'Member'),
('GRP-002', 7, 'Leader'),
('GRP-002', 8, 'Member'),
('GRP-002', 9, 'Member');

-- Create course
INSERT IGNORE INTO Courses (CourseID, CourseName) VALUES
('CS-001', 'Computer Science Research');

-- Create enrollments
INSERT IGNORE INTO Enrollments (EnrollmentID, GroupID, CourseID, SchoolYear, Semester) VALUES
('ENR-001', 'GRP-001', 'CS-001', '2025-2026', 1),
('ENR-002', 'GRP-002', 'CS-001', '2025-2026', 1);

-- Create proposals
INSERT IGNORE INTO Proposals (EnrollmentID, ResearchTitle, SubmissionDate, Deadline, Status) VALUES
('ENR-001', 'Machine Learning for Predictive Analytics', '2026-01-10', '2026-01-31', 'Pending'),
('ENR-002', 'Blockchain Technology in Healthcare Systems', '2026-01-12', '2026-01-31', 'Pending');

-- Create defenses
INSERT IGNORE INTO Defenses (DefenseID, EnrollmentID, ProposalID, DefenseType, Schedule, OverallVerdict) VALUES
('DEF-001', 'ENR-001', 1, 'Proposal Defense', '2026-02-15 10:00:00', 'Pending'),
('DEF-002', 'ENR-002', 2, 'Proposal Defense', '2026-02-16 14:00:00', 'Pending');

-- Create panel invitations
INSERT IGNORE INTO DefensePanel (DefenseID, PanelistUserID, Status) VALUES
('DEF-001', 1, 'Pending'),
('DEF-001', 2, 'Pending'),
('DEF-002', 2, 'Accepted'),
('DEF-002', 3, 'Pending');

SELECT 'Test data created successfully!' as Result;
