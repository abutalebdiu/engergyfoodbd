<header class="top-header">
    <nav class="gap-3 navbar navbar-expand">
        <div class="mobile-toggle-icon fs-3">
            <i class="bi bi-list"></i>
        </div>
        <form class="searchbar">
            <div class="position-absolute top-50 translate-middle-y search-icon ms-3"><i class="bi bi-search"></i></div>
            <input class="form-control navbar-search-field" type="text" placeholder="@lang('Type here to search')">
            <div class="position-absolute top-50 translate-middle-y search-close-icon"><i class="bi bi-x-lg"></i></div>
            <ul class="search-list d-none"></ul>
        </form>
        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item search-toggle-icon">
                    <a class="nav-link" href="#">
                        <div class="">
                            <i class="bi bi-search"></i>
                        </div>
                    </a>
                </li>

                @if (gs('multi_language'))
                    <li class="me-2">
                        @if (session()->get('lang') == 'en')
                            <button class="px-3 btn btn-outline-info btn-sm radius-30 langSel" data-lang="bn">
                                <i class="bi bi-translate"></i> @lang('Bangla')</button>
                        @endif

                        @if (session()->get('lang') == 'bn')
                            <button class="px-3 btn btn-outline-info btn-sm radius-30 langSel" data-lang="en">
                                <i class="bi bi-translate"></i> @lang('English')</button>
                        @endif
                    </li>
                @endif

               
                <li class="me-2">
                    <a href="{{ url('clear') }}" class="px-3 btn btn-outline-dark btn-sm radius-30"><i
                            class="bi bi-arrow-clockwise"></i> @lang('Cache Clear')</a>
                </li>
                <li class="nav-item dropdown dropdown-large">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                        data-bs-toggle="dropdown">
                        <div class="messages">
                            <span class="{{ $adminNotificationCount > 0 ? 'notify-badge' : '' }}"></span>
                            <i class="bi bi-bell"></i>
                        </div>
                    </a>
                    <div class="p-0 dropdown-menu dropdown-menu-end">
                        <div class="p-2 m-2 border-bottom">
                            <h5 class="mb-0 h5">@lang('Notifications')</h5>
                            <small class="mb-0 dropdown-msg-text text-secondary d-flex align-items-center">
                                @if ($adminNotificationCount > 0)
                                    @lang('You have') {{ $adminNotificationCount }} @lang('unread notification')
                                @else
                                    @lang('No unread notification found')
                                @endif
                            </small>
                        </div>
                        <div class="p-2 header-notifications-list">
                            @foreach ($adminNotifications as $notification)
                                <a class="dropdown-item"
                                    href="{{ route('admin.notification.read', $notification->id) }}">
                                    <div class="d-flex align-items-center">
                                        <div class="ms-3 flex-grow-1">
                                            <h6 class="mb-0 dropdown-msg-user"> {{ __($notification->title) }} </h6>
                                            <small
                                                class="mt-1 mb-0 dropdown-msg-text text-secondary d-flex align-items-center">
                                                <i class="bi bi-clock me-1"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach

                        </div>
                        <div class="p-2">
                            <div>
                                <hr class="dropdown-divider">
                            </div>
                            <a class="dropdown-item" href="{{ route('admin.notifications') }}">
                                <div class="text-center">@lang('View all notification')</div>
                            </a>
                        </div>
                    </div>
                </li>

                <li class="nav-item dropdown dropdown-user-setting">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                        data-bs-toggle="dropdown">
                        <div class="user-setting d-flex align-items-center">
                            <img src="{{ getImage(getFilePath('adminProfile') . '/' . auth()->guard('admin')->user()->image, getFileSize('adminProfile')) }}"
                                alt="@lang('Image')" class="user-img">
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="#">
                                <div class="d-flex align-items-center">
                                    <div class="ms-3">
                                        <h6 class="mb-0 dropdown-user-name">{{ auth()->guard('admin')->user()->name }}
                                        </h6>
                                        <small
                                            class="mb-0 dropdown-user-designation text-secondary">{{ auth()->guard('admin')->user()->username }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.profile') }}">
                                <div class="d-flex align-items-center">
                                    <div class=""><i class="bi bi-person"></i></div>
                                    <div class="ms-3"><span>@lang('Profile')</span></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.password') }}">
                                <div class="d-flex align-items-center">
                                    <div class=""><i class="bi bi-key"></i></div>
                                    <div class="ms-3"><span>@lang('Passowrd')</span></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.logout') }}">
                                <div class="d-flex align-items-center">
                                    <div class=""><i class="bi bi-box-arrow-right"></i></div>
                                    <div class="ms-3"><span>@lang('Logout')</span></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>

@push('script')
    <script>
        $(".langSel").on("click", function() {
            var langCode = $(this).data('lang');
            window.location.href = "{{ route('home') }}/change/" + langCode;
        });
    </script>
@endpush
