<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <div class="container-fluid" style="margin-bottom:25px">
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
                            <p class="date-card active-date-card" data-date="curr_month" id="date-target" data-inv-id="<?=$this->inv_id?>" data-url="inventories">الشهر الحالي</p>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <select class="user-type" style="width: 100%; margin-bottom: 10px;border-color: #CCC;">
                        <option value="">جميع العمليات</option>
                        <option value="<?=INSIDE_SALES?>">البيع الداخلي</option>
                        <option value="<?=SALES_INCOME?>">البيع الخارجي(المناديب)</option>
                        <option value="<?=BACK_CLOTHS?>">مضاف للمرتجعات مخصوم من المخزن</option>
                    </select>
                    <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
                    <table id="orders-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-danger">#id</th>
                                <th class="text-danger">نوع العمليه</th>
                                <th class="text-danger">الصنف</th>
                                <th class="text-danger">عدد القطع</th>
                                <th class="text-danger">الحجم</th>
                                <th class="text-danger">المبلغ</th>
                                <th class="text-danger">صاحب العمليه(اضافه)</th>
                                <th class="text-danger">صاحب العمليه(خصم)</th>
                                <th class="text-danger">ملاحظات</th>
                                <th class="text-danger">التاريخ</th>
                            </tr>
                        </thead>
                        <tbody id="myTable">
                            <?php if(!empty($this->order_details_inv)): ?>
                            <?php foreach($this->order_details_inv as $details): ?>
                            <tr data-type="<?=$details['order_type']?>">
                                <td class="id"><?=$details['id']?></td>
                                <td class="process-type"><?=ORDER_TYPE[$details['order_type']]?></td>
                                <td class="type"><?=$details['catg_name']?></td>
                                <td class="count"><?=$details['count_of_pieces']?></td>
                                <td class="size"><?=$details['size']?></td>
                                <td class="money"><?=$details['money']?></td>
                                <td class="name-add"><?=$details['inventory_user_add_name']?></td>
                                <td class="name-minus"><?=$details['inventory_user_minus_name']?></td>
                                <td class="notes"><?=$details['notes']?></td>
                                <td class="date"><?=$details['date_of_process']?></td>
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
    $('body').on('click','.pagination span',function(){
        var url = window.domain + '/admin/inventories/get_with_date',
        date_page = $('#data_count').attr('data-date');
        if(date_page.includes('!')){
           var requested_date = date_page.split("!"),
            data = {
                start   : requested_date[0],
                end     : requested_date[1]
            }
        }else{
            var data = {
                date : date_page
            }
        }
        data.id =  parseInt($('#date-target').attr('data-inv-id'));
        data.offset = parseInt($(this).text())*50;
        console.log(data);
        ajaxRequest(url,'POST',data,'text',setDataWithDate,ajaxError);
        $(this).siblings().removeClass('active');
        $(this).addClass('active');
    });
    function setDataWithDate (order_data){
        if(order_data.length > 0){
            $('#myTable').html(order_data);
        }else{
            alert('لا يوجد بيانات');
        }
    }
    function ajaxError(){
        alert('تحقق من الاتصال بالانترنت وقم بإعادة المحاوله');
    }
</script>