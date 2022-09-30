$(document).ready(function(){
    const EMPTY_CELL = '';
    const ATTACK_SYMBOL = 'x';
    let game_field =[
        '','','',
        '','','',
        '','',''];
    let symbols = {
        player : 'x',
        bot : 'o'
    };
    highlightAttacker();
    let clickDisabled = false;

    $('.td').on('click',function(){
        if(clickDisabled) return;

        let id = $(this).data('id');
        if(game_field[id]===EMPTY_CELL) {

            clickDisabled = true;
            updateField(id,symbols.player);//player attacks
            askBot();

        }

    });

    function highlightAttacker()
    {
        Object.keys(symbols).forEach(key=>{
            let label_name = $(`#${key}-name`);

            (symbols[key]===ATTACK_SYMBOL) ?
                $(label_name).addClass('attacker')
                : $(label_name).removeClass('attacker')
        })
    }



    function askBot(){
        $.ajax({
            url:"app/Bot/askBot.php",
            type:"POST",
            dataType: 'json',
            data: {
                game_field : game_field,
                level      :  $('#player-level').text(),
                symbols    : JSON.stringify(symbols)},

            success: function(response){
                if(response.id !== -1) {
                    updateField(response.id,symbols.bot);
                    if(response.result === EMPTY_CELL){
                        console.log("сработало");
                        clickDisabled = false;
                    }
                }
                if(response.result !== EMPTY_CELL){
                    setTimeout(() => {
                        alert(response.result);
                        restart();
                        $('#player-level').text(response.level);
                        clickDisabled = false;
                    }, 300);
                }

            },
            error: function(error){
                console.log(`Error: ${error}`);
            }
        });
    }
    function clearField(){
        $('.td div').removeClass("visible");
        game_field.fill(EMPTY_CELL);
    }
    function updateField(id,symbol){
        game_field[id] = symbol;
        showCell($(`.td[data-id='${id}']`),symbol);
    }
    function showCell(cell,symbol){
        $(cell).children(`.${symbol}`).addClass("visible");
    }

    function reverseRoles(){
        [symbols.player,symbols.bot] = [symbols.bot,symbols.player];
        highlightAttacker();
    }
    function restart(){
        clearField();
        reverseRoles();

        if(symbols.bot===ATTACK_SYMBOL)
        {
            clickDisabled = true;
            askBot();
        }
    }

});
