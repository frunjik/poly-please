<section>

<div>
  <a href="/poly/please/load/scratch">/poly/please/load/scratch</a><br/>
  => views/scratch
</div>

<hr/>
<div>
  <a href="/poly/please/view/scratch">/poly/please/view/scratch</a><br/>
  => scratch in please.page_view
</div>

<hr/>
<div>
  <a href="/poly/please/edit/scratch">/poly/please/edit/scratch</a><br/>
  => html_escaped scratch in please.page_edit
</div>

<hr/>
<div>
  <a href="/poly/please/save/foo?page=edit">/poly/please/save/foo?page=edit</a><br/>
  => save content in views/foo<br/>
  => goto please/edit/foo
</div>
  
<hr/>
<div>
  <a href="/poly/please/go/scratch">/poly/please/go/scratch</a><br/>
  => content of views/scratch
</div>

<hr/>
<div>
  <a href="/poly/please/go/-scratch">/poly/please/go/-scratch</a><br/>
  => 'scratch';
</div>

<hr/>
<div>
  <a href="/poly/please/go/:h1/-Hello">/poly/please/go/:h1/-Hello</a><br/>
  => ...
</div>

<hr/>
<div>
  <a href="/poly/please/go/escape/:div/-test">/poly/please/go/escape/:div/-test</a><br/>
  => &lt;div&gt;test&lt;/div&gt;
</div>

<hr/>
<div>
  <a href="/poly/alter/view/poly_view_faq">/poly/alter/view/poly_view_faq</a><br/>
  => this
</div>

<hr/>
<div>
  <a href="/poly/please/debug/escape/:div/-test">/poly/please/debug/escape/:div/-test</a><br/>
  => ... (try it)
</div>

</section>



