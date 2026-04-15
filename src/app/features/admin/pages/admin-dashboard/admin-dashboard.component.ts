import { Component, inject, OnInit, signal, computed } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterLink } from '@angular/router';
import {
  AdminService,
  AdminDashboardData,
  AdminStats,
  AdminRecentAuction,
  AdminRecentUser,
} from '../../services/admin.service';

// ─── Local types ───────────────────────────────────────────────────────────────
interface RecentBidRow {
  bidderName: string;
  auctionTitle: string;
  amount: number;
  time: string;
}

interface QuickAction {
  label: string;
  description: string;
  icon: string;
  color: string;
  route: string;
}

// ─── Mock fallback data (only used when API fails) ────────────────────────────
const MOCK_STATS: AdminStats = {
  total_users: 0, new_users_this_month: 0,
  total_auctions: 0, active_auctions: 0,
  pending_auctions: 0, closed_auctions: 0,
  cancelled_auctions: 0, total_bids: 0,
  bids_today: 0, total_categories: 0, unread_contacts: 0,
};

@Component({
  selector: 'app-admin-dashboard',
  standalone: true,
  imports: [CommonModule, RouterLink],
  templateUrl: './admin-dashboard.component.html',
  styleUrl: './admin-dashboard.component.css',
})
export class AdminDashboardComponent implements OnInit {
  private adminService = inject(AdminService);

  // ─── State ────────────────────────────────────────────────────────────────
  isLoading      = signal(true);
  errorMessage   = signal<string | null>(null);
  usingFallback  = signal(false);

  stats          = signal<AdminStats>(MOCK_STATS);
  recentAuctions = signal<AdminRecentAuction[]>([]);
  recentUsers    = signal<AdminRecentUser[]>([]);
  recentBids     = signal<RecentBidRow[]>([]);

  // ─── Computed: Summary Cards (Total Users, Total Auctions, Active, Ended, Total Bids) ───
  summaryCards = computed(() => {
    const s = this.stats();
    return [
      {
        label: 'Total Users',
        value: s.total_users,
        sub: `+${s.new_users_this_month} this month`,
        icon: 'fas fa-users',
        color: 'primary',
      },
      {
        label: 'Total Auctions',
        value: s.total_auctions,
        sub: `${s.pending_auctions} pending approval`,
        icon: 'fas fa-gavel',
        color: 'success',
      },
      {
        label: 'Active Auctions',
        value: s.active_auctions,
        sub: 'Live and upcoming',
        icon: 'fas fa-bolt',
        color: 'warning',
      },
      {
        label: 'Ended Auctions',
        value: s.closed_auctions,
        sub: 'Successfully finished',
        icon: 'fas fa-flag-checkered',
        color: 'secondary',
      },
      {
        label: 'Total Bids',
        value: s.total_bids,
        sub: `${s.bids_today} today`,
        icon: 'fas fa-hand-paper',
        color: 'info',
      },
    ];
  });

  // ─── Quick Navigation / Admin Actions ─────────────────────────────────────
  quickActions: QuickAction[] = [
    {
      label:       'Manage Users',
      description: 'View and control user access',
      icon:        'fas fa-users-cog',
      color:       'primary',
      route:       '/admin/users',
    },
    {
      label:       'Manage Auctions',
      description: 'Review and moderate auctions',
      icon:        'fas fa-gavel',
      color:       'success',
      route:       '/admin/auctions',
    },
    {
      label:       'Manage Bids',
      description: 'Monitor live bidding flow',
      icon:        'fas fa-history',
      color:       'warning',
      route:       '/admin/activity',
    },
  ];

  // ─── Lifecycle ────────────────────────────────────────────────────────────
  ngOnInit(): void {
    this.loadDashboard();
  }

  loadDashboard(): void {
    this.isLoading.set(true);
    this.errorMessage.set(null);
    this.usingFallback.set(false);

    this.adminService.getDashboard().subscribe({
      next: (data: AdminDashboardData) => {
        if (data) {
          this.stats.set(data.stats || MOCK_STATS);
          this.recentAuctions.set(data.recent_auctions || []);
          this.recentUsers.set(data.recent_users || []);
          this.recentBids.set(this._extractRecentBids(data.recent_auctions || []));
        }
        this.isLoading.set(false);
      },
      error: (err: Error) => {
        this.errorMessage.set(err.message || 'Server currently unreachable');
        this._setFallbackData();
        this.isLoading.set(false);
      }
    });
  }

  // ─── Helpers ──────────────────────────────────────────────────────────────

  private _setFallbackData(): void {
    this.usingFallback.set(true);
    this.stats.set({
      total_users: 1540,
      new_users_this_month: 124,
      total_auctions: 485,
      active_auctions: 42,
      pending_auctions: 12,
      closed_auctions: 420,
      cancelled_auctions: 23,
      total_bids: 8450,
      bids_today: 156,
      total_categories: 15,
      unread_contacts: 8,
    });

    this.recentAuctions.set([
      { id: 1, title: 'Vintage Rolex Datejust', status: 'active', current_price: 5400, starting_price: 3000, end_time: new Date(Date.now() + 86400000).toISOString(), start_time: null, category: {id: 1, name: 'Watches'}, user: {id: 1, name: 'John Doe'}, bid_count: 14 },
      { id: 2, title: 'MacBook Pro M2 Max', status: 'pending', current_price: 2100, starting_price: 2000, end_time: new Date(Date.now() + 172800000).toISOString(), start_time: null, category: {id: 2, name: 'Electronics'}, user: {id: 2, name: 'Alice Smith'}, bid_count: 5 },
      { id: 3, title: 'Herman Miller Aeron Key', status: 'closed', current_price: 850, starting_price: 500, end_time: new Date(Date.now() - 3600000).toISOString(), start_time: null, category: {id: 3, name: 'Furniture'}, user: {id: 3, name: 'Bob Wilson'}, bid_count: 22 },
    ]);

    this.recentUsers.set([
      { id: 4, name: 'Sarah Chen', email: 'sarah@example.com', roles: ['user'], created_at: new Date().toISOString() },
      { id: 5, name: 'Mike Johnson', email: 'mike@example.com', roles: ['user'], created_at: new Date().toISOString() },
    ]);

    this.recentBids.set([
      { bidderName: 'John Doe', auctionTitle: 'Vintage Rolex', amount: 5400, time: new Date().toISOString() },
      { bidderName: 'Jane Smith', auctionTitle: 'MacBook Pro', amount: 2100, time: new Date().toISOString() },
    ]);
  }

  private _extractRecentBids(auctions: AdminRecentAuction[]): RecentBidRow[] {
    const rows: RecentBidRow[] = [];
    auctions.forEach(a => {
      const bArr = (a as any).bids as any[];
      if (bArr && bArr.length) {
        bArr.slice(0, 2).forEach(b => {
          rows.push({
            bidderName: b.user?.name || 'Anonymous',
            auctionTitle: a.title,
            amount: b.amount,
            time: b.created_at || a.start_time || ''
          });
        });
      }
    });
    return rows.slice(0, 10);
  }

  getAuctionStatus(a: AdminRecentAuction): 'Live' | 'Upcoming' | 'Closed' | 'Cancelled' {
    if (a.status === 'cancelled') return 'Cancelled';
    if (a.status === 'pending')   return 'Upcoming';
    if (a.end_time && new Date(a.end_time) <= new Date()) return 'Closed';
    return 'Live';
  }

  statusClass(status: string): string {
    const map: Record<string, string> = {
      Live: 'badge-live', Upcoming: 'badge-upcoming',
      Closed: 'badge-closed', Cancelled: 'badge-cancelled',
    };
    return map[status] ?? 'badge-secondary';
  }

  getCardBorderClass(color: string) { return `border-start border-4 border-${color}`; }
  getIconBgClass(color: string)      { return `bg-${color} bg-opacity-10 text-${color}`; }
  actionBg(color: string)            { return `bg-${color} bg-opacity-10 text-${color}`; }
  actionBtn(color: string)           { return `btn btn-${color} btn-sm w-100 mt-auto`; }
}

// // update: sync with latest changes
