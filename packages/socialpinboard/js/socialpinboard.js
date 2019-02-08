var scr = jQuery.noConflict();
jQuery(document).ready(function($){
				$(function () {
			var scrollDiv = document.createElement('div');
			scr(scrollDiv).attr('id', 'toTop').html(scroll_to_top).appendTo('body');
			scr(window).scroll(function () {
			        if ($(this).scrollTop() != 0) {
			            scr('#toTop').fadeIn();
			        } else {
			           scr('#toTop').fadeOut();
			        }
			    });
			    scr('#toTop').click(function () {
			        scr('body,html').animate({
			            scrollTop: 0
			        },
			        800);
			    });
			});

			});

var scr = jQuery.noConflict();
               scr(document).ready(function($){
                scr.fn.extend({

 	customStyle : function(options) {
	  if(!scr.browser.msie || (scr.browser.msie&&scr.browser.version>6)){
	  return this.each(function() {

			var currentSelected = scr(this).find(':selected');
                        
//			scr(this).after('<span class="customStyleSelectBox"><span class="customStyleSelectBoxInner">'+currentSelected.text()+'</span></span>').css({position:'absolute', opacity:0,fontSize:scr(this).next().css('font-size')});
                            var selectBoxSpan = scr(this).next();
                            var selectBoxWidth = parseInt(scr(this).width()) - parseInt(selectBoxSpan.css('padding-left')) -parseInt(selectBoxSpan.css('padding-right'));
                            var selectBoxSpanInner = selectBoxSpan.find(':first-child');
//                            selectBoxSpan.css({display:'inline-block'});
//                            selectBoxSpanInner.css({display:'inline-block'});
                            var selectBoxHeight = parseInt() + parseInt(selectBoxSpan.css('padding-top')) + parseInt(selectBoxSpan.css('padding-bottom'));
                           scr(this).height(selectBoxHeight).change(function(){
                               
			    selectBoxSpanInner.text(scr(this).find(':selected').text()).parent().addClass('changed');
                            });

                        });
                    }
                }
            });
        });


        scr(function(){

            scr('select').customStyle();

        });

var winW;
    window.onload = function() {  
        if(document.getElementById('Nag'))
            {
        if (document.body && document.body.offsetWidth) {
         winW = document.body.offsetWidth;         
        }
        if (document.compatMode=='CSS1Compat' &&
            document.documentElement &&
            document.documentElement.offsetWidth ) {
         winW = document.documentElement.offsetWidth;         
        }
        if (window.innerWidth && window.innerHeight) {
         winW = window.innerWidth;         
        }
        /*document.writeln('Window width = '+winW);*/
        document.getElementById('Nag').style.left= winW/2-321+"px";
            }
    }
    window.onresize = function() {
        if(document.getElementById('Nag'))
            {
        if (document.body && document.body.offsetWidth) {
         winW = document.body.offsetWidth;
        }
        if (document.compatMode=='CSS1Compat' &&
            document.documentElement &&
            document.documentElement.offsetWidth ) {
         winW = document.documentElement.offsetWidth;
        }
        if (window.innerWidth && window.innerHeight) {
         winW = window.innerWidth;
        }
        document.getElementById('Nag').style.left= winW/2-321+"px";
            }
    }

