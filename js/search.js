'use strict';

(function ($) {
    $(document).ready(function () {
        $('#search').keyup(function () {
            $('#result-search ul').html('');

            let $search = $(this).val();

            if ($search != '') {
                $.get('index.php?controller=acceuil&task=search&search=' + encodeURIComponent($search))
                    .done(function (data) {
                        $('#result-search').removeClass('d-none');
                        if (data != '') {
                            $('#result-search ul').append(data);
                        }
                        else {
                            $('#result-search .artist').append('Aucun resultat');
                            $('#result-search .song').html('Aucun resultat');
                        }
                    })
            }
        })
    })
})(jQuery)