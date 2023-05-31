# Job Board | Back-End API

This project is a basic job board website made powered by [Laravel](https://laravel.com/).

The job board features:

-   Payments processing through [Stripe](https://stripe.com/) using [Laravel Cashier](https://laravel.com/docs/10.x/billing).
-   Authentication using Laravel [Breeze API](https://laravel.com/docs/10.x/starter-kits#breeze-and-next).
-   Authorization using [Spatie Permission](https://spatie.be/docs/laravel-permission/).

# Filtering

The API allows you to filter the listings depending on your needs. Right now it has a few filters available that you can learn how to use them down below.

First, you should now that to use any of these filters you can send query parameters where the name of the parameters is the name of the filter and its value, the value you are searching for that field.

The filters that are available are:

### title

To filter the listings by title, you just have to pass a query parameter with the role your interested in, and it will return results with similar title names.

`http://localhost:8000/api/listings/?title=Sr. Software Engineer`

### salary

For the salary there are several options, you can either filter the salary by range using the `min_salary` and `max_salary` filters like in the example down below.

`http://localhost:8000/api/listings/?min_salary=2500&max_salary=5000`

or you can use the `salary` filter that will return results with a salary equals or greater than the value assigned to the parameter like:

`http://localhost:8000/api/listings/?salary=2500`

### company

To filter company just use the `company` filter that will return listings with very similar company names.

`http://localhost:8000/api/listings/?company=Microsoft`

### location

You can filter by location by using `location` filter, same as company and title, you will receive results with very similar location names.

### tags

To be able to filter listings by tags, you can either send an array of tags like so:

`http://localhost:8000/api/listings/?tags[0]=PHP&tags[1]=Laravel`

or a string comma-separated like in the follwing example:

`http://localhost:8000/api/listings/?tags=PHP, Laravel`

# Test API

To test this project follow the next steps:

-   Clone this repository in your local machine `git clone https://github.com/jerezjustin/job-board`.
-   Copy the `.env.example` file in the root of the project and rename `.env`.
-   Install the dependencies `composer install`.
-   Generate an application key by running `php artisan key:generate`.
-   Run the migrations `php artisan migrate` or `php artisan migrate --seed` if you want to have some fake data.
-   Finally, you can run the project using `php artisan serve`.

I have used [Postman](https://www.postman.com/) to develop this project, and you can find a `api.postman_collection`
file with all
the endpoints of the
application, import it into Postman on your local machine, and start making HTTP requests to this API.

### Generate Payment Method

To create a job listing in this project you will require a `Stripe Payment Method` you can get one by executing the
following code in [Laravel Tinker](https://laravel.com/docs/10.x/artisan#tinker):

```php
use Stripe\StripeClient;

$stripe = new StripeClient(env('STRIPE_SECRET'));

$expirationDate = fake()->creditCardExpirationDate(true);

$paymentMethod = $stripe->paymentMethods->create([
    'type' => 'card',
    'card' => [
        'number' => '4242424242424242',
        'exp_month' => $expirationDate->format('m'),
        'exp_year' => $expirationDate->format('Y'),
        'cvc' => random_int(100, 999)
    ]
]);

return $paymentMethod->id;
```

You can enter thinker console by executing `php artisan tinker` or you can use [Laravel Tinker Plugin](https://github.com/Roboroads/laravel-tinker).

### Run tests

To check everything works well I have made sure that the most important features of the application are covered by
automated tests, to execute them you can run `php artisan test`.

# Credits

This project was inspired by [Larajobs](https://larajobs.com) and [Andrew Schmelyun](https://github.
com/aschmelyun) Laravel Job Board project.

# Contributing

If you find a bug, please feel free to open an issue on this repository or create a pull requests with the changes,
I'll be looking into it as soon as I can.
