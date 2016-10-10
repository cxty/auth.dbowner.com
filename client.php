<?php
$soap = new SoapClient('./dizzylion.wsdl');//如果是远程，那当然写dizzylion.wsdl的URL了。

echo date('Y-m-d H:i:s') . '<br>';
echo time() . '<br>';
echo $soap->sum(917,927);
echo '<br>';
echo time() . '<br>';
echo date('Y-m-d H:i:s') . '<br>';