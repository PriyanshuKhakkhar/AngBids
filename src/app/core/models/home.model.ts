/**
 * Category model for auction organization.
 */
export interface Category {
  id: number;
  name: string;
  icon: string;
}

/**
 * Site-wide statistics for the homepage.
 */
export interface HomeStats {
  liveAuctions: string;
  weeklyVolume: string;
  verifiedUsers: string;
  successRate: string;
}

/**
 * Upcoming teaser data for the homepage.
 */
export interface UpcomingAuction {
  id: number;
  title: string;
  description: string;
  date: string;
}

export interface Partner {
  icon: string;
}
