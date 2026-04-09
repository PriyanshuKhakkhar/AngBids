import { Component, OnInit, OnDestroy, inject, signal } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Subject, Subscription } from 'rxjs';
import { debounceTime, distinctUntilChanged } from 'rxjs/operators';
import { AuctionCard } from '../../../shared/components/auction-card/auction-card';
import { Auction } from '../../../core/models/auction.model';
import { Category } from '../../../core/models/home.model';
import { AuctionService } from '../../../core/services/auction.service';
import { CategoryService } from '../../../core/services/category.service';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-auctions',
  templateUrl: './auctions.html',
  styleUrl: './auctions.css',
  standalone: true,
  imports: [AuctionCard, CommonModule],
})
export class Auctions implements OnInit {
  private auctionService = inject(AuctionService);
  private categoryService = inject(CategoryService);

  auctions = signal<Auction[]>([]);
  categories = signal<Category[]>([]);
  isLoading = signal<boolean>(true);
  errorMessage = signal<string | null>(null);

  // Filters
  selectedCategory = signal<number | null>(null);
  sortOption = signal<string | null>(null);
  currentPage = signal<number>(1);
  lastPage = signal<number>(1);
  maxPrice = signal<number | null>(null);
  searchQuery = signal<string>('');

  private route = inject(ActivatedRoute);
  private router = inject(Router);

  private searchSubject = new Subject<string>();
  private searchSubscription!: Subscription;

  ngOnInit(): void {
    // Read initial search query from URL if present
    this.route.queryParams.subscribe(params => {
      const routeSearch = (params['search'] ?? params['q'] ?? '').toString();
      this.searchQuery.set(routeSearch);
      this.fetchData();
    });

    // Setup debounce for typing search
    this.searchSubscription = this.searchSubject.pipe(
      debounceTime(400),
      distinctUntilChanged()
    ).subscribe(query => {
      this.searchQuery.set(query);
      this.currentPage.set(1);
      
      // Update URL without reloading page
      this.router.navigate([], {
        relativeTo: this.route,
        queryParams: { q: query || null, search: query || null },
        queryParamsHandling: 'merge'
      });
      
      this.fetchData();
    });

    this.categoryService.getCategories().subscribe({
      next: (data: Category[]) => this.categories.set(data),
      error: (err) => console.error('Category Fetch Error:', err)
    });
  }

  ngOnDestroy(): void {
    if (this.searchSubscription) {
      this.searchSubscription.unsubscribe();
    }
  }

  fetchData(): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);

    this.auctionService.getAuctions({
      page: this.currentPage(),
      category: this.selectedCategory(),
      sort: this.sortOption(),
      maxPrice: this.maxPrice(),
      search: this.searchQuery() ? this.searchQuery().trim() : null,
    }).subscribe({
      next: (response) => {
        this.auctions.set(response.data);
        this.currentPage.set(response.current_page || 1);
        this.lastPage.set(response.last_page || 1);
        this.isLoading.set(false);
      },
      error: (err) => {
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }

  onSearchInput(event: Event): void {
    const value = (event.target as HTMLInputElement).value;
    this.searchQuery.set(value);
    this.searchSubject.next(value);
  }

  onSearch(): void {
    this.searchSubject.next(this.searchQuery());
  }

  clearSearch(): void {
    this.searchQuery.set('');
    this.selectedCategory.set(null);
    this.maxPrice.set(null);
    this.currentPage.set(1);
    
    // Clear URL parameters
    this.router.navigate([], {
      relativeTo: this.route,
      queryParams: { q: null, search: null },
      queryParamsHandling: 'merge'
    });
    
    this.fetchData();
  }

  onCategoryChange(categoryId: number): void {
    this.selectedCategory.set(
      this.selectedCategory() === categoryId ? null : categoryId
    );
    this.currentPage.set(1);
    this.fetchData();
  }

  onPriceChange(event: Event): void {
    const value = (event.target as HTMLInputElement).value;
    this.maxPrice.set(value ? Number(value) : null);
    this.currentPage.set(1);
    this.fetchData();
  }

  applyFilters(): void {
    this.currentPage.set(1);
    this.fetchData();
  }

  onSortChange(event: Event): void {
    const val = (event.target as HTMLSelectElement).value;
    this.sortOption.set(val);
    this.currentPage.set(1);
    this.fetchData();
  }

  onPageChange(page: number): void {
    if (page >= 1 && page <= this.lastPage() && page !== this.currentPage()) {
      this.currentPage.set(page);
      this.fetchData();
    }
  }
}
