<!DOCTYPE html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>	
<script>
var variable = {id : 'id'};
console.log(JSON.stringify(variable));
$.ajax({
        type: "POST",
        url: "/Web-Tehnologies-2018-2019/application/pub_sub_server.php",
        success: function(data){
            if(data.length > 0){
        	   console.log(data);
        	   alert('New vulnerabilities discovered!\nYou can see them at "/new.php"');
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
<body>

</body>
</html>


