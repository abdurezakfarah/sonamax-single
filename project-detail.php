<?php
 include "includes/site/utils.php";
 include "includes/site/data.php";

 $slug =  $_GET["slug"] ?? "";


// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

$query = '{
  "project": *[_type == "projects" && slug.current == "' . $slug . '"]{
    "image": coverImage.asset->url,
    date,
    client,
    website,
    location,
    overview,
    "slug": slug.current,
  }[0],
  "posts": *[_type == "post"][0..12]{
     title,
    "slug": slug.current,
    publishedAt,
    category,
    "coverImage": coverImage.asset->url
  },
  "projects": *[_type == "projects"][0..5] {
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
}';

$projects;
$project;
$content;
$social_links;
$posts;

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
  echo 'Error: ' . curl_error($ch);
} else {
  $result = json_decode($response, true)["result"];
  $project = $result["project"];
  $projects = $result["projects"];
  $posts = $result["posts"];
    $social_links = $result["settings"]["socialLinks"];

  // Check if project exists and overview is set
  if (isset($project) && isset($project["overview"]) && !empty($project["overview"])) {
    $portableText = $project["overview"];

    // Prepare secondary request data (assuming JSON body)
    $postData = json_encode(array(
      "portableText" => $portableText,
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
	    $content = $secondaryResult["html"];
      
	
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
				<h1 class="title">Project Details</h1>
				<ul class="page-breadcrumb">
						<li>
							<a href="/">Home</a>
						</li>
						<li>
							<a href="/projects">
								Projects
							</a>
						</li>
						<li><?= $project["slug"] ?></li>
					</ul>
			</div>
		</div>
	</section>
	<!-- end main-content -->

	<!--Project Details Start-->
	<section class="project-details">
		<div class="container">
			<div class="row">
				<div class="col-xl-12">
					<div class="project-details__top">
						<div class="project-details__img center"> 
              <img src="<?= $project["image"] ?>" alt=""> </div>
					</div>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="col-xl-10">
					<div class="project-details__content-right">
						<div class="project-details__details-box pb-25">
							<div class="row">
								<div class="col-6 col-md-3">
									<p class="project-details__client">Date</p>
									<h4 class="project-details__name"><?= formatDate($project["date"]) ?></h4>
								</div>
								<div class="col-6 col-md-3">
									<p class="project-details__client">Client</p>
									<h4 class="project-details__name"><?= $project["client"] ?></h4>
								</div>
								<div class="col-6 col-md-3">
									<p class="project-details__client">Website</p>
									<h4 class="project-details__name"><?= $project["website"] ?? "N/A" ?></h4>
								</div>
								<div class="col-6 col-md-3">
									<p class="project-details__client">Location</p>
									<h4 class="project-details__name"><?= $project["location"] ?></h4>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="project-details__content-left richtext">
			    <?= $content ?>
					</div>
				</div>
			</div>
			
		</div>
	</section>
	<!--Project Details End-->

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

     <!-- news-section -->
        <section id="news" class="news-section">
            <div class="auto-container">
                <div class="sec-title text-center">
                    <h2>Latest from the blog</h2>
                </div>
               <div class="row">

    <?php if(empty($posts)): ?>
        <h2 class="center">No posts yet!</h2>
    <?php else: ?>
        <?php foreach($posts as $post): ?>
           <div class="news-block col-lg-4 col-md-6">
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

        </section>
  
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
<script src="js/slick.min.js"></script>
<script src="js/slick-animation.min.js"></script>
<script src="js/wow.js"></script>
<script src="js/appear.js"></script>
<script src="js/script.js"></script>
</body>
</html>