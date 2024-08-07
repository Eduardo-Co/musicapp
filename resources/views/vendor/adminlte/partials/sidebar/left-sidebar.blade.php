<aside class="main-sidebar {{ config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4') }}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
              
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
                {{-- para user --}}


                
                {{-- para adm --}}
                @can('is_admin')
                    <li class="nav-item">
                        <a href="{{ route('admin.user') }}" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.artists') }}" class="nav-link">
                            <i class="nav-icon fas fa-headphones"></i>
                            <p>
                                Artists
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.musics') }}" class="nav-link">
                            <i class="nav-icon fas fa-music"></i>
                            <p>
                                Songs
                            </p>
                        </a>
                    </li>
                @endcan
            </ul>
        </nav>
    </div>

</aside>
