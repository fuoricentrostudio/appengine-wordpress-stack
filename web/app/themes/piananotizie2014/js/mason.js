(function($){
    $(document).ready(function(){          

        var $masonry = $('.masonry-container');
        
        var grouting = function(event , laidOutItems ) { 
               setTimeout(function(){ 
                   $masonry.off('layoutComplete', grouting);
                   $(event.target).masonry('layout'); 
                   $masonry.on('layoutComplete', grouting);
               }, 200);
            };
        
        $masonry.imagesLoaded(function(){
            
            var defaults = { 
                    itemSelector: '.hentry',
                    columnWidth: '.masonry-container .hentry',
                    gutterWidth: 0,
                    isResizeBound: true
                };
            
            $.extend(defaults,$masonry.data('masonry-options'));
            
            itemSelector =  defaults.itemSelector;
                        
            $masonry.masonry(defaults);    
            $masonry.on('layoutComplete', grouting); 
            
        });
        
        function updateNextLink(url){
            if(url){
                $('a.load-more').attr('href',url).removeClass('no-more');
            } else {
                $('a.load-more').addClass('no-more');
            }                    
        }
                      
        //behavior load more
        $('a.load-more').on('click', function(e){
           $.get($(this).attr('href'), function(response){
                var items = $(response).find('.masonry-container > .hentry');
                items.css('position','absolute').css('left', '-1000px');
                $masonry.append(items);
                $masonry.trigger('contentappended', [items, response]);
            });
            e.preventDefault();
        });      

        $('.masonry-container').on('contentappended contentupdated', function (e, items, rawContent){        
            if(('contentappended' === e.type) && items){
                $masonry.imagesLoaded(function(){
                    $masonry.masonry('appended', items);
                });
            }
            
            if('contentupdated' === e.type){
                $masonry.masonry('reloadItems');
                $masonry.imagesLoaded(function(){
                    $masonry.masonry();
                });
            }
            
            if(rawContent){
                updateNextLink($(rawContent).find('.pagination .next').attr('href'));           
            }            
        });
        
    });
})(jQuery);   