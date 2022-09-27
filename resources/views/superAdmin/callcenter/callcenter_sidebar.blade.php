<div class="profile-sidebar">
    <div class="widget-profile pro-widget-content">
        <div class="profile-info-widget">
            <a href="javascript:void(0)" class="booking-doc-img">
                <img src="{{ $callcenter->fullImage }}" alt="User Image">
            </a>
            <div class="profile-det-info">
                <h3>{{ $callcenter->name }}</h3>
            </div>
        </div>
    </div>

    <div class="dashboard-widget">
        <nav class="dashboard-menu">
            <ul>
                <li class="{{ $activeBar == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ url('callcenter/'.$callcenter->id.'/'.Str::slug($callcenter->name).'/dashboard') }}">
                        <i class="fas fa-columns"></i>
                        <span>{{__('Dashboard')}}</span>
                    </a>
                </li>
                <li class="{{ $activeBar == 'patients' ? 'active' : '' }}">
                    <a href="{{ url('callcenter/'.$callcenter->id.'/'.Str::slug($callcenter->name).'/patients') }}">
                        <i class="fas fa-user-injured"></i>
                        <span>{{__('Patients')}}</span>
                    </a>
                </li>
                <li class="{{ $activeBar == 'schedule' ? 'active' : '' }}">
                    <a href="{{ url('callcenter/'.$callcenter->id.'/'.Str::slug($callcenter->name).'/schedule') }}">
                        <i class="fas fa-hourglass-start"></i>
                        <span>{{__('Schedule Timings')}}</span>
                    </a>
                </li>
                <li class="{{ $activeBar == 'finance' ? 'active' : '' }}">
                    <a href="{{ url('callcenter/'.$callcenter->id.'/'.Str::slug($callcenter->name).'/finance') }}">
                        <i class="fas fa-file-invoice"></i>
                        <span>{{__('finance details')}}</span>
                    </a>
                </li>
                <li class="{{ $activeBar == 'password' ? 'active' : '' }}">
                    <a href="{{ url('callcenter/'.$callcenter->id.'/'.Str::slug($callcenter->name).'/change_password') }}">
                        <i class="fas fa-lock"></i>
                        <span>{{__('Change password')}}</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>
