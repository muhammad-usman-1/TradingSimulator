# Investment Trading Simulator - Complete Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [What Users Can Do (User Features)](#what-users-can-do-user-features)
3. [What Admins Can Do (Admin Features)](#what-admins-can-do-admin-features)
4. [How the System Works](#how-the-system-works)
5. [Real-Life Examples](#real-life-examples)
6. [Database Tables Explained](#database-tables-explained)
7. [Custom Data Structures](#custom-data-structures)
8. [Algorithms and Their Purposes](#algorithms-and-their-purposes)
9. [User Flow - Step by Step](#user-flow---step-by-step)
10. [Admin Flow - Step by Step](#admin-flow---step-by-step)
11. [System Architecture](#system-architecture)
12. [Technical Implementation Details](#technical-implementation-details)

---

## Project Overview

This is an **Investment Trading Simulator** built with Laravel (PHP) and MySQL. It's designed to help beginners learn trading concepts in a safe, virtual environment without risking real money. Think of it like a flight simulator, but for trading stocks and cryptocurrencies.

### Key Features:
- **Virtual Trading**: Practice buying and selling assets with fake money
- **Live Price Updates**: Watch prices change in real-time (simulated)
- **Portfolio Tracking**: See your holdings and profit/loss
- **Learning Tutorial**: Step-by-step guide for beginners
- **Professional Charts**: View price history with candlestick charts
- **Session Management**: Track your performance over time

---

## What Users Can Do (User Features)

### 1. **Account Management**
- **Register**: Create a new account with email and password
- **Login**: Access your account securely
- **Logout**: Safely exit your session

### 2. **Dashboard**
- **View Account Summary**: See your current balance, initial balance, and overall profit/loss
- **View Holdings**: See what assets you currently own
- **Active Session Info**: Check if you have an active trading session
- **Quick Navigation**: Access all features from the sidebar

### 3. **Practice Market (Live Trading)**
- **View Live Prices**: See real-time prices for all active assets (updates every 3 seconds)
- **Buy Assets**: Purchase stocks or cryptocurrencies
  - Select an asset (e.g., BLUE, TECH, REIT, BTC)
  - Enter quantity (how many units to buy)
  - Click "Buy" button
  - System checks if you have enough cash
  - If yes, trade executes and your balance decreases
- **Sell Assets**: Sell assets you own
  - Select an asset you own
  - Enter quantity to sell
  - Click "Sell" button
  - System checks if you own enough quantity
  - If yes, trade executes and your balance increases

### 4. **Portfolio View**
- **Current Holdings**: See all assets you own with:
  - Asset name and symbol
  - Quantity owned
  - Average purchase price
  - Current market price
  - Current value (quantity × current price)
  - Unrealized Profit/Loss (current value - cost basis)
- **Total Portfolio Value**: Cash + value of all holdings
- **Overall Profit/Loss**: How much you've gained or lost from your initial balance
- **Recent Trades**: Last 20 trades you made

### 5. **Charts (Price History)**
- **View All Charts**: Browse all available assets
- **Candlestick Chart**: Professional trading-style chart showing:
  - **Green Candles**: Price went up (close > open)
  - **Red Candles**: Price went down (close < open)
  - **Wicks**: Show highest and lowest prices
  - **Line Graph Overlay**: Blue line connecting closing prices
  - **Volume Bars**: Gray bars showing trading volume
  - **Interactive Features**:
    - Hover to see detailed price info (OHLC: Open, High, Low, Close)
    - Toggle between "Candles", "Line Graph", or "Both"
    - Crosshair for precise price reading

### 6. **Session Summary**
- **Performance Metrics**: See how you performed in your current session:
  - Starting balance vs current balance
  - Total profit/loss
  - Number of trades (total, buys, sells)
  - Average trade size
  - Net cash flow (money spent vs received)
- **Complete Session**: End your current session and review final results

### 7. **Learning Path (Tutorial)**
- **Step-by-Step Tutorial**: Learn trading basics:
  1. **Introduction**: What the simulator is and how it works
  2. **Candlesticks**: Understanding price charts
  3. **Risk Management**: How to avoid big losses
  4. **Practice Assignment**: Hands-on trading exercise
- **Progress Tracking**: System remembers which steps you've completed

### 8. **Market History**
- **View Historical Data**: See all past price candles for an asset
- **Search Functionality**: Find candles at specific time periods using binary search

---

## What Admins Can Do (Admin Features)

### 1. **Admin Dashboard**
- **System Overview**: View high-level statistics about the system
- **Monitor Activity**: See what's happening in the simulator

### 2. **Manage Users**
- **View All Users**: See list of all registered users
- **User Details**: View each user's:
  - Email address
  - Role (admin or user)
  - Initial balance
  - Current balance
  - Registration date
- **System Statistics**: See totals:
  - Total number of users
  - Total number of trades
  - Total number of assets
  - Total balance across all users

### 3. **Manage Assets**
- **View All Assets**: See list of all tradable assets
- **Create New Asset**: Add a new stock or cryptocurrency:
  - Symbol (e.g., "AAPL", "BTC")
  - Name (e.g., "Apple Inc.", "Bitcoin")
  - Description
  - Base Price (starting price)
  - Volatility (how much price fluctuates: 0.01 = 1% = stable, 0.05 = 5% = volatile)
  - Liquidity (how much trading volume affects price: higher = less impact)
  - Behavior Profile (stable, moderate, volatile)
  - Type (stock or crypto)
  - Active Status (whether users can trade it)
- **Edit Existing Asset**: Modify any asset's properties
- **Toggle Active Status**: Enable/disable trading for an asset

### 4. **System Configuration**
- **Control Market Behavior**: Adjust volatility and liquidity to create different market conditions
- **Monitor Trading Activity**: Watch how users interact with the system

---

## How the System Works

### The Big Picture

Think of the system like a **virtual stock exchange**:

1. **Users** register and get virtual money (e.g., $10,000)
2. **Assets** (stocks/crypto) are available to trade
3. **Prices** change automatically based on:
   - Random market movements (volatility)
   - User trading activity (buying pushes price up, selling pushes down)
4. **Users** buy and sell, building a portfolio
5. **System** tracks everything: trades, holdings, profit/loss

### Price Movement Algorithm

The system uses a **Price Engine** that simulates realistic price movements:

```
New Price = Old Price + Random Noise + Trade Impact
```

**Random Noise**:
- Every 3 seconds, prices move randomly
- Amount of movement depends on asset's "volatility"
- Example: If volatility is 0.02 (2%), price can move ±2% randomly

**Trade Impact**:
- When users buy, price goes up slightly
- When users sell, price goes down slightly
- Impact depends on:
  - Trade size (bigger trades = bigger impact)
  - Asset liquidity (lower liquidity = bigger impact)

**Real-Life Example**:
- **BLUE** stock has low volatility (0.01 = 1%) → stable, like a blue-chip stock
- **BTC** crypto has high volatility (0.05 = 5%) → wild swings, like real Bitcoin
- If 10 users buy 100 units each, price goes up
- If 1 user sells 1000 units, price goes down significantly

### Candle Formation

Every 5 price ticks, the system creates a **candlestick**:
- **Open**: First price in the period
- **High**: Highest price in the period
- **Low**: Lowest price in the period
- **Close**: Last price in the period
- **Volume**: Total trading activity

These candles are stored in the database and displayed on charts.

### Trade Execution Flow

When a user places a trade:

1. **Validation**: System checks:
   - Is the asset active?
   - Is quantity valid (0.01 to 1,000,000)?
   - For BUY: Does user have enough cash?
   - For SELL: Does user own enough quantity?

2. **Execution**:
   - **BUY**: 
     - Deduct cash from user's balance
     - Add/update holdings (if already own, update average cost)
     - Create trade record
   - **SELL**:
     - Add cash to user's balance
     - Reduce holdings quantity
     - Create trade record

3. **Price Impact**: Trade is queued to affect price on next tick

4. **Session Update**: Trade is linked to user's active session

---

## Real-Life Examples

### Example 1: Buying Your First Stock

**Scenario**: You want to buy 10 shares of "BLUE" stock at $100 each.

**Steps**:
1. Go to "Practice Market"
2. Find "BLUE" (currently $100.50)
3. Enter quantity: 10
4. Click "Buy"
5. System checks: Do you have $1,005? (10 × $100.50)
6. If yes:
   - Your balance: $10,000 → $8,995
   - You now own 10 shares of BLUE
   - Trade recorded in history

**Real-Life Equivalent**: Like buying stocks on Robinhood or E*TRADE, but with fake money.

### Example 2: Price Movement

**Scenario**: BLUE stock starts at $100.

**What Happens**:
- **Tick 1** (3 seconds): Random noise: +$0.50 → Price = $100.50
- **Tick 2** (6 seconds): Random noise: -$0.30 → Price = $100.20
- **Tick 3** (9 seconds): User buys 100 shares → Trade impact: +$0.10 → Price = $100.30
- **Tick 4** (12 seconds): Random noise: +$0.40 → Price = $100.70
- **Tick 5** (15 seconds): Random noise: -$0.20 → Price = $100.50
- **Candle Created**: Open=$100, High=$100.70, Low=$100.20, Close=$100.50

**Real-Life Equivalent**: Like watching a stock price ticker on a trading platform.

### Example 3: Portfolio Tracking

**Scenario**: You own:
- 10 shares of BLUE at $100 average cost (current price: $105)
- 5 shares of TECH at $50 average cost (current price: $48)

**Portfolio View Shows**:
- **BLUE**: 
  - Value: 10 × $105 = $1,050
  - Cost: 10 × $100 = $1,000
  - Profit: $50 (unrealized)
- **TECH**:
  - Value: 5 × $48 = $240
  - Cost: 5 × $50 = $250
  - Loss: -$10 (unrealized)
- **Total Portfolio**: Cash + $1,050 + $240 = Total value
- **Overall P&L**: How much you've made/lost from your starting $10,000

**Real-Life Equivalent**: Like viewing your portfolio on a brokerage app.

### Example 4: Admin Managing Assets

**Scenario**: Admin wants to add a new cryptocurrency "ETH" (Ethereum).

**Steps**:
1. Go to "Manage Assets"
2. Fill form:
   - Symbol: ETH
   - Name: Ethereum
   - Base Price: $2,500
   - Volatility: 0.04 (4% - crypto is volatile)
   - Liquidity: 1000000 (high liquidity)
   - Type: Crypto
   - Active: Yes
3. Click "Create Asset"
4. Now users can trade ETH

**Real-Life Equivalent**: Like a stock exchange adding a new listing.

---

## Database Tables Explained

The system uses **7 main tables** to store all data:

### 1. **users** Table
**Purpose**: Stores all user accounts (both regular users and admins)

**Columns**:
- `id`: Unique user ID
- `email`: User's email address (used for login)
- `password`: Encrypted password
- `role`: "admin" or "user"
- `initial_balance`: Starting money (e.g., 10000.00)
- `current_balance`: Current cash balance
- `created_at`, `updated_at`: Timestamps

**Real-Life Example**: Like a bank's customer database. Each row is one person's account.

**Relationships**:
- One user can have many trades
- One user can have many holdings
- One user can have many simulation sessions

### 2. **assets** Table
**Purpose**: Stores all tradable assets (stocks, cryptocurrencies)

**Columns**:
- `id`: Unique asset ID
- `symbol`: Short code (e.g., "BLUE", "BTC")
- `name`: Full name (e.g., "Blue Chip Industries", "Bitcoin")
- `description`: What the asset is
- `base_price`: Starting/reference price
- `volatility`: How much price fluctuates (0.01 = 1%, 0.05 = 5%)
- `liquidity`: How much trading volume affects price (higher = less impact)
- `behaviour_profile`: "stable", "moderate", or "volatile"
- `is_crypto`: true for crypto, false for stocks
- `is_active`: Can users trade this? (true/false)
- `created_at`, `updated_at`: Timestamps

**Real-Life Example**: Like a stock exchange's list of all available stocks. Each row is one stock/crypto.

**Relationships**:
- One asset can have many candles (price history)
- One asset can have many trades
- One asset can be held by many users (holdings)

### 3. **candles** Table
**Purpose**: Stores price history as candlesticks (OHLC data)

**Columns**:
- `id`: Unique candle ID
- `asset_id`: Which asset this candle is for
- `time_index`: Sequential number (0, 1, 2, ...) representing time periods
- `timeframe_seconds`: How long the candle represents (60 seconds)
- `open`: First price in the period
- `high`: Highest price in the period
- `low`: Lowest price in the period
- `close`: Last price in the period
- `volume`: Trading volume (currently 0, can be enhanced)
- `driver`: What caused the price movement ("mixed" = random + trades)
- `formed_at`: When the candle was created
- `created_at`, `updated_at`: Timestamps

**Real-Life Example**: Like historical stock price data. Each row is one time period's price summary (like a 1-minute candle on a trading chart).

**Relationships**:
- Many candles belong to one asset

### 4. **trades** Table
**Purpose**: Records every buy/sell transaction

**Columns**:
- `id`: Unique trade ID
- `user_id`: Who made the trade
- `asset_id`: What was traded
- `simulation_session_id`: Which session this trade belongs to
- `side`: "buy" or "sell"
- `quantity`: How many units (e.g., 10.5 shares)
- `price`: Price per unit when trade executed
- `executed_at`: When the trade happened
- `created_at`, `updated_at`: Timestamps

**Real-Life Example**: Like a transaction log. Each row is one trade (buy or sell).

**Relationships**:
- One trade belongs to one user
- One trade belongs to one asset
- One trade belongs to one simulation session

### 5. **holdings** Table
**Purpose**: Tracks what each user currently owns

**Columns**:
- `id`: Unique holding ID
- `user_id`: Who owns this
- `asset_id`: What they own
- `quantity`: How many units they own
- `average_cost`: Average price they paid (for profit/loss calculation)
- `created_at`, `updated_at`: Timestamps

**Real-Life Example**: Like your brokerage account showing "You own 10 shares of Apple at $150 average cost."

**Relationships**:
- One holding belongs to one user
- One holding belongs to one asset
- One user can have many holdings (one per asset they own)

**Important**: When you buy more of an asset you already own, the system updates the `average_cost` using a weighted average formula:
```
New Average Cost = (Old Quantity × Old Average + New Quantity × New Price) / Total Quantity
```

### 6. **simulation_sessions** Table
**Purpose**: Tracks practice sessions (like a "game session")

**Columns**:
- `id`: Unique session ID
- `user_id`: Who this session belongs to
- `starting_balance`: Balance when session started
- `current_balance`: Current balance in this session
- `started_at`: When session began
- `ended_at`: When session ended (null if still active)
- `created_at`, `updated_at`: Timestamps

**Real-Life Example**: Like starting a new game in a video game. Each session is one "practice run."

**Relationships**:
- One session belongs to one user
- One session can have many trades

### 7. **tutorial_progress** Table
**Purpose**: Tracks which tutorial steps each user has completed

**Columns**:
- `id`: Unique progress ID
- `user_id`: Who this progress belongs to
- `step_key`: Which tutorial step (e.g., "intro", "candles", "risk")
- `completed`: true if completed, false if not
- `completed_at`: When it was completed
- `created_at`, `updated_at`: Timestamps

**Real-Life Example**: Like a checklist. "User John completed step 1, 2, and 3, but not step 4."

**Relationships**:
- One progress record belongs to one user
- One user can have many progress records (one per tutorial step)

---

## Custom Data Structures

The system uses **4 custom data structures** built from scratch (no external libraries):

### 1. **SimpleList** (`app/Support/Collections/SimpleList.php`)

**What It Is**: A custom list/array implementation

**Operations**:
- `add($value)`: Add item to end of list
- `get($index)`: Get item at specific position
- `set($index, $value)`: Replace item at position
- `removeAt($index)`: Remove item and shift others
- `slice($start, $length)`: Get a portion of the list
- `size()`: Get number of items
- `last()`: Get last item

**Where It's Used**:
- **PriceEngine**: Stores price ticks for current candle (`currentCandleTicks`)
- **PriceEngine**: Stores recent candles in memory (`recentCandles`)

**Real-Life Example**: Like a shopping list where you can add items, remove items, and see what's at position 3.

**Code Example**:
```php
$list = new SimpleList();
$list->add(100.50);  // Add price
$list->add(100.75);
$price = $list->get(0);  // Get first price: 100.50
$last = $list->last();   // Get last: 100.75
```

### 2. **SimpleQueue** (`app/Support/Collections/SimpleQueue.php`)

**What It Is**: A First-In-First-Out (FIFO) queue

**Operations**:
- `enqueue($value)`: Add item to back of queue
- `dequeue()`: Remove and return item from front
- `peek()`: Look at front item without removing
- `isEmpty()`: Check if queue is empty
- `size()`: Get number of items

**Where It's Used**:
- **PriceEngine**: Queues trades waiting to affect prices (`tradeQueue`)

**Real-Life Example**: Like a line at a coffee shop. First person in line gets served first.

**Code Example**:
```php
$queue = new SimpleQueue();
$queue->enqueue($trade1);  // Add trade to queue
$queue->enqueue($trade2);
$first = $queue->dequeue();  // Get first trade: $trade1
```

### 3. **SimpleStack** (`app/Support/Collections/SimpleStack.php`)

**What It Is**: A Last-In-First-Out (LIFO) stack

**Operations**:
- `push($value)`: Add item to top
- `pop()`: Remove and return top item
- `peek()`: Look at top without removing
- `isEmpty()`: Check if stack is empty

**Where It's Used**:
- Intended for tutorial navigation history (back button functionality)

**Real-Life Example**: Like a stack of plates. You add plates on top and take from the top.

**Code Example**:
```php
$stack = new SimpleStack();
$stack->push("page1");
$stack->push("page2");
$top = $stack->pop();  // Get "page2" (last added)
```

### 4. **TutorialGraph** (`app/Support/Graphs/TutorialGraph.php`)

**What It Is**: A directed graph using adjacency list representation

**Operations**:
- `addEdge($from, $to)`: Create connection between nodes
- `depthFirstOrder($start)`: Traverse graph using Depth-First Search (DFS)

**Where It's Used**:
- **TutorialController**: Defines tutorial step order and traverses learning path

**Real-Life Example**: Like a roadmap. Nodes are cities, edges are roads. DFS visits all connected cities.

**Structure Example**:
```
intro → candles
intro → risk
candles → practice
risk → practice
```

**DFS Traversal**: Starting from "intro", visits: intro → candles → practice → risk → practice

**Code Example**:
```php
$graph = new TutorialGraph();
$graph->addEdge("intro", "candles");
$graph->addEdge("intro", "risk");
$graph->addEdge("candles", "practice");
$order = $graph->depthFirstOrder("intro");
// Returns: ["intro", "candles", "practice", "risk"]
```

---

## Algorithms and Their Purposes

The system uses **3 main algorithms** implemented from scratch:

### 1. **Merge Sort** (`app/Support/Algorithms/MergeSort.php`)

**Purpose**: Sort candle data by time_index for chart display

**How It Works**:
1. **Divide**: Split array in half
2. **Conquer**: Recursively sort each half
3. **Merge**: Combine sorted halves back together

**Time Complexity**: O(n log n) - efficient for large datasets

**Where It's Used**:
- **MarketHistoryController**: Sorts candles before displaying on chart

**Real-Life Example**: Like sorting a deck of cards by splitting into two piles, sorting each pile, then merging them back.

**Why Not Use Built-in Sort?**: To demonstrate the algorithm implementation for educational purposes.

**Code Example**:
```php
$candles = [
    ['time_index' => 5, 'close' => 100],
    ['time_index' => 2, 'close' => 98],
    ['time_index' => 8, 'close' => 102],
];
$sorter = new MergeSort();
$sorted = $sorter->sortByKey($candles, 'time_index');
// Result: time_index 2, 5, 8 (in order)
```

### 2. **Binary Search** (`app/Support/Algorithms/BinarySearch.php`)

**Purpose**: Quickly find a candle at a specific time_index

**How It Works**:
1. Start with sorted array
2. Compare target with middle element
3. If match, return index
4. If target < middle, search left half
5. If target > middle, search right half
6. Repeat until found or exhausted

**Time Complexity**: O(log n) - very fast, even for large datasets

**Where It's Used**:
- **MarketHistoryController**: Finds specific candles when user searches

**Real-Life Example**: Like finding a word in a dictionary. Instead of reading page by page, you open to the middle, then go left or right.

**Why Not Use Built-in Search?**: To demonstrate the algorithm implementation.

**Code Example**:
```php
$candles = [
    ['time_index' => 2, 'close' => 98],
    ['time_index' => 5, 'close' => 100],
    ['time_index' => 8, 'close' => 102],
];
$searcher = new BinarySearch();
$index = $searcher->findIndexByKey($candles, 'time_index', 5);
// Returns: 1 (found at index 1)
```

### 3. **Price Engine Algorithm** (`app/Services/PriceEngine.php`)

**Purpose**: Simulate realistic price movements

**How It Works**:

**Step 1: Calculate Random Noise**
```php
noiseImpact = random(-1 to +1) × volatility × currentPrice
```
- Random number between -1 and +1
- Multiplied by asset's volatility (e.g., 0.02 = 2%)
- Multiplied by current price
- Result: Random price movement

**Step 2: Calculate Trade Impact**
```php
tradeImpact = (quantity / liquidity) × currentPrice × direction
```
- `direction`: +1 for buy, -1 for sell
- `quantity / liquidity`: How big the trade is relative to market size
- Bigger trades = bigger price impact
- Lower liquidity = bigger impact

**Step 3: Update Price**
```php
newPrice = oldPrice + noiseImpact + tradeImpact
```

**Step 4: Create Candles**
- Every 5 ticks, compute OHLC:
  - **Open**: First price in period
  - **High**: Maximum price in period (scan all ticks)
  - **Low**: Minimum price in period (scan all ticks)
  - **Close**: Last price in period

**Where It's Used**:
- **MarketController**: Called every 3 seconds to update prices
- **TradeController**: Queues trades to affect prices

**Real-Life Example**: Like a real stock market where:
- Prices move randomly (market sentiment, news)
- Large orders move prices (supply and demand)
- Prices are recorded as candles (historical data)

**Code Flow**:
```php
$engine = new PriceEngine();
$engine->queueTrade($trade);  // User buys 100 shares
$prices = $engine->tick();     // Update all prices
// Prices now reflect random movement + trade impact
```

---

## User Flow - Step by Step

### Complete User Journey

#### **Step 1: Registration & Login**

1. **Register**:
   - Go to registration page
   - Enter email and password
   - System creates account with $10,000 initial balance
   - Redirected to login

2. **Login**:
   - Enter email and password
   - System validates credentials
   - Session created, redirected to dashboard

#### **Step 2: Dashboard Overview**

1. **View Dashboard**:
   - See account summary:
     - Current balance: $10,000
     - Initial balance: $10,000
     - Overall P&L: $0 (just started)
   - See active session info
   - See current holdings (empty at start)
   - Navigation sidebar with all features

#### **Step 3: Learning Tutorial (Optional)**

1. **Access Tutorial**:
   - Click "Learning Path" in sidebar
   - See list of tutorial steps

2. **Complete Steps**:
   - **Step 1 - Introduction**: Learn what the simulator is
   - **Step 2 - Candlesticks**: Understand price charts
   - **Step 3 - Risk Management**: Learn to avoid big losses
   - **Step 4 - Practice**: Hands-on assignment
   - Click "Mark as Complete" after each step

3. **Progress Tracking**:
   - System remembers completed steps
   - Can return anytime to review

#### **Step 4: Practice Market (Trading)**

1. **View Live Market**:
   - Click "Practice Market" in sidebar
   - See all active assets with live prices
   - Prices update every 3 seconds automatically

2. **Place a Buy Order**:
   - Select asset (e.g., "BLUE")
   - Enter quantity (e.g., 10)
   - Click "Buy" button
   - System validates:
     - Asset exists and is active ✓
     - Quantity is valid (0.01 to 1,000,000) ✓
     - User has enough cash ✓
   - Trade executes:
     - Cash deducted: $10,000 → $8,995 (if price is $100.50)
     - Holdings updated: Now own 10 shares of BLUE
     - Trade recorded in database
     - Trade queued to affect price on next tick

3. **Place a Sell Order**:
   - Select asset you own (e.g., "BLUE")
   - Enter quantity to sell (e.g., 5)
   - Click "Sell" button
   - System validates:
     - User owns enough quantity ✓
   - Trade executes:
     - Cash added: $8,995 → $9,497.50 (if price is $100.50)
     - Holdings updated: Now own 5 shares of BLUE
     - Trade recorded

#### **Step 5: View Portfolio**

1. **Access Portfolio**:
   - Click "Portfolio" in sidebar
   - See all current holdings

2. **View Holdings**:
   - **BLUE**: 5 shares
     - Average cost: $100.00
     - Current price: $105.00
     - Value: $525.00
     - Unrealized P&L: +$25.00 (profit)
   - **TECH**: 10 shares
     - Average cost: $50.00
     - Current price: $48.00
     - Value: $480.00
     - Unrealized P&L: -$20.00 (loss)

3. **View Summary**:
   - Total Holdings Value: $1,005.00
   - Cash Balance: $9,497.50
   - Total Portfolio Value: $10,502.50
   - Overall P&L: +$502.50 (from initial $10,000)

4. **View Recent Trades**:
   - See last 20 trades with:
     - Asset name
     - Side (buy/sell)
     - Quantity
     - Price
     - Time

#### **Step 6: View Charts**

1. **Access Charts Hub**:
   - Click "Charts" in sidebar
   - See all available assets as cards

2. **View Specific Chart**:
   - Click on an asset (e.g., "BLUE")
   - See professional candlestick chart:
     - Green/red candles
     - Blue line graph overlay
     - Volume bars
     - Price axis (left)
     - Time axis (bottom)
   - **Interactive Features**:
     - Hover to see OHLC data
     - Toggle view modes
     - Crosshair for precise reading

#### **Step 7: Session Summary**

1. **Access Summary**:
   - Click "Session Summary" in sidebar
   - See performance metrics:
     - Starting balance: $10,000
     - Current balance: $9,497.50
     - Profit/Loss: -$502.50
     - Total trades: 2
     - Buy trades: 1
     - Sell trades: 1
     - Average trade size: 7.5 shares
     - Net cash flow: -$502.50

2. **Complete Session**:
   - Click "Complete Session" button
   - Session marked as ended
   - Can start new session later

#### **Step 8: Market History (Advanced)**

1. **View History**:
   - Go to market history page
   - Select an asset
   - See all candles in a table
   - Candles sorted by time_index (using Merge Sort)

2. **Search**:
   - Enter time_index to search
   - System uses Binary Search to find candle
   - Displays result

---

## Admin Flow - Step by Step

### Complete Admin Journey

#### **Step 1: Login as Admin**

1. **Login**:
   - Use admin credentials (created by seeder)
   - System recognizes admin role
   - Redirected to admin dashboard

#### **Step 2: Admin Dashboard**

1. **View Dashboard**:
   - See admin control panel
   - System status information
   - Quick access to all admin features

#### **Step 3: Manage Users**

1. **View All Users**:
   - Click "Manage Users" in sidebar
   - See table of all users:
     - Email
     - Role (admin/user)
     - Initial balance
     - Current balance
     - Registration date

2. **View Statistics**:
   - Total users: 15
   - Total trades: 234
   - Total assets: 4
   - Total balance: $150,000

3. **Monitor Activity**:
   - See which users are active
   - Check user balances
   - Identify users who need help

#### **Step 4: Manage Assets**

1. **View All Assets**:
   - Click "Manage Assets" in sidebar
   - See table of all assets:
     - Symbol
     - Name
     - Base price
     - Volatility
     - Liquidity
     - Active status

2. **Create New Asset**:
   - Fill form:
     - Symbol: "ETH"
     - Name: "Ethereum"
     - Description: "A popular cryptocurrency"
     - Base Price: 2500.00
     - Volatility: 0.04 (4% - volatile)
     - Liquidity: 1000000.00 (high)
     - Behavior Profile: "volatile"
     - Type: Crypto (checkbox)
     - Active: Yes (checkbox)
   - Click "Create Asset"
   - Asset added to database
   - Users can now trade ETH

3. **Edit Existing Asset**:
   - Find asset in table
   - Click "Edit" button
   - Modify fields (e.g., change volatility from 0.01 to 0.02)
   - Click "Update"
   - Changes saved

4. **Toggle Active Status**:
   - Click toggle button for an asset
   - If active → becomes inactive (users can't trade)
   - If inactive → becomes active (users can trade)

#### **Step 5: System Configuration**

1. **Adjust Market Conditions**:
   - Edit asset volatility to create:
     - **Stable market**: Low volatility (0.01)
     - **Volatile market**: High volatility (0.05)
   - Edit liquidity to control:
     - **High liquidity**: Large trades have small impact
     - **Low liquidity**: Large trades have big impact

2. **Monitor Trading**:
   - Watch user activity through admin dashboard
   - Check system statistics
   - Ensure system is running smoothly

---

## System Architecture

### Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL
- **Frontend**: Blade templates (server-side rendering)
- **JavaScript**: Vanilla JS (no external libraries)
- **Charts**: HTML5 Canvas (custom rendering)

### File Structure

```
investment-trading-simulator/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AdminController.php          # Admin user management
│   │       ├── AssetAdminController.php     # Asset CRUD operations
│   │       ├── DashboardController.php      # User/admin dashboards
│   │       ├── MarketController.php         # Live market & price updates
│   │       ├── MarketHistoryController.php  # History with sorting/searching
│   │       ├── PortfolioController.php      # Portfolio view
│   │       ├── SessionSummaryController.php # Session performance
│   │       ├── TradeController.php          # Buy/sell execution
│   │       └── TutorialController.php       # Learning path
│   ├── Models/
│   │   ├── Asset.php                       # Asset model
│   │   ├── Candle.php                       # Candle model
│   │   ├── Holding.php                     # Holding model
│   │   ├── SimulationSession.php            # Session model
│   │   ├── Trade.php                        # Trade model
│   │   ├── TutorialProgress.php            # Tutorial progress model
│   │   └── User.php                         # User model
│   ├── Services/
│   │   ├── MarketState.php                 # Per-asset state
│   │   ├── PriceEngine.php                 # Market simulation engine
│   │   └── SimulationSessionManager.php    # Session lifecycle
│   └── Support/
│       ├── Algorithms/
│       │   ├── BinarySearch.php            # Binary search algorithm
│       │   └── MergeSort.php              # Merge sort algorithm
│       ├── Collections/
│       │   ├── SimpleList.php             # Custom list
│       │   ├── SimpleQueue.php           # Queue data structure
│       │   └── SimpleStack.php           # Stack data structure
│       └── Graphs/
│           └── TutorialGraph.php          # Graph + DFS traversal
├── database/
│   ├── migrations/                        # Database schema
│   └── seeders/
│       ├── AssetSeeder.php               # Sample assets
│       └── DatabaseSeeder.php            # Admin/learner users
├── resources/
│   └── views/
│       ├── auth/                         # Login/register
│       ├── dashboard/                    # User/admin dashboards
│       ├── market/                       # Market, chart, history
│       ├── tutorial/                     # Learning steps
│       ├── admin/                        # Admin views
│       ├── layouts/                      # Shared layout
│       └── partials/                     # Sidebar, etc.
└── routes/
    └── web.php                           # All routes
```

### Request Flow

1. **User Action**: User clicks "Buy" button
2. **Route**: Request goes to `/market/trade` (POST)
3. **Middleware**: `auth.manual` checks if user is logged in
4. **Controller**: `TradeController@store` handles request
5. **Validation**: Checks input (asset, quantity, side)
6. **Business Logic**: 
   - Checks balance/quantity
   - Updates user balance
   - Updates/creates holdings
   - Creates trade record
   - Queues trade in PriceEngine
7. **Response**: Redirects with success/error message
8. **View**: User sees updated balance and holdings

### Data Flow

1. **Price Updates**:
   - JavaScript timer calls `/market/tick` every 3 seconds
   - `MarketController@tick` calls `PriceEngine->tick()`
   - PriceEngine updates prices for all assets
   - Returns JSON with latest prices
   - JavaScript updates page with new prices

2. **Trade Execution**:
   - User submits trade form
   - `TradeController@store` processes trade
   - Updates database (users, holdings, trades tables)
   - Queues trade in PriceEngine
   - Next price tick includes trade impact

3. **Chart Display**:
   - User visits chart page
   - JavaScript fetches `/market/history/{asset}/json`
   - `MarketHistoryController@json` retrieves candles
   - Uses MergeSort to sort by time_index
   - Returns JSON
   - JavaScript renders chart on Canvas

---

## Technical Implementation Details

### Authentication System

- **Custom Implementation**: Manual authentication (not Laravel's built-in)
- **Password Hashing**: Custom hasher using PHP's `password_hash()`
- **Session Management**: PHP sessions store `user_id` and `role`
- **Middleware**: `auth.manual` checks session before allowing access
- **Role-Based Access**: `role:admin` middleware restricts admin routes

### Price Engine Details

**MarketState Class**:
- Stores per-asset state in memory
- `currentPrice`: Current market price
- `currentCandleTicks`: SimpleList of prices for current candle
- `recentCandles`: SimpleList of recent candles (for quick access)
- `nextTimeIndex`: Sequential number for next candle

**Tick Process**:
1. Get all active assets
2. For each asset:
   - Get/create MarketState
   - Calculate noise impact (random)
   - Calculate trade impact (from queue)
   - Update price
   - Add price to currentCandleTicks
   - If 5 ticks reached, close candle (create OHLC)
3. Return latest prices

**Candle Closing**:
1. Scan `currentCandleTicks` to find:
   - Open: first price
   - High: maximum price
   - Low: minimum price
   - Close: last price
2. Save to database
3. Add to `recentCandles` (trim if > 200)
4. Reset `currentCandleTicks` for new candle

### Trade Execution Details

**Buy Process**:
1. Validate input
2. Check user has enough cash: `quantity × price ≤ current_balance`
3. Start database transaction
4. Deduct cash: `user.current_balance -= quantity × price`
5. Update holdings:
   - If holding exists: Update quantity and average_cost
   - If not: Create new holding
6. Create trade record
7. Get/create active session, link trade
8. Queue trade in PriceEngine
9. Commit transaction
10. Return success

**Sell Process**:
1. Validate input
2. Check user owns enough: `quantity ≤ holding.quantity`
3. Start database transaction
4. Add cash: `user.current_balance += quantity × price`
5. Update holdings:
   - Reduce quantity
   - If quantity reaches 0, delete holding
6. Create trade record
7. Get/create active session, link trade
8. Queue trade in PriceEngine
9. Commit transaction
10. Return success

**Average Cost Calculation**:
```php
// When buying more of an asset you already own:
newAverageCost = (oldQuantity × oldAverageCost + newQuantity × newPrice) / totalQuantity

// Example:
// Own 10 shares at $100 average
// Buy 5 more at $110
// New average: (10×100 + 5×110) / 15 = (1000 + 550) / 15 = $103.33
```

### Portfolio Calculation

**Unrealized P&L**:
```php
unrealizedPnL = (currentPrice - averageCost) × quantity

// Example:
// Own 10 shares at $100 average
// Current price: $105
// Unrealized P&L: (105 - 100) × 10 = +$50 (profit)
```

**Total Portfolio Value**:
```php
totalPortfolioValue = cashBalance + sum(all holdings values)

// Example:
// Cash: $5,000
// BLUE: 10 shares × $105 = $1,050
// TECH: 5 shares × $48 = $240
// Total: $5,000 + $1,050 + $240 = $6,290
```

**Overall P&L**:
```php
overallPnL = totalPortfolioValue - initialBalance

// Example:
// Initial: $10,000
// Current: $6,290
// Overall P&L: -$3,710 (loss)
```

### Chart Rendering

**Canvas Drawing**:
1. Fetch candle data via AJAX
2. Calculate dimensions and scaling
3. Draw background and grid
4. Draw volume bars (bottom section)
5. Draw candlesticks (middle section)
6. Draw line graph overlay (if enabled)
7. Draw axes and labels
8. Add hover interactivity (crosshair, tooltip)

**Scaling Logic**:
```javascript
// Price scaling:
minPrice = Math.min(...all lows)
maxPrice = Math.max(...all highs)
priceRange = maxPrice - minPrice
priceScale = (chartHeight - padding) / priceRange

// Time scaling:
timeScale = (chartWidth - padding) / candles.length
```

### Session Management

**Session Lifecycle**:
1. **Start**: When user makes first trade, session created automatically
2. **Active**: Session tracks all trades and balance changes
3. **Complete**: User clicks "Complete Session" button
4. **Ended**: Session marked with `ended_at` timestamp

**Session Metrics**:
- Starting balance (when session started)
- Current balance (updated with each trade)
- Profit/Loss = current - starting
- Trade counts (total, buys, sells)
- Average trade size
- Net cash flow

---

## Summary

This Investment Trading Simulator is a complete educational system that demonstrates:

✅ **Complex Database Design**: 7 interlinked tables with relationships  
✅ **Custom Data Structures**: List, Queue, Stack, Graph  
✅ **Advanced Algorithms**: Merge Sort, Binary Search, DFS Traversal  
✅ **Business Logic**: Price simulation, trade execution, portfolio management  
✅ **OOP Principles**: Classes, inheritance, composition, polymorphism  
✅ **Client-Server Architecture**: AJAX, JSON APIs, server-side processing  
✅ **Professional UI**: Admin panel, interactive charts, responsive design  

The system provides a safe, virtual environment for beginners to learn trading concepts while demonstrating sophisticated programming techniques and algorithms.

---

**End of Documentation**
