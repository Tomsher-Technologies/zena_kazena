@foreach ($editors as $editor)
    <script>
        tinymce.init({
            selector: '{{ $editor['id'] }}',
            directionality: '{{ $editor['dir'] }}',
            menubar: 'edit view insert format tools table help',
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'preview', 'anchor',
                'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'template', 'code',
            ],
            menu: {
                edit: {
                    title: 'Edit',
                    items: 'undo redo | cut copy paste pastetext | selectall | searchreplace'
                },
                view: {
                    title: 'View',
                    items: 'visualaid visualchars visualblocks | spellchecker | preview fullscreen | showcomments'
                },
                insert: {
                    title: 'Insert',
                    items: 'image link media addcomment pageembed template codesample inserttable | charmap hr | pagebreak nonbreaking anchor tableofcontents | insertdatetime'
                },
                format: {
                    title: 'Format',
                    items: 'bold italic underline strikethrough superscript subscript | styles blocks fontfamily fontsize align lineheight | forecolor backcolor | language | removeformat'
                },
                tools: {
                    title: 'Tools',
                    items: 'spellchecker spellcheckerlanguage | a11ycheck wordcount'
                },
                table: {
                    title: 'Table',
                    items: 'inserttable | cell row column | advtablesort | tableprops deletetable'
                }
            },
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image media | preview  fullscreen | code',
        });
    </script>
@endforeach
