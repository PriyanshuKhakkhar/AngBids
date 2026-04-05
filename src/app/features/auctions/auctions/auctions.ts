import { Component, OnInit, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
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
  imports: [AuctionCard, CommonModule, RouterLink],
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
  statusFilter = signal<string>('all');
  sortOption = signal<string | null>(null);
  currentPage = signal<number>(1);
  lastPage = signal<number>(1);
  minPrice = signal<number | null>(null);
  maxPrice = signal<number | null>(null);
  searchQuery = signal<string>('');

  // UI state
  showMobileFilter = signal<boolean>(false);

  ngOnInit(): void {
    this.fetchData();
    this.categoryService.getCategories().subscribe({
      next: (data: Category[]) => this.categories.set(data),
      error: (err) => console.error('Category Fetch Error:', err)
    });
  }

  fetchData(): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);

    this.auctionService.getAuctions({
      page: this.currentPage(),
      category: this.selectedCategory(),
      sort: this.sortOption(),
      minPrice: this.minPrice(),
      maxPrice: this.maxPrice(),
    }).subscribe({
      next: (response) => {
        let filtered = response.data;

        // Client-side keyword filter
        const query = this.searchQuery().toLowerCase().trim();
        if (query) {
          filtered = filtered.filter(a => a.title.toLowerCase().includes(query));
        }

        // Client-side status filter
        const status = this.statusFilter();
        if (status !== 'all') {
          filtered = filtered.filter(a => a.status === status);
        }

        this.auctions.set(filtered);
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

  onStatusChange(status: string): void {
    this.statusFilter.set(status);
    this.currentPage.set(1);
    this.fetchData();
  }

  onCategoryChange(categoryId: number): void {
    this.selectedCategory.set(
      this.selectedCategory() === categoryId ? null : categoryId
    );
    this.currentPage.set(1);
    this.fetchData();
  }

  applyFilters(): void {
    this.currentPage.set(1);
    this.fetchData();
  }

  clearAllFilters(): void {
    this.selectedCategory.set(null);
    this.statusFilter.set('all');
    this.minPrice.set(null);
    this.maxPrice.set(null);
    this.searchQuery.set('');
    this.sortOption.set(null);
    this.currentPage.set(1);
    this.fetchData();
  }

  onSortChange(event: Event): void {
    const val = (event.target as HTMLSelectElement).value;
    this.sortOption.set(val || 'newest');
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
