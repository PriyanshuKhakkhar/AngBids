/**
 * Unified Auction Interface for AngBids.
 * Matches UI components and API responses.
 */
export interface Auction {
  id: number;
  title: string;
  description: string;
  imageUrl: string;
  images?: { url: string }[]; // Support Laravel's nested image arrays
  current_price?: string | number; // Laravel's default naming
  currentBid?: string | number; // UI compatibility
  startingPrice?: number;
  starting_price?: number; // Laravel compatibility
  endDate: string | Date;
  end_date?: string | Date; // Laravel compatibility
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
