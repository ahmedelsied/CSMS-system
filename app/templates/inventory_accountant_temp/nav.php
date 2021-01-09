<div class="wrapper" id="wrapper">
    <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header pull-right">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <!-- Top Menu Items -->
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
        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav side-nav">
                <li <?=(in_array('index',$this->url) || end($this->url) == 'inventory_accountant') ? 'class="active"' : null?>>
                    <a href="<?=DOMAIN?>/inventory_accountant"><i class="fas fa-clipboard slide-icons"></i>الحسابات</a>
                </li>
                <li <?=in_array('add_acc',$this->url) ? 'class="active"' : null?>>
                    <a href="<?=DOMAIN?>/inventory_accountant/add_acc"><i class="fas fa-plus slide-icons"></i>إضافة عمليه</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </nav>
</div>