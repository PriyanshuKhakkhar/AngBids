import { Component, OnInit, inject, signal } from '@angular/core';
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
      maxPrice: this.maxPrice(),
    }).subscribe({
      next: (response) => {
        // Apply client-side keyword filter on top of server results
        const query = this.searchQuery().toLowerCase().trim();
        const filtered = query
          ? response.data.filter(a => a.title.toLowerCase().includes(query))
          : response.data;

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

  onSearch(): void {
    this.currentPage.set(1);
    this.fetchData();
  }

  clearSearch(): void {
    this.searchQuery.set('');
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

  onPriceChange(event: Event): void {
    this.maxPrice.set(Number((event.target as HTMLInputElement).value));
  }

  applyFilters(): void {
    this.currentPage.set(1);
    this.fetchData();
  }

  onSortChange(event: Event): void {
    const val = (event.target as HTMLSelectElement).value;
    const map: Record<string, string> = {
      'price_desc': 'price_desc',
      'price_asc': 'price_asc',
      'closing_soon': 'closing_soon',
    };
    this.sortOption.set(map[val] ?? 'newest');
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
