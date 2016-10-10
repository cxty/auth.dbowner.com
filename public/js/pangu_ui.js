/*
$(document).ready( function () { 
     $("div").cssRadio(); 
     $("div").cssCheckBox(); 
}); 

#dx label { 
padding-left: 26px; 
background: url(RUnCheck.png) no-repeat; 
} 
#dx label.checked { 
padding-left: 26px; 
background: url(RCheck.png) no-repeat; 
color: #008800; 
} 
#dx label.over { 
color: #0000FF; 
} 

#tt label { 
padding-left: 26px; 
background: url(UnCheck.png) no-repeat; 
} 
#tt label.checked { 
padding-left: 26px; 
background: url(Check.png) no-repeat; 
color: #008800; 
} 
#tt label.over { 
color: #0000FF; 
} 

<form id="dx"> 
<P>第一组</p> 
<div> 
<p><input type="radio" name="radio1"/> <label>Option 1</label></p> 
<p><input type="radio" name="radio1"/> <label>Option 2</label></p> 
<p><input type="radio" name="radio1"/> <label>Option 3</label></p> 
<p><input type="radio" name="radio1"/> <label>Option 4</label></p> 
</div> 
<P>第二组</p> 
<div> 
<p><input type="radio" name="radio2"/> <label>Option 1</label></p> 
<p><input type="radio" name="radio2"/> <label>Option 2</label></p> 
<p><input type="radio" name="radio2"/> <label>Option 3</label></p> 
<p><input type="radio" name="radio2"/> <label>Option 4</label></p> 
</div> 
</form> 

<div id="tt"> 
<p>第一组></p> 
    <div> 
    <p><input id="CheckBox1" type="checkbox"  name="clb1" /><label >Checkbox 1</label></p> 
    <p><input id="CheckBox2" type="checkbox"  name="clb1" /><label >Checkbox 2</label></p>    
    </div> 
<p>第二组></p> 
    <div> 
    <p><input id="CheckBox3" type="checkbox"  name="clb2"/><label >Checkbox 3</label></p> 
    <p><input id="CheckBox4" type="checkbox" name="clb2"/><label >Checkbox 4</label></p>    
    </div> 
</div> 
*/

jQuery.fn.cssRadio = function () { 
    $(":input[type=radio] + label").each( function(){ 
            if ( $(this).prev()[0].checked ) 
                $(this).addClass("checked"); 
            }) 
        .hover( 
            function() { $(this).addClass("over"); }, 
            function() { $(this).removeClass("over"); } 
            ) 
        .click( function() { 
             var contents = $(this).parent().parent(); /*多组控制 关键*/ 
            $(":input[type=radio] + label", contents).each( function() { 
                $(this).prev()[0].checked=false; 
                $(this).removeClass("checked");    
            }); 
            $(this).prev()[0].checked=true; 
             $(this).addClass("checked"); 
            }).prev().hide(); 
}; 

jQuery.fn.cssCheckBox = function () { 
    $(":input[type=checkbox] + label").each( function(){ 
            if ( $(this).prev()[0].checked ) 
                {$(this).addClass("checked");}            
            }) 
        .hover( 
            function() { $(this).addClass("over"); }, 
            function() { $(this).removeClass("over"); } 
            ) 
        .toggle( function()  /*不能click，不然checked无法回到unchecked*/ 
            {                
                $(this).prev()[0].checked=true; 
                 $(this).addClass("checked"); 
            }, 
            function() 
            { 
                $(this).prev()[0].checked=false; 
                 $(this).removeClass("checked"); 
            }).prev().hide();           
} 