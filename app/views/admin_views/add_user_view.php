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
<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-8 col-md-offset-2">
                <form class="well" method="POST" action="<?=DOMAIN?>/admin/add_user/add">
                    <h3>إضافة مستخدم</h3>
                    <br><br>
                    <div class="input-parent">
                        <select class="user-type-add" style="width: 100%;border-color: #CCC;" name="type" required="required">
                            <option value="" selected="true" disabled="disabled">تحديد مستخدم</option>
                            <option value="<?=SUPERVISOR_ID?>">مشرف</option>
                            <option value="<?=CUSTOMERS_SERVICE_ID?>">خدمة العملاء</option>
                            <option value="<?=INVENTORY_ID?>">المخزن</option>
                            <option value="<?=INVENTORY_ACCOUNTANT_ID?>">محاسب</option>
                            <option value="<?=SHIPPING_ADMIN_ID?>">مسؤول شحن</option>
                            <option value="<?=SALES_REPRESENTATIVE_ID?>">مندوب</option>
                        </select>
                    </div>
                    <div class="input-parent" id="inventories">
                        <!-- <select class="inventory-select" style="width: 100%;border-color: #CCC;" name="inventory" required="required">
                            <option value="" selected="true" disabled="disabled">تحديد مخزن</option>
                            <?php if(!empty($this->all_inventories)): ?>
                            <?php foreach($this->all_inventories as $inv): ?>
                            <option value="<?=$inv['id']?>"><?=$inv['inventory_name']?></option>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </select> -->
                    </div>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="user_name" placeholder="اسم المستخدم" required="required"/>
                    </div>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="full_name" placeholder="الاسم بالكامل" required="required"/>
                    </div>
                    <div class="input-parent">
                        <input class="form-control" type="password" name="password" placeholder="كلمة السر" required="required"/>
                    </div>
                    <button class="btn btn-danger w-btn">إضافه<i class="fa fa-plus" style="margin-right: 2px;"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>