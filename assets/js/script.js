import $ from 'jquery';
import moment from "moment";

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    $('a.bi-trash,a.confirm-danger').click(function () {
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
    $('.dateRange').daterangepicker({
        locale: {
            "format": "DD.MM.YYYY"
        },
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        autoUpdateInput: false
    }, function (start_date, end_date) {
        $('.dateRange').find('input').val(start_date.format('DD.MM.YYYY') + ' - ' + end_date.format('DD.MM.YYYY'));
    });
});