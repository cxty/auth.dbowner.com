<?php 
return array(
			'mTitle' => $this->EmailLang['EmRBTitle'],
			'mContent' => '
<div style="width:80%;maigin:0;">
		<div style="">
				'. $this->EmailLang['EmGreet'] .'：
		</div>
		
		<div>
			<p>' . $this->EmailLang['content30'] . ' ' . $this->thirdName . ' ' . $this->EmailLang['content31'] . ' ' . $this->Receiver . ' ' . $this->EmailLang['content32'] . ' ' . $this->mAddresseeMail . ' ' . $this->EmailLang['content33'] . ' ' . $this->Receiver . ' ' . $this->EmailLang['content34'] . '</p>
			<p><a href="'. $this->EmailUrl[0] .'">' . $this->EmailLang['content35'] . '</a></p>
			<p>' . $this->EmailLang['content3'] . '：' . $this->EmailUrl[0] . '</p>
			<p style="color:red;">' . $this->EmailLang['content36'] . '</p>
			<p style="color:red;"><a style="color:red;" href="' . $this->EmailUrl[1] . '">' . $this->EmailLang['content37'] . '</a></p>
			<p style="color:red;">' . $this->EmailLang['content3'] . '：' . $this->EmailUrl[1] . '</p>
			<p>' . $this->EmailLang['content4'] . '<br />' . $this->EmailLang['content5'] . '<br />' . $this->EmailLang['content6'] . '</p>
			<p>' . $this->EmailLang['content7'] . '</p>
		</div>
</div>
'
		)
?>