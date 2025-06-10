 <!-- Navigation -->
 <ul class="navbar-nav mb-auto w-100">
     <li class="menu-label pt-0 mt-0">
         <!-- <small class="label-border">
                <div class="border_left hidden-xs"></div>
                <div class="border_right"></div>
            </small> -->
         <span>Main Menu</span>
     </li>
     {{-- <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
     <i class="fas fa-home menu-icon"></i>
     <span>Dashboards</span>
     </a>
     <!--end startbarDashboards-->
     </li> --}}
     <li class="nav-item">
         <a class="nav-link" href="#sidebarApplications" data-bs-toggle="collapse" role="button" aria-expanded="false"
             aria-controls="sidebarApplications">
            <i class="fas fa-location-dot menu-icon"></i>
             <span>Area</span>
         </a>
         <div class="collapse " id="sidebarApplications">
             <ul class="nav flex-column">
                @foreach ($areas as $area)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('areas.show', $area->id) }}">{{ $area->name }}</a>
                </li>
                @endforeach
                 <!--end nav-item-->
             </ul>
             <!--end nav-->
         </div>
         <!--end startbarApplications-->
     </li>
     <!--end nav-item-->
     <li class="nav-item">
         <a class="nav-link" href="{{ route('areas.index') }}">
            <i class="fas fa-industry menu-icon"></i>
             <span>Manage Areas</span>
         </a>
         <!--end startbarDashboards-->
     </li>
     <li class="nav-item">
         <a class="nav-link" href="{{ route('devices.index') }}">
            <i class="fas fa-project-diagram menu-icon"></i>
             <span>Manage Devices</span>
         </a>
         <!--end startbarDashboards-->
     </li>
     <li class="nav-item">
         <a class="nav-link" href="{{ route('users.index') }}">
             <i class="fas fa-user-cog menu-icon"></i>
             <span>Manage Users</span>
         </a>
         <!--end startbarDashboards-->
     </li>
     <li class="nav-item">
         <a class="nav-link" href="{{ route('admin.roles-permissions.index') }}">
             <i class="fas fa-user-group menu-icon"></i>
             <span>Manage Roles & Permissions</span>
         </a>
         <!--end startbarDashboards-->
     </li>
