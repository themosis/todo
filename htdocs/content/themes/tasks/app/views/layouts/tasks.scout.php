@include('header')

@yield('pre-document')

<!-- Toolbar -->
<div class="toolbar">
    <div class="container clearfix">
        <div class="toolbar__actions left">
            @yield('toolbar')
        </div>
        <div class="toolbar__title left">
            @section('toolbar_title')
                <h3>My Tasks</h3>
            @show
        </div>
        <div class="toolbar__auth left">
            <a href="{{ wp_logout_url(home_url()) }}" title="Logout">Logout</a>
        </div>
    </div>
</div>
<!-- End toolbar -->

<!-- Main -->
<div class="main">
    <div class="container">
        <div class="messages-wrapper">
            <div class="messages">
                @yield('messages')
            </div>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </div>
</div>
<!-- End main -->

@yield('post-document')

@include('footer')