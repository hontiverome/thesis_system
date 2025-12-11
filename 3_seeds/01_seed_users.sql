-- 1. Insert Roles
INSERT INTO Roles (RoleID, RoleName) VALUES
(1, 'Student'),
(2, 'Faculty'),
(3, 'Research Coordinator'),
(4, 'Administrator');

-- 2. Insert 17 Users (12 Students, 5 Faculty/admin)
INSERT INTO Users (UserID, SchoolID, FullName, Email, BirthDate, PasswordHash) VALUES
-- GROUP 1 STUDENTS (MOR)
(101, '2022-01001-MN-0', 'Leader G1', 's1@gmail.com', '2003-01-01', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(102, '2022-01002-MN-0', 'Member G1-A', 's2@gmail.com', '2003-01-02', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(103, '2022-01003-MN-0', 'Member G1-B', 's3@gmail.com', '2003-01-03', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(104, '2022-01004-MN-0', 'Member G1-C', 's4@gmail.com', '2003-01-04', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),

-- GROUP 2 STUDENTS (DP1)
(105, '2022-02001-MN-0', 'Leader G2', 's5@gmail.com', '2003-02-01', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(106, '2022-02002-MN-0', 'Member G2-A', 's6@gmail.com', '2003-02-02', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(107, '2022-02003-MN-0', 'Member G2-B', 's7@gmail.com', '2003-02-03', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(108, '2022-02004-MN-0', 'Member G2-C', 's8@gmail.com', '2003-02-04', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),

-- GROUP 3 STUDENTS (DP2)
(109, '2022-03001-MN-0', 'Leader G3', 's9@gmail.com', '2003-03-01', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(110, '2022-03002-MN-0', 'Member G3-A', 's10@gmail.com', '2003-03-02', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(111, '2022-03003-MN-0', 'Member G3-B', 's11@gmail.com', '2003-03-03', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),
(112, '2022-03004-MN-0', 'Member G3-C', 's12@gmail.com', '2003-03-04', '$2y$10$vI8aWBnW3fID.ZQ4/zo1G.q1lRps.9cGLcZEiGDMVr5yUP1KUOYTa'),

-- FACULTY & ADMIN (IDs 112+)
(113, '2010-00001-FA-0', 'Prof. Tokyo Athena', 'tokyo@gmail.com', NULL, '$2y$10$dummyhash'), -- Full-Time, Coordinator
(114, '2010-00002-FA-0', 'Prof. Jose Rizal', 'jose@gmail.com', NULL, '$2y$10$dummyhash'),    -- Part-Time
(115, '2010-00003-FA-0', 'Prof. Clara Oswald', 'clara@gmail.com', NULL, '$2y$10$dummyhash'),  -- Full-Time
(116, '2010-00004-FA-0', 'Prof. Sherlock', 'sherlock@gmail.com', NULL, '$2y$10$dummyhash'),  -- Part-Time
(117, '2000-00000-AD-0', 'Admin User', 'admin@gmail.com', NULL, '$2y$10$dummyhash');          -- Admin

-- 3. Assign Roles
INSERT INTO UserRoles (UserID, RoleID) VALUES 
-- Students
(101,1), (102,1), (103,1), (104,1), 
(105,1), (106,1), (107,1), (108,1), 
(109,1), (110,1), (111,1), (112,1),
-- Faculty
(113,2), (113,3), -- Tokyo (Faculty + Coordinator)
(114,2),          -- Jose (Faculty)
(115,2),          -- Clara (Faculty)
(116,2),          -- Sherlock (Faculty)
-- Admin
(117,4);          -- Admin

-- 4. Faculty Details
INSERT INTO FacultyDetails (FacultyDetailID, UserID, FacultyType) VALUES 
('F1', 113, 'Full-Time'), 
('F2', 114, 'Part-Time'), 
('F3', 115, 'Full-Time'), 
('F4', 116, 'Part-Time');

-- 5. Password Resets
INSERT INTO PasswordResets (UserID, ResetToken, CreatedAt, ExpiresAt, IsUsed) VALUES
(101, 'dummy_token_123', '2025-10-25 08:00:00', '2025-10-25 09:00:00', FALSE);


