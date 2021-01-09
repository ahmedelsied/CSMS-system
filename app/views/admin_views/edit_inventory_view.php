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
                <form class="well" method="POST" action="<?=DOMAIN?>/admin/inventories/edit/<?=$this->id?>">
                    <input type="hidden" name="id" value="<?=$this->custom_inv[0]['id']?>"/>
                    <h3>تعديل اسم المخزن</h3>
                    <br><br>
                    <div class="input-parent">
                        <input class="form-control" type="text" name="inv_name" value="<?=$this->custom_inv[0]['inventory_name']?>" placeholder="اسم المخزن" required="required"/>
                    </div>
                    <button class="btn btn-danger w-btn">تعديل<i class="fa fa-edit" style="margin-right: 2px;"></i></button>
                </form>
            </div>
        </div>
    </div>
</section>