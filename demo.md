<style>
    /* VS Code Markdown Preview - Elite Styling */
    table {
        width: 100% !important;
        display: table !important;
        border-collapse: collapse !important;
        border: 2px solid #000 !important;
        margin-bottom: 30px !important;
    }
    th {
        background-color: #d1d1d1 !important; /* Gray Header */
        border: 1px solid #000 !important;
        padding: 12px !important;
        text-align: left !important;
        color: #000 !important;
        font-weight: bold !important;
    }
    td {
        border: 1px solid #000 !important;
        padding: 10px !important;
        color: #333 !important;
    }
</style>

# LaraBids Project - Detailed Database Schema

This document contains the detailed database structure for the **LaraBids** project, following a tabular format for clear understanding of fields, constraints, and data types.

---

### 1) User
**Description**: Stores the personal and account details of all users (Bidders, Sellers, and Admins).

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Unique identification number for each user.           |
| **name**      | Varchar   | 255  | Not Null           | Full name of the user.                                |
| **username**  | Varchar   | 255  | Unique             | Unique handle/username for the user.                  |
| **email**     | Varchar   | 255  | Not Null, Unique   | Registered email address for login and notifications. |
| **phone**     | Varchar   | 20   | Nullable           | Contact mobile number.                                |
| **location**  | Varchar   | 255  | Nullable           | Physical address or city of the user.                 |
| **avatar**    | Varchar   | 255  | Nullable           | Path to the profile image file.                       |
| **bio**       | Text      | -    | Nullable           | Brief user biography or description.                  |
| **google_id** | Varchar   | 255  | Unique             | Identifier for Google Social Login.                   |
| **password**  | Varchar   | 255  | Not Null           | Encrypted password for authentication.                |
| **role**      | Varchar   | 50   | Not Null           | Handled via Spatie Permissions (super admin, admin).  |
| **deleted_at**| Timestamp | -    | Nullable           | Used for soft-deleting accounts.                      |
| **created_at**| Timestamp | -    | Not Null           | Date/Time of account creation.                        |
| **updated_at**| Timestamp | -    | Not Null           | Date/Time of last update.                             |

---

### 2) Category
**Description**: Stores categories and sub-categories to organize auction items.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Unique identification number for each category.       |
| **parent_id** | BigInt    | 20   | Foreign Key        | ID of the parent category (for sub-categories).       |
| **name**      | Varchar   | 255  | Not Null           | Name of the category (e.g., Electronics).             |
| **slug**      | Varchar   | 255  | Not Null, Unique   | URL-friendly version of the name.                     |
| **icon**      | Varchar   | 100  | Nullable           | FontAwesome class name for the category icon.         |
| **is_active** | Boolean   | -    | Not Null           | Status to toggle visibility (True/False).             |
| **deleted_at**| Timestamp | -    | Nullable           | Soft delete support.                                  |
| **timestamps**| Timestamp | -    | Not Null           | Standard created/updated time tracking.               |

---

### 3) Auction
**Description**: The central table containing all auction listing details.

| FieldName       | Datatype | Size | Constraint       | Description                                         |
| :---            | :---     | :--- | :---             | :---                                                |
| **id**          | BigInt   | 20   | Primary Key      | Unique identification number for the auction.       |
| **user_id**     | BigInt   | 20   | Foreign Key      | ID of the seller who posted the auction.            |
| **category_id** | BigInt   | 20   | Foreign Key      | ID of the linked category.                          |
| **winner_id**   | BigInt   | 20   | Foreign Key      | ID of the user who won the auction.                 |
| **title**       | Varchar  | 255  | Not Null         | Short name/title of the item.                       |
| **description** | Text     | -    | Not Null         | Detailed description of the item.                   |
| **starting_price**| Decimal| 16,2 | Not Null         | The initial price when bidding starts.              |
| **current_price** | Decimal| 16,2 | Not Null         | The latest highest bid amount.                      |
| **min_increment** | Decimal| 16,2 | Default: 1.0     | Minimum amount by which a bid must increase.        |
| **image**       | Varchar  | 255  | Nullable         | Path to the main item thumbnail.                    |
| **document**    | Varchar  | 255  | Nullable         | Supporting documents/verifications.                 |
| **specifications**| JSON   | -    | Nullable         | Detailed technical specifications.                  |
| **start_time**  | DateTime | -    | Not Null         | Date and time when bidding begins.                  |
| **end_time**    | DateTime | -    | Not Null         | Date and time when bidding ends.                    |
| **status**      | Enum     | -    | Not Null         | [draft, active, closed, cancelled].                 |
| **cancel_reason**| Text    | -    | Nullable         | Reason provided if cancelled.                       |
| **deleted_at**  | Timestamp| -    | Nullable         | Soft delete support.                                |
| **timestamps**  | Timestamp| -    | Not Null         | Standard tracking.                                  |

---

### 4) Bid
**Description**: Records every single bid placed by users.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Unique identification number for the bid.             |
| **auction_id**| BigInt    | 20   | Foreign Key        | The auction on which the bid is placed.               |
| **user_id**   | BigInt    | 20   | Foreign Key        | The user who placed the bid.                          |
| **amount**    | Decimal   | 16,2 | Not Null           | The bid amount.                                       |
| **created_at**| Timestamp | -    | Not Null           | Timestamp of when the bid was placed.                 |

---

### 5) Payment
**Description**: Stores details of successful auction payouts.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Unique ID for the payment record.                     |
| **auction_id**| BigInt    | 20   | Foreign Key        | The auction being paid for.                           |
| **user_id**   | BigInt    | 20   | Foreign Key        | The winner who made the payment.                      |
| **amount**    | Decimal   | 16,2 | Not Null           | The final closing amount paid.                        |
| **txn_id**    | Varchar   | 255  | Unique             | Transaction ID from the gateway.                      |
| **method**    | Varchar   | 50   | Not Null           | Method (Stripe, UPI, PayPal).                         |
| **status**    | Enum      | -    | Not Null           | [pending, completed, failed].                         |
| **paid_at**   | Timestamp | -    | Nullable           | Successful payment timestamp.                         |

---

### 6) Review / Feedback
**Description**: Stores feedback for completed auctions.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Unique ID for the review.                             |
| **auction_id**| BigInt    | 20   | Foreign Key        | The auction being reviewed.                           |
| **from_user** | BigInt    | 20   | Foreign Key        | User ID of the reviewer.                              |
| **to_user**   | BigInt    | 20   | Foreign Key        | User ID of the recipient.                             |
| **rating**    | Integer   | 1    | Not Null           | Star rating (1-5).                                    |
| **comment**   | Text      | -    | Nullable           | Feedback text.                                        |
| **created_at**| Timestamp | -    | Not Null           | Feedback timing.                                      |

---

### 7) Watchlist
**Description**: Bookmarked items followed by users.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Unique mapping ID.                                    |
| **user_id**   | BigInt    | 20   | Foreign Key        | The user following the item.                          |
| **auction_id**| BigInt    | 20   | Foreign Key        | The auction item being followed.                      |
| **created_at**| Timestamp | -    | Not Null           | Added on timestamp.                                   |

---

### 8) Notification
**Description**: System-generated alerts.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Unique ID for notification.                           |
| **user_id**   | BigInt    | 20   | Foreign Key        | Recipient of the alert.                               |
| **title**     | Varchar   | 255  | Not Null           | Short summary of the alert.                           |
| **message**   | Text      | -    | Not Null           | Full notification content.                            |
| **is_read**   | Boolean   | -    | Default: False     | Read status.                                          |
| **timestamps**| Timestamp | -    | Not Null           | Time tracking.                                        |

---

### 9) FAQ / Support
**Description**: Support form queries.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Message ID.                                           |
| **name**      | Varchar   | 255  | Not Null           | Sender name.                                          |
| **email**     | Varchar   | 255  | Not Null           | Sender email for replies.                             |
| **subject**   | Varchar   | 255  | Not Null           | Topic of inquiry.                                     |
| **message**   | Text      | -    | Not Null           | Inquiry message.                                      |
| **status**    | Varchar   | 50   | Not Null           | [unread, replied, etc].                               |
| **timestamps**| Timestamp | -    | Not Null           | Time tracking.                                        |

---

### 10) Auto Bids
**Description**: Configuration for Proxy Bidding (Auto-Bid).

| FieldName       | Datatype | Size | Constraint       | Description                                         |
| :---            | :---     | :--- | :---             | :---                                                |
| **id**          | BigInt   | 20   | Primary Key      | Rule ID.                                            |
| **auction_id**  | BigInt   | 20   | Foreign Key      | Targeted auction.                                   |
| **user_id**     | BigInt   | 20   | Foreign Key      | User who set the limit.                             |
| **max_bid_amount**| Decimal| 16,2 | Not Null         | Maximum budget limit.                               |
| **active**      | Boolean  | -    | Default: True    | Status of the proxy.                                |
| **timestamps**  | Timestamp| -    | Not Null         | Timing tracking.                                    |

---

### 11) Auction Images
**Description**: Image Gallery for items.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Mapping ID.                                           |
| **auction_id**| BigInt    | 20   | Foreign Key        | Related auction.                                      |
| **image_path**| Varchar   | 255  | Not Null           | Storage path.                                         |
| **sort_order**| Integer   | -    | Default: 0         | Sequence order.                                       |
| **is_primary**| Boolean   | -    | Not Null           | Main thumbnail flag.                                  |
| **timestamps**| Timestamp | -    | Not Null           | Time tracking.                                        |

---

### 12) Testimonials
**Description**: User reviews for landing page.

| FieldName     | Datatype  | Size | Constraint         | Description                                           |
| :---          | :---      | :--- | :---               | :---                                                  |
| **id**        | BigInt    | 20   | Primary Key        | Mapping ID.                                           |
| **name**      | Varchar   | 255  | Not Null           | Name of reviewer.                                     |
| **role**      | Varchar   | 255  | Nullable           | Designation.                                          |
| **content**   | Text      | -    | Not Null           | Review message.                                       |
| **avatar_url**| Varchar   | 255  | Nullable           | Image link.                                           |
| **is_active** | Boolean   | -    | Default: True      | Frontend visibility.                                  |
| **timestamps**| Timestamp | -    | Not Null           | Timing tracking.                                      |
