/**
 * Theme functions file
 *
 * Contains handlers for navigation
 *
 */
( function( $ ) {
	var body    = $( 'body' ),
		_window = $( window ),
		nav, button;

	nav = $( '#primary-navigation' );
	button = body.find( '.menu-toggle' );

	// Extend class to body.
	( function() {    
                button.on( 'click.pianafifteen', function() {
			body.toggleClass( 'menu-toggled-on' );                 
		} );
	} )();
        

        $( document ).ready(function(){
            nav.waypoint(function(direction) {
                if('down' === direction) {
                    $(this.element).addClass('stuck');
                }else {
                    $(this.element).removeClass('stuck');
                }
              }
            );   
    
            //load twitter tweets
            $('.twitter-timeline-js').load(twitterFeed);

            //collapsers
            $('.collapsible').on('click','.collapse-trigger', function(){
                $(this).parents('.collapsible').toggleClass('collapsed').find('.collapse-content').slideToggle();
            });
            
            $('h4.mobile-accordion-trigger').click(function(){
                var target = $(this).parents('section.mobile-accordion');
                if (!target.hasClass('open')){
                    $('section.mobile-accordion').removeClass('open');    
                }
                target.toggleClass('open');
                });
                
            //jquery instagram
                
            $('.jquery-instagram').on('didLoadInstagram', function(event, response) {
                var target = $(this);
                if(response.data){
                    $.each(response.data, function(i, photo) {
                        target.append(
                            $(
                            '<div class="instagram-item">'+
                                '<a href="'+photo.link+'">'+
                                    '<img class="instagram-image" src="'+photo.images.thumbnail.url+'" >'+
                                '</a>'+
                                '<div class="instagram-info">'+
                                    '<div class="instagram-caption">'+
                                        '<a class="instagram-user" href="https://instagram.com/'+photo.user.username+'/" >@'+photo.user.username+'</a>'+
                                        '<a class="instagram-title" href="'+photo.link+'">'+photo.caption.text+'</a>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'
                            )
                        );
                    });
                }
            });

            $('.jquery-instagram').each(function(){
                var settings = $(this).data();
                settings.clientId = settings.clientid;
                $(this).instagram(settings);                
            });
                
        });        

} )( jQuery );
