/**
 * API Client for interacting with the Laravel API endpoints
 */
class ApiClient {
    /**
     * Base URL for API requests
     * @type {string}
     */
    static BASE_URL = '/api';

    /**
     * Get the authentication token from local storage
     * @returns {string|null}
     */
    static getToken() {
        return localStorage.getItem('api_token');
    }

    /**
     * Set the authentication token in local storage
     * @param {string} token - The authentication token
     */
    static setToken(token) {
        localStorage.setItem('api_token', token);
    }

    /**
     * Create the headers for API requests
     * @param {boolean} includeAuth - Whether to include authentication header
     * @returns {Object} - Headers object
     */
    static createHeaders(includeAuth = true) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        };

        if (includeAuth) {
            const token = this.getToken();
            if (token) {
                headers['Authorization'] = `Bearer ${token}`;
            }
        }

        return headers;
    }

    /**
     * Make an API request
     * @param {string} endpoint - API endpoint
     * @param {string} method - HTTP method
     * @param {Object} data - Request data
     * @param {boolean} includeAuth - Whether to include authentication
     * @returns {Promise<Object>} - Response data
     */
    static async request(endpoint, method = 'GET', data = null, includeAuth = true) {
        const url = `${this.BASE_URL}${endpoint}`;
        const headers = this.createHeaders(includeAuth);

        const options = {
            method,
            headers
        };

        if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
            options.body = JSON.stringify(data);
        }

        try {
            const response = await fetch(url, options);
            const responseData = await response.json();

            if (!response.ok) {
                throw {
                    status: response.status,
                    message: responseData.message || 'An error occurred',
                    errors: responseData.errors || {}
                };
            }

            return responseData;
        } catch (error) {
            console.error('API request error:', error);
            throw error;
        }
    }

    /**
     * Register a new user
     * @param {Object} userData - User registration data
     * @returns {Promise<Object>} - Response data
     */
    static async register(userData) {
        return await this.request('/register', 'POST', userData, false);
    }

    /**
     * Login a user
     * @param {Object} credentials - Login credentials
     * @returns {Promise<Object>} - Response data with auth token
     */
    static async login(credentials) {
        const response = await this.request('/login', 'POST', credentials, false);
        if (response.data && response.data.access_token) {
            this.setToken(response.data.access_token);
            localStorage.setItem('user', JSON.stringify(response.data.user));
        }
        return response;
    }

    /**
     * Logout the current user
     * @returns {Promise<Object>} - Response data
     */
    static async logout() {
        return await this.request('/logout', 'POST');
    }

    /**
     * Get the current authenticated user
     * @returns {Promise<Object>} - User data
     */
    static async getCurrentUser() {
        return await this.request('/user');
    }

    /**
     * Get dashboard statistics
     * @returns {Promise<Object>} - Dashboard data
     */
    static async getDashboard() {
        return await this.request('/dashboard');
    }

    // Area CRUD operations
    static async getAreas() {
        return await this.request('/areas');
    }

    static async getArea(id) {
        return await this.request(`/areas/${id}`);
    }

    static async createArea(data) {
        return await this.request('/areas', 'POST', data);
    }

    static async updateArea(id, data) {
        return await this.request(`/areas/${id}`, 'PUT', data);
    }

    static async deleteArea(id) {
        return await this.request(`/areas/${id}`, 'DELETE');
    }

    // Hospital CRUD operations
    static async getHospitals() {
        return await this.request('/hospitals');
    }

    static async getHospital(id) {
        return await this.request(`/hospitals/${id}`);
    }

    static async createHospital(data) {
        return await this.request('/hospitals', 'POST', data);
    }

    static async updateHospital(id, data) {
        return await this.request(`/hospitals/${id}`, 'PUT', data);
    }

    static async deleteHospital(id) {
        return await this.request(`/hospitals/${id}`, 'DELETE');
    }

    // PHSS CRUD operations
    static async getPhssList() {
        return await this.request('/phss');
    }

    static async getPhss(id) {
        return await this.request(`/phss/${id}`);
    }

    static async createPhss(data) {
        return await this.request('/phss', 'POST', data);
    }

    static async updatePhss(id, data) {
        return await this.request(`/phss/${id}`, 'PUT', data);
    }

    static async deletePhss(id) {
        return await this.request(`/phss/${id}`, 'DELETE');
    }

    // Customer CRUD operations
    static async getCustomers() {
        return await this.request('/customers');
    }

    static async getCustomer(id) {
        return await this.request(`/customers/${id}`);
    }

    static async createCustomer(data) {
        return await this.request('/customers', 'POST', data);
    }

    static async updateCustomer(id, data) {
        return await this.request(`/customers/${id}`, 'PUT', data);
    }

    static async deleteCustomer(id) {
        return await this.request(`/customers/${id}`, 'DELETE');
    }

    // User CRUD operations
    static async getUsers() {
        return await this.request('/users');
    }

    static async getUser(id) {
        return await this.request(`/users/${id}`);
    }

    static async createUser(data) {
        return await this.request('/users', 'POST', data);
    }

    static async updateUser(id, data) {
        return await this.request(`/users/${id}`, 'PUT', data);
    }

    static async deleteUser(id) {
        return await this.request(`/users/${id}`, 'DELETE');
    }
} 