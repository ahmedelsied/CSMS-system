$(function(){
    var shipping_admin_socket = new WebSocket("wss://"+window.location.hostname+"/wss2/?access_token="+window.token+"&type=shipp&id="+window.user_id);

    function shipp_admin_socket(){
        shipping_admin_socket.onopen = function(e) {
            console.log('Socket Is Open');
        };
        shipping_admin_socket.onerror = function(e) {
            console.log('Socket Error');
        }
        shipping_admin_socket.onmessage = function(e)
        {
            var data = JSON.parse(e.data);
            console.log(data['status']);
            if(data['action'] == 'logout'){
                window.location.href=window.domain + "/logout";
            }
            if(data['status'] == 'deleted'){
                for(var id=0; id<data['idList'].length; id++){
                    $('td[data-id="'+data['idList'][id]+'"]').parent('tr').remove();   
                }
            }else if(data['status'] == 'updated'){   
                $('.no-orders').remove().end().next().remove();
                for(var order = 0; order < data['data_order'].length; order++){
                    var html = '<tr>';
        html += '<td class="id" data-id="'+data['data_order'][order]['id']+'">'+data['data_order'][order]['id']+'</td>';
        html += '<td class="address">'+data['data_order'][order]['address']+'</td>';
        html += '<td class="gove">'+data['data_order'][order]['government_name']+'</td>';
        html += '<td class="select-order-list"><input type="checkbox" class="order-row" value="'+data['data_order'][order]['id']+'"/></td>';
        html += '</tr>';
                    $('#myTable').append(html);
                    setPadID();
                }
                console.log(data['data_order']);
                
            }else if(data['status'] == 'send_to_sales'){
                for(var id=0; id<data['idList'].length; id++){
                    $('td[data-id="'+data['idList'][id]+'"]').parent('tr').remove();   
                }
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
        shipping_admin_socket.onclose = function(){
            console.log('Socket Is Closed!');
            alert('لقد تم فقد الاتصال سيتم إعادة المحاوله في خلال 10 ثواني');
            setTimeout(() => {
                try{
                    shipping_admin_socket = new WebSocket("wss://<?=DOMAIN_REF?>/?access_token="+window.token+"&type=shipp&id="+window.user_id);
                    setTimeout(() => {
                        if(shipping_admin_socket.readyState === 1)
                        {
                            console.log('Socket Is Open');
                            alert('تم إعادة الاتصال');
                            shipp_admin_socket();
                        }else{
                            alert('لقد فقدنا الاتصال سيتم إعادة تحميل الصفحه');
                            window.location.reload();
                        }
                    }, 5000);
                }catch(e){
                    console.log('Error');
                    
                    alert('لقد فقدنا الاتصال سيتم إعادة تحميل الصفحه');
                    window.location.reload();
                }
            }, 5000);
        }
    }
    shipp_admin_socket();

    // global var
    var overlay = $('.overlay'),
        driver  = $('.driver-name');
    // Search Function
    search('#search-front','#orders-table #myTable tr');

    // Select All Function
    selectAll('#select-all');
    
    // Select Gove
    selectGove();

    //Set PadID
    setPadID();
    // Send Orders To Sales Representative
    $('#send-orders').on('click',function(){
        var sales   = Number($('.select-sales').val()),
            checked = $('.order-row:checked'),
            idList  = [];
            $(checked).each(function(i, obj) {
                idList.push(Number(obj.value));
            });
        if(sales == 0){
            alert('من فضلك قم باختيار مندوب');
            return;
        }
        if(idList.length == 0){
            alert('من فضلك قم باختيار اوردر واحد على الأقل');
            return;
        }
        window.data = {
            id_list  : idList,
            sales_id : sales
        }
        overlay.show();
        driver.show();
    });
    driver.on('submit',function(e){
        e.preventDefault();

        shipping_admin_socket.send(
            JSON.stringify({
                'type':'send_to_sales',
                'sales_id': window.data.sales_id,
                'inventory_id': window.inventory_id,
                'idList'  : window.data.id_list,
                'driver_name' : $(this).children('input').val()
            })
        );
        $(this).fadeOut();
        overlay.fadeOut();
    });

    // Hidden Edit Form When Click On Overlay Div
    overlay.on('click',function(){
        $(this).fadeOut();
        driver.fadeOut();
    });

});