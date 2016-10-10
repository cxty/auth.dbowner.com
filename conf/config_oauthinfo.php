<?php
/**
 * isAuth 显示授权：true是、false否
 * isDefault 默认权限：true是、false否
 * isDisable checkbox可选状态：true是、false否
 * isOpen 对外开发：true是、false否
 */
return
	array(
		array(
			'permName' => 'auth_users',
			'permInfo' => '获取个人相关信息',
			'contains' => array(
					array(
							'oauthName' => 'show',
							'oauthInfo' => '获得个人信息',
							//'isDefault' => true,
							'isOpen' => true,								
					),
					array(
							'oauthName' => 'signout',
							'oauthInfo' => '退出此用户登录状态',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'istimeout',
							'oauthInfo' => '判断是否过期',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'getapplist',
							'oauthInfo' => '返回用户所有应用及其权限代码',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'getApiInfow',
							'oauthInfo' => '返回用户所有应用及其权限代码',
							//'isDefault' => false,
							'isOpen' => false,
					),
					array(
							'oauthName' => 'fresh_token',
							'oauthInfo' => '刷新token登录值',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'show_by_name',
							'oauthInfo' => '查询指定用户名的用户信息',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'show_by_userid',
							'oauthInfo' => '查询指定用户user_id的用户信息',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'istimeoutofpartner',
							'oauthInfo' => '检查绑定的第三方账号是否已经过期',
							//'isDefault' => true,
							'isOpen' => true,
					)						
			),
			'isAuth' => true,
			'isDefault' => true,
			'isDisable' => false,
		),
		array(
			'permName' => 'auth_statuses',
			'permInfo' => '获取用户的好友相关信息',
			'contains' => array(
					array(
							'oauthName' => 'friends',
							'oauthInfo' => '获取用户朋友信息',
							//'isDefault' => true,
							'isOpen' => false,
					)
			),
			'isAuth' => true,
			'isDefault' => false,
			'isDisable' => true,
		),
		array(
			'permName' => 'auth_content',
			'permInfo' => '获取短信息相关信息',
			'contains' => array(		
					array(
							'oauthName' => 'send_msg',
							'oauthInfo' => '发布信息短信息',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'get_new_msg',
							'oauthInfo' => '取用户未读短信息列表',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'get_read_msg',
							'oauthInfo' => '取用户已读短信息列表',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'get_send_msg',
							'oauthInfo' => '取用户已发送信息列表',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'get_del_msg',
							'oauthInfo' => '取用户已删除信息列表',
							//'isDefault' => true,
							'isOpen' => true,
					),
					array(
							'oauthName' => 'del_msg',
							'oauthInfo' => '删除短信息',
							//'isDefault' => true,
							'isOpen' => true,
					)
			),
			'isAuth' => true,
			'isDefault' => true,
			'isDisable' => true,
		),			
		array(
			'permName' => 'auth_appInfo',
			'permInfo' => '获取应用相关信息',
			'contains' => array(
					
			),
			'isAuth' => true,
			'isDefault' => false,
			'isDisable' => true,
		),
		array(
				'permName' => 'auth_thirdparty',
				'permInfo' => '操作合作方平台',
				'contains' => array(
							
				),
				'isAuth' => true,
				'isDefault' => false,
				'isDisable' => true,
		),
	);