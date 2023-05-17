@php
    $users = \Auth::user();
    $currantLang = $users->currentLanguage();
    $languages = Utility::languages();
@endphp

<header class="dash-header dash-header-custom transprent-bg">
    <div class="header-wrapper">
        <div class="me-auto dash-mob-drp">
            <li class="dash-h-item mob-hamburger">
                <a href="#!" class="dash-head-link" id="mobile-collapse">
                  <div class="hamburger hamburger--arrowturn">
                    <div class="hamburger-box">
                      <div class="hamburger-inner"></div>
                    </div>
                  </div>
                </a>
              </li>
        </div>
        <div class="ms-auto ml-0">
            <ul class="list-unstyled">
                @impersonating($guard = null)
                    <li class="dropdown dash-h-item drp-company">
                        <a class="btn btn-primary btn-active-color-primary btn-outline-secondary me-3"
                            href="{{ route('impersonate.leave') }}"><i class="ti ti-ban"></i>
                            {{ __('Exit Impersonation') }}
                        </a>
                    </li>
                @endImpersonating
                <li class="dropdown dash-h-item drp-language">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <i class="ti ti-world nocolor"></i>
                        <span class="drp-text hide-mob">{{ Str::upper($currantLang) }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">

                        @foreach ($languages as $language)
                            <a class="dropdown-item @if ($language == $currantLang) text-danger @endif"
                                href="{{ route('change.language', $language) }}">{{ Str::upper($language) }}</a>
                        @endforeach

                        {{-- <a class="btn btn-color-danger  Impersonation-link btn-active-color-primary btn-outline-secondary me-3" href="{{ route('impersonate.leave') }}">
      {{'Exit Impersonation'}}
  </a> --}}
                    </div>
                </li>
                <li class="dropdown dash-h-item drp-company">
                    <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        <span>
                            <img alt="image"
                                src="{{ Auth::user()->avatar_image }}"
                                class="rounded-circle mr-1">
                        </span>
                        <span class="hide-mob ms-2">{{ __('Hi,') }} {{ Auth::user()->name }}</span>
                        <i class="ti ti-chevron-down drp-arrow nocolor hide-mob"></i>
                    </a>
                    <div class="dropdown-menu dash-h-dropdown">

                        <a href="{{ route('profile.index') }}" class="dropdown-item">
                            <i class="ti ti-user"></i>
                            <span>{{ __('Profile') }}</span>
                        </a>
                        {{-- <a href="{{ route('users.leave', Auth::user()->id) }}" class="dropdown-item">
            <i class="ti ti-user"></i>
            <span>{{ __('Impersonate') }}</span>
          </a> --}}
                        <a href="javascript:void(0)" class="dropdown-item"
                            onclick="document.getElementById('logout-form').submit()">
                            <i class="ti ti-power"></i>
                            <span>{{ __('Logout') }}</span>
                            <form action="{{ route('logout') }}" method="POST" id="logout-form"> @csrf </form>
                        </a>
                    </div>
                </li>

            </ul>
        </div>
    </div>
</header>
