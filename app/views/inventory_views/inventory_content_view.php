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
    <form class="form-group form-order" id="edit-order" method="POST" action="<?=DOMAIN?>/inventory/index/edit">
        <input type="hidden" class="order_id_input" name="order_id"/>
        <h3>تعديل الكميه</h3>
        <h6><strong>(ملحوظه:لا تضغط على زر الحفظ إلا بعد التأكد من البيانات)</strong></h6>
        <br>
        <div class="order-data">
            <div class="input-parent order-details">
                <input type="text" class="form-control count-of-pieces-input" name="count-of-pieces" placeholder="عدد القطع المباعه" required="required"/>
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
<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-sm-offset-5 text-center">
                <div class="card card-info-blue daily-pieces" style="display: inline-block;">
                    <br>
                    إجمالي القطع الحاليه
                    <br><br>
                    <h3 class="daily-pieces-num">0000</h3>
                </div>
            </div>
            <div class="col-xs-12">
                <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
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
                            <td class="id"><?=$content['id']?></td>
                            <td class="pieces-type"><?=$content['catg_name']?></td>
                            <td class="pieces-count"><?=$content['count_of_pieces']?></td>
                            <td class="size"><?=$content['size']?></td>
                            <td class="notes"><?=$content['notes']?></td>
                            <td class="action" data-eq="<?=$content['id']?>">
                                <?php if($content['notification'] == 1): ?>
                                    تحت مراجعة المشرف
                                <?php else: ?>
                                <button class="btn btn-success btn-edit-order">تعديل
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button class="btn btn-danger del delete_content" data-type="inventory_content" data-id="<?=$content['id']?>">
                                    مسح 
                                    <i class="fa fa-times"></i>
                                </button>
                                <?php endif; ?>
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
    // get count of inventory content
    var sum = 0;
    $('.pieces-count').each(function(){
        sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
    });
    $('.daily-pieces-num').text(sum);
</script>
<script>
$(function(){
    window.inventory_socket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=inv_user&id=<?=$this->getSession('user_id')?>&inv_id=<?=$this->getSession('inventory_id')?>");

    function shipp_admin_socket(){
        window.inventory_socket.onopen = function(e) {
            console.log('Socket Is Open');
        };
        window.inventory_socket.onerror = function(e) {
            console.log('Socket Error');
        }
        window.inventory_socket.onmessage = function(e)
        {
            var data = JSON.parse(e.data);
            if(data['action'] == 'logout'){
                window.location.href=window.domain + "/logout";
            }
            if(data['status'] == 'edit_content_under_review' || data['status'] == 'delete_content_under_review'){
                $('.action[data-eq="'+data['order_id']+'"]').html('تحت مراجعة المشرف');
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
        window.inventory_socket.onclose = function(){
            console.log('Socket Is Closed!');
            alert('لقد فقدنا الاتصال سيتم إعادة تحميل الصفحه');
            window.location.reload();
        }
    }
    shipp_admin_socket();
});
</script>
<script>
    // Send Orders To Shipping Admin
    $('#edit-order').on('submit',function(e){
        e.preventDefault();
        var data = $(this).serialize();
        window.inventory_socket.send(
            JSON.stringify({
                'type':'edit_inv_content_notification',
                'inventory_user_id': "<?=$this->getSession('user_id')?>",
                'data' : data
            })
        );

        $('.edit-order').fadeOut();
        $('.overlay').fadeOut();
    });

        // Delete Confirmation
    $('.delete_content').on('click',function(){
        if(confirm('هل انت متأكد من مسح هذا التسجيل؟ لا يمكن التراجع عن هذا الإجراء')){
            var id  = $(this).data('id');
            window.inventory_socket.send(
                JSON.stringify({
                    'type':'delete_inv_content_notification',
                    'inventory_user_id': "<?=$this->getSession('user_id')?>",
                    'id' : id
                })
            );

        }
    });
</script>