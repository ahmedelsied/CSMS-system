<div class="wrapper" id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Top Menu Items -->
        <ul class="nav navbar-right top-nav">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$this->getSession('full_name')?><i style="margin-left:10px" class="fa fa-angle-down"></i></a>
                <ul class="dropdown-menu">
                    <li><a href="<?=DOMAIN?>/logout"><i class="fa fa-fw fa-power-off"></i> تسجيل الخروج</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</div>