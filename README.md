# E-Commerce Store — Backend

A PHP 8.2 GraphQL API server built without any framework, following PSR-4 autoloading and OOP best practices. It serves as the backend for a full-stack e-commerce platform, exposing a GraphQL endpoint for querying products and categories, and placing orders.

## Live API

```
https://e-commerce-website-server-php-production.up.railway.app/graphql
```

---

## Tech Stack

| Technology | Version | Purpose |
|---|---|---|
| PHP | 8.2 | Server-side language |
| webonyx/graphql-php | ^15.2 | GraphQL server implementation |
| nikic/fast-route | ^1.3 | HTTP request routing |
| vlucas/phpdotenv | ^5.6 | Environment variable management |
| MySQL | 5.6+ | Relational database |
| PDO | — | Database access layer |
| Docker | — | Containerized deployment |

---

## Project Structure

```
/
├── index.php                             # Application entry point — routing bootstrap
├── composer.json                         # PHP dependencies and autoload config
├── Dockerfile                            # Docker configuration for Railway deployment
├── .htaccess                             # Apache rewrite rules (for non-Docker environments)
└── src/
    ├── Controller/
    │   └── GraphQL.php                   # Handles incoming GraphQL requests, builds schema
    ├── Core/
    │   └── Database.php                  # Singleton PDO database connection
    ├── Model/
    │   ├── Product.php                   # Raw PDO queries for products
    │   ├── Category.php                  # Raw PDO queries for categories
    │   ├── Order.php                     # Raw PDO queries for orders
    │   ├── Attribute.php                 # Raw PDO queries for attributes
    │   └── Price.php                     # Raw PDO queries for prices
    ├── Repositories/
    │   ├── ProductRepository.php         # Maps raw product data to ProductEntity objects
    │   ├── CategoryRepository.php        # Maps raw category data to CategoryEntity objects
    │   └── OrderRepository.php           # Maps raw order data to OrderEntity objects
    ├── Entities/
    │   ├── ProductEntity.php             # Product domain object
    │   ├── CategoryEntity.php            # Category domain object
    │   ├── OrderEntity.php               # Order domain object
    │   ├── OrderItemEntity.php           # Order item domain object
    │   ├── AttributeEntity.php           # Attribute group domain object
    │   ├── AttributeItemEntity.php       # Attribute option domain object
    │   └── PriceEntity.php               # Price domain object
    └── GraphQL/
        ├── Types/
        │   ├── ProductType.php           # GraphQL Product output type
        │   ├── CategoryType.php          # GraphQL Category output type
        │   ├── AttributeType.php         # GraphQL Attribute output type
        │   ├── AttributeItemType.php     # GraphQL AttributeItem output type
        │   ├── PriceType.php             # GraphQL Price output type
        │   ├── OrderType.php             # GraphQL Order output type
        │   ├── OrderItemType.php         # GraphQL OrderItem output type
        │   ├── OrderItemInputType.php    # GraphQL input type for placing orders
        │   ├── SelectedOptionType.php    # GraphQL SelectedOption output type
        │   └── SelectedOptionInputType.php # GraphQL input type for selected options
        └── Resolvers/
            ├── ProductResolver.php       # Resolves product queries
            ├── CategoryResolver.php      # Resolves category queries
            └── OrderResolver.php         # Resolves order mutations
```

---

## Architecture

The application follows a clean layered architecture with clear separation of responsibilities:

```
HTTP Request
    ↓
index.php  ← FastRoute dispatcher, CORS headers, OPTIONS preflight handling
    ↓
Controller\GraphQL  ← Parses GraphQL query, builds schema, executes query
    ↓
Resolvers  ← CategoryResolver / ProductResolver / OrderResolver
    ↓
Repositories  ← Convert raw DB arrays into typed Entity objects
    ↓
Models  ← Raw PDO queries against MySQL (fetch, insert)
    ↓
Entities  ← Typed domain objects with getters/setters, implement JsonSerializable
    ↓
GraphQL Types  ← Define the shape of the GraphQL response
    ↓
JSON Response
```

### Key Design Patterns

**Singleton** — `Database` maintains a single PDO connection for the entire request lifecycle, preventing redundant connections:
```php
$db = Database::getInstance(); // always returns the same PDO instance
```

**Repository Pattern** — Repositories sit between Models and Resolvers. Models return raw associative arrays from PDO; Repositories convert them into typed Entity objects:
```php
// Model returns raw array
$raw = ProductModel::fetchAll(); // [['id' => 1, 'name' => '...'], ...]

// Repository maps to entities
$entities = ProductRepository::all(); // [ProductEntity, ProductEntity, ...]
```

**Entity Pattern** — All domain objects are fully encapsulated with private properties, public getters/setters, and implement `\JsonSerializable` for clean serialization.

**No Framework** — Routing is handled by `nikic/fast-route` only. No Laravel, Symfony, Slim, or any other framework is used.

---

## GraphQL Schema

The endpoint is `/graphql` and accepts `POST` requests with a JSON body containing a `query` field.

### Queries

**Get all products:**
```graphql
{
  products {
    product_id
    id
    name
    brand
    category
    inStock
    description
    attributes {
      id
      name
      type
      items {
        id
        value
        displayValue
      }
    }
    prices {
      amount
      currency
    }
    gallery
  }
}
```

**Get products by category:**
```graphql
{
  products(category: "tech") {
    id
    name
    inStock
    prices {
      amount
      currency
    }
  }
}
```

**Get a single product by ID:**
```graphql
{
  products(id: "apple-imac-2021") {
    id
    name
    description
    gallery
  }
}
```

**Get all categories:**
```graphql
{
  categories {
    id
    name
  }
}
```

**Get a category by ID:**
```graphql
{
  categories(id: "1") {
    id
    name
  }
}
```

### Mutations

**Place an order:**
```graphql
mutation {
  placeOrder(
    items: [
      {
        productId: 1,
        quantity: 2,
        selectedOptions: [
          { name: "Color", value: "#44FF03" },
          { name: "Capacity", value: "512G" }
        ]
      }
    ],
    total: 199.99
  ) {
    id
    total
    createdAt
    items {
      product_id
      quantity
      selectedOptions {
        name
        value
      }
    }
  }
}
```

---

## Database Schema

| Table | Description |
|---|---|
| `categories` | Product categories (e.g. all, clothes, tech) |
| `products` | Core product data — name, brand, description, stock status |
| `attributes` | Attribute groups per product (e.g. Size, Color) |
| `attribute_items` | Individual attribute options (e.g. S, M, L or #44FF03) |
| `prices` | Product prices with currency symbol |
| `galleries` | Product image URLs |
| `orders` | Placed orders with total amount and timestamp |
| `order_items` | Items within an order, with quantity and selected options stored as JSON |

---

## Environment Variables

The application reads database credentials from environment variables using `getenv()`. For local development, create a `.env` file in the project root:

```env
MYSQLHOST=your_database_host
MYSQLDATABASE=your_database_name
MYSQLUSER=your_database_user
MYSQLPASSWORD=your_database_password
MYSQLPORT=3306
```

On Railway, these variables are automatically injected by the MySQL plugin and do not need to be set manually.

---

## Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- MySQL 5.6+

### Installation

```bash
# Install PHP dependencies
composer install
```

### Local Development

```bash
# Start the PHP built-in server
php -S localhost:8000 -t .
```

The GraphQL endpoint will be available at:
```
http://localhost:8000/graphql
```

### Testing the API

You can test the endpoint using curl:
```bash
curl -X POST http://localhost:8000/graphql \
  -H "Content-Type: application/json" \
  -d '{"query": "{ categories { id name } }"}'
```

---

## Deployment

This project is deployed on **Railway** using Docker.

### Dockerfile

```dockerfile
FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql mysqli mbstring \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html/
WORKDIR /var/www/html

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8080
CMD ["php", "-S", "0.0.0.0:8080", "-t", "."]
```

### Deployment Steps

1. Push your code to GitHub
2. Go to [railway.app](https://railway.app) and create a new project
3. Select **"Deploy from GitHub repo"** and choose your repository
4. Add a **MySQL** database from the Railway dashboard (**New → Database → MySQL**)
5. Railway automatically injects `MYSQLHOST`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`, and `MYSQLPORT` into your service
6. Railway detects the `Dockerfile` and deploys automatically on every `git push`

### CORS

CORS headers are set in `index.php` to allow cross-origin requests from any origin. OPTIONS preflight requests are handled before routing so browsers can complete the CORS handshake:

```php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}
```

---

## Standards & Conventions

- **PSR-4** autoloading — `App\` namespace maps to `src/` directory
- **PSR-12** code style
- All entities implement `\JsonSerializable`
- No procedural code outside `index.php` bootstrap
- No backend frameworks used
- Composer post-install/post-update hooks run `composer dump-autoload -o` automatically
