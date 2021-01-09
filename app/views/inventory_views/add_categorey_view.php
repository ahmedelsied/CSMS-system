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
                <form method="POST" action="<?=DOMAIN?>/inventory/add_categorey/add">
                    <h3>إضافة صنف</h3>
                    <br><br>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="catg" placeholder="اسم الصنف" required="required"/>
                    </div>
                    <button class="btn btn-danger w-btn">إضافه<i class="fa fa-plus" style="margin-right: 2px;"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    window.inventory_socket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=inv_user&id=<?=$this->getSession('user_id')?>&inv_id=<?=$this->getSession('inventory_id')?>");
    window.inventory_socket.onmessage = function(e)
    {
        var data = JSON.parse(e.data);
        if(data['action'] == 'logout'){
            window.location.href=window.domain + "/logout";
        }
    }
</script>