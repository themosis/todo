@include('header')
<div class="login">
    <h1>Tasks Manager</h1>
    <div class="login__form">
        <?php
            wp_login_form(array(
                'redirect'      => home_url('tasks')
            ));
        ?>
    </div>
</div>
@include('footer')