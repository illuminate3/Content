<?php

namespace Illuminate3\Content\Controller;

use Illuminate3\Content\Model\Content;
use App, Form, Input, Redirect, Request, Layout, Session;

class ConfigController extends \BaseController
{

	/**
	 * @param Content $content
	 * @return mixed
	 */
	public function store()
	{   
        $params = array_diff_key(Input::all(), array_flip(array('page_id', 'block_id', 'section_id', '_token')));
                
		// Store the content from the configuration form
        $content = Content::create(Input::all());
		$content->params = Input::all();
		$content->save();

		// Redirect to the page where the content is placed on.
		return Redirect::back();
	}
    
	/**
	 * @param Content $content
	 * @return mixed
	 */
	public function edit(Content $content)
	{
		// Get the previous page and store it in a session. We need this page later to go back to.
		$referer = Request::header('referer');
		Session::put('referer', $referer);

		$block = $content->block;

		// Check if there is a form configuration present for this block
		list($controller, $action) = explode('@', $block->controller);
		if(!method_exists($controller, $action . 'Config')) {
			Redirect::to($referer);
		}

		// Build the configuration form
		$fb = App::make('Illuminate3\Form\FormBuilder');
		$fb->route('admin.content.config.update', $content->id);
        $fb->method('put');

		// Now call the config method and give the form to the user.
		// The user can add elements to the configuration form.
		Layout::dispatch($block->controller . 'Config', compact('fb'));

		// After the form is completely built we can set the values
		// we might already have for this content block.
		$fb->defaults($content->params);
        
		// Return the html with the form
		return $fb->build();
	}

	/**
	 * @param Content $content
	 * @return mixed
	 */
	public function update(Content $content)
	{
		// Store the content from the configuration form
		$content->params = Input::all();
		$content->save();

		// Redirect to the page where the content is placed on.
		return Redirect::to(Session::get('referer') . '?mode=content');
	}

}