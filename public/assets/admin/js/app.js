

$(function () {
	"use strict";


	function proPicURL(input) {
		if (input.files && input.files[0]) {
			console.log(input);
			var reader = new FileReader();
			reader.onload = function (e) {
				var preview = $(input).closest('.image-upload-wrapper').find('.image-upload-preview');
				$(preview).css('background-image', 'url(' + e.target.result + ')');
				$(preview).addClass('has-image');
				$(preview).hide();
				$(preview).fadeIn(650);
			}
			reader.readAsDataURL(input.files[0]);
		}
	}

	$(".image-upload-input").on('change', function () {
		proPicURL(this);
	});


	$(".remove-image").on('click', function () {
		$(this).parents(".image-upload-preview").css('background-image', 'none');
		$(this).parents(".image-upload-preview").removeClass('has-image');
		$(this).parents(".image-upload-wrapper").find('input[type=file]').val('');
	});


	$("form").on("change", ".file-upload-field", function () {
		$(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, ''));
	});



	// Tooltops

	$(function () {
		$('[data-bs-toggle="tooltip"]').tooltip();
	})



	$(".nav-toggle-icon").on("click", function () {
		$(".wrapper").toggleClass("toggled")
	})

	$(".mobile-toggle-icon").on("click", function () {
		$(".wrapper").addClass("toggled")
	})

	$(function () {
		for (var e = window.location, o = $(".metismenu li a").filter(function () {
			return this.href == e
		}).addClass("").parent().addClass("mm-active"); o.is("li");) o = o.parent("").addClass("mm-show").parent("").addClass("mm-active")
	})


	$(".toggle-icon").click(function () {
		$(".wrapper").hasClass("toggled") ? ($(".wrapper").removeClass("toggled"), $(".sidebar-wrapper").unbind("hover")) : ($(".wrapper").addClass("toggled"), $(".sidebar-wrapper").hover(function () {
			$(".wrapper").addClass("sidebar-hovered")
		}, function () {
			$(".wrapper").removeClass("sidebar-hovered")
		}))
	})



	$(function () {
		$("#menu").metisMenu()
	})


	$(".search-toggle-icon").on("click", function () {
		$(".top-header .navbar form").addClass("full-searchbar")
	})
	$(".search-close-icon").on("click", function () {
		$(".top-header .navbar form").removeClass("full-searchbar")
	})


	$(".chat-toggle-btn").on("click", function () {
		$(".chat-wrapper").toggleClass("chat-toggled")
	}), $(".chat-toggle-btn-mobile").on("click", function () {
		$(".chat-wrapper").removeClass("chat-toggled")
	}), $(".email-toggle-btn").on("click", function () {
		$(".email-wrapper").toggleClass("email-toggled")
	}), $(".email-toggle-btn-mobile").on("click", function () {
		$(".email-wrapper").removeClass("email-toggled")
	}), $(".compose-mail-btn").on("click", function () {
		$(".compose-mail-popup").show()
	}), $(".compose-mail-close").on("click", function () {
		$(".compose-mail-popup").hide()
	})


	$(document).ready(function () {
		$(window).on("scroll", function () {
			$(this).scrollTop() > 300 ? $(".back-to-top").fadeIn() : $(".back-to-top").fadeOut()
		}), $(".back-to-top").on("click", function () {
			return $("html, body").animate({
				scrollTop: 0
			}, 600), !1
		})
	})


	// switcher

	$("#LightTheme").on("click", function () {
		$("html").attr("class", "light-theme")
	}),

		$("#DarkTheme").on("click", function () {
			$("html").attr("class", "dark-theme")
		}),

		$("#SemiDarkTheme").on("click", function () {
			$("html").attr("class", "semi-dark")
		}),

		$("#MinimalTheme").on("click", function () {
			$("html").attr("class", "minimal-theme")
		})


	$("#headercolor1").on("click", function () {
		$("html").addClass("color-header headercolor1"), $("html").removeClass("headercolor2 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8")
	}), $("#headercolor2").on("click", function () {
		$("html").addClass("color-header headercolor2"), $("html").removeClass("headercolor1 headercolor3 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8")
	}), $("#headercolor3").on("click", function () {
		$("html").addClass("color-header headercolor3"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor8")
	}), $("#headercolor4").on("click", function () {
		$("html").addClass("color-header headercolor4"), $("html").removeClass("headercolor1 headercolor2 headercolor3 headercolor5 headercolor6 headercolor7 headercolor8")
	}), $("#headercolor5").on("click", function () {
		$("html").addClass("color-header headercolor5"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor3 headercolor6 headercolor7 headercolor8")
	}), $("#headercolor6").on("click", function () {
		$("html").addClass("color-header headercolor6"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor3 headercolor7 headercolor8")
	}), $("#headercolor7").on("click", function () {
		$("html").addClass("color-header headercolor7"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor3 headercolor8")
	}), $("#headercolor8").on("click", function () {
		$("html").addClass("color-header headercolor8"), $("html").removeClass("headercolor1 headercolor2 headercolor4 headercolor5 headercolor6 headercolor7 headercolor3")
	})

	new PerfectScrollbar(".header-notifications-list")


});



Array.from(document.querySelectorAll('table')).forEach(table => {
	let heading = table.querySelectorAll('thead tr th');
	Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
		Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
			colum.setAttribute('data-label', heading[i].innerText)
		});
	});
});


$('.navbar-search-field').on('input', function () {
	var search = $(this).val().toLowerCase();
	var search_result_pane = $('.search-list');
	$(search_result_pane).html('');
	if (search.length == 0) {
		$('.search-list').addClass('d-none');
		return;
	}
	$('.search-list').removeClass('d-none');

	// search
	var match = $('.sidebar__menu-main li a').filter(function (idx, elem) {
		return $(elem).text().trim().toLowerCase().indexOf(search) >= 0 ? elem : null;
	}).sort();

	// search not found
	if (match.length == 0) {
		$(search_result_pane).append('<li class="text-muted pl-5">No search result found.</li>');
		return;
	}

	// search found
	match.each(function (idx, elem) {
		var parent = $(elem).parents('.sidebar--menu.sidebar--dropdown').find('.menu-title').first().text();
		if (!parent) {
			parent = `Main Menu`
		}
		parent = `<small class="d-block">${parent}</small>`
		var item_url = $(elem).attr('href') || $(elem).data('default-url');
		var item_text = $(elem).text().replace(/(\d+)/g, '').trim();
		$(search_result_pane).append(`
        <li>
          ${parent}
          <a href="${item_url}" class="fw-bold text-color--3 d-block">${item_text}</a>
        </li>
      `);
	});


	var len = 0;
	var clickLink = 0;
	var search = null;
	var process = false;
	$('.navbar-search-field').on('keydown', function (e) {
		var length = $('.search-list li').length;

		if (search != $(this).val() && process) {
			len = 0;
			clickLink = 0;
			$(`.search-list li:eq(${len}) a`).focus();
			$(`.navbar-search-field`).focus();
		}
		//Down
		if (e.keyCode == 40 && length) {
			process = true;
			var contra = false;
			if (len < clickLink && clickLink < length) {
				len += 2;
			}
			$(`.search-list li[class="bg-dark"]`).removeClass('bg-dark');
			$(`.search-list li a[class="text-white"]`).removeClass('text-white');
			$(`.search-list li:eq(${len}) a`).focus().addClass('text-white');
			$(`.search-list li:eq(${len})`).addClass('bg-dark');
			$(`.navbar-search-field`).focus();
			clickLink = len;
			if (!$(`.search-list li:eq(${clickLink}) a`).length) {
				$(`.search-list li:eq(${len})`).addClass('text-white');
			}
			len += 1;
			if (length == Math.abs(clickLink)) {
				len = 0;
			}
		}
		//Up
		else if (e.keyCode == 38 && length) {
			process = true;
			if (len > clickLink && len != 0) {
				len -= 2;
			}
			$(`.search-list li[class="bg-dark"]`).removeClass('bg-dark');
			$(`.search-list li a[class="text-white"]`).removeClass('text-white');
			$(`.search-list li:eq(${len}) a`).focus().addClass('text-white');
			$(`.search-list li:eq(${len})`).addClass('bg-dark');
			$(`.navbar-search-field`).focus();
			clickLink = len;
			if (!$(`.search-list li:eq(${clickLink}) a`).length) {
				$(`.search-list li:eq(${len})`).addClass('text-white');
			}
			len -= 1;
			if (length == Math.abs(clickLink)) {
				len = 0;
			}
		}
		//Enter
		else if (e.keyCode == 13) {
			e.preventDefault();
			if ($(`.search-list li:eq(${clickLink}) a`).length && process) {
				$(`.search-list li:eq(${clickLink}) a`)[0].click();
			}
		}
		//Retry
		else if (e.keyCode == 8) {
			len = 0;
			clickLink = 0;
			$(`.search-list li:eq(${len}) a`).focus();
			$(`.navbar-search-field`).focus();
		}
		search = $(this).val();
	});



	$(document).ready(function() {
		$('.red__notify').addClass('animate');
	  });

});

function validateNumber(e) {
    const pattern = /^[0-9]+$/;
    return pattern.test(e.key);
}

function getLists(url, showID, formID = null, pageNo = null) {
    let formData = "";

    if (formID !== null) {
        formData = $('#' + formID).serialize();
    }

    let page = pageNo !== null ? pageNo : 1;

    let finalUrl = url + "?page=" + page;
    if (formData.length > 0) {
        finalUrl += "&" + formData;
    }

    $("#manualLoader").fadeIn(200);

    $.ajax({
        url: finalUrl,
        type: "GET",
        data: formData,
        dataType: "json",
        beforeSend: function () {
            $('#' + showID).html('<div class="text-center p-3">Loading...</div>');
        },
        success: function (res) {
            $("#manualLoader").fadeOut(200);
            if (res.status === true) {
                $('#' + showID).html(res.html);
            } else {
                toastr.warning("No data found!");
            }
        },
        error: function () {
            $("#manualLoader").fadeOut(200);
            toastr.error("Something went wrong!");
        }
    });
}

function storeData(formID, submitBtnID) {
    let form = formID;
    let url = form.attr("action");
    let formData = new FormData(form[0]);

    $("#manualLoader").fadeIn(200);

    $("#" + submitBtnID).prop("disabled", true).text("সাবমিট হচ্ছে...");

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {

            $("#manualLoader").fadeOut(200);

            if (response.status === true) {
                form[0].reset();

                $("#" + submitBtnID).prop("disabled", false).text("Submit");

                toastr.success(response.message ?? 'Data saved successfully!');

                if (response.redirect) {
                    setTimeout(function () {
                        window.location.href = response.redirect;
                    }, 1000);
                }

            } else {
                toastr.warning(response.message ?? 'Something went wrong!');
                $("#" + submitBtnID).prop("disabled", false).text("Submit");
            }
        },
        error: function(xhr) {
            $("#manualLoader").fadeOut(200);

            let errors = xhr.responseJSON?.errors;

            if (errors) {
                Object.values(errors).forEach(errArr => {
                    errArr.forEach(err => toastr.error(err));
                });
            } else {
                toastr.error('Something went wrong!');
            }

            $("#" + submitBtnID).prop("disabled", false).text("Submit");
        }
    });
}

function editData(formID, submitBtnID) {
    let form = $("#" + formID);
    let url = form.attr("action");
    let formData = new FormData(form[0]);

    $("#manualLoader").fadeIn(200);
    $("#" + submitBtnID).prop("disabled", true).text("সাবমিট হচ্ছে...");

    $.ajax({
        url: url,
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            $("#manualLoader").fadeOut(200);

            if (response.status === true) {
                toastr.success(response.message ?? "Quotation updated successfully!");

                if (response.redirect) {
                    setTimeout(function () {
                        window.location.href = response.redirect;
                    }, 1000);
                }
            } else {
                toastr.warning(response.message ?? "Something went wrong!");
            }

            $("#" + submitBtnID).prop("disabled", false).text("Submit");
        },
        error: function(xhr) {
            $("#manualLoader").fadeOut(200);
            $("#" + submitBtnID).prop("disabled", false).text("Submit");

            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                Object.values(errors).forEach(errArr => {
                    errArr.forEach(err => toastr.error(err));
                });
            } else {
                toastr.error(xhr.responseJSON?.message ?? "Something went wrong!");
            }
        }
    });
}

