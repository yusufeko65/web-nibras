		
		<div class="col-md-12 menu-footer">
			<div class="container">
				<div class="row">
					
					<div class="col-md-3 wow bounceIn order-md-2 menu-item-footer list-menu" data-wow-delay=".5s">
						<h4>Informasi</h4>
				<?php if($menuinformasi) { ?>
						<ul>
					<?php foreach($menuinformasi as $menu) { ?>
							<li><a href="<?php echo URL_PROGRAM . $menu['al'] ?>"><?php echo $menu['nm'] ?></a></li>
					<?php } ?>
						</ul>
				<?php } ?>
					</div>
					<div class="col-md-2 wow bounceIn order-md-3 menu-item-footer list-img" data-wow-delay=".5s">
						<h4>Pembayaran</h4>
				<?php if($bank) { ?>
							<ul>
				<?php foreach($bank as $b) { ?>
								<li><img src="<?php echo URL_IMAGE.'_other/other_'.$b['lgs']?>" alt="<?php echo $b['nms'] ?>" title="<?php echo $b['nms'] ?>" class="rounded d-block img-fluid img-thumbnail"></li>
				<?php } ?>
							</ul>
				<?php } ?>
					</div>
					<div class="col-md-3 wow bounceIn order-md-4 menu-item-footer list-img" data-wow-delay=".5s">
						<h4>Pengiriman</h4>
			<?php if($shipping) { ?>
						<ul>
					<?php foreach($shipping as $ship) { ?>
						<?php if($ship['logo'] != '' && !empty($ship['logo'])) { ?>
							<li><img src="<?php echo URL_IMAGE.'_other/other_'.$ship['logo'] ?>" class="rounded img-thumbnail"></li>
						<?php } ?>
					<?php } // end foreach shipping ?>
						</ul>
			<?php } // end if shipping ?>
					</div>
					
					
					
					<div class="col-md-4 wow bounceIn order-md-1 menu-item-footer" data-wow-delay=".5s">
						<h4> <?php echo $config_namatoko ?></h4>
						<i class="fa fa-map-marker fa-lg" aria-hidden="true"></i> <?php echo $config_alamattoko ?><br><Br>
						<?php echo nl2br($config_openingtime) ?>
						<?php if($config_pagefb != '') { ?>
						<div class="media-sosial">
							<a href="<?php echo $config_pagefb ?>"><i class="fa fa-facebook fa-lg" aria-hidden="true"></i></a>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	
		<footer class="footer">
			<div class="container">
			        <!-- trust ssl -->  
		<script type="text/javascript"> //<![CDATA[
        var tlJsHost = ((window.location.protocol == "https:") ? "https://secure.trust-provider.com/" : "http://www.trustlogo.com/");
            document.write(unescape("%3Cscript src='" + tlJsHost + "trustlogo/javascript/trustlogo.js' type='text/javascript'%3E%3C/script%3E"));
            //]]></script>
        <script language="JavaScript" type="text/javascript">
            TrustLogo("https://www.positivessl.com/images/seals/positivessl_trust_seal_sm_124x32.png", "POSDV", "none");
        </script>
				&copy; 2022 <span><?php echo $config_namatoko ?>. All Rights Reserved.</span>
			</div>
		</footer>
		
		
		<script>
			new WOW().init();
			
			$(document).ready(function() {
				
 
				var owl = $('.owl-carousel');
					owl.owlCarousel({
						loop:true,
						margin:20,
						autoplay:true,
						autoplayTimeout:1000,
						autoplayHoverPause:true
					});
				// breakpoint and up  
				$(window).resize(function(){
					if ($(window).width() >= 980){	

					  // when you hover a toggle show its dropdown menu
					  $(".navbar .dropdown-toggle").hover(function () {
						 $(this).parent().toggleClass("show");
						 $(this).parent().find(".dropdown-menu").toggleClass("show"); 
					   });

						// hide the menu when the mouse leaves the dropdown
					  $( ".navbar .dropdown-menu" ).mouseleave(function() {
						$(this).removeClass("show");  
					  });
				  
						// do something here
					}	
				});  
			
			});
		
		
		</script>
		
		<!-- WhatsHelp.io widget -->
        
        <script type="text/javascript">
            (function () {
            var options = {
            whatsapp: "+6285731607779", // WhatsApp number
            company_logo_url: "//static.whatshelp.io/img/flag.png", // URL of company logo (png, jpg, gif)
            greeting_message: "Customer Care NIBRAS, Fast Response ", // Text of greeting message
            call_to_action: "Chat me", // Call to action
            position: "right", // Position may be 'right' or 'left'
            };
            var proto = document.location.protocol, host = "whatshelp.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
            })();
        </script>

        <!-- /end WhatsHelp.io widget -->

		<!--  crisp chat -->
		<!--
		<script type="text/javascript">window.$crisp=[];window.CRISP_WEBSITE_ID="54adcb69-9d11-4eff-855f-5585f9a74a02";(function(){d=document;s=d.createElement("script");s.src="https://client.crisp.chat/l.js";s.async=1;d.getElementsByTagName("head")[0].appendChild(s);})();</script>
		<!-- /end crisp chat -->
		
	<!--	<script async data-id="20579" src="https://cdn.widgetwhats.com/script.min.js"></script> -->
		
	   <!-- Facebook Pixel Code -->
        <script>
          !function(f,b,e,v,n,t,s)
          {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
          n.callMethod.apply(n,arguments):n.queue.push(arguments)};
          if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
          n.queue=[];t=b.createElement(e);t.async=!0;
          t.src=v;s=b.getElementsByTagName(e)[0];
          s.parentNode.insertBefore(t,s)}(window, document,'script',
          'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '2662939710444661');
          fbq('track', 'PageView');
        </script>
        <noscript><img height="1" width="1" style="display:none"
          src="https://www.facebook.com/tr?id=2662939710444661&ev=PageView&noscript=1" /></noscript>
        <!-- End Facebook Pixel Code -->
        
		<?php if($config_googleanalisis != '') {?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $config_googleanalisis ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];
			function gtag(){dataLayer.push(arguments);}
			gtag('js', new Date());

			gtag('config', '<?php echo $config_googleanalisis ?>');
		</script>
		<?php } ?>
	</body>
</html>
