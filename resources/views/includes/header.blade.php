<nav class="main-header navbar navbar-expand navbar-white navbar-light border-bottom-0 text-sm">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">Home</a>
      </li>
      <!--li class="nav-item d-none d-sm-inline-block">
        <a href="#" class="nav-link">Contact</a>
      </li-->
    </ul>

    <ul class="navbar-nav ml-auto user-menu">
      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown user-menu">
        <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
          <i class="fas fa-user-circle" aria-hidden="true" style="font-size:28px;"></i>
        </a>

        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <!-- User image -->
          <li class="user-header">
            <img src="{{ asset('/storage/'.auth()->user()->profile_image)}}" class="img-circle elevation-2" alt="User Image">
            <p>
              {{ auth()->user()->name}}
              <small>[{{ auth()->user()->email}}]</small>
            </p>
          </li>
          <!-- Menu Body -->

          <!-- Menu Footer-->
          <li class="user-footer">
            <a href="{{ route('change.profile') }}" class="btn btn-default btn-flat">Profile</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault();    document.getElementById('logout-form').submit();" class="btn btn-default btn-flat float-right">Sign out</a>
          </li>
        </ul>

      </li>


    </ul>
  </nav>
