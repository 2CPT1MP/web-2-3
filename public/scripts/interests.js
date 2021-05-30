function addListItems () {
    for (let i = 0; i < arguments.length; i++)
        appendItem(arguments[i])
}

function appendItem(item) {
    $('#interests-list')
        .append('<li><a href="' + item.url + '">' + item.title + "</a></li>");
}

$(document).ready( () => {
    addListItems(
        {title: 'Любимые занятия', url: '#hobbies'},
        {title: 'Любимые книги', url: '#books'},
        {title: 'Любимая музыка', url: '#music'}
    );
});