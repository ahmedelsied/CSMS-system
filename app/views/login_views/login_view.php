    <?php if($this->issetSession('failed')):?>
        <div class="alert alert-danger result result-visible"><?=$this->getSession('failed');?></div>
        <?=$this->unSetSession('failed');?>
    <?php endif;?>
    <section class="login-section text-center">
        <div>
            <div class="row">
                <div class="col-sm-6 hidden-xs">
                    <div class="img-parent">
                        <img src="<?=IMGS?>logo<?=DS?>csms_logo.png" alt="Joker Logo"/>
                    </div>
                </div>
                <div class="col-sm-6 form-login">
                    <div class="form-parent">
                        <form class="form-group" method="POST" action="<?=DOMAIN?>/login/index/login_proccess">
                            <input type="hidden" name="_method" value="PUT"/>
                            <h2>تسجيل الدخول</h2>
                            <br>
                            <select class="text-center" name="user-type" required="required">
                                <option value="" selected="true" disabled="disabled">الصلاحيات</option>
                                <option value="<?=ADMIN_ID?>">مدير</option>
                                <option value="<?=SUPERVISOR_ID?>">مشرف</option>
                                <option value="<?=CUSTOMERS_SERVICE_ID?>">خدمة العملاء</option>
                                <option value="<?=INVENTORY_ID?>">المخزن</option>
                                <option value="<?=INVENTORY_ACCOUNTANT_ID?>">محاسب مخزن</option>
                                <option value="<?=SHIPPING_ADMIN_ID?>">مسؤول شحن</option>
                                <option value="<?=SALES_REPRESENTATIVE_ID?>">مندوب</option>
                            </select>
                            <br>
                            <br>
                            <br>
                            <div class="form-inputs">
                                <input class="form-control" type="text" name="user" placeholder="اسم المستخدم" required="required"/>
                                <br>
                                <input class="form-control" type="password" name="pass" placeholder="كلمة السر" required="required"/>
                            </div>
                            <br>
                            <br>
                            <button class="btn btn-danger">تسجيل الدخول</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>