<?php

namespace Illuminate3\Content\Model;

use Illuminate3\Content\Model\Page as Page;

class PageRepository
{
	/**
	 * @param string $alias
	 * @param string $method
	 * @return Page|null
	 */
	static public function findPageByAliasAndMethod($alias, $method)
	{
		return Page::whereAlias($alias)->whereMethod($method)->with('content', 'content.section', 'layout', 'layout.sections')->first();
	}

}

