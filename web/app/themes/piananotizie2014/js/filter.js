/* 
 * FuoricentroStudio WP PostCrossTax Frontend 
 * 
 * v1.1 - Copyright 2013 - Brando Meniconi (b.meniconi[at]fuoricentrostudio.com)
 */

(function($){
    
    $(document).ready(function(){
        
        var queryUrl = postCrossTaxConfig.queryUrl;
        var filterTarget = postCrossTaxConfig.filterTarget;
        var loadingClass = 'filter-loading';
        var itemSelector = '.hentry';
        
        $('.filter-option').on('change',function(){
                        
            var options = $('#article-multifilter').serializeArray();
            var parsedOptions = {};
            
            for (var i = 0; i < options.length; i++) {
                if(typeof parsedOptions[options[i].name] === 'undefined')
                    parsedOptions[options[i].name] = new Array();
                if( options[i].value !== '')
                    parsedOptions[options[i].name].push(options[i].value);
            }
                                         
            var urlString = prepare_url_path(parsedOptions);

            window.history.pushState(parsedOptions, $(this).attr('name'), queryUrl+urlString);

            update_page_content(urlString);
            
        });
              
        // Handler Evento in caso di Back o Forward del Browser
        window.onpopstate = function(event) {
            if(event.state !== null)
                update_page_content(prepare_url_path(event.state));
        };
        
        // Carica via AJAX il contenuto nella pagina
        function update_page_content(urlString){
            $('body').addClass(loadingClass);
            $(filterTarget).load(queryUrl+urlString+'/ '+itemSelector ,{},function(returnedHtml){                                              
                $(this).trigger('contentupdated', [[], returnedHtml]);
                $('body').removeClass(loadingClass);
            });
        }
                
        //Converte l'array associativo in URI
        function prepare_url_path(parsedOptions) {
                       
            var urlOptions = [];
            
            for (var key in parsedOptions) {
                if (parsedOptions[key].length !== 0){
                    if(key.toString().indexOf('_') == 0){
                        urlOptions.push(parsedOptions[key].join('+'));                    
                    }else{  
                        urlOptions.push(encodeURIComponent(key)+ '/' +parsedOptions[key].join('+'));
                    }
                }
            }
            
            return urlOptions.join('/');
        } 
    });
})(jQuery);
