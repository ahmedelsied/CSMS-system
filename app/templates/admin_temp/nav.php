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
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$this->getSession('full_name')?><i style="margin-left:10px" class="fa fa-angle-down"></i></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?=DOMAIN?>/logout"><i class="fa fa-fw fa-power-off"></i> تسجيل الخروج</a></li>
                    </ul>
                </li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li <?= (end($this->url) == 'admin' || in_array('index',$this->url)) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/index"><i class="fas fa-chart-bar slide-icons"></i>لوحة البيانات</a>
                    </li>
                    <li <?=(in_array('manage_users',$this->url) || end($this->url) == 'add_user') ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/manage_users"><i class="fas fa-users slide-icons"></i>إدارة المستخدمين</a>
                    </li>
                    <li <?=in_array('orders',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/orders"><i class="fas fa-chart-line slide-icons"></i>آداء الشركه</a>
                    </li>
                    <li <?=in_array('shipping_admin',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/shipping_admin"><i class="fa fa-cogs slide-icons"></i>مسؤولي الشحن</a>
                    </li>
                    <li <?=in_array('sales_representative',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/sales_representative"><i class="fa fa-truck slide-icons"></i>المناديب</a>
                    </li>
                    <li <?=in_array('sales',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/sales"><i class="fa fa-truck slide-icons"></i>مواقع المناديب</a>
                    </li>
                    <li <?=in_array('customers_service',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/customers_service"><i class="fas fa-phone slide-icons"></i>خدمة العملاء</a>
                    </li>
                    <li <?=in_array('inventory_accountant',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/inventory_accountant"><i class="fas fa-user slide-icons"></i>محاسب المخزن</a>
                    </li>
                    <li <?=( end($this->url) == 'inventories' || end($this->url) == 'add_view' || (isset($this->inv_id) && $this->num($this->inv_id)) || ((isset($this->id) && $this->num($this->id)))) ? 'class="active"' : null?>>
                    <?php if(isset($this->inv_id) && in_array(end($this->url),['show_content/'.$this->inv_id,'inventory_process/'.$this->inv_id,'back_clothes/'.$this->inv_id])): ?>
                        <i data-toggle="collapse" data-target="#submenu-3" class="fa fa-fw fa-angle-down pull-left slide-more" aria-expanded="true"></i>
                        <a href="<?=DOMAIN?>/admin/inventories">المخازن</a>
                        <ul id="submenu-3" class="collapse-in" aria-expanded="true">
                            <li <?=end($this->url) == 'show_content/'.$this->inv_id ? 'class="active"' : null ?>><a href="<?=DOMAIN?>/admin/inventories/show_content/<?=$this->inv_id?>" class="btn text-info"><i class="pull-right fas fa-tshirt"></i> القطع بالمخزن</a></li>
                            <li <?=end($this->url) == 'back_clothes/'.$this->inv_id ? 'class="active"' : null ?>><a href="<?=DOMAIN?>/admin/inventories/back_clothes/<?=$this->inv_id?>" class="btn text-danger"><i class="pull-right fas fa-undo"></i>المرتجعات</a></li>
                            <li <?=end($this->url) == 'inventory_process/'.$this->inv_id ? 'class="active"' : null ?>><a href="<?=DOMAIN?>/admin/inventories/inventory_process/<?=$this->inv_id?>" class="btn text-primary"><i class="pull-right fas fa-tasks"></i>الجرد</a></li>
                        </ul>
                    <?php else: ?>
                        <a href="<?=DOMAIN?>/admin/inventories"><i class="fas fa-warehouse slide-icons"></i>المخازن</a>
                    <?php endif; ?>
                    </li>
                    <li <?=in_array('set_targets',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/admin/set_targets"><i class="fas fa-percent"></i>تارجت المناديب</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
    </div>