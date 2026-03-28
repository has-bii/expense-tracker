# Expense API

All endpoints require authentication via Bearer token.

Base URL: `/api`

---

## List Expenses

```
GET /expense
```

### Query Parameters

#### Pagination & Sorting

| Parameter | Type    | Default      | Description                        |
|-----------|---------|--------------|------------------------------------|
| cursor    | string  | null         | Cursor for next/previous page      |
| limit     | integer | 10           | Items per page (min: 10, max: 100) |
| sort_by   | string  | `created_at` | Column to sort by                  |
| order     | string  | `desc`       | Sort order: `asc` or `desc`        |

#### Filters

| Parameter         | Type   | Description                              |
|-------------------|--------|------------------------------------------|
| expense_date_from | date   | Filter expenses on or after this date    |
| expense_date_to   | date   | Filter expenses on or before this date   |
| category          | uuid   | Filter by category ID (must exist)       |
| amount_from       | number | Filter expenses with amount >= this value |
| amount_to         | number | Filter expenses with amount <= this value |

All filter parameters are optional. Invalid filters are silently ignored.

### Response

```json
{
  "success": true,
  "message": "ok",
  "data": {
    "data": [
      {
        "id": "uuid",
        "category_id": "uuid",
        "amount": "1000.00",
        "description": "Lunch",
        "expense_date": "2026-03-28",
        "created_at": "2026-03-28T10:00:00.000000Z"
      }
    ],
    "path": "/api/expense",
    "per_page": 10,
    "next_cursor": "eyJpZCI6...",
    "next_page_url": "/api/expense?cursor=eyJpZCI6...",
    "prev_cursor": null,
    "prev_page_url": null
  }
}
```

### Example

```
GET /expense?limit=10&sort_by=expense_date&order=asc&expense_date_from=2026-01-01&category=550e8400-e29b-41d4-a716-446655440000
```

---

## Create Expense

```
POST /expense
```

### Request Body

| Field        | Type   | Required | Description              |
|--------------|--------|----------|--------------------------|
| category_id  | uuid   | Yes      | Category ID              |
| amount       | number | Yes      | Amount (up to 2 decimals)|
| description  | string | No       | Optional description     |
| expense_date | date   | Yes      | Date of the expense      |

### Response `201`

```json
{
  "success": true,
  "message": "Lunch has been added successfully",
  "data": {
    "id": "uuid",
    "user_id": "uuid",
    "category_id": "uuid",
    "amount": "1000.00",
    "description": "Lunch",
    "expense_date": "2026-03-28",
    "created_at": "2026-03-28T10:00:00.000000Z",
    "updated_at": "2026-03-28T10:00:00.000000Z"
  }
}
```

---

## Update Expense

```
PUT /expense/{id}
```

### Request Body

| Field        | Type   | Required | Description              |
|--------------|--------|----------|--------------------------|
| category_id  | uuid   | Yes      | Category ID              |
| amount       | number | Yes      | Amount (up to 2 decimals)|
| description  | string | No       | Optional description     |
| expense_date | date   | Yes      | Date of the expense      |

### Response `201`

```json
{
  "success": true,
  "message": "Lunch has been updated successfully",
  "data": {
    "id": "uuid",
    "user_id": "uuid",
    "category_id": "uuid",
    "amount": "1000.00",
    "description": "Lunch",
    "expense_date": "2026-03-28",
    "created_at": "2026-03-28T10:00:00.000000Z",
    "updated_at": "2026-03-28T10:00:00.000000Z"
  }
}
```

---

## Delete Expense

```
DELETE /expense/{id}
```

### Response `200`

```json
{
  "success": true,
  "message": "Lunch has been deleted successfully",
  "data": {
    "id": "uuid",
    "user_id": "uuid",
    "category_id": "uuid",
    "amount": "1000.00",
    "description": "Lunch",
    "expense_date": "2026-03-28",
    "created_at": "2026-03-28T10:00:00.000000Z",
    "updated_at": "2026-03-28T10:00:00.000000Z"
  }
}
```
