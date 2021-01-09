<?php if($this->req($this->getSession('formError'))): ?>
<ul class="alert alert-danger list-unstyled backend-result">
<?php foreach($this->getSession('formError') as $err): ?>
<li><?=$err?></li>
<?php endforeach; ?>
</ul>
<?php $this->unSetSession('formError'); ?>
<?php endif; ?>

<?php if($this->req($this->getSession('success'))): ?>
<div class="alert alert-success backend-result"><?=$this->getSession('success')?></div>
<?php $this->unSetSession('success'); ?>
<?php endif; ?>

<div class="alert alert-success result text-center text-success"></div>
<div class="overlay"></div>
<div class="edit-order text-center">
    <form class="form-group form-add-order" id="edit-order" method="POST" action="<?=DOMAIN?>/inventory_accountant/index/edit">
        <input type="hidden" class="order_id_input" name="order_id"/>
        <h3>تعديل الكميه</h3>
        <h6><strong>(ملحوظه:لا تضغط على زر الحفظ إلا بعد التأكد من البيانات)</strong></h6>
        <br>
        <div class="order-data">
            <div class="input-parent order-details">
                <label class="text-right input-label"><strong>البنزين في بداية اليوم</strong></label>
                <input type="number" class="form-control petrol-start-input" name="start-day" placeholder="البنزين في بداية اليوم" required="required"/>
                <label class="text-right input-label"><strong>البنزين في نهاية اليوم</strong></label>
                <input type="number" class="form-control petrol-end-input" name="end-day" placeholder="البنزين في نهاية اليوم" required="required"/>
                <label class="text-right input-label"><strong>تحميل</strong></label>
                <input type="number" class="form-control load-input" name="load" placeholder="تحميل" required="required"/>
                <label class="text-right input-label"><strong>تفريغ</strong></label>
                <input type="number" class="form-control sell-input" name="unloading" placeholder="تفريغ" required="required"/>
                <label class="text-right input-label"><strong>اسم السواق</strong></label>
                <input type="text" class="form-control driver-name-input" name="driver" placeholder="السواق" required="required"/>
                <label class="text-right input-label"><strong>المبلغ الذي تم تحقيقه</strong></label>
                <input type="number" class="form-control money-input" name="money" placeholder="الملغ الذي تم تحقيقه" required="required"/>
                <label class="text-right input-label"><strong>حساب المندوب</strong></label>
                <input type="number" class="form-control money-sales-input" name="sales-money" placeholder="حساب المندوب" required="required"/>
                <label class="text-right input-label"><strong>حساب السواق</strong></label>
                <input type="number" class="form-control money-driver-input" name="driver-money" placeholder="حساب السواق" required="required"/>
                <label class="text-right input-label"><strong>حساب العربيه</strong></label>
                <input type="number" class="form-control money-truck-input" name="car-money" placeholder="حساب العربيه" required="required"/>
                <label class="text-right input-label"><strong>حساب البنزين</strong></label>
                <input type="number" class="form-control money-petrol-input" name="petrol-money" placeholder="حساب البنزين" required="required"/>
            </div>
        </div>
        <br>
        <br>
        <br>
        <button class="btn btn-danger w-btn save-edit" data-eq>حفظ
            <i class="fa fa-save"></i>
        </button>
    </form>
</div>
<?php if(!empty($this->all_targets)): ?>
<div class="all_targets_parent">
    <i class="fa fa-info"></i>
    <h5 class="text-right"><strong>معلومات</strong></h5>
    <ul class="info_targets text-right">
        <li>
            أقل عدد اوردرات لحساب اليوم هو <?=$this->all_targets[0]['target']?> اوردر
        </li>
        <li>
            الراتب اليومي : <strong><?=$this->all_targets[0]['order_price']?></strong> جنيه
        </li>
        <li>
            تارجت المناديب :
            <ul class="all_targets" style="list-style:inside decimal">
                <?php unset($this->all_targets[0]); ?>
                <?php foreach($this->all_targets as $target):?>
                <li>عدد الاوردرات = <?=$target['target']?>
                | سعر الأوردر = <strong><?=$target['order_price']?></strong> جنيه </li>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
</div>
<?php endif; ?>
<section class="parent-section text-center">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                        <table id="orders-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-danger">#id</th>
                                    <th class="text-danger">ب.اليوم</th>
                                    <th class="text-danger">ن.اليوم</th>
                                    <th class="text-danger">تحميل</th>
                                    <th class="text-danger">تفريغ</th>
                                    <th class="text-danger">المندوب</th>
                                    <th class="text-danger">السواق</th>
                                    <th class="text-danger">المبلغ</th>
                                    <th class="text-danger">عدد الاوردرات</th>
                                    <th class="text-danger">ح.المندوب</th>
                                    <th class="text-danger">ح.السواق</th>
                                    <th class="text-danger">ح.العربيه</th>
                                    <th class="text-danger">ح.البنزين</th>
                                    <th class="text-danger">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                <?php if(!empty($this->accountant)): ?>
                                <?php foreach($this->accountant as $accountant): ?>
                                <?php $s_acc = empty($accountant['sales_representative_id']) ? 0 : $accountant['sales_representative_id']; ?>
                                <?php $orders_count = $this->orders->RowCount(' WHERE delivery_date = "'.$accountant['process_date'].'" AND sales_representative_id = '.$s_acc); ?>
                                <tr>
                                    <td class="id"><?=$accountant['id']?></td>
                                    <td class="start-day"><?=$accountant['out_petrol']?></td>
                                    <td class="end-day"><?=$accountant['back_petrol']?></td>
                                    <td class="load"><?=$accountant['loading']?></td>
                                    <td class="sell"><?=$accountant['unloading']?></td>
                                    <td class="sales"><?=$accountant['sales_representative_full_name']?></td>
                                    <td class="driver"><?=$accountant['driver']?></td>
                                    <td class="money"><?=$accountant['money']?></td>
                                    <td class="orders_count"><?=$orders_count?></td>
                                    <td class="sales-money"><?=$accountant['sales_representative_money']?></td>
                                    <td class="driver-money"><?=$accountant['driver_money']?></td>
                                    <td class="truck-money"><?=$accountant['car_money']?></td>
                                    <td class="petrol-money"><?=$accountant['petrol_money']?></td>
                                    <td class="actions">
                                        <button class="btn btn-primary btn-edit-order">تعديل
                                            <i class="fa fa-edit"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
<script>
    $('.all_targets_parent').on('click',function(){
        $(this).toggleClass('info-show');
    });
</script>
<script>
    window.inventory_acc_socket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=inv_acc&id=<?=$this->getSession('user_id')?>&inv_id=<?=$this->getSession('inventory_id')?>");
    window.inventory_acc_socket.onmessage = function(e)
    {
        var data = JSON.parse(e.data);
        if(data['action'] == 'logout'){
            window.location.href=window.domain + "/logout";
        }
    }
</script>