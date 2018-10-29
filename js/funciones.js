$("#nombreEmoticon1").hide();
$(".e1").click(function () { //Al hacer click al boton, Se Oculta el Div
    $("#nombreEmoticon1").fadeOut(1000); //Despues de 2000 Milisegundos

});
$(".e1").hover(function () {

    $("#nombreEmoticon1").show();
    $("#nombreEmoticon1").fadeIn(3000).css("position", "relative");
});
//2
$("#nombreEmoticon2").hide();
$(".e2").click(function () {
    $("#nombreEmoticon2").fadeOut(1000);
});
$(".e2").hover(function () {
    $("#nombreEmoticon2").show();
    $("#nombreEmoticon2").fadeIn(3000).css("position", "relative");
});
//3
$("#nombreEmoticon3").hide();
$(".e3").click(function () {
    $("#nombreEmoticon3").fadeOut(1000);
});
$(".e3").hover(function () {
    $("#nombreEmoticon3").show();
    $("#nombreEmoticon3").fadeIn(3000).css("position", "relative");
});
//4
$("#nombreEmoticon4").hide();
$(".e4").click(function () {
    $("#nombreEmoticon4").fadeOut(1000);
});
$(".e4").hover(function () {
    $("#nombreEmoticon4").show();
    $("#nombreEmoticon4").fadeIn(3000).css("position", "relative");
});
//5
$("#nombreEmoticon5").hide();
$(".e5").click(function () {
    $("#nombreEmoticon5").fadeOut(1000);
});
$(".e5").hover(function () {
    $("#nombreEmoticon5").show();
    $("#nombreEmoticon5").fadeIn(3000).css("position", "relative");
});


function calificaPost(){
        var parametros = {
            nom : $("input[name=nemoticon]:checked", "#emoform").val(),
            idpost : $("input[name=postID]").val(),
            numerip : $("input[name=Nemouser]").val(),
            pt:1
            };
        $.ajax({
                data:  parametros, //datos que se envian a traves de ajax
                url: my_ajax_object.ajax_url + "?action=mi_funcion_accion",
                type:  'post', //método de envio
                success:  function (response) { 
                    $("input[type=radio]").attr('disabled', true);
                    $(this).removeAttr('checked');
                    $("div.emoticones").html(response);
                       
                }
        });
}
