<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <div class="container mt-3">
            <div class="row">
              <div class="col-12 ps-4 m-0 text-center">
                <p class="mb-0" id="logo"> Notes Project</p>
              </div>
            </div>
        </div>
        
        <ul class="sidebar-nav mt-4">

          @can('viewany-role')
          <li class="sidebar-item accordion">
            <a class="sidebar-link"  href="#" data-bs-toggle="collapse" data-bs-target="#configuration-collapse" aria-expanded="true" >
                <i class="fa-solid fa-sliders"></i> 
                <span class="align-middle">Configuration</span>
                <span style="float: right" class="mt-1 pr-3">
                  <i class="fa-solid fa-caret-down"></i>
                </span>
            </a>
            <div id="configuration-collapse">
              <ul class="sidebar-nav ps-3">

                <li class="sidebar-item {{ request()->routeIs('role.index')? 'active':'' }}">
                  <a class="sidebar-link" href="{{ route('role.index') }}">
                    <i class="fa-sharp fa-solid fa-code-branch"></i>
                    <span class="align-middle">Roles</span>
                  </a>
                </li>

              </ul>
            </div>
          </li>
          @endcan

          @can('viewany-user')
          <li class="sidebar-item {{ request()->routeIs('user.index')? 'active':'' }}">
            <a class="sidebar-link" href="{{ route('user.index') }}">
              <i class="fa-solid fa-user"></i>
              <span class="align-middle">Users</span>
            </a>
          </li>
          @endcan

          @can('viewany-note')
          <li class="sidebar-item {{ request()->routeIs('notes.index')? 'active':'' }}">
            <a class="sidebar-link" href="{{ route('notes.index') }}">
              <i class="fa-solid fa-note-sticky"></i>
              <span class="align-middle">Notes</span>
            </a>
          </li>
          @endcan

      </ul>
  </div>
</nav>