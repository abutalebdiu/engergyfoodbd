<div>
    <div class="profile d-flex align-items-center gap-4">
        <img src="{{ getImage(getFilepath('userProfile') . '/' . auth()->user()->image) }}">
        <div>
            <p>@lang('Welcome To')</p>
            <h4>{{ auth()->user()->name }}</h4>
        </div>
    </div>
</div>
<div class="d-flex gap-3 justify-content-between align-items-end">
    <button class="d-mobile-btn d-mobile-toggle"><i class="bi bi-list"></i></button>
    @stack('title')
</div>
