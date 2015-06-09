<?php

namespace Illuminate3\Content\Subscriber;

use Illuminate\Events\Dispatcher as Events;
use Illuminate3\Content\Model\Content;
use DB;

/**
 * If a page is created with no block yet, add a content block on that page
 * with the same controller. This gives the user more control on where to
 * place the content later on.
 */
class ReorderBlocksInSection
{
	/**
	 * Register the listeners for the subscriber.
	 *
	 * @param Events $events
	 */
	public function subscribe(Events $events)
	{
		Content::creating(function($content) {

			$position = DB::table($content->getTable())
				->whereSectionId($content->section_id)
				->wherePageId($content->page_id)
				->count();

			$content->position = $position;

		});

		Content::updated(function($content) {

			DB::table($content->getTable())
				->where('position', '>=', $content->position)
				->where('id', '!=', $content->id)
				->increment('position', 1);

		});
	}

}