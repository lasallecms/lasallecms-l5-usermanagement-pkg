<?php namespace Lasallecms\Usermanagement;

/**
 *
 * User Management package for the LaSalle Content Management System, based on the Laravel 5 Framework
 * Copyright (C) 2015  The South LaSalle Trading Corporation
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @package    User Management package for the LaSalle Content Management System
 * @version    1.0.0
 * @link       http://LaSalleCMS.com
 * @copyright  (c) 2015, The South LaSalle Trading Corporation
 * @license    http://www.gnu.org/licenses/gpl-3.0.html
 * @author     The South LaSalle Trading Corporation
 * @email      info@southlasalle.com
 *
 */

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
		$this->setupConfiguration();

        $this->setupMigrations();

		$this->setupRoutes($this->app->router);
	}

    /**
     * Setup the Configuration.
     *
     * @return void
     */
    protected function setupConfiguration()
    {
        // config filename is "auth.php" instead of "usermanagement.php" because
        // we are extracting an actual native app config file, instead of
        // creating a brand new config file
        $configuration = realpath(__DIR__.'/../config/auth.php');

        $this->publishes([
            $configuration => config_path('auth.php'),
        ]);


        $this->mergeConfigFrom($configuration, 'usermanagement');
    }

    /**
     * Setup the Migrations.
     *
     * @return void
     */
    protected function setupMigrations()
    {
        $migrations = realpath(__DIR__.'/../database/migrations');
        $this->publishes([
            $migrations    => base_path('database/migrations'),
        ]);
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

		$this->app->bind(
			'Illuminate\Contracts\Auth\Registrar',
			'Lasallecms\Usermanagement\Services\Registrar'
		);
	}


	/**
	 * Define the routes for the application.
	 *
	 * @param  \Illuminate\Routing\Router  $router
	 * @return void
	 */
	public function setupRoutes(Router $router)
	{
        $this->loadViewsFrom(realpath(__DIR__.'/../views'), 'usermanagement');

		$router->group(['namespace' => 'Lasallecms\Usermanagement\Http\Controllers'], function($router)
		{
			require __DIR__.'/Http/routes.php';
		});

	}

}