import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, map } from 'rxjs';
import { environment } from '../../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/admin`;

  // --- Dashboard Data ---
  getDashboardStats(): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/stats`).pipe(
      map(res => res.data || res)
    );
  }

  getRecentActivity(): Observable<any[]> {
    return this.http.get<any>(`${this.apiUrl}/activity`).pipe(
      map(res => res.data || res)
    );
  }

  // --- User Management ---
  getUsers(params?: any): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/users`, { params }).pipe(
      map(res => res.data || res)
    );
  }

  updateUser(id: number, data: any): Observable<any> {
    return this.http.put(`${this.apiUrl}/users/${id}`, data);
  }

  deleteUser(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/users/${id}`);
  }

  // --- Auction Control ---
  getAuctions(params?: any): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/auctions`, { params }).pipe(
      map(res => res.data || res)
    );
  }

  approveAuction(id: number): Observable<any> {
    return this.http.post(`${this.apiUrl}/auctions/${id}/approve`, {});
  }

  rejectAuction(id: number, reason: string): Observable<any> {
    return this.http.post(`${this.apiUrl}/auctions/${id}/reject`, { reason });
  }

  deleteAuction(id: number): Observable<any> {
    return this.http.delete(`${this.apiUrl}/auctions/${id}`);
  }
}
