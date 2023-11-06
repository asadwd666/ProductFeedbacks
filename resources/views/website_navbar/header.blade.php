<style>
    .main-nav{
        background-color:#222;
  border:1px solid grey;
  z-index: 1;
 /* position:absolute; */
  box-shadow: 1px 1px 5px rgba(255, 255, 255, 0.5);
    }
    .navbar-nav > li:hover{
  color:#121212;
  background-color:#fff;
}

</style>
    <nav class="navbar navbar-expand-lg navbar-light bg-light main-nav">
        <a class="navbar-brand" href="/">Product Feedbacks</a>
    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                @guest
                    <li class="nav-item ml-2">
                        <a class="nav-link btn" href="{{ url('login') }}">Login</a>
                    </li>
                    <li class="nav-item ml-2">
                        <a class="nav-link btn" href="{{ url('register') }}">Signup</a>
                    </li>
                @else
                    <li class="nav-item ml-2">
                        <a class="nav-link btn" href="{{ url('/') }}">Dashboard</a>
                    </li>
                    <li class="nav-item ml-2">
                        <form action="{{ url('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-link nav-link btn">Logout</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </nav>



