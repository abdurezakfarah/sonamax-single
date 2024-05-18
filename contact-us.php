<?php 
 include "includes/site/data.php";
 include "includes/site/utils.php";

$social_links;

// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

// Query parameters
$query = '{
  "settings": *[_type == "settings"][0]{
    socialLinks[link in [*].links] {
    url,
    "icon": icon.name
  }
}
}
';

// Parameters for the GET request
$params = array(
    'query' => $query,
    // Add any other parameters here if needed
);

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and get the response
$response = curl_exec($ch);

// Check if the request was successful
if ($response === false) {
    // Handle the error
    echo 'Error: ';
} else {
    // Output the response
  $result = json_decode($response, true)["result"];
  $social_links = $result["settings"]["socialLinks"];
}

// Close cURL session
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="en">

<?php include "includes/site/head.php"; ?>

<body>

<div class="page-wrapper">

	<!-- Preloader -->
	<!-- <div class="preloader"></div> -->
    
  <!-- Main Header-->
      <?php include "includes/site/header.php"; ?>
  <!--End Main Header -->

	<!-- Start main-content -->
	<section class="page-title" style="background-image: url(images/background/page-title-bg.png);">
		<div class="auto-container">
			<div class="title-outer text-center">
				<h1 class="title">Contact Us</h1>
				<ul class="page-breadcrumb">
					<li><a href="/">Home</a></li>
					<li>Contact</li>
				</ul>
			</div>
		</div>
	</section>
	<!-- end main-content -->

	<!--Contact Details Start-->
	<section class="contact-details">
		<div class="container ">
			<div class="row">
				<div class="col-xl-7 col-lg-6">
					<div class="sec-title">
						<span class="sub-title">Send us email</span>
						<h2>Feel free to write</h2>
					</div>
					<!-- Contact Form -->
					<form 
            id="contact_form" 
            name="contact_form"  
            action="includes/sendmail.php" 
            method="post"
            >
						<div class="row">
							<div class="col-sm-6">
								<div class="mb-3">
									<input 
                    name="form_name" 
                    class="form-control" 
                    type="text" 
                    placeholder="First Name"
                    >
								</div>
							</div>
							<div class="col-sm-6">
								<div class="mb-3">
									<input 
                    name="form_email" 
                    class="form-control required email" 
                    type="email" placeholder="Enter Email">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="mb-3">
									<input name="form_subject" class="form-control required" type="text" placeholder="Enter Subject">
								</div>
							</div>
							<div class="col-sm-6">
								<div class="mb-3">
									<input name="form_phone" class="form-control" type="tel" placeholder="Enter Phone">
								</div>
							</div>
						</div>
						<div class="mb-3">
							<textarea name="form_message" class="form-control required" rows="7" placeholder="Enter Message"></textarea>
						</div>
						<div class="mb-5">
							<input name="form_botcheck" class="form-control" type="hidden" value="" />
							<button 
								type="submit" 
								class="theme-btn btn-style-one" 
								data-loading-text="Please wait..."
								name="submit"
								>
								<span class="btn-title">Send</span>
							</button>
							<!-- <button type="reset" class="theme-btn btn-style-one bg-theme-color5"><span class="btn-title">Reset</span></button> -->
						</div>
					</form>
					<!-- Contact Form Validation-->
				</div>
				<div class="col-xl-5 col-lg-6">
					<div class="contact-details__right">
						<div class="sec-title">
							<span class="sub-title">Need any help?</span>
							<h2>Get in touch with us</h2>
							<div class="text">Questions or need assistance? Contact us today. Our dedicated team is ready to help you with any inquiries you may have. Whether you need more information about our services or have specific needs, we're here to assist you every step of the way</div>
						</div>
						<ul class="list-unstyled contact-details__info">
							<li>
								<div class="icon bg-theme-color2">
									<span class="lnr-icon-phone-plus"></span>
								</div>
								<div class="text">
									<h6>Have any question?</h6>
									<a href="tel:<?= $site["phone"] ?>"><span>Free </span><?= $site["phone"] ?></a>
								</div>
							</li>
							<li>
								<div class="icon">
									<span class="lnr-icon-envelope1"></span>
								</div>
								<div class="text">
									<h6>Write email</h6>
									<a href="mailto:<?= $site["email"] ?>"><?= $site["email"] ?></a>
								</div>
							</li>
							<li>
								<div class="icon">
									<span class="lnr-icon-location"></span>
								</div>
								<div class="text">
									<h6>Visit anytime</h6>
									<span><?= $site["location"] ?></span>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--Contact Details End-->
  
  <?php include "includes/site/footer.php" ?>
</div><!-- End Page Wrapper -->


<!-- Scroll To Top -->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>


<script src="js/jquery.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/wow.js"></script>
<script src="js/appear.js"></script>
<script src="js/select2.min.js"></script>
<script src="js/script.js"></script>

<!-- form submit -->
<script src="js/jquery.validate.min.js"></script>
<script src="js/jquery.form.min.js"></script>
<script>
	(function($) {
		$("#contact_form").validate({
			submitHandler: function(form) {
				var form_btn = $(form).find('button[type="submit"]');
				var form_result_div = '#form-result';
				$(form_result_div).remove();
				form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
				var form_btn_old_msg = form_btn.html();
				form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
				$(form).ajaxSubmit({
					dataType:  'json',
					success: function(data) {
						if( data.status == 'true' ) {
							$(form).find('.form-control').val('');
						}
						form_btn.prop('disabled', false).html(form_btn_old_msg);
						$(form_result_div).html(data.message).fadeIn('slow');
						setTimeout(function(){ $(form_result_div).fadeOut('slow') }, 6000);
					}
				});
			}
		});
	})(jQuery);
</script>
</body>
</html>