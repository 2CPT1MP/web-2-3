function addPopOver(element, text) {
    $(element).prepend('<div class="popover">' + text +'</div>')
        .focusin(function() {
            $(this).find('.popover').css({'position': 'absolute',
                'left' : '5em',
                'background-color': 'rgba(89, 46, 131, 1)',
                'color' : 'white',
                'padding': '0 0.5em',
               }).fadeIn();
        })
        .focusout(function() {
            $(this).find('.popover').fadeOut();
        });
    $('.popover').hide();
}

$(document).ready( () => {
    addPopOver('#fio-label', 'например, Иванов Иван Иванович');
    addPopOver('#email-label', 'например, igor@mail.ru');
    addPopOver('#phone-label', 'например, +79785668854');
});