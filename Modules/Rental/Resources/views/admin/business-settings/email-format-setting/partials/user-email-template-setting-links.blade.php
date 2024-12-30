<div class="d-flex flex-wrap justify-content-between align-items-center mb-5 mt-4 __gap-12px">
    <div class="js-nav-scroller hs-nav-scroller-horizontal mt-2">
        <!-- Nav -->
        <ul class="nav nav-tabs border-0 nav--tabs nav--pills">
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/rental-email-setup/user/new-order') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.rental-email-setup', ['user','new-order']) }}">{{translate('Order_Placement')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/rental-email-setup/user/refund-order') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.rental-email-setup', ['user','refund-order']) }}">{{translate('messages.refund_order')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ Request::is('admin/business-settings/rental-email-setup/user/refund-request-deny') ? 'active' : '' }}"
                href="{{ route('admin.business-settings.rental-email-setup', ['user','refund-request-deny']) }}">
                    {{translate('Refund_Request_Rejected')}}
                </a>
            </li>
        </ul>
        <!-- End Nav -->
    </div>
</div>
