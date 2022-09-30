$( document ).ready( function() {
    $("#auth").click( function() {

        $(".error").html("");
        sendAjaxLoginForm("#auth-form","app/Player/Auth.php");
        return false;

    });
});
function sendAjaxLoginForm(ajax_form,url){
    $.ajax({
        type:     "POST",
        dataType: "json",
        url:      url,
        data:     $(ajax_form).serialize(),

        success: function(response) {

            if(response.length === 0)
            {
                alert("Пользователь успешно вошёл");
                document.location.href = "game.php";
            }
            else
            {
                if(! (response['login'] === undefined))
                {
                    $("#login-error").html(response['login']);
                }

                if(! (response['password'] === undefined))
                {
                    $("#password-error").html(response['password']);
                }

            }

        },

        error: function(error){
            console.log(`Error: ${error}`);
        }
    });
}