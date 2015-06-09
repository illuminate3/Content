<?php

namespace Illuminate3\Content\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate3\Content\Model\Content;
use Illuminate3\Content\Model\Block;
use Illuminate3\Pages\Model\Page;
use Illuminate3\Pages\Model\Section;
use Illuminate\Database\Eloquent\Model;
use Input, App, Redirect, Route;

/**
 * Each time a resource page is created, we add the appropriate content on that page.
 */
class AddContentOnResourcePage
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		$events->listen('page.createResourcePage', array($this, 'onCreateResourcePage'));
	}

	/**
	 * @param Page $page
	 */
	public function onCreateResourcePage(Page $page)
	{
		// Check if there already is content attached to this page.
		// If so, then we don't have to add new content.
		if(Content::wherePageId($page->id)->first()) {
			return;
		}

		$block = Block::whereController($page->controller)->first();
		$section = Section::whereName('content')->first();

		$content = new Content;
		$content->page()->associate($page);
		$content->section()->associate($section);

		if($block) {
			$content->block()->associate($block);
		}
		else {
			$content->controller = $page->controller;
		}

		$content->save();

	}

}