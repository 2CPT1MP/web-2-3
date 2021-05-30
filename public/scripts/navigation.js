$(document).ready( function() {
    $('.subNav').mouseenter(function() {
        $(this).find('#sublist')
            .css('display', 'flex').show();
    }).mouseleave( function() {
        $(this).find('#sublist').hide();
    });

    $('body > nav > *').mouseenter( function () {
        if (!$(this).find('a').hasClass('active')) {
            let img = $(this).find('img');
            let currSrc = img.attr('src');

            let dotPos = currSrc.lastIndexOf('.');
            let leftPart = currSrc.substring(0, dotPos)
            let rightPart = currSrc.substring(dotPos + 1, currSrc.length);

            let attrib = leftPart + '-checked.' + rightPart;
            img.attr('src', attrib);
        }
    })
    .mouseleave( function () {
        if (!$(this).find('a').hasClass('active')) {
            let img = $(this).find('img');
            let currSrc = img.attr('src');
            let checkedPos = currSrc.lastIndexOf('-checked');
            let dotPos = currSrc.lastIndexOf('.');

            if (checkedPos !== -1) {
                let leftPart = currSrc.substring(0, checkedPos)
                let rightPart = currSrc.substring(dotPos + 1, currSrc.length);
                let attrib = leftPart + '.' + rightPart;
                img.attr('src', attrib);
            }
        }
    });
});

