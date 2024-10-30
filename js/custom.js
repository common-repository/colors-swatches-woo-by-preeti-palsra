jQuery(document).ready(function ($) {
    function toggleSwatchInputs() {
        $('#anagha_p_swatches_data .swatch-select').each(function () {
            var $colorInput = $(this).closest('tr').find('.color-input');
            var $imageInput = $(this).closest('tr').find('.image-input');

            if ($(this).val() === 'color') {
                $colorInput.show();
                $imageInput.hide();
            } else if ($(this).val() === 'image') {
                $colorInput.hide();
                $imageInput.show();
            } else {
                $colorInput.hide();
                $imageInput.hide();
            }
        });
    }

    $('#anagha_p_swatches_data .swatch-select').change(function () {
        toggleSwatchInputs();
    });

    $('#anagha_p_swatches_data .upload-image-button').click(function (e) {
        e.preventDefault();
        var file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload an Image',
            button: {
                text: 'Use this image'
            },
            multiple: false
        });
        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $(e.target).closest('.image-input').find('.image-url').val(attachment.url);
        });
        file_frame.open();
    });

    toggleSwatchInputs(); // Initial call to handle existing data
});