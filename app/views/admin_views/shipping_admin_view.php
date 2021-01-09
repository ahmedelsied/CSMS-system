<section class="parent-section text-center">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-9 col-xs-12 text-center">
                                    <label>من تاريخ:</label>
                                    <input type="date" class="start-date custom-date"/>
                                    
                                    <br class="visible-xs">
                                    <label>إلى تاريخ:</label>
                                    <input type="date" class="end-date custom-date"/>
                                    <br class="visible-xs">
                                    <input type="submit" value="تنفيذ" class="btn btn-danger any-date" id="get-data"/>
                                </div>
                                <br class="visible-xs">
                                <br class="visible-xs">
                                <br class="visible-xs">
                                <br class="visible-xs">
                                <hr class="visible-xs">
                                <div class="col-md-3 col-xs-12">
                                    <p class="date-card" data-date="today">اليوم</p>
                                    <p class="date-card active-date-card" data-date="curr_month" id="date-target" data-url="shipping_admin">الشهر الحالي</p>
                                </div>
                            </div>
                        </div>
                        <div class="container-fluid">
                            <br>
                            <br>
                            <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                            <table id="orders-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-danger">#id</th>
                                        <th class="text-danger">مسؤول الشحن</th>
                                        <th class="text-danger">عدد العمليات</th>
                                        <th class="text-danger">اسم المخزن</th>
                                        <th class="text-danger">التاريخ</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                <?php if(!empty($this->all_orders)): ?>
                                <?php foreach($this->all_orders as $orders): ?>
                                    <tr>
                                        <td class="id"><?=$orders['id']?></td>
                                        <td class="shipping-admin-name"><?=str_replace('"', '',$orders['shipping_admin_full_name'])?></td>
                                        <td class="orders"><?=$orders['count_of_orders']?></td>
                                        <td class="inv_name"><?=str_replace('"', '',$orders['inventory_name'])?></td>
                                        <td class="data"><?=$orders['request_date']?></td>
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