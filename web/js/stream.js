$(document).ready(function() {

    $.ajax({
        url         :   "ajax.php?action=getUserData",
        type        :   "get",
        dataType    :   "json",
        success     :   function(data) {
            for(var type in data.userdata) {
                $("#userdata_" + type).text(data.userdata[type])
            }
        }
    })

})
