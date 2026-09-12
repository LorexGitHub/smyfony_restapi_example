# Symfony RESTful API Introduction

This project demonstrates a RESTful API built with Symfony 8.1, using Doctrine ORM for database operations.
The focus is on the RESTful API, with basic functionality for create, read, update, and delete operations.

## Database Structure

The application uses **PostgreSQL** (configured in `.env`):

```
DATABASE_URL="postgresql://postgres:1@127.0.0.1:5432/symfony_intro?serverVersion=18&charset=utf8"
```

### Entity Tables

#### 1. `users` table (from HelloController)
Stores user greetings with name and age:
- `id` (integer, primary key, auto-generated)
- `name` (string, max 128 chars, required)
- `age` (integer)
- `message` (string, max 256 chars)

#### 2. `numbers` table (from BinarySearchController)
Stores numbers for binary search operations:
- `id` (integer, primary key, auto-generated)
- `value` (integer)
- `search_target` (string, max 100 chars) - the target value being searched
- `found_index` (string, max 20 chars) - index where target was found

#### 3. `integers` table (from SumOfArrayController)
Stores integers with sum and count calculations:
- `id` (integer, primary key, auto-generated)
- `value` (integer)
- `sum` (integer) - calculated sum of values
- `count` (integer) - number of records

#### 4. `products` table (existing)
- `id` (integer, primary key)
- `name` (string, max 128 chars)
- `size` (integer, nullable)
- `is_available` (boolean, default: true)
- `published_on` (date, nullable)

## RESTful API Endpoints

### Users API (`/api/users`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/users` | List all users |
| `GET` | `/api/users/{id}` | Get a specific user by ID |
| `POST` | `/api/users` | Create a new user |
| `PUT`/`PATCH` | `/api/users/{id}` | Update an existing user |
| `DELETE` | `/api/users/{id}` | Delete a user |

**Example POST body:**
```json
{
    "name": "John Doe",
    "age": 25
}
```

### Numbers API (`/api/numbers`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/numbers` | List all numbers |
| `GET` | `/api/numbers/{id}` | Get a specific number by ID |
| `POST` | `/api/numbers` | Create a new number (includes search target) |
| `PUT`/`PATCH` | `/api/numbers/{id}` | Update a number |
| `DELETE` | `/api/numbers/{id}` | Delete a number |

**Example POST body:**
```json
{
    "value": 42,
    "searchTarget": "82"
}
```
The controller will perform binary search and store the `foundIndex`.

### Integers API (`/api/integers`)

| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/integers` | List all integers |
| `GET` | `/api/integers/{id}` | Get a specific integer by ID |
| `POST` | `/api/integers` | Create a new integer (calculates sum and count) |
| `PUT`/`PATCH` | `/api/integers/{id}` | Update an integer |
| `DELETE` | `/api/integers/{id}` | Delete an integer |

**Example POST body:**
```json
{
    "value": 5
}
```
The controller calculates: sum = 5, count = 1.

### Products API (`/api/products`) *(existing)*

Full CRUD operations for products - see `ProductController.php` for endpoints.

## How It Works

1. **Doctrine ORM** maps PHP entities to database tables
2. **EntityManagerInterface** handles database operations (`persist`, `flush`, `remove`)
3. **SerializerInterface** converts entities to/from JSON
4. **ValidatorInterface** validates input data
5. **Repository classes** fetch entities from the database

## Running the Application

1. Start the built-in web server:
   ```bash
   symfony serve -d
   ```

2. Access the API at `http://localhost:8000`

3. API endpoints:
   - `http://localhost:8000/api/users`
   - `http://localhost:8000/api/numbers`
   - `http://localhost:8000/api/integers`
   - `http://localhost:8000/api/products`

4. To create database tables (first time only):
   ```bash
   php bin/console doctrine:migrations:migrate
   # or generate initial migration:
   php bin/console make:migration
   php bin/console doctrine:migrations:migrate
   ```

## Testing

Run the test suite:
```bash
php bin/phpunit
```