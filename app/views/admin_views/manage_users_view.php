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
<div class="overlay"></div>
<div class="edit-order text-center">
    <form class="form-group form-order" id="edit-order" method="POST" action="<?=DOMAIN?>/admin/manage_users/edit">
        <h3>تفاصيل الأوردر</h3>
        <br>
        <input class="type" type="hidden" name="type"/>
        <input class="order_id_input" type="hidden" name="id"/>
        <div class="input-parent">
            <label class="text-right input-label"><strong>الاسم بالكامل</strong></label>
            <input class="form-control full-name-input" type="text" name="full-name" placeholder="الاسم بالكامل" required="required"/>
            <i class="fas fa-users inside-inpt"></i>
        </div>
        <div class="input-parent">
            <div class="input-parent">
                <label class="text-right input-label"><strong>اسم المستخدم</strong></label>
                <input class="form-control user-name-input" type="text" name="user" placeholder="اسم المستخدم" required="required">
                <i class="fas fa-user inside-inpt"></i>
            </div>
        </div>
        <div class="input-parent">
            <label class="text-right input-label"><strong>كلمة المرور الجديده</strong></label>
            <input class="form-control" type="password" name="pass" placeholder="كلمة المرور الجديده"/>
            <i class="fa fa-lock inside-inpt"></i>
        </div>
        <button class="btn btn-danger w-btn save-edit" data-eq>حفظ
            <i class="fa fa-save"></i>
        </button>
    </form>
</div>
<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="container-fluid">            
                    <div class="text-right">
                        <a href="<?=DOMAIN?>/admin/add_user" class="btn btn-primary">إضافة مستخدم<i class="fa fa-plus"></i></a>
                    </div>
                    <br>
                    <select class="user-type" style="width: 100%; margin-bottom: 10px;border-color: #CCC;">
                        <option value="">جميع المستخدمين</option>
                        <option value="<?=SUPERVISOR_ID?>">مشرف</option>
                        <option value="<?=CUSTOMERS_SERVICE_ID?>">خدمة العملاء</option>
                        <option value="<?=INVENTORY_ID?>">المخزن</option>
                        <option value="<?=INVENTORY_ACCOUNTANT_ID?>">محاسب</option>
                        <option value="<?=SHIPPING_ADMIN_ID?>">مسؤول شحن</option>
                        <option value="<?=SALES_REPRESENTATIVE_ID?>">مندوب</option>
                    </select>
                    <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                    <table id="orders-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-danger">#id</th>
                                <th class="text-danger">الاسم بالكامل</th>
                                <th class="text-danger">اسم المستخدم</th>
                                <th class="text-danger">اسم المخزن</th>
                                <th class="text-danger">الإجراء</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php if(!empty($this->customer_service)): ?>
                            <?php foreach($this->customer_service as $customer_service): ?>
                            <tr data-type="<?=CUSTOMERS_SERVICE_ID?>">
                                <td class="id"><?=$customer_service['id']?></td>
                                <td class="fullName"><?=$customer_service['full_name']?></td>
                                <td class="username"><?=$customer_service['user_name']?></td>
                                <td></td>
                                <td class="actions">
                                    <button class="btn btn-success btn-edit-order">تعديل
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a class="btn btn-danger delete_user" href="<?=DOMAIN?>/admin/manage_users/delete/<?=CUSTOMERS_SERVICE_ID?>/<?=$customer_service['id']?>">
                                        مسح 
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if(!empty($this->inv_accountant)): ?>
                            <?php foreach($this->inv_accountant as $inv_accountant): ?>
                            <tr data-type="<?=INVENTORY_ACCOUNTANT_ID?>">
                                <td class="id"><?=$inv_accountant[0]?></td>
                                <td class="fullName"><?=$inv_accountant['full_name']?></td>
                                <td class="username"><?=$inv_accountant['user_name']?></td>
                                <td class="inventory_name"><?=$inv_accountant['inventory_name']?></td>
                                <td class="actions">
                                    <button class="btn btn-success btn-edit-order">تعديل
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a class="btn btn-danger delete_user" href="<?=DOMAIN?>/admin/manage_users/delete/<?=INVENTORY_ACCOUNTANT_ID?>/<?=$inv_accountant[0]?>">
                                        مسح 
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if(!empty($this->inv_user)): ?>
                            <?php foreach($this->inv_user as $inv_user): ?>
                            <tr data-type="<?=INVENTORY_ID?>">
                                <td class="id"><?=$inv_user[0]?></td>
                                <td class="fullName"><?=$inv_user['full_name']?></td>
                                <td class="username"><?=$inv_user['user_name']?></td>
                                <td class="inventory_name"><?=$inv_user['inventory_name']?></td>
                                <td class="actions">
                                    <button class="btn btn-success btn-edit-order">تعديل
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a class="btn btn-danger delete_user" href="<?=DOMAIN?>/admin/manage_users/delete/<?=INVENTORY_ID?>/<?=$inv_user[0]?>">
                                        مسح 
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if(!empty($this->sales_representative)): ?>
                            <?php foreach($this->sales_representative as $sales_representative): ?>
                            <tr data-type="<?=SALES_REPRESENTATIVE_ID?>">
                                <td class="id"><?=$sales_representative[0]?></td>
                                <td class="fullName"><?=$sales_representative['full_name']?></td>
                                <td class="username"><?=$sales_representative['user_name']?></td>
                                <td class="inventory_name"><?=$sales_representative['inventory_name']?></td>
                                <td class="actions">
                                    <button class="btn btn-success btn-edit-order">تعديل
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a class="btn btn-danger delete_user" href="<?=DOMAIN?>/admin/manage_users/delete/<?=SALES_REPRESENTATIVE_ID?>/<?=$sales_representative[0]?>">
                                        مسح 
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if(!empty($this->shipping_admin)): ?>
                            <?php foreach($this->shipping_admin as $shipping_admin): ?>
                            <tr data-type="<?=SHIPPING_ADMIN_ID?>">
                                <td class="id"><?=$shipping_admin[0]?></td>
                                <td class="fullName"><?=$shipping_admin['full_name']?></td>
                                <td class="username"><?=$shipping_admin['user_name']?></td>
                                <td class="inventory_name"><?=$shipping_admin['inventory_name']?></td>
                                <td class="actions">
                                    <button class="btn btn-success btn-edit-order">تعديل
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a class="btn btn-danger delete_user" href="<?=DOMAIN?>/admin/manage_users/delete/<?=SHIPPING_ADMIN_ID?>/<?=$shipping_admin[0]?>">
                                        مسح 
                                        <i class="fa fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if(!empty($this->supervisor)): ?>
                            <?php foreach($this->supervisor as $supervisor): ?>
                            <tr data-type="<?=SUPERVISOR_ID?>">
                                <td class="id"><?=$supervisor['id']?></td>
                                <td class="fullName"><?=$supervisor['full_name']?></td>
                                <td class="username"><?=$supervisor['user_name']?></td>
                                <td></td>
                                <td class="actions">
                                    <button class="btn btn-success btn-edit-order">تعديل
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <a class="btn btn-danger delete_user" href="<?=DOMAIN?>/admin/manage_users/delete/<?=SUPERVISOR_ID?>/<?=$supervisor['id']?>">
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
    </div>
</section>
<script>
    window.manage_users = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=admin&id=<?=$this->getSession('user_id')?>");
    $('.delete_user').on('click',function(e){
        var sure = confirm('هل أنت متأكد من مسح هذا المستخدم؟ لا يمكن الرجوع عن هذا القرار');
        e.preventDefault();
        if(sure){
            var users = <?=json_encode(USERS_PATHS_ARRAY)?>,
                user_type = $(this).parents('tr').data('type'),
                user_id   = parseInt($(this).parents('tr').find('.id').text());
            window.manage_users.send(
            JSON.stringify({
                    'type'      : 'kick_user_out',
                    'user_type' : users[user_type],
                    'user_id'   : user_id
                })
            );
            window.location.href = $(this).attr('href');
        }
    });
    var send = 0;
    $('#edit-order').on('submit',function(e){
        if(send == 0){
            e.preventDefault();
            var users     = <?=json_encode(USERS_PATHS_ARRAY)?>,
            user_type = parseInt($(this).find('input[name="type"]').val()),
            user_id   = parseInt($(this).find('input[name="id"]').val());
            window.manage_users.send(
            JSON.stringify({
                    'type'      : 'kick_user_out',
                    'user_type' : users[user_type],
                    'user_id'   : user_id
                })
            );
            send += 1;
            $(this).submit();
        }
    })
</script>