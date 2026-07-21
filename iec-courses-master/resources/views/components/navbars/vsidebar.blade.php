@props(['activePage'])

<style>
    .custom-active {
        color: #fff;
        background-color: #3490dc;
    }
</style>

<aside
    class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-3 bg-gradient-dark"
    id="sidenav-main">
    <div class="sidenav-header">
        <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true" id="iconSidenav"></i>
        <a class="navbar-brand m-0 d-flex text-wrap align-items-center" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets') }}/img/logo-ct.png" class="navbar-brand-img h-100" alt="main_logo">
            <span class="ms-3 font-weight-bold text-white sidebar-brand-text-compact">
                @php
                $userRoles = Auth::user()->roles->pluck('name')->toArray();
                $isShobaViral = in_array('Shoba_Viral', $userRoles);
                $isShobaViralIec = in_array('Shoba_Viral_Iec', $userRoles);
            @endphp
            @if(request()->is('shoba-viral*'))
                Shoba Viral Fatawa Page
                @else
                Nazim Viral Fatawa Page
                @endif
            </span>
        </a>
    </div>

    <hr class="horizontal light mt-0 mb-2">

    <div class="w-auto max-height-vh-100" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            @php
                $userRoles = Auth::user()->roles->pluck('name')->toArray();
                $isShobaViral = in_array('Shoba_Viral', $userRoles);
                $isShobaViralIec = in_array('Shoba_Viral_Iec', $userRoles);
            @endphp

            @if(request()->is('shoba-viral*'))
                @if($isShobaViral || $isShobaViralIec)
                    <!-- Shoba Viral or Shoba Viral IEC -->
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('shoba-viral') ? 'custom-active' : '' }}"
                            href="{{ route('shoba-viral') }}">
                            <span class="nav-link-text ms-1">Dashboard</span>
                        </a>
                    </li>
                @else
                    <!-- Other roles -->
                    <li class="nav-item">
                        <a class="nav-link text-white {{ request()->routeIs('viral-fatawa') ? 'custom-active' : '' }}"
                            href="{{ route('viral-fatawa') }}">
                            <span class="nav-link-text ms-1">Viral Fatawa</span>
                        </a>
                    </li>
                    <ul>
                        @foreach(['Noorulirfan', 'Faizan-e-Ajmair', 'Gulzar-e-Taiba', 'Markaz-ul-Iqtisaad'] as $darulifta)
                            <li class="nav-item">
                                <a class="nav-link text-white {{ $activePage == $darulifta ? 'active bg-gradient-primary' : '' }}"
                                    href="{{ route('viral-fatawa', $darulifta) }}">
                                    <span class="nav-link-text ms-1">{{ $darulifta }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif
        </ul>
    </div>

    <div class="sidenav-footer position-absolute w-0 bottom-0">
        <!-- Footer content here if needed -->
    </div>
</aside>
