var Modal=Modal||{
    setup:function(){
        $(document).keydown(function(a){
            if(a.keyCode==27){
                var c=$(".ModalContainer:visible").attr("id");
                if(c)Modal.close(c);else $("#zoomScroll").length&&window.History.back();
                a.preventDefault()
                }
            })
    },
show:function(a){
    if(a=='EmailModal')
        {
            document.getElementById('MessageRecipientName').value='';
            document.getElementById('MessageRecipientEmail').value='';
            document.getElementById('MessageBody').value='';
            document.getElementById('output').innerHTML='';
        }
    var c=scr("#"+a);
    a=scr(".modal:first",c);
	if(scr('body').hasClass('noscrollf'))
	{
	}
	else
	{
    scr("body").addClass("noscroll");
	}
    c.show();
    var d=a.outerHeight()-50;
    a.css("margin-bottom","-"+d/2+"px");
    setTimeout(function(){
        c.addClass("visible");
        c.css("-webkit-transform","none")
        },1);
    return false
    },
close:function(a){
    var c=
    scr("#"+a);
    scr("#zoomScroll").length===0&&scr("body").removeClass("noscroll");
    c.removeClass("visible");
    setTimeout(function(){
        c.hide();
        c.css("-webkit-transform","translateZ(0)")
        },251);
    return false
    }
};

var RepinDialog=RepinDialog||{
    setup:function(){
        
        var a=scr("#Repin"),c=scr("form",a),d=scr(".Buttons .Button",a),f=scr("strong",d),g=scr(".DescriptionTextarea",a),b=scr(".mainerror",a);
      
	  
	   
         
            Tagging.initTextarea("#DescriptionTextarea");
            Tagging.priceTag("#DescriptionTextarea","#imagepin");
            scr("#Repin").submit(function(){
                Tagging.loadTags("#DescriptionTextarea","#id_pin_replies","#pin_tags","#id_buyable")
                });
            scr("#DescriptionTextarea").keyup(function(){
                scr("#postDescription").html(scr(this).val())
                })
           
	  
	  
        AddDialog.shareCheckboxes("Repin");
       
           
            
       
},
grid:function(){
    $(".repin_link").live("click",function(){
        
        pinID=$(this).parents(".pin").attr("data-id");
        RepinDialog.show(pinID);
        return false
        })
    },
show:function(a){
   
   
    },
reset:function(){
    document.getElementById('Header').style.zIndex = 7;
    var a=scr("#Repin");
    Modal.close("Repin");
    a.removeClass("visible").removeClass("super");
    scr(".PostSuccess",a).hide();
    scr("form",a).attr("action","");
    scr(".DescriptionTextarea",a).val("");
    scr(".ImagePicker .Images",a).html("");
    scr(".price",a).removeClass("visible").html("");
    scr(".mainerror",a).html("");
    scr(".Buttons .RedButton",a).removeClass("disabled");
    scr(".Buttons .RedButton strong",a).html("Pin It");
    scr("#repin_pin_id",a).val("")
    },
    append:function()
    {

var a=scr("#Repin");
//                        trackGAEvent("repin_submit","success","dialogue");
                        var h=scr("#PostSuccess");
                       
                        h.show();
                        setTimeout(function(){
                            a.addClass("super")
                            },1);
                        setTimeout(function(){
                            RepinDialog.reset()
                            },2500);
                       
    }
};
var BoardPicker=function(){
    return{
        setup:function(a,c,d){
            a=scr(a);
            var f=scr(".BoardListOverlay",a.parent()),g=scr(".BoardList",a),b=scr(".CurrentBoard",a),e=scr("ul",g);
            a.click(function(){
                g.show();
                f.show()
                });
            f.click(function(){
                g.hide();
                f.hide()
                });
            scr("li",e).live("click",function(){
                b.text(scr(this).text());
                f.hide();
                g.hide();
                c&&c(scr(this).attr("data"));
                return false
                });
            a=scr(".CreateBoard",g);
            var h=scr("input",a),k=scr(".Button",a);
            scr("strong",k);
            var l=scr(".CreateBoardStatus",a);
           
            k.click(function(){
                if(k.attr("disabled")==
                    "disabled")return false;
                if(h.val()=="Create New Board"){
                    l.html("Enter a board name").css("color","red").show();
                    return false
                    }
                    l.html("").hide();
                k.addClass("disabled").attr("disabled","disabled");
               
            return false
            })
        }
    }
}();
var AddDialog=function(){
    return{
        setup:function(a){
            var c="#"+a,d=scr(c);
            BoardPicker.setup(c+" .BoardPicker",function(f){
                scr(c+" #id_board").val(f)
                },function(f){
                scr(c+" #id_board").val(f)
                });
            AddDialog.shareCheckboxes(a);
            Tagging.initTextarea(c+" .DescriptionTextarea");
            Tagging.priceTag(c+" .DescriptionTextarea",c+" .ImagePicker");
         
    },
reset:function(a){
    a==="CreateBoard"&&CreateBoardDialog.reset();
    a==="ScrapePin"&&ScrapePinDialog.reset();
    a==="UploadPin"&&UploadPinDialog.reset();
    AddDialog._resets[a]&&AddDialog._resets[a]()
    },
close:function(a,c){
    scr("#"+a).addClass("super");
    Modal.show(c)
    },
childClose:function(a,
    c){
    var d=this,f=scr("#"+c);
    scr(".ModalContainer",f);
   
    //d.reset(c);
    
    scr("#"+a).removeClass("super");
    
    Modal.close(a);
    Modal.close(c)
    },
pinBottom:function(a){
    var c=scr("#"+a);
    scr(".PinBottom",c).slideDown(300,function(){
        var d=scr(".modal:first",c);
        d.css("margin-bottom","-"+d.outerHeight()/2+"px")
        })
    },
shareCheckboxes:function(a){
    function c(g){
        var b=scr("#"+a+" .publish_to_"+g),e=scr("#"+a+" #id_publish_to_"+g);
        b.change(function(){
            if(b.is(":checked")){
                e.attr("checked","checked");
                b.parent().addClass("active")
                }else{
                e.removeAttr("checked");
                b.parent().removeClass("active")
                }
            });
    var h=b.is(":checked");
    return function(){
        if(h){
            b.parent().addClass("active");
            b.attr("checked","checked")
            }else{
            b.parent().removeClass("active");
            b.removeAttr("checked")
            }
        }
}
var d=c("facebook"),f=c("twitter");
AddDialog._resets=AddDialog._resets||{};

AddDialog._resets[a]=function(){
    d();
    f()
    }
}
}
}();
var EditPin=function(){
    return{
        
        setup:function(){
         
            Tagging.initTextarea("#description_pin_edit");
            Tagging.priceTag("#description_pin_edit","#PinEditPreview");
            scr("#PinEdit").submit(function(){
                Tagging.loadTags("#description_pin_edit","#id_pin_replies","#pin_tags","#id_buyable")
                });
            scr("#description_pin_edit").keyup(function(){
                scr("#postDescription").html(scr(this).val())
                })
            }
        }
}();




var CreateBoardDialog=function(){
    return{
        setup:function(){
            function a(){
                if(!g){
                    g=true;
                    Tagging.initInput("#CreateBoard #collaborator_name",function(b){
                        f=b
                        },function(){
                        $("#CreateBoard #submit_collaborator").click()
                        })
                    }
                }
            function c(){
            var b=[];
            $("#CurrentCollaborators .Collaborator",d).each(function(){
                b.push($(this).attr("username"))
                });
            return b
            }
            var d=scr("#CreateBoard"),f=null,g=false;
   
    
BoardPicker.setup("#CreateBoard .BoardPicker",function(b){
    $("#id_category",d).val(b)
    });
scr("#BoardName",d).keyup(function(){
    scr(".board_name.error",
        d).html()!==""&&scr(".board_name.error",d).html("")
    });
scr(".Submit .Button",d).click(function(){
    
    if(scr("#BoardName",d).val()=="Board Name"||$("#BoardName",d).val()==""){
        scr(".board_name.error",d).html("Please enter a board name").show();
        return false
        }
        if(!scr("#id_category",d).val()){
        scr(".board_category.error",d).html("Please select a category").show();
        return false
        }
        var b=scr(".Submit .Button",d),e=b.children("strong");
    b.attr("disabled","disabled").addClass("disabled");
    e.html("Creating &hellip;");
  
return false
})
},
reset:function(){
    $("#BoardName").val("");
    $("input[value='me']").attr("checked",true);
    $("#CurrentCollaborators").empty()
    }
}
}();



var CropImage=function(){
    this.initialize.apply(this,arguments)
    };
    var BoardCoverSelector=function(){
    this.initialize.apply(this,arguments)
    };
    var Tagging=function(){
    return{
        friends:null,
        friendsLinks:{},
        getFriends:function(a,c,d){
            var e=a.term;
            (function(f){
                Tagging.friends?f():$.get("/x2ns4tdf0cd7cc9b/_getfriends/",function(b){
                    Tagging.friends=[];
                    $.each(b,function(g,h){
                        Tagging.friends.push({
                            label:h.name,
                            value:h.username,
                            image:h.image,
                            link:"/"+h.username+"/",
                            category:"People"
                        });
                        Tagging.friendsLinks["/"+h.username+"/"]=1
                        });
                    f()
                    })
                })(function(){
                var f=[];
                if(d)for(name in d)Tagging.friendsLinks[name]||!d.hasOwnProperty(name)||f.push(d[name]);f=f.concat(Tagging.friends);
                if(e)f=tagmate.filter_options(f,e);
                c(f)
                })
            },
        initInput:function(a,c,d){
            a=$(a);
            var e=$("<div class='CollabAutocompleteHolder'></div>");
            a.after(e);
            a.autocomplete({
                source:Tagging.getFriends,
                minLength:1,
                delay:5,
                appendTo:e,
                change:function(f,b){
                    c&&c(b.item)
                    },
                select:function(f,b){
                    c&&c(b.item);
                    return false
                    },
                position:{
                    my:"left top",
                    at:"left bottom",
                    offset:"0 -1"
                }
            }).keydown(function(f){
            f.which==13&&d&&d()
            });
       
        },
initTextarea:function(a,c){
    a=scr(a);
    var d={};

    d["@"]=tagmate.USER_TAG_EXPR;
    d["#"]=tagmate.HASH_TAG_EXPR;
    d.$=tagmate.USD_TAG_EXPR;
    d["\u00a3"]=tagmate.GBP_TAG_EXPR;
    a.tagmate({
        tagchars:d,
        sources:{
            "@":function(e,f){
                Tagging.getFriends(e,f,c)
                }
            }
    })
},
loadTags:function(a,c,d,e){
    a=$(a).getTags();
    for(var f=[],b=[],g=null,h=0;h<a.length;h++){
        a[h][0]==
        "@"&&f.push(a[h].substr(1));
        a[h][0]=="#"&&b.push(a[h].substr(1));
        if(a[h][0]=="$"||a[h][0]=="\u00a3")g=a[h]
            }
            $(c).val(f.join(","));
    $(d).val(b.join(","));
    $(e).val(g)
    },
priceTag:function(a,c){

    function d(){
        var e=scr(".price",c);
        if(e.length<=0){
            e=scr("<div class='price'></div>");
            c.prepend(e)
            }
            var f=a.getTags({
            $:tagmate.USD_TAG_EXPR,
            "\u00a3":tagmate.GBP_TAG_EXPR
            });
        if(f&&f.length>0){
            e.text(f[f.length-1]);
            e.addClass("visible");
            }else{
            e.removeClass("visible");
            e.text("")
            }
        }
    a=scr(a);
c=scr(c);
a.unbind(".priceTag").bind("keyup.priceTag",
    d).bind("focus.priceTag",d).bind("change.priceTag",d);
d()
}
}
}();
var ScrapePinDialog=ScrapePinDialog||{
    id:"ScrapePin",
    setup:function(){
        var a=this;
        AddDialog.setup(a.id);
        a.initScraperInput()
        },
    initScraperInput:function(){
        function a(j){
            return/(http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/.test(j)
            }
            function c(j){
            var k=true;
            if(j.indexOf("http")!=0)j="http://"+j;
            if(j=="")k=false;
            if(j=="http://")k=false;
            if(j.length<2)k=false;
            if(j.indexOf(".")==-1)k=false;
            a(j)||(k=false);
            return k
            }
            function d(){
            var j=scr("#"+ScrapePinDialog.id),k=scr("#ScrapePinInput").val();
            
            }
    function e(){
    if(images_count>0){
        images_count=
        -1;
        f()
        }
    }
function f(){
    strHtml="";
    imgFound=false;
    for(var j=foundCtr=0;j<imagesArray.length;j++){
        img=imagesArray[j];
        if(img.width>=150&&img.height>=50){
            imgFound=true;
            foundCtr++;
            strHtml+="<li>"+(is_video(img.src)?"<img src='"+media_url+"images/VideoIndicator.png' alt='Video Icon' class='video' />":"")+"<img src='"+img.src+"' width='156px' alt='' /></li>"
            }
        }
    if(strHtml!=""){
    $("#ScrapePin .ImagePicker .Images ul").html(strHtml);
    b(foundCtr)
    }else alert("No Large Images Found.")
    }
    function b(){
    var j=function(p,
        s){
        im=$(s).find("img")[0];
        if($(im).hasClass("video"))im=$(s).find("img")[1];
        src=$(im).attr("src");
        $("#id_img_url").val(src);
        $("#id_link").val($("#ScrapePinInput").val())
        },k=$("#ScrapePin .ImagePicker .Images").jcarousel({
        buttonNextHTML:null,
        buttonPrevHTML:null,
        initCallback:function(p){
            $("#ScrapePin .imagePickerNext").click(function(){
                p.next();
                return false
                });
            $("#ScrapePin .imagePickerPrevious").click(function(){
                p.prev();
                return false
                })
            },
        animation:"fast",
        itemVisibleInCallback:{
            onAfterAnimation:j
        },
        scroll:1
    });
    j(k,scr("#ScrapePin .ImagePicker").find("li")[0],1,"next")
    }
    function g(){
    var j=scr("#ScrapeButton");
    if(c(scr("#ScrapePinInput").val())){
        j.addClass("disabled");
        d()
        }else{
        alert("Please enter a valid website URL");
        j.removeClass("disabled")
        }
    }
var h="";
scr("#ScrapePinInput").bind("keydown",function(j){
    j.keyCode===13&&g()
    });
scr("#ScrapeButton").click(function(){
    g();
    return false
    })
},
reset:function(){
    var a=$("#"+this.id);
    $("#ScrapePinInput",a).val("");
    $(".PinBottom",a).hide();
    $(".modal",a).css("margin-bottom","0");
    $(".Buttons .Button",
        a).removeClass("disabled");
    $(".Buttons .Button strong",a).html("Pin It");
    ScrapePinDialog.initScraperInput()
    }
};
var UploadPinDialog=UploadPinDialog||{
    id:"UploadPin",
    setup:function(){
        var a=this,c=scr("#"+a.id);
        AddDialog.setup(a.id);
        
    },
reset:function(){
    var a=$("#"+this.id);
    $("input[type=file]",a).val("");
    $(".PinBottom",a).hide();
    $(".modal",a).css("margin-bottom","0");
    $(".Buttons .Button",a).removeClass("disabled");
    $(".Buttons .Button strong",a).html("Pin It")
    }
};
var FancyForm=function(){
    return{
        inputs:".Form input, .Form textarea",
        button:".SubmitButton",
        setup:function(){
            var a=this;
            this.inputs=scr(this.inputs);
            a.inputs.each(function(){
                var c=scr(this);
                a.checkVal(c)
                });
            a.inputs.live("keyup blur",function(){
                var c=scr(this);
                a.checkVal(c);
                var d=c.parents("ul"),e=c.parents(".Form").find(a.button);
                c.parents("li").hasClass("NoCheck")||a.checkDisabled(d,e)
                });
            scr(a.button).live("click",function(){
                var c=scr(this).attr("data-form");
                if(scr(this).hasClass("disabled"))return false;else scr("#"+
                    c+" form").submit()
                    })
            },
        checkVal:function(a){
            a.val().length>0?a.parent("li").addClass("val"):a.parent("li").removeClass("val")
            },
        checkDisabled:function(a,c){
            a.children("li:not(.optional)").length<=a.children("li.val").length?c.removeClass("disabled"):c.addClass("disabled")
            }
        }
}();
(function(){
    jQuery.each({
        getSelection:function(){
            var a=this.jquery?this[0]:this;
            return("selectionStart"in a&&function(){
                var c=a.selectionEnd-a.selectionStart;
                return{
                    start:a.selectionStart,
                    end:a.selectionEnd,
                    length:c,
                    text:a.value.substr(a.selectionStart,c)
                    }
                }||document.selection&&function(){
                a.focus();
                var c=document.selection.createRange();
                if(c==null)return{
                    start:0,
                    end:a.value.length,
                    length:0
                };

                var d=a.createTextRange(),e=d.duplicate();
                d.moveToBookmark(c.getBookmark());
                e.setEndPoint("EndToStart",d);
                var f=
                e.text.length,b=f;
                for(d=0;d<f;d++)e.text.charCodeAt(d)==13&&b--;
                f=e=c.text.length;
                for(d=0;d<e;d++)c.text.charCodeAt(d)==13&&f--;
                return{
                    start:b,
                    end:b+f,
                    length:f,
                    text:c.text
                    }
                }||function(){
            return{
                start:0,
                end:a.value.length,
                length:0
            }
        })()
    },
setSelection:function(a,c){
    var d=this.jquery?this[0]:this,e=a||0,f=c||0;
    return("selectionStart"in d&&function(){
        d.focus();
        d.selectionStart=e;
        d.selectionEnd=f;
        return this
        }||document.selection&&function(){
        d.focus();
        var b=d.createTextRange(),g=e;
        for(i=0;i<g;i++)if(d.value[i].search(/[\r\n]/)!=
            -1)e-=0.5;g=f;
        for(i=0;i<g;i++)if(d.value[i].search(/[\r\n]/)!=-1)f-=0.5;b.moveEnd("textedit",-1);
        b.moveStart("character",e);
        b.moveEnd("character",f-e);
        b.select();
        return this
        }||function(){
        return this
        })()
    },
replaceSelection:function(a){
    var c=this.jquery?this[0]:this,d=a||"";
    return("selectionStart"in c&&function(){
        c.value=c.value.substr(0,c.selectionStart)+d+c.value.substr(c.selectionEnd,c.value.length);
        return this
        }||document.selection&&function(){
        c.focus();
        document.selection.createRange().text=d;
        return this
        }||
    function(){
        c.value+=d;
        return this
        })()
    }
},function(a){
    jQuery.fn[a]=this
    })
})();

var tagmate=tagmate||{
    USER_TAG_EXPR:"@\\w+(?: \\w*)?",
    HASH_TAG_EXPR:"#\\w+",
    USD_TAG_EXPR:"\\$(?:(?:\\d{1,3}(?:\\,\\d{3})+)|(?:\\d+))(?:\\.\\d{2})?",
    GBP_TAG_EXPR:"\\\u00a3(?:(?:\\d{1,3}(?:\\,\\d{3})+)|(?:\\d+))(?:\\.\\d{2})?",
    filter_options:function(a,c){
        for(var d=[],e=0;e<a.length;e++){
            var f=a[e].label.toLowerCase(),b=c.toLowerCase();
            b.length<=f.length&&f.indexOf(b)==0&&d.push(a[e])
            }
            return d
        },
    sort_options:function(a){
        return a.sort(function(c,d){
            c=c.label.toLowerCase();
            d=d.label.toLowerCase();
            if(c>
                d)return 1;
            else if(c<d)return-1;
            return 0
            })
        }
    };
(function(a){
    function c(b,g,h){
        b=b.substring(h||0).search(g);
        return b>=0?b+(h||0):b
        }
        function d(b){
        return b.replace(/[-[\]{}()*+?.,\\^$|#\s]/g,"\\$&")
        }
        function e(b,g,h){
        var j={};

        for(tok in g)if(h&&h[tok]){
            var k={},p={};

            for(key in h[tok]){
                var s=h[tok][key].value,o=h[tok][key].label,l=d(tok+o),q=["(?:^(",")$|^(",")\\W|\\W(",")\\W|\\W(",")$)"].join(l),u=0;
                for(q=new RegExp(q,"gm");(u=c(b.val(),q,u))>-1;){
                    var v=p[u]?p[u]:null;

                    if(!v||k[v].length<o.length)p[u]=s;
                    k[s]=o;
                    u+=o.length+1
                    }
                }
                for(u in p)j[tok+p[u]]=
            tok
            }else{
            k=null;
            for(q=new RegExp("("+g[tok]+")","gm");k=q.exec(b.val());)j[k[1]]=tok
                }
                b=[];
    for(l in j)b.push(l);return b
    }
    var f={
    "@":tagmate.USER_TAG_EXPR,
    "#":tagmate.HASH_TAG_EXPR,
    $:tagmate.USD_TAG_EXPR,
    "\u00a3":tagmate.GBP_TAG_EXPR
    };

a.fn.extend({
    getTags:function(b,g){
        var h=a(this);
        b=b||h.data("_tagmate_tagchars");
        g=g||h.data("_tagmate_sources");
        return e(h,b,g)
        },
    tagmate:function(b){
        function g(o,l,q){
            for(l=new RegExp("["+l+"]");q>=0&&!l.test(o[q]);q--);
            return q
            }
            function h(o){
            var l=o.val(),q=o.getSelection(),
            u=-1;
            o=null;
            for(tok in s.tagchars){
                var v=g(l,tok,q.start);
                if(v>u){
                    u=v;
                    o=tok
                    }
                }
            l=l.substring(u+1,q.start);
        if((new RegExp("^"+s.tagchars[o])).exec(o+l))return o+l;
        return null
        }
        function j(o,l,q){
        var u=o.val(),v=o.getSelection();
        v=g(u,l[0],v.start);
        var z=u.substr(0,v);
        u=u.substr(v+l.length);
        o.val(z+l[0]+q+u);
        u=v+q.length+1;
        o.setSelection(u,u);
        s.replace_tag&&s.replace_tag(l,q)
        }
        function k(o,l){
        l=tagmate.sort_options(l);
        for(var q=0;q<l.length;q++){
            var u=l[q].label,v=l[q].image;
            q==0&&o.html("");
            var z="<span>"+
            u+"</span>";
            if(v)z="<img src='"+v+"' alt='"+u+"'/>"+z;
            u=s.menu_option_class;
            if(q==0)u+=" "+s.menu_option_active_class;
            o.append("<div class='"+u+"'>"+z+"</div>")
            }
        }
        function p(o,l){
    var q=l=="down"?":first-child":":last-child",u=l=="down"?"next":"prev";
    l=o.children("."+s.menu_option_active_class);
    if(l.length==0)l=o.children(q);
    else{
        l.removeClass(s.menu_option_active_class);
        l=l[u]().length>0?l[u]():l
        }
        l.addClass(s.menu_option_active_class);
    u=o.children();
    var v=Math.floor(a(o).height()/a(u[0]).height())-
    1;
    if(a(o).height()%a(u[0]).height()>0)v-=1;
    for(q=0;q<u.length&&a(u[q]).html()!=a(l).html();q++);
    q>v&&q-v>=0&&q-v<u.length&&o.scrollTo(u[q-v])
    }
    var s={
    tagchars:f,
    sources:null,
    capture_tag:null,
    replace_tag:null,
    menu:null,
    menu_class:"tagmate-menu",
    menu_option_class:"tagmate-menu-option",
    menu_option_active_class:"tagmate-menu-option-active"
};

return this.each(function(){
    function o(){
        v.hide();
        var B=h(l);
        if(B){
            var F=B[0],n=B.substr(1),m=l.getSelection(),y=g(l.val(),F,m.start);
            m.start-y<=B.length&&function(A){
                if(typeof s.sources[F]===
                    "object")A(tagmate.filter_options(s.sources[F],n));else typeof s.sources[F]==="function"?s.sources[F]({
                    term:n
                },A):A()
                    }(function(A){
                if(A&&A.length>0){
                    k(v,A);
                    v.css("top",l.outerHeight()-1+"px");
                    v.show();
                    for(var D=l.data("_tagmate_sources"),E=0;E<A.length;E++){
                        for(var K=false,L=0;!K&&L<D[F].length;L++)K=D[F][L].value==A[E].value;
                        K||D[F].push(A[E])
                        }
                    }
                    B&&s.capture_tag&&s.capture_tag(B)
                })
        }
    }
b&&a.extend(s,b);
    var l=a(this);
    l.data("_tagmate_tagchars",s.tagchars);
    var q={};

    for(var u in s.sources)q[u]=[];l.data("_tagmate_sources",
    q);
var v=s.menu;
if(!v){
    v=a("<div class='"+s.menu_class+"'></div>");
    l.after(v)
    }
    l.offset();
    v.css("position","absolute");
    v.hide();
    var z=false;
    a(l).unbind(".tagmate").bind("focus.tagmate",function(){
    o()
    }).bind("blur.tagmate",function(){
    setTimeout(function(){
        v.hide()
        },300)
    }).bind("click.tagmate",function(){
    o()
    }).bind("keydown.tagmate",function(B){
    if(v.is(":visible"))if(B.keyCode==40){
        p(v,"down");
        z=true;
        return false
        }else if(B.keyCode==38){
        p(v,"up");
        z=true;
        return false
        }else if(B.keyCode==13){
        B=v.children("."+
            s.menu_option_active_class).text();
        var F=h(l);
        if(F&&B){
            j(l,F,B);
            v.hide();
            z=true;
            return false
            }
        }else if(B.keyCode==27){
        v.hide();
        z=true;
        return false
        }
    }).bind("keyup.tagmate",function(){
    if(z){
        z=false;
        return true
        }
        o()
    });
a("."+s.menu_class+" ."+s.menu_option_class).die("click.tagmate").live("click.tagmate",function(){
    var B=a(this).text(),F=h(l);
    j(l,F,B);
    v.hide();
    z=true;
    return false
    })
})
}
})
})(jQuery);

