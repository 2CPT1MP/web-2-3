function fillMonth() {
    let rootEl = $('#calendar-root').empty();
    let date = new Date();
    date.setFullYear($('[name="sender-year"]').val(), $('[name="sender-month"]').val(), 1);

    for (let i = date.getDay(), j = 1; ;) {
        while (j < i) {
            rootEl.append('<div class="calendar-cell"> </div>');
            j++;
        }
        rootEl.append('<div class="calendar-cell">' + date.getDate().toString() + '</div>');
        let currDate = date.getDate();
        date.setDate(currDate + 1);

        if (date.getDate() <= currDate)
            break;
    }

    $('#calendar-root > *').click(function() {
        let senderDay = $('[name="sender-day"]');
        senderDay.val($(this).text());
        $('#dob-calendar').fadeOut();
    });
}

$(document).ready( () => {

    $('[name="sender-day"]').focusin(function() {
        fillMonth();
        $('#dob-calendar').fadeIn();
    });

    $('[name="sender-month"], [name="sender-year"]').change(fillMonth);
});