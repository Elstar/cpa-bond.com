import $ from 'jquery';

$(document).ready(function() {

    $('a.bi-trash').click(function () {
        let title = $(this).attr('data-title');
        let href = $(this).attr('data-href');
        let body = $(this).attr('data-content');

        if (title) {
            $('#confirmDialog-title').html(title);
        }
        if (body) {
            $('#confirmDialog-body').html('<p>' + body + '</p>');
        }
        if (href) {
            $('#confirmDialog-href').attr('href', href);
        }
    });
});