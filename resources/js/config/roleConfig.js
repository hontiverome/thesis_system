/** resources/js/config/roleConfig.js **/
export const ROLE_METADATA = {
  student: {
    label: 'Student',
    permissions: { canEdit: false, canEvaluate: false, canSubmit: true },
    dashboard: [
      { id: 1, title: 'My Courses', desc: 'Manage enrolled courses', icon: '📚', color: '#4CAF50', path: '/student/courses' },
      { id: 2, title: 'Submissions', desc: 'Track deadlines', icon: '📝', color: '#2196F3', path: '/student/submissions' },
      { id: 3, title: 'Feedback', desc: 'Review adviser comments', icon: '💬', color: '#FF9800', path: '/student/feedback' },
      { id: 4, title: 'Progress', desc: 'Monitor thesis status', icon: '📊', color: '#9C27B0', path: '/student/progress' },
    ]
  },
  adviser: {
    label: 'Adviser',
    permissions: { canEdit: true, canEvaluate: true, canSubmit: false },
    dashboard: [
      { id: 1, title: 'My Advisees', desc: 'Advisee groups', icon: '👨‍🎓', color: '#3F51B5', path: '/adviser/advisees' },
      { id: 2, title: 'Courses', desc: 'Advising courses', icon: '📚', color: '#4CAF50', path: '/adviser/courses' },
      { id: 3, title: 'Submissions', desc: 'Student work reviews', icon: '📝', color: '#2196F3', path: '/adviser/submissions' },
      { id: 4, title: 'Panel Status', desc: 'Review tracking', icon: '📊', color: '#607D8B', path: '/adviser/status' },
    ]
  },
  faculty: {
    label: 'Faculty',
    permissions: { canEdit: false, canEvaluate: true, canSubmit: false },
    dashboard: [
      { id: 1, title: 'My Classes', desc: 'Assigned classes', icon: '📚', color: '#4CAF50', path: '/faculty/courses' },
      { id: 2, title: 'Submissions', desc: 'Review student work', icon: '📝', color: '#2196F3', path: '/faculty/submissions' },
      { id: 3, title: 'Grading', desc: 'Grade & feedback', icon: '✅', color: '#8BC34A', path: '/faculty/grading' },
      { id: 4, title: 'Materials', desc: 'Class resources', icon: '📦', color: '#FFC107', path: '/faculty/materials' },
    ]
  },
  admin: {
    label: 'Administrator',
    permissions: { canEdit: true, canEvaluate: true, canSubmit: false },
    dashboard: [
      { id: 1, title: 'Manage Users', desc: 'User management', icon: '👥', color: '#00BCD4', path: '/admin/users' },
      { id: 2, title: 'Courses', desc: 'Curriculum settings', icon: '📚', color: '#4CAF50', path: '/admin/courses' },
      { id: 3, title: 'System Logs', desc: 'Activity monitoring', icon: '📋', color: '#795548', path: '/admin/logs' },
      { id: 4, title: 'Analytics', desc: 'System reports', icon: '📊', color: '#E91E63', path: '/admin/analytics' },
    ]
  }
};

export const COURSE_TABS = {
  student: ['overview', 'tasks', 'submissions', 'grades'],
  adviser: ['overview', 'advisees', 'reviews', 'grades'],
  faculty: ['overview', 'sections', 'materials', 'grading'],
  admin:   ['overview', 'settings', 'logs']
};

export const COURSE_MAP = {
  mor: 'Methods of Research',
  dp1: 'Project Design 1',
  dp2: 'Project Design 2'
};

export const COMMON_ITEMS = (role) => ({
  id: 99, title: 'Thesis Archive', desc: 'CpE Abstract Library', icon: '📁', color: '#607D8B', path: `/${role}/archive`
});