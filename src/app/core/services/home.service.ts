import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, catchError, throwError, retry } from 'rxjs';
import { HomeStats, UpcomingAuction, Partner } from '../models/home.model';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class HomeService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/home`;

  /**
   * Fetches all static site components (stats, upcoming items, partners) 
   * specifically for the homepage view.
   */
  getHomeData(): Observable<{ stats: HomeStats[], upcoming: UpcomingAuction[], partners: Partner[] }> {
    return this.http.get<any>(this.apiUrl).pipe(
      retry(1),
      catchError(this.handleError)
    );
  }

  private handleError(error: any) {
    console.error('[HomeService Error]:', error);
    return throwError(() => new Error('Failed to load homepage data. Check your API server connection.'));
  }
}
