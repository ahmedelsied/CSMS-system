<nav class="navbar navbar-default navbar-inverse text-center" role="navigation">
    <a href="<?=DOMAIN?>/logout" class="pull-right logout">تسجيل الخروج<i class="fa fa-sign-out-alt"></i></a>
    <h2 class="hidden-xs">مرحبا <?=$this->getSession('full_name')?></h2>
    <h5 class="visible-xs">مرحبا <?=$this->getSession('full_name')?></h5>
</nav>