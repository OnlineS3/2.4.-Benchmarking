
function getMaxCol(arr) {
    var max=0;
    for(var i=0;i<arr.length;i++) {
        var row_len = arr[i].length;
        if (row_len > max) {max = row_len;}
    }
    
    return max;
}

// format number to string
function formatNumber(n, dp){
    var w=n.toFixed(dp), k=w|0, b=n < 0 ? 1 : 0,
        u=Math.abs(w-k), d=(''+u.toFixed(dp)).substr(2, dp),
        s=''+k, i=s.length, r='';
    while ((i-=3)> b) {r=','+s.substr(i,3)+r;}
    return s.substr(0, i+3)+r+ (d ? '.'+d: '');
};

// convert json to array
function json2arr(json) {
    json = JSON.stringify(json);
    var parsed = JSON.parse(json);
    var arr = [];

    for(var x in parsed){
      arr.push(parsed[x]);
    }
    
    return arr;
}
 
 // call loadP and unloadP when body loads/unloads and scroll position will not move
function getScrollXY() {
    var x = 0, y = 0;
    if(typeof(window.pageYOffset)==='number') {
        // Netscape
        x = window.pageXOffset;
        y = window.pageYOffset;
    } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {
        // DOM
        x = document.body.scrollLeft;
        y = document.body.scrollTop;
    } else if( document.documentElement && ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {
        // IE6 standards compliant mode
        x = document.documentElement.scrollLeft;
        y = document.documentElement.scrollTop;
    }
    return [x, y];
}
           
function setScrollXY(x, y) {
    window.scrollTo(x, y);
}

function createCookie(name,value,days) {
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)===' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ)===0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function loadP(pageref){
    x=readCookie(pageref+'x');
    y=readCookie(pageref+'y');
    setScrollXY(x,y);
}

function unloadP(pageref){
    s=getScrollXY();
    createCookie(pageref+'x',s[0],0.1);
    createCookie(pageref+'y',s[1],0.1);
}

