jQuery(document).ready(function($) {
    $('.swatch-label input[type="radio"]').change(function() {
        $('.swatch-label').removeClass('activeSwatch');
        var colorName = $(this).closest('label').attr('title');
        $(this).closest('label').addClass('activeSwatch');
        //$('.js-color-swatches__color').text(colorName);
    });

    var $form = $('.variations_form');
    var variations = $form.data('product_variations');

    $(document).on('change', '.color-swatches-cust', async function () {
        var selectedColor = $(this).val();
        if (variations) {
            for (var i = 0; i < variations.length; i++) {
                var variation = variations[i];
                if ((variation.attributes.attribute_color != undefined && (variation.attributes.attribute_color).toLowerCase() === selectedColor.toLowerCase()) ||  (variation.attributes.attribute_pa_color != undefined && (variation.attributes.attribute_pa_color).toLowerCase() === selectedColor.toLowerCase())) {
                    $form.find('input.variation_id').val(variation.variation_id);
                    $form.trigger('found_variation', [variation]);

                    break;
                }
            }
        }
    });
});