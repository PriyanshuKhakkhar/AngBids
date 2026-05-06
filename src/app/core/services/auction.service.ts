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
  private baseUrl = environment.apiUrl.replace('/api', '');

  /**
   * Fetch all auctions from the Laravel API with optional filtering.
   */
  getAuctions(queryParams?: {
    category?: number | null;
    minPrice?: number | null;
    maxPrice?: number | null;
    sort?: string | null;
    page?: number;
    search?: string | null;
  }): Observable<{ data: Auction[], current_page?: number, last_page?: number }> {
    
    let params = new HttpParams();
    
    if (queryParams) {
      if (queryParams.category) params = params.set('category_id', queryParams.category.toString());
      if (queryParams.minPrice !== undefined && queryParams.minPrice !== null) params = params.set('min_price', queryParams.minPrice.toString());
      if (queryParams.maxPrice !== undefined && queryParams.maxPrice !== null) params = params.set('max_price', queryParams.maxPrice.toString());
      if (queryParams.sort) params = params.set('sort', queryParams.sort);
      if (queryParams.page) params = params.set('page', queryParams.page.toString());
      if (queryParams.search) {
        params = params.set('search', queryParams.search);
        params = params.set('q', queryParams.search);
      }
    }

    return this.http.get<any>(`${this.apiUrl}/search`, { params }).pipe(
      retry(1),
      map(response => {
        // Handle paginated or non-paginated data
        const rawData = response.data || response;
        const parsedData = Array.isArray(rawData) ? rawData.map((item: any) => this.mapToModel(item)) : [];
        
        return {
          data: parsedData,
          current_page: response.meta?.current_page || response.current_page || 1,
          last_page: response.meta?.last_page || response.last_page || 1
        };
      }),
      catchError(this.handleError)
    );
  }

  /**
   * Place a bid on an auction.
   */
  placeBid(auctionId: number, amount: number): Observable<{ message: string; bid: Bid }> {
    return this.http.post<any>(
      `${this.apiUrl}/${auctionId}/bid`,
      { amount: amount }
    ).pipe(
      map(response => {
        const bidData = response.data?.bid || response.bid || {};
        return {
          message: response.message || 'Bid placed successfully!',
          bid: {
            id: bidData.id || Date.now(),
            auctionId: auctionId,
            amount: bidData.amount || amount,
            bidderName: bidData.user?.name || bidData.bidderName || 'You',
            time: bidData.created_at || bidData.time || new Date().toISOString()
          }
        };
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
  public mapToModel(raw: any): Auction {
    // 1. Smart Image URL Resolution
    const placeholder = `${this.baseUrl}/assets/images/banner-3.png`;
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
        // Clean URL
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

    let sellerAvatar: string | undefined = undefined;
    if (raw.user && raw.user.avatar) {
      sellerAvatar = raw.user.avatar.startsWith('http') ? raw.user.avatar : `${this.baseUrl}/storage/${raw.user.avatar}`;
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
      seller: raw.user ? { name: raw.user.name, avatar: sellerAvatar } : null,
      totalBids: raw.bid_count ?? raw.bids_count ?? (raw.bids ? raw.bids.length : 0),
      minIncrement: raw.min_increment ?? 1,
      bids: raw.bids ? raw.bids.map((b: any) => ({
        id: b.id,
        auctionId: raw.id,
        amount: b.amount,
        bidderName: b.user?.name || 'Anonymous',
        time: b.created_at || new Date().toISOString()
      })) : []
    };
  }

  /**
   * Handle API errors gracefully.
   */
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
      errorMessage = error.error?.message || 'Server Error: Something went wrong on our end. Please try again later.';
    } else {
      if (typeof error.error === 'string' && error.error.trim().startsWith('<')) {
        errorMessage = 'Invalid response from server. Please try again.';
      } else if (error.error instanceof SyntaxError || (error.error?.message && error.error.message.includes('JSON'))) {
        errorMessage = 'Invalid response format from server. Please try again.';
      } else {
        errorMessage = error.error?.message || error.message || `Unexpected Error: ${error.status}`;
      }
    }

    console.error('[AuctionService Error]:', error);
    return throwError(() => new Error(errorMessage));
  }
}
