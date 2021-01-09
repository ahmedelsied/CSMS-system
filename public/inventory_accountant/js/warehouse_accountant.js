$(function(){
    // Global Varibles
    var overlay          = $('.overlay'),
    saveEdit         = $('.save-edit');
    editOrder        = $('.edit-order'),
    // Search Function
    search('#search-front','#orders-table #myTable tr');

    // Set Pad ID
    setPadID();

    $('.btn-edit-order').on('click',function(){
        
        var id              = $(this).parent('td').siblings('.id').text(),
            //Get Data
            startDay        = $(this).parent('td').siblings('.start-day').text(),
            endDay          = $(this).parent('td').siblings('.end-day').text(),
            load            = $(this).parent('td').siblings('.load').text(),
            sell            = $(this).parent('td').siblings('.sell').text(),
            sales           = $(this).parent('td').siblings('.sales').text(),
            driver          = $(this).parent('td').siblings('.driver').text(),
            salesMoney      = $(this).parent('td').siblings('.sales-money').text(),
            Money           = $(this).parent('td').siblings('.money').text(),
            driverMoney     = $(this).parent('td').siblings('.driver-money').text(),
            truckMoney      = $(this).parent('td').siblings('.truck-money').text(),
            petrolMoney     = $(this).parent('td').siblings('.petrol-money').text();
        saveEdit.attr('data-eq',Number(id-1));
        window.rowEq = $('#myTable tr').eq(saveEdit.attr('data-eq'));
        editOrder.find('.order_id_input').val(parseInt(id));
        editOrder.find('.petrol-start-input').val(parseInt(startDay));
        editOrder.find('.petrol-end-input').val(parseInt(endDay));
        editOrder.find('.load-input').val(parseInt(load));
        editOrder.find('.sell-input').val(parseInt(sell));
        editOrder.find('.sales-name-input').val(sales);
        editOrder.find('.driver-name-input').val(driver);
        editOrder.find('.money-input').val(parseInt(Money));
        editOrder.find('.money-sales-input').val(parseInt(salesMoney));
        editOrder.find('.money-driver-input').val(parseInt(driverMoney));
        editOrder.find('.money-truck-input').val(parseInt(truckMoney));
        editOrder.find('.money-petrol-input').val(parseInt(petrolMoney));
        editOrder.find('input[type="number"]').each(function(){
            if(isNaN($(this).val())){
                $(this).val('')
            }
        });

        //Get Data
        overlay.fadeIn();
        editOrder.fadeIn();
    });

    // Hidden Edit Form When Click On Overlay Div
    overlay.on('click',function(){
        $(this).fadeOut();
        editOrder.fadeOut();
    });
});