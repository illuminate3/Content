<?php namespace Illuminate3\Content;

use Illuminate\Support\ServiceProvider;
use Route;

class ContentServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
            $this->package('illuminate3/content');

            $this->app->register('DeSmart\Layout\LayoutServiceProvider');
            $this->app->register('DeSmart\ResponseException\ResponseExceptionServiceProvider');
            
            // Every route with the parameter {content} will now have
            // the Content model out of the box.
            Route::model('content', 'Illuminate3\Content\Model\Content');
        }

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return array('illuminate3/content');
	}

}