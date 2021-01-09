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
                        <select class="select-shipp">
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
                <table id="orders-table" class="table table-bordered table-striped" data-url="order_under_shipping">
                    <thead>
                        <tr>
                            <th class="text-danger">#id</th>
                            <th class="text-danger">العنوان</th>
                            <th class="text-danger">المحافظة</th>
                            <th class="text-danger">رقم هاتف</th>
                            <th class="text-danger">اسم العميل</th>
                            <th class="text-danger">تفاصيل الأوردر</th>
                            <th class="text-danger">ملاحظات</th>
                            <th class="text-danger">المندوب</th>
                            <th class="text-danger">مسؤول الشحن</th>
                            <th class="text-danger">تاريخ الطلب</th>
                            <th class="text-danger">تحديد</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php foreach($this->review_orders as $order): ?>
                        <tr>
                            <td class="id"><?=$order['id']?></td>
                            <td class="address"><?=$order['address']?></td>
                            <td class="govs" data-gove-id="<?=$order['government']?>"><?=$order['government_name']?></td>
                            <td class="phone"><?=$order['phone_number']?></td>
                            <td class="full-name"><?=$order['full_name']?></td>
                            <td class="details"><?=$order['order_details']?></td>
                            <td class="notes"><?=$order['notes']?></td>
                            <td class="sales_representative"><?=$order['sales_representative_full_name']?></td>
                            <td class="shipping_admin" data-id="<?=$order['id']?>"><?=$order['shipping_admin_full_name']?></td>
                            <td class="request_date"><?=$order['request_date']?></td>
                            <td class="select-order-list"><input type="checkbox" class="order-row not" data-shipp="<?=$order['shipping_admin_id']?>" value="<?=$order['id']?>"/></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <button class="btn btn-danger w-btn" id="change-shpping-admin">إرسال
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
<script type="text/javascript">
		jQuery(function($){
            // Websocket
        if(!("WebSocket" in window)){
            alert('المتصفح قديم للغايه');
        }
        window.clientSocket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=supervisor&id=<?=$this->getSession('user_id')?>");
        $(document).on('click','#change-shpping-admin',function(){
            var shipping_admin   = Number($('.select-shipp').val()),
                checked = $('.order-row:checked'),
                idList  = [],
                old_shipping_admin = [];
                $(checked).each(function(i, obj) {
                    idList.push(Number(obj.value));
                    old_shipping_admin.push($(this).attr('data-shipp'));
                    $(this).attr('data-shipp',shipping_admin)
                });
            if(shipping_admin == 0 || isNaN(shipping_admin)){
                alert('من فضلك قم باختيار مسؤول شحن');
                return;
            }
            if(idList.length == 0){
                alert(' من فضلك قم باختيار اوردر واحد على الأقل غير موجود لمسؤول الشحن المحدد');
                return;
            }
            var chat_msg = $(this).val();
                window.clientSocket.send(
                    JSON.stringify({
                        'type':'shipping_admin',
                        'shipping_admin_id': shipping_admin,
                        'old_shipping_admin_id': old_shipping_admin,
                        'update_order_status': 1,
                        'idList': idList
                    })
                );
        });
        function clientSocket(){
			window.clientSocket.onopen = function(e) {
				console.log('Socket Is Open');
			};
			window.clientSocket.onerror = function(e) {
				console.log('Socket Error');
            }
            window.handle_message_from_other_pages = function(e)
			{
                var data = JSON.parse(e.data);
                if(data['action'] == 'logout'){
                    window.location.href=window.domain + "/logout";
                }
                if(data['status'] == 'updated'){
                    for(var id =0; id < data['idList'].length; id++){
                        $("#myTable tr").find("[data-id='" + data['idList'][id] + "']").parent('tr').remove();
                    }
                    var result  = $('.result');
                        result.text('تم التعديل بنجاح');
                        result.fadeIn();
                        setTimeout(function(){
                            result.fadeOut();
                        },3000);
                }else if(data['status'] == 'recived' || data['status'] == 'edit_content_under_review' || data['status'] == 'delete_content_under_review'){

                }else{
                    $('body').append('<div class="alert alert-danger result failed">هناك شئ ما خطأ</div>');
                    $('.failed').fadeIn();
                    setTimeout(function(){
                        $('.failed').fadeOut();
                    },3000,function(){
                        $('.failed').remove();
                    });
                }
            }
            window.clientSocket.onclose = function(){
                console.log('Socket Is Closed!');
                alert('لقد تم فقد الاتصال سيتم إعادة المحاوله في خلال 10 ثواني');
                setTimeout(() => {
                    try{
                        window.clientSocket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=supervisor&id=<?=$this->getSession('user_id')?>");
                        setTimeout(() => {
                            if(window.clientSocket.readyState === 1)
                            {
                                console.log('Socket Is Open');
                                alert('تم إعادة الاتصال');
                                clientSocket();
                            }else{
                                alert('لقد فقدنا الاتصال سيتم إعادة تحميل الصفحه');
                                window.location.reload();
                            }
                        }, 5000);
                    }catch(e){
                        
                    }
                    
                }, 5000);
            }
        }
        clientSocket();
		});
		</script>