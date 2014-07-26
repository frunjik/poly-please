var PolyPlease = PolyPlease || {};

PolyPlease.Uri = function()
{
	var pls = 'please/';

	var full = function()
	{
	  return document.location.href;
	} 
	  
	var site = function(url)
	{
	  url = url || full();
	  return url.split(pls)[0];
	}

	var path = function(url)
	{
	  url = url || full();
	  return pls + url.split(pls)[1];
	}
	  
	var please = function(url)
	{
	  return site() + pls + url;
	}

	var publicAPI =
	{
	  full: 		full,
	  site: 		site,
	  path: 		path,
	  please: 		please,
	};

	return publicAPI;
}();


