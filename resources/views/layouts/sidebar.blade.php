@php
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = Utility::languages();
@endphp


<nav class="dash-sidebar light-sidebar transprent-bg">
    <div class="navbar-wrapper">
        <div class="m-header">

            <a href="{{ route('home') }}" class="b-brand text-center">
                <!-- ========   change your logo hear   ============ -->
                @if (Utility::getsettings('dark_mode') == 'on')
                    <img src="{{ Storage::exists('logo/app-logo.png') ? Utility::getpath('logo/app-logo.png') : Storage::url('logo/app-logo.png') }}"
                        class="app-logo w-75 img_setting">
                @else
                    <img src="{{ Storage::exists('logo/app-dark-logo.png') ? Utility::getpath('logo/app-dark-logo.png') : Storage::url('logo/app-dark-logo.png') }}"
                        class="app-logo w-75 img_setting">
                @endif
            </a>
        </div>
        <div class="navbar-content">
            <ul class="dash-navbar">
                {{-- @if (tenant('id') != null) --}}

                <li
                class="dash-item dash-hasmenu {{ request()->is('risk*') || request()->is('framework*') ? 'active dash-trigger' : 'collapsed' }}">
                <a href="#!" class="dash-link"><span class="dash-micon"><i
                            class="ti" style="font-size: 10px;font-weight:800;">AI</i></span><span
                        class="dash-mtext">{{ 'AI Risks' }}</span><span class="dash-arrow"><i
                            data-feather="chevron-right"></i></span></a>
                <ul class="dash-submenu">

                    <li class="dash-item {{ request()->is('framework*') ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('risk.framework') }}">{{ __('RM Framework') }}</a>
                    </li>
                    <li class="dash-item {{ request()->is('reports') ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('risk.reports') }}">{{ __('Reports') }}</a>
                    </li>
                    <li class="dash-item {{ request()->is('insurance') ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('risk.insurance') }}">{{ __('insurance') }}</a>
                    </li>
                    <li class="dash-item {{ request()->is('documents') ? 'active' : '' }}">
                        <a class="dash-link" href="{{ route('risk.documents') }}">{{ __('Documents') }}</a>
                    </li>

                </ul>
            </li>
           {{-- @endif --}}

                <li class="dash-item dash-hasmenu ">
                    <a href="{{ route('home') }}" class="dash-link">
                        <span class="dash-micon"><i class="ti ti-home"></i></span>
                        <span class="dash-mtext">{{ __('Dashboard') }}</span>
                    </a>
                </li>
                @if ($users->type == 'Super Admin')
                <li class="dash-item dash-hasmenu ">
                    <a href="{{ route('Adminprompts') }}" class="dash-link">
                        <span class="dash-micon"><i class="ti ti-line"></i></span>
                        <span class="dash-mtext">{{ __('Manage Prompts') }}</span>
                    </a>
                </li>
                <li class="dash-item dash-hasmenu ">
                    <a href="{{ route('riskKeys') }}" class="dash-link">
                        <span class="dash-micon"><i class="ti ti-key"></i></span>
                        <span class="dash-mtext">{{ __('Api Keys') }}</span>
                    </a>
                </li>
                    <li
                        class="dash-item dash-hasmenu {{ request()->is('users*') || request()->is('roles*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-layout-2"></i></span><span
                                class="dash-mtext">{{ 'User Management' }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-user')
                                <li class="dash-item {{ request()->is('users*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('users.index') }}">{{ __('Admins') }}</a>
                                </li>
                            @endcan
                            @can('manage-role')
                                <li class="dash-item {{ request()->is('roles*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li
                        class="dash-item dash-hasmenu {{ request()->is('request-domain*') || request()->is('change-domain*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-lock"></i></span><span
                                class="dash-mtext">{{ 'Domain Management' }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-domain-request')
                                <li class="dash-item {{ request()->is('request-domain*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('requestdomain.index') }}">{{ __('Domain Requests') }}</a>
                                </li>
                            @endcan
                            @can('manage-domain-request')
                                <li class="dash-item {{ request()->is('change-domain*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('changedomain') }}">{{ __('Change Domain') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li
                        class="dash-item dash-hasmenu {{ request()->is('coupon*') || request()->is('plans*') || request()->is('payment*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-gift"></i></span><span
                                class="dash-mtext">{{ 'Subscription' }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-coupon')
                                <li class="dash-item {{ request()->is('coupon*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('coupon.index') }}">{{ __('Coupons') }}</a>
                                </li>
                            @endcan
                            @can('manage-plan')
                                <li
                                    class="dash-item {{ request()->is('plans*') || request()->is('payment*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('plans.index') }}">{{ __('Plans') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li
                        class="dash-item dash-hasmenu {{ request()->is('Offline*') || request()->is('sales*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-clipboard-check"></i></span><span
                                class="dash-mtext">{{ 'Payment' }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            <li class="dash-item {{ request()->is('Offline*') ? 'active' : '' }}">
                                <a class="dash-link"
                                    href="{{ route('Offline.index') }}">{{ __('Offline Payments') }}</a>
                            </li>
                            <li class="dash-item {{ request()->is('sales*') ? 'active' : '' }}">
                                <a class="dash-link" href="{{ route('sales.index') }}">{{ __('Transactions') }}</a>
                            </li>
                        </ul>
                    </li>

                    <li
                        class="dash-item dash-hasmenu {{ request()->is('manage-support-ticket*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-database"></i></span><span
                                class="dash-mtext">{{ 'Support' }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-support-ticket')
                                <li class="dash-item {{ request()->is('manage-support-ticket*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('support-ticket.index') }}">{{ __('Support Tickets') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li
                        class="dash-item dash-hasmenu {{ request()->is('froentend-setting*') || request()->is('faqs*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-table"></i></span><span
                                class="dash-mtext">{{ 'Frontend Setting' }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-frontend')
                                <li class="dash-item {{ request()->is('froentend-setting*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('froentend.page') }}">{{ __('Frontend Page Landing Page') }}</a>
                                </li>
                            @endcan
                            @can('manage-faqs')
                                <li class="dash-item {{ request()->is('faqs*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('faqs.index') }}">{{ __('Faqs') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>

                    <li
                        class="dash-item dash-hasmenu {{ request()->is('email-template*') || request()->is('create-language*') || request()->is('settings*') ? 'active dash-trigger' : 'collapsed' }}">
                        <a href="#!" class="dash-link"><span class="dash-micon"><i
                                    class="ti ti-apps"></i></span><span
                                class="dash-mtext">{{ 'Account Setting' }}</span><span class="dash-arrow"><i
                                    data-feather="chevron-right"></i></span></a>
                        <ul class="dash-submenu">
                            @can('manage-email-template')
                                <li class="dash-item {{ request()->is('email-template*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('email-template.index') }}">{{ __('Email Templates') }}</a>
                                </li>
                            @endcan
                            @can('manage-langauge')
                                <li class="dash-item {{ request()->is('create-language**') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('manage.language', [$currantLang]) }}">{{ __('Manage Languages') }}</a>
                                </li>
                            @endcan
                            @can('manage-setting')
                                <li class="dash-item {{ request()->is('settings*') ? 'active' : '' }}">
                                    <a class="dash-link" href="{{ route('settings') }}">{{ __('Settings') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endif

                @if ($users->type != 'Super Admin')
                    @canany(['manage-user', 'manage-role'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('users*') || request()->is('roles*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-layout-2"></i></span><span
                                    class="dash-mtext">{{ 'User Management' }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-user')
                                    <li class="dash-item {{ request()->is('users*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('users.index') }}">{{ __('Users') }}</a>
                                    </li>
                                @endcan
                                @can('manage-role')
                                    <li class="dash-item {{ request()->is('roles*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('roles.index') }}">{{ __('Roles') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['manage-blog', 'manage-category'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('blogs*') || request()->is('category*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-forms"></i></span><span
                                    class="dash-mtext">{{ 'Blog' }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-blog')
                                    <li class="dash-item {{ request()->is('blogs*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('blogs.index') }}">{{ __('Blogs') }}</a>
                                    </li>
                                @endcan
                                @can('manage-category')
                                    <li class="dash-item {{ request()->is('category*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('category.index') }}">{{ __('Categories') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['manage-coupon', 'manage-plan'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('coupon*') || request()->is('plans*') || request()->is('myplan*') || request()->is('payment*')  ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-gift"></i></span><span
                                    class="dash-mtext">{{ 'Subscription' }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-coupon')
                                    <li class="dash-item {{ request()->is('coupon*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('coupon.index') }}">{{ __('Coupons') }}</a>
                                    </li>
                                @endcan
                                @can('manage-plan')
                                    <li class="dash-item {{ request()->is('plans*') || request()->is('payment*')  ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('plans.index') }}">{{ __('Plans') }}</a>
                                    </li>
                                @endcan
                                @if ($users->type == 'Admin')
                                    <li class="dash-item {{ request()->is('myplan*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('plans.myplan') }}">{{ __('My Plans') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endcanany

                    @if ($users->type == 'Admin')
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('Offline*') || request()->is('sales*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-clipboard-check"></i></span><span
                                    class="dash-mtext">{{ 'Payment' }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                <li class="dash-item {{ request()->is('Offline*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('Offline.index') }}">{{ __('Offline Payments') }}</a>
                                </li>
                                <li class="dash-item {{ request()->is('sales*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('sales.index') }}">{{ __('Transactions') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @if ($users->type == 'Admin')
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('chat*') || request()->is('support-ticket*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-database"></i></span><span
                                    class="dash-mtext">{{ 'Support' }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @if (Utility::getsettings('pusher_status') == '1')
                                    <li class="dash-item {{ request()->is('chat*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('chat') }}">{{ __('Chats') }}</a>
                                    </li>
                                @endif
                                <li class="dash-item {{ request()->is('support-ticket*') ? 'active' : '' }}">
                                    <a class="dash-link"
                                        href="{{ route('support-ticket.index') }}">{{ __('Support Tickets') }}</a>
                                </li>
                            </ul>
                        </li>
                    @endif

                    @canany(['manage-frontend', 'manage-faqs'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('froentend-setting*') || request()->is('faqs*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-table"></i></span><span
                                    class="dash-mtext">{{ 'Frontend Setting' }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-frontend')
                                    <li class="dash-item {{ request()->is('froentend-setting*') ? 'active' : '' }}">
                                        <a class="dash-link"
                                            href="{{ route('froentend.page') }}">{{ __('Frontend Page Landing Page') }}</a>
                                    </li>
                                @endcan
                                @can('manage-faqs')
                                    <li class="dash-item {{ request()->is('faqs*') ? 'active' : '' }}">
                                        <a class="dash-link" href="{{ route('faqs.index') }}">{{ __('Faqs') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany

                    @canany(['manage-setting'])
                        <li
                            class="dash-item dash-hasmenu {{ request()->is('settings*') ? 'active dash-trigger' : 'collapsed' }}">
                            <a href="#!" class="dash-link"><span class="dash-micon"><i
                                        class="ti ti-apps"></i></span><span
                                    class="dash-mtext">{{ 'Account Setting' }}</span><span class="dash-arrow"><i
                                        data-feather="chevron-right"></i></span></a>
                            <ul class="dash-submenu">
                                @can('manage-setting')
                                    <li class="dash-item">
                                        <a class="dash-link" href="{{ route('settings') }}">{{ __('Settings') }}</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endcanany
                @endif

                {{-- <li class="dash-item dash-hasmenu">
                    <a href="{{ route('landing.page') }}" class="dash-link"><span class="dash-micon"><i
                                class="ti ti-layout-grid text-primary"></i></span><span
                            class="dash-mtext">{{ __('Landing Page') }}</span></a>
                </li> --}}

            </ul>
        </div>
    </div>
</nav>
