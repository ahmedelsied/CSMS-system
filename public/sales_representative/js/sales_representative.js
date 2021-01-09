$(function(){
    var status = $('.select-status'),    
        overlay          = $('.overlay'),
        result           = $('.result');
        undelivery       = $('.undelivery'),
        editOrder        = $('.edit-order'),
        formEditOrder    = $('#edit-order'),
        saveEdit         = $('.save-edit');
    // Search Function
    search('#search-front','#orders-table #myTable tr');

    //Set PadID
    setPadID();
    
    // Disable ButEdit Button Except When Status Equal 1
    status.on('change',function(){
        if($(this).val() == 1){
            $(this).parent().siblings('.action').children('button').attr('disabled',false);
        }else{
            $(this).parent().siblings('.action').children('button').attr('disabled',true);
        }
        if($(this).val() == 2 || parseInt($(this).val()) === 0 ){
            $(this).parent().siblings('.select-order-list').children('input').attr('disabled',true);
            $('#form-heading').text($(this).children('option:selected').data('text'));
            undelivery.children('form').attr('data-action',$(this).children('option:selected').data('url'));
            undelivery.find('textarea').val('');
            var orderId = parseInt($(this).parent().siblings('.id').text());
            undelivery.find('input[name="order_id"]').val(orderId);
            undelivery.fadeIn();
            overlay.fadeIn();
        }else{
            $(this).parent().siblings('.select-order-list').children('input').attr('disabled',false);
        }
    });


    // Hidden Edit Form When Click On Overlay Div
    overlay.on('click',function(){
        var orderParent = $('.order-data');
        orderParent.find('select,input').val('');
        orderParent.children('.input-parent').not(':first-of-type').remove();
        $(this).fadeOut();
        editOrder.fadeOut();
        undelivery.fadeOut();
    });

    // Edit Order
    $('.btn-edit-order').on('click',function(){
        var id       = parseInt($(this).parents('tr').data('id'));
        formEditOrder.find('#order_id').val(id);
        window.rowEq = $('#myTable tr[data-id="'+id+'"]');
        //Get Data
        overlay.fadeIn();
        editOrder.fadeIn();
    });

    $('#undelivered-form').on('submit',function(e){
        e.preventDefault();
        var data = {
            order_id        : parseInt($(this).find('input[name="order_id"]').val()),
            reason          : $(this).find('textarea').val()
        };
        window.order_id = data.order_id;
        var url = window.domain + '/sales_representative/index/'+$(this).data('action');
        
        ajaxRequest(url,'POST',data,'text',ajaxUndilevredSuccess,ajaxError);
    });
    formEditOrder.on('submit',function(e){
        e.preventDefault();
        var orderParent = $('.order-data'),
            inptParent  = $('.input-parent'),
            data = {
                order_id        : parseInt($(this).find('#order_id').val()),
                clothing_type   : [],
                size            : [],
                count_of_pieces : [],
                notes           : [],
                money           : []
            };
        
        inptParent.each(function(){
            data.clothing_type.push(parseInt($(this).find('select').val()));

            data.size.push($(this).find('.size-input').val());

            data.count_of_pieces.push(parseInt($(this).find('.count-of-pieces-input').val()));
            
            data.notes.push($(this).find('.notes-input').val());
            
            data.money.push(parseInt($(this).find('.money-input').val()));
        });
        var url = window.domain + '/sales_representative/index/sold_out';
        console.log(data);
        ajaxRequest(url,'POST',data,'text',ajaxSuccess,ajaxError);
        window.rowEq.children('.money').text(array_sum(data.money));
        window.rowEq.children('.count-of-peceies').text(array_sum(data.count_of_pieces));
        orderParent.children('.input-parent').not(':first-of-type').remove();
        orderParent.find('select,input').val('');
        editOrder.fadeOut();
        overlay.fadeOut();
    });

    // Filter Orders By Status
    $('.select-sales').on('change',function(){
        var value   = $(this).children('option:selected').val(),
            tr      = $('#myTable tr');    
        if(isEmpty(value)){
            tr.show();
            return;
        }
        $(tr).filter(function() {
        $(this).toggle($(this).attr('data-status').indexOf(value) > -1)
        });
    });

    // When Ajax Request Success
    function ajaxSuccess(data){
        if(data.length != 0){
            $('[data-id="'+data+'"]').remove();
            result.text('تم الإرسال بنجاح');
            var orders_count = parseInt($('.data-daily-orders').text());
            $('.data-daily-orders').text(orders_count+1);
            result.fadeIn();
            setTimeout(function(){
                result.fadeOut();
            },3000);
            $('.order-row:checked').parents('tr').remove();
        }
    }

    // When Undilevred Ajax Request Success
    function ajaxUndilevredSuccess(data){
        if(data.length != ''){
            result.text('تم الإرسال بنجاح');
            result.fadeIn();
            setTimeout(function(){
                result.fadeOut();
            },3000);
            $('[data-id="'+data+'"]').remove();
            $('.order-row').eq((window.order_id - 1)).parents('tr').remove();
            editOrder.fadeOut();
            undelivery.fadeOut();
            overlay.fadeOut();
        }else{
            alert('هناك شئ ما خطأ');
        }
    }

    // When Ajax Request Failed
    function ajaxError(){
        alert('يرجى التحقق من الاتصال بالانترنت \n وإعادة المحاوله');
    }

    // Add More Details
    var element = $('.order-details:first-of-type');
    $('.add-more').on('click',function(){
        var el = element.clone(true);
        el.children('input').val('');
        el.append('<i class="fa fa-times pull-left remove-details"></i>');
        $('.order-data').append(el);
    });

    // Delete Some Details
    $('body').on('click','.remove-details',function(){
        $(this).parent().remove();
    });
});