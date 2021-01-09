<?php if($this->issetSession('success')): ?>
    <div class="alert alert-success result result-visible"><?=$this->getSession('success')?></div>
    <?php $this->unSetSession('success')?>
<?php endif;?>
<?php if($this->issetSession('failed')): ?>
    <div class="alert alert-danger result result-visible"><?=$this->getSession('failed')?></div>
    <?php $this->unSetSession('failed')?>
<?php endif;?>
<section class="order-section text-center">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <a class="review" href="<?=DOMAIN?>/customers_service/index/review_orders">المراجعه قبل الإرسال</a>
                <div class="alert card card-info-red text-center">
                    أوردرات اليوم
                    <br><br>
                    <h3 class="orders-today-data"><?=$this->orders_count_today?></h3>
                </div>
                <div class="alert card card-info-blue text-center">
                    أوردرات الشهر الحالي
                    <br><br>
                    <h3 class="curr-month-data"><?=$this->orders_count_month?></h3>
                </div>
                <div class="alert card card-info-black text-center">
                    أوردرات يوم:
                    <input class="inpt_date" type="date" name="custom-date"/>
                    <br><br>
                    <h3 class="custom-month-data">0000</h3>
                </div>
            </div>
            <div class="col-md-9">
                <form class="form-group form-add-order" method="POST" action="<?=DOMAIN?>/customers_service/save_orders">
                    <h3>تفاصيل الأوردر</h3>
                    <br>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="address" placeholder="العنوان"/>
                        <i class="fas fa-address-book inside-inpt"></i>
                    </div>
                    <div style="display: flex;">
                        <select class="input-parent form-control half-right" name="gove">
                            <option value="" selected="true">المحافظه</option>
                            <?php foreach($this->governments as $gove):?>
                                <option value="<?=$gove['id']?>"><?=$gove['government']?></option>
                            <?php endforeach;?>
                        </select>
                        <div class="input-parent half-left">
                            <input class="form-control" type="text" name="phone" placeholder="رقم الهاتف" pattern="^([0-9]+([\.][0-9]+)?)|([\u0660-\u0669]+([\.][\u0660-\u0669]+)?)$" required="required">
                            <i class="fas fa-phone inside-inpt"></i>
                        </div>
                    </div>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="full_name" placeholder="الاسم بالكامل"/>
                        <i class="fa fa-user inside-inpt"></i>
                    </div>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="order_details" placeholder="تفاصيل الأوردر"/>
                        <i class="fas fa-info-circle inside-inpt"></i>
                    </div>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="notes" placeholder="ملاحظات"/>
                        <i class="fas fa-pen inside-inpt"></i>
                    </div>
                    <button class="btn btn-danger w-btn">حفظ
                        <i class="fa fa-save"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    // Send Orders
    window.customers_service_socket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=customers_service&id=<?=$this->getSession('user_id')?>");
    
    window.customers_service_socket.onmessage = function(e)
    {
        var data = JSON.parse(e.data);
        
        if(data['action'] == 'logout'){
            window.location.href=window.domain + "/logout";
        }
    }
</script>