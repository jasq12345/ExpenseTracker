# 💰 Expense Tracker

A comprehensive Symfony-based REST API for managing personal finances, including transactions, categories, budgets, and financial reports.

---

## 📋 Table of Contents

- [Features](#features)
- [API Endpoints](#api-endpoints)
- [Authentication](#authentication)
- [Filtering & Pagination](#filtering--pagination)
- [Budget Alerts](#budget-alerts)
- [Reports](#reports)
- [Admin Features](#admin-features)
- [Development Progress](#development-progress)
- [Future Improvements](#future-improvements)

---

## ✅ Features

### User Management
- User registration with email and password
- JWT authentication with access and refresh tokens
- Role-based access control (User / Admin)

### Category Management
- System categories available to all users (Food, Transport, Bills, Entertainment, Shopping, Health, Other)
- Personal categories created by individual users
- Categories with custom icons and colors
- Owner-based access control

### Transaction Management
- Create income and expense transactions
- Assign transactions to categories
- Add descriptions and custom dates
- Filter transactions by type, categories, and date range
- Full CRUD operations with ownership validation

### Budget Management
- Set monthly spending limits
- Category-specific budgets or total monthly budgets
- Unique constraint: one budget per user per category per month
- Automatic budget tracking

### Budget Alerts
- Warning notification at 80% budget usage
- Alert notification at 100% budget usage
- Automatic check after each expense creation

### Financial Reports
- Monthly spending summaries
- Weekly spending breakdowns
- Category-wise expense distribution
- Daily spending averages
- Income vs expense totals

---

## 🔗 API Endpoints

### Authentication

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | `/api/register` | Register a new user account |
| POST | `/api/login` | Authenticate and receive JWT tokens |
| POST | `/api/token/refresh` | Refresh expired access token |

### Categories

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/categories` | List all categories (system + personal) |
| POST | `/api/categories` | Create a new personal category |
| GET | `/api/categories/{id}` | Get single category details |
| PUT | `/api/categories/{id}` | Update a category |
| DELETE | `/api/categories/{id}` | Delete a personal category |

### Transactions

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/transactions` | List transactions with filters and pagination |
| POST | `/api/transactions` | Create a new transaction |
| GET | `/api/transactions/{id}` | Get single transaction details |
| PUT | `/api/transactions/{id}` | Update a transaction |


### Budgets

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/budgets` | List all user budgets |
| POST | `/api/budgets` | Create a new budget |
| GET | `/api/budgets/{id}` | Get single budget details |
| PUT | `/api/budgets/{id}` | Update budget limit |
| GET | `/api/budgets/status` | Get current budget usage status |

### Reports

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/reports/monthly` | Get monthly spending report |
| GET | `/api/reports/weekly` | Get weekly spending report |
| GET | `/api/reports/budget-status` | Get all budgets with current usage |
| GET | `/api/reports/category-breakdown` | Get expenses grouped by category |

### Admin Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/admin/users` | List all users |
| GET | `/api/admin/users/{id}/transactions` | View any user's transactions |
| POST | `/api/admin/categories` | Create system category |
| PUT | `/api/admin/categories/{id}` | Edit system category |
| DELETE | `/api/admin/categories/{id}` | Delete system category |

---

## 🔐 Authentication

All endpoints except `/api/register` and `/api/login` require a valid JWT token.

Include the token in the Authorization header:
```
Authorization: Bearer <your_jwt_token>
```

Tokens expire after a configured time. Use the refresh token endpoint to obtain a new access token without re-authenticating.

---

## 🔍 Filtering & Pagination

### Transaction Filters

| Parameter | Type | Description | Example |
|-----------|------|-------------|---------|
| `type` | string | Filter by transaction type | `?type=income` or `?type=expense` |
| `categories[]` | array | Filter by category IDs | `?categories[]=1&categories[]=2` |
| `date_from` | string | Start date (Y-m-d format) | `?date_from=2024-01-01` |
| `date_to` | string | End date (Y-m-d format) | `?date_to=2024-12-31` |

### Pagination Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | int | 1 | Page number |
| `limit` | int | 20 | Items per page (max 100) |

### Pagination Response Structure

```json
{
    "data": [
        { "id": 1, "amount": 50.00, "type": "expense", ... },
        { "id": 2, "amount": 100.00, "type": "income", ... }
    ],
    "meta": {
        "total": 150,
        "page": 1,
        "per_page": 20,
        "total_pages": 8
    }
}
```

### Filter Combinations

You can combine any filters together:

- All income transactions: `?type=income`
- Expenses in specific categories: `?type=expense&categories[]=1&categories[]=3`
- All transactions in date range: `?date_from=2024-01-01&date_to=2024-01-31`
- Income from specific category with pagination: `?type=income&categories[]=2&page=2&limit=10`

---

## ⚠️ Budget Alerts

The system automatically monitors budget usage and provides alerts:

### Warning Level (80%)
- Triggered when spending reaches 80% of the budget limit
- Notification logged for user awareness
- Spending still allowed

### Alert Level (100%)
- Triggered when spending reaches or exceeds the budget limit
- Strong notification logged
- User informed they have exceeded their budget

### Budget Status Response

```json
{
    "category": "Food",
    "limit": 500.00,
    "spent": 425.00,
    "remaining": 75.00,
    "percentage": 85,
    "status": "warning"
}
```

---

## 📊 Reports

### Monthly Report
- Total income for the month
- Total expenses for the month
- Net balance (income - expenses)
- Breakdown by category
- Daily average spending
- Comparison with previous month

### Weekly Report
- Total expenses for current week
- Day-by-day breakdown
- Top spending categories
- Daily average

### Category Breakdown
- Expenses grouped by category
- Percentage of total spending per category
- Sorted by amount (highest first)

---

## 👑 Admin Features

Administrators have elevated access:

- View all users' transactions
- Create, edit, and delete system categories
- Access all budgets and reports
- Override ownership restrictions

Regular users can only:
- View and manage their own transactions
- Create personal categories
- Manage their own budgets
- View their own reports

---

## 📈 Development Progress

### ✅ Phase 1: Entity Setup (Complete)
- User entity with authentication fields
- Category entity with owner field and system category support
- Transaction entity with type, amount, description, date
- Budget entity with limit, month, year, unique constraints
- All migrations executed

### ✅ Phase 2: Security Voters (Complete)
- TransactionVoter for VIEW, EDIT, DELETE, CREATE permissions
- CategoryVoter for system vs personal category access
- BudgetVoter for owner and admin access control

### ✅ Phase 3: DTOs (Complete)
- CreateTransactionDto with validation
- UpdateTransactionDto with optional fields
- TransactionFilterDto for query parameters
- CreateBudgetDto and UpdateBudgetDto

### ✅ Phase 4: Repository Methods (Complete)
- TransactionRepository with filtering and pagination
- findByUserAndPeriod method
- findByUserAndDateRange method
- getTotalByUserAndPeriod method
- getTotalByUserCategoryAndPeriod method
- BudgetRepository with findByUserAndMonth

### ✅ Phase 5: Services (Complete)
- TransactionService with create, update, delete, getFiltered, getAllForUser, getByType, getByCategories
- CategoryService with full CRUD operations
- BudgetService with create, update, delete, getUserBudgets
- BudgetAlertService with checkBudgetAfterExpense, getBudgetStatus
- ReportService with getMonthlyReport, getWeeklyReport
- UserProviderService for current user retrieval
- LoggerNotificationService for alert logging

### ✅ Phase 6: Controllers (Complete)
- TransactionController with all CRUD endpoints and filtering
- CategoryController with voter-protected endpoints
- BudgetController with full API routes
- ReportController with monthly, weekly, and budget-status endpoints

### ✅ Phase 7: Admin Features (Complete)
- Admin can view all transactions
- Admin-only system category management
- Admin endpoint for viewing any user's data

### 🔄 Phase 8: Data Fixtures (In Progress)
- System categories to be seeded
- Sample admin and regular user accounts
- Sample transactions and budgets for testing

### ⏳ Phase 9: Testing & Validation (Pending)
- API endpoint testing with user tokens
- API endpoint testing with admin tokens
- 403 response verification
- Budget alert trigger testing
- Edge case handling

---

## 🚀 Future Improvements

### Pagination Enhancements
- Add sorting options (by date, amount, category)
- Cursor-based pagination for large datasets
- Configurable default page size per user

### Performance Optimizations
- Add database indexes on frequently queried fields
- Implement query result caching
- Optimize N+1 queries in reports

### Additional Filters
- Filter by amount range (min/max)
- Filter by description search (partial match)
- Filter by multiple types simultaneously

### Extended Reports
- Yearly summary reports
- Custom date range reports
- Export to CSV/PDF
- Spending trends and predictions

### Notification System
- Email notifications for budget alerts
- Push notifications support
- Configurable notification preferences

### Additional Features
- Recurring transactions
- Transaction tags/labels
- Multiple currency support
- Shared budgets between users
- Transaction attachments (receipts)

### API Improvements
- Rate limiting
- API versioning
- OpenAPI/Swagger documentation
- Response caching headers

### Security Enhancements
- Two-factor authentication
- Password reset functionality
- Account lockout after failed attempts
- Audit logging for sensitive operations
