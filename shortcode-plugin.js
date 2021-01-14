(function() {
    // Load the script
    var script = document.createElement("SCRIPT");
    script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
    script.type = 'text/javascript';
    
    // Ajax requests
    script.onload = function() {
        var $ = window.jQuery;
        $(".normal-sequence").on("click", function(){
            var value = $(".normal-sequence").html();
            var data = {
                postID: php_object.post_id,
                value: value,
                sequenceType: "normal"
            }
            $.post(php_object.post_url, data, function(response){ 
                console.log(response);
            });
        });
        $(".reversed-sequence").on("click", function(){
            var value = $(".reversed-sequence").html();
            var data = {
                postID: php_object.post_id,
                value: value,
                sequenceType: "reversed"
            }
            $.post(php_object.post_url, data, function(response){ 
                console.log(response);
            });
        });
    };

    document.getElementsByTagName("head")[0].appendChild(script);
})();