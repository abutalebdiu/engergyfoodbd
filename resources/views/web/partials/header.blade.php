<header>
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{route('home')}}">
                    <img src="{{ siteLogo() }}" alt="" style="width: 200px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page"
                                href="{{ route('home') }}">@lang('Home')</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('contact') }}">@lang('Contact')</a>
                        </li>
                        @php
                            $pages = App\Models\Page::where('is_default', Status::NO)->get();
                        @endphp
                        @foreach ($pages as $k => $data)
                            <li class="nav-item"><a href="{{ route('pages', [$data->slug]) }}"
                                    class="nav-link">{{ __($data->name) }}</a></li>
                        @endforeach
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.login') }}">@lang('Login')</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.register') }}">@lang('Register')</a>
                            </li>
                        @endguest
                        <li class="nav-item">
                            @if (gs('multi_language'))
                                @php
                                    $language = App\Models\Language::all();
                                @endphp
                                <select class="langSel form-control form-select">
                                    <option value="">@lang('Select One')</option>
                                    @foreach ($language as $item)
                                        <option value="{{ $item->code }}"
                                            @if (session('lang') == $item->code) selected @endif>{{ __($item->name) }}
                                        </option>
                                    @endforeach
                                </select>
                            @endif
                        </li>
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ auth()->user()->username }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.profile.setting') }}">@lang('Profile Settings')</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                    <li><a class="dropdown-item"
                                            href="{{ route('user.logout') }}">@lang('Logout')</a></li>
                                </ul>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>
    </div>

</header>

<style>
    body {
        background: #ebebeb;
    }

    .navbar-collapse {
        flex-grow: inherit !important;
    }

    header {
        background: #ffffff;
    }
</style>