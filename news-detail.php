 <?php 
  include "includes/site/data.php"; 
  include "includes/site/utils.php"; 

  $slug =  $_GET["slug"] ?? "";

 $social_links;
 $post;
 $latest_posts;
 $content;

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
 "post": *[_type == "post" && slug.current == "' . $slug . '" ][0] {
    title,
    "slug": slug.current,
    "coverImage": coverImage.asset->url,
    publishedAt,
    excerpt,
    _id,
    "headings": body[length(style) == 2 && string::startsWith(style, "h")],
    body,
    tags[]-> {
      name,
	  "slug": slug.current
    },
    author[]->{
    name,
    twitter
    },
    "plainText": pt::text(body),
    "keywords": string::split(keywords, ",")
  },
   "latestPosts": *[_type == "post" && slug.current != "' . $slug . '" ] | order(publishedAt desc)[0..12] {
     title,
    "slug": slug.current,
    "coverImage": coverImage.asset->url
  }
}';

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
  $result = json_decode($response, true)["result"];
    $social_links = $result["settings"]["socialLinks"];
  $post = $result["post"];
  $latest_posts = $result["latestPosts"];
  $content = $post["body"];

    if (isset($post) && isset($content) && !empty($content)) {

    // Prepare secondary request data (assuming JSON body)
    $postData = json_encode(array(
      "portableText" => $content,
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
    echo "Post not found or overview is empty.";
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
					<h1 class="title">Blog</h1>
					<ul class="page-breadcrumb">
						<li>
							<a href="/">Home</a>
						</li>
						<li>
							<a href="/blog">
								blog
							</a>
						</li>
						<li><?= $post["slug"] ?></li>
					</ul>
				</div>
			</div>
		</section>
		<!-- end main-content -->

		<!--Blog Details Start-->
		<section class="blog-details">
			<div class="container blog-wrapper">
				<div class="row">
					<div class="col-xl-8 col-lg-7">
						<div class="blog-details__left">
							<div class="blog-details__img">
								<img src="<?= $post["coverImage"] ?>" alt="<?= $post["title"] ?>">
								<!-- <div class="blog-details__date">
									<span class="day">28</span>
									<span class="month">Aug</span>
								</div> -->
							</div>
							<div class="blog-details__content">
								<!-- <ul class="list-unstyled blog-details__meta">
									<li><a href="news-details.html"><i class="fas fa-user-circle"></i> Admin</a> </li>
									<li><a href="news-details.html"><i class="fas fa-comments"></i> 02
											Comments</a>
									</li>
								</ul> -->
								<div>
									By: <a class="user-links style-two" href="https://www.twitter.com/<?= $post["author"][0]["twitter"] ?>"><?= $post["author"][0]["name"] ?></a> <span> on </span> <span><?= formatDate($post["publishedAt"]) ?>.</span>
								</div>
								<h3 class="blog-details__title"><?= $post["title"] ?></h3>
								<div c;ass="richtext">

									<?= $content ?>
								</div>
							</div>
							<div class="blog-details__bottom">
								<p class="blog-details__tags"> 
									<span>Tags</span> 

								<?php if(isset($post["tags"]) && !empty($post["tags"])): ?>

									<?php foreach( $post["tags"] as $tag): ?>
											<a href="blog?tag=<?= $tag["slug"] ?>">
												<?= $tag["name"] ?>
											</a> 
											
									<?php endforeach; ?>

								<?php endif; ?>
								</p>
								<!-- <div class="blog-details__social-list"> 
									<a href="news-details.html">
										<i class="fab fa-twitter"></i>
									</a> 
									<a href="news-details.html">
										<i class="fab fa-facebook"></i>
									</a> 
									<a href="news-details.html">
										<i class="fab fa-pinterest-p"></i>
									</a> 
									<a href="news-details.html">
										<i class="fab fa-instagram"></i>
									</a> 
								</div> -->
							</div>
							<!-- <div class="nav-links">
								<div class="prev">
									<a href="news-details.html" rel="prev">Bring to the table win-win survival strategies</a>
								</div>
								<div class="next">
									<a href="news-details.html" rel="next">How to lead a healthy &amp; well-balanced life</a>
								</div>
							</div> -->
							<!-- <div class="comment-one">
								<h3 class="comment-one__title">2 Comments</h3>
								<div class="comment-one__single">
									<div class="comment-one__image"> <img src="images/resource/testi2-1.png" alt=""> </div>
									<div class="comment-one__content">
										<h3>Kevin Martin</h3>
										<p>Mauris non dignissim purus, ac commodo diam. Donec sit amet lacinia nulla.
											Aliquam quis purus in justo pulvinar tempor. Aliquam tellus nulla,
											sollicitudin at euismod.
										</p>
										<a href="news-details.html" class="theme-btn btn-style-one comment-one__btn"><span class="btn-title">Reply</span></a>
									</div>
								</div>
								<div class="comment-one__single">
									<div class="comment-one__image"> <img src="images/resource/testi2-2.png" alt=""> </div>
									<div class="comment-one__content">
										<h3>Sarah Albert</h3>
										<p>Mauris non dignissim purus, ac commodo diam. Donec sit amet lacinia nulla.
											Aliquam quis purus in justo pulvinar tempor. Aliquam tellus nulla,
											sollicitudin at euismod.
										</p>
										<a href="news-details.html" class="theme-btn btn-style-one comment-one__btn"><span class="btn-title">Reply</span></a>
									</div>
								</div>
								<div class="comment-form">
									<h3 class="comment-form__title">Leave a Comment</h3>
									<form id="contact_form" name="contact_form" class="" action="includes/sendmail.php" method="post">
										<div class="row">
											<div class="col-sm-6">
												<div class="mb-3">
													<input name="form_name" class="form-control" type="text" placeholder="Enter Name">
												</div>
											</div>
											<div class="col-sm-6">
												<div class="mb-3">
													<input name="form_email" class="form-control required email" type="email" placeholder="Enter Email">
												</div>
											</div>
										</div>
										<div class="mb-3">
											<textarea name="form_message" class="form-control required" rows="5" placeholder="Enter Message"></textarea>
										</div>
										<div class="mb-3">
											<input name="form_botcheck" class="form-control" type="hidden" value="" />
											<button type="submit" class="theme-btn btn-style-one" data-loading-text="Please wait..."><span class="btn-title">Submit Comment</span></button>
										</div>
									</form>
								</div>  
							</div> -->
						
						</div>
					</div>
					<div class="col-xl-4 col-lg-5">
						<div class="sidebar">
							<!-- <div class="sidebar__single sidebar__search">
								<form action="#" class="sidebar__search-form">
									<input type="search" placeholder="Search here">
									<button type="submit"><i class="lnr-icon-search"></i></button>
								</form>
							</div> -->
							<div class="sidebar__single sidebar__post">
								<?php if(isset($latest_posts) && !empty($latest_posts)): ?>
								<h3 class="sidebar__title">Latest Posts</h3>
									<ul class="sidebar__post-list list-unstyled">
										<?php foreach($latest_posts as $latest_post): ?>
											<li>
											<div class="sidebar__post-image"> 
												<img src="<?= $latest_post["coverImage"] ?>" alt=""> 
											</div>
											<div class="sidebar__post-content">
												<h3> 
													<!-- <span class="sidebar__post-content-meta">
													<i class="fas fa-user-circle"></i>
													Admin
												   </span>  -->
													<a href="/blog/<?= $latest_post["slug"] ?>" class="line-clamp-2">
														<?= $latest_post["title"] ?>
													</a>
												</h3>
											</div>
											</li>
										<?php endforeach; ?>
										
									</ul>
								<?php endif; ?>
							</div>
							<!-- <div class="sidebar__single sidebar__category">
								<h3 class="sidebar__title">Categories</h3>
								<ul class="sidebar__category-list list-unstyled">
									<li><a href="news-details.html">Business<span class="icon-right-arrow"></span></a> </li>
									<li class="active"><a href="news-details.html">Digital Agency<span class="icon-right-arrow"></span></a></li>
									<li><a href="news-details.html">Introductions<span class="icon-right-arrow"></span></a> </li>
									<li><a href="news-details.html">New Technologies<span class="icon-right-arrow"></span></a> </li>
									<li><a href="news-details.html">Parallax Effects<span class="icon-right-arrow"></span></a> </li>
									<li><a href="news-details.html">Web Development<span class="icon-right-arrow"></span></a> </li>
								</ul>
							</div> -->
							<!-- <div class="sidebar__single sidebar__tags">
								<h3 class="sidebar__title">Tags</h3>
								<div class="sidebar__tags-list"> <a href="#">Consulting</a> <a href="#">Agency</a> <a href="#">Business</a> <a href="#">Digital</a> <a href="#">Experience</a> <a href="#">Technology</a> </div>
							</div> -->
							<!-- <div class="sidebar__single sidebar__comments">
								<h3 class="sidebar__title">Recent Comments</h3>
								<ul class="sidebar__comments-list list-unstyled">
									<li>
										<div class="sidebar__comments-icon"> <i class="fas fa-comments"></i> </div>
										<div class="sidebar__comments-text-box">
											<p>A wordpress commenter on <br>
												launch new mobile app
											</p>
										</div>
									</li>
									<li>
										<div class="sidebar__comments-icon"> <i class="fas fa-comments"></i> </div>
										<div class="sidebar__comments-text-box">
											<p> <span>John Doe</span> on template:</p>
											<h5>comments</h5>
										</div>
									</li>
									<li>
										<div class="sidebar__comments-icon"> <i class="fas fa-comments"></i> </div>
										<div class="sidebar__comments-text-box">
											<p>A wordpress commenter on <br>
												launch new mobile app
											</p>
										</div>
									</li>
									<li>
										<div class="sidebar__comments-icon"> <i class="fas fa-comments"></i> </div>
										<div class="sidebar__comments-text-box">
											<p> <span>John Doe</span> on template:</p>
											<h5>comments</h5>
										</div>
									</li>
								</ul>
							</div> -->
						</div>
					</div> 
				<!-- </div>-->
			</div>
		</section>
		<!--Blog Details End-->

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
					dataType: 'json',
					success: function(data) {
						if (data.status == 'true') {
							$(form).find('.form-control').val('');
						}
						form_btn.prop('disabled', false).html(form_btn_old_msg);
						$(form_result_div).html(data.message).fadeIn('slow');
						setTimeout(function() { $(form_result_div).fadeOut('slow') }, 6000);
					}
				});
			}
		});
	})(jQuery);
	</script>
</body>

</html>