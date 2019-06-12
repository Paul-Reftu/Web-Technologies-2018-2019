
console.log('working');

var variable = {id : 'id'};
$.ajax({
        type: "POST",
        url: "/Web-Tehnologies-2018-2019/application/pub_sub_server.php",
        success: function(data){
            if(data.length > 0){
                if(confirm('Found new vulnerabilities!\nWant to look at them now ?')){
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
            else{
                console.log('nothing new');
                console.log(data);
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
