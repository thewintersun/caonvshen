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
 
function rightBottomAd(){
  document.getElementById("yxj001").style.top=getScrollTop()+getClientHeight()-280;
  document.getElementById("yxj001").style.left=getClientWidth()-336;
  setTimeout(function(){rightBottomAd();},1);
}
rightBottomAd();
