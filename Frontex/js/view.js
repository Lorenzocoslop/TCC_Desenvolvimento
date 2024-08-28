$(document).ready(function() {

    //ABRIR MODAL
    $(".open_modal_aviso").click(function () {
        $(".modal_aviso").fadeIn(400);
        $(".modal_aviso").css("display", "flex");
    });
    //FECHA MODAL
    $(".modal_aviso-close").click(function () {
        $(".modal_aviso").fadeOut(400);
    });

    //INTERVALO DE TEMPO - MODAIS
    setInterval(function () {
        $(".modal_aviso").fadeOut(1000);
    }, 5000);

    //MOSTRA A SENHA
    $( "#mostarsenha" ).mousedown(function() {
        $("#senha").attr("type", "text");
    });

    $( "#mostarsenha" ).mouseup(function() {
        $("#senha").attr("type", "password");
    });

    setTimeout(function(){
        var statusContainer = document.getElementById('status-container');
        if (statusContainer) {
                $("#status-container").hide();
        }
    }, 3000);

});


const hamBurger = document.querySelector(".toggle-btn");
const sidebar = document.querySelector("#sidebar");

// Verifica se existe um estado salvo no localStorage
if (localStorage.getItem("sidebarExpanded") === "true") {
    sidebar.classList.add("expand");
}

// Adiciona o evento de clique no botão
hamBurger.addEventListener("click", function () {
    sidebar.classList.toggle("expand");

    // Salva o estado da sidebar no localStorage
    if (sidebar.classList.contains("expand")) {
        localStorage.setItem("sidebarExpanded", "true");
    } else {
        localStorage.setItem("sidebarExpanded", "false");
    }
});
