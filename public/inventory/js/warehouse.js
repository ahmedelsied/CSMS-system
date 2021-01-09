$(function(){

    // Global Varibles
    var overlay          = $('.overlay'),
        saveEdit         = $('.save-edit');
        editOrder        = $('.edit-order'),
    // Search Function
    search('#search-front','#orders-table #myTable tr');

    // Set Pad ID
    setPadID();

    // Select All Function
    selectAll('#select-all');

    // Edit Order
    $('.btn-edit-order').on('click',function(){
        var id          = $(this).parent('td').siblings('.id').text(),
            //Get Data
            piecesCount = $(this).parent('td').siblings('.pieces-count').text();
            catg        = $(this).parent('td').siblings('.pieces-type').text();
            
        editOrder.find('.edit-catg').val(catg);
        editOrder.find('.order_id_input').val(parseInt(id));
        editOrder.find('.count-of-pieces-input').val(parseInt(piecesCount));
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
    });
});