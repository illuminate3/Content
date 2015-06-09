<?php

namespace Illuminate3\Content\Controller;

use View, Request, Session, Redirect;

class SwitchController extends \BaseController
{
	public function contentMode()
	{
		if(Session::get('mode') == 'view') {
			Session::put('mode', 'content');
		}
		else {
			Session::put('mode', 'view');
		}

		return Redirect::back();
	}
}