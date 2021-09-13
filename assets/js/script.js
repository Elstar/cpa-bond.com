import $ from 'jquery';

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
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
    $('.setPayout').click(function () {
        let sum = $(this).attr('data-sum');
        let details = $(this).attr('data-details');
        let id = $(this).attr('data-payout-id');

        $('#payoutDialog-id').val(id);
        $('#payoutSumFinal').val(sum);
        $('#payoutDetails').html(details);
    });
});