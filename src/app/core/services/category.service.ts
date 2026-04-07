import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
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

  private handleError(error: HttpErrorResponse): Observable<never> {
    let errorMessage = 'An unexpected error occurred.';

    if (error.error instanceof ErrorEvent) {
      errorMessage = `Network Error: ${error.error.message}`;
    } else if (error.status === 0) {
      errorMessage = 'Backend is not reachable. Please check your internet connection or try again later.';
    } else if (error.status === 401) {
      errorMessage = 'Unauthorized: Please log in to continue.';
    } else if (error.status === 403) {
      errorMessage = 'Forbidden: You do not have permission to perform this action.';
    } else if (error.status === 404) {
      errorMessage = 'Not Found: The requested resource could not be found.';
    } else if (error.status === 422) {
      let validationErrors = '';
      if (error.error && error.error.errors) {
        const errors: any = error.error.errors;
        validationErrors = Object.keys(errors).map(key => errors[key]).join('\n');
      }
      errorMessage = validationErrors || error.error?.message || 'Validation failed. Please check your input.';
    } else if (error.status === 500) {
      errorMessage = 'Server Error: Something went wrong on our end. Please try again later.';
    } else {
      if (typeof error.error === 'string' && error.error.trim().startsWith('<')) {
        errorMessage = 'Invalid response from server. Please try again.';
      } else if (error.error instanceof SyntaxError || (error.error?.message && error.error.message.includes('JSON'))) {
        errorMessage = 'Invalid response format from server. Please try again.';
      } else {
        errorMessage = error.error?.message || error.message || `Unexpected Error: ${error.status}`;
      }
    }

    console.error('[CategoryService Error]:', error);
    return throwError(() => new Error(errorMessage));
  }
}
