<?php

namespace Illuminate3\Content\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate\View\View;

/**
 * Each time a resource page is created, we add the appropriate content on that page.
 */
class ChangeCrudTitle
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('content.dispatch.renderContent', array($this, 'onRenderContent'));
	}

	/**
	 * @param $view
	 * @param $content
	 */
	public function onRenderContent($view, $content) {

		if(!$view instanceof View) {
			return;
		}

		switch($view->getName()) {
			case 'crud::crud.index':
			case 'crud::crud.create':
			case 'crud::crud.edit':
				$view->title = $content->page->title;
				break;
		}

	}

}