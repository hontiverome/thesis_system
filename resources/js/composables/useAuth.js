import { ref, computed } from 'vue';
import axios from 'axios';

// Store the auth instance
let authInstance = null;

// Create the auth instance
function createAuth() {
    const user = ref(window.Laravel?.auth?.user || null);
    const isAuthenticated = computed(() => !!user.value);
    const loading = ref(false);
    const error = ref(null);
    const initialized = ref(false);
    
    // Initialize axios headers with token from localStorage if it exists
    const initAuth = async () => {
        if (initialized.value) {
            return Promise.resolve();
        }
        
        const token = localStorage.getItem('auth_token');
        if (token && !axios.defaults.headers.common['Authorization']) {
            setAuthToken(token);
            try {
                await fetchUser();
            } catch (err) {
                console.error('Failed to fetch user:', err);
                // Clear invalid token
                localStorage.removeItem('auth_token');
                delete axios.defaults.headers.common['Authorization'];
                user.value = null;
            }
        }
        
        initialized.value = true;
        return Promise.resolve();
    };
    
    // Set auth token in axios and localStorage
    const setAuthToken = (token) => {
        localStorage.setItem('auth_token', token);
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    };
    
    // Fetch user data from the server
    const fetchUser = async () => {
        try {
            // ⚡️ FIX: Updated URL to v1
            const response = await axios.get('/api/v1/user');
            user.value = response.data;
        } catch (err) {
            console.error('Failed to fetch user:', err);
            // Clear invalid token
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
            user.value = null;
        }
    };

    // Login method
    const login = async (credentials) => {
        loading.value = true;
        error.value = null;
        
        try {
            await axios.get('/sanctum/csrf-cookie');

            // ⚡️ FIX: Send exactly what the backend error asked for
            const payload = {
                SchoolID: credentials.student_number, // Map 'student_number' to 'SchoolID'
                birth_month: parseInt(credentials.birth_month),
                birth_day: parseInt(credentials.birth_day),
                birth_year: parseInt(credentials.birth_year),
                password: credentials.password,       // Send password too
                device_name: 'web-browser'
            };

            // Post to the STUDENT route
            const response = await axios.post('/api/v1/auth/login/student', payload);
            
            if (!response.data || !response.data.token) {
                throw new Error('No token received from server');
            }
            
            const { token, user: userData } = response.data;
            setAuthToken(token);
            user.value = userData;
            
            return { token, user: userData };

        } catch (err) {
            console.error('Auth error:', err);
            error.value = err.response?.data?.message || 'Login failed.';
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Logout method
    const logout = async () => {
        try {
            // ⚡️ FIX: Updated URL to v1
            await axios.post('/api/v1/auth/logout');
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            // Clear auth state
            user.value = null;
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
        }
    };

    // Check auth status
    const checkAuth = async () => {
        try {
            // ⚡️ FIX: Updated URL to v1
            const response = await axios.get('/api/v1/user');
            user.value = response.data;
            return true;
        } catch (err) {
            user.value = null;
            return false;
        }
    };

    // Initialize auth when the composable is first used
    initAuth();
    
    return {
        user,
        isAuthenticated,
        loading,
        error,
        initialized,
        initAuth,
        login,
        logout,
        checkAuth,
        fetchUser,
        setAuthToken
    };
}

// Export the useAuth function
export function useAuth() {
    // Create the instance if it doesn't exist
    if (!authInstance) {
        authInstance = createAuth();
    }
    return authInstance;
}

// Default export for backward compatibility
export default useAuth;