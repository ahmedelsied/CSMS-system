<section class="parent-section text-center">
    <div class="container-fluid">
        <div class="alert card card-info-red count-of-inventory-clothes text-center">
            <h4>القطع الكليه</h4>
            <br>
            <h3 class="count-inventory-peices">0000</h3>
        </div>
        <input class="form-control" type="text" id="search-front" placeholder="بحث..."/>
        <div class="row">
            <div class="col-xs-12">
                <table id="orders-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-danger">#id</th>
                            <th class="text-danger">الصنف</th>
                            <th class="text-danger">عدد القطع</th>
                            <th class="text-danger">الحجم</th>
                            <th class="text-danger">المبلغ</th>
                            <th class="text-danger">تاريخ اخر تعديل</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php if(!empty($this->back_clothes)): ?>
                        <?php foreach($this->back_clothes as $back_clothes): ?>
                        <tr>
                            <td class="id"><?=$back_clothes['id']?></td>
                            <td class="type"><?=$back_clothes['catg_name']?></td>
                            <td class="count"><?=$back_clothes['count_of_pieces']?></td>
                            <td class="size"><?=$back_clothes['size']?></td>
                            <td class="money"><?=$back_clothes['money']?></td>
                            <td class="notes"><?=$back_clothes['date_of_last_edit']?></td>
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
    // get count of inventory content
    var sum = 0;
    $('.count').each(function(){
        sum += parseFloat($(this).text());  // Or this.innerHTML, this.innerText
    });
    $('.count-inventory-peices').text(sum);
</script>