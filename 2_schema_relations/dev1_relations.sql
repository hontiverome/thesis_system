-- Constraint 1.1: Link UserRoles.UserID to Users.UserID
ALTER TABLE UserRoles
ADD CONSTRAINT fk_userroles_user
FOREIGN KEY (UserID) REFERENCES Users(UserID)
ON DELETE CASCADE -- If a user is deleted, their entries in UserRoles are also deleted.
ON UPDATE CASCADE; -- If a UserID is updated, the corresponding UserRoles entries update.

-- Constraint 1.2: Link UserRoles.RoleID to Roles.RoleID
ALTER TABLE UserRoles
ADD CONSTRAINT fk_userroles_role
FOREIGN KEY (RoleID) REFERENCES Roles(RoleID)
ON DELETE RESTRICT -- Prevent deletion of a Role if it is currently assigned to a user.
ON UPDATE CASCADE;

-- 2. FacultyDetails Table Constraint (One-to-One relationship to Users)

-- Constraint 2.1: Link FacultyDetails.UserID to Users.UserID
ALTER TABLE FacultyDetails
ADD CONSTRAINT fk_facultydetails_user
FOREIGN KEY (UserID) REFERENCES Users(UserID)
ON DELETE CASCADE -- If a faculty user is deleted, their associated FacultyDetails record is also deleted.
ON UPDATE CASCADE;

-- 3. Courses Table Constraint (Self-referencing relationship for prerequisites)

-- Constraint 3.1: Link Courses.PrerequisiteCourseID to Courses.CourseID
ALTER TABLE Courses
ADD CONSTRAINT fk_courses_prereq
FOREIGN KEY (PrerequisiteCourseID) REFERENCES Courses(CourseID)
ON DELETE SET NULL -- If a prerequisite course is deleted, the PrerequisiteCourseID for dependent courses is set to NULL.
ON UPDATE CASCADE;

-- 4. PasswordResets Constraint (Linking to Users)
-- NEW CONSTRAINT ADDED
ALTER TABLE PasswordResets
ADD CONSTRAINT fk_resets_user
FOREIGN KEY (UserID) REFERENCES Users(UserID)
ON DELETE CASCADE -- If user is deleted, delete their reset history
ON UPDATE CASCADE;

