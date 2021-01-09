<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                <br>
                <div class="container-fluid">
                    <div class="pull-left">
                        <label for="select-all">تحديد الكل</label>
                        <input type="checkbox" id="select-all"/>
                    </div>
                </div>
                <table id="orders-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-danger">#id</th>
                            <th class="text-danger">الصنف</th>
                            <th class="text-danger">عدد القطع</th>
                            <th class="text-danger">الحجم</th>
                            <th class="text-danger">ملاحظات</th>
                            <th class="text-danger">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php if(!empty($this->content)): ?>
                        <?php foreach($this->content as $content): ?>
                        <tr>
                            <td class="id" data-id="<?=$content['id']?>"><?=$content['id']?></td>
                            <td class="pieces-type"><?=$content['catg_name']?></td>
                            <td class="pieces-count"><?=$content['count_of_pieces']?></td>
                            <td class="size"><?=$content['size']?></td>
                            <td class="notes"><?=$content['notes']?></td>
                            <td class="select-order-list"><input type="checkbox" class="order-row" value="<?=$content['id']?>"/></td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div class="text-center">
                    <button class="btn btn-danger w-btn" id="send-orders" data-url="/supervisor/index/send_to_shipping_admin">إرسال
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(function(){
    window.minus_clothes = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=inv_user&id=<?=$this->getSession('user_id')?>&inv_id=<?=$this->getSession('inventory_id')?>");

    function shipp_admin_socket(){
        window.minus_clothes.onopen = function(e) {
            console.log('Socket Is Open');
        };
        window.minus_clothes.onerror = function(e) {
            console.log('Socket Error');
        }
        window.minus_clothes.onmessage = function(e)
        {
            var data = JSON.parse(e.data);
            console.log(data['status']);
            if(data['action'] == 'logout'){
                window.location.href=window.domain + "/logout";
            }
            if(data['status'] == 'deleted'){
                console.log(data['idList']);
                for(var id=0; id<data['idList'].length; id++){
                    $('td[data-id="'+data['idList'][id]+'"]').parent('tr').remove();   
                }
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
        window.minus_clothes.onclose = function(){
            console.log('Socket Is Closed!');
            alert('لقد تم فقد الاتصال سيتم إعادة المحاوله في خلال 10 ثواني');
            setTimeout(() => {
                try{
                    window.minus_clothes = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=inv_user&id=<?=$this->getSession('user_id')?>");
                    setTimeout(() => {
                        if(window.minus_clothes.readyState === 1)
                        {
                            console.log('Socket Is Open');
                            alert('تم إعادة الاتصال');
                            shipp_admin_socket();
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
    shipp_admin_socket();
});
</script>
<script>
    // Send Orders To Shipping Admin
    $('#send-orders').on('click',function(){
        var checked = $('.order-row:checked'),
            idList  = [];
            $(checked).each(function(i, obj) {
                idList.push(Number(obj.value));
            });
        if(idList.length == 0){
            alert('من فضلك قم باختيار اوردر واحد على الأقل');
            return;
        }

        if(confirm('تأكيد الإرسال؟')){
            window.minus_clothes.send(
                JSON.stringify({
                    'type':'inventory',
                    'inventory_user_name': "<?=$this->getSession('full_name')?>",
                    'idList'  : idList
                })
            );
        }
    });
</script>