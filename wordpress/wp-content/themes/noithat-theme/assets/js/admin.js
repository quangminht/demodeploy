(function($) {
    'use strict';

    // Banner media uploader
    $(document).on('click', '.noithat-select-image', function(e) {
        e.preventDefault();
        var $btn     = $(this);
        var $item    = $btn.closest('.noithat-banner-item');
        var $urlInput = $item.find('.banner-url');
        var $preview  = $item.find('.banner-preview');

        var frame = wp.media({
            title: 'Chọn ảnh banner',
            button: { text: 'Dùng ảnh này' },
            multiple: false
        });

        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            $urlInput.val(attachment.url);
            $preview.html('<img src="' + attachment.url + '" style="width:100%;height:100%;object-fit:cover;">');
            $btn.text('🔄 Đổi ảnh');

            // Show remove button if not already there
            if ( ! $item.find('.noithat-remove-image').length ) {
                $btn.after('<button type="button" class="button noithat-remove-image" style="width:100%;margin-top:4px;color:#a00;">✕ Xoá ảnh</button>');
            }
        });

        frame.open();
    });

    // Remove banner image
    $(document).on('click', '.noithat-remove-image', function(e) {
        e.preventDefault();
        var $item = $(this).closest('.noithat-banner-item');
        $item.find('.banner-url').val('');
        $item.find('.banner-preview').html('<div style="height:100%;display:flex;align-items:center;justify-content:center;color:#999;font-size:12px;">Chưa có ảnh</div>');
        $item.find('.noithat-select-image').text('📷 Chọn ảnh');
        $(this).remove();
    });

})(jQuery);
