<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
            <?php if(!empty($this->archive)): ?>
                <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                <br>
                <div class="container-fluid">
                    <div class="pull-right">
                        <p class="date-card" data-date="today">اليوم</p>
                    </div>
                    <div class="pull-left custom-date">
                        <label>الطلبات المنتهيه في تاريخ:</label>
                        <input type="date" id="custom-date"/>
                    </div>
                </div>
                <table id="orders-table" class="table table-bordered table-striped" data-url="archive">
                    <thead>
                        <tr>
                            <th class="text-danger">#id</th>
                            <th class="text-danger">العنوان</th>
                            <th class="text-danger">المحافظة</th>
                            <th class="text-danger">رقم هاتف</th>
                            <th class="text-danger">اسم العميل</th>
                            <th class="text-danger">تفاصيل الأوردر</th>
                            <th class="text-danger">ملاحظات</th>
                            <th class="text-danger">خدمة العملاء</th>
                            <th class="text-danger">المندوب</th>
                            <th class="text-danger">مسؤول الشحن</th>
                            <th class="text-danger">الحاله</th>
                            <th class="text-danger">سبب الرفض</th>
                            <th class="text-danger">عدد القطع</th>
                            <th class="text-danger">المبلغ</th>
                            <th class="text-danger">المخزن</th>
                            <th class="text-danger">تاريخ الطلب</th>
                            <th class="text-danger">تاريخ التسليم</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                    <?php foreach($this->archive as $archive): ?>
                    <?php $details = json_decode($archive['order_details']); ?>
                        <tr style="border-right:2px solid <?=$details->order_status == SOLD_OUT ? 'green' : 'red' ?>">
                            <td class="id"><?=$details->id?></td>
                            <td class="address"><?=$details->address?></td>
                            <td class="govs"><?=$details->government_name?></td>
                            <td class="phone"><?=$details->phone_number?></td>
                            <td class="full-name"><?=$details->full_name?></td>
                            <td class="details"><?=$details->order_details?></td>
                            <td class="notes"><?=$details->notes?></td>
                            <td class="customers-service"><?=$details->customers_service_full_name?></td>
                            <td class="sales"><?=$details->sales_representative_full_name?></td>
                            <td class="shipping_admin"><?=$details->shipping_admin_full_name?></td>
                            <td class="status"><?=ORDER_STATUS[$details->order_status]?></td>
                            <td class="undelivery_reason"><?=empty($details->reason_of_undelivery) ? 'لا يوجد' : $details->reason_of_undelivery; ?></td>
                            <td class="count-pecies"><?=$details->count_of_pieces?></td>
                            <td class="money"><?=$details->money?></td>
                            <td class="inventory"><?=$details->inventory_name?></td>
                            <td class="date-request"><?=$details->request_date?></td>
                            <td class="date-receive"><?=$details->delivery_date?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="text-center" style="margin:20px"><h3>لا يوجد اوردرات</h3></div>
            <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<script>
        window.clientSocket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=supervisor&id=<?=$this->getSession('user_id')?>");
</script>