<nav class="navbar navbar-expand-lg custom-menu custom-menu__light">
    <div class="container">
        <a class="navbar-brand" href="/">
        <img src="images/logo.jpg" alt="Finderiko - Best Reviews" class="logo-md" width="180">
        </a>
        {{-- <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent"
        aria-expanded="false" aria-label="Toggle navigation">
        <span class="menu-icon__circle">
        </span>
        <span class="menu-icon">
        <span class="menu-icon__bar"></span>
        <span class="menu-icon__bar"></span>
        <span class="menu-icon__bar"></span>
        </span>
    </button> --}}
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ml-lg-auto float-left">
        <li class="nav-item active">
            <a class="nav-link" href="/#departments">All Categories
            </a>
        </li>
        </ul>
        <form class="form-inline mt-2 mt-md-0" action="{{ route('search') }}">
            <input class="form-control mr-sm-2" type="text" name="query" value="{{ request('query') }}" placeholder="Search" aria-label="Search">
            <button class="btn btn-primary btn-pills" type="submit">Search</button>
        </form>
    </div>
    </div>
</nav>