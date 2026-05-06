import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../../../environments/environment';
import { map } from 'rxjs/operators';

export interface AdminUser {
  id: number;
  name: string;
  username: string;
  email: string;
  phone: string | null;
  location: string | null;
  bio: string | null;
  avatar_url: string | null;
  email_verified_at: string | null;
  created_at: string;
  roles: string[];
  status?: string; // Appended by frontend
}

export interface AdminUserResponse {
  data: AdminUser[];
  meta?: any;
  links?: any;
}

@Injectable({
  providedIn: 'root'
})
export class AdminUserService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/admin/users`;

  getUsers(params?: any): Observable<AdminUserResponse> {
    return this.http.get<AdminUserResponse>(this.apiUrl, { params }).pipe(
      map(res => {
        // Enforce parsing status safely natively if backend has soft deletes
        res.data = res.data.map(user => {
          // If a user has a deleted_at in real app we could block them, but we will just simulate status for now.
          user.status = 'Active'; 
          return user;
        });
        return res;
      })
    );
  }

  getUser(id: number): Observable<{ data: AdminUser }> {
    return this.http.get<{ data: AdminUser }>(`${this.apiUrl}/${id}`);
  }

  deleteUser(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/${id}`);
  }

  // Not implemented natively by generic Controller yet, so handled via DELETE
  toggleStatus(id: number, currentStatus: string): Observable<any> {
     // Implement any logic here (e.g., suspend or restore)
     if (currentStatus === 'Active') {
         return this.http.delete(`${this.apiUrl}/${id}`);
     } else {
         return this.http.post(`${this.apiUrl}/${id}/restore`, {});
     }
  }
}
