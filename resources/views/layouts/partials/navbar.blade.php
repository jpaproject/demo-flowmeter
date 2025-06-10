<div class="container-xxl">
    <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">


        <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
            <li>
                <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                    <i class="iconoir-menu-scale"></i>
                </button>
            </li>
        </ul>
        <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">

            <li class="topbar-item">
                <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                    <i class="icofont-moon dark-mode"></i>
                    <i class="icofont-sun light-mode"></i>
                </a>
            </li>


            <li class="dropdown topbar-item">   
                <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&size=128" alt="" class="thumb-lg rounded-circle">
                </a>
                <div class="dropdown-menu dropdown-menu-end py-0">
                    <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                        <div class="flex-shrink-0">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=random&size=128" alt="" class="thumb-md rounded-circle">
                        </div>
                        <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                            <h6 class="my-0 fw-medium text-dark fs-13">{{ Auth::user()->name ?? ''}}</h6>
                            <small class="text-muted mb-0">Front End Developer</small>
                        </div>
                        <!--end media-body-->
                    </div>
                    <div class="dropdown-divider mt-0"></div>
                    <small class="text-muted px-2 pb-1 d-block">Account</small>
                    <a class="dropdown-item" href="{{ route('users.show', Auth::user()->id) }}"><i
                            class="las la-user fs-18 me-1 align-text-bottom"></i> Profile</a>
                    <small class="text-muted px-2 py-1 d-block">Settings</small>
                    <a class="dropdown-item" href="pages-profile.html"><i
                            class="las la-cog fs-18 me-1 align-text-bottom"></i>Account Settings</a>
                    <a class="dropdown-item" href="pages-faq.html"><i
                            class="las la-question-circle fs-18 me-1 align-text-bottom"></i> Help Center</a>
                    <div class="dropdown-divider mb-0"></div>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                    
                    <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="las la-power-off fs-18 me-1 align-text-bottom"></i> Logout
                    </a>
                </div>
            </li>
        </ul>
        <!--end topbar-nav-->
    </nav>
    <!-- end navbar-->
</div>