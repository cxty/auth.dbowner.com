<?php 
return array(
			'mTitle' => $this->EmailLang['EmATitle'],
			'mContent' => '
<div style="width:80%;maigin:0;">
		<div style="">
				'. $this->EmailLang['EmGreet'] .'：
		</div>
		
		<div>
			<p>' . $this->EmailLang['content24'] . ' ' . $this->thirdName . ' ' . $this->EmailLang['content31'] . ' ' . $this->Receiver . ' ' . $this->EmailLang['content25'] . '</p>
			<p><a href="'. $this->EmailUrl[0] .'">' . $this->EmailLang['content26'] . '</a></p>
			<p>' . $this->EmailLang['content3'] . '：' . $this->EmailUrl[0] . '</p>
			<p style="color:red;">' . $this->EmailLang['content27'] . '</p>
			<p style="color:red;"><a style="color:red;" href="' . $this->EmailUrl[1] . '">' . $this->EmailLang['content29'] . '</a></p>
			<p style="color:red;">' . $this->EmailLang['content3'] . '：' . $this->EmailUrl[1] . '</p>
			<p>' . $this->EmailLang['content4'] . '<br />' . $this->EmailLang['content5'] . '<br />' . $this->EmailLang['content6'] . '</p>
			<p>' . $this->EmailLang['content7'] . '</p>
		</div>
</div>
'
		)
?>