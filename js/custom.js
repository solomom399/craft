
var path = "http://localhost:2200/craft/server/app.php"


var sobanjo = function () {
	var self = this


	self.ds = function (formId) {
		$("#"+formId+" button[type=submit]").attr('disabled', 'disabled')
	}

	self.es = function (formId) {
		$("#"+formId+" button[type=submit]").removeAttr('disabled')
	}

	self.makeUse = function (formData, callback, errorCallback = null) {
		$.ajax({
			url: path,
			type: "POST",
			data: formData,
			cache: false,
		    processData: false,
		    contentType: false,
		    dataType: "JSON",
			success: function(r) {
				callback(r)
			},
		    error: function(XMLHttpRequest, textStatus, errorThrown){
		        errorCallback(XMLHttpRequest, textStatus, errorThrown)
		    }
		})
	}

	self.load = function (text = "") {
		swal({
			html: true,
			title : text,
			text : '<div class="md-preloader pl-size-md">'+
                '<svg viewbox="0 0 75 75">'+
                    '<circle cx="37.5" cy="37.5" r="33.5" class="pl-blue" stroke-width="4" />'+
                '</svg>'+
            '</div>',
			showConfirmButton : false
		})
	}

	self.error = function (text = "") {
		swal({
			html: true,
			title : "",
			text : 'Try again...',
			type : 'error',
		    confirmButtonColor: "#E91E63",
		})
	}


	self.success = function (msg = "", callback) {
		swal({
			html: true,
			title : msg,
			text : 'Thanks for Joining',
			type : 'success',
			confirmButtonClass : 'waves-effect waves-light btn btn-lg bg-pink',
		    confirmButtonColor: "#E91E63"
		},
        function () {
            callback()
        })
	}


    self.check = function (input, type, callback) {
        var f = new FormData()
        f.append('key', 'check')
        f.append('input', input)
        f.append('type', type)
        self.makeUse(f, function (r) {
            if (r.msg == 'found') {
                callback(r.text)
            } else {
               callback("")
            }
        }, function (a, b, c) {
            console.log(a+" "+b+" "+c)
        })
    }

}



var s = new sobanjo()

// =======================================================================

$("input[name=email]").bind('keyup blur', function () {
    s.check($(this).val(), 'email', function (r) {
        $("span[data-check=email]").html(r)
    })
})

// =======================================================================

$("input[name=username]").bind('keyup blur', function () {
    s.check($(this).val(), 'username', function (r) {
        $("span[data-check=username]").html(r)
    })
})

// =======================================================================

$(".logout").click(function () {
    s.load('Signing out..')
    localStorage.removeItem('member_log')
    localStorage.removeItem('member')
    window.location = "../index.html"
})

// =========================================================================



$(function () {
    $('#sign_in').validate({
        rules: {
            'username': {
                required: true
            },
            'password': {
                required: true
            }
        },
        messages: {
            'username':{
                required: "Password do not match"
            }
        },
        highlight: function (input) {

            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
        },
        submitHandler: function (form) {
            s.load('Processing...')

            var formData = new FormData(form)
            formData.append('key', 'signin')

            s.makeUse(formData, function (resp) {
                if(resp.msg != 'error') {
                    var user_details = JSON.stringify(resp.msg)
                    localStorage.setItem('member_log', '1')
                    localStorage.setItem('member', user_details)
                	window.location = 'data/home.html'
                } else {
                    s.error(resp.text)
                }
            }, function (a, b, c) {
            	s.error()
                console.log(a+" "+b+" "+c)	
            })
            
            return false
        }
    });
});


// =======================================================================


$(function () {
    $('#sign_up').validate({
        rules: {
            'terms': {
                required: true
            },
            'confirm': {
                equalTo: '.ps'
            }
        },
        highlight: function (input) {
            
            $(input).parents('.form-line').addClass('error');
        },
        unhighlight: function (input) {
            $(input).parents('.form-line').removeClass('error');
        },
        errorPlacement: function (error, element) {
            $(element).parents('.input-group').append(error);
            $(element).parents('.form-group').append(error);
        },
        submitHandler: function (form) {
            s.load('Processing..')

            var formData = new FormData(form)
            formData.append('key', 'signup')

            s.makeUse(formData, function (resp) {
            	if (resp.msg != 'error') {
            		s.success(resp.msg, function () {
	            		$("a[point=login]").trigger('click')
	            	})
            	} else {
            		s.error()
            	}
            	
            }, function (a, b, c) {
            	s.error()
            })
            
            return false
        }
    });
});



// ================================================================


$('select').material_select();

// ===============================================================


$('.button-collapse').sideNav({
        menuWidth: 300, // Default is 300
        edge: 'left', // Choose the horizontal origin
        closeOnClick: false, // Closes side-nav on <a> clicks, useful for Angular/Meteor
        draggable: true // Choose whether you can drag to open on touch screens
    }
)