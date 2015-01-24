var default_content="";
$(document).ready(function()
{
	checkURL();
	//$('ul#sidebar_menu li a.dashboard').click(function (e)
	$('ul li a.menu').click(function (e)
	{
		checkURL(this.hash);
	});
	
	//filling in the default content
	default_content = $('#main_container').html();
	//setInterval("checkURL()",250);
});

var lasturl="";
function checkURL(hash)
{
	if(!hash) hash=window.location.hash;
	if(hash != lasturl)
	{
		lasturl=hash;
		// FIX - if we've used the history buttons to return to the homepage,
		// fill the main_container with the default_content
		if(hash=="")
			$('#main_container').html(default_content);
		else
			loadPage(hash);
	}
}

function loadPage(url)
{
	//url=url.replace('#page','');
	url=url.replace('#','');
	//$('#main_container').remove();
	$('#loadingx').css('visibility','visible');
	$.ajax(
	{
		type: "POST",
		url: "load_page.php",
		cache:'false',
		data: 'page='+url,
		//dataType: "html",
		success: function(msg)
		{
			//if(parseInt(msg)!=0)
			//{
				//$(body).add('#main_container');
				$('#main_container').html(msg);
				$('#loadingx').css('visibility','hidden');
			//}
		}
	});
}