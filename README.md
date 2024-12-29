# Laravel Invoicing API

This API allows customers to generate and retrieve invoices for user events such as registration, activation, and appointments.

---

## Features

- Generate invoices for customer activities within a specific time frame.
- Retrieve detailed invoice data, including event frequency and pricing.

---

## Requirements

- PHP 8.1 or higher
- Composer
- MySQL
- Laravel 11

---

## Installation

### 1. Clone Repository
```bash
git clone https://github.com/ahmedfarghaly1/invoicing-api.git
cd laravel-invoicing-api
```
2. Install Dependencies
```bash
composer install
````
### 2. Configure Environment

```bash
cp .env.example .env
````
### 3. Update .env
```bash
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
````
### 4. Generate Key & Migrate Database
```bash
php artisan key:generate
php artisan migrate --seed
````

### 4. Generate Key & Migrate Database
```bash
php artisan key:generate
php artisan migrate --seed
````
### 5. Start Application
```bash
php artisan serve
````

## Endpoints
### 1. Create Invoice
#### POST /api/v1/invoices
Request:
```bash
{
  "start_date": "2021-01-01",
  "end_date": "2021-02-01",
  "customer_id": 1
}
````
Response:
````bash
{
  "invoice_id": 6
}
````
### 2. Get Invoice Details
#### GET /api/v1/invoices/{id}
Response:
````bash
{
  "invoice_id": 6,
  "customer": "Test Customer",
  "start_date": "2021-01-01",
  "end_date": "2021-01-31",
  "total_price": "400.00",
  "event_frequency": [
    { "event_type": "activation", "frequency": 2 },
    { "event_type": "appointment", "frequency": 1 },
  ],
  "events": [
    {
      "user_email": "example1@example.com",
      "event_type": "appointment",
      "event_price": "200"
    }
   {
      "user_email": "example2@example.com",
      "event_type": "activation",
      "event_price": "100.00"
    }
   {
      "user_email": "example3@example.com",
      "event_type": "activation",
      "event_price": "100.00"
    }
  ]
}
````
## Testing
### Run the test suite:
```bash
php artisan test
```


