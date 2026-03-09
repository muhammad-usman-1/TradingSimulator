# Technical Documentation: Investment Trading Simulator

## Project Overview

This is an educational trading simulator built with Laravel (PHP) and MySQL, designed to help beginners learn trading concepts in a safe, virtual environment. The system demonstrates complex algorithms, data structures, and business logic as required by the coursework complexity checklist.

---

## System Architecture

### Technology Stack
- **Backend**: Laravel 11 (PHP 8.2+)
- **Database**: MySQL
- **Frontend**: Blade templates, vanilla JavaScript (no external libraries)
- **Authentication**: Custom manual authentication with password hashing

### Core Components

1. **Database Layer** (MySQL)
   - Complex relational schema with 7+ interlinked tables
   - Foreign key constraints and indexes
   - Parameterized SQL queries via Eloquent ORM

2. **Business Logic Layer** (PHP Services)
   - PriceEngine: Market simulation algorithm
   - SimulationSessionManager: Session lifecycle management
   - PasswordHasher: Custom hashing implementation

3. **Data Structures** (Custom PHP Classes)
   - SimpleList: Custom list implementation
   - SimpleStack: Stack data structure
   - SimpleQueue: Queue data structure
   - TutorialGraph: Graph structure with DFS traversal

4. **Algorithms** (Custom Implementations)
   - MergeSort: Manual merge sort algorithm
   - BinarySearch: Manual binary search algorithm

5. **Presentation Layer** (Blade Views)
   - Admin-style sidebar navigation
   - Live price updates via JavaScript timers
   - Canvas-based candlestick chart rendering

---

## Complexity Checklist Mapping

### Band A Complexity (Grade B+)

#### 1. Complex Data Model in Database
**Location**: `database/migrations/`
- **Tables**: `users`, `assets`, `candles`, `trades`, `holdings`, `simulation_sessions`, `tutorial_progress`
- **Relationships**: 
  - User → Portfolio (1:1)
  - User → Holdings (1:many)
  - User → Trades (1:many)
  - User → SimulationSessions (1:many)
  - Asset → Candles (1:many)
  - Asset → Trades (1:many)
  - Asset → Holdings (1:many)
- **Cross-table queries**: Aggregate functions (SUM, COUNT, AVG) in AdminController
- **Parameterized SQL**: All queries use Eloquent ORM with parameter binding

#### 2. Data Structures and Algorithms
**Location**: `app/Support/Collections/` and `app/Support/Algorithms/`

- **Hash tables/Lists/Stacks/Queues**: 
  - `SimpleList.php`: Custom list with add, removeAt, slice, get operations
  - `SimpleStack.php`: Stack with push, pop, peek
  - `SimpleQueue.php`: Queue with enqueue, dequeue operations
  - Used in: `PriceEngine` (tradeQueue), `MarketState` (currentCandleTicks, recentCandles)

- **Graph/Tree Traversal**: 
  - `TutorialGraph.php`: Directed graph with adjacency list
  - `depthFirstOrder()`: Recursive DFS traversal
  - Used in: `TutorialController` to order learning steps

- **Advanced Algorithms**:
  - `MergeSort.php`: Recursive merge sort implementation
  - `BinarySearch.php`: Binary search on sorted arrays
  - Used in: `MarketHistoryController` to sort candles and find specific time indices

- **List Operations**: 
  - Add prices: `MarketState->currentCandleTicks->add()`
  - Read last value: `MarketState->currentCandleTicks->last()`
  - Slice data: `SimpleList->slice()` for candle aggregation

#### 3. Complex Scientific/Mathematical/Business Model
**Location**: `app/Services/PriceEngine.php`

- **Price Simulation Algorithm**:
  - Random noise calculation: `calculateNoiseImpact()` uses volatility and random walk
  - Trade impact calculation: `consumeTradeImpactForAsset()` uses liquidity and volume ratios
  - Price update: `newPrice = oldPrice + noiseImpact + tradeImpact`
  - Demonstrates: Complex business logic for market simulation

- **Candle Aggregation**:
  - OHLC calculation from tick prices
  - Volume tracking (placeholder for future expansion)
  - Time-indexed candle formation

#### 4. Object-Oriented Programming (OOP)
**Location**: Throughout `app/Models/` and `app/Services/`

- **Classes**: Asset, Candle, Trade, Holding, User, SimulationSession, TutorialProgress
- **Inheritance**: All models extend `Illuminate\Database\Eloquent\Model`
- **Composition**: PriceEngine contains MarketState objects
- **Polymorphism**: Models implement relationships (hasMany, belongsTo)
- **Dynamic object generation**: `MarketState` created dynamically per asset in PriceEngine

#### 5. Client-Server Model
**Location**: `routes/web.php`, Controllers, Views

- **Server-side scripting**: 
  - Laravel controllers handle HTTP requests/responses
  - `MarketController@tick`: Returns JSON for AJAX calls
  - `TradeController@store`: Processes form submissions

- **Client-side interaction**:
  - JavaScript timer (`setInterval`) calls `/market/tick` every 3 seconds
  - `fetch()` API for AJAX requests (no external libraries)
  - Canvas API for chart rendering

- **Web service APIs**:
  - JSON endpoints: `/market/history/{asset}/json`
  - RESTful routes for CRUD operations

### Band B Complexity (Grade C/D)

#### 1. Simple Data Model
- **Two-three interlinked tables**: Portfolio ↔ User, Holdings ↔ User ↔ Asset
- **Single table queries**: Basic SELECT queries in controllers
- **Non-parameterized SQL**: Not used (all queries use Eloquent)

#### 2. Multi-dimensional Arrays / Dictionaries
- **Price history**: `MarketState->recentCandles` stores arrays of candle data
- **Candle data**: Each candle is an associative array with `open`, `high`, `low`, `close`, `time_index`
- **Market states**: `PriceEngine->marketStates` is an associative array keyed by asset_id

#### 3. File Organization
- **Sequential access**: Not heavily used (database-centric)
- **File I/O**: Logging via Laravel's logger (standard library)

#### 4. Simple User-Defined Algorithms
- **Average cost calculation**: Weighted average in `TradeController`
- **Profit/Loss calculation**: Simple arithmetic in `PortfolioController` and `SessionSummaryController`
- **Balance updates**: Addition/subtraction in trade execution

---

## Key Algorithms Explained

### 1. Merge Sort (`app/Support/Algorithms/MergeSort.php`)
**Purpose**: Sort candle data by time_index for chart display

**How it works**:
1. Recursively split array into halves
2. Sort each half recursively
3. Merge sorted halves back together
4. Time complexity: O(n log n)

**Usage**: `MarketHistoryController@show` sorts candles before displaying

### 2. Binary Search (`app/Support/Algorithms/BinarySearch.php`)
**Purpose**: Quickly find a candle at a specific time_index

**How it works**:
1. Assume array is sorted
2. Compare target with middle element
3. Narrow search to left or right half
4. Repeat until found or exhausted
5. Time complexity: O(log n)

**Usage**: `MarketHistoryController@show` finds specific candles when user searches

### 3. Price Engine Algorithm (`app/Services/PriceEngine.php`)
**Purpose**: Simulate realistic price movements

**How it works**:
1. **Noise Impact**: `random(-1, 1) * volatility * currentPrice`
2. **Trade Impact**: `(quantity / liquidity) * currentPrice * direction`
3. **New Price**: `oldPrice + noiseImpact + tradeImpact`
4. **Candle Formation**: After N ticks, compute OHLC from tick list

**Business Logic**:
- Higher volatility = larger random moves
- Lower liquidity = larger price impact from trades
- Large buy orders push price up, large sells push down

### 4. Graph Traversal (`app/Support/Graphs/TutorialGraph.php`)
**Purpose**: Order tutorial steps using depth-first search

**How it works**:
1. Build adjacency list: `edges['intro'] = ['candles', 'risk']`
2. Recursive DFS visits each node once
3. Returns nodes in DFS order

**Usage**: `TutorialController@index` displays steps in learning order

---

## Database Schema

### Core Tables

1. **users**
   - Stores user accounts (admin/user roles)
   - Tracks initial and current balance
   - Links to: Portfolio, Holdings, Trades, SimulationSessions

2. **assets**
   - Tradable instruments (stocks/crypto)
   - Contains: symbol, base_price, volatility, liquidity, behaviour_profile
   - Links to: Candles, Trades, Holdings

3. **candles**
   - OHLC price data per asset per time period
   - Contains: time_index, open, high, low, close, volume
   - Links to: Asset

4. **trades**
   - User buy/sell transactions
   - Contains: side, quantity, price, executed_at
   - Links to: User, Asset, SimulationSession

5. **holdings**
   - Current positions per user per asset
   - Contains: quantity, average_cost
   - Links to: User, Asset

6. **simulation_sessions**
   - Practice runs for learning
   - Contains: starting_balance, current_balance, started_at, ended_at
   - Links to: User, Trades

7. **tutorial_progress**
   - Learning step completion tracking
   - Contains: step_key, completed, completed_at
   - Links to: User

---

## User Flows

### Beginner User Flow
1. Register → Login
2. View Dashboard (see balance, active session)
3. Complete Tutorial steps (intro → candles → risk → practice)
4. Go to Practice Market
5. Watch live prices update every 3 seconds
6. Place buy/sell orders
7. View Portfolio (holdings, P&L)
8. View Session Summary (performance metrics)
9. Complete session when done

### Admin Flow
1. Login as admin
2. View Admin Dashboard (system stats)
3. Manage Users (view all users, balances)
4. Manage Assets (create/edit assets, set volatility/liquidity)
5. Monitor system activity

---

## Testing & Validation

### Input Validation
**Location**: Controllers (especially `TradeController`)

- **Trade validation**: 
  - Asset must exist and be active
  - Quantity: 0.01 to 1,000,000
  - Side: 'buy' or 'sell'
  - Custom error messages for each validation failure

- **Balance checks**:
  - Buy: User must have sufficient cash
  - Sell: User must own sufficient quantity

### Error Handling
- Try-catch blocks in `TradeController`
- Graceful error messages to users
- Database transactions ensure data consistency

---

## Future Enhancements (Not Implemented)

1. **Real-time price updates**: WebSocket integration
2. **Advanced charting**: Multiple timeframes, indicators
3. **Order book simulation**: Limit orders, stop-loss
4. **Portfolio rebalancing**: Automatic suggestions
5. **Risk metrics**: Sharpe ratio, maximum drawdown
6. **Export functionality**: CSV export of trades

---

## File Structure

```
app/
├── Http/Controllers/
│   ├── AdminController.php          # Admin user management
│   ├── AssetAdminController.php     # Asset CRUD
│   ├── DashboardController.php      # User/admin dashboards
│   ├── MarketController.php         # Live market & price ticks
│   ├── MarketHistoryController.php  # History with sorting/searching
│   ├── PortfolioController.php      # Portfolio view
│   ├── SessionSummaryController.php # Session performance
│   ├── TradeController.php          # Buy/sell execution
│   └── TutorialController.php       # Learning path
├── Models/
│   ├── Asset.php                    # Tradable assets
│   ├── Candle.php                   # OHLC data
│   ├── Holding.php                  # User positions
│   ├── Portfolio.php                # Portfolio summary
│   ├── SimulationSession.php        # Practice sessions
│   ├── Trade.php                    # Transactions
│   ├── TutorialProgress.php         # Learning progress
│   └── User.php                     # User accounts
├── Services/
│   ├── MarketState.php              # Per-asset state
│   ├── PasswordHasher.php           # Custom hashing
│   ├── PriceEngine.php              # Market simulation
│   └── SimulationSessionManager.php # Session lifecycle
└── Support/
    ├── Algorithms/
    │   ├── BinarySearch.php         # Binary search
    │   └── MergeSort.php           # Merge sort
    ├── Collections/
    │   ├── SimpleList.php           # Custom list
    │   ├── SimpleQueue.php          # Queue
    │   └── SimpleStack.php          # Stack
    └── Graphs/
        └── TutorialGraph.php        # Graph + DFS

database/
├── migrations/                      # All table schemas
└── seeders/
    ├── AssetSeeder.php              # Sample assets
    └── DatabaseSeeder.php           # Admin/learner users

resources/views/
├── auth/                            # Login/register
├── dashboard/                       # User/admin dashboards
├── market/                          # Market, chart, history
├── tutorial/                        # Learning steps
└── layouts/                         # Shared layout
```

---

## Conclusion

This system demonstrates:
- **Complex database design** with interlinked tables
- **Custom data structures** (List, Stack, Queue, Graph)
- **Advanced algorithms** (Merge Sort, Binary Search, Graph Traversal)
- **Complex business logic** (Price simulation, P&L calculation)
- **OOP principles** (Classes, inheritance, composition, polymorphism)
- **Client-server architecture** (AJAX, JSON APIs, server-side processing)

All implementations avoid external libraries where possible, using only standard PHP/Laravel features and manual algorithm implementations to meet coursework requirements.
