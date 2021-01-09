<section class="parent-section text-center">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="container-fluid">
                            <br>
                            <br>
                            <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                            <table id="orders-table" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-danger">#id</th>
                                        <th class="text-danger">المندوب</th>
                                        <th class="text-danger">الاجراء</th>
                                    </tr>
                                </thead>
                                <tbody id="myTable">
                                <?php if(!empty($this->all_sales)): ?>
                                <?php foreach($this->all_sales as $sales): ?>
                                    <tr>
                                        <td class="id"><?=$sales['id']?></td>
                                        <td class="sales_representative_name"><?=$sales['full_name']?></td>
                                        <td class="action">
                                            <a class="btn btn-danger w-btn" href="<?=DOMAIN?>/admin/sales/location/<?=$sales['id']?>">الموقع</a>
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