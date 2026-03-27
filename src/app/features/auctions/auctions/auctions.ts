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

  // State signals
  auctions = signal<Auction[]>([]);
  categories = signal<Category[]>([]);
  isLoading = signal<boolean>(true);
  errorMessage = signal<string | null>(null);

  ngOnInit(): void {
    this.fetchData();
  }

  fetchData(): void {
    this.isLoading.set(true);
    
    // Fetch Auctions
    this.auctionService.getAuctions().subscribe({
      next: (data: Auction[]) => {
        console.log('Normalized Auctions:', data);
        this.auctions.set(data);
        this.isLoading.set(false);
      },
      error: (err) => {
        console.error('Auction Fetch Error:', err);
        this.errorMessage.set(err.message);
        this.isLoading.set(false);
      }
    });

    // Fetch Categories
    this.categoryService.getCategories().subscribe({
      next: (data: Category[]) => {
        this.categories.set(data);
      },
      error: (err) => console.error('Category Fetch Error:', err)
    });
  }
}
