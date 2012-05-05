var cur_url = "http://shsair.local/~shsair/git/Config6/";
var s_cur_url = "http://shsair.local/~shsair/git/Config6/";

$.ajaxSetup ({  
    cache: false  
});

//writes custom indexOf function for internet explorer
if(!Array.indexOf){
    Array.prototype.indexOf = function(obj){
        for(var i=0; i<this.length; i++){
            if(this[i]==obj) return i;
        }
        return -1;
    }
}

function linkPage(page){
	window.location.href = cur_url+page;
}

function linkSPage(page){
	window.location.href = s_cur_url+page;
}