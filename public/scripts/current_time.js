function insertDate() {
    let dat = new Date();
    let date = twoDigit(dat.getDate()) + "." + twoDigit(dat.getMonth()+1) + "." + dat.getFullYear();
    let time = twoDigit(dat.getHours()) + ":" + twoDigit(dat.getMinutes()) + ":" + twoDigit(dat.getSeconds());

    $('#header-date').html(date + ' ' + time);
}

function twoDigit(value) {
    return (value < 10)? '0' + value : value;
}

$(document).ready( () => {
    insertDate();
    window.setInterval(insertDate, 1000);
})