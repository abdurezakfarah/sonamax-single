<?php 
  include "includes/site/data.php"; 
  include "includes/site/utils.php"; 

 $social_links;
 $services;

// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

// Query parameters
$query = '{
  "settings": *[_type == "settings"][0]{
    socialLinks[link in [*].links] {
    url,
    "icon": icon.name
  }
},
 "services": *[_type == "services"][0..8]{
     title,
     description,
    "slug": slug.current,
  },
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
    echo 'Error: ' . curl_error($ch);
} else {
    // Output the response
  $result = json_decode($response, true)["result"];
   $social_links = $result["settings"]["socialLinks"];
    $services = $result["services"];
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
	<div class="preloader"></div>

	<!-- Main Header-->

<?php include "includes/site/header.php"; ?>
	<!--End Main Header -->

	<!-- Start main-content -->
	<section class="page-title" style="background-image: url(images/background/page-title-bg.png);">
		<div class="auto-container">
			<div class="title-outer text-center">
				<h1 class="title">Services</h1>
				<ul class="page-breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li>Services</li>
				</ul>
			</div>
		</div>
	</section>
	<!-- end main-content -->

	<!-- Service section Four -->
  <? if(isset($services)): ?>
  <section class="service-section-two">
    <div class="auto-container">
       <!-- service-section -->
        <section id="services" class="service-section">
            <div class="auto-container">
                <div class="sec-title text-center">
                    <h2>WE WILL PROVIDE YOUTHE <br>BEST SERVICE</h2>
                </div>
                <div class="row">
                    <!-- service-block -->
                    <?php foreach($services as $service): ?>
                    <div class="service-block col-lg-3 col-sm-6">
                        <div class="inner-box">
                            <!-- <div class="icon-box"><i class="<?= $service["icon"] ?>"></i></div> -->
                            <div class="content-box">
                                <h3 class="title"><a href="/services/<?= $service["slug"] ?>"><?= $service["title"] ?></a></h3>
                                <div class="text"><?= $service["description"] ?></div>
                                <a href="/services/<?= $service["slug"] ?>" data-animation-in="fadeInUp" data-delay-in="0.4"
                                    class="theme-btn ser-btn">Learn more <i
                                        class="flaticon-arrow-pointing-to-right btn-icon ms-1"></i></a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <!-- End service-section -->
      </div>
    </section>
    <? endif; ?>
	<!-- end service section fore -->

	<!-- Main Footer -->

<?php include "includes/site/footer.php"; ?>
	<!--End Main Footer -->

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
	<script src="js/swiper.min.js"></script>
	<script src="js/owl.js"></script>
	<script src="js/script.js"></script>
</body>

</html>