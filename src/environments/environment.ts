/**
 * Global application environment configuration.
 * Centralizes the API base URL to prevent mismatches across services.
 * 
 * Using relative path '/api' with Angular proxy configuration.
 * Proxy will forward /api requests to http://127.0.0.1:8000/api
 */
export const environment = {
  production: false,
  apiUrl: '/api'
};
