$('div.logininfo a:eq(1)',window.parent.document).on('click', function(e){
    e.preventDefault();
    var logout_url = $(this).attr('href');

    var jqxhr = $.ajax({
                    type: "GET",
                    url: "./index.php?qa=auth&qa_1=logout&noredirect=true",
                    data: {}
    })
    .done(function() {
        //console.log('question2answers session closed redirection to:'+logout_url);        
    })
    .fail(function() {
        //console.log('Failed to close question2answers')
    })
    .always(function() {
        window.parent.location.replace(logout_url);
    });

    return false;

});
