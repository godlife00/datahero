//email validation check
function email_check(email){
    var email_ck=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
    if(email_ck.test(email) === false){
        return false;
    } else{
        return true;
    }
}

function ajax_params_set(list){
    var param = '';
    for(var key in list){
        var val = list[key];
        param += key+'='+val+'&';
    }

    return encodeURI(param);
}

function del_space(str){
    return str.replace(/(\s*)/g, "");
}

function password_validate(passwd){
    if(passwd.length < 10){
        return false;
    }
    var regexp_number = passwd.search(/[0-9]/g);
    var regexp_char = passwd.search(/[a-z]/ig);
    if(regexp_number < 0 || regexp_char < 0){
        return false;
    }
    return true;
}

function view_tracking(o_num) {
    var url = "/adminpanel/sales/ajax_get_tracking";;
    var param = "o_num="+o_num;
    $.post(url, param, function(data){
        var res = $.parseJSON(data);
       
        if(res.is_success == true) {
            window.open(res.msg, 'checkShipmentTracnking', 'width=760,height=800,scrollbars=yes');
        }else {
            alert(res.msg);
            return false;
        }
    });
}

function set_number_format(n) {
    if(n != null) {
        var reg = /(^[+-]?\d+)(\d{3})/;
        n += '';
        while (reg.test(n)) n = n.replace(reg, '$1' + ',' + '$2');
        return n;
    }
}

function unset_number_format(n) {
    return n.replace(/,/g, "");
}

function SuccessMsg(msg){
    $.smallBox({
        title : 'Success',
        content : "<i class='fa fa-check'></i> <i>"+msg+"</i>",
        color : "#119a6a",
        iconSmall : "fa fa-check fa-2x fadeInRight animated",
        timeout : 4000
    });
}

String.prototype.comma = function() {
    var tmp = this.split('.');

    var minus = false;
    var str = new Array();

    if(tmp[0].indexOf('-') >= 0) {
        minus = true;
        tmp[0] = tmp[0].substring(1, tmp[0].length);
    }

    var v = tmp[0].replace(/,/gi,'');
    for(var i=0; i<=v.length; i++) {
        str[str.length] = v.charAt(v.length-i);
        if(i%3==0 && i!=0 && i!=v.length) {
            str[str.length] = '.';
        }
    }
    str = str.reverse().join('').replace(/\./gi,',');
    if(minus) str = '-' + str;

    return (tmp.length==2) ? str + '.' + tmp[1] : str;
}


function number_to_comma(n) {
    var reg = /(^[+-]?\d+)(\d{3})/;
    n += '';

    while (reg.test(n))
    n = n.replace(reg, '$1' + ',' + '$2');

    return n;
}

function remove_comma(str) {
    while (str.search(",") >= 0) {
        str = (str + "").replace(',', '');
    }
    return str;
};

function onlyNum(obj) {
    var val = obj.value;
    var re = /[^0-9]/gi;
    obj.value = val.replace(re, '');
}

function fillzero(obj, len) {
  obj= '000000000000000'+obj;
  return obj.substring(obj.length-len);
} 

function timeSt(dateIn) {
   var yyyy = dateIn.getFullYear();
   var mm = dateIn.getMonth()+1;
   var dd  = dateIn.getDate();
   return String(yyyy +'-'+ fillzero(mm,2) +'-'+ fillzero(dd,2));
}

