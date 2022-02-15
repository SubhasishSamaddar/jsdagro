<?php
namespace App\Http\ViewComposers;

use App\Navigation;
use App\Contentblock;
use App\Banner;
use App\ImageCategory;
use App\Blog;
use App\BlogCategory;
use DB;
class NavComposer
{
    public function compose($view)
    {
        $header_menu = Navigation::where('position_block','header')->orderBy('position_order')->get();
        $footer_menu = Navigation::where('position_block','footer')->orderBy('position_order')->get();


        $view->with('header_menu', $header_menu)
            ->with('footer_menu', $footer_menu);
    }
}
