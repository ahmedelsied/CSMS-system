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
                            <th class="text-danger">ملاحظات</th>
                            <th class="text-danger">تاريخ اخر تعديل</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php if(!empty($this->inventory_content)): ?>
                        <?php foreach($this->inventory_content as $content): ?>
                        <tr>
                            <td class="id"><?=$content['id']?></td>
                            <td class="type"><?=$content['catg_name']?></td>
                            <td class="count"><?=$content['count_of_pieces']?></td>
                            <td class="size"><?=$content['size']?></td>
                            <td class="notes"><?=$content['notes']?></td>
                            <td class="notes"><?=$content['date_of_last_edit']?></td>
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