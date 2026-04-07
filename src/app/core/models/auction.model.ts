/**
 * Normalized Auction model — all fields are mapped from raw API responses
 * by AuctionService.mapToModel() before reaching components.
 */
export interface Auction {
  id: number;
  title: string;
  description: string;
  imageUrl: string;
  currentBid: string | number;
  startingPrice: number;
  endDate: string | Date;
  status: 'active' | 'closed' | 'pending';
  category: string;
  seller?: { name: string; avatar?: string } | null;
  totalBids?: number;
}

export interface Bid {
  id: number;
  auctionId: number;
  amount: number;
  bidderName: string;
  time: string | Date;
}
