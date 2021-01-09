<style>
    input[type="text"]{
        font-size: 14px;
        direction:ltr;
    }
    legend{
        font-size:15px;
    }
</style>
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
<div class="alert alert-success result"></div>
<div class="overlay"></div>
<form class="form-group form-targets" id="form-targets" method="POST" action="<?=DOMAIN?>/admin/set_targets/add_target">
    <h3>إضافة تارجت</h3>
    <br>
    <div class="target_clone">
        <div class="target_clone_parent">
            <div class="input-parent half-right">
                <input class="form-control targets" type="text" name="target" placeholder="التارجت" required="required"/>
            </div>
            <div class="input-parent half-left">
                <input class="form-control target_price" type="text" name="traget-price" placeholder="سعر الأوردر بعد تحقيق التارجت" required="required"/>
            </div>
        </div>
    </div>
    <br>
    <button class="btn btn-danger w-btn">حفظ
        <i class="fa fa-save"></i>
    </button>
</form>
<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="all-salaries-parent">
                    <h3><strong>وضع تارجت المناديب</strong></h3>
                    <form class="text-right targets-form-basic">
                        <fieldset dir="rtl">
                            <legend>المندوبين</legend>
                            <div class="target-inpt" data-id="1">
                                <div class="input-parent half-right">
                                    <input class="form-control targets" type="text" name="min-orders" placeholder="أقل عدد أوردرات لحساب اليوم" required="required" value="<?=isset($this->all_targets[0]['target']) ? $this->all_targets[0]['target'] : '' ?>"/>
                                </div>
                                <div class="input-parent half-left">
                                    <input class="form-control target_price" type="text" name="sales-salary" placeholder="ثابت المندوبين يومي" required="required" value="<?=isset($this->all_targets[0]['order_price']) ? $this->all_targets[0]['order_price'] : '' ?>"/>
                                </div>
                            </div>
                            <?php if(!empty($this->all_targets)): ?>
                            <?php unset($this->all_targets[0]); ?>
                            <?php foreach($this->all_targets as $target): ?>
                            <div class="target-inpt" data-id="<?=$target['id']?>">
                                <div class="input-parent half-right">
                                    <input class="form-control targets" type="text" name="target" placeholder="التارجت" required="required" value="<?=$target['target']?>"/>
                                </div>
                                <div class="input-parent half-left remove-parent">
                                    <input class="form-control target_price" type="text" name="pre-traget-order-price" placeholder="سعر الأوردر قبل تحقيق التارجت" required="required" value="<?=$target['order_price']?>"/>
                                    <a class="remove-target del" href="<?=DOMAIN?>/admin/set_targets/delete_target/<?=$target['id']?>"><i class="pull-left fa fa-times"></i></a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <div class="text-right">
                                <button type="button" class="btn btn-success add-more-targets-form">إضافة تارجت <i class="fa fa-plus"></i></button>
                            </div>
                        </fieldset>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary w-btn" id="save-salary">حفظ 
                                <i class="fas fa-save"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    var add_target_btn  = $('.add-more-targets-form'),
        targets_form    = $('#form-targets'),
        overlay         = $('.overlay'),
        result  = $('.result');
    add_target_btn.on('click',function(){
        overlay.fadeIn();
        targets_form.fadeIn();
    });
    overlay.on('click',function(){
        targets_form.fadeOut();
        $(this).fadeOut();
    });
    $('.targets-form-basic').on('submit',function(e){
        e.preventDefault();
        var url = window.domain + '/admin/set_targets/update_targets',
            data = {
                id           : [],
                targets      : [],
                target_price : []
            };
        $('.target-inpt').each(function(){
            data.id.push($(this).data('id'));
            data.targets.push(parseInt($(this).find('.targets').val()));
            data.target_price.push(parseInt($(this).find('.target_price').val()));
        });
        ajaxRequest(url,'POST',data,'text',ajaxTargetSuccess,ajaxError);
    });
    function ajaxTargetSuccess(data){
        if(data.length === 0){
            result.text('تم الحفظ بنجاح');
            result.fadeIn();
            setTimeout(function(){
                result.fadeOut();
            },3000);
        }else{
            alert('هناك شئ ما خطأ\nتأكد من ان الارقام باللغه الانجليزيه');
        }
    }
    function ajaxError(){
        alert('هناك شئ ما خطأ');
    }
</script>