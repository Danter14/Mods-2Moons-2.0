let voucher_url = "?page=voucher&mode=setUserVoucher";

$(document).ready(function () {
    $("#form-voucher").submit(function (e) {
        e.preventDefault();
        let voucher = $("input[name='voucher']").val();
        setUseVoucher(voucher);
    });
});

function setUseVoucher(voucher) {
    $.post(voucher_url, { voucher: voucher }, function (data) {
        NotifyBox(data.message, 3000);
        setTimeout(function () {
            window.location.reload();
        }, 3000);
    }, 'json');
}