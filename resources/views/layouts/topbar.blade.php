<div class="app-header header sticky">
  <div class="container-fluid main-container">
    <div class="d-flex">
      <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar"
         href="javascript:void(0)"></a>
      <!-- sidebar-toggle-->
      <a class="logo-horizontal " href="{{ Auth::user()->roles->dashboard_url }}">
        <img src="{{ asset('assets/img/brand/logo-small.png') }}" class="header-brand-img desktop-logo" alt="logo">
        <img src="{{ asset('assets/img/brand/logo-small.png') }}" style="width: 50px; height: 50px" class="header-brand-img light-logo1" alt="logo">
      </a>
      <!-- LOGO -->
      <div class="d-flex order-lg-2 ms-auto header-right-icons">
        <div class="dropdown d-none">
          <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
            <i class="fe fe-search"></i>
          </a>
          <div class="dropdown-menu header-search dropdown-menu-start">
            <div class="input-group w-100 p-2">
              <input type="text" class="form-control" placeholder="Search....">
              <div class="input-group-text btn btn-primary">
                <i class="fe fe-search" aria-hidden="true"></i>
              </div>
            </div>
          </div>
        </div>
        <!-- SEARCH -->
        <button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon fe fe-more-vertical"></span>
        </button>
        <div class="navbar navbar-collapse responsive-navbar p-0">
          <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
            <div class="d-flex order-lg-2">
              <div class="dropdown d-lg-none d-flex">
                <a href="javascript:void(0)" class="nav-link icon" data-bs-toggle="dropdown">
                  <i class="fe fe-search"></i>
                </a>
                <div class="dropdown-menu header-search dropdown-menu-start">
                  <div class="input-group w-100 p-2">
                    <input type="text" class="form-control" placeholder="Search....">
                    <div class="input-group-text btn btn-primary">
                      <i class="fa fa-search" aria-hidden="true"></i>
                    </div>
                  </div>
                </div>
              </div>
              <!-- COUNTRY -->
            <!-- SEARCH -->
              <!-- Theme-Layout -->
              <div class="dropdown d-flex">
                <a class="nav-link icon full-screen-link nav-link-bg">
                  <i class="fe fe-minimize fullscreen-button"></i>
                </a>
              </div>
              <!-- FULL-SCREEN -->
            <!-- NOTIFICATIONS -->
            <!-- MESSAGE-BOX -->
              <div class="dropdown d-flex header-settings">
                <a href="{{ route('logout') }}" class="nav-link icon">
                  <i class="fa-light fa-right-from-bracket"></i>
                </a>
              </div>
            <!-- SIDE-MENU -->
              <div class="dropdown d-flex profile-1">
                <a href="javascript:void(0)" data-bs-toggle="dropdown"
                   class="nav-link leading-none d-flex">
                  <img
                    src="{{ auth()->user()->image != NULL ? asset("/storage/images/original/".auth()->user()->image) : asset('assets/img/svgs/no-content.svg') }}"
                    alt="profile-user" class="avatar  profile-user bg-round cover-image">
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <div class="drop-heading">
                    <div class="text-center">
                      <h5 class="text-dark mb-0 fs-14 fw-semibold">{{ auth()->user()->name }}</h5>
                      <small class="text-muted">{{ auth()->user()->roles->name }}</small>
                    </div>
                  </div>
                  <div class="dropdown-divider m-0"></div>
                  <a class="dropdown-item" href="{{ route('logout') }}">
                    <i class="dropdown-icon fe fe-alert-circle"></i> Sign out
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
