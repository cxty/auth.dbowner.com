<?php 
$AUTH_IDPROVIDERS = ARRAY
	(
		/* -- OpenID
		"OpenID" 		=> ARRAY ( 
				"txt"		=> 'OpenID',
				"enabled" 	=> TRUE,
				"keys"	 	=> NULL,
				"wrapper" 	=> "Providers/OpenID.php",
				"icon"		=> "login_001"
			),*/
		// -- QQ
		"QQ" 			=> ARRAY ( 
				"txt"		=> 'QQ',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY ( 
					"CONSUMER_KEY" 	  => "30444",
					"CONSUMER_SECRET" => "f3e7a815ebfc46a08f4b08714718d4e7", 
				),
				"wrapper" 	=> "Providers/qq.php",
				"icon"		=> "login_003"
			),
		// -- Sina weibo
		"Weibo" 			=> ARRAY ( 
				"txt"		=> '新浪微博',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY ( 
					"CONSUMER_KEY" 	  => "1434420599",
					"CONSUMER_SECRET" => "990e16a560409bf6156589ac6492fe4c", 
				),
				"wrapper" 	=> "Providers/weibo.php",
				"icon"		=> "login_002"
			),
		// -- Google
		"Google" => ARRAY ( 
				"txt"		=> 'Google',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY ( 
					"CONSUMER_KEY" 	  => "auth.pangu.me",
					"CONSUMER_SECRET" => "ef5JSmZe0_VOMx6AdjfHamKw", 
				),
				"wrapper" 	=> "Providers/Google.php",
				"icon"		=> "login_006"
			),

		// -- Facebook
		"Facebook" => ARRAY ( 
				"txt"		=> 'Facebook',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY( 
					"APPLICATION_ID"  => "113906752042186", 
					"CONSUMER_SECRET" => "6cdaffe56f18b1714afdf0e00e0a9b70", 
				),
				"wrapper" 	=> "Providers/Facebook.php",
				"icon"		=> "login_004"
			),
		// -- LinkedIn
		"LinkedIn"  => ARRAY ( 
				"txt"		=> 'LinkedIn',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY ( 
					"CONSUMER_KEY" 	    => "bpl2z7q93cjn",
					"CONSUMER_SECRET"   => "YvRIM5bbTCXKUswI", 
				),
				"wrapper" 	=> "Providers/LinkedIn.php",
				"icon"		=> "login_007" 
			),
		

		// -- Twitter
		"Twitter"   	=> ARRAY ( 
				"txt"		=> 'Twitter',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY( 
					"CONSUMER_KEY" 	  => "27839422-2fHaNJZwmXZEKmzCzV7AmUcqau9SmkwqyusBdz4pw",
					"CONSUMER_SECRET" => "MdHgTSzHhjgEDdCtGM42veAHwLS3vW8mXGdKUhgY", 
				),
				"wrapper" 	=> "Providers/Twitter.php",
				"icon"		=> "login_008"
			),

		/* -- Vimeo
		"Vimeo"  => ARRAY ( 
				"txt"		=> 'Vimeo',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY ( 
					"CONSUMER_KEY" 	  => "",
					"CONSUMER_SECRET" => "", 
				),
				"wrapper" 	=> "Providers/Vimeo.php",
				"icon"		=> ""
			),*/

		// -- Yahoo
		"Yahoo"  => ARRAY ( 
				"txt"		=> 'YahooID',
				"enabled" 	=> TRUE,
				"keys"	 	=> NULL, // not keys needed, its OPEN ID after all
				"wrapper" 	=> "Providers/Yahoo.php",
				"icon"		=> "login_010"
			),
		// -- Windows Live
		"Live"  => ARRAY ( 
				"txt"		=> 'Live',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY( 
					"CONSUMER_ID"       => "00000000400164C0",
					"CONSUMER_SECRET"   => "4nOatYmh3AO2Cfvc2cbQ8ehLBDaNB7rT" 
				),
				"wrapper" 	=> "Providers/Live.php",
				"icon"		=> "login_015" 
			),
		// -- MySpace
		"MySpace" 	=> ARRAY ( 
				"txt"		=> 'MySpace',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY( 
					"CONSUMER_KEY" 	  => "",
					"CONSUMER_SECRET" => "", 
				),
				"wrapper" 	=> "Providers/MySpace.php",
				"icon"		=> ""
			),
			
		// -- PayPal
		"PayPal"  => ARRAY ( 
				"txt"		=> 'PayPal',
				"enabled" 	=> TRUE,
				"keys"	 	=> NULL, // not keys needed, its OPEN ID after all
				"wrapper" 	=> "Providers/PayPal.php",
				"icon"		=> "login_011" 
			),

		// -- Foursquare
		"Foursquare"  => ARRAY ( 
				"txt"		=> 'Foursquare',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY ( 
					"CLIENT_ID" 	  => "",
					"CLIENT_SECRET"   => "", 
				),
				"wrapper" 	=> "Providers/Foursquare.php",
				"icon"		=> "login_012" 
			),

		

		// -- 豆瓣
		"Douban"  => ARRAY ( 
				"txt"		=> '豆瓣',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY( 
					"CONSUMER_KEY"      => "01ab7674284da7f60502635c78e8f7e2",
					"CONSUMER_SECRET"   => "73c10a123a671291" 
				),
				"wrapper" 	=> "Providers/Douban.php",
				"icon"		=> "" 
			),

		// -- Tumblr
		"Tumblr"  => ARRAY ( 
				"txt"		=> 'Tumblr',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY( 
					"CONSUMER_KEY"      => "5ulaO5Qek5XOdwGUGztza6zF60csR97TC7aovotZtmfUR0g4X1",
					"CONSUMER_SECRET"   => "PCDhd7LY943hMDG7qb1mP83K6ACRsTfoxMA6LSRN1fR4xYNS5g" 
				),
				"wrapper" 	=> "Providers/Tumblr.php",
				"icon"		=> "login_014" 
			),
/*
		// -- Gowalla
		"Gowalla"  => ARRAY ( 
				"txt"		=> 'Gowalla',
				"enabled" 	=> TRUE,
				"keys"	 	=> ARRAY( 
					"CONSUMER_KEY"      => "",
					"CONSUMER_SECRET"   => "" 
				),
				"wrapper" 	=> "Providers/Gowalla.php",
				"icon"		=> "" 
			),
*/
		
	);
?>