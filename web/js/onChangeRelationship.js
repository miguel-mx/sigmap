var $rel = $("#recommendation_relationships");

$(document).ready(function(){
    $rel.change(function(){

        $("#recommendation_relationship").attr('readonly', true);

        if ($rel.val()=='Other') {

            $("#recommendation_relationship").attr('readonly', false);
        }
    });
});