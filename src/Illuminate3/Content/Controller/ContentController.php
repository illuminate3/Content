<?php

namespace Illuminate3\Content\Controller;

use Illuminate3\Crud\CrudController;
use Illuminate3\Form\FormBuilder;
use Illuminate3\Model\ModelBuilder;
use Illuminate3\Overview\OverviewBuilder;
use Illuminate3\Content\Model\Content;
use DeSmart\ResponseException\Exception as ResponseException;
use Redirect, Request;

class ContentController extends CrudController
{

    /**
     * @param FormBuilder $fb
     */
    public function buildForm(FormBuilder $fb)
    {
        $fb->modelSelect('block_id')->model('Illuminate3\Content\Model\Block');
        $fb->hidden('layout_id');
        $fb->hidden('page_id');
        $fb->hidden('section_id');
        $fb->hidden('position')->value(0);
    }

    /**
     * @param ModelBuilder $mb
     */
    public function buildModel(ModelBuilder $mb)
    {
        $mb->name('Illuminate3\Content\Model\Content')->table('content');
    }

    /**
     * @param OverviewBuilder $ob
     */
    public function buildOverview(OverviewBuilder $ob)
    {
        
    }

    /**
     * Redirect to the content configuration form.
     *
     * Right after the content block has been created, we can hook into the
     * save method and redirect to a configuration form. There we can set
     * the needed params required for this content block to work.
     *
     * It uses the ResponseException instead of the normal Redirect object.
     * This is because a normal redirect will not work, because that
     * object is never returned as a view. But now we throw a special
     * exception that will do the trick for us.
     *
     * @param Content $content
     */
    public function onSaved(Content $content)
    {
		if(Request::ajax()) {
			return;
		}

        $redirect = Redirect::to(\URL::route('admin.content.config.edit', $content->id). '?mode=view');
        return ResponseException::chain($redirect)->fire();
    }

}