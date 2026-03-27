import { Injectable, inject } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable, catchError, throwError, retry } from 'rxjs';
import { Category } from '../models/home.model';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class CategoryService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/categories`;

  getCategories(): Observable<Category[]> {
    return this.http.get<Category[]>(this.apiUrl).pipe(
      retry(1),
      catchError(this.handleError)
    );
  }

  private handleError(error: any) {
    console.error('[CategoryService Error]:', error);
    return throwError(() => new Error('Failed to load categories. Please check your connection.'));
  }
}
