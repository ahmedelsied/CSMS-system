$(function(){
    // Global Var
    var overlay         = $('.overlay'),
        editOrder       = $('.edit-order'),
        formEditOrder   = $('#edit-order'),
        saveEdit        = $('.save-edit'),
        result          = $('.result'),
        divParent       = $('table').parent();


        // Form Edit Order Inputs

        idInpt          = editOrder.find($('input[name="id"]')),
        addrInpt        = editOrder.find($('input[name="address"]')),
        goveInpt        = editOrder.find($('select[name="gove"]')),
        phoneInpt       = editOrder.find($('input[name="phone"]')),
        fullNameInpt    = editOrder.find($('input[name="full_name"]')),
        detailsInpt     = editOrder.find($('input[name="order_details"]')),
        notesInpt       = editOrder.find($('input[name="notes"]'));
    //Set Last Day Of Date
    setDate('.inpt_date');

    // Search
    search('#search-front','#orders-table #myTable tr');

    // Edit Order
    $('body').on('click','.btn-edit-order',function(){
        //Get Data
        var id          = $(this).parent('td').siblings('.id').text(),
            address     = $(this).parent('td').siblings('.address').text(),
            gove        = $(this).parent('td').siblings('.gove'),
            phone       = $(this).parent('td').siblings('.phone').text(),
            fullName    = $(this).parent('td').siblings('.full-name').text(),
            details     = $(this).parent('td').siblings('.details').text(),
            notes       = $(this).parent('td').siblings('.notes').text();
        // Set Data Inside Inputs Values
        idInpt.val(id);
        addrInpt.val(address);
        goveInpt.val(gove.attr('data-gove-id'));
        phoneInpt.val(phone);
        fullNameInpt.val(fullName);
        detailsInpt.val(details);
        notesInpt.val(notes);
        overlay.fadeIn();
        editOrder.fadeIn();
        
        var self   = $(this).parents('tr'),
            index  = self.index();
        saveEdit.attr('data-eq',index);          
    });

    // GET_MORE_ORDERS
    $('#myTable').children('tr').length > 49 ? $('#orders-table').after('<p class="text-primary text-center" style="cursor:pointer" id="getMore" data-offset="50"><i class="fa fa-plus"></i>عرض المزيد</p>') : '';
    $('body').on('click','#getMore',function(){
        var data = {
            offset : $(this).attr('data-offset')
        }
        $(this).attr('data-offset',parseInt(data.offset) + 50);
        url = window.domain + '/customers_service/index/get_more_orders';
        ajaxRequest(url,'POST',data,'text',getOrdersSuccess,ajaxErrorFunc);
    });

    // Hidden Edit Form When Click On Overlay Div
    overlay.on('click',function(){
        $(this).fadeOut();
        editOrder.fadeOut();
    });

    // Edit Order When Submit it
    formEditOrder.on('submit',function(e){
        e.preventDefault();
        
        window.rowEq = $('#myTable tr').eq(saveEdit.attr('data-eq'));
        data = {
            id              : parseInt(idInpt.val()),
            address         : addrInpt.val(),
            gove            : parseInt(goveInpt.children('option:selected').val()),
            phone           : phoneInpt.val(),
            full_name       : fullNameInpt.val(),
            order_details   : detailsInpt.val(),
            notes           : notesInpt.val()
        };
        url = window.domain + '/customers_service/save_orders/update_order';
        ajaxRequest(url,'POST',data,'text',editSuccessFunc,ajaxErrorFunc);
    });
    



    
    // Get Count Of Orders In Custom Date
    $('.inpt_date').on('change',function(){
        data_send = {
            date : $(this).val()
        }
        url = window.domain + '/customers_service/save_orders/get_orders_count_by_month';
        ajaxRequest(url,'POST',data_send,'text',getCountDataSuccess,ajaxErrorFunc);
    });

    // On Edit Success
    function editSuccessFunc(data){
        if(data == 'dn'){
            window.rowEq.find('.id').text(idInpt.val());
            window.rowEq.find('.address').text(addrInpt.val());
            window.rowEq.find('.gove').text(goveInpt.children('option:selected').text());
            window.rowEq.find('.gove').attr('data-gove-id',goveInpt.children('option:selected').val());
            window.rowEq.find('.phone').text(phoneInpt.val());
            window.rowEq.find('.full-name').text(fullNameInpt.val());
            window.rowEq.find('.details').text(detailsInpt.val());
            window.rowEq.find('.notes').text(notesInpt.val());
            result.text('تم التعديل بنجاح');
            result.fadeIn();
            setTimeout(function(){
                result.fadeOut();
            },3000);
        }else{
            $('body').append('<div class="alert alert-danger result failed">هناك شئ ما خطأ</div>');
            $('.failed').fadeIn();
            setTimeout(function(){
                $('.failed').fadeOut();
            },3000,function(){
                $('.failed').remove();
            });
        }
        editOrder.fadeOut();
        overlay.fadeOut();
    }

    // Get Data With Date Success
    function getCountDataSuccess(data){
        $('.custom-month-data').text(data);
    }
    // On Ajax Error
    function ajaxErrorFunc(){
        alert('يرجى التحقق من الاتصال بالانترنت\nوإعادة المحاوله');
    }
    // Pad ID
    $('.id').each(function(){
        var id= pad($(this).text(),4);
        $(this).text(id);
    });
        // When Get Data Success
    function getOrdersSuccess(data){
        if(data.length === 0){
            alert('لم يعد هناك المزيد');
            $('#getMore').remove();
            return;
        }
        $('#myTable').append(data);
        $('.gove').each(function(){
            var goveName = $('#edit-order').find('select[name="gove"]').children('option[value="'+$(this).attr('data-gove-id')+'"]').text();
            if($(this).text().length > 0){
                $(this).text(goveName);
            }else{
                $(this).text('لم يتم التحديد');
            }
        });
    }
});