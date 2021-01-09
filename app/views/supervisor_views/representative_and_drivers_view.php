<section class="parent-section text-center">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                        <br>
                        <div class="container-fluid">
                            <div class="pull-right">
                                <p class="date-card" data-date="today">اليوم</p>
                            </div>
                            <div class="pull-left custom-date">
                                <label>الحسابات  في تاريخ:</label>
                                <input type="date" id="custom-date"/>
                            </div>
                        </div>
                        <table id="orders-table" class="table table-bordered table-striped" data-url="representative_and_drivers">
                            <thead>
                                <tr>
                                    <th class="text-danger">#id</th>
                                    <th class="text-danger">البنزين ب.ا</th>
                                    <th class="text-danger">البنزين ن.ا</th>
                                    <th class="text-danger">حساب البنزين</th>
                                    <th class="text-danger">تحميل</th>
                                    <th class="text-danger">تفريغ</th>
                                    <th class="text-danger">المبلغ</th>
                                    <th class="text-danger">المندوب</th>
                                    <th class="text-danger">السواق</th>
                                    <th class="text-danger">مرتب المندوب</th>
                                    <th class="text-danger">مرتب السواق</th>
                                    <th class="text-danger">حساب السياره</th>
                                    <th class="text-danger">محاسب المخزن</th>
                                    <th class="text-danger">المخزن</th>
                                    <th class="text-danger">وقت الخروج</th>
                                    <th class="text-danger">وقت الرجوع</th>
                                </tr>
                            </thead>
                            <tbody id="myTable">
                            <?php if(!empty($this->accountant)): ?>
                            <?php foreach($this->accountant as $accountant): ?>
                                <tr>
                                    <td class="id"><?=$accountant[0]?></td>
                                    <td class="petrol-start"><?=$accountant['out_petrol']?></td>
                                    <td class="petrol-start"><?=$accountant['back_petrol']?></td>
                                    <td class="petrol-money"><?=$accountant['petrol_money']?></td>
                                    <td class="load"><?=$accountant['loading']?></td>
                                    <td class="empty"><?=$accountant['unloading']?></td>
                                    <td class="money"><?=$accountant['money']?></td>
                                    <td class="sales-representative"><?=$accountant['sales_representative_full_name']?></td>
                                    <td class="driver"><?=$accountant['driver']?></td>
                                    <td class="sales-money"><?=$accountant['sales_representative_money']?></td>
                                    <td class="driver-money"><?=$accountant['driver_money']?></td>
                                    <td class="car-money"><?=$accountant['car_money']?></td>
                                    <td class="inventort_accountant"><?=$accountant['inventory_accountant_full_name']?></td>
                                    <td class="inventory"><?=$accountant['inventory_name']?></td>
                                    <td class="date"><?=$accountant['process_date']?></td>
                                    <td class="date"><?=$accountant['back_date']?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                        <?php if(empty($this->accountant)): ?>
                            <div class="text-center" style="margin:20px"><h3>لا يوجد اوردرات</h3></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
<script>
        window.clientSocket = new WebSocket("wss://<?=DOMAIN_REF?>/wss2/?access_token=<?=$this->getSession('access_token')?>&type=supervisor&id=<?=$this->getSession('user_id')?>");
</script>