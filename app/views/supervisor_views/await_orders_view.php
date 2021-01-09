<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <?php if(!empty($this->review_orders)): ?>
                <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                <br>
                <div class="container-fluid">
                    <div class="pull-right">
                        <select class="select-gove" id="select-gove">
                            <option value="" selected="true">عرض كل المحافظات</option>        
                            <?php foreach($this->governments as $gove): ?>
                                <option value="<?=$gove['id']?>"><?=$gove['government']?></option>
                            <?php endforeach;?>
                        </select>
                        <select class="select-sales">
                            <option value="" selected="true" disabled="disabled">تحديد مسؤول شحن</option>
                            <?php foreach($this->shipping_admin as $shipping_admin): ?>
                                <option value="<?=$shipping_admin['id']?>"><?=$shipping_admin['full_name'] . ' - ' . $shipping_admin['inventory_name']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="pull-left">
                        <label for="select-all">تحديد الكل</label>
                        <input type="checkbox" id="select-all"/>
                    </div>
                </div>
                <table id="orders-table" class="table table-bordered table-striped" data-url="index">
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
                            <th class="text-danger">الإجراء</th>
                            <th class="text-danger">تحديد</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php foreach($this->review_orders as $order): ?>
                        <tr>
                            <td class="id"><?=$order['id']?></td>
                            <td class="address"><?=$order['address']?></td>
                            <td class="gove" data-gove-id="<?=$order['government']?>"><?=$order['government']?></td>
                            <td class="phone"><?=$order['phone_number']?></td>
                            <td class="full-name"><?=$order['full_name']?></td>
                            <td class="details"><?=$order['order_details']?></td>
                            <td class="notes"><?=$order['notes']?></td>
                            <td class="customers-service" data-service-id="<?=$order['customers_service_id']?>"><?=$order['customers_service_full_name']?></td>
                            <td>
                                <button class="btn btn-success btn-edit-order">تعديل
                                    <i class="fa fa-edit"></i>
                                </button>
                            </td>
                            <td class="select-order-list"><input type="checkbox" class="order-row" value="<?=$order['id']?>"/></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <button class="btn btn-danger w-btn" id="send-orders" data-url="/supervisor/index/send_to_shipping_admin">إرسال
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </div>
                <?php else: ?>
                    <div class="text-center" style="margin:20px"><h3>لا يوجد اوردرات</h3></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<script>
    $('.gove').each(function(){
        var goveName = $('#edit-order').find('select[name="gove"]').children('option[value="'+$(this).attr('data-gove-id')+'"]').text();
        if($(this).text().length > 0){
            $(this).text(goveName);
        }else{
            $(this).text('لم يتم التحديد');
        }
    });
</script>

<script>
        window.clientSocket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=supervisor&id=<?=$this->getSession('user_id')?>");
</script>