jQuery( document ).ready(function() {

    jQuery(window).scroll(function(){
        if  (jQuery(window).scrollTop() >= 100){
            //CSS changes go here
        }
        var scrolltop = jQuery(window).scrollTop();
        jQuery('.scrolltop').html( scrolltop );
    });
    var windowWidth = jQuery(window).width();
    jQuery('.windowWidth').html( windowWidth );

    //main top nav
    jQuery('.nav-container p.mobile').click(function(){
        jQuery('.top-block-mobile').hide();
        jQuery('#nav').toggle();

    })
    //search on mobile
    jQuery('.search-mobile').click(function(){
        jQuery('.links-mobile, .mini-cart').hide();
        jQuery('#search_mini_form').toggle();
    })

    /*open menu my account */
    jQuery('.my-account-mobile').click(function(){
        jQuery('.mini-cart, #search_mini_form').hide();
        jQuery('.links-mobile').toggle();
    })

    ///*open menu topcart */
    //jQuery('.mini-cart-show').click(function(){
    //    jQuery('.links-mobile, #search_mini_form').hide();
    //    jQuery('.mini-cart').toggle();
    //})

    jQuery('#narrow-by-list dt').click(function(){
        jQuery('#narrow-by-list dd').hide();
        jQuery(this).toggleClass(' active ');
        if (jQuery(this).hasClass('active')){
            jQuery(this).next('dd').show();
            jQuery('#narrow-by-list dt').removeClass(' active ');
            jQuery(this).addClass(' active ');
        }else{
            jQuery(this).next('dd').hide();
            jQuery('#narrow-by-list dt').removeClass(' active ');
        }
    })


});