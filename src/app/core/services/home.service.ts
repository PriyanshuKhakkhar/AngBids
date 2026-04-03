import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, catchError, throwError, retry } from 'rxjs';
import { HomeStats, UpcomingAuction, Partner } from '../models/home.model';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class HomeService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/home`;

  getHomeData(): Observable<{ stats: HomeStats[], upcoming: UpcomingAuction[], partners: Partner[] }> {
    return this.http.get<any>(this.apiUrl).pipe(
      retry(1),
      catchError(this.handleError)
    );
  }

  private handleError(error: HttpErrorResponse) {
    const message = error.error?.message || error.message || 'Failed to load homepage data.';
    console.error('[HomeService Error]:', error);
    return throwError(() => new Error(message));
  }
}
