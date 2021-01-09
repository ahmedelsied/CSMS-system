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
    <form class="form-group form-order" id="edit-order" method="POST" action="<?=DOMAIN?>/inventory/categories/edit">
        <input type="hidden" class="order_id_input" name="catg_id"/>
        <h3>تعديل اسم الصنف</h3>
        <br>
        <div class="order-data">
            <div class="input-parent order-details">
                <input type="text" class="form-control edit-catg" name="clothing-type" placeholder="اسم الصنف" required="required"/>
            </div>
        </div>
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
            <div class="col-xs-12">
                <div class="pull-right">
                    <a href="<?=DOMAIN?>/inventory/add_categorey" class="btn btn-success">إضافة صنف <i class="fa fa-plus"></i></a>
                </div>
            </div>
            <br>
            <br>
            <br>
            <div class="col-xs-12">
                <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                <table id="orders-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-danger">#id</th>
                            <th class="text-danger">الصنف</th>
                            <th class="text-danger">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php if(!empty($this->categories)): ?>
                        <?php foreach($this->categories as $catg): ?>
                        <tr>
                            <td class="id"><?=$catg['id']?></td>
                            <td class="pieces-type"><?=$catg['catg_name']?></td>
                            <td class="action">
                                <button class="btn btn-primary btn-edit-order">تعديل
                                    <i class="fa fa-edit"></i>
                                </button>
                                <a class="btn btn-danger del" href="<?=DOMAIN?>/inventory/categories/delete/<?=$catg['id']?>">
                                    مسح 
                                    <i class="fa fa-times"></i>
                                </a>
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
    //DELETE Categorey
    deleteConfirm('هل أنت متأكد من مسح هذا الصنف؟ سوف يؤثر هذا في عرض البيانات');
</script>
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