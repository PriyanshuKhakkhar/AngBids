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
  standalone: true,
  imports: [AuctionCard, CommonModule],
})
export class Auctions implements OnInit {
  private auctionService = inject(AuctionService);
  private categoryService = inject(CategoryService);

  // Original State signals
  auctions = signal<Auction[]>([]);
  categories = signal<Category[]>([]);
  isLoading = signal<boolean>(true);
  errorMessage = signal<string | null>(null);

  // Filter & Pagination signals
  selectedCategory = signal<number | null>(null);
  sortOption = signal<string | null>(null);
  currentPage = signal<number>(1);
  lastPage = signal<number>(1);
  maxPrice = signal<number | null>(null);

  ngOnInit(): void {
    this.fetchData();
    
    // Fetch Categories
    this.categoryService.getCategories().subscribe({
      next: (data: Category[]) => this.categories.set(data),
      error: (err) => console.error('Category Fetch Error:', err)
    });
  }

  fetchData(): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);
    
    // Build parameters from state
    const params = {
      page: this.currentPage(),
      category: this.selectedCategory(),
      sort: this.sortOption(),
      maxPrice: this.maxPrice()
    };
    
    // Fetch Auctions
    this.auctionService.getAuctions(params).subscribe({
      next: (response) => {
        this.auctions.set(response.data);
        this.currentPage.set(response.current_page || 1);
        this.lastPage.set(response.last_page || 1);
        this.isLoading.set(false);
      },
      error: (err) => {
        console.error('Auction Fetch Error:', err);
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });
  }

  onCategoryChange(categoryId: number): void {
    // Toggle active category
    if (this.selectedCategory() === categoryId) {
      this.selectedCategory.set(null); 
    } else {
      this.selectedCategory.set(categoryId);
    }
    this.currentPage.set(1);
    this.fetchData();
  }

  onPriceChange(event: any): void {
    this.maxPrice.set(Number(event.target.value));
  }

  applyFilters(): void {
    this.currentPage.set(1);
    this.fetchData();
  }

  onSortChange(event: any): void {
    const val = event.target.value;
    let sortValue = 'newest';
    
    if (val.includes('High - Low')) sortValue = 'price_desc';
    else if (val.includes('Low - High')) sortValue = 'price_asc';
    else if (val.includes('Closing')) sortValue = 'closing_soon';
    
    this.sortOption.set(sortValue);
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
