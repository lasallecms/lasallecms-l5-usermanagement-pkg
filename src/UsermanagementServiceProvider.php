<?php namespace Lasallecms\Usermanagement;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * This is the User Management service provider class.
 *
 * @author Bob Bloom <info@southlasalle.com>
 */
class UsermanagementServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;


	/**
	 * Boot the service provider.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadViewsFrom(realpath(__DIR__.'/../views'), 'usermanagement');
		$this->setupRoutes($this->app->router);
	}



	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->registerUsermanagement();
	}


	/**
	 * Register the application bindings.
	 *
	 * @return void
	 */
	private function registerUsermanagement()
	{
		$this->app->bind('contact', function($app) {
			return new Usermanagement($app);
		});
	}


	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function setupRoutes(Router $router)
	{
		$router->group(['namespace' => 'Lasallecms\Usermanagement\Http\Controllers'], function($router)
		{
			require __DIR__.'/Http/routes.php';
		});

	}

}