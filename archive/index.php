<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Ecommerce">
		<meta name="author" content="Ecommerce">
		<title>Tutorsonwheels</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="css/bootstrap-override.css" rel="stylesheet">
		<script type="text/javascript" src="js/jquery.js"></script>
		<!-- jQuery library (served from Google) -->
		<script type="text/javascript" src="slider/jquery.min.js"></script>
		<script type="text/javascript" src="js/carousels.js"></script>
		<!-- bxSlider Javascript file -->
		<script type="text/javascript" src="slider/jquery.bxslider.min.js"></script>
		<script type="text/javascript" src="slider/jquery.bxslider.js"></script>
		<!-- bxSlider CSS file -->
		<link href="slider/jquery.bxslider.css" rel="stylesheet" />
		
		<script type="text/javascript">
			$(document).ready(function(){
			  $('.bxslider').bxSlider();
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
		</script>
	</head>
	<body>
	
	 <div class="container">
		<header>
			<div class="row">
				<div class="col-sm-3">
					<div class="logoholder">
						<a href="http://tutorsonwheels.com"><img src="images/logo.jpg"></a>
					</div>
				</div>
				<div class="col-sm-9">
					<div class="nav-top-search">
						<div class="moduletable1">
							<form action="index.php" method="post">
								<div class="search">
									<input name="searchword" id="mod_search_searchword" maxlength="30" alt="Search" class="inputbox" type="text" size="30" value="Search..." onblur="if(this.value=='') this.value='Search...';" onfocus="if(this.value=='Search...') this.value='';">	
									<a href="#"><img height="25" src="images/searchButton.gif" class="search-border"></a>
								</div>
							</form>		
						</div>
						<div class="moduletable">
							<ul class="menu-nav">
								<li class="item28">
									<a href="contact-us.html"><span>Contact Us</span></a>
								</li>
								<li class="item53">
									<a href="faq.html"><span>FAQs</span></a>
								</li>
								<li class="item54">
									<a href="http://blog.tutorsonwheels.com" target="_blank"><span>Blogs</span></a>
								</li>
							</ul>
						</div>
						<div class="social">
							<ul class="social-ul">
								<li class="social-li1"><a href="http://blog.tutorsonwheels.com"><img src="images/blog.png"></a></li>
								<li class="social-li2" style="padding-top:10px;"><a href="https://www.facebook.com/tutors.wheels?fref=ts" target="_blank"><img src="images/fb.png"></a></li>
							</ul>
						</div>
					</div>
					
					<div class="moduletable2">
						<div class="tolfree-div">
							<img src="images/phone.png" height="22" alt="phone"><span class="tolfree">TOLL FREE</span><br>
						</div>
						<div class="phone-no">
							<span class="phone-text">1-877-TUTOR NY / (718) 268-0092</span>
						</div>
						
					</div>
				</div>
			</div>
		</header>
		

      <!-- Static navbar -->
      <div class="navbar navbar-default" role="navigation">
        <div class="container-fluid">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            
          </div>
          <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
				<li class="active dropdown1"><a href="http://tutorsonwheels.com">HOME</a></li>
				<li class="dropdown1"><a href="about-us.html">ABOUT US</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">SERVICES <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					  <li><a href="school-educators.html">School / Educators</a></li>
					  <li><a href="government-agencies.html">Government Agencies</a></li>
					  <li><a href="libraries.html">Libraries</a></li>
					  <li><a href="military-students.html">Military Students</a></li>
					  <li><a href="non-profit-organizations.html">Non Profit Organizations</a></li>
					</ul>
				</li>
				<li class="dropdown1"><a href="our-mission-and-goals.html">OUR MISSION AND GOALS</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle">GRADE LEVELS<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					  <li><a href="elementary.html">Elementary</a></li>
					  <li><a href="middle-school.html">Middle School</a></li>
					  <li><a href="high-school.html">High School</a></li>
					  <li><a href="college.html">College</a></li>
					  <li><a href="adult-tutoring.html">Adult Tutoring</a></li>
					  
					</ul>
				</li>
				<li class="dropdown1"><a href="subjects.html">SUBJECTS</a></li>
				<li class="dropdown1"><a href="test-prep.html">TEST PREP</a></li>
				<li class="dropdown1"><a href="our-approach.html">OUR APPROACH</a></li>
				<li class="dropdown1"><a href="our-method.html">OUR METHOD</a></li>
				<li class="dropdown1"><a href="our-tutors.html">OUR TUTORS</a></li>
				<li class="dropdown1"><a href="tutor-jobs.html">TUTOR JOBS</a></li>
            </ul>
           
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </div>

      <!-- Main component for a primary marketing message or call to action -->
      
	  <div class="container">
	  <ul class="bxslider">
			<li><img src="images/tutor1.png" /></li>
		  <li><img src="images/tutor2.png" /></li>
		  <li><img src="images/tutor3.png" /></li>
		  <li><img src="images/tutor4.png" /></li>
		  <li><img src="images/tutor5.png" /></li>
		  
		</ul>
		</div>
		<div class="row pad-gap">
			<div class="col-sm-8 col-sm-offset-2">
				
				<div class="testimonial" id="testimonials">
					<h1>Testimonials</h1><br>
					<div class="carousel-wrap">
          <ul id="testimonial-list" class="clearfix">
            <li>
				<h4>
					<a target="parent" title="Said, 4rd grade student, Bayside, Queens, NY " href="said-4rd-grade-student.html">Said, 4th grade student, Bayside, Queens, NY </a>
				</h4>                            
				<p>"I don’t like school, but I like Joshua, my online math tutor. He knows when I get it and when I...<a target="parent" class="lof-readmore" title="Said, 4rd grade student, Bayside, Queens, NY " href="said-4rd-grade-student.html">Read more</a>
				</p>
            </li>
            <li>
				<h4>
					<a target="parent" title="Brian, 6th grade student, Parkchester, NY" href="brian-6th-grade-student.html">Brian, 6th grade student, Parkchester, NY</a>
				</h4>
				<p>"I never used to talk in class. I’m better now because of my English tutor. I don’t mind being...<a target="parent" class="lof-readmore" title="Brian, 6th grade student, Parkchester, NY" href="brian-6th-grade-student.html">Read more</a>
				</p>				
            </li>
            <li>
				<h4>
					<a target="parent" title="Victoria, high school student, Glen Cove, NY" href="victoria-high-school.html">Victoria, High school student, Glen Cove, NY</a>
				</h4>                            
				<p>"I’ve always been a good student, but I was falling behind in chemistry. My parents gave me a...<a target="parent" class="lof-readmore" title="Victoria, High school student, Glen Cove, NY" href="victoria-high-school.html">Read more</a>
				</p>
            </li>
            <li>
				<h4>
					<a target="parent" title="Liana, Prospect Heights, Brooklyn, NY" href="liana-prospect-heights.html">Liana, Prospect Heights, Brooklyn, NY</a>
				</h4>                            
				<p>"I failed the GED two times because I didn’t prepare enough. I got online English tutoring and...<a target="parent" class="lof-readmore" title="Liana, Prospect Heights, Brooklyn, NY" href="liana-prospect-heights.html">Read more</a>
				</p>
            </li>
			<li>
				<h4>
					<a target="parent" title="Delfina R., Pelham Manor, NY" href="delfina-r-pelham.html">Delfina R., Pelham Manor, NY</a>
				</h4>                            
				<p>"It was agony getting John to go for math tutoring. But after a while, it seemed like he was...<a target="parent" class="lof-readmore" title="Delfina R., Pelham Manor, NY" href="delfina-r-pelham.html">Read more</a>
				</p>
            </li>
			<li>
				<h4>
					<a target="parent" title="Dawn H., Greenwich Village, NY" href="dawn-h-greenwich.html">Dawn H., Greenwich Village, NY</a>
				</h4>                            
				<p>"My son’s science tutor bent over backwards to keep him engaged. He had my son observe and...<a target="parent" class="lof-readmore" title="Dawn H., Greenwich Village, NY" href="dawn-h-greenwich.html">Read more</a>
				</p>
            </li>
			<li>
				<h4>
					<a target="parent" title="Tutors on Wheels to anyone who loves to teach" href="tutor-on-wheels-to-anyone.html">Tutors on Wheels to anyone who loves to teach</a>
				</h4>                            
				<p>Working as a tutor with Tutors on Wheels is a great way to better reach out and help students...<a target="parent" class="lof-readmore" title="Tutors on Wheels to anyone who loves to teach" href="tutor-on-wheels-to-anyone.html">Read more</a>
				</p>
            </li>
			<li>
				<h4>
					<a target="parent" title="Thomas D. Cocks | Program Director" href="thomas-d-cocks-program.html">Thomas D. Cocks | Program Director</a>
				</h4>                            
				<p>We are a child welfare agency that supports families and strengthens them in the community. For over...<a target="parent" class="lof-readmore" title="Thomas D. Cocks | Program Director" href="thomas-d-cocks-program.html">Read more</a>
				</p>
            </li>
			
          </ul><!-- @end #testimonial-list -->
        </div><!-- @end .carousel-wrap -->
				</div><br>
				<hr>
			</div>
			
		</div>
		<div class="row pad-gap">
			<div class="col-sm-12">
				<div class="col-sm-3 col-text">
					<img src="images/col1-img.png">
					<div class="head-text">
						<h4>ONLINE TUTORING</h4>
						<p style="color:#909090;font-size: 14px;text-align:justify;">Greetings! Welcome to Tutors on Wheels online tutoring program! This exciting new method of instruction can be taken in the comfort of your own home.</p>
						<a href="online-tutoring.html" class="readmore">Read More</a>
					</div>
				</div>
				<div class="col-sm-3 col-text">
					<img src="images/col2-img.png">
					<div class="head-text">
						<h4>LEARNING CENTER</h4>
						<p style="color:#909090;font-size: 14px;text-align:justify;">At the Tutors on Wheels Learning Center, your child will receive the highest-quality one-to-one instruction available today. We will help your child develop stronger academic skills.</p>
						<a href="our-learning-center.html" class="readmore">Read More</a>
					</div>
				</div>
				<div class="col-sm-3 col-text">
					<img src="images/col3-img.png">
					<div class="head-text">
						<h4>AT HOME</h4>
						<p style="color:#909090;font-size: 14px;text-align:justify;">We offer one-on-one at home tutoring instruction for grades K-12. Our tutors are highly trained in helping your child succeed in todays demanding educational enviroment.</p>
						<a href="at-home-tutoring.html" class="readmore">Read More</a>
					</div>
				</div>
				<div class="col-sm-3 col-text">
					<img src="images/col4-img.png">
					<div class="head-text">
						<h4>HOME SCHOOLING</h4>
						<p style="color:#909090;font-size: 14px;text-align:justify;">Our certified tutors are able to home tutor from grades K-12. The process is simple. After signing up and choosing your package. You will be connected with our tutor at specified times.</p>
						<a href="home-school-tutoring.html" class="readmore">Read More</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row pad-gap">
			<div class="col-sm-8 col-sm-offset-2">
				<hr>
			</div>
		</div>
		<div class="row pad-gap">
			<div class="col-sm-12">
				<div class="col-sm-4 div1">
					<a href="online-tutoring.html"><img src="images/online-tutorial.png" style="width:100%;max-width:265px;"></a><br><br>
					<a href="hire-a-tutor.html"><img src="images/hire-tutorial.png" style="width:100%;max-width:265px;"></a><br>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="buy-now-butt">
						<input name="cmd" value="_s-xclick" type="hidden">
						<input name="hosted_button_id" value="LR4YSL8RPLWDN" type="hidden">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
						<input src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" name="submit" alt="PayPal - The safer, easier way to pay online!" border="0" type="image">
						<img alt="" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" style="display: block; margin-left: 10px; margin-right: auto;" border="0" height="1" width="1"><br>
					</form>
					
				</div>
				
				<div class="col-sm-4 div3">
					<span style="font-size:20px;font-weight:bolder;">RECENT NEWS!</span><br><br>
					<marquee onmouseover="this.stop();" onmouseout="this.start();" direction="up" scrollamount="1" style="width:100%;height:200px;">
						<p><a href="report-cards.html" title="" style="text-decoration: none; color: #4d4d4f; font-weight: bold;" target="blank" onclick="gbar.qs(this)">Report Cards, State Tests, SAT’s and ACT’s Are Coming</a></p>Now is the time to consider a personalized tutoring program to help your student improve his or her academic performance....<br><br>

						<p><a href="tow-online-tutoring.html" title="" style="text-decoration: none; color: #4d4d4f; font-weight: bold;" target="blank" onclick="gbar.qs(this)">I Ready Diagnostic Assessments And Prescriptive Programs</a></p>Tutors on Wheels now employs the I Ready Online Diagnostic Assessment to measure our students’ academic performance and needs...<br>

						<p><a href="at-home-tutoring.html" title="" style="text-decoration: none; color: #4d4d4f; font-weight: bold;" target="blank" onclick="gbar.qs(this)">Tutors On Wheels Online Tutoring Program</a></p>Tutors on Wheels is proud to introduce its new Online Tutoring Program. This new program utilizes white board technology...<br>

						<p><a href="http://blogs.edweek.org/teachers/teaching_ahead" style="text-decoration: none; color: #4d4d4f; font-weight: bold;" target="blank" onclick="gbar.qs(this)">If We're Serious About the Common Core, Then Put It First</a></p>By Carl Finer <a href="http://blogs.edweek.org/teachers/teaching_ahead" style="color:blue;text-decoration:none;">http://blogs.edweek.org/teachers/teaching_ahead</a> on February 18, 2014 12:10 PM <br>
					</marquee>
				</div>
			</div>
		</div>
		
    </div> <!-- /container -->
	<footer class="container">
			<div class="col-sm-8 footer-left">
			<ul class="footer-nav">
				<li class="active"><a href="faq.html">FAQ's</a></li>
				<li><a href="affiliations.html">Affiliations</a></li>
				<li><a href="press-releases.html">Press Releases</a></li>
				<li><a href="education-news.html">Education News</a></li>
				 <li><a href="online-resources.html">Resources</a>
				 <li><a href="privacy-policy.html">Privacy Policy</a></li>
				<li><a href="tutoring-costs.html">Tutoring Costs</a></li></ul>
			</ul>
			</div>
			<div class="col-sm-4">
				<ul class="footer-nav1">
					<li><a href="#">Copyright &copy; 2014&nbsp;&nbsp;Tutors On Wheels. All rights reserved</a></li>
					
				</ul>
			</div>
		</footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    <script src="js/bootstrap.min.js"></script>
	<script type="text/javascript">
	
	var s5_qc_spam_text = document.getElementById("spambox").value;
 
function s5_qc_clearbody() {
if (document.getElementById("messagebox").value == "Your Message...") {
document.getElementById("messagebox").value="";
}
if (document.getElementById("namebox").value.length < 1) {
document.getElementById("namebox").value = "Name...";
}
if (document.getElementById("emailbox").value.length < 1) {
document.getElementById("emailbox").value = "Email...";
}
if (document.getElementById("subjectbox").value.length < 1) {
document.getElementById("subjectbox").value = "Subject...";
}
if (document.getElementById("spambox").value.length < 1) {
document.getElementById("spambox").value = s5_qc_spam_text;
}
}

		function s5_qc_clearname() {
if (document.getElementById("namebox").value == "Name...") {
document.getElementById("namebox").value="";
}
if (document.getElementById("messagebox").value.length < 1) {
document.getElementById("messagebox").value = "Your Message...";
}
if (document.getElementById("emailbox").value.length < 1) {
document.getElementById("emailbox").value = "Email...";
}
if (document.getElementById("subjectbox").value.length < 1) {
document.getElementById("subjectbox").value = "Subject...";
}
if (document.getElementById("spambox").value.length < 1) {
document.getElementById("spambox").value = s5_qc_spam_text;
}
}

function s5_qc_clearemail() {
if (document.getElementById("emailbox").value == "Email...") {
document.getElementById("emailbox").value="";
}
if (document.getElementById("namebox").value.length < 1) {
document.getElementById("namebox").value = "Name...";
}
if (document.getElementById("messagebox").value.length < 1) {
document.getElementById("messagebox").value = "Your Message...";
}
if (document.getElementById("subjectbox").value.length < 1) {
document.getElementById("subjectbox").value = "Subject...";
}
if (document.getElementById("spambox").value.length < 1) {
document.getElementById("spambox").value = s5_qc_spam_text;
}
}

function s5_qc_clearsubject() {
if (document.getElementById("subjectbox").value == "Subject...") {
document.getElementById("subjectbox").value="";
}
if (document.getElementById("namebox").value.length < 1) {
document.getElementById("namebox").value = "Name...";
}
if (document.getElementById("emailbox").value.length < 1) {
document.getElementById("emailbox").value = "Email...";
}
if (document.getElementById("messagebox").value.length < 1) {
document.getElementById("messagebox").value = "Your Message...";
}
if (document.getElementById("spambox").value.length < 1) {
document.getElementById("spambox").value = s5_qc_spam_text;
}
}

function s5_qc_clearspam() {
if (document.getElementById("spambox").value == s5_qc_spam_text) {
document.getElementById("spambox").value="";
}
if (document.getElementById("namebox").value.length < 1) {
document.getElementById("namebox").value = "Name...";
}
if (document.getElementById("emailbox").value.length < 1) {
document.getElementById("emailbox").value = "Email...";
}
if (document.getElementById("messagebox").value.length < 1) {
document.getElementById("messagebox").value = "Your Message...";
}
if (document.getElementById("subjectbox").value.length < 1) {
document.getElementById("subjectbox").value = "Subject...";
}
}

function s5_qc_isValidEmail(str_email) {
   if (str_email.indexOf(".") > 2 && str_email.indexOf("@") > 0) {
   alert('Form submission is successful - Thank you!');
   document.quick_contact.submit();
   }
   else {
   alert('Your email address is not valid, please check again - Thank you!');
   }
}

function s5_qc_submit() 
{

	if (document.getElementById("spambox").value == s5_qc_spam_text || document.getElementById("subjectbox").value == "Subject..." || document.getElementById("namebox").value == "Name..." || document.getElementById("emailbox").value == "Email..." || document.getElementById("messagebox").value == "Your Message...") 
	{
		alert('All fields are required, please complete the form - Thank you!');
		return false;
	}

	if (document.getElementById("spambox").value != "8678") {
	alert('Your spam verification answer is incorrect.');
	return false;
	}

	var s5_message_holder = document.getElementById("messagebox").value;
	var s5_first_message_char = s5_message_holder.charAt(0);
	var s5_second_message_char = s5_message_holder.charAt(1);
	var s5_third_message_char = s5_message_holder.charAt(2);
	var s5_fourth_message_char = s5_message_holder.charAt(3);

	if (s5_first_message_char == "<") {
	return false;
	}

	if (s5_first_message_char == "w" && s5_second_message_char == "w" && s5_third_message_char == "w") {
	return false;
	}

	if (s5_first_message_char == "h" && s5_second_message_char == "t" && s5_third_message_char == "t") {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	if (s5_message_holder.indexOf("s5_qc_null") >= 0) {
	return false;
	}

	else {
	document.getElementById("email_address").value = "info@tutorsonwheels.com";
	var email_str = document.getElementById("emailbox").value;
	s5_qc_isValidEmail(email_str);
	}
}



		</script>
	 </div>
	</body>
</html>


<?php 

if($_GET['contact']==1){

 $message = "\n Name : ".$_POST["namebox"]."\n Message : ".$_POST["messagebox"]."\n Subject : ".$_POST["subjectbox"]."\n Email Address : ".$_POST["emailbox"];
    
    $from=$_POST["emailbox"];
    $subject="Quick contact from ".$_POST["subjectbox"] ;
    // message lines should not exceed 70 characters (PHP rule), so wrap it
    $message = wordwrap($message, 70);
    // send mail
    mail("info@tutorsonwheels.com",$subject,$message,"From: $from\n");
	
    echo '<script> alert("Thank you for your inquiry, a team member will be in touch shortly!"); </script>' ;
	
}
?>
