<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>	
<script>
var variable = {id : 'id'};
$.ajax({
        type: "POST",
        url: "/Web-Tehnologies-2018-2019/application/pub_sub_server.php",
        success: function(data){
            if(data.length > 0){
                if(confirm('Found new vulnerabilities!\nWant to look at the now ?')){
                    var form = document.createElement('form');
                    document.body.appendChild(form);
                    form.method = 'post';
                    form.action = '/Web-Tehnologies-2018-2019/application/new.php';
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'data';
                    input.value = data;
                    form.appendChild(input);
                    form.submit();
                }
            }
        },
        failure: function(errMsg) {
            console.log('fail');
        },
        error:function(x,e) {
    if (x.status==0) {
        alert('You are offline!!\n Please Check Your Network.');
    } else if(x.status==404) {
        alert('Requested URL not found.');
    } else if(x.status==500) {
        alert('Internel Server Error.');
    } else if(e=='parsererror') {
        alert('Error.\nParsing JSON Request failed.');
    } else if(e=='timeout'){
        alert('Request Time out.');
    } else {
        alert('Unknow Error.\n'+x.responseText);
    }
} 
  });


</script>
</head>
<body class="notif">

</body>
</html>


