/**
 * Defines the user structure for both Authentication and Profiles.
 */
export interface User {
  id: string | number;
  firstName: string;
  lastName: string;
  email: string;
  avatar?: string;
  role: 'user' | 'admin';
  isVerified: boolean;
  phone?: string;
  address?: string;
}

/**
 * Defines the structure for Auth responses (Login/Register).
 */
export interface AuthResponse {
  token: string;
  user: User;
}
