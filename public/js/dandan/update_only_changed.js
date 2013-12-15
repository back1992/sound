var inputs = $('input[type="text"]').each(function() {
    $(this).data('original', this.value);
});

$('#form').submit(function(){
    inputs.each(function() {
        if ($(this).data('original') !== this.value) {
            // Do something for the changed value
        } else {
            // And something else for the rest.
        }
    });
});