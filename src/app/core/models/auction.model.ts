/**
 * Unified Auction Interface for AngBids.
 * Matches UI components and API responses.
 */
export interface Auction {
  id: number;
  title: string;
  description: string;
  imageUrl: string;
  currentBid: string | number; // Support both for flexibility
  startingPrice?: number;
  endDate: string | Date;
  buttonText?: string;
  status?: 'active' | 'closed' | 'pending';
  category?: string;
  sellerId?: number;
}

/**
 * Defines a Bid record.
 */
export interface Bid {
  id: number;
  auctionId: number;
  amount: number;
  bidderName: string;
  time: string | Date;
}
