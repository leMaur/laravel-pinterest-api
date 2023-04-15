# Laravel Pinterest Api

[![Latest Version on Packagist](https://img.shields.io/packagist/v/lemaur/laravel-pinterest-api.svg?style=flat-square)](https://packagist.org/packages/lemaur/laravel-pinterest-api)
[![Total Downloads](https://img.shields.io/packagist/dt/lemaur/laravel-pinterest-api.svg?style=flat-square)](https://packagist.org/packages/lemaur/laravel-pinterest-api)
[![License](https://img.shields.io/packagist/l/lemaur/laravel-pinterest-api.svg?style=flat-square&color=yellow)](https://github.com/leMaur/laravel-pinterest-api/blob/main/LICENSE.md)
[![Tests](https://img.shields.io/github/actions/workflow/status/lemaur/laravel-pinterest-api/run-tests.yml?label=tests&style=flat-square)](https://github.com/leMaur/laravel-pinterest-api/actions/workflows/run-tests.yml)
[![GitHub Sponsors](https://img.shields.io/github/sponsors/lemaur?style=flat-square&color=ea4aaa)](https://github.com/sponsors/leMaur)
[![Trees](https://img.shields.io/badge/dynamic/json?color=yellowgreen&style=flat-square&label=Trees&query=%24.total&url=https%3A%2F%2Fpublic.offset.earth%2Fusers%2Flemaur%2Ftrees)](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9)


An Object-Oriented wrapper for consuming Pinterest API with Laravel.

It uses [Pinterest API v5](https://developers.pinterest.com/docs/api/v5/).

<br>

> **Warning**: This package DOESN'T store Pinterest's credentials, you should provide your own logic!  
> 👉 Please read carefully the [Manage credentials](#manage-credentials) section! 👈


<br>

## Support Me

Hey folks,

Do you like this package? Do you find it useful, and it fits well in your project?

I am glad to help you, and I would be so grateful if you considered supporting my work.

You can even choose 😃:
* You can [sponsor me 😎](https://github.com/sponsors/leMaur) with a monthly subscription.
* You can [buy me a coffee ☕ or a pizza 🍕](https://github.com/sponsors/leMaur?frequency=one-time&sponsor=leMaur) just for this package.
* You can [plant trees 🌴](https://ecologi.com/lemaur?r=6012e849de97da001ddfd6c9). By using this link we will both receive 30 trees for free and the planet (and me) will thank you. 
* You can "Star ⭐" this repository (it's free 😉).


<br>

## Roadmap

This package still in development.  
You can vote for endpoints not yet covered by [requesting them here](https://github.com/lemaur/laravel-pinterest-api/discussions/new?category=polls).  
If so, it would be great if you wanted [sponsor me](https://github.com/sponsors/leMaur) to support my work.

- [ ] Pins
    - [ ] list
    - [x] create
    - [ ] get
    - [x] delete
    - [ ] update
    - [ ] save
    - [ ] analytics
- [ ] Boards
    - [x] list
    - [ ] create
    - [ ] get
    - [ ] delete
    - [ ] update
    - [ ] pins
- [ ] Board Sections
- [ ] Ad Accounts
- [ ] Ad Groups
- [ ] Ads
- [ ] Audience Insights
- [ ] Audiences
- [ ] Bulk
- [ ] Campaigns
- [ ] Catalogs
- [ ] Conversion Events
- [ ] Conversion Tags
- [ ] Customer Lists
- [ ] Integrations
- [ ] Interests
- [ ] Keywords
- [ ] Media
- [x] OAuth
- [ ] Order Lines
- [ ] Product Group Promotions
- [ ] Product Groups
- [ ] Resources
- [ ] Search
- [ ] Terms
- [ ] Terms of Service
- [ ] User Account


<br>

## Installation

You can require the package via composer:
```bash
composer require lemaur/laravel-pinterest-api
```

You can install it with:
```bash
php artisan pinterest-api:install
```

<br>

## Manage credentials

[Pinterest API Authentication](https://developers.pinterest.com/docs/getting-started/authentication/) follows the [OAuth2 standard](https://datatracker.ietf.org/doc/html/rfc6749/). 

The package contains helpful methods to obtain the credentials from Pinterest OAuth server, but where to store them is up to you!
Every project is different, with different requirements. You can store credentials on disk, or you can write them on DB and so on...

> **Note**: You'll need to register a new app on Pinterest and get the app ID and secret key.  
> But don't worry, we'll do it in the [next section](#require-pinterest-access).

Here I'll show you how to configure your project to manage credentials.

The authentication flow returns an object containing `access_token` and `refresh_token` within the expiration timestamps and other information.

Here an example:
```json
{
    "access_token": "{an access token string prefixed with 'pina'}",
    "refresh_token": "{a refresh token string prefixed with 'pinr'}",
    "response_type": "authorization_code",
    "token_type": "bearer",
    "expires_in": 2592000,
    "refresh_token_expires_in": 31536000,
    "scope": "boards:read boards:write pins:read"
}
```

The package emits `Lemaur\Pinterest\Events\CredentialsRetrieved::class` event within `Lemaur\Pinterest\Data\OAuthData` object when it receives the credentials.
You can listen for this event in your project and store the credentials where you want.

To do that, you need to create a new listener 
```bash
php artisan make:listener --event=\\Lemaur\\Pinterest\\Events\\CredentialsRetrieved StorePinterestCredentials
``` 

And inside the `handle` method you can decide where to store the credentials.
```php
// file: app/Listeners/StorePinterestCredentials.php

/**
 * Handle the event.
 *
 * @param  \Lemaur\Pinterest\Events\CredentialsRetrieved  $event
 * @return void
 */
public function handle(CredentialsRetrieved $event)
{
    // Store the credentials from `$event->oauth`.
    // Where `$event->oauth` is an instance of `Lemaur\Pinterest\Data\OAuthData`.
    
    /**
     * For e.g. you can extend your User model by adding a json column `pinterest_credentials` 
     * and store the credentials for each authenticated user.
     *
     * \Illuminate\Support\Facades\Auth::user()->update([
     *     'pinterest_credentials' = $event->oauth->toArray(),
     * ]);
     */
}
```

Don't forget to register the listener in the `App\Providers\EventServiceProvider`.
```php
// file: app/Providers/EventServiceProvider.php

/**
 * The event listener mappings for the application.
 *
 * @var array<class-string, array<int, class-string>>
 */
protected $listen = [
    \Lemaur\Pinterest\Events\CredentialsRetrieved::class => [
        \App\Listeners\StorePinterestCredentials::class,
    ],
];
```

Now it's time to edit the service provider.
Open `App\Providers\PinterestServiceProvider`, and inside the `register` method you will find a predefined implementation. 

As you can see in the `@TODO` comment, here is where you should pass the credentials you previously stored.  
```php
public function register(): void
{
    $this->app->singleton(PinterestContract::class, fn (Application $app) => new PinterestService(
        config: ConfigData::fromConfig($app['config']['pinterest']),
        oauth: OAuthData::from([]), // @TODO: <-- please fill in the credentials...
    ));
}
```

In the previous examples we stored the credentials in the user's table.  
So here, we can fetch them from the authenticated user.
```php
public function register(): void
{
    $this->app->singleton(PinterestContract::class, fn (Application $app) => new PinterestService(
        config: ConfigData::fromConfig($app['config']['pinterest']),
        oauth: OAuthData::from(\Illuminate\Support\Facades\Auth::user()->pinterest_credentials),
    ));
}
```


<br>

## Require Pinterest Access

### Register and get your app ID and secret key

> **Note**: internally `app ID` is `client_id`, internally `secret key` is `client_secret`! 

1. Log into www.pinterest.com; open a new tab with the account that you’ll use to manage your apps.
2. Go to My Apps.
3. Select Connect app and complete the request form with your app information.
4. Submit your request to get trial access.
5. As soon as Pinterest completes the review, Pinterest will notify you by email.
6. Once you have received the email approval, go to My Apps to see your app ID and secret key.

It's now time to copy/paste the app ID and secret key to the .env file.

```dotenv
PINTEREST_API_CLIENT_ID="app ID"
PINTEREST_API_CLIENT_SECRET="secret key"
```

### Configure the redirect URI
The package provides a default redirect URI `/pinterest/callack`. You are free to change it in your configuration file.

1. Back to your Pinterest account.
2. Go to My Apps and select your app.
3. Go to Configure and, in Redirect URIs, enter the desired URI and save.

> **Note**: For local development it's preferred to use `http://localhost/pinterest/callback`

```dotenv
PINTEREST_API_REDIRECT_URI=http://localhost/pinterest/callback
```

### Generate an access token

1. To start the OAuth flow and request user access run `php artisan pinterest:get-access-code-link`
2. Copy/Paste the link to your browser and follow the instructions on screen.
3. At the end of the process you will see a white page with a text saying "All good! You can close this page."

Now you are ready to call the Pinterest API.


<br>

# NOTES

Pinterest provides an `access_token` valid for 30 days and a `refresh_token` valid for 1 year.   
The package automatically retrieves a new access token every time it expires, as long as the refresh token is still valid.  
After 1 year, when you try to call an API endpoint, the package will throw a `OAuthException` with a message informing you to request a new access code.

If you are curious you can [read the codebase to learn more](https://github.com/leMaur/laravel-pinterest-api/blob/main/src/Services/Concerns/BuildBaseRequest.php#L18).

Or you can use `Lemaur\Pinterest\Facades\Pinterest::oauth()->credentials()->accessTokenExpiresIn` to get the number of days before the access token will expire.  
The same for the refresh token `Lemaur\Pinterest\Facades\Pinterest::oauth()->credentials()->refreshTokenExpiresIn`.

<br>

## Testing

The package offers a nifty fake method to help you write your tests.  
If you want some examples, I suggest to look at [the package test suite](https://github.com/leMaur/laravel-pinterest-api/tree/main/tests). 

```php
Pinterst::assertSent(callable $callback): void
```

```php
Pinterst::assertNotSent(callable $callback): void
```

```php
Pinterst::assertSentCount(int $count): void
```

```php
Pinterst::assertNothingSent(): void
```

<br>

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

<br>

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

<br>

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

<br>

## Credits

- [Maurizio](https://github.com/lemaur)
- [All Contributors](../../contributors)

<br>

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
