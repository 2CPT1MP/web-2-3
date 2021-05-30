globalIndex = 0;

$(document).ready( () => {
    $('#img-container img').click(function() {
        $(this).toggleClass('enlarged');
    });

    $('#next-img').click(function() {
        let query = "#img-container #";
        query += (globalIndex+1 < $('#img-container > *').length)? ++globalIndex : globalIndex;

        $(this).parent().find('img')
            .attr('src', $(query).find('img').attr('src'));
        $(this).parent().find('p').text($(query).find('p').text());
    });

    $('#prev-img').click(function() {
        let query = "#img-container #";
        query+= (globalIndex-1 >= 0)? --globalIndex : globalIndex;
        $(this).parent().find('img')
            .attr('src', $(query).find('img').attr('src'));
        $(this).parent().find('p').text($(query).find('p').text());
    });

    $(this).blur();
})

