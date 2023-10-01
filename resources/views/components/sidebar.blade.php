 <aside class="left-sidebar">
     <div>
         <div class="brand-logo d-flex align-items-center justify-content-between">
             <a href="{{ url('/') }}" class="text-nowrap logo-img mx-auto">
                 <img src="{{ asset('img/logo.png') }}" width="100" alt="" />
             </a>
             <div class="close-btn d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                 <i class="ti ti-x fs-8"></i>
             </div>
         </div>
         <div class="search_menu pb-3">
             <label for="search_menu" class="nav-small-cap px-0">
                 <span class="hide-menu">Search</span>
             </label>
             <input type="search" name="search_menu" id="search_menu" class="form-control">
         </div>
         <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
             <ul id="sidebarnav">
                 @foreach ($menu_items as $menu => $value)
                     @if (count($value) > 0)
                         <li class="nav-small-cap">
                             <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                             <span class="hide-menu">{{ $menu }}</span>
                         </li>
                         @foreach ($value as $item)
                             <li class="sidebar-item">
                                 <a class="sidebar-link {{ request()->is($item->menu_item_link) || request()->is($item->menu_item_link . '/*') ? 'active' : '' }}"
                                     href="{{ url($item->menu_item_link) }}">
                                     <span>
                                         <i class="{{ $item->menu_icon }}"></i>
                                     </span>
                                     <span class="hide-menu">{{ $item->menu_item_name }}</span>
                                 </a>
                             </li>
                         @endforeach
                     @endif
                 @endforeach
             </ul>
         </nav>
     </div>
 </aside>
