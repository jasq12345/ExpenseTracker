# 💰 Expense Tracker

Expense Tracker is a Symfony-based application to manage personal and shared expenses, budgets, and reports.

---

##cat Development Checklist / Implementation Guide

Follow these phases to implement, test, and maintain the Expense Tracker.

---

## Progress Overview

| Phase | Description |
|-------|-------------|
| 1 | Entity Adjustments |
| 2 | Security Voters |
| 3 | DTOs |
| 4 | Repository Methods |
| 5 | Services |
| 6 | Controllers |
| 7 | Admin Features |
| 8 | Data Fixtures |
| 9 | Testing & Validation |
| 10 | Optional Enhancements |

---

## Phase 1: Entity Adjustments

- [x] Add `owner` field to `Category` entity (nullable) and `isSystemCategory()` method to `Category`
- [x] Create `Budget` entity with fields:
    - `limitAmount` (decimal)
    - `month` (smallint)
    - `year` (smallint)
    - `user` (ManyToOne)
- [x] Add unique constraint: one budget per user per month
- [x] Run migrations:
  ```bash
  php bin/console make:migration
  php bin/console doctrine:migrations:migrate

## Phase 2: Security Voters

- [ ] TransactionVoter.php (VIEW, EDIT, DELETE)
  - Access: Owner or Admin

- [ ] CategoryVoter.php(VIEW, EDIT, DELETE, CREATE)
  - Access: System categories = admin only; personal categories = owner only

- [ ] BudgetVoter.php (VIEW, EDIT, DELETE)
  - Access: Owner or Admin

---

## Phase 3: DTOs

**Transaction DTOs**
- `CreateTransactionDto.php` (`amount`, `description`, `categoryId`, `date`)
- `UpdateTransactionDto.php` (`amount?`, `description?`, `categoryId?`, `date?`)

**Budget DTOs**
- `CreateBudgetDto.php` (`limitAmount`, `month`, `year`, `categoryId?`)
- `UpdateBudgetDto.php` (`limitAmount?`)

---

## Phase 4: Repository Methods

**TransactionRepository**
- `findByUserAndPeriod(User $user, ?int $month, ?int $year): array`
- `findByUserAndDateRange(User $user, DateTimeInterface $start, DateTimeInterface $end): array`
- `getTotalByUserAndPeriod(User $user, int $month, int $year): float`
- `getTotalByUserCategoryAndPeriod(User $user, Category $category, int $month, int $year): float`

**BudgetRepository**
- Add finder methods for user budgets
- Implement `findByUserAndMonth(User $user, int $month, int $year)`

---

## Phase 5: Services

**TransactionService.php**
- Methods: `create()`, `update()`, `delete()`, `getUserTransactions()`
- Inject: `EntityManager`, `Security`, `BudgetAlertService`

**BudgetService.php**
- Methods: `create()`, `update()`, `delete()`, `getUserBudgets()`

**BudgetAlertService.php**
- Methods: `checkBudgetAfterExpense()`, `getBudgetStatus()`
- Triggers warnings at 80% and alerts at 100% usage

**ReportService.php**
- Methods: `getMonthlyReport()`, `getWeeklyReport()`
- Returns totals, by-category breakdown, daily averages

---

## Phase 6: Controllers

**TransactionController.php**
- Add `denyAccessUnlessGranted()` calls with voter constants
- Delegate logic to `TransactionService`

**CategoryController.php**
- Apply voter checks for edit/delete operations

**BudgetController.php** (API)
- Routes: `GET /api/budgets`, `POST /api/budgets`, `PUT /api/budgets/{id}`, `DELETE /api/budgets/{id}`

**ReportController.php** (API)
- Routes: `GET /api/reports/monthly`, `GET /api/reports/weekly`, `GET /api/reports/budget-status`

---

## Phase 7: Admin Features

- Admin sees all transactions; regular users see only their own
- Admin-only route: `GET /api/admin/users/{id}/transactions`
- System category management:
    - Only admin can create categories with `owner = null`
    - Only admin can edit/delete system categories

---

## Phase 8: Data Fixtures

- Update `AppFixtures.php`:
    - Add default system categories: Food, Transport, Bills, Entertainment, Shopping, Health, Other
    - Create sample admin user
    - Create sample regular user with transactions and budgets

---

## Phase 9: Testing & Validation

**API Tests**
- Test all endpoints with **regular user token** (user sees/edits only own data)
- Test all endpoints with **admin token** (admin sees/edits all data)
- Verify 403 response when accessing another user's resources
- Verify budget alerts trigger at 80% and 100%

**Edge Cases**
- Creating expense without valid category
- Budget with null category (total monthly budget)
- Report generation with no data

---

## Phase 10: Optional Enhancements

- Add serialization groups to entities:
  ```php
  #[Groups(['transaction:read', 'transaction:write'])]
