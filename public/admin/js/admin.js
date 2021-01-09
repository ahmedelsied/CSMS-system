$(function(){
    // Global Varibles
    var overlay          = $('.overlay'),
        saveEdit         = $('.save-edit'),
        editOrder        = $('.edit-order'),
        result           = $('.result'),
        inventories      = $('#inventories'),
        dateCard         = $('.date-card'),
        addInventory     = $('.add-inventory-name');
    // Search Function
    search('#search-front','#orders-table #myTable tr');

    search('#search-inventory','.inventory-parent');

    // Set Pad ID
    setPadID();

    // Set Last Day Of Input Date
    setDate('.custom-date');
    
    // Delete Confirmation
    deleteConfirm('هل أنت متأكد من مسح هذا التسجيل؟ لا يمكن التراجع عن هذا الإجراء');

    // Select All Function
    selectAll('#select-all');

    // Edit Order
    $('.btn-edit-order').on('click',function(){
        var id              = $(this).parent('td').siblings('.id').text(),
            //Get Data
            fullName        = $(this).parent('td').siblings('.fullName').text(),
            username        = $(this).parent('td').siblings('.username').text();
            var self   = $(this).parents('tr'),
            index  = self.index();
        saveEdit.attr('data-eq',index); 
        window.rowEq = $('#myTable tr').eq(saveEdit.attr('data-eq'));
        editOrder.find('.order_id_input').val(parseInt(id));
        editOrder.find('.full-name-input').val(fullName);
        editOrder.find('.user-name-input').val(username);
        editOrder.find('.type').val($(this).parents('tr').data('type'));

        //Get Data
        overlay.fadeIn();
        editOrder.fadeIn();
    });
    
    // Hidden Edit Form When Click On Overlay Div
    overlay.on('click',function(){
        var orderParent = $('.order-data');
        orderParent.find('select,input').val('');
        $(this).fadeOut();
        editOrder.fadeOut();
        $('.add-inventory-name').fadeOut();
    });

    // Filter Users
    $('.user-type').on('change',function(){
        var value = $(this).val().toLowerCase();
        $('#orders-table #myTable tr').filter(function() {
          $(this).toggle($(this).attr('data-type').toLowerCase().indexOf(value) > -1)
        });
    });

    // Add User Form
    $('.user-type-add').on('change',function(){
        var selectVal = parseInt($(this).val());
        if(jQuery.inArray(selectVal,[3,4,5,6]) !== -1){
            var content = inventories.html();
            var content2 = content.replace("<!--","");
            var finalContent = content2.replace("-->","");
            inventories.html(finalContent);
        }else if(inventories.html().trim().charAt(1) !== '!'){
            inventories.html('<!--' + inventories.html() + '-->');
        }
    });

    // Abstract Function To Get Data From Json Between Dates 
    function getDataBetweenDate(start,dateFromJson,end) {
        var d1          = new Date(start).getTime(),
            d2          = new Date(end).getTime(),
            jsonDate    = new Date(dateFromJson).getTime();
        return jsonDate >= d1 && jsonDate <= d2 ? true : false;
    }

    // Send Cash
    $('#cash-dn').on('click',function(){
        var checked = $('.order-row:checked'),
            idList  = [];
            $(checked).each(function(i, obj) {
                if($(this).parents('tr').find('.money').text() == 0){
                    return;
                }else{
                    idList.push(Number(obj.value));
                }
            });
        if(idList.length == 0){
            alert('من فضلك قم باختيار شخص واحد لديه مستحقات على الأقل');
            return;
        }
        var data = {
            id_list : idList
        }
        ajaxRequest('#','POST',data,'text',ajaxSuccess,ajaxError);
    });

        // Add Active Date Card
    dateCard.on('click',function(){
        var data = {
            date : $(this).data('date')
        },
        target = $('#date-target').attr('data-url'),
        url = window.domain + '/admin/'+target+'/get_with_date';
        if(target == 'inventories'){
            data.id =  parseInt($('#date-target').attr('data-inv-id'));
            data.offset = 0;
        }
        ajaxRequest(url,'POST',data,'text',setDataWithDate,ajaxError);
        $(this).addClass('active-date-card').siblings().removeClass('active-date-card');
    });
    $('.any-date').on('click',function(){
        
        var data = {
            start :$('.start-date').val(),
            end :$('.end-date').val()
        },
        target = $('#date-target').attr('data-url'),
        url = window.domain + '/admin/'+target+'/get_with_date';
        if(target == 'inventories'){
            data.id =  parseInt($('#date-target').attr('data-inv-id'));
            data.offset = 0;
        }
        ajaxRequest(url,'POST',data,'text',setDataWithDate,ajaxError);
    });
    function setDataWithDate (order_data){
        if(order_data.length === 0){
            alert('لا يوجد اوردرات بهذا التاريخ');
            return;
        }
        if(typeof order_data == 'string' && order_data[0] != '{'){
            $('#myTable').html(order_data);
            if($('#data_count').val() >= 50){
                $('.pagination').remove();
                var pagination = '<div class="pagination text-center">',
                    page_num = Math.ceil($('#data_count').val()/50);
                for(var i=0;i<page_num;i++){
                    pagination += i == 0 ? '<span class="active">'+i+'</span>' : '<span>'+i+'</span>';
                }
                pagination += '</div>';
                $('#pagination-parent').append(pagination);
            }
            return;
        }
        var data = JSON.parse(order_data),
            array_of_data = [['Year', 'Sales', 'Refused']];
        $.each(data,function(i,value){
            var refused = typeof value[5] !== 'undefined' ? value[5][1] : 0,
                sales   = typeof value[6] !== 'undefined' ? value[6][1] : 0;
            $arr_of_data = [i,sales,refused];
            array_of_data.push($arr_of_data);
        });
        console.log(data);
        function drawChart(order_data) {
            var data = google.visualization.arrayToDataTable(order_data);
    
            var options = {
                title: 'أداء الشركه',
                curveType: 'function',
                legend: {position : 'right'},
                series: {
                    0: { color: '#c700ff' },
                    1: { color: '#00d5ff' },
                },
                hAxis: { minValue: 0, maxValue: 9 },
                pointSize: 10,
                backgroundColor:'#222222',
                vAxis : {
                    gridlines : {
                        color : '#424242'
                    }
                }
            };
    
            var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
    
            chart.draw(data, options);
        }
        drawChart(array_of_data);
    }
    // When Ajax Request Success
    function ajaxSuccess(){        
        result.text('تم الإرسال بنجاح');
        result.fadeIn();
        setTimeout(function(){
            result.fadeOut();
        },3000);
        $('.order-row:checked').parents('tr').find('.money').text('0');
    }
    function ajaxError(){
        alert('تحقق من الاتصال بالانترنت وقم بإعادة المحاوله');
    }
});