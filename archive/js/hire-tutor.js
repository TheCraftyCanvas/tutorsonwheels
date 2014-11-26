$(function() {
               $("#datepicker").datepicker({ dateFormat: "dd-mm-yy" }).val()
			});

			
			$(function() {

				var $el, leftPos, newWidth,
				
				$mainNav2 = $(".navbar-nav");
				$mainNav2.append("<li id='magic-line-two'></li>");
    
				var $magicLineTwo = $("#magic-line-two");
    
				$magicLineTwo
					.width($(".active").width())
					.height($mainNav2.height())
					.css("left", $(".active a").position().left)
					.css("top", $(".active a").position().top)
					.data("origLeft", $(".active a").position().left)
					.data("origTop", $(".active a").position().top)
					.data("origWidth", $magicLineTwo.width())
					.data("origColor", $(".active a").attr("rel"));
							
				$(".navbar-nav a").hover(function() {
					$el = $(this);
					leftPos = $el.position().left;
					topPos= $el.position().top;
					newWidth = $el.parent().width();
					$magicLineTwo.stop().animate({
						left: leftPos,
						top: topPos,
						width: newWidth,
						backgroundColor: $el.attr("rel")
					})
				}, function() {
					$magicLineTwo.stop().animate({
						left: $magicLineTwo.data("origLeft"),
						width: $magicLineTwo.data("origWidth"),
						backgroundColor: $magicLineTwo.data("origColor")
					});    
				});
				
				$("ul.dropdown-menu li").hover(function() {

				$magicLineTwo.stop().animate();

				$("#magic-line").css('visibility', 'hidden');



				}, function() {

				$("#magic-line-two").css('visibility', 'visible');

				leftPos = $el.position().left;

				newWidth = $el.parent().width();
				});
		
			});
$("document").ready(function()
	{	
		$.validator.addMethod("isalpha",function(value,element)
		{
			var temp=true;
			var str=/[0-9!@#$%^&*|()_><]/;
			temp=!str.test(value);
			return temp;
		},"only characters are allowed");
		
		$.validator.addMethod("isspace", function(value,element)
		{
			var temp=true;
			var str1=/\s/;
			temp=!str1.test(value);
			return temp;
		},"spaces not allowed");

		$.validator.addMethod("isnumber", function(value,element)
		{
			var temp=true;
			var str3=/[^0-9()-]/;
			temp=!str3.test(value);
			return temp;
		},"enter valid number");
		
		$.validator.addMethod("isemailid", function(value,elemenmt)
		{
			var temp=true;
			var str2=/^[a-zA-z0-9\-\.]+\@[a-zA-z0-9\.]+\.[a-zA-z]+$/;
			temp=str2.test(value);	
			return temp;
		},"enter valid email id");

		$("#hire-tutor").validate(
		{
			errorElement:"div",
			rules:
			{
				fname:
				{
					required:true,
					isalpha:true,
					isspace:true,
					minlength:4,
					maxlength:10
				},
			
				email:
				{
					required:true,
					isemailid:true
				},
				h_phone:
				{
					isnumber:true,
					minlength:10,
					maxlength:15
				},
				w_phone:
				{
					isnumber:true,
					minlength:10,
					maxlength:15
				},
				c_phone:
				{
					isnumber:true,
					minlength:10,
					maxlength:15
				}
			},
			messages:
			{
				fname:
				{
					required:"please enter first name",
					minlength:"Enter minimum 4 characters.",
					maxlength:"Enter maximum 10 letters."
				},
				
				email:
				{
					required:"please enter valid email id"
				}
			}
		});
	});