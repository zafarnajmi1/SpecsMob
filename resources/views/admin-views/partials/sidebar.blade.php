<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between">
                <div class="logo">
                    <a href="{{ route('admin.dashboard') }}">
                        <img src="{{ asset('logo.png') }}" style="width:70%; height:auto;"
                            alt="Logo">
                    </a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">
                <li class="sidebar-title">Menu</li>

                {{-- Dashboard --}}
                @can('access_dashboard')
                    <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                            <i class="bi bi-grid-fill"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                @endcan

                {{-- Content Management --}}
                @canany(['article_view', 'article_create', 'article_edit', 'article_delete', 'tag_manage'])
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('admin.articles.*', 'admin.tags.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-newspaper"></i>
                            <span>Content Management</span>
                        </a>
                        <ul class="submenu">
                            {{-- Articles --}}
                            @can('article_view')
                                <li class="submenu-item {{ request()->routeIs('admin.articles.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.articles.index') }}">All Articles</a>
                                </li>
                            @endcan
                            @can('article_create')
                                <li class="submenu-item {{ request()->routeIs('admin.articles.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.articles.create') }}">Add New Article</a>
                                </li>
                            @endcan

                            {{-- Article Types --}}
                            <li class="submenu-item {{ request()->query('type') == 'news' ? 'active' : '' }}">
                                <a href="{{ route('admin.articles.index', ['type' => 'news']) }}">News Articles</a>
                            </li>
                            <li class="submenu-item {{ request()->query('type') == 'article' ? 'active' : '' }}">
                                <a href="{{ route('admin.articles.index', ['type' => 'article']) }}">Blog Posts</a>
                            </li>
                            <li class="submenu-item {{ request()->query('type') == 'featured' ? 'active' : '' }}">
                                <a href="{{ route('admin.articles.index', ['type' => 'featured']) }}">Featured Content</a>
                            </li>

                            {{-- Tags --}}
                            @can('tag_manage')
                                <li class="submenu-item {{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.tags.index') }}">Tags Management</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- SEO Management --}}
                @can('seo_manage')
                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-search"></i>
                            <span>SEO Management</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ request()->routeIs('admin.seo.global') ? 'active' : '' }}">
                                <a href="{{ route('admin.seo.global') }}">Global SEO Settings</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.seo.schema') ? 'active' : '' }}">
                                <a href="{{ route('admin.seo.schema') }}">Schema & Structured Data</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.seo.sitemap') ? 'active' : '' }}">
                                <a href="{{ route('admin.seo.sitemap') }}">Sitemap & Robots</a>
                            </li>
                        </ul>
                    </li>
                @endcan


                {{-- Review Management --}}
                @canany(['review_view', 'review_create', 'review_edit', 'review_delete'])
                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-phone-fill"></i>
                            <span>Review Management</span>
                        </a>
                        <ul class="submenu">
                            @can('review_view')
                                <li class="submenu-item {{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.reviews.index') }}">All Reviews</a>
                                </li>
                            @endcan

                            @can('review_create')
                                <li class="submenu-item {{ request()->routeIs('admin.reviews.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.reviews.create') }}">Add New Review</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany


                {{-- Video Management --}}
                @canany(['video_view', 'video_create', 'video_edit', 'video_delete'])
                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-play-btn-fill"></i>
                            <span>Video Management</span>
                        </a>
                        <ul class="submenu">
                            @can('video_view')
                                <li class="submenu-item {{ request()->routeIs('admin.videos.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.videos.index') }}">All Videos</a>
                                </li>
                            @endcan
                            @can('video_create')
                                <li class="submenu-item {{ request()->routeIs('admin.videos.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.videos.create') }}">Add New Video</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Brand Management --}}
                @canany(['brand_view', 'brand_create', 'brand_edit', 'brand_delete'])
                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-tags-fill"></i>
                            <span>Brand Management</span>
                        </a>
                        <ul class="submenu">
                            @can('brand_view')
                                <li class="submenu-item {{ request()->routeIs('admin.brands.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.brands.index') }}">All Brands</a>
                                </li>
                            @endcan
                            @can('brand_create')
                                <li class="submenu-item {{ request()->routeIs('admin.brands.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.brands.create') }}">Add New Brand</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Home Review Slider --}}
                @canany(['homereview_slider_view', 'homereview_slider_create', 'homereview_slider_edit', 'homereview_slider_delete'])
                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.homereview-slider.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-images"></i>
                            <span>Home Review Slider</span>
                        </a>
                        <ul class="submenu">
                            @can('homereview_slider_view')
                                <li
                                    class="submenu-item {{ request()->routeIs('admin.homereview-slider.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.homereview-slider.index') }}">All Sliders</a>
                                </li>
                            @endcan
                            @can('homereview_slider_create')
                                <li
                                    class="submenu-item {{ request()->routeIs('admin.homereview-slider.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.homereview-slider.create') }}">Add New Slider</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Device Management --}}
                @canany(['device_view', 'device_create', 'device_edit', 'device_delete', 'devicetype_view', 'devicetype_create'])
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('admin.devices.', 'admin.devicetypes.') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-phone-fill"></i>
                            <span>Device Management</span>
                        </a>
                        <ul class="submenu">
                            {{-- Device Types --}}
                            @can('devicetype_view')
                                <li class="submenu-item {{ request()->routeIs('admin.devicetypes.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.devicetypes.index') }}">Device Types</a>
                                </li>
                            @endcan

                            {{-- Devices --}}
                            @can('device_view')
                                <li class="submenu-item {{ request()->routeIs('admin.devices.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.devices.index') }}">All Devices</a>
                                </li>
                            @endcan
                            @can('device_create')
                                <li class="submenu-item {{ request()->routeIs('admin.devices.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.devices.create') }}">Add New Device</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany



                {{-- Pricing & Market Setup --}}
                @canany(['country_view', 'currency_view', 'store_view'])
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('admin.countries.*', 'admin.currencies.*', 'admin.stores.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-cash-stack"></i>
                            <span>Pricing Setup</span>
                        </a>
                        <ul class="submenu">
                            @can('country_view')
                                <li class="submenu-item {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.countries.index') }}">Countries</a>
                                </li>
                            @endcan
                            @can('currency_view')
                                <li class="submenu-item {{ request()->routeIs('admin.currencies.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.currencies.index') }}">Currencies</a>
                                </li>
                            @endcan
                            @can('store_view')
                                <li class="submenu-item {{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.stores.index') }}">Stores</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Reviews & Comments --}}
                @canany(['review_manage', 'opinion_moderate', 'comment_moderate'])
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('admin.opinions.*', 'admin.comments.*', 'admin.article-comments.*', 'admin.reviews.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-chat-left-text-fill"></i>
                            <span>Reviews & Comments</span>
                        </a>
                        <ul class="submenu">
                            {{-- Professional Reviews --}}
                            @can('review_manage')
                                <li class="submenu-item {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.reviews.index') }}">Professional Reviews</a>
                                </li>
                            @endcan

                            {{-- User Opinions Moderation --}}
                            @can('opinion_moderate')
                                <li class="submenu-item {{ request()->routeIs('admin.opinions.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.opinions.index') }}">User Opinions</a>
                                </li>
                            @endcan

                            {{-- Comments Moderation --}}
                            @can('comment_moderate')
                                <li class="submenu-item {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.comments.index') }}">Comments Moderation</a>
                                </li>
                            @endcan

                            {{-- Article Comments --}}
                            <li class="submenu-item {{ request()->routeIs('admin.article-comments.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.article-comments.index') }}">Article Comments</a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                {{-- Deals & Offers --}}
                @canany(['deal_view', 'deal_create', 'deal_edit', 'deal_delete'])
                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.deals.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-percent"></i>
                            <span>Deals & Offers</span>
                        </a>
                        <ul class="submenu">
                            @can('deal_view')
                                <li class="submenu-item {{ request()->routeIs('admin.deals.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.deals.index') }}">All Deals</a>
                                </li>
                            @endcan
                            @can('deal_create')
                                <li class="submenu-item {{ request()->routeIs('admin.deals.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.deals.create') }}">Add New Deal</a>
                                </li>
                            @endcan
                            <li class="submenu-item {{ request()->routeIs('admin.deals.featured') ? 'active' : '' }}">
                                <a href="{{ route('admin.deals.index', ['featured' => true]) }}">Featured Deals</a>
                            </li>
                        </ul>
                    </li>
                @endcanany

                {{-- Analytics & Reports --}}
                @can('reports_view')
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('admin.analytics.*', 'admin.reports.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-graph-up"></i>
                            <span>Analytics</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ request()->routeIs('admin.analytics.overview') ? 'active' : '' }}">
                                <a href="{{ route('admin.analytics.overview') }}">Overview</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.analytics.devices') ? 'active' : '' }}">
                                <a href="{{ route('admin.analytics.devices') }}">Device Analytics</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.analytics.articles') ? 'active' : '' }}">
                                <a href="{{ route('admin.analytics.articles') }}">Content Analytics</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                                <a href="{{ route('admin.reports.index') }}">Reports</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- User Management (Admin Only) --}}
                @canany(['user_view', 'user_create', 'user_edit', 'user_delete'])
                    <li class="sidebar-item has-sub {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-people-fill"></i>
                            <span>User Management</span>
                        </a>
                        <ul class="submenu">
                            @can('user_view')
                                <li class="submenu-item {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                    <a href="{{ route('admin.users.index') }}">All Users</a>
                                </li>
                            @endcan
                            @can('user_create')
                                <li class="submenu-item {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                    <a href="{{ route('admin.users.create') }}">Add New User</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- Settings (Admin Only) --}}
                @can('settings_manage')
                    <li
                        class="sidebar-item has-sub {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <a href="#" class='sidebar-link'>
                            <i class="bi bi-gear-fill"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item {{ request()->routeIs('admin.settings.general') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.general') }}">General Settings</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.settings.social') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.social') }}">Social Links</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.settings.ads') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.ads') }}">Ad Management</a>
                            </li>
                            <li class="submenu-item {{ request()->routeIs('admin.settings.mail') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.mail') }}">Mail Configuration</a>
                            </li>
                        </ul>
                    </li>
                @endcan

                {{-- Logout --}}
                <li class="sidebar-item">
                    <a href="{{ route('admin.logout') }}" class='sidebar-link'
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>

                <li class="sidebar-item has-sub user-menu mt-auto">
                    <div class="sidebar-link user-info">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-sm bg-primary text-white d-flex align-items-center justify-content-center overflow-hidden flex-shrink-0" style="width: 32px; height: 32px; border-radius: 50%;">
                                @php
                                    $sidebarImage = auth()->user()->image;
                                    $isSidebarImageExists = $sidebarImage && (str_starts_with($sidebarImage, 'http') || Storage::disk('public')->exists($sidebarImage));
                                @endphp
                                @if($isSidebarImageExists)
                                    <img src="{{ auth()->user()->image_url }}" alt="User"
                                        style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <span class="text-xs">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                                @endif
                            </div>
                            <div class="ms-3">
                                <h6 class="mb-0 text-sm">{{ auth()->user()->name }}</h6>
                                <small
                                    class="text-muted text-xs">{{ auth()->user()->roles->first()->name ?? 'Administrator' }}</small>
                            </div>
                        </div>
                    </div>

                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="{{ route('admin.profile.edit') }}"><i class="bi bi-person me-2"></i>Profile</a>
                        </li>
                        <li class="submenu-item">
                            <a href="{{ route('admin.logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>