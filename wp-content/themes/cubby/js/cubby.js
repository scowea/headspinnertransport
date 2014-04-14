(function ($) {
            $.fn.extend({
                Scroll: function (opt, callback) {
                    if (!opt) var opt = {};
                    var _btnUp = $("#" + opt.up);  
                    var _btnDown = $("#" + opt.down);
                    var _this = this.eq(0).find("ul:first");
                    var lineH = _this.find("li:first").height();     
                    var line = opt.line ? parseInt(opt.line, 10) : parseInt(this.height() / lineH, 10); 
                    var speed = opt.speed ? parseInt(opt.speed, 10) : 600; 
                    var m = line;  
                    var count = _this.find("li").length; 
                    var upHeight = line * lineH;
                    function scrollUp() {
                        if (!_this.is(":animated")) {  
                            if (m < count) {  
                                m += line;

                                _this.animate({ marginTop: "-=" + upHeight + "px" }, speed);
                            }
                        }
                    }
                    function scrollDown() {
                        if (!_this.is(":animated")) {
                            if (m > line) { 
                                m -= line;
                                _this.animate({ marginTop: "+=" + upHeight + "px" }, speed);
                            }
                        }
                    }
                    _btnUp.bind("click", scrollUp);
                    _btnDown.bind("click", scrollDown);
                }
            });
        })(jQuery);
      jQuery(function () {
            jQuery(".slogan").Scroll({ line: 1, speed: 500,up: "scroll_up", down: "scroll_down" });
        });
	  
  jQuery(document).ready(function() {
	 //carousel
	  if (jQuery("#feature-slider").length > 0) { 
      jQuery("#feature-slider").owlCarousel({
      navigation : true,
      slideSpeed : 300,
      paginationSpeed : 400,
      singleItem : true,
	  autoPlay : true,
	  pagination : false,
	  navigationText: [
		"<i class='icon-chevron-left icon-white'></i>",
		"<i class='icon-chevron-right icon-white'></i>"
		],
		afterMove:function(e){
		jQuery(".owl-buttons .carousel_item_title").remove();
		var title    = e.find(".owl-item:nth-child("+(this.owl.currentItem+1)+") img").attr("alt");
		var img_link = e.find(".owl-item:nth-child("+(this.owl.currentItem+1)+") a").attr("href");
		if(typeof img_link === "undefined"){img_link = "#";}
		jQuery(".owl-prev").after("<span class='carousel_item_title'><a href='"+img_link+"'>"+title+"</a></span>");
		},
		afterInit:function(e){
		
		var title    = e.find(".owl-item:nth-child(1) img").attr("alt");
		var img_link = e.find(".owl-item:nth-child(1) a").attr("href");
		if(typeof img_link === "undefined"){img_link = "#";}
		jQuery(".owl-prev").after("<span class='carousel_item_title'><a href='"+img_link+"'>"+title+"</a></span>");
		}
      });
	  }
    if (jQuery("#partners-slider").length > 0) { 
	var owl = jQuery("#partners-slider");
    owl.owlCarousel({items : 4,pagination : false});
	
    // Custom Navigation Events
    jQuery(".carousel-next").click(function(){
    owl.trigger('owl.next');
    })
    jQuery(".carousel-prev").click(function(){
    owl.trigger('owl.prev');
    })
     }
  //menu

  jQuery('.nav_menu ul li').hover(function(){
	jQuery(this).find('ul:first').slideDown(100);
	jQuery(this).addClass("hover");
	},function(){
	jQuery(this).find('ul').css('display','none');
	jQuery(this).removeClass("hover");
	});
  jQuery('.nav_menu li ul li:has(ul)').find("a:first").append(" <span class='menu_more'>»</span> ");
   var menu_width = 0;
		jQuery('.nav_menu ul:first > li').each(function(){
       menu_width = jQuery(this).outerWidth()+menu_width;
		if(menu_width > jQuery(this).parents("ul").innerWidth()){
			jQuery(this).prev().addClass("menu_last_item");
			menu_width = jQuery(this).outerWidth();
			}						   
});
		
		
		/*!
/* Mobile Menu
*/
(function($) {

	var current = $('.nav_menu li.current-menu-item a').html();
	if( $('span').hasClass('custom-mobile-menu-title') ) {
		current = $('span.custom-mobile-menu-title').html();
	}
	else if( typeof current == 'undefined' || current === null ) {
		if( $('body').hasClass('home') ) {
			if( $('.logo span').hasClass('site-name') ) {
				current = $('.logo .site-name').html();
			}
			else {
				current = $('.logo img').attr('alt');
			}
		}
		else{
			if( $('body').hasClass('woocommerce') ) {
				current = $('h1.page-title').html();
			}
			else if( $('body').hasClass('archive') ) {
				current = $('h6.title-archive').html();
			}
			else if( $('body').hasClass('search-results') ) {
				current = $('h6.title-search-results').html();
			}
            else if( $('body').hasClass('page-template-blog-excerpt-php') ) {
                current = $('.current_page_item').text();
            }
            else if( $('body').hasClass('page-template-blog-php') ) {
                current = $('.current_page_item').text();
            }
			else {
				current = $('h1.post-title').html();
			}
		}
	};
	
    if(typeof current == 'undefined' || current === null){current = "GO TO";}
	$('.nav_menu').append('<a id="responsive_menu_button"></a>');
	$('.nav_menu').prepend('<div id="responsive_current_menu_item">' + current + '</div>');
	$('a#responsive_menu_button, #responsive_current_menu_item').click(function(){												
		$('body .nav_menu ul').slideToggle( function() {
			if( $(this).is(':visible') ) {
				$('a#responsive_menu_button').addClass('responsive-toggle-open');
			}
			else {
				$('a#responsive_menu_button').removeClass('responsive-toggle-open');
				$('body .nav_menu ul').removeAttr('style'); 
			}
		});
});

})(jQuery);


    });