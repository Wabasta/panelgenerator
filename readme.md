## Panel Generator

__IMPORTANT__: This package has been updated to work with newest Laravel versions.

Finally, see free alternatives in this article on Laravel News: [13 Laravel Admin Panel Generators](https://laravel-news.com/13-laravel-admin-panel-generators)

## Installation

### Please note: This package requires fresh Laravel installation and is not suitable for use on already existing project.
#### Also note that this package expects that laravel auth is installed & ready, but in recent latest versions of laravel auth is not included by default when you create a new project.

In that case to enable `auth` issue the following commands in the root directory of your project.
```sh
composer require laravel/ui
```
Then issue the following commands
```sh
php artisan ui bootstrap --auth
```
and then run the following to compile & build the js & css assets
```sh
npm install

npm run dev

# 'or run both instructions in one go'

npm install && npm run dev 
```

1. Install the package via composer by issuing the following command

```sh
composer require wabasta/panelgenerator
```
2. Add 
```sh
Wabasta\panelgenerator\PanelGeneratorServiceProvider::class,
```
to your `\config\app.php` providers **after `App\Providers\RouteServiceProvider::class,`** otherwise you will not be able to add new ones to freshly generated controllers.

3. Configure your .env file with correct database information
4. Run 
```sh
php artisan panelgenerator:install
```
and fill the required information

5. Register middleware 
```sh
'role'     => \Wabasta\PanelGenerator\Middleware\HasPermissions::class,
```
in your `App\Http\Kernel.php` at `$routeMiddleware`

6. Run the project as you do for any Laravel project
```sh
php artisan serve
```

7. Access PanelGenerator panel by visiting `http://yourdomain/admin`.

## License
The MIT License (MIT). Please see [License File](license.md) for more information.

---
