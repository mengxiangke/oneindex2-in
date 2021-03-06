<?php view::layout('layout')?>
<?php $mpd =  str_replace("thumbnail","videomanifest",$item['thumb'])."&part=index&format=dash&useScf=True&pretranscode=0&transcodeahead=0";?>
<?php view::begin('content');?>
<link class="dplayer-css" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/dplayer/dist/DPlayer.min.css">
<script src="https://cdn.jsdelivr.net/npm/dplayer/dist/DPlayer.min.js"></script>
<div class="mdui-container-fluid">
	<br>
	<div id="dplayer"></div>
	<br>
	<!-- 固定标签 -->
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">下载地址</label>
	  <input class="mdui-textfield-input" type="text" value="<?php e($url);?>"/>
	</div>
	<div class="mdui-textfield">
	  <label class="mdui-textfield-label">引用地址</label>
	  <textarea class="mdui-textfield-input"><video><source src="<?php e($url);?>" type="video/mp4"></video></textarea>
	</div>
</div>
<script>
const dp = new DPlayer({
	container: document.getElementById('dplayer'),
	lang:'zh-cn',
	video: {
	    url: '<?php e($item['downloadUrl']);?>',
	    pic: '<?php @e($item['thumb'].'&width=176&height=176');?>',
	    type: 'auto'
	},
    autoplay:true
});

// 防止出现401 token过期
dp.on('error',function () {
    console.log('获取资源错误，开始重新加载！');
    var last = dp.video.currentTime;
    dp.video.src = '<?php e($url);?>';
    dp.video.load();
    dp.video.currentTime = last;
    dp.play();
});

// 如果是播放状态 & 没有播放完 每25分钟重载视频防止卡死
setInterval(function () {
    if(!dp.video.paused && !dp.video.ended){
        console.log('开始重新加载！');
        var last = dp.video.currentTime;
        dp.video.src = '<?php e($url);?>';
        dp.video.load();
        dp.video.currentTime = last;
        dp.play();
    }
},1000 * 60 * 25)
</script>
<a href="<?php e($url);?>" class="mdui-fab mdui-fab-fixed mdui-ripple mdui-color-theme-accent"><i class="mdui-icon material-icons">file_download</i></a>
<?php view::end('content');?>