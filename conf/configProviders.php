<?php 

return 
	array (
			//'callback'  => 'https://localhost/callback/callback.php',
			'callback'  => array(
						'normal' => 'http://auth.dbowner.com/index/checkthirdparty.html',
						'openid' => 'http://auth.dbowner.com/index/index-loginThirdParty-openid'
					),
			
			'providers' => array(
					 'QQ'          => array(
										'txt'     => 'QQ',
										'enabled' => true,
					 					'common' => true,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => '100578388',//'801457722',//'1101126016',//'100384386',//'801257756',
												'api_sercet' => 'dbad008d0a8289309f6da7022a0b9786',//'8a5bebb78ed96eeed42caa6527cb6117',//'bANVQzaxQIwby090',//'bf5fcd589d6692ebba46fc6a1beae734',//'90bfa5a74b66ce2678f2991139ab685d'
										),
										'urls'    => array(
												//'requestTokenURL' => 'http://open.t.qq.com/cgi-bin/request_token',
												//'authenticateURL' => 'http://open.t.qq.com/cgi-bin/authorize',
												//'accessTokenURL'  => 'http://open.t.qq.com/cgi-bin/access_token',
												//'hostURL'         => 'http://open.t.qq.com/api/',
												'authorize_URL' => 'https://graph.qq.com/oauth2.0/authorize',
												'accessToken_URL' => 'https://graph.qq.com/oauth2.0/token',
												'hostURL'         => 'https://graph.qq.com',												
										),
										'wrapper' => 'Providers/QQ.php',
										'icon'	  => 'login_qq'
								),
					
					   'Sina'       => array(
									    'txt'     => '新浪微博',
							    	    'enabled' => true,
					   					'common' => true,
							    	    'authway' => 'auth2',
									    'keys'    => array(
									   			 'api_key'    => '173405198',//'2118970831',
									   			 'api_sercet' => 'd5e757194a043ae589c0adccda810927'//'1e4c83aae7d49e50fe5395f7fb7e83d4',
									             ),
							            'urls'   => array(
							            		 'authorize_URL'   => 'https://api.weibo.com/oauth2/authorize',
							            		 'accessToken_URL' => 'https://api.weibo.com/oauth2/access_token',
							            		 'hostURL'         => 'https://api.weibo.com/2/',
							            		 ),
							            'wrapper' => 'Providers/Sina.php',
										'icon'	  => 'login_sina'
							    	),
					
						'Wangyi'  => array (
										'txt'     => '网易',
										'enabled' => true,
										'common' => true,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => 'Xfr6fkkJanRiEhHt',
												'api_sercet' => 'OykRgkRh7IxKmdp4LADV0dYiiUQvCrdt'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://api.t.163.com/oauth2/authorize',
												'accessToken_URL' => 'https://api.t.163.com/oauth2/access_token',
												'hostURL'         => 'https://api.t.163.com',
										),
										'wrapper' => 'Providers/Wangyi.php',
										'icon'	  => 'login_wy'
										),

			
						'Douban'    => array(
										'txt'     => '豆瓣',
										'enabled' => true,
										'common' => false,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => '0c58dc7206b3eb6e250a9556141bc24a',
												'api_sercet' => '3195746cf984d870'
												  ),
										'urls'    => array(
												 'authorize_URL'   => 'https://www.douban.com/service/auth2/auth',
							            		 'accessToken_URL' => 'https://www.douban.com/service/auth2/token',
							            		 'hostURL'         => 'https://api.douban.com/',
												  ),
										'wrapper' => 'Providers/Douban.php',
										'icon'	  => 'login_db'
										),
						'Renren'  => array (
										'txt'     => '人人网',
										'enabled' => false,
										'common' => false,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => 'b20fe8d82f0e4b4bb905bc67e35bad46',
												'api_sercet' => '11a7d87a0ab54ecb8db82ec86419b60a'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://graph.renren.com/oauth/authorize',
												'accessToken_URL' => 'https://graph.renren.com/oauth/token',
												//'hostURL'         => 'http://api.renren.com/restserver.do',
												'hostURL'         => 'https://api.renren.com',
										),
										'wrapper' => 'Providers/Renren.php',
										'icon'	  => 'login_rr'
										),
						'Kaixin'  => array (
										'txt'     => '开心网',
										'enabled' => true,
										'common' => false,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => '296413140426eae6eb99b7210f8c35b2',//'109508664056fe504bc6154624bb9f95',
												'api_sercet' => 'c6d6ae4e1da787cd917e32610a9e2308',//'0091ed9d28cab388f0d3046a1828927f'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://api.kaixin001.com/oauth2/authorize',
												'accessToken_URL' => 'https://api.kaixin001.com/oauth2/access_token',
												'hostURL'         => 'https://api.kaixin001.com/',
												//'requestTokenURL' => 'http://api.kaixin001.com/oauth/request_token',
												//'authenticateURL' => 'http://api.kaixin001.com/oauth/authorize',
												//'accessTokenURL'  => 'http://api.kaixin001.com/oauth/access_token',
												//'hostURL'         => 'http://api.kaixin001.com/',
										),
										'wrapper' => 'Providers/Kaixin.php',
										'icon'	  => 'login_kx'
										),
						'Tianya'  => array (
										'txt'     => '天涯社区',
										'enabled' => true,
										'common' => false,
										'authway' => 'auth1',
										'keys'    => array(
												'api_key'    => '21feb465381dce3df1b59c1946cccc09050879a1d',
												'api_sercet' => '51f152c9688abd22b195c35b89e362cd'
										),
										'urls'    => array(
												 'requestTokenURL' => 'http://open.tianya.cn/oauth/request_token.php',
												 'authenticateURL' => 'http://open.tianya.cn/oauth/authorize.php',
												 'accessTokenURL'  => 'http://open.tianya.cn/oauth/access_token.php',
												 'hostURL'         => 'http://open.tianya.cn/api/',
										),
										'wrapper' => 'Providers/Tianya.php',
										'icon'	  => 'login_ty'
										),
						'Sohu'  => array (
								'txt'     => '搜狐',
								'enabled' => true,
								'common' => true,
								'authway' => 'auth2',
								'keys'    => array(
										'api_key'    => 'DStDHSx2LBd7EMeMIbwO',
										'api_sercet' => 'xaESIg)SjA$GSZj)haU(w$Mc#R-HV%4pnB3HUAI-'
								),
								'urls'    => array(
										'authorize_URL' => 'https://api.t.sohu.com/oauth2/authorize',
										'accessToken_URL' => 'https://api.t.sohu.com/oauth2/access_token',
										'hostURL'         => 'https://api.t.sohu.com/',
								),
								'wrapper' => 'Providers/Sohu.php',
								'icon'	  => 'login_sohu'
						),
					
						'Baidu'  => array (
								'txt'     => '百度',
								'enabled' => true,
								'common' => true,
								'authway' => 'auth2',
								'keys'    => array(
										'api_key'    => 'gLICoauRUg6dymuB8IU6G2o8',
										'api_sercet' => 'HD1GEIoOMyVZ0eMc3DQ2ycI9bntlGG2o'
								),
								'urls'    => array(
										'authorize_URL'   => 'https://openapi.baidu.com/oauth/2.0/authorize',
										'accessToken_URL' => 'https://openapi.baidu.com/oauth/2.0/token',
										'hostURL'         => 'https://openapi.baidu.com/rest/2.0/',
								),
								'wrapper' => 'Providers/Baidu.php',
								'icon'	  => 'login_baidu'
						),
							
						'Diandian'  => array (
								'txt'     => '点点',
								'enabled' => true,
								'common' => true,
								'authway' => 'auth2',
								'keys'    => array(
										'api_key'    => 'T4loBpuG9U',
										'api_sercet' => 'NcXHsHqDnO71cElnYDVk2sezYEi39AGWnZpt'
								),
								'urls'    => array(
										'authorize_URL'   => 'https://api.diandian.com/oauth/authorize',
										'accessToken_URL' => 'https://api.diandian.com/oauth/token',
										'hostURL'         => 'https://api.diandian.com/',
								),
								'wrapper' => 'Providers/Diandian.php',
								'icon'	  => 'login_dd'
						),
					
						'Tianyi'  => array (
								'txt'     => '天翼',
								'enabled' => true,
								'common' => true,
								'authway' => 'auth2',
								'keys'    => array(
										'api_key'    => '252914040000030815',
										'api_sercet' => 'e6b5f1eb3484c44694dc7ea998d892f7'
								),
								'urls'    => array(
										//'authorize_URL'   => 'https://oauth.api.189.cn/emp/oauth2/authorize',
										//'accessToken_URL' => 'https://oauth.api.189.cn/emp/oauth2/access_token',
										'authorize_URL'   => 'https://oauth.api.189.cn/emp/oauth2/v2/authorize',
										'accessToken_URL' => 'https://oauth.api.189.cn/emp/oauth2/v2/access_token',
										'hostURL'         => 'http://api.189.cn/',
								),
								'wrapper' => 'Providers/Tianyi.php',
								'icon'	  => 'login_tyi'
						),
							
						'Facebook'       => array(
								'txt'     => 'Facebook',
								'enabled' => true,
								'common' => false,
								'authway' => 'auth2',
								'keys'    => array(
										'api_key'    => '408995609194323',
										'api_sercet' => '496a491b0d27281cf55e3fbf7d4e7c12',
								),
								'urls'    => array(
										'authorize_URL'   => 'https://graph.facebook.com/oauth/authorize',
										'accessToken_URL' => 'https://graph.facebook.com/oauth/access_token',
										'hostURL'         => 'https://graph.facebook.com/'
								),
								'wrapper' => 'Providers/Facebook.php',
								'icon'	  => 'login_facebook'
						),
					
						'Google'  => array (
										'txt'     => 'Google',
										'enabled' => false,
										'common' => false,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => '56951910415.apps.googleusercontent.com',
												'api_sercet' => 'v342XZ_g1YLQJyMloND6UscM'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://accounts.google.com/o/oauth2/auth',
												'accessToken_URL' => 'https://accounts.google.com/o/oauth2/token',
												'hostURL'         => 'https://www.googleapis.com/',
										),
										'wrapper' => 'Providers/Google.php',
										'icon'	  => 'login_006'
										),
					
						'Yahoo'  => array (
										'txt'     => 'Yahoo',
										'enabled' => false,
										'common' => false,
										'authway' => 'openid',
										'keys'    => array(
												'api_key'    => 'dj0yJmk9Q2dNYnFUeTNzaUNDJmQ9WVdrOVZuVmxWM0psTXpZbWNHbzlNVEV5TmpZMk5EWTJNZy0tJnM9Y29uc3VtZXJzZWNyZXQmeD1iNA--',
												'api_sercet' => 'd36a05a5feed3287172cc55959fe366eb04d11c0'
												  ),
										'urls'    => array(
												 'openidIdentifier' => 'https://open.login.yahooapis.com/openid20/www.yahoo.com/xrds',
												 'authorize_URL'    => 'https://api.login.yahoo.com/oauth/v2/get_request_token',
												 'accessToken_URL'  => 'https://api.login.yahoo.com/oauth/v2/get_token',
												 'hostURL'          => 'http://social.yahooapis.com/v1', 
												  ),
										'wrapper' => 'Providers/Yahoo.php',
										'icon'	  => 'login_yahoo'
										),
					
					
						'Twitter'          => array(
										'txt'     => 'twitter',
										'enabled' => false,
										'common' => false,
										'authway' => 'auth1',
										'keys'    => array(
												'api_key'    => 'On3BXl06NDfyZV9QhHqrDQ',
												'api_sercet' => 'EDoJDlLTWB6nLsmXnVrtnbfZaNOk8m2R46pOiikwOI'
										),
										'urls'    => array(
												'requestTokenURL' => 'https://api.twitter.com/oauth/request_token',
												'authenticateURL' => 'https://api.twitter.com/oauth/authorize',
												'accessTokenURL'  => 'https://api.twitter.com/oauth/access_token',
												'hostURL'         => 'https://api.twitter.com/1/'
										),
										'wrapper' => 'Providers/Twitter.php',
										'icon'	  => 'login_twitter'
										),
					
						'Live'        => array(
										'txt'     => 'Live',
										'enabled' => true,
										'common' => false,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => '00000000400ED429',
												'api_sercet' => 'B9JovrDONshQr0Likq4BkZyVb020OX0k'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://login.live.com/oauth20_authorize.srf',
												'accessToken_URL' => 'https://login.live.com/oauth20_token.srf',
												'hostURL'         => 'https://apis.live.net/v5.0/'
										),
										'wrapper' => 'Providers/Live.php',
										'icon'	  => 'login_live'
										),
						'Linkedin'     => array(
										'txt'     => 'LinkedIn',
										'enabled' => true,
										'common' => false,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => 'j8iy3nb2tigo',
												'api_sercet' => '8ZgUrv23s0pHAGJf'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://www.linkedin.com/uas/oauth2/authorization',
												'accessToken_URL' => 'https://www.linkedin.com/uas/oauth2/accessToken',
												'hostURL'         => 'https://api.linkedin.com/'
										),
										'wrapper' => 'Providers/Linkedin.php',
										'icon'	  => 'login_linkedin'
										),
						'Paypal'     => array(
										'txt'     => 'PayPal',
										'enabled' => false,
										'common' => false,
										'authway' => 'openid',
										'keys'    => array(
												'api_key'    => 'bpl2z7q93cjn',
												'api_sercet' => 'YvRIM5bbTCXKUswI'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://login.live.com/oauth20_authorize.srf',
												'accessToken_URL' => 'https://api.twitter.com/oauth/authorize',
												'hostURL'         => 'https://api.twitter.com/1/'
										),
										'wrapper' => 'Providers/Paypal.php',
										'icon'	  => 'login_paypal'
										),
					
						'Foursquare'     => array(
										'txt'     => 'Foursquare',
										'enabled' => true,
										'common' => false,
										'authway' => 'auth2',
										'keys'    => array(
												'api_key'    => 'DORU0O0FM24HCEEQF0G0FITPFNZ3ZE1LGLRH3YYDUK4SZJGL',
												'api_sercet' => 'V4M2USK5RLPBICNZ2XZSEZZHRMJYJER13Y2TL4DXL34EU1GQ'
										),
										'urls'    => array(
												'authorize_URL'   => 'https://foursquare.com/oauth2/authenticate',
												'accessToken_URL' => 'https://foursquare.com/oauth2/access_token',
												'hostURL'         => 'https://api.foursquare.com/v2/'
										),
										'wrapper' => 'Providers/Foursquare.php',
										'icon'	  => 'login_foursquare'
										),
						
						'Github'     => array(
								'txt'     => 'Github',
								'enabled' => true,
								'common' => false,
								'authway' => 'auth2',
								'keys'    => array(
										'api_key'    => 'd6e657e7712c026ee2a7',
										'api_sercet' => 'c971366a13a897991a2351cebc1b1ddfe59368d6'
								),
								'urls'    => array(
										'authorize_URL'   => 'https://github.com/login/oauth/authorize',
										'accessToken_URL' => 'https://github.com/login/oauth/access_token',
										'hostURL'         => 'https://api.github.com/'
								),
								'wrapper' => 'Providers/Github.php',
								'icon'	  => 'login_github'
						),
					
						'Tumblr'     => array(
										'txt'     => 'Tumblr',
										'enabled' => true,
										'common' => false,
										'authway' => 'auth1',
										'keys'    => array(
												'api_key'    => '6TMZl0CfqsTx2IIyY9wmQQzoooFQYxH2pLOIWx4eUGEWZDRwbF',//'bpl2z7q93cjn',
												'api_sercet' => 'LoTRjZsd4aBiHn1lxsW9OJD5WnQpbkmdK5g2EgdZ7PUhbwh8Jz'//'YvRIM5bbTCXKUswI'
										),
										'urls'    => array(
												'requestTokenURL' => 'http://www.tumblr.com/oauth/request_token',
												'authenticateURL' => 'http://www.tumblr.com/oauth/authorize',
												'accessTokenURL'  => 'http://www.tumblr.com/oauth/access_token',
												'hostURL'         => 'http://www.tumblr.com/',
										),
										'wrapper' => 'Providers/Tumblr.php',
										'icon'	  => 'login_tumblr'
										),
						
							
						),
				
	      );
?>