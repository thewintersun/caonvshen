function getScrollWidth(d) {
            try {
                d = d || window;
                if (d.document.compatMode === "BackCompat") {
                    return d.document.body.scrollWidth
                } else {
                    return d.document.documentElement.scrollWidth
                }
            } catch(c) {
                return 0
            }
}
function getScrollHeight(d) {
            try {
                d = d || window;
                if (d.document.compatMode === "BackCompat") {
                    return d.document.body.scrollHeight
                } else {
                    return d.document.documentElement.scrollHeight
                }
            } catch(c) {
                return 0
            }
}
function getClientWidth(d) {
            try {
                d = d || window;
                if (d.document.compatMode === "BackCompat") {
                    return d.document.body.clientWidth
                } else {
                    return d.document.documentElement.clientWidth
                }
            } catch(c) {
                return 0
            }
}
 function getClientHeight(d) {
            try {
                d = d || window;
                if (d.document.compatMode === "BackCompat") {
                    return d.document.body.clientHeight
                } else {
                    return d.document.documentElement.clientHeight
                }
            } catch(c) {
                return 0
            }
}
function getScrollTop(c) {
            c = c || window;
            var e = c.document;
            return window.pageYOffset || e.documentElement.scrollTop || e.body.scrollTop
}
function getScrollLeft(c) {
            c = c || window;
            var e = c.document;
            return window.pageXOffset || e.documentElement.scrollLeft || e.body.scrollLeft
}
function scolse() {  document.getElementById("yxj001").style.display = 'none';}
function scolse2() {  document.getElementById("yxj002").style.display = 'none';}
function scolse3() {  document.getElementById("yxj003").style.display = 'none';}
var adtop1= 460;
var adtop2= 460;
function rightBottomAd(){
		
  document.getElementById("yxj001").style.top=(getScrollTop()+getClientHeight()-adtop1)+"px";
  document.getElementById("yxj001").style.left=(getClientWidth()-120)+"px";
  setTimeout(function(){rightBottomAd();},1);
}
function leftBottomAd(){
  document.getElementById("yxj002").style.top=getScrollTop()+getClientHeight()-adtop2+"px";
  document.getElementById("yxj002").style.left=0+"px";
  setTimeout(function(){leftBottomAd();},1);
}
function youxiaAd(){
  document.getElementById("yxj003").style.top=getScrollTop()+getClientHeight()-yxjtoppx+"px";
  document.getElementById("yxj003").style.left=(getClientWidth()-336)+"px";
  setTimeout(function(){youxiaAd();},1);
}
var moveflag1 = 0;
var moveflag2 = 0;
var moveflag3 = 0;
var yxjtoppx = 300;
function moveyxjdiv(){
	document.getElementById("gbyxj3").onmouseover=function(){
		if(moveflag3 == 0){
			moveflag3 = 1;
			setTimeout(function(){yxjtoppx = 330;},350);
		}
	}
	document.getElementById("gbyxj2").onmouseover=function(){
		if(moveflag2 == 0){
			moveflag2 = 1;
			setTimeout(function(){adtop2 = 500;},350);
		}
	}
	document.getElementById("gbyxj1").onmouseover=function(){
		if(moveflag1 == 0){
			moveflag1 = 1;
			setTimeout(function(){adtop1 = 500;},350);
		}
	}
}
rightBottomAd();
leftBottomAd();
youxiaAd();
moveyxjdiv();
