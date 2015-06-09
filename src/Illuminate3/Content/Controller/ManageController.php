<?php

namespace Illuminate3\Content\Controller;

use View, Request, Session, Redirect;

class ManageController extends \BaseController
{
	public function toolbar()
	{
		return View::make('content::manage.toolbar', array(
			'mode' => Session::get('mode'),
		));
	}
}