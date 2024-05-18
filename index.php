<?php 
  include "includes/site/data.php";
  include "includes/site/utils.php";


$posts;

$projects;

$social_links;
$services;

// URL of the Sanity HTTP API endpoint you want to query
$url = 'https://om0khjs6.api.sanity.io/v2024-04-15/data/query/production';

// Query parameters
$query = '{
  "posts": *[_type == "post"] | order(publishedAt desc) [0..3]{
     title,
    "slug": slug.current,
    publishedAt,
    category,
    "coverImage": coverImage.asset->url
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
 "services": *[_type == "services"][0..9]{
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

  $posts = $result["posts"];
  $projects = $result["projects"];
  $services = $result["services"];
  $social_links = $result["settings"]["socialLinks"];
}

// Close cURL session
curl_close($ch);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <base href="https://www.sonamaxmarketing.com/" />
    <!-- <base href="http://localhost:8080/" /> -->

    <link rel="prefetch" href="images/banner/banner-1.jpg" as="image">


    <!-- META DATA  -->

    <link rel="manifest" href="site.webmanifest" />

    <link rel="shortcut icon" href="images/favicon/favicon-32x32.png" type="image/x-icon">   
    <link rel="icon" href="images/favicon/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="images/favicon/apple-touch-icon.png">

    <!-- HTML Meta Tags -->
    <title><?= $site["name"] ?> - Digital Marketing Agency</title>
    <meta name="description" content="<?= $site["description"] ?>">

     <meta name="theme-color" content="#f94a29" media="(prefers-color-scheme: light)">
     <meta name="theme-color" content="black" media="(prefers-color-scheme: dark)">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="https://www.sonamaxmarketing.com/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $site["name"] ?>">
    <meta property="og:description" content="<?= $site["description"] ?>">
    <meta property="og:image" content="https://www.sonamaxmarketing.com/images/og.png">

    <!-- Twitter Meta Tags -->
    <meta property="twitter:url" content="https://www.sonamaxmarketing.com/">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="sonamax">
    <meta name="twitter:title" content="Sonamax - digital marketing agency">
    <meta name="twitter:description" content="<?= $site["description"] ?>">
    <meta name="twitter:image" content="https://www.sonamaxmarketing.com/images/og.png">

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css">
    <link rel="stylesheet" type="text/css" href="css/slick.css">
    <link href="css/style.css?v=<?php echo time(); ?>" rel="stylesheet">

   
    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
</head>

<body>
    <div class="page-wrapper">

        <!-- Main Header-->
        <?php include "includes/site/header.php"; ?>
        <!--End Main Header -->

        <!-- banner-section  -->
        <section id="home" class="banner-section">
            <div class="banner-slider">
                <div class="banner-slide">
                    <img src="images/banner/banner-1.jpg" alt="image">
                    <div class="outer-box">
                        <div class="auto-container">
                            <div class="content-box">
                                <h1 data-animation-in="fadeInLeft" data-delay-in="0.2">
                                  Your Partner for Digital
                                    marketing activities
                                  
                                </h1>
                                <div data-animation-in="fadeInUp" data-delay-in="0.3" class="text">With every single one
                                    of our clients, we bring forth a deep passion for creative problem solving — which
                                    is what we deliver.</div>
                                <div class="btn-box">
                                    <a href="/services" data-animation-in="fadeInUp" data-delay-in="0.4"
                                        class="theme-btn">Our services <i
                                            class="btn-icon fa-sharp far fa-arrow-right ml-10 font-size-18"></i></a>
                                    <a href="https://www.youtube.com/watch?v=Fvae8nxzVz4" class="play-btn"
                                        data-fancybox="gallery" data-caption="" data-animation-in="fadeInLeft"
                                        data-delay-in="0.4">
                                        <i class="fa-sharp fa-solid fa-play"></i>
                                        <span>Play intro</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="banner-social">
                <h4>Follow us</h4>
                <ul>

                   <?php if(isset($social_links)): ?>
                         <?php foreach($social_links as $link): ?>
                        <li><a href="<?= $link["url"] ?>" title=""><i class="<?= formatFabIcon($link["icon"]) ?>"></i></a></li>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </section>
        <!-- End banner-section -->

        <!-- service-section -->
        <? if(isset($services)): ?>
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
        <? endif; ?>
        <!-- End service-section -->

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

        <!-- process-section -->
       
<section class="process-section">
    <div class="auto-container">
        <div class="sec-title text-center">
            <h2>Our working  Process</h2>
        </div>
        <div class="row">
            <!-- process-block -->
            <div class="process-block col-lg-3 col-sm-6">
                <div class="inner-box">
                    <div class="icon-box">
                        <i class="flaticon-creativity-1"></i>
                    </div>
                    <div class="content-box">
                        <h4 class="title">Idea Generation</h4>
                        <div class="text">Explore new ideas with creativity.</div>
                    </div>
                </div>
            </div>
            <!-- process-block -->
            <div class="process-block col-lg-3 col-sm-6">
                <div class="inner-box">
                    <div class="icon-box">
                        <i class="flaticon-workers"></i>
                    </div>
                    <div class="content-box">
                        <h4 class="title">Analysis</h4>
                        <div class="text">Conduct analysis for strategic insights.</div>
                    </div>
                </div>
            </div>
            <!-- process-block -->
            <div class="process-block col-lg-3 col-sm-6">
                <div class="inner-box">
                    <div class="icon-box">
                        <i class="flaticon-winner"></i>
                    </div>
                    <div class="content-box">
                        <h4 class="title">Prototyping</h4>
                        <div class="text">Visualize ideas through prototypes.</div>
                    </div>
                </div>
            </div>
            <!-- process-block -->
            <div class="process-block col-lg-3 col-sm-6">
                <div class="inner-box">
                    <div class="icon-box">
                        <i class="flaticon-web-programming"></i>
                    </div>
                    <div class="content-box">
                        <h4 class="title">Testing & Launch</h4>
                        <div class="text">Rigorously test before launching into market.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
        <!-- End process section -->

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

        <!-- testmonial-section -->
        <section id="testimonials" class="testimonial-section">
            <div class="inner-container">
                <div class="sec-title text-center">
                    <h2>What do our customers say?</h2>
                </div>
                <div class="row testi-slider">
                    <!-- testimonial-block -->
                    <?php foreach($testimonials as $testimony): ?>
                      <div class="testimonial-block col-md-6">
                            <div class="inner-box">
                                <div class="icon-box">
                                    <i class="flaticon-quote-1"></i>
                                </div>
                                <div class="content-box">
                                    <div class="text"><?= $testimony["text"] ?></div>
                                    <div class="auther-info">
                                      
                                            <img src="<?= $testimony["image"] ?>" alt="<?= $testimony["name"] ?> image">
                                       
                                        <div class="info-box">
                                            <h6 class="title"><?= $testimony["name"] ?></h6>
                                            <span><?= $testimony["profession"] ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>
                   
                </div>
            </div>
        </section>
        <!-- End testimonial-section -->

        <!-- why-choose us section -->
        <section class="choose-us-section">
            <div class="auto-container">
                <div class="row">
                    <!-- content-column -->
                    <div class="content-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="sec-title light">
                                <h2>Why you should choose our services?</h2>
                                <div class="text">
Choose Sonamax for top-notch digital solutions. Our team prioritizes your success with tailored strategies and innovative approaches. Trust us to elevate your online presence and achieve your goals efficiently. With Sonamax, you're in capable hands for all your digital needs.
                                </div>
                            </div>
                            <div class="list-sec">
                                <ul class="list">
                                    <li><i class="fa-solid fa-circle-check"></i>Unlock your potential with our expertise and innovation</li>
                                    <li><i class="fa-solid fa-circle-check"></i>Tailored solutions crafted for your unique needs.</li>
                                    <li><i class="fa-solid fa-circle-check"></i>.Experience excellence and results-driven success.</li>
                                </ul>
                                <ul class="list">
                                    <li><i class="fa-solid fa-circle-check"></i>Social Media Management for Engaging Online Presence.</li>
                                    <li><i class="fa-solid fa-circle-check"></i>Email Marketing Campaigns for Targeted Outreach.</li>
                                    <li><i class="fa-solid fa-circle-check"></i>Graphic Design Services for Stunning Visual Communication</li>
                                </ul>
                            </div>
                            <div class="row">
                                <div class="choose-block col-xl-6 col-lg-12 col-md-6">
                                    <div class="inner-box">
                                        <div class="icon-box">
                                            <i class="flaticon-title"></i>
                                        </div>
                                        <h6 class="title">best Consulting and Strategy</h6>
                                    </div>
                                </div>
                                <div class="choose-block col-xl-6 col-lg-12 col-md-6">
                                    <div class="inner-box">
                                        <div class="icon-box">
                                            <i class="flaticon-creativity"></i>
                                        </div>
                                        <h6 class="title">search engine optimization</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- image-column -->
                    <div class="image-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="image-box">
                                <figure class="image overlay-anim"><a href="/about-us"><img
                                            src="images/resource/choose1-1.jpg" alt=""></a></figure>
                                <div class="exp-box bounce-y">
                                    <h6 class="title">5+</h6>
                                    <div class="text">Years of
                                        experience</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End wht-choose-us section -->

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
                                    <a href="/contact-us" class="theme-btn-v2">Choose Package<i
                                            class="flaticon-arrow-pointing-to-right btn-icon ml-10"></i></a>
                                </div>
                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>
            </div>
        </section>
        <!-- End pricing-section -->

        <!-- contact-banner-section -->
        <section class="contact-banner">
            <div class="auto-container">
                <div class="outer-box">
                    <h3 class="title">We&apos;re shaping the perfect<br>Marketing solutions</h3>
                    <a href="contact-us" class="theme-btn-v2">Free Consultations <i
                            class="flaticon-arrow-pointing-to-right btn-icon ml-10 font-size-18"></i></a>
                </div>
            </div>
        </section>
        <!-- End contact-banner section -->

        <!-- contact-section -->
        <section id="contact" class="contact-section">
            <div class="auto-container">
                <div class="outer-box">
                    <div class="row">
                        <!-- content-column -->
                        <div class="content-column col-lg-6">
                            <div class="inner-column">
                                <div class="sec-title light">
                                    <h2>LET’S WORK TOGETHER FOR A GREAT BUSINESS</h2>
                                    <div class="text">At Sonamax, we're passionate about propelling businesses to new heights through cutting-edge digital marketing solutions. With our personalized strategies and unwavering dedication, we'll help you stand out in the digital landscape, connect with your target audience, and achieve remarkable success. Let's collaborate and transform your vision into reality.</div>
                                </div>
                                <div class="row">
                                    <!-- contact-block -->
                                    <div class="contact-block col-sm-6">
                                        <div class="inner-box">
                                            <div class="icon-box"> <i class="flaticon-map-locator"></i> </div>
                                            <div class="content-box"> <span>Location</span>
                                                <h6 class="title"><?= $site["location"] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- contact-block -->
                                    <div class="contact-block col-sm-6">
                                        <div class="inner-box">
                                            <div class="icon-box"> <i class="flaticon-call-3"></i> </div>
                                            <div class="content-box"> <span>Phone</span>
                                                <h6 class="title"><?= $site["phone"] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- contact-block -->
                                    <div class="contact-block col-sm-6">
                                        <div class="inner-box">
                                            <div class="icon-box"> <i class="flaticon-envelope"></i> </div>
                                            <div class="content-box">
                                                <span>Email</span>
                                                <h6 class="title"><?= $site["email"] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- contact-block -->
                                    <div class="contact-block col-sm-6">
                                        <div class="inner-box">
                                            <div class="icon-box"> <i class="flaticon-worldwide"></i> </div>
                                            <div class="content-box"> 
                                                <span>Website</span>
                                                <h6 class="title"><a href="https://www.sonamaxmarketing.com/" class="title">sonamaxmarketing.com</a></h6> 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- form-column -->
                        <div class="form-column col-lg-6">
                            <div class="inner-column">
                                <h4 class="title">Get in touch</h4>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End contact-section -->

        

        <!-- news-section -->
        <section id="news" class="news-section-two">
            <div class="auto-container">
                <div class="sec-title">
                    <h2>Latest from the blog</h2>
                </div>
                
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
                                </svg>
                                <span>

                                    <?= formatDate($post["publishedAt"]) ?>
                                </span>
                            </li>
                            <?php if(isset($post["category"])): ?>
                            <li>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="14"
                                    viewBox="0 0 10 14" fill="none">
                                    <path opacity="0.8"
                                        d="M0.625 0H9.375C9.72019 0 10 0.303636 10 0.678183V13.6608C10 13.8481 9.86006 14 9.6875 14C9.62881 14 9.57125 13.982 9.5215 13.9481L5 10.8722L0.478494 13.9481C0.332269 14.0476 0.139412 13.9997 0.0477311 13.841C0.0165436 13.787 0 13.7246 0 13.6608V0.678183C0 0.303636 0.279825 0 0.625 0ZM8.75 1.35637H1.25V11.8224L5 9.27123L8.75 11.8224V1.35637Z"
                                        fill="#F94A29" />
                                </svg>
                                <span class="line-clamp-1">

                                    <?= $post["category"] ?? '' ?>
                                </span>
                            </li>
                            <?php endif; ?>
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
        <!-- End news section -->

        <!-- faq-section -->
        <section class="faqs-section">
            <div class="auto-container">
                <div class="row">
                    <!-- image-column -->
                    <div class="image-column col-lg-6 col-md-12 col-sm-12">
                        <div class="inner-column">
                            <div class="image-box">
                                <figure class="image overlay-anim"><img src="images/resource/faq1-1.jpg" alt="">
                                </figure>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Column -->
                    <div class="faq-column col-lg-6 col-md-12 col-sm-12 wow fadeInUp" data-wow-delay="400ms">
                        <div class="inner-column">
                            <div class="sec-title light">
                                <h2 class="title">See Frequently Asked Questions</h2>
                            </div>
                            <ul class="accordion-box wow fadeInRight">
                                <!-- Block 1 -->
        <li class="accordion block">
            <div class="acc-btn">What digital marketing services do you offer?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content">
                <div class="content">
                    <div class="text">We offer a comprehensive range of digital marketing services, including search engine optimization (SEO), pay-per-click (PPC) advertising, social media marketing, content marketing, email marketing, and more. Our team can tailor a strategy to meet your specific needs and goals.</div>
                </div>
            </div>
        </li>
        <!-- Block 2 -->
        <li class="accordion block active-block">
            <div class="acc-btn">How can digital marketing benefit my business?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content current">
                <div class="content">
                    <div class="text">Digital marketing offers numerous benefits for businesses, including increased brand visibility, targeted audience reach, higher website traffic, lead generation, improved conversion rates, and measurable ROI. By leveraging digital channels effectively, you can engage with your audience, build relationships, and drive business growth.</div>
                </div>
            </div>
        </li>
        <!-- Block 3 -->
        <li class="accordion block">
            <div class="acc-btn">How do you measure the success of digital marketing campaigns?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content">
                <div class="content">
                    <div class="text">We utilize various metrics and analytics tools to measure the success of digital marketing campaigns, including website traffic, conversion rates, click-through rates (CTR), engagement metrics, return on ad spend (ROAS), and more. By tracking key performance indicators (KPIs), we can evaluate campaign effectiveness and make data-driven decisions to optimize performance.</div>
                </div>
            </div>
        </li>
        <!-- Block 4 -->
        <li class="accordion block">
            <div class="acc-btn">How long does it take to see results from digital marketing efforts?
                <div class="icon fa fa-plus"></div>
            </div>
            <div class="acc-content">
                <div class="content">
                    <div class="text">The timeline for seeing results from digital marketing efforts can vary depending on factors such as the competitiveness of your industry, the effectiveness of your strategy, and the channels you're utilizing. While some tactics may deliver immediate results, such as PPC advertising, others, like SEO, may take longer to yield noticeable outcomes. We work diligently to implement strategies that deliver both short-term wins and long-term success.</div>
                </div>
            </div>
        </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End faqs-section -->

      

        <!-- contact-banner-two -->
        <section class="contact-banner-two">
            <div class="auto-container">
                <div class="outer-box">
                    <h2 class="title wow fadeInLeft" data-wow-delay="300ms">Have a project? <br>Let's discuss</h2>
                    <div class="btn-box wow fadeInRight" data-wow-delay="400ms">
                        <a href="/contact-us" class="theme-btn-v2">Free Consultations<i
                                class="btn-icon fa-sharp far fa-arrow-right ml-10 font-size-18"></i></a>
                        <a href="tel:<?= $site["phone"] ?>" class="theme-btn-v2 two"><?= $site["phone"] ?><i
                                class="fa-sharp far fa-phone ml-10 font-size-18"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- End contact banner section -->

      	<!-- Main Footer -->
        <?php include "includes/site/footer.php"; ?>
		<!--End Main Footer --


    </div><!-- End Page Wrapper -->

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

    <script src="js/jquery.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/slick.min.js"></script>
    <script src="js/slick-animation.min.js"></script>
    <script src="js/jquery.fancybox.js"></script>
    <script src="js/progress-bar.js"></script>
    <script src="js/wow.js"></script>
    <script src="js/appear.js"></script>
    <script src="js/mixitup.js"></script>
    <script src="js/script.js"></script>
</body>

</html>