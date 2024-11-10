
//Pop-up Start //

function fenster(url)
	{
	window.open(url,"fenster","top=5,left=5,height=640,width=615,scrollbars=yes,toolbar=no,status=no,menuebar=no,directories=no,location=no,resizable=yes").focus();
	}

//Pop-up Ende //


var currentElement = '';
var currentsubElement = '';
var currentsubsubElement = '';


function showElement(id)
{
var element = document.getElementById(id);
if (element)
	{
	element.style.display = ((element.style.display == 'none') ? 'block' : 'none');
	}

var current = document.getElementById(currentElement);
if (current && (currentElement != id))
	{
	current.style.display = 'none';
	}
currentElement = id;
}

function showsubElement(id) {
	//alert(currentsubElement);
	var element = document.getElementById(id);
	if (element) {
		element.style.display = ((element.style.display == 'none') ? 'block' : 'none');
		}

	var current = document.getElementById(currentsubElement);
	if (current && currentsubElement != id)	{
		current.style.display = 'none';
		}

	currentsubElement = id;
	}

function showsubsubElement(id) {
	var element = document.getElementById(id);
	if (element) {
		element.style.display = ((element.style.display == 'none') ? 'block' : 'none');
		}

	var current = document.getElementById(currentsubsubElement);
	if (current && currentsubsubElement != id)	{
		current.style.display = 'none';
		}

	currentsubsubElement = id;
	}

function SwapLayer(showIt, hideIt) {
	var sDiv = document.getElementById(showIt);
	var hDiv = document.getElementById(hideIt);
	hDiv.className = sDiv.className.replace(new RegExp("show"), "hide");
	sDiv.className = hDiv.className.replace(new RegExp("hide"), "show");
}

