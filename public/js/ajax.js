/**
 * Created by lyudmila on 05.10.16.
 */
$( document ).ready(function() {
    console.log( "ready!" );

        $.ajax({
            url: '/users',
            async: true,
            success: function(msg){
                $( ".container" ).append( "<table class=\"table table-striped table-bordered table-hover table-responsive loginsList\">"+
                    "<thead>"+
                    "<tr class=\"info\">"+
                    "<td>Login</td>"+
                    "<td>Date</td>"+
                    "</tr>"+
                    "</thead>"+
                    "<tbody>"+
                    "</tbody>");

                msg=JSON.parse(msg);
                for (var i = 0; i < msg.length; i++){
                    $( ".loginsList tbody" ).append(
                        "<tr>"+
                        "<td class='customerIDCell'>"+msg[i].email+"</td>"+
                        "<td>"+msg[i].created_at+"</td>"+
                        "</tr>");
                };

                $('.customerIDCell').click(function() {
                    $('#email').val($(this).html())
                });
            }
        });
});
