'use strict';
/* global Modernizr */

(function ($, undefined) {

  function setEqualHeight(columns) {
    var pddingBottom = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 0;

    var tallestcolumn = 0;
    columns.each(function () {
      var currentHeight = $(this).height();
      if (currentHeight > tallestcolumn) {
        tallestcolumn = currentHeight;
      }
    });
    columns.height(tallestcolumn + pddingBottom);
  }
  $(document).ready(function () {
    if (Modernizr.mq('(min-width: 768px)')) {
      $('#three_blocks .three_blocks_item').height('auto');
      setEqualHeight($('#three_blocks .three_blocks_item'));
      $('#three_blocks .three_blocks_item .tb_category').css('position', 'absolute');

      setEqualHeight($('.equal_footer'));
    }
  });
  $(window).resize(function () {
    if (Modernizr.mq('(min-width: 768px)')) {
      $('#three_blocks .three_blocks_item').height('auto');
      setEqualHeight($('#three_blocks .three_blocks_item'));
      $('#three_blocks .three_blocks_item .tb_category').css('position', 'absolute');

      setEqualHeight($('.equal_footer'));
    } else {
      $('#three_blocks .three_blocks_item').height('auto');
      $('#three_blocks .three_blocks_item .tb_category').css('position', 'absolute');
      $('.equal_footer').height('auto');
    }
  });
})(jQuery);
//# sourceMappingURL=main.js.map
