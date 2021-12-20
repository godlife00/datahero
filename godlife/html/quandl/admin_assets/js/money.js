<!--
function numchk(num){ 
    num=new String(num); 
    num=num.replace(/,/gi,""); 
    return numchk1(num); 
} 
function numchk1(num){ 
    var sign=""; 
    if(isNaN(num)) { 
        alert("숫자만 입력할 수 있습니다."); 
        return 0; 
    } 
    if(num==0) { 
        return num; 
    } 

    if(num<0){ 
        num=num*(-1); 
        sign="-"; 
    } 
    else{ 
        num=num*1; 
    } 
    num = new String(num) 
    var temp=""; 
    var pos=3; 
    num_len=num.length; 
    while (num_len>0){ 
        num_len=num_len-pos; 
        if(num_len<0) { 
            pos=num_len+pos; 
            num_len=0; 
        } 
        temp=","+num.substr(num_len,pos)+temp; 
    } 
    return sign+temp.substr(1); 
} 

function num_han(num) { 
    if ( num == "1" )       return "일"; 
    else if ( num == "2" )  return "이"; 
    else if ( num == "3" )  return "삼"; 
    else if ( num == "4" )  return "사"; 
    else if ( num == "5" )  return "오"; 
    else if ( num == "6" )  return "육"; 
    else if ( num == "7" )  return "칠"; 
    else if ( num == "8" )  return "팔"; 
    else if ( num == "9" )  return "구"; 
    else if ( num == "십" ) return "십"; 
    else if ( num == "백" ) return "백"; 
    else if ( num == "천" ) return "천"; 
    else if ( num == "만" ) return "만 "; 
    else if ( num == "억" ) return "억 "; 
    else if ( num == "조" ) return "조 "; 
    else if ( num == "0" )  return ""; 
} 

function NUM_HAN(num,mode,return_input) { 
    if ( num == "" || num == "0" ) { 
        if ( mode == "3" ) { 
            return_input.value = ""; 
        } 
        return; 
    } 

    num=new String(num); 
    num=num.replace(/,/gi,""); 

    var len  = num.length; 
    var temp1 = ""; 
    var temp2 = ""; 

    if ( len/4 > 3 && len/4 <= 4 ) { 
        if ( len%4 == 0 ) { 
            temp1 = ciphers_han(num.substring(0,4)) + "조" + ciphers_han(num.substring(4,8)) + "억" + ciphers_han(num.substring(8,12)) + "만" + ciphers_han(num.substring(12,16)); 
        } 
        else { 
            temp1 = ciphers_han(num.substring(0,len%4)) + "조" + ciphers_han(num.substring(len%4,len%4+4)) + "억" + ciphers_han(num.substring(len%4+4,len%4+8)) + "만" + ciphers_han(num.substring(len%4+8,len%4+12)); 
        } 
    } 
    else if ( len/4 > 2 && len/4 <= 3 ) { 
        if ( len%4 == 0 ) { 
            temp1 = ciphers_han(num.substring(0,4)) + "억" + ciphers_han(num.substring(4,8)) + "만" + ciphers_han(num.substring(8,12)); 
        } 
        else { 
            temp1 = ciphers_han(num.substring(0,len%4)) + "억" + ciphers_han(num.substring(len%4,len%4+4)) + "만" + ciphers_han(num.substring(len%4+4,len%4+8)); 
        } 
    } 
    else if ( len/4 > 1 && len/4 <= 2 ) { 
        if ( len%4 == 0 ) { 
            temp1 = ciphers_han(num.substring(0,4)) + "만" + ciphers_han(num.substring(4,len)); 
        } 
        else { 
            temp1 = ciphers_han(num.substring(0,len%4)) + "만" + ciphers_han(num.substring(len%4,len)); 
        } 
    } 
    else if ( len/4 <= 1 ) { 
        temp1 = ciphers_han(num.substring(0,len)); 
    } 

    for (var i=0; i<temp1.length; i++) { 
        temp2 = temp2 + num_han(temp1.substring(i, i+1)); 
    } 

    temp3=new String(temp2); 
    temp3=temp3.replace(/억 만/gi,"억 "); 
    temp3=temp3.replace(/조 억/gi,"조 "); 

    if ( mode == 1 ) { 
        alert(temp3 + " 원"); 
    } else if ( mode == 2 ) { 
        return temp3; 
    } else if ( mode == 3 ) { 
        return_input.value = "( " + temp3 + " 원 )"; 
    } 
} 

function ciphers_han(num) { 
    var len  = num.length; 
    var temp = ""; 

    if( len == 1 ) { 
        temp = num; 
    }else if ( len == 2 ) { 
        temp = num.substring(0,1) + "십" + num.substring(1,2); 
    }else if ( len == 3 ) { 
        temp = num.substring(0,1) + "백" + num.substring(1,2) + "십" + num.substring(2,3); 
    }else if ( len == 4 ) { 
        temp = num.substring(0,1) + "천" + num.substring(1,2) + "백" + num.substring(2,3) + "십" + num.substring(3,4); 
    } 
    num=new String(temp); 
    num=num.replace(/0십/gi,""); 
    num=num.replace(/0백/gi,""); 
    num=num.replace(/0천/gi,""); 
    return num; 
} 
function moncom(mon) { 
    var factor = mon.length % 3; 
    var su     = (mon.length - factor) / 3; 
    var com    =  mon.substring(0,factor); 
    for(var i=0; i < su ; i++) { 
        if((factor == 0) && (i == 0)) { 
            com += mon.substring(factor+(3*i), factor+3+(3*i)); 
        } 
        else { 
            com += ","  ; 
            com += mon.substring(factor+(3*i), factor+3+(3*i)); 
        } 
    } 
    document.write(com); 
}
//-->