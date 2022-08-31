'use strict';


// Modal de Suppression
$('#delete').on('show.bs.modal', function (e) {
    $('#formdelete').attr("action", e.relatedTarget.href);
    $('#formdelete #deleteId').val(e.relatedTarget.dataset.id);
})



// TinyMCE Pour le textarea
tinymce.init({
    selector: 'textarea',
    height: 300,
    relative_urls: false,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table paste imagetools wordcount"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    content_css: [
        'css/bootstrap.min.css',
        'css/editor.css'
    ],
    images_upload_url: '<?= URL ?>/lib/uploadTinyMce.php',
    statusabar: false,
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});
