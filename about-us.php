<?php 
  include "includes/site/data.php";
  include "includes/site/utils.php";
  
$projects;
$services;
$social_links;
$about_us;

// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

// Query parameters
$query = '{
  "about-us": *[_type == "about-us"][0] {
      body
    },
  "projects": *[_type == "projects"][0..5]{
     title,
     category,
    "slug": slug.current,
    "image": coverImage.asset->url
  },
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

  $projects = $result["projects"];
  $services = $result["services"];
  $social_links = $result["settings"]["socialLinks"];
  $about_us = $result["about-us"]["body"];

   if (isset($about_us) && !empty($about_us)) {

    // Prepare secondary request data (assuming JSON body)
    $postData = json_encode(array(
      "portableText" => $about_us,
    ));

    // Initialize secondary cURL session for POST request
    $secondaryCh = curl_init("https://blog.sonamaxmarketing.com/api/to-html");
    curl_setopt($secondaryCh, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($secondaryCh, CURLOPT_POST, true);
    curl_setopt($secondaryCh, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($secondaryCh, CURLOPT_HTTPHEADER, array('Content-Type: application/json')); // Set content type

    // Execute secondary request and get response
    $secondaryResponse = curl_exec($secondaryCh);

    // Check if secondary request was successful (optional, handle response)
    if (curl_errno($secondaryCh)) {
      echo 'Error sending secondary request: ' . curl_error($secondaryCh);
    } else {
      // Process the response from the secondary request (if needed)
      $secondaryResult = json_decode($secondaryResponse, true);
      // ... handle secondary result ... (e.g., process "html" if returned)
	    $about_us = $secondaryResult["html"];
    }

    // Close secondary cURL session
    curl_close($secondaryCh);
  } else {
    echo "Project not found or overview is empty.";
  }
}

// Close cURL session
curl_close($ch);
  
  ?>
<!DOCTYPE html>
<html lang="en">


<?php include "includes/site/head.php" ?>



<body>

<div class="page-wrapper">

	<!-- Preloader -->
	<div class="preloader"></div>
    
  <!-- Main Header-->
 <?php include "includes/site/header.php" ?>
  <!--End Main Header -->

	<!-- Start main-content -->
	<section class="page-title" style="background-image: url(images/background/page-title-bg.png);">
		<div class="auto-container">
			<div class="title-outer text-center">
				<h1 class="title">About Us</h1>
				<ul class="page-breadcrumb">
					<li><a href="/">Home</a></li>
					<li>About Us</li>
				</ul>
			</div>
		</div>
	</section>
	<!-- end main-content -->

        <div class="about-us__content richtext">
			    <?= $about_us ?>
		</div>
		

<!-- about-section -->
        <section id="about" class="about-section">
            <div class="auto-container">
                <div class="row">
                    <!-- image-column -->
                    <div class="image-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="image-box">
                                <figure class="image overlay-anim"><img src="images/resource/about1-1.jpg" alt="">
                                </figure>
                                <div class="play-box">
                                    <figure class="image-2 overlay-anim"><img src="images/resource/about1-2.jpg" alt="">
                                    </figure>
                                    <a title="" href="https://www.youtube.com/watch?v=Fvae8nxzVz4"
                                        data-fancybox="gallery" data-caption="">
                                        <i class="icon fa fa-play"></i>
                                    </a>
                                </div>
                                <div class="exp-box">
                                    <div class="icon-box">
                                        <img src="images/resource/tv.png" alt="">
                                    </div>
                                    <h4 class="title">MARKETING SOLUTION</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- content-column -->
                    <div class="content-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="sec-title light">
                                <h2>Get the better experience & grow your business with us</h2>
                                <div class="text">Elevate your brand, expand your reach, and thrive in today's digital
                                    landscape with our tailored solutions. Experience the difference and watch your
                                    business soar. Partner with us for success!</div>
                            </div>
                            <div class="inner-box">
                                <div class="content-box">
                                    <span>5+</span>
                                    <h6 class="title">Years of experience</h6>
                                </div>
                                <div class="content-box">
                                    <span>500+</span>
                                    <h6 class="title">Successful project </h6>
                                </div>
                                <div class="content-box">
                                    <span>1200+</span>
                                    <h6 class="title">Happy customer </h6>
                                </div>
                            </div>
                            <div class="btn-box">
                                <a href="/about-us" class="theme-btn-v2">Get started <i
                                        class="btn-icon fa-sharp far fa-arrow-right ml-10 font-size-18"></i></a>
                                <div class="contact-btn">
                                    <i class="flaticon-telephone-1"></i>
                                    <span>Call us</span>
                                    <h6 class="title">
                                        <?= $site["phone"] ?>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End about-section -->


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

    <!-- project-section -->
        <section id="projects" class="project-section">
            <div class="auto-container">
                <div class="sec-title">
                    <h2>Recently <br>Completed Projects</h2>
                    <div class="text">Transforming Ideas into Digital Success: Our Latest Achievements in Online Branding</div>
                </div>
                <div class="slider-btn">
                    <button class="prev-btn"><span><i
                                class="flaticon-arrow-pointing-to-right btn-icon"></i></span></button>
                    <button class="next-btn"><span><i
                                class="flaticon-arrow-pointing-to-right btn-icon"></i></span></button>
                </div>
            </div>
            <div class="row project-slider">
              <? if(isset($projects)): ?>

                <?php foreach($projects as $project): ?>
                    <div class="project-block col-lg-3 col-md-6">
                        <div class="inner-box">
                            <div class="image-box">
                                <figure class="image overlay-anim">
                                    <a href="/projects/<?= $project["slug"] ?>">
                                        <img
                                            src="<?= $project["image"] ?>" alt=""
                                            >
                                    </a></figure>
                                <figure class="image-2">
                                    <a href="projects/<?= $project["slug"] ?>">
                                    <img
                                        src="images/resource/projec1-2.png" alt="">
                                    </a>
                                </figure>
                            </div>
                            <div class="content-box">
                                <span><?= $project["category"] ?></span>
                                <h6 class="title"><a href="project-detail.php?slug=<?= $project["slug"] ?>"><?= $project["title"] ?></a></h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <? endif; ?>
                <!-- project-block -->
              
            </div>
        </section>
        <!-- End project section -->

   <!-- pricing-section -->
        <section class="pricing-section">
            <div class="auto-container">
                <div class="sec-title text-center">
                    <h2>Website Design & <br>development Packages</h2>
                </div>
                <div class="row">
                    <!-- pricing-block -->
                    <?php foreach($prices as $price): ?>

                        <div class="pricing-block col-lg-4 col-md-6 col-sm-12">
                            <div class="inner-box">
                                <div class="upper-box">
                                    <span><?= $price["title"] ?></span>
                                    <div class="content-box">
                                        <h2 class="title"><?= $price["currency"] ?><?= $price["price"] ?></h2>
                                        <div class="text">per user <br>per month</div>
                                    </div>
                                </div>
                                <div class="text v2">What you'll get</div>
                                <div class="list-sec">
                                    <ul class="list">
                                        <?php foreach($price["features"] as $feature):?>
                                         <li><i class="fa-solid fa-circle-check"></i><?= $feature ?></li>
                                        <?php endforeach; ?>
                                        <!-- <li><i class="fa-solid fa-circle-check"></i>Landing page (25 pages)</li> -->
                                        <!-- <li><i class="fa-solid fa-circle-check"></i>HTML+CSS design (30 pages)</li> -->
                                        <!-- <li><i class="fa-solid fa-circle-check"></i>Social Media Marketing</li> -->
                                        <!-- <li><i class="fa-solid fa-circle-check"></i>Online support (24/7)</li> -->
                                    </ul>
                                    <a href="pricing" class="theme-btn-v2">Choose Package<i
                                            class="flaticon-arrow-pointing-to-right btn-icon ml-10"></i></a>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </section>
        <!-- End pricing-section -->

  <!-- Main Footer -->
         <?php include "includes/site/footer.php" ?>

  <!--End Main Footer -->

</div>
<!-- End Page Wrapper -->

<!-- Scroll To Top -->
<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>

<script src="js/jquery.js"></script>
<script src="js/popper.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/slick.min.js"></script>
<script src="js/slick-animation.min.js"></script>
<script src="js/jquery.fancybox.js"></script>
<script src="js/jquery-ui.js"></script>
<script src="js/wow.js"></script>
<script src="js/appear.js"></script>
<script src="js/script.js"></script>
</body>
</html>