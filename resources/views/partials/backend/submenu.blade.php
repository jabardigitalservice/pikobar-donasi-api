<ul class="treeview-menu">
    @if(isset($menu))
        @foreach($menu as $submenu)
            <li class="{{ (request()->is('admin/'.$submenu['url'])) ? 'active' : '' }}">
                <a href="{{ url('admin/'.$submenu['url']) }}">
                    <i class="{{ isset( $submenu['icon']) ?$submenu['icon'] : "" }}"></i>
                    <span class="menu-title">{{ $submenu['menu_title'] }}</span>
                </a>
                @if (isset($submenu['children']))
                    @include('panels/submenu', ['menu' => $submenu['children']])
                @endif
            </li>
        @endforeach
    @endif
</ul>
