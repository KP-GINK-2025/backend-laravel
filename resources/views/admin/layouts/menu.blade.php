<div class="side-menu">
    <div class="login-info">
        <img src="{{ !empty(auth()->user()->image) ? 'avatar/' . auth()->user()->image : 'images/man.png' }}" alt="">
        <h1>{{ auth()->user()->name }}</h1>
        <p class="mb-2">{{ auth()->user()->email }}</p>
        <a href="admin/profile" class="btn btn-outline-primary" style="padding: 3px 12px; font-size: 10px;">Edit Profile</a>
    </div>
    <ul>
        <li class="list-title">Configuration</li>
        <li class="{{ request()->segment(2) == 'dashboard' || request()->segment(2) == null ? 'active' : null }}"><a href="admin/dashboard"><i class="fa fa-home"></i> Dashboard</a></li>
        @if (auth()->user()->hasPermissionTo('settings') || auth()->user()->hasRole('Super Admin'))
            <li class="{{ request()->segment(2) == 'settings' ? 'active' : null }}"><a href="admin/settings"><i class="fa-solid fa-wrench"></i> Settings</a></li>
        @endif
    </ul>
</div>
