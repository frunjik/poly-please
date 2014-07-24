<!doctype html>
<html>
<head>
  <title>/poly/please/edit/[get_url]</title>
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

    html, body {
      height: 100%;
      margin: 0;
    }
  
    body {
      font-family: sans-serif;
    }
    
    core-scaffold {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
    }
    
    .content {
      background-color: #fff;
      height: 5000px;
      padding: 20px;
    }
    
    /* some default styles for mode="cover" on core-scaffold */
    core-scaffold[mode=cover]::shadow core-header-panel::shadow #mainContainer {
      left: 120px;
    }
    
    core-scaffold[mode=cover] .content {
      margin: 20px 100px 20px 0;
    }
    
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
  
	<form id="form" action="{{post_url}}" method="POST">
		<textarea wrap="hard" name="content" type="text" hidden></textarea>
		<!--input name="name" type="text" hidden/ -->
	</form>
  
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

<polymer-element name="file-list">
  <template>
   <section>
		<core-ajax id="request" handleAs="json" on-core-response="{{onResponse}}" url="/poly/please/get/files/poly_"></core-ajax>
    <template repeat="{{f in files}}">
      <p>
        <a onClick="goto('{{f}}');">{{f}}</a>
      </p>
    </template>
    </section>
  </template>

  <script>
    Polymer('file-list', {
      ready: function() {
        this.files = [];
        this.refresh();
      },
      
      refresh: function() {
        this.$.request.go();
      },
      
      onResponse: function(event, response) {
        this.files = response.response; 
      },
    });
  </script>
</polymer-element>

<core-scaffold>
  <core-header-panel navigation flex mode="seamed">
    <core-toolbar style="background-color: #526E9C; color: #fff;">
      <a style="color: #fff;" href="/poly/please">Poly! - Please?</a></core-toolbar>
    <core-menu selected="0" selectedindex="0" id="core_menu">
      <core-submenu active label="Site" icon="settings" valueattr="name" id="core_submenu">
        <core-item label="welcome" size="24" horizontal center layout onClick="show('load/poly_view_welcome')"></core-item>
        <core-item label="faq" size="24" horizontal center layout onClick="show('load/poly_view_faq')"></core-item>
        <core-item label="php errors" size="24" horizontal center layout onClick="show('get/errors')"></core-item>
        <core-item label="server errors" size="24" horizontal center layout onClick="show('get/server')"></core-item>
        <core-item label="access" size="24" horizontal center layout onClick="show('get/access')"></core-item>
      </core-submenu>
      <core-submenu label="Meta" icon="settings" valueattr="name" id="core_submenu1">
        <core-item label="" size="24" id="core_item2" horizontal center layout>
		  <file-list/>
          <!--a href="/poly/poly/edit/new">New</a-->
        </core-item>
      </core-submenu>
    </core-menu>
  </core-header-panel>
  
  <div tool>[get_url]</div>
  
	<code-view id="view"
		get_url="[get_url]" 
		post_url="" 
		height="550px"
		code="">
	</code-view>
  
</core-scaffold>

 <script>
   
  function path_segments(url)
  {
    var segs = url.split('/');
    if(segs && !segs[0])
    	segs.shift();
    return segs;
  }
   
  function ctrlr_name()
  {
    return path_segments(document.location.pathname)[0];
  }

  function goto(view_url)
  {
    var ctrl = ctrlr_name();
    var v = document.getElementById('view');
		var u = '/' + ctrl + '/please/edit/' + view_url;
    //alert(u);
    document.location.replace(u);
  }

  function show(view_url)
  {
    var ctrl = ctrlr_name();
    var v = document.getElementById('view');
		var u = '/' + ctrl + '/please/' + view_url;
    
    //alert(u);
    
    v.get_url = u;
		v.load();
  }
  </script>

</body>
</html>
