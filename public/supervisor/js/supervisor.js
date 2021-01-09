$(function(){
    // File Vars
    var result           = $('.result'),
        dateCard         = $('.date-card'),
        overlay          = $('.overlay'),
        editOrder        = $('.edit-order'),
        formEditOrder    = $('#edit-order'),
        saveEdit         = $('.save-edit'),
        
         // Form Edit Order Inputs

        idInpt          = editOrder.find($('input[name="id"]')),
        addrInpt        = editOrder.find($('input[name="address"]')),
        goveInpt        = editOrder.find($('select[name="gove"]')),
        phoneInpt       = editOrder.find($('input[name="phone"]')),
        fullNameInpt    = editOrder.find($('input[name="user"]')),
        detailsInpt     = editOrder.find($('input[name="order-details"]')),
        notesInpt       = editOrder.find($('input[name="notes"]'));
        notification    = $('#notification_sound');

    window.clientSocket.onmessage = function(e)
    {
        var domain = window.domain + '/supervisor/notification_action/';
        if(typeof window.handle_message_from_other_pages == 'function'){
            window.handle_message_from_other_pages(e);
        }
        var data = JSON.parse(e.data);
        if(data['action'] == 'logout'){
            window.location.href=window.domain + "/logout";
        }
        if(data['status'] == 'edit_content_under_review'){
            var notifi_data = JSON.parse(data['notif_data']),
                html = "<li>";
                html += "يريد <strong>"+notifi_data[0]['inv_user_full_name']+ "</strong> من مخزن <strong>" +notifi_data[0]['inv_name']+"</strong> تعديل عدد القطع من صنف <strong>"+notifi_data[0]['catg_name']+"</strong> وحجم <strong>"+notifi_data[0]['size']+"</strong> من <strong>"+notifi_data[0]['count_of_pieces']+"</strong> إلى <strong>"+notifi_data[0]['notifi_count_of_pieces']+"</strong>";
                html += "<br>";
                html += "<a class='btn btn-success accept-edit edit' href='"+domain+'inv_content/accept/update/'+notifi_data[0]['id']+"'>قبول</a>";
                html += "<a class='btn btn-danger refuse-edit edit' href='"+domain+'inv_content/refuse/delete/'+notifi_data[0]['id']+"'>رفض</a>";
                html += "<span class='date'>"+notifi_data[0]['date_of_last_edit']+"</span>";
                html += "<span class='type'>محتوى المخزن</span>";
                html += "</li>";
            $('.notif-box').html($('.notif-box').html() + html);
            if($('#count_of_notif').length > 0){
                $('#count_of_notif').text($('.notif-box').children().length);
                document.title = 'csms ' + '(' +$('.notif-box').children().length+ ')';
            }else{
                $('.navbar-left').append('<span id="count_of_notif">'+$('.notif-box').children().length+'</span>');
                document.title = 'csms';
            }
            notification[0].play();
        }
        if(data['status'] == 'edit_back_clothes_under_review' && !isEmpty(data['notif_data'])){
            var notifi_data = JSON.parse(data['notif_data']),
                html = "<li>";
                html += "يريد <strong>"+notifi_data[0]['inv_user_full_name']+ "</strong> من مخزن <strong>" +notifi_data[0]['inv_name']+"</strong> تعديل عدد المرتجعات من صنف <strong>"+notifi_data[0]['catg_name']+"</strong> وحجم <strong>"+notifi_data[0]['size']+"</strong> من <strong>"+notifi_data[0]['count_of_pieces']+"</strong> إلى <strong>"+notifi_data[0]['notifi_count_of_pieces']+"</strong>";
                html += "<br>";
                html += "<a class='btn btn-success accept-edit edit' href='"+domain+'back_clothes/accept/update/'+notifi_data[0]['id']+"'>قبول</a>";
                html += "<a class='btn btn-danger refuse-edit edit' href='"+domain+'back_clothes/refuse/update/'+notifi_data[0]['id']+"'>رفض</a>";
                html += "<span class='date'>"+notifi_data[0]['date_of_last_edit']+"</span>";
                html += "<span class='type'>مرتجعات</span>";
                html += "</li>";
            $('.notif-box').html($('.notif-box').html() + html);
            if($('#count_of_notif').length > 0){
                $('#count_of_notif').text($('.notif-box').children().length);
                document.title = 'csms ' + '(' +$('.notif-box').children().length+ ')';
            }else{
                $('.navbar-left').append('<span id="count_of_notif">'+$('.notif-box').children().length+'</span>');
                document.title = 'csms';
            }
            notification[0].play();
        }
        if(data['status'] == 'delete_content_under_review' && !isEmpty(data['notif_data'])){
            var notifi_data = JSON.parse(data['notif_data']),
                html = "<li>";
                html += "يريد <strong>"+notifi_data[0]['inv_user_full_name']+ "</strong> من مخزن <strong>" +notifi_data[0]['inv_name']+"</strong> مسح محتوى المخزن من صنف <strong>"+notifi_data[0]['catg_name']+"</strong> وحجم <strong>"+notifi_data[0]['size']+"</strong>";
                html += "<br>";
                html += "<a class='btn btn-success accept-edit edit' href='"+domain+'inv_content/accept/delete/'+notifi_data[0]['id']+"'>قبول</a>";
                html += "<a class='btn btn-danger refuse-edit edit' href='"+domain+'inv_content/refuse/update/'+notifi_data[0]['id']+"'>رفض</a>";
                html += "<span class='date'>"+notifi_data[0]['date_of_last_edit']+"</span>";
                html += "<span class='type'>محتوى المخزن</span>";
                html += "</li>";
            $('.notif-box').html($('.notif-box').html() + html);
            if($('#count_of_notif').length > 0){
                $('#count_of_notif').text($('.notif-box').children().length);
                document.title = 'csms ' + '(' +$('.notif-box').children().length+ ')';
            }else{
                $('.navbar-left').append('<span id="count_of_notif">'+$('.notif-box').children().length+'</span>');
                document.title = 'csms';
            }
            notification[0].play();
        }
        if(data['status'] == 'delete_back_clothes_under_review' && !isEmpty(data['notif_data'])){
            var notifi_data = JSON.parse(data['notif_data']),
                html = "<li>";
                html += "يريد <strong>"+notifi_data[0]['inv_user_full_name']+ "</strong> من مخزن <strong>" +notifi_data[0]['inv_name']+"</strong> مسح مرتجعات المخزن من صنف <strong>"+notifi_data[0]['catg_name']+"</strong> وحجم <strong>"+notifi_data[0]['size']+"</strong>";
                html += "<br>";
                html += "<a class='btn btn-success accept-edit edit' href='"+domain+'back_clothes/accept/delete/'+notifi_data[0]['id']+"'>قبول</a>";
                html += "<a class='btn btn-danger refuse-edit edit' href='"+domain+'back_clothes/refuse/delete/'+notifi_data[0]['id']+"'>رفض</a>";
                html += "<span class='date'>"+notifi_data[0]['date_of_last_edit']+"</span>";
                html += "<span class='type'>محتوى المخزن</span>";
                html += "</li>";
            $('.notif-box').html($('.notif-box').html() + html);
            if($('#count_of_notif').length > 0){
                $('#count_of_notif').text($('.notif-box').children().length);
                document.title = 'csms ' + '(' +$('.notif-box').children().length+ ')';
            }else{
                $('.navbar-left').append('<span id="count_of_notif">'+$('.notif-box').children().length+'</span>');
                document.title = 'csms';
            }
            notification[0].play();
        }
    }
    // Search Function
    search('#search-front','#orders-table #myTable tr');

    // Select All Function
    selectAll('#select-all');

    // Set Last Day Of Input Date
    setDate('#custom-date');

    // Select Gove
    selectGove();

    // Set Pad ID
    setPadID();
    
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
        saveEdit.attr('data-eq',index);  ;
    }); 

    // GET_MORE_ORDERS
    $('#myTable').children('tr').length > 49 ? $('#orders-table').after('<p class="text-primary text-center" style="cursor:pointer" id="getMore" data-offset="50"><i class="fa fa-plus"></i>عرض المزيد</p>') : '';
    $('body').on('click','#getMore',function(){
        var data = {
            offset : $(this).attr('data-offset')
        },
        url = window.domain + '/supervisor/'+$(this).prev().attr('data-url')+'/show_more';
        $(this).attr('data-offset',parseInt(data.offset) + 50);
        ajaxRequest(url,'POST',data,'text',getOrdersSuccess,ajaxError);
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
            id              : idInpt.val(),
            address         : addrInpt.val(),
            gove            : goveInpt.val(),
            phone           : phoneInpt.val(),
            full_name       : fullNameInpt.val(),
            order_details   : detailsInpt.val(),
            notes           : notesInpt.val(),
        };
        url = window.domain + '/supervisor/index/update_order';
        ajaxRequest(url,'POST',data,'text',editSuccessFunc,ajaxError);
    });
    
    // Send Orders To Shipping Admin
    $('#send-orders').on('click',function(){
        var sales   = Number($('.select-sales').val()),
            checked = $('.order-row:checked'),
            idList  = [];
            $(checked).each(function(i, obj) {
                idList.push(Number(obj.value));
            });
        if(sales == 0 || isNaN(sales)){
            alert('من فضلك قم باختيار مسؤول شحن');
            return;
        }
        if(idList.length == 0){
            alert('من فضلك قم باختيار اوردر واحد على الأقل');
            return;
        }
        var data = {
            idList             : idList,
            shipping_admin_id   : sales
        }
        var url = window.domain + $(this).data('url');
        confirm('تأكيد الإرسال؟') ? ajaxRequest(url,'POST',data,'text',ajaxSuccess,ajaxError) : null;
    });

    // Add Active Date Card
    dateCard.on('click',function(){
        var data = {
            date : $(this).data('date')
        },
        url = window.domain + '/supervisor/'+$('#orders-table').attr('data-url')+'/get_with_date';
        ajaxRequest(url,'POST',data,'text',setDataWithDate,ajaxError);
        $(this).addClass('active-date-card').siblings().removeClass('active-date-card');
    });


    // Get Sales And Drivers Activity In Custom Date
    $('#custom-date').on('change',function(){
        var data = {
            date : $(this).val()
        },
        url = window.domain + '/supervisor/'+$('#orders-table').attr('data-url')+'/get_with_date';
        ajaxRequest(url,'POST',data,'text',setDataWithDate,ajaxError);
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

    // When Ajax Request Success
    function ajaxSuccess(data){
        if(data == 'dn'){
            var checked = $('.order-row:checked');
            result.text('تم الإرسال بنجاح');
            result.fadeIn();
            setTimeout(function(){
                result.fadeOut();
            },3000);
            checked.parents('tr').siblings().length === 0 ? checked.parents('.col-xs-12').children().remove().end().append('<div class="text-center" style="margin:20px"><h3>لا يوجد اوردرات</h3></div>') : checked.parents('tr').remove();
        }else{
            $('body').append('<div class="alert alert-danger result failed">هناك شئ ما خطأ</div>');
            $('.failed').fadeIn();
            setTimeout(function(){
                $('.failed').fadeOut();
            },3000,function(){
                $('.failed').remove();
            });
        }
    }

    // When Ajax Request Failed
    function ajaxError(){
        alert('يرجى التحقق من الاتصال بالانترنت \n وإعادة المحاوله');
    }
    // When Get Data  Success
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

    // When Get Data With Date Success
    function setDataWithDate(data){
        if(data.length === 0){
            alert('لا يوجد بيانات بهذا التاريخ');
            return;
        }
        $('#myTable').html(data);
    }
});