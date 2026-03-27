/**
 * Defines the core structure of an Auction item in AngBids.
 * This ensures consistency across components like AuctionCard and AuctionDetails.
 */
export interface Auction {
  id: string | number;
  title: string;
  description: string;
  image: string;
  currentBid: number;
  startingPrice: number;
  endTime: Date | string;
  category: string;
  status: 'active' | 'closed' | 'pending';
  sellerId?: string | number;
  totalBids?: number;
}

/**
 * Defines a Bid record on an auction.
 */
export interface Bid {
  id: string | number;
  auctionId: string | number;
  amount: number;
  bidderName: string;
  bidderAvatar?: string;
  time: Date | string;
}
