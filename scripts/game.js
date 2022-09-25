$(document).ready(function(){
    const EMPTY_CELL = '';
    let table = $(".table");
    let game_field =[
        '','','',
        '','','',
        '','',''];
    let symbols = {
        player : 'x',
        bot : 'o'
    };
    let clickDisabled = false;

    $('.td').on('click',function(){
        if(clickDisabled) return;

        preventClicks();

        let id = $(this).data('id');
        if(game_field[id]===EMPTY_CELL) {
            updateField(id,symbols.player);//player attacks
            askBot();
        }

    });

    function askBot(){
        $.ajax({
            url:"bot.php",
            type:"POST",
            dataType: 'json',
            data: {
                game_field : game_field,
                level      :  $('#player-level').text(),
                symbols    : JSON.stringify(symbols)},

            success: function(response){
                if(response.id !== -1) {
                    updateField(response.id,symbols.bot);
                }
                if(response.result !== EMPTY_CELL){
                    setTimeout(()=> alert(response.result),200);
                    setTimeout(()=> clearField(),200);
                    $('#player-level').text(response.level);
                    setTimeout(()=> reverseRoles(),200);
                }

            },
            error: function(error){
                console.log(`Error: ${error}`);
            }
        });
    }
    function clearField(){
        $('.td div').css("visibility","hidden");
        game_field.fill(EMPTY_CELL);
    }
    function updateField(id,symbol){
        game_field[id] = symbol;
        showCell($(`.td[data-id='${id}']`),symbol);
    }
    function showCell(cell,symbol){
        $(cell).children(`.${symbol}`).css("visibility","visible");
    }
    function preventClicks(){
        clickDisabled = true;
        setTimeout(()=> { clickDisabled = false;}, 500);
    }
    function reverseRoles(){
        let buf = symbols.player;
        symbols.player = symbols.bot;
        symbols.bot = buf;

        if(symbols.bot==='x')
        {
            askBot();
            preventClicks();
        }
    }





});

/*

$('.highlighted label').on( 'click',function(){
    console.log(1);
    symbols = {
        player : 'o',
        bot : 'x'
    };
    $("#center").css("display","none");
    askBot();
    preventClicks();
    /!*$("#center").css("display","none");*!/

});
*/