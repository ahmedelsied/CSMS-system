<audio class="hidden" id="notification_sound" controls>
  <source src="<?=SUPERVISOR_SOUNDS?>notification_sound.mp3" type="audio/mp3">
Your browser does not support the audio element.
</audio>
<div class="alert alert-success result text-center text-success"></div>
    <div class="overlay"></div>
    <?php if(in_array('index',$this->url) || end($this->url) == 'supervisor'): ?>
    <div class="edit-order text-center">
        <form class="form-group form-add-order" id="edit-order">
            <h3>تفاصيل الأوردر</h3>
            <br>
            <input type="hidden" name="id"/>
            <div class="input-parent">
                <input class="form-control" type="text" name="address" placeholder="العنوان" required="required"/>
                <i class="fas fa-address-book inside-inpt"></i>
            </div>
            <div style="display: flex;">
                <select class="input-parent form-control half-right" name="gove" required="required">
                    <option value="" selected="true" disabled="disabled">المحافظه</option>
                    <?php foreach($this->governments as $gove): ?>
                        <option value="<?=$gove['id']?>"><?=$gove['government']?></option>
                    <?php endforeach;?>
                </select>
                <div class="input-parent half-left">
                    <input class="form-control" type="text" name="phone" placeholder="رقم الهاتف" required="required">
                    <i class="fas fa-phone inside-inpt"></i>
                </div>
            </div>
            <div class="input-parent">
                <input class="form-control" type="text" name="user" placeholder="الاسم بالكامل" required="required"/>
                <i class="fa fa-user inside-inpt"></i>
            </div>
            <div class="input-parent">
                <input class="form-control" type="text" name="order-details" placeholder="تفاصيل الأوردر" required="required"/>
                <i class="fas fa-info-circle inside-inpt"></i>
            </div>
            <div class="input-parent">
                <input class="form-control" type="text" name="notes" placeholder="ملاحظات" required="required"/>
                <i class="fas fa-pen inside-inpt"></i>
            </div>
            <button class="btn btn-danger w-btn save-edit" data-eq>حفظ
                <i class="fa fa-save"></i>
            </button>
        </form>
    </div>
    <?php endif; ?>
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
            <ul class="nav navbar-left top-nav pull-left">
                <?php if($this->notification->count_notif != 0): ?>
                <span id="count_of_notif"><?=$this->notification->count_notif?></span>
                <?php endif; ?>
                <li class="dropdown">
                    <a  class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fas fa-bell fa-2x pull-left bell-notification"></i>
                    </a>
                    <ul class="dropdown-menu notif-box">
                        <?php if($this->notification->count_notif != 0): ?>
                            <?php foreach($this->notification->notif_data as $notif_data): ?>
                                <?php if($notif_data['record_delete'] == 1): ?>
                            <li>يريد <strong><?=$notif_data['inv_user_full_name']?></strong> من مخزن <strong><?=$notif_data['inv_name']?></strong> مسح <?=(isset($notif_data['notes']) ? 'محتوى المخزن' : 'المرتجعات')?> من صنف <strong><?=$notif_data['catg_name']?></strong> وحجم <strong><?=$notif_data['size']?></strong>
                                <?php else: ?>
                            <li>يريد <strong><?=$notif_data['inv_user_full_name']?></strong>  من مخزن  <strong><?=$notif_data['inv_name']?></strong> تعديل عدد القطع من صنف <strong><?=$notif_data['catg_name']?></strong> وحجم <strong><?=$notif_data['size']?></strong> من <strong><?=$notif_data['count_of_pieces']?></strong> إلى <strong><?=$notif_data['notifi_count_of_pieces']?></strong>
                                <?php endif; ?>
                                <br>
                                <a href="<?=DOMAIN?>/supervisor/notification_action/<?=isset($notif_data['notes']) ? 'inv_content' : 'back_clothes'?>/accept/<?=$notif_data['record_delete'] == 1 ? 'delete' : 'update' ?>/<?=$notif_data['id']?>" class="btn btn-success accept-edit edit">قبول</a>
                                <a href="<?=DOMAIN?>/supervisor/notification_action/<?=isset($notif_data['notes']) ? 'inv_content' : 'back_clothes'?>/refuse/delete/<?=$notif_data['id']?>" class="btn btn-danger refuse-edit edit">رفض</a>
                                <span class='date'><?=$notif_data['date_of_last_edit']?></span>
                                <span class='type'><?=(isset($notif_data['notes']) ? 'محتوى المخزن' : 'المرتجعات')?></span>
                            </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
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
                    <li <?=(in_array('index',$this->url) || end($this->url) == 'supervisor') ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/supervisor/index"><i class="fas fa-clipboard slide-icons"></i>طلبات بانتظار الإرسال</a>
                    </li>
                    <li <?=in_array('uncompleted_orders',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/supervisor/uncompleted_orders"><i class="fas fa-clock slide-icons"></i>الطلبات غير المكتمله</a>
                    </li>
                    <li <?=in_array('order_under_shipping',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/supervisor/order_under_shipping"><i class="fas fa-dolly-flatbed"></i>طلبات قيد الشحن</a>
                    </li>
                    <li <?=in_array('order_under_dilevery',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/supervisor/order_under_dilevery"><i class="fas fa-shipping-fast"></i>طلبات قيد التوصيل</a>
                    </li>
                    <li <?=in_array('archive',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/supervisor/archive"><i class="fas fa-archive slide-icons"></i>الأرشيف</a>
                    </li>
                    <li <?=in_array('representative_and_drivers',$this->url) ? 'class="active"' : null?>>
                        <a href="<?=DOMAIN?>/supervisor/representative_and_drivers"><i class="fas fa-truck slide-icons"></i>عمليات محاسب المخزن</a>
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>
    </div>