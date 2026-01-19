<li class="{{ menuActive('admin.general-messaging.sendmessage*') }}">
    <a href="{{ route('admin.general-messaging.sendmessage') }}">
        <i class="bi bi-circle"></i>@lang('General SMS Send')
    </a>
</li>
<li class="{{ menuActive('admin.supplier.messaging.sendmessage*') }}">
    <a href="{{ route('admin.supplier.messaging.sendmessage') }}">
        <i class="bi bi-circle"></i>@lang('Supplier SMS')
    </a>
</li>
<li class="{{ menuActive('admin.sms.history*') }}">
    <a href="{{ route('admin.sms.history') }}">
        <i class="bi bi-circle"></i>@lang('SMS History')
    </a>
</li>
<li class="{{ menuActive('admin.send-mail.index*') }}">
    <a href="{{ route('admin.send-mail.index') }}">
        <i class="bi bi-circle"></i>
        @lang('Send Mail')
    </a>
</li>
<li class="{{ menuActive('admin.send-mail.group*') }}">
    <a href="{{ route('admin.send-mail.group') }}">
        <i class="bi bi-circle"></i>
        @lang('Send Mail User')
    </a>
</li>
<li class="{{ menuActive('admin.send-mail.mail.history*') }}">
    <a href="{{ route('admin.send-mail.mail.history') }}">
        <i class="bi bi-circle"></i>
        @lang('Mail History')
    </a>
</li>
<li class="{{ menuActive('admin.setting.email*') }}">
    <a href="{{ route('admin.setting.email') }}">
        <i class="bi bi-circle"></i>
        @lang('Mail Setting')
    </a>
</li>
