<?php
//error_reporting(0);
ini_set('error_reporting', E_ALL);
ini_set("display_errors", 1);

$LoginUrl = 'http://freebapp.org/tools/openid/';
$AuthUrl = 'http://freebapp.org/tools/openid/auth.php';
$RealmUrl = 'http://freebapp.org/';
$PolicyUrl = 'http://freebapp.org/policy_url.html';

if(!empty($_REQUEST['AuthType'])){
	$AuthType = $_REQUEST['AuthType'];
	setcookie('cookieAuthType', $AuthType);
}else{
	if(!empty($_COOKIE['cookieAuthType'])){
		$AuthType = $_COOKIE['cookieAuthType'];
	}else{
		LocationHtml($LoginUrl);
	}
}

if(!empty($_REQUEST['return_to'])){
	$ReturnToUrl = $_REQUEST['return_to'];
	setcookie('cookieReturnTo', $ReturnToUrl);
}else{
	if(!empty($_COOKIE['cookieReturnTo'])){
		$ReturnToUrl = $_COOKIE['cookieReturnTo'];
	}else{
		$ReturnToUrl = $RealmUrl;
	}
}

if(!empty($_COOKIE['cookieAssocHandle'])){
	$AssocHandle = $_COOKIE['cookieAssocHandle'];
}else{
	$AssocHandle = null;
}

switch($AuthType){
	case 'Google':
		if(empty($_REQUEST['openid_mode'])){
			$openid_server = getXrdsUri('https://www.google.com/accounts/o8/id');
			$data['openid.ns'] = 'http://specs.openid.net/auth/2.0';
			$data['openid.mode'] = 'associate';
			$data['openid.assoc_type'] = 'HMAC-SHA1';
			$data['openid.session_type'] = 'no-encryption';
			$AssocHandle = getAssociationHandle($openid_server.'?'.http_build_query($data));
			setcookie('cookieAssocHandle', $AssocHandle);
			unset($data);

			$data['openid.ns'] = 'http://specs.openid.net/auth/2.0';
			$data['openid.claimed_id'] = 'http://specs.openid.net/auth/2.0/identifier_select';
			$data['openid.identity'] = 'http://specs.openid.net/auth/2.0/identifier_select';
			$data['openid.return_to'] = $AuthUrl;
			$data['openid.realm'] = $RealmUrl;
			$data['openid.assoc_handle'] = $AssocHandle;
			$data['openid.mode'] = 'checkid_setup';
			$data['openid.ns.ext1'] = 'http://openid.net/srv/ax/1.0';
			$data['openid.ext1.mode'] = 'fetch_request';
			$data['openid.ext1.type.email'] = 'http://axschema.org/contact/email';
			$data['openid.ext1.required'] = 'email';

			$LocationUrl  = $openid_server.'?'.http_build_query($data);
		}else{
			if('id_res' == $_REQUEST['openid_mode']
				&& $AssocHandle == $_REQUEST['openid_assoc_handle']){
				$openid = !empty($_REQUEST['openid_ext1_value_email']) ? $_REQUEST['openid_ext1_value_email'] : formUrl($_REQUEST['openid_claimed_id']);
				$email = $_REQUEST['openid_ext1_value_email'];
			}
			echo('<pre>');
			echo("<a href=$LoginUrl>BACK</a>\n\n");
			print_r($_REQUEST);
			echo('</pre>');
		}
		break;
	case 'Yahoo':
		if(empty($_REQUEST['openid_mode'])){
			$openid_server = 'https://open.login.yahooapis.com/openid/op/auth';
			$data['openid.ns'] = 'http://specs.openid.net/auth/2.0';
			$data['openid.mode'] = 'associate';
			$data['openid.assoc_type'] = 'HMAC-SHA1';
			$data['openid.session_type'] = 'no-encryption';
			$AssocHandle = getAssociationHandle($openid_server.'?'.http_build_query($data));
			setcookie('cookieAssocHandle', $AssocHandle);
			unset($data);

			$data['openid.assoc_handle'] = $AssocHandle;
			$data['openid.ax.mode'] = 'fetch_request';
			$data['openid.ax.required'] = 'attr1,attr2,attr3,attr4,attr5';
			$data['openid.ax.type.attr1'] = 'http://axschema.org/contact/email';
			$data['openid.ax.type.attr2'] = 'http://axschema.org/namePerson/first';
			$data['openid.ax.type.attr3'] = 'http://axschema.org/namePerson/last';
			$data['openid.ax.type.attr4'] = 'http://axschema.org/contact/country/home';
			$data['openid.ax.type.attr5'] = 'http://axschema.org/pref/language';
			$data['openid.claimed_id'] = 'http://specs.openid.net/auth/2.0/identifier_select';
			$data['openid.identity'] = 'http://specs.openid.net/auth/2.0/identifier_select';
			$data['openid.mode'] = 'checkid_setup';
			$data['openid.ns'] = 'http://specs.openid.net/auth/2.0';
			$data['openid.ns.ax'] = 'http://openid.net/srv/ax/1.0';
			$data['openid.ns.sreg'] = 'http://openid.net/extensions/sreg/1.1';
			$data['openid.realm'] = $RealmUrl;
			$data['openid.return_to'] = $AuthUrl;
			$data['openid.sreg.optional'] = 'nickname,email,fullname,dob,gender,postcode,country,language,timezone';
			$data['openid.sreg.policy_url'] = $PolicyUrl;
			$data['openid.sreg.required'] = 'email';
			$data['openid.trust_root'] = $RealmUrl;
			//$data['xopenid_lang_pref'] = 'tw';

			$LocationUrl  = $openid_server.'?'.http_build_query($data);
		}else{
			if('id_res' == $_REQUEST['openid_mode']
				&& $AssocHandle == $_REQUEST['openid_assoc_handle']){
				$openid = !empty($_REQUEST['openid_identity']) ? formUrl($_REQUEST['openid_identity']) : formUrl($_REQUEST['openid_claimed_id']);
				$email = $_REQUEST['openid_sreg_email'];
				$fullname = $_REQUEST['openid_sreg_fullname'];
				$nickname = $_REQUEST['openid_sreg_nickname'];
				$timezone = $_REQUEST['openid_sreg_timezone'];
			}
			echo('<pre>');
			echo("<a href=$LoginUrl>BACK</a>\n\n");
			print_r($_REQUEST);
			echo('</pre>');
		}
		break;
	case 'Live':
		$DEBUG = true;
		$KEYFILE = './Live/Application-Key.xml';
		$COOKIE = 'webauthtoken';
		$COOKIETTL = time() + (10 * 365 * 24 * 60 * 60);

		include_once('./Live/lib/windowslivelogin.php');
		$wll = WindowsLiveLogin::initFromXml($KEYFILE);
		$wll->setDebug($DEBUG);

		if(!empty($_REQUEST['action'])){
			switch($_REQUEST['action']) {
				case 'logout':
					setcookie($COOKIE);
					$LocationUrl = $RealmUrl;
					break;
				case "clearcookie":
					setcookie($COOKIE);
					$LocationUrl = $AuthUrl;
					break;
				default:
					$user = $wll->processConsent($_REQUEST);
					if(!empty($user)){
						setcookie($COOKIE, $user->getToken(), $COOKIETTL);
						$LocationUrl = $AuthUrl;
					}else{
						$LocationUrl = $LoginUrl;
					}
					break;
			}
			LocationHtml($LocationUrl);
		}
		$token = $_COOKIE[$COOKIE];
		if($token){
			$user = $wll->processConsentToken($token);
			if(!$user->isValid()){
				$LocationUrl = $AuthUrl.'?AuthType=Live&action=clearcookie';
			}else{
				$url = 'https://livecontacts.services.live.com/users/@L@'.$user->getLocationID().'/rest/livecontacts/owner';
				$owner = getLiveID($url, array('Authorization: DelegatedToken dt="'.$user->getDelegationToken().'"'));
				$openid = $owner->WindowsLiveID.' | http://cid-'.$user->getLocationID().'.profile.live.com/';
				$email = $owner->WindowsLiveID;
				$fullname = $owner->Profiles->Personal->LastName.' '.$owner->Profiles->Personal->FirstName;
				$nickname = $owner->Profiles->Personal->DisplayName;
				echo('<pre>');
				echo("<a href=$LoginUrl>BACK</a>\n\n");
				print_r($owner);
				echo('</pre>');
			}
		}else{
			$LocationUrl = $wll->getConsentUrl('Contacts.View', 'zh-CN');
		}
		break;
	case 'OpenID':
		if(empty($_REQUEST['openid_mode'])){
			if(empty($_REQUEST['OpenID']))
				LocationHtml($LoginUrl, 'OpenID is null!');
			$openid_url = formUrl($_REQUEST['OpenID']);
			$openid_server_list = getOpenIDServer($openid_url);
			if(empty($openid_server_list))
				LocationHtml($LoginUrl, 'OpenID server is null!');

			if(!empty($openid_server_list[1]))
				$openid_server = $openid_server_list[1];
			if(!empty($openid_server_list[2]))
				$openid_server = $openid_server_list[2];
			$data['openid.ns'] = 'http://specs.openid.net/auth/2.0';
			$data['openid.mode'] = 'associate';
			$data['openid.assoc_type'] = 'HMAC-SHA1';
			$data['openid.session_type'] = 'no-encryption';
			$AssocHandle = getAssociationHandle($openid_server.'?'.http_build_query($data));
			setcookie('cookieAssocHandle', $AssocHandle);
			unset($data);

			$data['openid.assoc_handle'] = $AssocHandle;
			$data['openid.ax.mode'] = 'fetch_request';
			$data['openid.ax.required'] = 'attr1,attr2,attr3,attr4,attr5';
			$data['openid.ax.type.attr1'] = 'http://axschema.org/contact/email';
			$data['openid.ax.type.attr2'] = 'http://axschema.org/namePerson/first';
			$data['openid.ax.type.attr3'] = 'http://axschema.org/namePerson/last';
			$data['openid.ax.type.attr4'] = 'http://axschema.org/contact/country/home';
			$data['openid.ax.type.attr5'] = 'http://axschema.org/pref/language';
			$data['openid.claimed_id'] = $openid_url;
			$data['openid.identity'] = $openid_url;
			$data['openid.mode'] = 'checkid_setup';
			$data['openid.ns'] = 'http://specs.openid.net/auth/2.0';
			$data['openid.ns.ax'] = 'http://openid.net/srv/ax/1.0';
			$data['openid.ns.sreg'] = 'http://openid.net/extensions/sreg/1.1';
			$data['openid.realm'] = $RealmUrl;
			$data['openid.return_to'] = $AuthUrl;
			$data['openid.sreg.optional'] = 'nickname,email,fullname,dob,gender,postcode,country,language,timezone';
			$data['openid.sreg.policy_url'] = $PolicyUrl;
			$data['openid.sreg.required'] = 'email';
			$data['openid.trust_root'] = $RealmUrl;

			$LocationUrl  = $openid_server.'?'.http_build_query($data);
		}else{
			if('id_res' == $_REQUEST['openid_mode']
				&& $AssocHandle == $_REQUEST['openid_assoc_handle']){
				$openid = !empty($_REQUEST['openid_claimed_id']) ? formUrl($_REQUEST['openid_claimed_id']) : formUrl($_REQUEST['openid_identity']);
				$email = $_REQUEST['openid_sreg_email'];
				$fullname = $_REQUEST['openid_sreg_fullname'];
				$nickname = $_REQUEST['openid_sreg_nickname'];
				$timezone = $_REQUEST['openid_sreg_timezone'];
				echo('<pre>');
				echo("<a href=$LoginUrl>BACK</a>\n\n");
				print_r($_REQUEST);
				echo('</pre>');
			}
		}
		break;
	default:
		LocationHtml($LoginUrl);
}

if(empty($openid))
	LocationHtml($LocationUrl);

echo('<pre>');
echo("openid  : $openid\n");
echo("email   : $email\n");
echo("fullname: $fullname\n");
echo("nickname: $nickname\n");
echo("timezone: $timezone\n");
echo('</pre>');

function getLiveID($url, $header){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	//curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec($ch);
	curl_close($ch);
	try{
		$xml = new SimpleXMLElement($data);
	}catch(Exception $e){
		return false;
	}
	return $xml;
}

function write_callback($ch, $data){
	echo(htmlspecialchars($data));
	return strlen($data);
}

function getOpenIDServer($url){
	$c = curl_init($url);

	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($c, CURLOPT_WRITEFUNCTION, write_callback);
	curl_setopt($c, CURLOPT_HEADER, false);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

	$data = curl_exec($c);
	curl_close($c);

	if(!empty($data)){
		$s = array();
		preg_match("/\<link rel=\"openid\.server\" href=\"([^\"]+)\"( )?(\/)?\>/i", $data, $matches);
		if(!empty($matches))
			$s[1] = $matches[1];
		preg_match("/\<link rel=\"openid2\.provider\" href=\"([^\"]+)\"( )?(\/)?\>/i", $data, $matches);
		if(!empty($matches))
			$s[2] = $matches[1];
		return $s;
	}else{
		return false;
	}
}

function formUrl($url){
	$urls = parse_url($url);
	$scheme = 'http';
	if(empty($urls['scheme']))
		$urls = parse_url($scheme.'://'.$url);
	else
		$scheme = $urls['scheme'];
	if(empty($urls['host']))
		$host = '';
	else
		$host = $urls['host'];
	if(empty($urls['path']))
		$path = '/';
	else
		$path = $urls['path'];
	if('/' != $path){
		$path = ereg_replace('/$', '', $path);
	}
	if(empty($urls['query']))
		$query = '';
	else
		$query = '?'.$urls['query'];
	return strtolower($scheme.'://'.$host).$path.$query;
}

function getXrdsUri($url){
	$c = curl_init($url);

	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_HEADER, false);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

	$request_contents = curl_exec($c);

	curl_close($c);

	$domdoc = new DOMDocument();
	$domdoc->loadXML($request_contents);

	$uri = $domdoc->getElementsByTagName("URI");
	$uri = $uri->item(0)->nodeValue;

	return $uri;
}

function getAssociationHandle($url){
	$c = curl_init($url);

	curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($c, CURLOPT_HEADER, false);
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);

	$contents = curl_exec($c);
	//print_r($contents);
	//exit;

	curl_close($c);

	$assoc_handle = time();

	$lines = explode("\n", $contents);

	foreach($lines as $line){
		if(substr($line, 0, 13) == "assoc_handle:"){
			$assoc_handle = substr($line, 13);
			break;
		}
	}

	return $assoc_handle;
}

function LocationHtml($url, $e=null){
	if(empty($url))
		exit;
	echo('<html>');
	echo('<head>');
	echo('<title>Redirect to: '.$url.'</title>');
	echo('<meta http-equiv="refresh" content="1;url='.$url.'">');
	echo('</head>');
	echo('<body bgcolor="#FFFFFF" onload="window.location.href=\''.$url.'\'">');
	echo('<table border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" height="100%" width="100%">');
	echo('<tr>');
	echo('<td align="center" valign="middle">');
	echo('<a href="'.$url.'" style="color:#000000;text-decoration:none">');
	echo('<img src="http://freebapp.org/static/images/location.gif" border="0" alt="Redirect to: '.$url.'" title="Redirect to: '.$url.'"/>');
	echo('</a>');
	echo('</td>');
	echo('</tr>');
	echo('</table>');
	if(!empty($e)){
		echo('<script type="text/javascript">');
		if(is_array($e))
			echo('alert("'.implode('\n', $e).'");');
		else
			echo('alert("'.$e.'");');
		echo('</script>');
	}
	echo('</body>');
	echo('</html>');
	exit;
}
?>