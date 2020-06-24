@if(isset($menu))
    @foreach($menu as $submenu)
        <li class="dd-item dd3-item" data-id="{!!$submenu['id']!!}">
            <div class="dd-handle dd3-handle"></div>
            <div class="dd3-content">
                <a href="#" data-action="LOAD" data-load-to='#menu-entry' data-href='{{url('admin/sidebars/showChildForm')}}/{!!$submenu['id']!!}'>
                    <i class="{!! !empty($submenu['icon']) ? $submenu['icon'] : '' !!}"></i> {!!$submenu['menu_title']!!}
                    <span class="pull-right"><i class="fa fa-angle-double-right"></i></span>
                </a>
            </div>
            @if (isset($submenu['children']))
                <ol class="dd-list">
                    @include('modules/backend/sidebar/sub', ['menu' => $submenu['children']])
                </ol>
            @endif
        </li>
    @endforeach
@endif

