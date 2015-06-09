<?php

namespace Illuminate3\Content\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate3\Content\Model\Content;
use Illuminate3\Pages\Model\Page;
use Illuminate3\Pages\Model\Section;
use Illuminate\Database\Eloquent\Model;
use Input, App, Redirect, Route;

/**
 * If a page is created with no block yet, add a content block on that page
 * with the same controller. This gives the user more control on where to
 * place the content later on.
 */
class AddContentOnPage
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('page.createWithContent', array($this, 'onCreateWithContent'));
	}

	/**
	 * @param Page $page
	 */
	public function onCreateWithContent(Page $page)
	{
		// If the page doesn't have a controller, then we can do nothing
		if(!$page->controller || !$page->layout) {
			return;
		}

		// Check if there already is content attached to this page.
		// If so, then we don't have to add new content.
		if(Content::wherePageId($page->id)->first()) {
			return;
		}

		// Get the main content section
		$section = Section::whereName('content')->first();

		// Create the new content
		$content = new Content;
		$content->page()->associate($page);
		$content->section()->associate($section);
		$content->controller = $page->controller;
		$content->save();
	}

}