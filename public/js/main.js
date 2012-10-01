// Generated by CoffeeScript 1.3.3
(function() {
  var update_vendor_image_preview;

  update_vendor_image_preview = function() {
    var el, frame, img;
    el = $(".vendor-image-url input");
    frame = el.closest(".vendor-image-url").find(".vendor-image-preview-frame");
    img = frame.find("img");
    return img.attr("src", el.val());
  };

  $(document).on("click", "#add-deliverable-button", function() {
    return $(".deliverables-row:eq(0)").clone().appendTo(".prices-table tbody").find("input").val("");
  });

  $(document).on("click", ".remove-deliverable", function() {
    if ($(".deliverables-row").length !== 1) {
      return $(this).closest(".deliverables-row").remove();
    }
  });

  $(document).on("click", ".show-dismiss-modal", function() {
    var el, modal;
    el = $(this);
    modal = $("#dismiss-modal");
    modal.find(".company-name").text(el.data('vendor-company-name'));
    modal.find("textarea").val("");
    modal.find("button").button('reset');
    modal.modal('show');
    modal.off(".rfpez-dismiss");
    return modal.on("click.rfpez-dismiss", ".dismiss-btn", function() {
      $(this).button('loading');
      return $.ajax({
        url: "/contracts/" + el.data('contract-id') + "/bids/" + el.data('bid-id') + "/dismiss",
        data: {
          reason: modal.find("select[name=reason]").val(),
          explanation: modal.find("textarea[name=explanation]").val()
        },
        type: "GET",
        success: function(data) {
          if (data.status === "already dismissed" || "success") {
            modal.modal('hide');
            if (el.data('remove-from-list')) {
              return el.closest("." + el.data('remove-from-list')).remove();
            } else {
              return window.location.reload();
            }
          }
        }
      });
    });
  });

  $(document).on("submit", "#ask-question-form", function(e) {
    var button, el;
    e.preventDefault();
    el = $(this);
    button = el.find("button");
    button.button('loading');
    return $.ajax({
      url: "/questions",
      data: {
        contract_id: el.find("input[name=contract_id]").val(),
        question: el.find("textarea[name=question]").val()
      },
      type: "POST",
      success: function(data) {
        var new_question;
        button.button('reset');
        el.find("textarea[name=question]").val('');
        if (data.status === "success") {
          new_question = $(data.html);
          new_question.hide();
          $(".questions").append(new_question);
          return new_question.fadeIn(300);
        } else {
          return alert('error!');
        }
      }
    });
  });

  $(document).on("blur", ".vendor-image-url input", update_vendor_image_preview);

  $(document).on("click", ".answer-question-toggle", function() {
    var el, form, question;
    el = $(this);
    question = $(this).closest(".question-wrapper");
    form = $("#answer-question-form");
    form.find("input[name=id]").val(question.data('id'));
    form.find("textarea[name=answer]").val('');
    form.appendTo(question);
    return form.show();
  });

  $(document).on("submit", "#new-contract-form", function() {
    return $(this).find("button[type=submit]").button('loading');
  });

  $(document).on("submit", "#answer-question-form", function(e) {
    var el, question;
    e.preventDefault();
    el = $(this);
    question = el.closest(".question-wrapper");
    return $.ajax({
      url: el.attr('action'),
      type: "post",
      data: {
        id: el.find("input[name=id]").val(),
        answer: el.find("textarea[name=answer]").val()
      },
      success: function(data) {
        var new_question;
        if (data.status === "success") {
          el.hide();
          el.prependTo('body');
          question.find(".answer-question").remove();
          new_question = $(data.html);
          new_question.find(".answer").hide();
          question.replaceWith(new_question);
          return new_question.find(".answer").fadeIn(300);
        } else {
          return alert('error');
        }
      }
    });
  });

  $(function() {
    return update_vendor_image_preview();
  });

}).call(this);
