import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse } from '@angular/common/http';
import { Observable, throwError, retry } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { Auction } from '../models/auction.model';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuctionService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/auctions`;
  private baseUrl = 'http://127.0.0.1:8000'; // Base for image normalization

  /**
   * Fetch all auctions from the Laravel API.
   */
  getAuctions(): Observable<Auction[]> {
    return this.http.get<any>(this.apiUrl).pipe(
      retry(1),
      map(response => {
        const data = response.data || response;
        return Array.isArray(data) ? data.map(item => this.mapToModel(item)) : [];
      }),
      catchError(this.handleError)
    );
  }

  /**
   * Fetch a single auction by its ID.
   */
  getAuctionById(id: string | number): Observable<Auction> {
    return this.http.get<any>(`${this.apiUrl}/${id}`).pipe(
      retry(1),
      map(response => this.mapToModel(response.data || response)),
      catchError(this.handleError)
    );
  }

  /**
   * Normalizes raw API data into a clean, consistent Auction model.
   */
  private mapToModel(raw: any): Auction {
    // 1. Smart Image URL Resolution
    const placeholder = `${this.baseUrl}/assets/images/banner-3.png`;
    let imageUrl = placeholder;
    const rawImage = raw.imageUrl || (raw.images && raw.images.length > 0 ? (raw.images[0].url || raw.images[0]) : null);
    
    if (rawImage && typeof rawImage === 'string' && rawImage.trim() !== '') {
      // Handle malformed/escaped URLs like "storage/https://remote.com/img.jpg"
      if (rawImage.includes('http') && !rawImage.startsWith('http')) {
        const urlMatch = rawImage.match(/https?:\/\/[^\s]+/);
        if (urlMatch) {
          imageUrl = urlMatch[0];
        }
      } 
      else if (rawImage.startsWith('http')) {
        imageUrl = rawImage;
      } 
      else {
        // Handle local paths correctly
        const path = rawImage.startsWith('/') ? rawImage.substring(1) : rawImage;
        
        // Differentiate between storage assets and public assets
        if (path.startsWith('assets/')) {
          imageUrl = `${this.baseUrl}/${path}`;
        } else {
          // Normalize to storage/
          const finalPath = path.startsWith('storage/') ? path : `storage/${path}`;
          imageUrl = `${this.baseUrl}/${finalPath}`;
        }
      }
    }

    // 2. Map to Unified Model
    return {
      id: raw.id,
      title: raw.title || 'Elite Asset',
      description: raw.description || 'Premium details are currently being updated for this exclusive listing.',
      imageUrl: imageUrl,
      currentBid: raw.current_price || raw.currentBid || 0,
      startingPrice: raw.starting_price || raw.startingPrice || 0,
      endDate: raw.end_date || raw.endDate || raw.end_time || '',
      status: raw.status || 'active',
      category: raw.category?.name || raw.category || 'Luxury Collection'
    };
  }

  /**
   * Handle API errors gracefully.
   */
  private handleError(error: HttpErrorResponse) {
    let errorMessage = '';
    
    if (error.error instanceof ErrorEvent) {
      // Client-side/network error
      errorMessage = `Connection Error: ${error.error.message}`;
    } else {
      // Server-side error
      errorMessage = `Server Error [${error.status}]: ${error.message || 'The server returned an error.'}`;
    }
    
    console.error(`[AuctionService API Error]: ${errorMessage}`, error);
    return throwError(() => new Error(errorMessage));
  }
}
