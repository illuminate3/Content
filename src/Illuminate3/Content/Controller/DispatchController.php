<?php

namespace Illuminate3\Content\Controller;

use Illuminate\Database\Eloquent\Collection;
use Illuminate3\Pages\Model\Page;
use Illuminate3\Pages\Model\Layout;
use Illuminate3\Pages\Model\Section;
use Illuminate3\Content\Model\Content;
use View,
    App,
    URL,
    Route,
    Session,
    Event;

class DispatchController extends \BaseController
{

    /**
     * @param Page $page
     * @return View
     */
    public function renderPage(Page $page)
    {
        if(!$page->layout && $page->controller) {
            return $this->renderController($page->controller);
        }
        
        return $this->renderPageWithLayout($page);
    }
    
    /**
     * 
     * @param Layout $layout
     * @param Page $page
     * @return View|null
     */
    public function renderPageWithLayout(Page $page)
    {
        $layout = $page->layout;
        $vars = array();

        $content = Content::findByPage($page);

        foreach ($layout->sections as $section) {
            $vars[$section->name] = $this->renderSection($page, $section, $content);
        }

        return View::make($layout->name, $vars);        
    }

	/**
	 * @param Page       $page
	 * @param Section    $section
	 * @param Collection $content
	 * @return mixed
	 */
	public function renderSection(Page $page, Section $section, Collection $content)
    {
        $isContentMode = Session::get('mode') == 'content';
        $isModePublic = $section->isPublic();
        
        // Dispatch all the blocks in this section
        $blocks = array();        
        foreach ($content as $item) {
            if($item->section_id == $section->id) {
                $blocks[] = $this->renderContent($item);
            }
        }

		if (!$content->count() || implode('', $blocks) === '') {
			return;
		}

        if ($isContentMode) {

            // Build the form for adding a content block in this section
            $fb = App::make('Illuminate3\Content\Controller\ContentController')->init('create')->getFormBuilder();
            $fb->url(URL::route('admin.content.store'));
            $fb->defaults(array(
                'section_id' => $section->id,
                'page_id' => $section->page_id,
            ));
            $form = $fb->build();
        }

        Event::fire('content.dispatch.renderSection', array(&$blocks, $section, $page, $isContentMode, $isModePublic));
        
        return View::make('content::section', compact('blocks', 'section', 'form', 'isContentMode', 'isModePublic'));
    }

    /**
     * @param Content $content
     * @return View|null
     */
    public function renderContent(Content $content)
    {
        $isContentMode = Session::get('mode') == 'content';
		$isModePublic = $content->section->isPublic();
        $hasConfigForm = $content->hasConfigForm();

        try {

			$controller = $content->getController();
			$response = $this->renderController($controller, $content->params);

            if (!$response) {
                return;
            }
            
        } 
        catch (\RuntimeException $e) {
            $response = '--- Block not configured properly: missing required fields ---';
        }

        Event::fire('content.dispatch.renderContent', array($response, $content));

        return View::make('content::block', compact('response', 'content', 'isContentMode', 'isModePublic', 'hasConfigForm'));
    }
    
    /**
     * 
     * @param string $controller
     * @param array $params
     * @return View
     */
    public function renderController($controller, $params = array())
    {
        $params = array_merge($params, Route::getCurrentRoute()->getParameters());

        return App::make('layout')->dispatch($controller, $params);
    }

}