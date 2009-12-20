<html>
<body>

<span FOREACH="item.getEgoods(),egood">
<span IF="egood.delivery=#L#">
You have ordered a downloadable product: {egood.name}.
<br>
Please use the following link:  <a href="{egood.link:r}">{egood.link:r}</a> to download the product from our website.
<br>
<span IF="egood.expires=#T#">
<i>Note: this link will expire in {egood.exp_time} days so please download the product at your earliest convenience.</i>
</span>
<span IF="egood.expires=#D#">
<i>Note: this link will expire after {egood.downloads} download(s).</i>
</span>
<span IF="egood.expires=#B#">
<i>Note: this link will expire after {egood.downloads} download(s) or in {egood.exp_time} days so please download the product at your earliest convenience.</i>
</span>
<br><br>
</span>
</span>

</body>
</html>
