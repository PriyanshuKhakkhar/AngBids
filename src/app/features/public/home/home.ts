import { Component, OnInit, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AuctionCard } from '../../../shared/components/auction-card/auction-card';
import { Auction } from '../../../core/models/auction.model';
import { AuctionService } from '../../../core/services/auction.service';
import { CategoryService } from '../../../core/services/category.service';
import { HomeService } from '../../../core/services/home.service';
import { Category, HomeStats, UpcomingAuction } from '../../../core/models/home.model';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-home',
  imports: [RouterLink, AuctionCard, CommonModule],
  templateUrl: './home.html',
  styleUrl: './home.css',
})
export class Home implements OnInit {
  private auctionService = inject(AuctionService);
  private categoryService = inject(CategoryService);
  private homeService = inject(HomeService);

  // States
  liveAuctions = signal<Auction[]>([]);
  categories = signal<Category[]>([]);
  stats = signal<HomeStats | null>(null);
  upcomingAuctions = signal<UpcomingAuction[]>([]);
  isLoading = signal(true);

  // Computed array for the stats loop in HTML
  get statsArray() {
    const s = this.stats();
    if (!s) return [];
    return [
      { label: 'Live Auctions', value: s.liveAuctions },
      { label: 'Weekly Volume', value: s.weeklyVolume },
      { label: 'Verified Users', value: s.verifiedUsers },
      { label: 'Success Rate', value: s.successRate }
    ];
  }

  ngOnInit(): void {
    this.loadData();
  }

  loadData(): void {
    this.isLoading.set(true);
    
    // Fetch Live Auctions
    this.auctionService.getAuctions().subscribe(data => this.liveAuctions.set(data.slice(0, 3)));
    
    // Fetch Categories
    this.categoryService.getCategories().subscribe(data => this.categories.set(data));
    
    // Fetch Home Specific Data (Stats + Upcoming)
    this.homeService.getHomeData().subscribe(data => {
      // Mocking the mapping if the API structure differs
      this.stats.set(data.stats[0]); // Assumes stats is an array or object
      this.upcomingAuctions.set(data.upcoming);
      this.isLoading.set(false);
    });
  }

  categoryPills = [
    { label: 'All Auctions', icon: '', active: true },
    { label: 'Electronics', icon: 'fas fa-laptop', active: false },
    { label: 'Watches', icon: 'fas fa-clock', active: false },
    { label: 'Vintage Cars', icon: 'fas fa-car', active: false },
    { label: 'Jewelry', icon: 'fas fa-gem', active: false },
  ];

  partners = [
    { icon: 'fab fa-fedex' },
    { icon: 'fab fa-ups' },
    { icon: 'fab fa-dhl' },
    { icon: 'fab fa-stripe' },
    { icon: 'fab fa-apple-pay' },
  ];
}
