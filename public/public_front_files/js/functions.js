// Global Vars
window.result     =    $('.result');
window.domain     =    'https://'+window.location.hostname;

//Function To Set Last Day Of Input Date
function setDate(input){
    var currentDay = new Date().toISOString().split("T")[0];
    $(input).attr('min','2020-07-01');
    $(input).attr('max',currentDay);
}

//Function To Search In User Interface
function search(input,table){
    $(input).on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(table).filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
}

// Is Empty
function isEmpty(value){
    return value == '' ? true : false
}

// Handle Ajax Request
function ajaxRequest(url,requestMethod,inptData,dataType,successFunc,errorFunc){
    $.ajax({
        url:url,
        type:requestMethod,
        data:inptData,
        dataType:dataType,
        success:function(data){
            if(typeof(successFunc) == 'function'){
                successFunc(data);
            }
        },
        error:function(data){
            if(typeof(errorFunc) == 'function'){
                errorFunc(data);
            }
        }
    });
}

// Select All Func
function selectAll(main){
    $(main).on('click',function(){
        $('input:checkbox').not(this).not($('input:hidden')).prop('checked', this.checked);
    });
}

//# Pad Numbers Like This ==> 0001
function pad(num, size) {
    var s = num+"";
    while (s.length < size) s = "0" + s;
    return s;
}

// Set Pad ID
function setPadID(){
    $('.id').each(function(){
        var id= pad($(this).text(),4);
        $(this).text(id);
    });
}

// Select Gove
function selectGove(){
    $('#select-gove').on("change", function() {
        var value = $(this).children('option:selected').text().toLowerCase();
        if(isEmpty($(this).children('option:selected').val())){
            $('#myTable tr').show();
            return;
        }
        $('#myTable tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
}

// Function To Sum Array Values
function array_sum(inpt){
    var sum = 0;
   for(var i = 0; i < inpt.length; i++){
        sum += parseInt(inpt[i]);
    }
    return sum;
}

// Delete Confirmation
function deleteConfirm(msg){
    $('.del').on('click',function(e){
        confirm(msg) ? null : e.preventDefault(); 
    });
}


setTimeout(function(){
    $('.backend-result').remove();
},3000);

// Remove Post Data Afer Send
if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
}