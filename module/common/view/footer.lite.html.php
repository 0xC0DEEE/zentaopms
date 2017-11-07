<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<iframe frameborder='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='debugwin hidden'></iframe>
<?php if($this->loadModel('cron')->runable()) js::execute('startCron()');?>
<script laguage='Javascript'>
<?php if(isset($pageJS)) echo $pageJS;?>
</script>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?8d61c35145acbe7d10d8b1f8bfe1bc7d";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>

</body>
</html>
