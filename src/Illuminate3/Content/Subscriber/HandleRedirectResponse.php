<?php

namespace Illuminate3\Content\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate\Http\RedirectResponse;
use DeSmart\ResponseException\Exception as ResponseException;
use Route;

/**
 * 
 * If a page block or a default route returns a redirect
 * response, then redirect with the redirect exception.
 * 
 * There are many cases where the normal Redirect response
 * won't work because of dispatching multiple routes. This
 * subscriber fixes that.
 * 
 */
class HandleRedirectResponse
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('content.dispatch.renderContent', array($this, 'handleDispatchResponse'));
		
        Route::after(array($this, 'handleRouteResponse'));
	}
    
	/**
	 * @param mixed $response
	 */
	public function handleRouteResponse($request, $response)
	{
        unset($request); // Use in conde for keeping code quality
        
		// Catch a Redirect response
		if($response instanceof RedirectResponse) {
			ResponseException::chain($response)->fire();
		}
	}

	/**
	 * @param mixed $response
	 */
	public function handleDispatchResponse($response)
	{
		// Catch a Redirect response
		if($response instanceof RedirectResponse) {
			ResponseException::chain($response)->fire();
		}
	}

}