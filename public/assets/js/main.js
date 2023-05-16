autosize(document.querySelectorAll('textarea'));

const seePassword = (filde, icon) => {
    let input = document.getElementById(filde);
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        input.type = "password";
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}
$('#barcode').focus();
var barcode = '';
var interval;

document.addEventListener('keydown', function (e) {
    if (interval) {
        clearInterval(interval);
    }
    if (e.code == 'Enter') {
        if (barcode != '') {
            handleBarcode(barcode);
        }
        barcode = '';
        return;
    }
    if (e.code == 'Escape') {
        // console.log('barcode is empty');
        barcode = '';
        return;
    }
    if (e.code != 'ShiftLeft' || e.code != 'ShiftRight') {
        // console.log(e.code);
        barcode += e.key;
    }
    interval = setInterval(() => {
        barcode = '';
    }, 20);
});

const handleBarcode = (barcode) => {
    $('#barcode').val(barcode);
}


disableNetworkLogs = function () {
    // get data form browser
    var networkLogs = window.performance.getEntriesByType("resource");
    // clear a networkLogs
    networkLogs.forEach(function (log) {
        if (log.name.indexOf('http') === 0) {
            window.performance.clearResourceTimings();
        }
    });
};
disableNetworkLogs();
$(document).on('click', function (event) {
    if ($(event.target).hasClass('autocomplete-data') || $(event.target).attr('id') == 'barcode') {
        $('.autocomplete').removeClass('d-none');
    }else{
        $('.autocomplete').addClass('d-none');
    }
});

const PlayAudio = (src) => {
    let sound = new Audio();
    sound.src = src;
    sound.play();
}

var TablePrint = $('#POS-Table');

function printDiv() {
    var printContents = TablePrint.html();
    var TabelFotter = $('.Tabel-Fotter').html();
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents + TabelFotter;
    document.body.innerHTML = originalContents;
    window.print();
    if (window.print || window.close) {
        window.location.reload();
    }
}