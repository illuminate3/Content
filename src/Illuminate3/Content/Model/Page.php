<?php

namespace Illuminate3\Content\Model;

use Illuminate3\Pages\Model\Page as BasePage;

class Page extends BasePage
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function content()
    {
        return $this->hasMany('Illuminate3\Content\Model\Content');
    }


}

