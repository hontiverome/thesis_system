-- 1. Connect GROUPS to USERS (Adviser)
ALTER TABLE `Groups`
ADD CONSTRAINT fk_groups_adviser
FOREIGN KEY (AdviserUserID) REFERENCES Users(UserID)
ON DELETE RESTRICT -- Don't allow deleting a User if they are currently a Group Adviser
ON UPDATE CASCADE;

-- 2. Connect GROUPMEMBERS to GROUPS
ALTER TABLE GroupMembers
ADD CONSTRAINT fk_members_group
FOREIGN KEY (GroupID) REFERENCES `Groups`(GroupID)
ON DELETE CASCADE -- If Group is deleted, the Member list is deleted
ON UPDATE CASCADE;

-- 3. Connect GROUPMEMBERS to USERS (Student)
ALTER TABLE GroupMembers
ADD CONSTRAINT fk_members_student
FOREIGN KEY (StudentUserID) REFERENCES Users(UserID)
ON DELETE CASCADE -- If Student user is deleted, remove them from the group
ON UPDATE CASCADE;

-- 4. Connect GROUPADVISERS to GROUPS
ALTER TABLE GroupAdvisers
ADD CONSTRAINT fk_advisers_group
FOREIGN KEY (GroupID) REFERENCES `Groups`(GroupID)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- 5. Connect GROUPADVISERS to USERS
ALTER TABLE GroupAdvisers
ADD CONSTRAINT fk_advisers_user
FOREIGN KEY (AdviserUserID) REFERENCES Users(UserID)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- 6. Connect ENROLLMENTS to GROUPS
ALTER TABLE Enrollments
ADD CONSTRAINT fk_enrollments_group
FOREIGN KEY (GroupID) REFERENCES `Groups`(GroupID)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- 7. Connect ENROLLMENTS to COURSES (Linking to Dev 1)
ALTER TABLE Enrollments
ADD CONSTRAINT fk_enrollments_course
FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
ON DELETE RESTRICT -- Don't delete a Course if students are enrolled in it
ON UPDATE CASCADE;

-- 8. Connect PROPOSALS to ENROLLMENTS
ALTER TABLE Proposals
ADD CONSTRAINT fk_proposals_enrollment
FOREIGN KEY (EnrollmentID) REFERENCES Enrollments(EnrollmentID)
ON DELETE CASCADE
ON UPDATE CASCADE;
