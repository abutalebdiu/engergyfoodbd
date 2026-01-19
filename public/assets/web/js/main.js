Array.from(document.querySelectorAll('table')).forEach(table => {
	let heading = table.querySelectorAll('thead tr th');
	Array.from(table.querySelectorAll('tbody tr')).forEach((row) => {
		Array.from(row.querySelectorAll('td')).forEach((colum, i) => {
			colum.setAttribute('data-label', heading[i].innerText)
		});
	});
});

$('.d-subbtn > a').on('click', function () {
	$(this).parent().find('.d-submenu').slideToggle()
})

$('.d-mobile-toggle').on('click', function () {
	$('.d-mobile-menu').toggleClass('active');
})


// image upload
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


$("form").on("change", ".file-upload-field", function () {
	$(this).parent(".file-upload-wrapper").attr("data-text", $(this).val().replace(/.*(\/|\\)/, ''));
});


$('.d-submenu.mm-active > .mm-active').find('.fa-regular').removeClass('fa-circle').addClass('fa-circle-dot');


// image upload end