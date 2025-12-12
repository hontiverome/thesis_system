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
        console.log('initAuth: token from localStorage:', token ? 'exists' : 'none');
        
        if (token && !axios.defaults.headers.common['Authorization']) {
            setAuthToken(token);
            try {
                console.log('initAuth: fetching user...');
                await fetchUser();
                console.log('initAuth: user fetched successfully');
            } catch (err) {
                console.error('Failed to fetch user:', err);
                console.log('initAuth: clearing invalid token');
                // Clear invalid token
                localStorage.removeItem('auth_token');
                delete axios.defaults.headers.common['Authorization'];
                user.value = null;
            }
        } else {
            console.log('initAuth: no token or already has authorization header');
        }
        
        initialized.value = true;
        console.log('initAuth: initialized, isAuthenticated:', isAuthenticated.value);
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
            console.log('fetchUser: calling /api/v1/users/me');
            const response = await axios.get('/api/v1/users/me');
            console.log('fetchUser: response received:', response.data);
            user.value = response.data; // Fix: user data is directly in response.data
            console.log('fetchUser: user set:', user.value);
        } catch (err) {
            console.error('Failed to fetch user:', err);
            console.log('fetchUser: error response:', err.response?.data);
            console.log('fetchUser: error status:', err.response?.status);
            // Clear invalid token
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
            user.value = null;
        }
    };

    // Student login method
    const studentLogin = async (credentials) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await axios.post('/api/v1/auth/login/student', {
                ...credentials,
                device_name: 'web-browser'
            });
            
            if (!response.data || !response.data.token) {
                throw new Error('No token received from server');
            }
            
            // Store the token and user data
            const { token, user: userData, redirect_to } = response.data;
            
            // Set the token in axios and localStorage
            setAuthToken(token);
            
            // Update the user object
            user.value = userData;
            
            // Return the response data for the component to use
            return { token, user: userData, redirect_to };
        } catch (err) {
            console.error('Student auth error:', err);
            console.log('Full error response:', err.response?.data);
            
            // Handle different error statuses
            if (err.response) {
                // Server responded with a status code outside 2xx
                if (err.response.status === 422) {
                    // Log the full response for debugging
                    console.log('422 Response data:', err.response.data);
                    
                    // Handle different 422 response formats
                    const responseData = err.response.data;
                    
                    // Laravel validation errors
                    if (responseData.errors) {
                        error.value = Object.values(responseData.errors).flat().join(' ');
                    } 
                    // Laravel validation message
                    else if (responseData.message) {
                        error.value = responseData.message;
                    }
                    // Fallback for other 422 responses
                    else {
                        error.value = 'Invalid input. Please check your student number, birth date, and password.';
                    }
                } else if (err.response.status === 401) {
                    error.value = 'Invalid student number, birth date, or password. Please try again.';
                } else if (err.response.data?.message) {
                    error.value = err.response.data.message;
                } else {
                    error.value = `Server error (${err.response.status}): ${err.response.statusText}`;
                }
            } else if (err.request) {
                // Request was made but no response received
                error.value = 'Unable to connect to the server. Please check your internet connection.';
            } else {
                // Something happened in setting up the request
                error.value = 'An unexpected error occurred. Please try again.';
            }
            
            // Re-throw the error so the calling component can handle it if needed
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Faculty login method
    const facultyLogin = async (credentials) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await axios.post('/api/v1/auth/login/faculty', {
                ...credentials,
                device_name: 'web-browser'
            });
            
            if (!response.data || !response.data.token) {
                throw new Error('No token received from server');
            }
            
            // Store the token and user data
            const { token, user: userData, redirect_to } = response.data;
            
            // Set the token in axios and localStorage
            setAuthToken(token);
            
            // Update the user object
            user.value = userData;
            
            // Return the response data for the component to use
            return { token, user: userData, redirect_to };
        } catch (err) {
            console.error('Faculty auth error:', err);
            console.log('Full error response:', err.response?.data);
            
            // Handle different error statuses
            if (err.response) {
                // Server responded with a status code outside 2xx
                if (err.response.status === 422) {
                    // Log the full response for debugging
                    console.log('422 Response data:', err.response.data);
                    
                    // Handle different 422 response formats
                    const responseData = err.response.data;
                    
                    // Laravel validation errors
                    if (responseData.errors) {
                        error.value = Object.values(responseData.errors).flat().join(' ');
                    } 
                    // Laravel validation message
                    else if (responseData.message) {
                        error.value = responseData.message;
                    }
                    // Fallback for other 422 responses
                    else {
                        error.value = 'Invalid input. Please check your faculty ID and password.';
                    }
                } else if (err.response.status === 401) {
                    error.value = 'Invalid faculty ID or password. Please try again.';
                } else if (err.response.data?.message) {
                    error.value = err.response.data.message;
                } else {
                    error.value = `Server error (${err.response.status}): ${err.response.statusText}`;
                }
            } else if (err.request) {
                // Request was made but no response received
                error.value = 'Unable to connect to the server. Please check your internet connection.';
            } else {
                // Something happened in setting up the request
                error.value = 'An unexpected error occurred. Please try again.';
            }
            
            // Re-throw the error so the calling component can handle it if needed
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Legacy login method for backward compatibility
    const login = async (credentials) => {
        loading.value = true;
        error.value = null;
        
        try {
            const response = await axios.post('/api/login', {
                ...credentials,
                device_name: 'web-browser'
            });
            
            if (!response.data || !response.data.token) {
                throw new Error('No token received from server');
            }
            
            // Store the token and user data
            const { token, user: userData } = response.data;
            
            // Set the token in axios and localStorage
            setAuthToken(token);
            
            // Update the user object
            user.value = userData;
            
            // Return the response data for the component to use
            return { token, user: userData };
        } catch (err) {
            console.error('Auth error:', err);
            console.log('Full error response:', err.response?.data);
            
            // Handle different error statuses
            if (err.response) {
                // Server responded with a status code outside 2xx
                if (err.response.status === 422) {
                    // Log the full response for debugging
                    console.log('422 Response data:', err.response.data);
                    
                    // Handle different 422 response formats
                    const responseData = err.response.data;
                    
                    // Laravel validation errors
                    if (responseData.errors) {
                        error.value = Object.values(responseData.errors).flat().join(' ');
                    } 
                    // Laravel validation message
                    else if (responseData.message) {
                        error.value = responseData.message;
                    }
                    // Fallback for other 422 responses
                    else {
                        error.value = 'Invalid input. Please check your email and password.';
                    }
                } else if (err.response.status === 401) {
                    error.value = 'Invalid email or password. Please try again.';
                } else if (err.response.data?.message) {
                    error.value = err.response.data.message;
                } else {
                    error.value = `Server error (${err.response.status}): ${err.response.statusText}`;
                }
            } else if (err.request) {
                // Request was made but no response received
                error.value = 'Unable to connect to the server. Please check your internet connection.';
            } else {
                // Something happened in setting up the request
                error.value = 'An unexpected error occurred. Please try again.';
            }
            
            // Re-throw the error so the calling component can handle it if needed
            throw err;
        } finally {
            loading.value = false;
        }
    };

    // Logout method
    const logout = async () => {
        try {
            await axios.post('/api/v1/auth/logout');
        } catch (err) {
            console.error('Logout error:', err);
        } finally {
            // Clear auth state
            user.value = null;
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
            
            // Redirect to landing page
            window.location.href = '/';
        }
    };

    // Check auth status
    const checkAuth = async () => {
        try {
            const response = await axios.get('/api/v1/users/me');
            user.value = response.data; // Fix: user data is directly in response.data
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
        studentLogin,
        facultyLogin,
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
