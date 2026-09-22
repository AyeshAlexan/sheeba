<div class="app-topbar">
    <div class="header header-one">
        <div class="header-left header-left-one">
            <a href="{{ route('home') }}" class="logo">
                <span class="logo-mark" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <polyline points="3.29 7 12 12 20.71 7"/>
                        <line x1="12" y1="22" x2="12" y2="12"/>
                    </svg>
                </span>
                <span class="logo-text">Sheeba</span>
            </a>
            <a href="javascript:void(0);" id="toggle_btn">
               <i class="fas fa-bars"></i>
            </a>
         </div>
        <ul class="nav nav-tabs user-menu">
            <li>
               <a href="" class="branch-pill">
                   <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                       <line x1="6" y1="3" x2="6" y2="15"/>
                       <circle cx="18" cy="6" r="3"/>
                       <circle cx="6" cy="18" r="3"/>
                       <path d="M18 9a9 9 0 0 1-9 9"/>
                   </svg>
                   Branch : <span>{{ optional(Auth::user())->Branch ?? optional(Auth::user())->BC ?? '-' }}</span></a>
            </li>
            <li class="nav-item dropdown has-arrow main-drop">
                <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                <span>{{ Auth::user()->username}}</span>
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href=""><i data-feather="user" class="me-1"></i>
                        Profile</a>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                      document.getElementById('logout-form').submit();">
                         {{ __('Logout') }}
                     </a>

                     <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                         @csrf
                     </form>
                </div>

            </li>

        </ul>

    </div>
    </div>



