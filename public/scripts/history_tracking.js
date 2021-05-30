function addRecord() {
    let newVisit = localStorage.getItem(document.title);
    let logVar = localStorage.getItem('[history-tracking]');

    if (newVisit === null)
        localStorage.setItem('[history-tracking]',
            (logVar === null)? document.title :  logVar + ';' +document.title);
    localStorage.setItem(document.title,
        (newVisit === null)? '1' : parseInt(newVisit) + 1);
}

function addCookieRecord() {
    let parsedCookies = parseCookies(document.cookie);
    for (let i of parsedCookies) {
        if (i[0] === document.title) {
            document.cookie = i[0] + '=' + (parseInt(i[1])+1);
            return;
        }
    }
    document.cookie = document.title + '=' + '1';
}

function parseCookies(cookie) {
    let cookies = cookie.split('; ');
    for (let i = 0; i < cookies.length; i++)
        cookies[i] = cookies[i].split('=');
    return cookies;
}

function findCookie(cookie, parsedCookies) {
    for (let i of parsedCookies)
        if (i[0] === cookie)
            return i[1];
    return null;
}

function fillTable() {
    let loggedVisits = localStorage.getItem('[history-tracking]').split(';');
    let table = $('#log-table');
    let parsedCookies = parseCookies(document.cookie);

    for (let i of loggedVisits) {
        table.append('<tr><td>' + i + '</td><td>' + localStorage.getItem(i) + '</td>'
            + '<td>' + findCookie(i, parsedCookies) + '</td></tr>');
    }
}

$(document).ready( () => {
    if ($('#log-table').length === 0) {
        addRecord();
        addCookieRecord();
    }
    else {
        fillTable();
        $('#localStorage-clear').click( () => {localStorage.clear()});
    }
});