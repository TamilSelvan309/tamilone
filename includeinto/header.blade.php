<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <!-- @auth {{auth()->employee()->name}}  @endauth -->
    </a>
    </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        @auth
        </li><li class="nav-item">
          <a class="nav-link" href="#">Logout</a>
        </li>
        @else
        <li class="nav-item">
          <a class="nav-link" href="{{route('employee.login')}}">Login</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('employee.create')}}">Registration</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="{{route('employee.upload')}}">Upload csv</a>
        </li>
        <!-- <li class="nav-item">
          <a class="nav-link" href="{{route('employee.upload')}}">Uploadexcel</a>
        </li> -->
       @endauth
      </ul>
      <span class="navbar-text"> Company Details
        <!-- {{config('app.name')}} -->
      </span>
    </div>
  </div>
</nav>