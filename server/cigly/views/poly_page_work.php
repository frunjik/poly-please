<!doctype html>
<html>
<head>
  <title></title>
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0, user-scalable=yes">
	<script src="components/platform/platform.js"></script>
	<link rel="import" href="components/core-scaffold/core-scaffold.html">
	<link rel="import" href="components/core-menu/core-menu.html">
	<link rel="import" href="components/core-menu/core-submenu.html">
	<link rel="import" href="components/code-mirror/code-mirror.html">
	<link rel="import" href="components/core-ajax/core-ajax.html">
	<link rel="import" href="components/core-field/core-field.html">
  
</head>
<body unresolved touch-action="auto">
<style>
html,body { font-family: 'RobotoDraft', sans-serif; }
@media (min-width: 481px) { .container { width: 400px; } }
</style>
    
<polymer-element name="code-view" attributes="code, post_url, get_url, height">
<template>
  <core-field>

  <core-icon icon="file-upload" size="48"></core-icon>
    <label>from:</label>
    <input id="id_get_url" placeholder="source" style="width: 40%" value="{{get_url}}">
    <core-icon-button icon="refresh" on-tap="{{load}}"></core-icon-button>

    <core-icon icon="file-download" size="48"></core-icon>
    <label>to:</label>
    <input id="id_post_url" placeholder="destination" value="{{post_url}}" flex>
    <core-icon-button icon="save" on-tap="{{save}}"></core-icon-button>

    <core-icon-button icon="apps" on-tap="{{view}}"></core-icon-button>
    
  </core-field>
  
	<core-ajax id="request" url="{{get_url}}" handleAs="text" on-core-response="{{onResponse}}"></core-ajax>
	<code-mirror id="editor" style="height: {{height}}" value="{{code}}"></code-mirror>
  
  <form id="form" action="{{post_url}}?page=work" method="POST">
		<textarea wrap="hard" name="content" type="text" hidden></textarea>
		<!--input name="name" type="text" hidden/ -->
	</form>
  
  
  <iframe src="{{get_url}}" style="width: 100%; height: 150px; border:1px solid black"></iframe>      
 
</template>
<script>
Polymer({
  ready: function(event, response) {
    if(this.get_url)
	{
		this.post_url = this.get_url.replace('/load/','/save/');
		this.load();
	}
  },
  onResponse: function(event, response) {
    this.show(response.response); 
  },
  load: function() { 
    this.$.request.go(); 
  },
  save: function()
  {
	var form = this.$.form;
	var code = this.$.editor.mirror.getValue();
	code = code.replace(/(\r\n|\n|\r)/gm,"\r");		
	form.content.value = code;
	form.submit();
  },
  view: function() { 
    window.location = this.get_url; 
  },
  show: function(text)
  {
    this.$.editor.mirror.setValue(text); 
  },
});
</script>
</polymer-element> 
  
    
<polymer-element name="page-main">
<template>
<core-scroll-header-panel style="height: 500px">
<core-toolbar id="title">
</core-toolbar>
  
<command-prompt/>  

<div content>
  
  <!--code-view height="400px"/-->
  
	<code-view id="view"
		get_url="[get_url]" 
		post_url="" 
		height="400px"
		code="">
	</code-view>
  
</div>
</core-scroll-header-panel>
</template>
<script>
Polymer({
	ready: function(event, response) {
    this.$.title.innerText = window.location.href;
  },
});
</script>
</polymer-element> 

<page-main/>

</body>
</html>
