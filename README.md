## Laravel Nullable Attributes

Package for auto generation and population the database columns as nullable.

### Installation

```bash
composer require mcmatters/laravel-nullable-attributes
```

If you use Laravel 5.5 or higher you may skip this step.

Include the service provider within your `config/app.php` file.

```php
'providers' => [
    McMatters\NullableAttributes\ServiceProvider::class,
]
```

Publish the configuration.

```bash
php artisan vendor:publish --provider="McMatters\NullableAttributes\ServiceProvider"
```

Then open the `config/nullable-attributes.php` and configure paths where your models are locating.

### Requirements

This package requires php `7.0` or higher and Laravel `5.2` or higher. It was tested with Laravel `5.2` and higher.

### Usage

You need to run the command `php artisan nullable-attributes:cache` every time, when you rebuild your database schema.
