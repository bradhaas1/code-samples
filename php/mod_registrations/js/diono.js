var jQ = jQuery.noConflict();
jQ(document).ready(function () {
	//jQ('input:button').click(function () {
	//	jQ('#RegistrationForm').submit();
	//});
	jQ('#RegistrationForm').submit(function () {
		validate();
		return false;
	});
	jQ('.greyed').focus(function () {
		if ((jQ(this).val() == 'MM/DD/YYYY') || (jQ(this).val() == 'MM/YY')) {
			jQ(this).val('').removeClass('greyed');
		}
	});
	//var value = jQ('#purDate').val();
	//alert(value);

});
function validate() {

	var dataValid = true;
	jQ('#info').html('');
	jQ('.email').each(function () {
		var cur = jQ(this);
		cur.next('span').remove();
		var emailRegX = /^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/;
		if (!emailRegX.test(cur.val())) {
			cur.after('<span class="error">Invalid email address</span>');
			dataValid = false;
		}
	});
	jQ('.date').each(function () {
		var cur = jQ(this);
		cur.next('span').remove();
		var dateRegX = /^((0[1-9])|(1[0-2]))\/(\d{2})$/;		
		if (!dateRegX.test(cur.val())) {
			cur.after('<span class="error">Pease enter a valid MM/YY format</span>');
			dataValid = false;
		}
	});
	jQ('.datefull').each(function () {
		var cur = jQ(this);
		cur.next('span').remove();
		var dateRegXFull = /^(0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])[- /.](19|20)\d\d$/;
		if (!dateRegXFull.test(cur.val())) {
			cur.after('<span class="error">Pease enter a valid MM/DD/YYYY format</span>');
			dataValid = false;
		}
	});

	jQ('.required').each(function () {
		var cur = jQ(this);
		cur.next('span').remove();
		if (jQ.trim(cur.val()) == '') {
			cur.after('<span class="error">Required Field</span>');
			dataValid = false;
		}
	});
	//jQ('.greyed').each(function () {
	//	var cur = jQ(this);
	//	cur.next('span').remove();
	//	if (jQ(this).val() == 'MM/DD/YYYY') {
	//		cur.after('<span class="error">Please enter a valid date</span>');
	//		dataValid = false;
	//	}
	//});
	if (dataValid) {
		jQ('#RegistrationForm').submit();
	}
}

