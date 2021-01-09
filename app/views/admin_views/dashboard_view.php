<section class="parent-section text-center">
            <div class="container-fluid stander-margin-bottom">
                <div class="container-fluid well">
                    <p class="h3">مرحبا <?=$this->getSession('full_name')?></p>
                </div>
                <div class="container-fluid well">
                    <div class="row">
                        <div class="col-md-5 col-md-5 col-md-offset-1">
                            <div class="alert card card-info-red">
                                عدد المبيعات هذا الشهر
                                <br><br>
                                <h3><?=$this->archive_sold_out_orders?></h3>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="alert card card-info-blue">
                                عدد المبيعات اليوم 
                                <br><br>
                                <h3><?=$this->sold_out_orders?></h3>
                            </div>
                        </div>
                        <div class="col-md-5 col-md-5 col-md-offset-1">
                            <div class="alert card card-info-black">
                                الأوردرات المرفوضه اليوم
                                <br><br>
                                <h3><?=$this->refused_orders?></h3>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="alert card card-info-blue">
                                إجمالي الربح هذا الشهر   
                                <br><br>
                                <h3><?=empty($this->month_earnings['month_earnings']) ? 0 :$this->month_earnings['month_earnings'] ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12 stander-margin-bottom">
                        <div class="container-fluid well">
                            <h4><strong>آخر الطلبات الوارده</strong></h4>
                            <table id="orders-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-danger">#id</th>
                                        <th class="text-danger">العنوان</th>
                                        <th class="text-danger">المحافظة</th>
                                        <th class="text-danger">رقم هاتف</th>
                                        <th class="text-danger">اسم العميل</th>
                                        <th class="text-danger">خدمة العملاء</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                <?php if(!empty($this->latest_orders)): ?>
                                <?php foreach($this->latest_orders as $order): ?>
                                    <tr>
                                        <td class="id"><?=$order['id']?></td>
                                        <td class="address"><?=$order['address']?></td>
                                        <td class="gove"><?=$order['gove_name']?></td>
                                        <td class="phone"><?=$order['phone_number']?></td>
                                        <td class="full-name"><?=$order['full_name']?></td>
                                        <td class="customers-service"><?=$order['customer_service_name']?></td>
                                    </tr>
                                <?php endforeach; ?>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <div class="container-fluid well">
                            <h4><strong>آخر الطلبات المباعه</strong></h4>
                            <table id="orders-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-danger">#id</th>
                                        <th class="text-danger">العنوان</th>
                                        <th class="text-danger">رقم هاتف</th>
                                        <th class="text-danger">اسم العميل</th>
                                        <th class="text-danger">مبلغ العمليه</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                <?php if(!empty($this->sold_out_orders_details)): ?>
                                <?php foreach($this->sold_out_orders_details as $order): ?>
                                    <tr>
                                        <td class="id"><?=$order['id']?></td>
                                        <td class="address"><?=$order['address']?></td>
                                        <td class="phone"><?=$order['phone_number']?></td>
                                        <td class="full-name"><?=$order['full_name']?></td>
                                        <td class="money"><?=$order['money']?></td>
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