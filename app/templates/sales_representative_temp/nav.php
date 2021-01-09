<div class="wrapper" id="wrapper">
        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <ul class="nav navbar-right top-nav">
                <li><a href="#" data-placement="bottom" data-toggle="tooltip" href="#" data-original-title="Stats"><i class="fa fa-bar-chart-o"></i>
                        </a>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$this->getSession('full_name')?><i style="margin-left:10px" class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=DOMAIN?>/logout"><i class="fa fa-fw fa-power-off"></i> تسجيل الخروج</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>