<div class="alert alert-success result text-center text-success"></div>
<div class="overlay"></div>
<form class="driver-name text-center well">
    <h3>أدخل اسم السائق</h3>
    <input class="form-control" type="text" placeholder="أدخل اسم السائق" name="driver-name" required="required"/>
    <br>
    <button class="btn btn-danger send-with-driver">إرسال
        <i class="fa fa-paper-plane"></i>
    </button>
</form>
<section class="parent-section text-center" style="padding-right: 0;">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                <br>
                <div class="container-fluid">
                    <div class="pull-right">
                        <select class="select-gove" id="select-gove">
                            <option value="" selected="true">عرض كل المحافظات</option>
                            <?php foreach($this->governments as $government): ?>
                            <option value="<?=$government['id']?>"><?=$government['government']?></option>
                            <?php endforeach; ?>
                        </select>
                        <select class="select-sales">
                            <option value="" selected="true" disabled="disabled">تحديد مندوب</option>
                            <?php foreach($this->sales_representative as $sales_representative): ?>
                            <option value="<?=$sales_representative['id']?>"><?=$sales_representative['full_name']?></option>
                            <?php endforeach; ?> 
                        </select>
                    </div>
                    <div class="pull-left">
                        <label for="select-all">تحديد الكل</label>
                        <input type="checkbox" id="select-all"/>
                    </div>
                </div>
                <table id="orders-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-danger">#id</th>
                            <th class="text-danger">العنوان</th>
                            <th class="text-danger">المحافظة</th>
                            <th class="text-danger">تحديد</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                    <?php if(!empty($this->orders)): ?>
                    <?php foreach($this->orders as $order): ?>
                        <tr>
                            <td class="id" data-id="<?=$order['id']?>"><?=$order['id']?></td>
                            <td class="address"><?=$order['address']?></td>
                            <td class="gove" data-gove-id="13"><?=$order['government_name']?></td>
                            <td class="select-order-list"><input type="checkbox" class="order-row" value="<?=$order['id']?>"/></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
                <div class="text-center no-orders-div">
                    <?php if(empty($this->orders)): ?>
                        <h3 class="no-orders">لا يوجد أوردرات</h3><br>
                    <?php endif; ?>
                    <button class="btn btn-danger w-btn" id="send-orders">إرسال
                        <i class="fa fa-paper-plane"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
window.user_id      = <?=$this->getSession('user_id')?>;
window.inventory_id = <?=$this->getSession('inventory_id')?>;
window.token        = "<?=$this->getSession('access_token')?>";
</script>