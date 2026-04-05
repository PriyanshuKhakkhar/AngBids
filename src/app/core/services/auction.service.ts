import { Injectable, inject } from '@angular/core';
import { HttpClient, HttpErrorResponse, HttpParams } from '@angular/common/http';
import { Observable, throwError, retry } from 'rxjs';
import { catchError, map } from 'rxjs/operators';
import { Auction, Bid } from '../models/auction.model';
import { environment } from '../../../environments/environment';

@Injectable({
  providedIn: 'root'
})
export class AuctionService {
  private http = inject(HttpClient);
  private apiUrl = `${environment.apiUrl}/auctions`;
  private baseUrl = ''; // For image URLs - using relative paths

  /**
   * Fetch all auctions from the Laravel API with optional filtering.
   */
  getAuctions(queryParams?: {
    category?: number | null;
    minPrice?: number | null;
    maxPrice?: number | null;
    sort?: string | null;
    page?: number;
  }): Observable<{ data: Auction[], current_page?: number, last_page?: number }> {
    
    let params = new HttpParams();
    
    if (queryParams) {
      if (queryParams.category) params = params.set('category', queryParams.category.toString());
      if (queryParams.minPrice !== undefined && queryParams.minPrice !== null) params = params.set('minPrice', queryParams.minPrice.toString());
      if (queryParams.maxPrice !== undefined && queryParams.maxPrice !== null) params = params.set('maxPrice', queryParams.maxPrice.toString());
      if (queryParams.sort) params = params.set('sort', queryParams.sort);
      if (queryParams.page) params = params.set('page', queryParams.page.toString());
    }

    return this.http.get<any>(this.apiUrl, { params }).pipe(
      retry(1),
      map(response => {
        // Handle paginated or non-paginated data
        const rawData = response.data || response;
        const parsedData = Array.isArray(rawData) ? rawData.map(item => this.mapToModel(item)) : [];
        
        return {
          data: parsedData,
          current_page: response.current_page || 1,
          last_page: response.last_page || 1
        };
      }),
      catchError(this.handleError)
    );
  }

  /**
   * Place a bid on an auction.
   */
  placeBid(auctionId: number, amount: number): Observable<{ message: string; bid: Bid }> {
    return this.http.post<{ message: string; bid: Bid }>(
      `${this.apiUrl}/${auctionId}/bids`,
      { amount }
    ).pipe(catchError(this.handleError));
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
    const placeholder = '/assets/images/banner-3.png';
    let imageUrl = placeholder;
    const rawImage = raw.imageUrl || raw.image || (raw.images && raw.images.length > 0 ? (raw.images[0].url || raw.images[0]) : null);
    
    if (rawImage && typeof rawImage === 'string' && rawImage.trim() !== '') {
      // Handle malformed/escaped URLs like "http://127.0.0.1:8000/storage/https://remote.com/img.jpg"
      const lastHttpIndex = rawImage.lastIndexOf('http');
      
      if (lastHttpIndex > 0) {
        // Extract the nested external URL
        imageUrl = rawImage.substring(lastHttpIndex);
      } 
      else if (rawImage.startsWith('http')) {
        // Clean absolute URL
        imageUrl = rawImage;
      } 
      else {
        // Handle local paths - these will be served by Laravel backend
        // Since we're using proxy, we don't need to modify the path
        imageUrl = rawImage.startsWith('/') ? rawImage : `/${rawImage}`;
      }
    }

    // Map to Unified Model
    return {
      id: raw.id,
      title: raw.title || 'Elite Asset',
      description: raw.description || 'Premium details are currently being updated for this exclusive listing.',
      imageUrl,
      currentBid: raw.current_price ?? raw.currentBid ?? 0,
      startingPrice: raw.starting_price ?? raw.startingPrice ?? 0,
      endDate: raw.end_date ?? raw.endDate ?? raw.end_time ?? '',
      status: raw.status ?? 'active',
      category: raw.category?.name ?? raw.category ?? 'Luxury Collection',
      bidCount: raw.bid_count ?? raw.bids_count ?? raw.bids?.length ?? 0,
      sellerName: raw.seller?.name ?? raw.user?.name ?? raw.creator?.name ?? 'AngBids',
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
      const serverMsg = error.error?.message || error.statusText || 'The server returned an error.';
      errorMessage = `Server Error [${error.status}]: ${serverMsg}`;
    }
    
    console.error(`[AuctionService API Error]:`, {
      message: errorMessage,
      status: error.status,
      url: error.url,
      error: error.error
    });
    
    return throwError(() => new Error(errorMessage));
  }
}
