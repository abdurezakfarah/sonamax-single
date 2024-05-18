<?php 

  include "includes/site/data.php";
  include "includes/site/utils.php";

  $tag =  $_GET["tag"] ?? "";

  $tag_filter = $tag ? "&& '$tag' in tags[]->.slug.current" : "";



$posts = $social_links = null;

// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

// Query parameters
$query = '{
  "posts": *[_type == "post"' . $tag_filter. '] | order(publishedAt desc) {
     title,
    "slug": slug.current,
    publishedAt,
    category,
    "coverImage": coverImage.asset->url
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

  $posts = $result["posts"];
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
				<h1 class="title">Blogs</h1>
				<ul class="page-breadcrumb">
					<li><a href="/">Home</a></li>
					<li>blogs <?= !empty($tag_filter) ? "#$tag" : "" ?></li>
				</ul>
			</div>
		</div>
	</section>
	<!-- end main-content -->

	<!-- News Section Two -->
  <section class="news-section-two pt-120 pb-90">
    <div class="auto-container">
      <?php if(isset($posts)): ?>
        <div class="row">
          <?php if(empty($posts)): ?>
        <h2 class="center">No posts yet!</h2>
    <?php else: ?>
        <?php foreach($posts as $post): ?>
           <div class="news-block-two col-lg-4 col-md-6">
                <div class="inner-box">
                    <div class="image-box">
                        <figure class="image">
                            <a href="news-details.html">
                                <img src="<?= $post["coverImage"] ?>" alt="">
                            </a>
                        </figure>
                    </div>
                    <div class="content-box">
                        <ul class="post">
                            <li>
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                    viewBox="0 0 14 14" fill="none">
                                    <path opacity="0.8"
                                        d="M4.9 0V1.4H9.1V0H10.5V1.4H13.3C13.6866 1.4 14 1.7134 14 2.1V13.3C14 13.6866 13.6866 14 13.3 14H0.7C0.313404 14 0 13.6866 0 13.3V2.1C0 1.7134 0.313404 1.4 0.7 1.4H3.5V0H4.9ZM12.6 7H1.4V12.6H12.6V7ZM3.5 2.8H1.4V5.6H12.6V2.8H10.5V4.2H9.1V2.8H4.9V4.2H3.5V2.8Z"
                                        fill="#F94A29" />
                                </svg><?= formatDate($post["publishedAt"]) ?>
                            </li>
                            <li>
                                <?php if(isset($post["category"])): ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="14"
                                    viewBox="0 0 10 14" fill="none">
                                    <path opacity="0.8"
                                        d="M0.625 0H9.375C9.72019 0 10 0.303636 10 0.678183V13.6608C10 13.8481 9.86006 14 9.6875 14C9.62881 14 9.57125 13.982 9.5215 13.9481L5 10.8722L0.478494 13.9481C0.332269 14.0476 0.139412 13.9997 0.0477311 13.841C0.0165436 13.787 0 13.7246 0 13.6608V0.678183C0 0.303636 0.279825 0 0.625 0ZM8.75 1.35637H1.25V11.8224L5 9.27123L8.75 11.8224V1.35637Z"
                                        fill="#F94A29" />
                                </svg><?= $post["category"] ?? '' ?>
                                <?php endif; ?>
                            </li>
                        </ul>
                        <h6 class="title">
                            <a href="/blog/<?= $post["slug"] ?>">
                                <?= $post["title"] ?>
                            </a>
                        </h6>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
         
        </div>
      <?php endif; ?>
    </div>
  </section>
	<!--End News Section -->
  
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