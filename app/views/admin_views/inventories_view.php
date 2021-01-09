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
        <div class="text-right">
            <a href="<?=DOMAIN?>/admin/inventories/add_view" class="btn btn-primary" id="add-inventory-btn">إضافة مخزن</a>
        </div>
        <hr>
        <input class="form-control" type="text" id="search-inventory" placeholder="بحث..."/>
        <br><br>
        <div class="row">
            <?php if(!empty($this->all_inventories)): ?>
            <?php foreach($this->all_inventories as $inv): ?>
            <div class="col-md-3 col-xs-4 inventory-parent">
                <div class="inventory-box">
                    <br><br>
                    <a href="<?=DOMAIN?>/admin/inventories/show_content/<?=$inv['id']?>" class="inventory-name"><?=$inv['inventory_name']?></a>
                    <br>
                    <a href="<?=DOMAIN?>/admin/inventories/edit_view/<?=$inv['id']?>" class="btn btn-success">تعديل <i class="fa fa-edit"></i></a>
                    <a href="<?=DOMAIN?>/admin/inventories/delete/<?=$inv['id']?>" class="btn btn-danger del">مسح <i class="fa fa-times"></i></a>
                </div>
            </div>
            <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>