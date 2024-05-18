<?php 

  include "includes/site/data.php";
  include "includes/site/utils.php";

$projects = $social_links = null;

// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

// Query parameters
$query = '{
  "projects": *[_type == "projects"][0..20]{
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
	<div class="preloader"></div>

  <!-- Main Header-->
 <?php include "includes/site/header.php" ?>
  <!--End Main Header -->

	<!-- Start main-content -->
	<section class="page-title" style="background-image: url(images/background/page-title-bg.png);">
		<div class="auto-container">
			<div class="title-outer text-center">
				<h1 class="title">Projects</h1>
				<ul class="page-breadcrumb">
					<li><a href="index.php">Home</a></li>
					<li>Projects</li>
				</ul>
			</div>
		</div>
	</section>
	<!-- end main-content -->
    
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
                                    <a href="/projects/<?= $project["slug"] ?>"><img
                                            src="images/resource/projec1-2.png" alt="">
                                    </a>
                                </figure>
                            </div>
                            <div class="content-box">
                                <span><?= $project["category"] ?></span>
                                <h6 class="title"><a href="/projects/<?= $project["slug"] ?>"><?= $project["title"] ?></a></h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
      <!-- project-block -->
              
            </div>
        </section>
        <!-- End project section -->
              
            </div>
        </section>
        <!-- End project section -->
              
            </div>
        </section>
        <!-- End project section -->

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
<script src="js/script.js"></script>
</body>
</html>