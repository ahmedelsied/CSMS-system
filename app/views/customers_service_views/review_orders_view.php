<?php $this->unSetSession('success')?>
<div class="alert alert-success result text-center text-success"></div>
    <div class="overlay"></div>
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
                    <?php endforeach; ?>
                </select>
                <div class="input-parent half-left">
                    <input class="form-control" type="text" name="phone" placeholder="رقم الهاتف" pattern="^([0-9]+([\.][0-9]+)?)|([\u0660-\u0669]+([\.][\u0660-\u0669]+)?)$" required="required">
                    <i class="fas fa-phone inside-inpt"></i>
                </div>
            </div>
            <div class="input-parent">
                <input class="form-control" type="text" name="full_name" placeholder="الاسم بالكامل" required="required"/>
                <i class="fa fa-user inside-inpt"></i>
            </div>
            <div class="input-parent">
                <input class="form-control" type="text" name="order_details" placeholder="تفاصيل الأوردر" required="required"/>
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
    <nav class="navbar navbar-default navbar-inverse text-center" role="navigation">
        <a href="<?=DOMAIN?>/logout" class="pull-right">تسجيل الخروج<i class="fa fa-sign-out-alt"></i></a>
        <a class="btn btn-primary w-btn pull-left back" href="../">رجوع
            <i class="fas fa-long-arrow-alt-left"></i>
        </a>
    </nav>
    <section class="my-orders">
        <div class="container">
            <?php if(!empty($this->review_orders)): ?>
            <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
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
                        <th class="text-danger">الإجراء</th>
                    </tr>
                </thead>
                <tbody id="myTable">
                    <?php $idList = []; foreach($this->review_orders as $order): $idList[] = $order['id'];?>
                    <tr>
                        <td class="id"><?=$order['id']?></td>
                        <td class="address"><?=$order['address']?></td>
                        <td class="gove" data-gove-id="<?=$order['government']?>"><?=$order['government']?></td>
                        <td class="phone"><?=$order['phone_number']?></td>
                        <td class="full-name"><?=$order['full_name']?></td>
                        <td class="details"><?=$order['order_details']?></td>
                        <td class="notes"><?=$order['notes']?></td>
                        <td>
                            <button class="btn btn-success btn-edit-order">تعديل
                                <i class="fa fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center">
                <button class="btn btn-danger w-btn" id="send-orders">إرسال
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>
            <?php else: ?>
                <div class="text-center" style="margin:20px"><h3>لا يوجد اوردرات</h3></div>
            <?php endif; ?>
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
    <?php if(!empty($idList)): ?>
    <script>
            // Send Orders
        window.customers_service_socket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=customers_service&id=<?=$this->getSession('user_id')?>");
        $(document).on('click','#send-orders',function(){

            var trEmpty = [];
            $('#myTable tr td').each(function(){
                if($(this).text().length === 0){
                    trEmpty.push(0);
                }
                if($(this).hasClass('gove')){
                    $(this).text() == 'لم يتم التحديد' ? trEmpty.push(1) : null;
                }
            });
            if($('table').children().length > 0 && trEmpty.length === 0){
                if(confirm('تأكيد الإرسال؟')){
                    window.customers_service_socket.send(
                        JSON.stringify({
                            'type'                  : 'send_to_supervisor',
                            'id'                    : <?=$this->getSession('user_id')?>,
                            'idList'                : <?= json_encode($idList); ?>
                        })
                    );
                }else{
                    return null;
                }
            }else{
                alert('يرجى ملئ جميع البيانات');
            }
        });

    function cust_socket(){
			window.customers_service_socket.onopen = function(e) {
				console.log('Socket Is Open');
			};
			window.customers_service_socket.onerror = function(e) {
				console.log('Socket Error');
            }
			window.customers_service_socket.onmessage = function(e)
			{
                var data = JSON.parse(e.data);
                
                if(data['action'] == 'logout'){
                    window.location.href=window.domain + "/logout";
                }
                if(data['status'] == 'send-success'){
                    var result  = $('.result');
                    result.text('تم الإرسال بنجاح');
                    result.fadeIn();
                    setTimeout(function(){
                        result.fadeOut();
                    },3000);
                    $('table').parent().append('<div class="text-center"><h3>لا يوجد اوردرات</h3></div>');
                    $('table').remove();
                    $('#send-orders').remove();
                    $('#search-front').remove();
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
            window.customers_service_socket.onclose = function(){
                console.log('Socket Is Closed!');
                alert('لقد تم فقد الاتصال سيتم إعادة المحاوله في خلال 10 ثواني');
                setTimeout(() => {
                    try{
                        window.customers_service_socket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/");
                        setTimeout(() => {
                            if(window.customers_service_socket.readyState === 1)
                            {
                                console.log('Socket Is Open');
                                alert('تم إعادة الاتصال');
                                cust_socket();
                            }else{
                                alert('لقد فقدنا الاتصال سيتم إعادة تحميل الصفحه');
                                window.location.reload();
                            }
                        }, 5000);
                    }catch(e){
                        console.log('Error');
                        alert('لقد فقدنا الاتصال سيتم إعادة تحميل الصفحه');
                        window.location.reload();
                    }
                }, 5000);
            }
        }
        cust_socket();
    </script>
    <?php endif; ?>