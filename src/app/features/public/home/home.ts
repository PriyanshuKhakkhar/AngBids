import { Component, OnInit, inject, signal } from '@angular/core';
import { RouterLink } from '@angular/router';
import { AuctionCard } from '../../../shared/components/auction-card/auction-card';
import { Auction } from '../../../core/models/auction.model';
import { AuctionService } from '../../../core/services/auction.service';
import { CategoryService } from '../../../core/services/category.service';
import { HomeService } from '../../../core/services/home.service';
import { Category, HomeStats, UpcomingAuction, Partner } from '../../../core/models/home.model';
import { CommonModule } from '@angular/common';

@Component({
  selector: 'app-home',
  standalone: true,
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
  partners = signal<Partner[]>([]);
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
    this.auctionService.getAuctions().subscribe({
      next: (response) => this.liveAuctions.set(response.data.slice(0, 3)),
      error: (err) => console.error('Live Auctions Fetch Error:', err)
    });
    
    // Fetch Categories
    this.categoryService.getCategories().subscribe({
      next: (data: Category[]) => this.categories.set(data),
      error: (err) => console.error('Categories Fetch Error:', err)
    });
    
    // Fetch Home Specific Data (Stats + Upcoming + Partners)
    this.homeService.getHomeData().subscribe({
      next: (data) => {
        if (data.stats) this.stats.set(data.stats[0]);
        if (data.upcoming) this.upcomingAuctions.set(data.upcoming);
        if (data.partners) this.partners.set(data.partners);
        this.isLoading.set(false);
      },
      error: (err) => {
        console.error('Home Data Fetch Error:', err);
        this.isLoading.set(false);
      }
    });
  }

  categoryPills = [
    { label: 'All Auctions', icon: '', active: true },
    { label: 'Electronics', icon: 'fas fa-laptop', active: false },
    { label: 'Watches', icon: 'fas fa-clock', active: false },
    { label: 'Vintage Cars', icon: 'fas fa-car', active: false },
    { label: 'Jewelry', icon: 'fas fa-gem', active: false },
  ];
}
