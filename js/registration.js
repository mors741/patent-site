
$(document).ready(function(){
	var login_correct = false;
    $.validator.addMethod(
        "uniqueUserName", 
        function(value, element) {
            $.ajax({
                type: "POST",
                url: "Other/check.php",
                data: "login="+value,
                dataType:"html",
				cache: false,
                success: function(response)
                {
					res = response;
					if(response == 1){
						//$('#login').css('border', '3px #C33 solid');	
						//$('#tick').hide();     							// галочка и крестик
						//$('#cross').fadeIn();  							// галочка и крестик
						$('#login_error').fadeIn();
						login_correct = false;
					}else{
						//$('#login').css('border', '3px #090 solid');
						//$('#cross').hide();								// галочка и крестик
						//$('#tick').fadeIn();								// галочка и крестик
						$('#login_error').hide();
						login_correct = true;
					}
                }
            });
			return true;
        },
        "1Извините, этот логин уже занят"
    );

	$("#register-form").validate({
		rules: {
			login: {
				uniqueUserName: true,
				required: true,
				minlength: 5
			},
			fname: "required",
			lname: "required",
			email: {
				required: true,
				email: true
			},
			password: {
				required: true,
				minlength: 5
			},
			rpassword: {
				equalTo: "#password"
			}
        },
		messages: {
			login: {
				uniqueUserName: "Бла-бла-бла",
				required: "Пожалуйста, введите логин",
                minlength: "Минимальная длина логина - 5 символов"  
			},
			fname: "Пожалуйста, введите Ваше имя",
			lname: "Пожалуйста, введите Вашу фамилию",
			password: {
				required: "Пожалуйста, введите пароль",
				minlength: "Минимальная длина пароля - 5 символов"
			},
			email: {
				required: "Пожалуйста, введите ваш Email адрес",
				email:  "Некорректный формат Email адреса"
			},
			rpassword: "Пароли должны совпадать"
		},
		submitHandler: function(form) {
			if (login_correct)
				form.submit();
			else
				$('#login').focus();
		}
	});
});