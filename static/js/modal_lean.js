(function($){

  $.fn.extend({
    leanModal: function(options) {

      var defaults = {
        top: 100,
        overlay: 0.5,
        speed: 200
      };

      opts = $.extend(defaults, options);

      if (this.length) {
        return this.each(function() {
          $(this).click(function(e) {

            var overlay = $('<div id="lean_overlay" />'),
                modal = $($(this).attr('href')),
                modal_width;

            e.preventDefault();

            if (modal.length) {
              modal_width = modal.outerWidth();

              overlay
                .appendTo('body')
                .click(function() {
                  $(this).fadeOut(opts.speed, function () {
                    modal.hide();
                    $(this).remove();
                  });
                })
                .css({ display: 'block', opacity: 0 })
                .fadeTo(opts.speed, opts.overlay);

              modal
                .css({
                  display: 'block',
                  position: 'fixed',
                  opacity: 0,
                  'z-index': 11000,
                  left: 50 + '%',
                  'margin-left': -(modal_width/2) + 'px',
                  top: opts.top + 'px'
                })
                .fadeTo(opts.speed, 1);
            }

          });
        });
      }
    }
  });

})(jQuery);