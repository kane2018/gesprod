$(document).ready(function () {
    var dataTable = $('#commandegerant').DataTable({
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            "url": "",
            "type": "POST"
        },
        "columnDefs": [
            {

                "targets": [4, 5], //first column / numbering column
                "orderable": false //set not orderable
            }
        ],
        "oLanguage": {
            "sSearch": "Recherche"
        }
    });
    
});

