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
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$this->getSession('full_name'); ?><i style="margin-left:10px" class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=DOMAIN?>/logout"><i class="fa fa-fw fa-power-off"></i> تسجيل الخروج</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li <?=(in_array('index',$this->url) || end($this->url) == 'inventory') ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/inventory"><i class="fas fa-clipboard slide-icons"></i>المخزون</a>
                    </li>
                    <li <?=in_array('add_content',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/inventory/add_content"><i class="fas fa-plus slide-icons"></i>إضافة مخزون</a>
                    </li>
                    <li <?=in_array('add_inside_sales',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/inventory/add_inside_sales"><i class="fa fa-shopping-bag slide-icons"></i>البيع الداخلي</a>
                    </li>
                    <li <?=(in_array('back_clothes',$this->url) || in_array('add_back_clothes',$this->url)) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/inventory/back_clothes"><i class="fa fa-undo slide-icons"></i>المرتجعات</a>
                    </li>
                    <li <?=in_array('minus_clothes',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/inventory/minus_clothes"><i class="fa fa-minus slide-icons"></i>بضاعه يجب خصمها</a>
                    </li>
                    <li <?=(in_array('categories',$this->url) || in_array('add_categorey',$this->url)) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/inventory/categories"><i class="fa fa-tshirt slide-icons"></i>الأصناف</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
    </div>