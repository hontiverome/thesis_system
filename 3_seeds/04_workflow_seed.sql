
-- 1. Proposals
INSERT INTO Proposals (ProposalID, EnrollmentID, ResearchTitle, SubmissionDate, Deadline, Status) VALUES 
('P1', 'E1', 'G1 MOR Study', '2025-10-24', '2025-10-30', 'Pending'),   -- G1
('P2', 'E2', 'G2 DP1 System', '2025-05-20', '2025-05-30', 'Approved'),  -- G2 (History)
('P3', 'E3', 'G3 DP2 Finale', '2024-10-24', '2024-10-30', 'Approved');  -- G3 (History)

-- 2. Approvals
INSERT INTO ProposalApprovals (ApprovalID, ProposalID, ApprovedUserID, ApprovalRole, Status) VALUES 
('A1', 'P2', 115, 'Adviser', 'Approved'), -- Approved by Clara (115)
('A2', 'P3', 113, 'Adviser', 'Approved'); -- Approved by Tokyo (113)

-- 3. Defenses (With History)
INSERT INTO Defenses (DefenseID, EnrollmentID, ProposalID, DefenseType, Schedule, OverallVerdict) VALUES 
-- Group 2 History
('D_G2_MOR', 'E2', 'P2', 'MOR', '2025-06-15 09:00:00', 'Defended'),       

-- Group 3 History
('D_G3_MOR', 'E3', 'P3', 'MOR', '2024-12-15 09:00:00', 'Defended'),
('D_G3_DP1', 'E3', 'P3', 'DP1', '2025-06-15 09:00:00', 'Defended'),
('D_G3_DP2', 'E3', 'P3', 'DP2', '2026-03-15 09:00:00', 'Defended');       

-- 4. Defense Panel (Uses updated Faculty IDs)
INSERT INTO DefensePanel (DefenseID, PanelistUserID, Status) VALUES 
('D_G2_MOR', 113, 'Accepted'), ('D_G2_MOR', 114, 'Accepted'), -- Tokyo (113) & Jose (114)
('D_G3_DP2', 115, 'Accepted'), ('D_G3_DP2', 116, 'Accepted'); -- Clara (115) & Sherlock (116)
-- 5. Submissions (Files)
INSERT INTO Submissions (FileID, ProposalID, DefenseID, FileType, FilePath, UploadedByUserID) VALUES 
-- G1
('F1', 'P1', NULL, 'Proposal', '/uploads/g1_proposal.pdf', 101),
-- G2
('F2_OLD', 'P2', 'D_G2_MOR', 'Manuscript_MOR', '/uploads/g2_mor_manuscript.pdf', 105),
-- G3
('F3_OLD1', 'P3', 'D_G3_MOR', 'Manuscript_MOR', '/uploads/g3_mor_manuscript.pdf', 109),
('F3_OLD2', 'P3', 'D_G3_DP1', 'Manuscript_DP1', '/uploads/g3_dp1_manuscript.pdf', 109),
('F3_NOW1', 'P3', 'D_G3_DP2', 'Co-ownership', '/uploads/g3_co_own.pdf', 109),
('F3_NOW2', 'P3', 'D_G3_DP2', 'Tech Transfer', '/uploads/g3_tech.pdf', 109),
('F3_NOW3', 'P3', 'D_G3_DP2', 'Copyright Patent', '/uploads/g3_copy.pdf', 109);

