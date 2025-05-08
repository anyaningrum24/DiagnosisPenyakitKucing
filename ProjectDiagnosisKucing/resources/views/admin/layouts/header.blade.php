  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        
    </ul>

    @if (auth()->check())

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item">
            <a class="nav-link" href="/logout" role="button">
                Logout
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>

    @else

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="/login" role="button">
                Login
                <i class="fas fa-sign-in-alt"></i>
            </a>
        </li>
    </ul>

    @endif
</nav>
<!-- /.navbar -->