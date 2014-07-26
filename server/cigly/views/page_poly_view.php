<!doctype html>
<!--
Copyright (c) 2014 The Polymer Project Authors. All rights reserved.
This code may only be used under the BSD style license found at http://polymer.github.io/LICENSE.txt
The complete set of authors may be found at http://polymer.github.io/AUTHORS.txt
The complete set of contributors may be found at http://polymer.github.io/CONTRIBUTORS.txt
Code distributed by Google as part of the polymer project is also
subject to an additional IP rights grant found at http://polymer.github.io/PATENTS.txt
-->
<html>
<head>
  <title></title>
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  
  <script src="components/platform/platform.js"></script>
  <script src="/poly/scripts/poly_js.js"></script>
  
  <link rel="import" href="components/core-scaffold/core-scaffold.html">
  <link rel="import" href="components/core-header-panel/core-header-panel.html">
  <link rel="import" href="components/core-menu/core-menu.html">
	<link rel="import" href="components/core-menu/core-submenu.html">
  <link rel="import" href="components/core-item/core-item.html">
  <link rel="import" href="components/core-ajax/core-ajax.html">
  
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
  
</head>
  
<body unresolved>
  
<polymer-element name="ajax-view" attributes="get_url">
  <template>
	<p id="data">
		<content></content>
	</p>
	<core-ajax auto id="request" handleAs="text" on-core-response="{{onResponse}}" url="{{get_url}}"></core-ajax>
  </template>
  <script>
    Polymer('ajax-view', {
      ready: function() {
        if(this.get_url)
        {
          this.refresh();
        }
      },
      refresh: function() {
        this.$.request.go();
      },
      onResponse: function(event, response) {
	  		//alert(response.response);
	  		this.show(response.response);
      },
      show: function(text)
      {
        //alert(this.$.data.outerHTML);
        //alert(text);
        this.$.data.innerHTML = text;
      }
    });
  </script>
</polymer-element>
  
<polymer-element name="file-list" attributes="url">
  <template>
		<core-ajax id="request" handleAs="json" on-core-response="{{onResponse}}" url="{{url}}"></core-ajax>
    <template repeat="{{f in files}}">
      <p>
        <a onClick="edit('{{f}}');">{{f}}</a>
      </p>
    </template>
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
      <core-toolbar style="background-color: #526E9C; color: #fff;">Poly</core-toolbar>
        <core-menu selected="0" selectedindex="0" id="core_menu">
          <core-submenu active label="Site" icon="settings" valueattr="name" id="core_submenu">
            <core-item label="welcome" size="24" horizontal center layout onClick="show('load/view_poly_welcome')"></core-item>
            <core-item label="faq" size="24" horizontal center layout onClick="show('load/view_poly_faq')"></core-item>
            <core-item label="php errors" size="24" horizontal center layout onClick="show('get/errors')"></core-item>
            <core-item label="server errors" size="24" horizontal center layout onClick="show('get/server')"></core-item>
            <core-item label="access" size="24" horizontal center layout onClick="show('get/access')"></core-item>
          </core-submenu>
          <core-submenu label="Meta" icon="settings" valueattr="name" id="core_submenu1">
            <file-list id="list" url="/poly/please/get/files"/>
          </core-submenu>
        </core-menu>
    	</core-header-panel>
    <div tool>Main</div>
    <div class="content">
    <ajax-view id="view" get_url="">[@content@]</ajax-view>
    </div>
  </core-scaffold>
  
  <script>
  function edit(view_url)
  {
    var url = PolyPlease.Uri.please('edit/'+view_url);
    window.location.assign(url);
  }
  function show(view_url)
  {
    var url = PolyPlease.Uri.please(view_url);
    var v = document.getElementById('view');
    v.get_url = url;
  }
  </script>
  
</body>
</html>
