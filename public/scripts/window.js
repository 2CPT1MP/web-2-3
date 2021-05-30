$(document).ready(function(){
    $('form').submit(function(e) {
        e.preventDefault();
        $('body > *:not(.confirm-dialog)').css({'filter': 'blur(1px)', 'pointer-events': 'none'});
        $('body').prepend('<div class="confirm-dialog"><p>Введённые данные верны?</p><button type="submit" ' +
            'id="confirm">Да</button><button id="cancel">Нет</button></div>').hide().fadeIn();
        $('.confirm-dialog').css({
            'background-color': 'rgb(89, 46, 131)',
            'padding': '1em', 'z-index': '1000', 'margin': '0 auto',
            'transform': 'translate(-50%, -50%)', 'position': 'fixed', 'top': '50%',
            'left': '50%', 'border-radius': '0.25em', 'color': 'white'
        });

        $('button#confirm').click(() => {
            $('.confirm-dialog').fadeOut();
            $('body > *').css({'pointer-events': 'auto', 'filter': 'none'});
            this.submit();
        });

        $('button#cancel').click(() => {
            $('.confirm-dialog').fadeOut();
            $('body > *').css({'pointer-events': 'auto', 'filter': 'none'});
        });
    });
});