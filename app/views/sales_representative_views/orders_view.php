<div class="alert alert-success result text-center text-success"></div>
<div class="overlay"></div>
<div class="undelivery text-center">
    <form id="undelivered-form" class="well">
        <h2 id="form-heading">سبب</h2>
        <input type="hidden" name="order_id"/>
        <textarea class="form-control" name="reason_of_undelivery" required="required" maxlength="100" style="resize: none;" placeholder="السبب"></textarea>
        <br>
        <button class="btn btn-danger w-btn">إرسال <i class="fa fa-paper-plane"></i></button>
    </form>
</div>
<div class="edit-order text-center">
        <form class="form-group form-order well" id="edit-order">
            <h3>إضافة تفاصيل</h3>
            <h6><strong>(ملحوظه:لا تضغط على زر الحفظ إلا بعد التأكد من البيانات)</strong></h6>
            <br>
            <div class="order-data">
                <input type="hidden" name="order_id" id="order_id"/>
                <div class="input-parent order-details">
                    <select class="clothing-type" name="clothing-type" required="required">
                        <option value="" selected="true" disabled="disabled">تحديد الصنف</option>
                        <?php if(!empty($this->catgs)): ?>
                        <?php foreach($this->catgs as $catg): ?>
                        <option value="<?=$catg['id']?>"><?=$catg['catg_name']?></option>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <input type="text" class="size-input" name="size" placeholder="الحجم" required="required"/>
                    <input type="text" class="count-of-pieces-input" name="count-of-pieces" placeholder="عدد القطع المباعه" required="required"/>
                    <input type="text" class="money-input" name="money" placeholder="المبلغ" required="required"/>
                    <input type="text" class="notes-input" name="notes" placeholder="الملاحظات" required="required"/>
                </div>
            </div>
            <button class="btn btn-success pull-right add-more" type="button" data-eq>إضافة المزيد
                <i class="fa fa-plus"></i>
            </button>
            <br>
            <br>
            <br>
            <button class="btn btn-danger w-btn" data-eq>حفظ
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
            أقل عدد اوردرات لحساب اليوم هو <strong><?=$this->all_targets[0]['target']?></strong> اوردر
        </li>
        <li>
            تارجت :
            <ul class="all_targets" style="list-style:inside decimal">
                <?php unset($this->all_targets[0]); ?>
                <?php foreach($this->all_targets as $target):?>
                <li>عدد الاوردرات = <strong><?=$target['target']?></strong>
                <?php endforeach; ?>
            </ul>
        </li>
    </ul>
</div>
<?php endif; ?>
        <section class="orders-ready text-center">
            <div class="container">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-4">
                        <div class="card card-info-red daily-orders">
                            <br>
                            عدد الأوردرات اليوم
                            <br><br>
                            <h3 class="data-daily-orders"><?=$this->sold_count?></h3>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                        <br>
                        <div class="container-fluid">
                            <div class="pull-right">
                                <select class="select-sales">
                                    <option value="">جميع الطلبات</option>
                                    <option value="<?=READY?>">جاهزه للإرسال</option>
                                    <option value="<?=REDELIVERY?>">إعادة ارسال (غير مكتمله)</option>
                                </select>
                            </div>
                        </div>
                        <table id="orders-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th class="text-danger">#id</th>
                                    <th class="text-danger">العنوان</th>
                                    <th class="text-danger">المحافظة</th>
                                    <th class="text-danger">رقم هاتف</th>
                                    <th class="text-danger">اسم العميل</th>
                                    <th class="text-danger">تفاصيل الأوردر</th>
                                    <th class="text-danger">ملاحظات</th>
                                    <th class="text-danger">الحاله</th>
                                    <th class="text-danger">الإجراء</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                                <?php if(!empty($this->orders)): ?>
                                <?php foreach($this->orders as $order): ?>
                                <tr data-id="<?=$order['id']?>" data-status="<?=$order['redelivery']?>">
                                    <td class="id"><?=$order['id']?></td>
                                    <td class="address"><?=$order['address']?></td>
                                    <td class="gove" data-gove-id="<?=$order['government']?>"><?=$order['gove_name']?></td>
                                    <td class="phone"><a href="tel:<?=$order['phone_number']?>"><?=$order['phone_number']?></a></td>
                                    <td class="full-name"><?=$order['full_name']?></td>
                                    <td class="details"><?=$order['order_details']?></td>
                                    <td class="notes"><?=$order['notes']?></td>
                                    <td class="status">
                                        <select class="select-status">
                                            <option value="" selected="true">تحديد الحاله</option>
                                            <option value="0" data-url="refuse_order" data-text="سبب الرفض">
                                                مرفوضه</option>
                                            <option value="1">تم التسليم</option>
                                            <option value="2" data-url="undelivery_order" data-text="سبب عدم الاكتمال">
                                            غير مكتمله</option>
                                        </select>
                                    </td>
                                    <td class="action">
                                        <button class="btn btn-success btn-edit-order" disabled="true">تعديل
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
    window.user_id      = <?=$this->getSession('user_id')?>;
    window.access_token = "<?=$this->getSession('access_token')?>";
</script>
<script>
    $('.all_targets_parent').on('click',function(){
        $(this).toggleClass('info-show');
    });
</script>