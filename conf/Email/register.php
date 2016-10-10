<?php 
return array(
			'mTitle' => $this->EmailLang['EmRTitle'],
			'mContent' => '
<div style="width:80%;maigin:0;">
		<div style="">
				'. $this->EmailLang['EmGreet'] .'：
		</div>
		
		<div>
			<p>' . $this->EmailLang['content1'] . '</p>
			<p><a href="'. $this->EmailUrl .'">' . $this->EmailLang['content2'] . '</a></p>
			<p>' . $this->EmailLang['content3'] . '：' . $this->EmailUrl . '</p>
			<p>' . $this->EmailLang['content4'] . '<br />' . $this->EmailLang['content5'] . '<br />' . $this->EmailLang['content6'] . '</p>
			<p>' . $this->EmailLang['content7'] . '</p>
		</div>
</div>
'
		)
?>