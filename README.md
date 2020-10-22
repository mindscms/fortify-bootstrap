# Fortify Bootstrap

## Introduction

Authentication scaffolding with Bootstrap 4. 

### Installation

You have to install [Laravel Fortify](https://github.com/laravel/fortify), and complete all setup without add blades calling in App\Providers\FortifyServiceProvider.php.

To get started, install package using composer:

```bash
composer require mindscms/fortify-bootstrapsss
```

Next, Run install artisan command to publish bootstrap scaffolding to application

```bash
php artisan fortify:bootstrapsss
npm install && npm run dev
```

This command will update package.json with Bootstrap dependencies, update resources/sass and resources/js, and resources/views with authentication views like login, register, reset password request, layouts files etc.

## Build With

[Laravel](https://github.com/laravel/laravel)  
[Laravel Fortify](https://github.com/laravel/fortify)  
[Bootstrap](http://getbootstrap.com)

## Useful Links

[Official Laravel Ui Package](https://github.com/laravel/ui)

## Contributing

Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

## License

[MIT](https://choosealicense.com/licenses/mit/)
