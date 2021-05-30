function checkName() {
    let fioRegex = /^[А-ЯA-Z]([а-яa-z])+\s[А-ЯA-Z]([а-яa-z])+\s[А-ЯA-Z]([а-яa-z])+$/;
    let fio = $('[name="sender-name"]').css('border', 'none');
    if (!fioRegex.test(fio.val()))
        fio.css('background-color', 'rgba(255,0,0,0.25)')
            .get(0).setCustomValidity("Неверный формат ФИО");
    else
        fio.css('background-color', 'rgba(0,255,0,0.25)')
            .get(0).setCustomValidity("");
}

function checkPhoneNumber() {
    let phoneRegex = /^[+][7|3][0-9]{9,11}$/;
    let phone = $('[name="sender-phone"]').css('border', 'none');
    if (!phoneRegex.test(phone.val()))
        phone.css('background-color', 'rgba(255,0,0,0.25)')
            .get(0).setCustomValidity("Неверный формат номера телефона");
    else
        phone.css('background-color', 'rgba(0,255,0,0.25)')
            .get(0).setCustomValidity('');
}

function checkEmail() {
    let emailRegex = /^[A-Za-z0-9_]+[@][A-Za-z0-9_]+[.][A-Za-z0-9_]+$/;
    let email = $('[name="sender-email"]').css('border', 'none');
    if (!emailRegex.test(email.val()))
        email.css('background-color', 'rgba(255,0,0,0.25)')
            .get(0).setCustomValidity("Неверный формат электронной почты");
    else
        email.css('background-color', 'rgba(0,255,0,0.25)')
            .get(0).setCustomValidity("");
}

$(document).ready( () => {
    $('[name="sender-email"]').on('input', checkEmail);
    $('[name="sender-phone"]').on('input', checkPhoneNumber);
    $('[name="sender-name"]').on('input', checkName);
    $('#contact-form').on('submit', () => {
        checkName();
        checkPhoneNumber();
        checkEmail();
    });
});
