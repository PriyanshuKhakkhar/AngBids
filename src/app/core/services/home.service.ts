import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, catchError, throwError, forkJoin, map } from 'rxjs';
import { HomeStats, UpcomingAuction } from '../models/home.model';

@Injectable({
  providedIn: 'root'
})
export class HomeService {
  private http = inject(HttpClient);
  private apiUrl = 'http://localhost:8000/api/home';

  /**
   * Fetches all static site components (stats, upcoming items) 
   * specifically for the homepage view.
   */
  getHomeData(): Observable<{ stats: HomeStats[], upcoming: UpcomingAuction[] }> {
    return this.http.get<any>(this.apiUrl).pipe(
      catchError(err => throwError(() => new Error('Failed to load homepage data')))
    );
  }
}
