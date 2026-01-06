import { storeToRefs } from 'pinia';
import axios from 'axios';
import { useUserStore } from '@/stores/user';
import { ref } from 'vue';

let authInstance = null;

function createAuth() {
    const store = useUserStore();
    const { 
        user, 
        isAuthenticated,
        isAdmin,
        isStudent,
        isFaculty,
        isAdviser,
        isResearchCoordinator 
    } = storeToRefs(store);
    const loading = ref(false);
    const error = ref(null);

    // --- 1. AUTHORIZATION LOGIC ---
    const isAuthorized = (requiredRoles) => {
        if (!store.user) return false;
        
        if (Array.isArray(requiredRoles)) {
            return requiredRoles.some(role => store.hasRole(role));
        }
        return store.hasRole(requiredRoles);
    };

    // Check if user has a specific permission (wraps store.can)
    // Also returns true if user is Admin (handled by store)
    const can = (permission) => {
        return store.can(permission);
    };

    const checkRole = isAuthorized;

    // --- 2. FETCH USER ---
    const fetchUser = async () => {
        try {
            const response = await axios.get('/api/v1/user');
            store.setUser(response.data);
            return true;
        } catch (err) {
            store.clearUser();
            return false;
        }
    };

    // --- 3. CHECK AUTH ---
    const checkAuth = async () => {
        if (store.isAuthenticated) return true;

        const token = localStorage.getItem('auth_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            return await fetchUser();
        }

        return false;
    };

    // --- 4. LOGIN ---
    const login = async (credentials) => {
        loading.value = true;
        error.value = null;
        
        try {
            await axios.get('/sanctum/csrf-cookie');

            const payload = {
                SchoolID: credentials.student_number, 
                birth_month: parseInt(credentials.birth_month),
                birth_day: parseInt(credentials.birth_day),
                birth_year: parseInt(credentials.birth_year),
                password: credentials.password,
                device_name: 'web-browser'
            };

            const response = await axios.post('/api/v1/auth/login/student', payload);
            
            if (!response.data.token) throw new Error('No token received');

            store.setToken(response.data.token);
            store.setUser(response.data.user);
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;

            return { success: true, user: response.data.user };

        } catch (err) {
            console.error('Login error:', err);
            if (err.response?.status === 422) {
                error.value = err.response.data.message || 'Invalid credentials.';
            } else {
                error.value = err.response?.data?.message || 'Login failed.';
            }
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // --- 5. LOGOUT ---
    const logout = async () => {
        try {
            await axios.post('/api/v1/auth/logout');
        } catch (e) {
            console.warn('Logout server error:', e);
        } finally {
            store.clearUser();
            delete axios.defaults.headers.common['Authorization'];
        }
    };

    // --- 6. INIT AUTH (Fixes the App Crash) ---
    const initAuth = async () => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            // We can optionally fetch the user here if needed, 
            // but usually checkAuth() handles that in the router.
        }
    };
    
    // Auto-run init logic
    initAuth();

    return {
        // State
        user, loading, error, isAuthenticated,
        isAdmin, isStudent, isFaculty, isAdviser, isResearchCoordinator, // <--- ADDED
        
        // Methods
        login, 
        logout,
        checkRole,     // <--- UPDATED
        isAuthorized,  // <--- UPDATED
        can,           // <--- ADDED
        checkAuth, 
        fetchUser,
        initAuth
    };
}

export function useAuth() {
    if (!authInstance) authInstance = createAuth();
    return authInstance;
}
export default useAuth;