
-- 1. Create 3 Groups
INSERT INTO `Groups` (GroupID, GroupCode, YearLevel, AdviserUserID) VALUES 
('G1', '3301', 3, 113), -- G1 (MOR) Adviser: Tokyo (113)
('G2', '3302', 3, 115), -- G2 (DP1) Adviser: Clara (115)
('G3', '4401', 4, 113); -- G3 (DP2) Adviser: Tokyo (113)

-- 2. Group Members
INSERT INTO GroupMembers (GroupID, StudentUserID, GroupRole) VALUES 
-- Group 1
('G1', 101, 'Leader'), ('G1', 102, 'Member'), ('G1', 103, 'Member'), ('G1', 104, 'Member'),
-- Group 2
('G2', 105, 'Leader'), ('G2', 106, 'Member'), ('G2', 107, 'Member'), ('G2', 108, 'Member'),
-- Group 3
('G3', 109, 'Leader'), ('G3', 110, 'Member'), ('G3', 111, 'Member'), ('G3', 112, 'Member');

-- 3. Group Advisers (Note: G3 has 2 Advisers)
INSERT INTO GroupAdvisers (GroupID, AdviserUserID) VALUES 
('G1', 113), -- Tokyo
('G2', 115), -- Clara
('G3', 113), ('G3', 114); -- Tokyo (113) & Jose (114)

-- 4. Enrollments
INSERT INTO Enrollments (EnrollmentID, GroupID, CourseID, SchoolYear, Semester) VALUES 
('E1', 'G1', 'C1', '2025-2026', '1st'), -- MOR
('E2', 'G2', 'C2', '2025-2026', '1st'), -- DP1
('E3', 'G3', 'C3', '2025-2026', '2nd'); -- DP2
