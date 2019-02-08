function getXhr(){
    try{
        req=new XMLHttpRequest
        }catch(a){
        try{
            req=new ActiveXObject("Msxml2.XMLHTTP")
            }catch(c){
            try{
                req=new ActiveXObject("Microsoft.XMLHTTP")
                }catch(b){
                req=!1
                }
            }
    }
return req
}
function getcurrentboard(){
    document.getElementById("CreateBoard").style.display="block"
    }
function getpin(a,c,b){
    if("0"==b)return window.open(c+"?option=com_socialpinboard&view=people","_self"),!1;
    document.getElementById("Header").style.zIndex=9999;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var b=a.split("*&@#@%asdfbk",7),c=b[0],h=b[1],j=b[2],l=b[3],k=b[4],m=b[5];
                document.getElementById("DescriptionTextarea").value=m;
                ""!=k&&(document.getElementById("pinImage")?document.getElementById("pinImage").src=k:document.getElementById("imagepin").innerHTML=
                    '<img src="'+k+'" name="pinImage" id="pinImage">');
                document.getElementById("pin_type_id").value=c;
                document.getElementById("pin_repin_id").value=h;
                document.getElementById("pin_url").value=l;
                document.getElementById("pin_real_pin_id").value=j;
                Modal.show("Repin")
                }
            }catch(n){
            alert(n.message)
            }
        };

d.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getpininfo&pin_id="+a,!0);
d.send(null);
return!0
}
function getcomment(a,c,b,d){
    var e=document.getElementById("GridComment"+a).value,g=getXhr();
    g.onreadystatechange=function(){
        if(4==g.readyState)try{
            if(""!=g.responseText){
                var c=document.getElementById("comments_new"+a),h=document.createElement("div");
                h.setAttribute("id","comments_new"+a);
                content='<a href="'+d+'" class="ImgLink"><img src="'+b+'" width="50" height="50"/></a>';
                content+="<p>"+e+"</p>";
                h.innerHTML=content;
                c.appendChild(h)
                }
            }catch(j){
            alert(j.message)
            }
        };

g.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcommentinfo&pin_id="+
    a+"&user_id="+c+"&comment="+e,!0);
g.send(null)
}
function addcommentElement(a,c,b,d){
    var e=document.getElementById("comment"+a),g=document.createElement("div"),f=a+"_0_comment";
    g.setAttribute("id",f);
    ""==document.getElementById("comment_hidden_"+a).value?(content='<a href="'+c+'" class="ImgLink"><img src="'+b+'" ></a>',content+='<textarea class="GridComment" id="GridComment'+a+"\" onclick=\"javascript:if(this.value=='Add a comment...'){ this.value=''}\"></textarea>",content+='<a href="javascript:void(0);" class="Button WhiteButton Button11 grid_comment_button" onclick="getcomment('+a+
        ","+d+",'"+b+"','"+c+'\')" style="visibility: visible; display: inline; "><strong>Comment</strong><span></span></a>',g.innerHTML=content,e.appendChild(g),document.getElementById("comment_hidden_"+a).value=1):1==document.getElementById("comment_hidden_"+a).value?(document.getElementById(f).style.display="none",document.getElementById("comment_hidden_"+a).value=2):(document.getElementById(f).style.display="block",document.getElementById("comment_hidden_"+a).value=1)
    }
function addnewboard(a){
    var c=document.getElementById("boardtxt").value;
    if(""==c)return document.getElementById("descriptionerror").innerHTML='<label id="login_error_msg" style="display: block;clear: both;">'+board_name_exist+'</label>',!1;
    document.getElementById("descriptionerror").innerHTML="";
    var b=getXhr();
    b.onreadystatechange=function(){
        if(4==b.readyState)try{
            var a=b.responseText;
            if(""!=a)ddMenu("one",-1),document.getElementById("one-ddheader").innerHTML=c,document.getElementById("board_selection").value=
                a;else return ddMenu("one",-1),document.getElementById("descriptionerror").innerHTML='<label style="color:red">'+board_name_exist+'</label>',!1
                }catch(d){
            alert(d.message)
            }
        };

var d=1<c.indexOf("&")?c.replace("&","_"):c,a="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addnewboard&user_id="+a+"&board_name="+d;
b.open("GET",a,!0);
b.send(null)
}
function addnewmenuboard(a){
    var c=document.getElementById("uploadboardtxt").value,b=document.getElementById("upload_board");
    if(""==c||"Enter New Board Name"==c||"Please Enter Board Name"==c||enter_board_name==c)return document.getElementById("boardError").innerHTML='<label id="login_error_msg" >'+enter_board_name+'</label>',!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var f=document.createElement("OPTION");
                f.text=c;
                f.value=a;
                b.selectedIndex=f.value;
                b.options.add(f);
                f.attributes.selected=
                "selected";
                document.getElementById("uploadboardtxt").value="";
                document.getElementById("upload_board").value=f.value;
                scr(".customStyleSelectBoxInner").text(f.text)
                return document.getElementById("boardError").innerHTML='',!1;
                }else return document.getElementById("boardError").innerHTML='<label id="login_error_msg" style="color:red;">'+board_name_exist+'</label>',!1
                }catch(e){}
        };

var e=1<c.indexOf("&")?c.replace("&","_"):c,a="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addnewboard&user_id="+a+"&board_name="+e;
d.open("GET",a,!0);
d.send(null)
}
function addrepinmenuboard(a){
    var c=document.getElementById("boardtxt").value,b=document.getElementById("repin_board");
    if(""==c)return document.getElementById("descriptionerror").innerHTML='<label style="color:red">'+enter_board_name+' </label>',!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var f=document.createElement("OPTION");
                f.text=c;
                f.value=a;
                b.selectedIndex=f.value;
                b.options.add(f);
                f.attributes.selected="selected";
                document.getElementById("boardtxt").value=
                "";
                document.getElementById("repin_board").value=f.value;
                scr(".customStyleSelectBoxInner").text(f.text)
                return document.getElementById("descriptionerror").innerHTML='',!1;
                }else return document.getElementById("descriptionerror").innerHTML='<label style="color:red">'+board_name_exist+'</label>',!1
                }catch(e){
            alert(e.message)
            }
        };

var e=1<c.indexOf("&")?c.replace("&","_"):c,a="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addnewboard&user_id="+a+"&board_name="+e;
d.open("GET",a,!0);
d.send(null)
}
function addnewmenupin(a){
    var c=document.getElementById("uploadboardtxted").value,b=document.getElementById("pin_board");
    if(""==c||"Enter New Board Name"==c || enter_board_name==c)return document.getElementById("boardErrorpin").innerHTML='<div id="login_error_msg" style="margin: 10px 0 0 -22px;display: block;clear: both;padding: 8px 0 0 0;">'+enter_board_name+'</div>',!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var f=document.createElement("OPTION");
                f.text=
                c;
                f.value=a;
                b.selectedIndex=f.value;
                b.options.add(f);
                f.attributes.selected="selected";
                document.getElementById("uploadboardtxted").value="";
                document.getElementById("pin_board").value=f.value;
                scr(".customStyleSelectBoxInner").text(f.text)
                return document.getElementById("boardErrorpin").innerHTML='',!1;
                }else return document.getElementById("boardErrorpin").innerHTML='<div id="login_error_msg"  style="margin: 10px 0 0 -109px;display: block;clear: both;padding: 8px 0 0 0;">'+board_name_exist+'</label>',!1
                }catch(e){
            alert(e.message)
            }
        };

var e=1<c.indexOf("&")?c.replace("&",
    "_"):c,a="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addnewboard&user_id="+a+"&board_name="+e;
d.open("GET",a,!0);
d.send(null)
}
function getXhr(){
    try{
        req=new XMLHttpRequest
        }catch(a){
        try{
            req=new ActiveXObject("Msxml2.XMLHTTP")
            }catch(c){
            try{
                req=new ActiveXObject("Microsoft.XMLHTTP")
                }catch(b){
                req=!1
                }
            }
    }
return req
}
function ajxGetBoards(a){
    var c=getXhr(),b=document.getElementById("repin_board").value;
    if(""==b)return document.getElementById("descriptionerror").innerHTML='<label style="color:red">Please Select A Board </label>',!1;
    var d=document.getElementById("DescriptionTextarea").value;
    document.getElementById("boardLink").href=a+"?option=com_socialpinboard&view=boardpage&bId="+b;
    if(""==d)return document.getElementById("descriptionerror").innerHTML='<label style="color:red">Please enter description</label>',
        !1;
    var e=document.getElementById("pin_repin_id").value,a=document.getElementById("pin_real_pin_id").value,g=document.getElementById("pin_user_id").value;
    0!=g&&(c.onreadystatechange=function(){
        if(4==c.readyState)try{
            if(c.responseText){
                var a,b=0;
                isNaN(scr("#repincountspan"+e).text())?(b=parseInt(scr("#repincountspan"+e).text().substring(0,scr("#repincountspan"+e).text().indexOf(" ")))+1,a=b+" Repins "):a="1 Repin ";
                scr("#repincountspan"+e).text(a);
                RepinDialog.append()
                }
            }catch(d){
            alert(d.message)
            }
        },c.open("GET",
    "?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getrepin&board_id="+b+"&description="+d+"&repin_id="+e+"&real_pin_id="+a+"&pin_user_id="+g,!0),c.send(null))
}
function getcurrentboard(){
    document.getElementById("CreateBoard").style.display="block"
    }
function getlike(a,c,b){
    var d=getXhr();
    if(0==c)return d=getXhr(),window.open("?option=com_socialpinboard&view=people","_self"),!1;
    d.onreadystatechange=function(){
        0==b?(document.getElementById("like"+a).style.display="none",document.getElementById("unlike"+a).style.display="block"):1==b&&(document.getElementById("unlike"+a).style.display="none",document.getElementById("like"+a).style.display="block");
        if(4==d.readyState)try{
            var c=d.responseText;
            document.getElementById("likescountspan"+a).innerHTML=
            c
            }catch(g){
            alert(g.message)
            }
        };

d.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getlikeinfo&pin_id="+a+"&user_id="+c+"&pin_flag="+b,!0);
d.send(null);
return!0
}
function getcomment(a,c,b,d){
    var e=document.getElementById("GridComment"+a).value,g=getXhr();
    g.onreadystatechange=function(){
        if(4==g.readyState)try{
            if(""!=g.responseText){
                var c=document.getElementById("comments_new"+a),h=document.createElement("div");
                h.setAttribute("id","comments_new"+a);
                content='<a href="'+d+'" class="ImgLink"><img src="'+b+'" width="50" height="50"/></a>';
                content+="<p>"+e+"</p>";
                h.innerHTML=content;
                c.appendChild(h)
                }
            }catch(j){
            alert(j.message)
            }
        };

g.open("GET","?option=com_socialpinboard&tmpl=component&task=getcommentinfo&pin_id="+
    a+"&user_id="+c+"&comment="+e,!0);
g.send(null)
}
function addcommentElement(a,c,b,d){
    var e=document.getElementById("comment"+a),g=document.createElement("div"),f=a+"_0_comment";
    g.setAttribute("id",f);
    ""==document.getElementById("comment_hidden_"+a).value?(content='<a href="'+c+'" class="ImgLink"><img src="'+b+'" ></a>',content+='<textarea class="GridComment" id="GridComment'+a+"\" onclick=\"javascript:if(this.value=='Add a comment...'){ this.value=''}\"></textarea>",content+='<a href="javascript:void(0);" class="Button WhiteButton Button11 grid_comment_button" onclick="getcomment('+a+
        ","+d+",'"+b+"','"+c+'\')" style="visibility: visible; display: inline; "><strong>Comment</strong><span></span></a>',g.innerHTML=content,e.appendChild(g),document.getElementById("comment_hidden_"+a).value=1):1==document.getElementById("comment_hidden_"+a).value?(document.getElementById(f).style.display="none",document.getElementById("comment_hidden_"+a).value=2):(document.getElementById(f).style.display="block",document.getElementById("comment_hidden_"+a).value=1)
    }
function followall(a,c,b){
    document.getElementById("follow").innerHTML='<input type="button" name="unfollow" value="'+un_follow_lang+'" id="unfollow" class="unfollow_btn" onclick="unfollowall('+a+","+c+",'"+b+"')\" />";
    var bid=b.split(",");
    var d=b.length;
        b=getXhr();
    b.onreadystatechange=
    function(){};

    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=followall&user_id="+a+"&fuser_id="+c,!0);
    b.send(null);
    for(var i=0;i<d;i++){
        var e='<input type="button" name="unfollowboard" value="'+un_follow_board_lang+'" class="unfollowboard" id="unfollowboard'+bid[i]+'"  onclick="unfollows('+a+","+c+","+bid[i]+')" />';
        document.getElementById("newfollowboard"+bid[i]).innerHTML=e
        }
    return!0
    }
function unfollowall(a,c,b){

    document.getElementById("follow").innerHTML='<input type="button" name="followall" value="'+follow_all_lang+'" class="follow" id="follow" onclick="followall('+a+","+c+",'"+b+"')\"/>";
    var bid=b.split(","),d=b.length;

        b=getXhr();
    b.onreadystatechange=
    function(){};

    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=unfollowall&user_id="+a+"&fuser_id="+c,!0);
    b.send(null);
    for(var i=0;i<d;i++){
        var e='<input type="button" name="followboard" value="'+follow_board_lang+'" class="followboard followboard-height" onclick="followboard('+a+","+c+","+bid[i]+')"  id="followboard'+bid[i]+'"/>';
        document.getElementById("newfollowboard"+bid[i]).innerHTML=e
        }
    return!0
    }
function followboard(a,c,b){
    document.getElementById("newfollowboard"+b).innerHTML='<input type="button" name="unfollowboard" value="'+un_follow_board_lang+'" class="unfollowboard" id="unfollowboard'+b+'"   onclick="unfollows('+a+","+c+","+b+')" />';
    var d=getXhr();
    d.onreadystatechange=function(){};

    d.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=followboard&user_id="+a+"&fuser_id="+c+"&boardid="+b,!0);
    d.send(null);
    return!0
    }
function unfollows(a,c,b){
    unfollowboard='<input type="button" name="followboard" value="'+follow_board_lang+'" class="followboard followboard-height" id="followboard'+b+'"  onclick="followboard('+a+","+c+","+b+')" />';
    document.getElementById("newfollowboard"+b).innerHTML=unfollowboard;
    var d=getXhr();
    d.onreadystatechange=function(){};

    d.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=unfollowboard&user_id="+a+"&fuser_id="+c+"&boardid="+b,!0);
    d.send(null)
    }
function getCategory(a,c){
    document.getElementById("categoryimage"+a).style.display="none";
    document.getElementById("tickcategory"+a).style.display="block";
    var categorycount = document.getElementById("categorycount").value;
    var x=1;
    x=x+Number(categorycount);
    var b=getXhr();
    b.onreadystatechange=function(){};
document.getElementById("categorycount").value=x;
    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getcategoryimage&cid="+a+"&userId="+c,!0);
    b.send(null)
    }
function getUncategory(a,c){
    document.getElementById("categoryimage"+a).style.display="block";
    document.getElementById("tickcategory"+a).style.display="none";
    var categorycount = Number(document.getElementById("categorycount").value);
    var b=getXhr();
    b.onreadystatechange=function(){};
document.getElementById("categorycount").value=categorycount-1;
    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getuncategoryimage&cid="+a+"&userId="+c,!0);
    b.send(null)
    }
function followusers(a,c){
    document.getElementById("category_user_follow"+c).innerHTML='<input type="button" name="unfollow" class="unfollow"  value="'+un_follow_lang+'" id="unfollow" onclick="unfollowusers('+a+","+c+')" />';
    var b=getXhr();
    b.onreadystatechange=function(){};
var categorycount = Number(document.getElementById("followerscount").value);
document.getElementById("followerscount").value=categorycount+1;
    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=followall&user_id="+a+"&fuser_id="+c,!0);
    b.send(null);
    return!0
    }
function unfollowusers(a,c){
    unfollowboard='<input type="button" name="followuser" class="follow"  value="'+follow_user_lang+'" id="followuser"  onclick="followusers('+a+","+c+')" />';
    document.getElementById("category_user_follow"+c).innerHTML=unfollowboard;
    var b=getXhr();
    b.onreadystatechange=function(){};
var categorycount = Number(document.getElementById("followerscount").value);
document.getElementById("followerscount").value=categorycount-1;
    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=unfollowall&user_id="+a+"&fuser_id="+c,!0);
    b.send(null)
    }
function userContributers(){
    var a=document.getElementById("contributers_name_addboard").value;
    if("Name or Email Address"==a||""==a)return alert("Please enter the name"),!1;
    var c=getXhr();
    c.onreadystatechange=function(){
        if(4==c.readyState)try{
            var b=c.responseText;
            if(""==b)return alert("Looks like something went wrong! We're looking into it. Try again"),!1;
            var e=document.getElementById("id_contributers").value;
            if(""!=e){
                var g=e.split(",");
                for(i=0;i<g.length;i++)if(b==g[i])return!1;document.getElementById("id_contributers").value=
                e+","+b
                }else document.getElementById("id_contributers").value=b;
            document.getElementById("contributer_name").style.display="block";
            var f=document.getElementById("contributer_name"),h=document.createElement("div");
            h.setAttribute("id","contributer_addboard"+b);
            content=a+'  <span class="add_contr_success">added successfully</span>';
            h.innerHTML=content;
            f.appendChild(h)
            }catch(j){
            alert(j.message)
            }
        };

var b="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addcontributers&name_contributers="+
a;
document.getElementById("menuSearchVal").innerHTML="";
c.open("GET",b,!0);
c.send(null)
}
function mobuserContributers(){
    var a=document.getElementById("mobcontributers_name_addboard").value;
    if("Name or Email Address"==a||""==a)return alert("Please enter the name"),!1;
    var c=getXhr();
    c.onreadystatechange=function(){
        if(4==c.readyState)try{
            var b=c.responseText;
            if(""==b)return alert("Looks like something went wrong! We're looking into it. Try again"),!1;
            var e=document.getElementById("mobid_contributers").value;
            if(""!=e){
                var g=e.split(",");
                for(i=0;i<g.length;i++)if(b==g[i])return!1;document.getElementById("mobid_contributers").value=
                e+","+b
            }else document.getElementById("mobid_contributers").value=b;
            document.getElementById("mobcontributer_name").style.display="block";
            var f=document.getElementById("mobcontributer_name"),h=document.createElement("div");
            h.setAttribute("id","contributer_addboard"+b);
            content=a+'  <span class="add_contr_success">added successfully</span>';
            h.innerHTML=content;
            f.appendChild(h)
        }catch(j){
            alert(j.message)
        }
    };

    var b="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addcontributers&name_contributers="+
    a;
    document.getElementById("menuSearchVal").innerHTML="";
    c.open("GET",b,!0);
    c.send(null)
}


function mobaddnewmenuboard(a){
    var c=document.getElementById("mobuploadboardtxt").value,b=document.getElementById("mobupload_board");
    if(""==c||"Enter New Board Name"==c || enter_board_name==c)return document.getElementById("mobboardError").innerHTML='<label id="login_error_msg" style="margin: 10px 0 0 -144px;">'+enter_board_name+'</label>',!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var f=document.createElement("OPTION");
                f.text=c;
                f.value=a;
                b.selectedIndex=f.value;
                b.options.add(f);
                f.attributes.selected=
                "selected";
                document.getElementById("mobuploadboardtxt").value="";
                document.getElementById("mobupload_board").value=f.value;
                scr(".customStyleSelectBoxInner").text(f.text)
            }else return document.getElementById("mobboardError").innerHTML='<label id="login_error_msg" style="color:red;margin-left: -167px;">'+board_name_exist+'</label>',!1
        }catch(e){}
    };

    var e=1<c.indexOf("&")?c.replace("&","_"):c,a="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addnewboard&user_id="+a+"&board_name="+escape(e);
    d.open("GET",a,!0);
    d.send(null)
}


function mobaddnewmenupin(a){
    var c=document.getElementById("mobuploadboardtxted").value,b=document.getElementById("mobpin_board");
    if(""==c||"Enter New Board Name"==c||enter_board_name==c)return document.getElementById("mobboardErrorpin").innerHTML='<div id="login_error_msg" style="margin: 10px 0 0 -89px;display: block;clear: both;padding: 8px 0 0 0;">'+enter_board_name+'</div>',!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var f=document.createElement("OPTION");
                f.text=
                c;
                f.value=a;
                b.selectedIndex=f.value;
                b.options.add(f);
                f.attributes.selected="selected";
                document.getElementById("mobuploadboardtxted").value="";
                document.getElementById("mobpin_board").value=f.value;
                scr(".customStyleSelectBoxInner").text(f.text)
            }else return document.getElementById("mobboardErrorpin").innerHTML='<div id="login_error_msg"  style="margin: 10px 0 0 -109px;display: block;clear: both;padding: 8px 0 0 0;">'+board_name_exist+'</label>',!1
        }catch(e){
            alert(e.message)
        }
    };

    var e=1<c.indexOf("&")?c.replace("&",
        "_"):c,a="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addnewboard&user_id="+a+"&board_name="+escape(e);
    d.open("GET",a,!0);
    d.send(null)
}
function mobgetpin(a,c,b){
    if("0"==b)return window.open(c+"?option=com_socialpinboard&view=people","_self"),!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var b=a.split("*&@#@%asdfbk",7),c=b[0],h=b[1],j=b[2],l=b[3],k=b[4],m=b[5];
                document.getElementById('mobrepin').style.display = 'block';
                document.getElementById('container').style.display = 'none';
                document.getElementById("mobDescriptionTextarea").value=m;
                ""!=k&&(document.getElementById("mobpinImage")?document.getElementById("mobpinImage").src=k:document.getElementById("mobimagepin").innerHTML=
                    '<img src="'+k+'" name="pinImage" id="mobpinImage">');
                document.getElementById("mobpin_type_id").value=c;
                document.getElementById("mobpin_repin_id").value=h;
                document.getElementById("mobpin_url").value=l;
                document.getElementById("mobpin_real_pin_id").value=j;
            }
        }catch(n){
            alert(n.message)
        }
    };

    d.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getpininfo&pin_id="+a,!0);
    d.send(null);
    return!0
}
function mobaddrepinmenuboard(a){
    var c=document.getElementById("mobboardtxt").value,b=document.getElementById("mobrepin_board");
    if(""==c)return document.getElementById("mobdescriptionerror").innerHTML='<label style="color:red">'+enter_board_name+' </label>',!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var f=document.createElement("OPTION");
                f.text=c;
                f.value=a;
                b.selectedIndex=f.value;
                b.options.add(f);
                f.attributes.selected="selected";
                document.getElementById("mobboardtxt").value="";
                document.getElementById("mobrepin_board").value=f.value;
                scr(".customStyleSelectBoxInner").text(f.text)
            }else return document.getElementById("mobdescriptionerror").innerHTML='<label style="color:red">'+board_name_exist+'</label>',!1
        }catch(e){
            alert(e.message)
        }
    };

    var e=1<c.indexOf("&")?c.replace("&","_"):c,a="?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=addnewboard&user_id="+a+"&board_name="+escape(e);
    d.open("GET",a,!0);
    d.send(null)
}
function repinCancel()
{
    document.getElementById('mobrepin').style.display = 'none';
    document.getElementById('container').style.display = 'block';
    return;
}
function mobajxGetBoards(baseurl)
{
    var xhr = getXhr();
    var board=document.getElementById('mobrepin_board').value;
    if(board=='')
    {
        document.getElementById('mobdescriptionerror').innerHTML='<label style="color:red">Please Select A Board </label>';
        return false;
    }
    var descriptions = document.getElementById('mobDescriptionTextarea').value;
    if(descriptions=='')
    {
        document.getElementById('mobdescriptionerror').innerHTML='<label style="color:red">Please enter description</label>';
        return false;
    }
    var pin_repin_id = document.getElementById('mobpin_repin_id').value;
    var pin_real_pin_id=document.getElementById('mobpin_real_pin_id').value;
    var pin_user_id=document.getElementById('mobpin_user_id').value;
    if (pin_user_id!=0)
    {
        document.getElementById('mobPostwait').style.display = "block";
        xhr.onreadystatechange = function(){
            if(xhr.readyState == 4){
                try
                {
                    var options = xhr.responseText;
                    if(options)
                    {
                        var span;
                        var count = 0

                        if(isNaN(scr("#repincountspan"+pin_repin_id).text())){

                            count =parseInt(scr("#repincountspan"+pin_repin_id).text().substring(0,scr("#repincountspan"+pin_repin_id).text().indexOf(" ")))+1;
                            span = count+" Repins ";
                        }else{
                            span= "1 Repin ";
                        }
                        scr("#repincountspan"+pin_repin_id).text(span);

                    }
                    document.getElementById('mobPostwait').style.display = "none";
                    document.getElementById('mobPostsuccess').style.display = "block";
                    window.open("?option=com_socialpinboard&view=home","_self");
                }
                catch(e) {
                    alert(e.message)
                }
            }
        }
        var url = baseurl+'index.php?board_id='+board+'&description='+descriptions+'&repin_id='+pin_repin_id+'&real_pin_id='+pin_real_pin_id+'&pin_user_id='+pin_user_id;
        xhr.open("GET",url,true);
        xhr.send(null);
    }
}
function mobpingetpin(a,c,b){
    if("0"==b)return window.open(c+"?option=com_socialpinboard&view=people","_self"),!1;
    var d=getXhr();
    d.onreadystatechange=function(){
        if(4==d.readyState)try{
            var a=d.responseText;
            if(""!=a){
                var b=a.split("*&@#@%asdfbk",7),c=b[0],h=b[1],j=b[2],l=b[3],k=b[4],m=b[5];
                document.getElementById('mobrepin').style.display = 'block';
                document.getElementById('boarddiv').style.display = 'none';
                document.getElementById('sideboard').style.display = 'none';
                document.getElementById('pindiv').style.display = 'none';
                document.getElementById('SocialShare').style.display = 'none';
                document.getElementById("mobDescriptionTextarea").value=m;
                ""!=k&&(document.getElementById("mobpinImage")?document.getElementById("mobpinImage").src=k:document.getElementById("mobimagepin").innerHTML=
                    '<img src="'+k+'" name="pinImage" id="mobpinImage">');
                document.getElementById("mobpin_type_id").value=c;
                document.getElementById("mobpin_repin_id").value=h;
                document.getElementById("mobpin_url").value=l;
                document.getElementById("mobpin_real_pin_id").value=j;
            }
        }catch(n){
            alert(n.message)
        }
    };

    d.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=getpininfo&pin_id="+a,!0);
    d.send(null);
    return!0
}
function block(a,c){
    unfollowboard='<input type="button" name="followuser"  style="float:right;cursor: pointer;"  value="Unblock"  class="Button Button13 RedButton clickable blockuserbutton" onclick="unblock('+a+","+c+');" />';
    document.getElementById("category_user_follow"+a).innerHTML=unfollowboard;
    document.getElementById("ReportLabelblock").innerHTML="You Blocked this user";
    document.getElementById('userblock').innerHTML = 'Unblock';
    document.getElementById('blockUnblockUsermessagefirst').innerHTML = 'If you unblock'
    document.getElementById('blockUnblockUsermessagesecond').innerHTML ='you will be able to Follow each other and interact with each others pins.';
    var b=getXhr();
    b.onreadystatechange=function(){
         if(4==b.readyState)try{
             location.reload(true)
           }catch(d){
            alert(d.message)
        }
    };
    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=block&user_id="+a+"&fuser_id="+c,!0);
    b.send(null);
     return!0
}
function unblock(a,c){
    unfollowboard='<input type="button" name="followuser"  style="float:right;cursor: pointer;"  value="Block"  class="Button Button13 RedButton clickable blockuserbutton" onclick="block('+a+","+c+');" />';
    document.getElementById("category_user_follow"+a).innerHTML=unfollowboard;
    document.getElementById('userblock').innerHTML = 'Block';
    document.getElementById('blockUnblockUsermessagefirst').innerHTML = 'If you block';
    document.getElementById('blockUnblockUsermessagesecond').innerHTML = 'you wont be able to Follow each other, or interact with each others pins.';
    var b=getXhr();
    b.onreadystatechange=function(){
          if(4==b.readyState)try{
              location.reload(true)
             }catch(d){
            alert(d.message)
        }
    };
    b.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=unblock&user_id="+a+"&fuser_id="+c,!0);
    b.send(null)
}
function remove_contributers_addboard(a){
    xmlhttp=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function(){
        if(4==xmlhttp.readyState&&200==xmlhttp.status)try{
            document.getElementById("contributer_addboard"+a).style.display="none"
            }catch(c){
            alert(c.message)
            }
        };

document.getElementById("contributer_addboard"+a).style.display="none";
xmlhttp.open("GET","?option=com_socialpinboard&view=ajaxcontrol&tmpl=component&task=removeContributers&user_id="+a+
    "&bidd=",!0);
xmlhttp.send(null);
return!0
};