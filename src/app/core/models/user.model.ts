/**
 * Defines the user structure for both Authentication and Profiles.
 */
export interface User {
  id: string | number;
  firstName: string;
  lastName: string;
  email: string;
  name?: string;
  avatar?: string;
  roles?: string[];
  role?: string;
  is_admin?: boolean;
  isVerified?: boolean;
  phone?: string;
  address?: string;
  kyc_status?: 'pending' | 'submitted' | 'approved' | 'rejected' | null;
}

/**
 * Defines the structure for Auth responses (Login/Register).
 */
export interface AuthResponse {
  token: string;
  user: User;
  message?: string;
  success?: boolean;
}

/**
 * Defines the structure for the initial Registration response (pending OTP).
 */
export interface RegisterResponse {
  success: boolean;
  message: string;
  email: string;
}
