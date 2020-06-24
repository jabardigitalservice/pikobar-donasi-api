<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * Class BaseBackendController.
 *
 * @author Odenktools Technology
 * @license MIT
 * @copyright (c) 2020, Odenktools Technology.
 *
 * @package App\Http\Controllers
 */
abstract class BaseBackendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $routes = array();

    protected $slugs = array();

    protected $views = array();

    protected $breadcrumb = null;

    protected $route;

    protected $slug;

    protected $view;

    protected $menu = null;

    public function __construct()
    {
        $this->routes = [
            'frontend' => 'frontend::',
            'backend' => 'backend::',
        ];
        $this->slugs = [
            'frontend' => '',
            'backend' => 'admin/',
        ];
        $this->views = [
            'frontend' => 'frontend.',
            'backend' => 'modules.backend.',
        ];
    }

    protected function breadcrumbs($children = null)
    {
        $breadcrumb = null;
        if (!empty($children)) {
            $breadcrumb .= '<ol class="breadcrumb">';
            $breadcrumb .= '<li><a href="' . route($this->routes['backend'] . 'home') . '"><i class="fa fa-home fa-fw"></i>' . 'Home' . '</a></li>';
            $breadcrumb .= $children;
            $breadcrumb .= '</ol>';
        }
        return $breadcrumb;
    }

    protected function share()
    {
        view()->share([
            'menu' => $this->menu,
            'route' => $this->route,
            'slug' => $this->slug,
            'view' => $this->view,
        ]);
    }
}
