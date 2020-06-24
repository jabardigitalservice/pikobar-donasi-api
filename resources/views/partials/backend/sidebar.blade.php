@php
    $menuData = odk_admin_sidebar();
@endphp
<ul class="sidebar-menu" data-widget="tree">
    <li class="header">MODULE NAVIGATION</li>
    @if(isset($menuData))
        @foreach($menuData as $menu)
            @if(isset($menu->navheader))
                <li class="navigation-header">
                    <span>{{ $menu->navheader }}</span>
                </li>
            @else
                @if($menu['children'])
                    <li class="treeview">
                        <a href="javascript:;">
                            <i class="{{ isset($menu['icon']) ? $menu['icon'] : "" }}"></i>
                            <span>{{$menu['menu_title']}}</span>
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        @include('partials/backend/submenu', ['menu' => $menu['children']])
                    </li>
                @else
                    <li class="{{ (request()->is('admin/'.$menu['url'])) ? 'active' : '' }}">
                        <a href="{{ isset($menu['url']) ? url('admin/'.$menu['url']) : "javascript:;" }}">
                            <i class="{{ isset($menu['icon']) ? $menu['icon'] : "" }}"></i><span>{{ $menu['menu_title'] }}</span>
                            <span class="pull-right-container"></span>
                        </a>
                    </li>
                @endif
            @endif
        @endforeach
    @endif
</ul>
