<!DOCTYPE html>
<html lang="zh-cmn-Hans">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>点餐宝</title>
	<style>*{margin:0;padding:0}html{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}body,html{height:100%;-webkit-tap-highlight-color:transparent}body{font-family:-apple-system-font,Helvetica Neue,Helvetica,sans-serif;line-height:1.6}.page,body{background-color:#f8f8f8}.page{z-index:1;overflow-y:auto;-webkit-overflow-scrolling:touch;position:absolute;top:0;right:0;bottom:0;left:0}.slideIn{-webkit-animation:slideIn .3s forwards;animation:slideIn .3s forwards}.slideOut{-webkit-animation:slideOut .3s forwards;animation:slideOut .3s forwards}.slideUp{-webkit-animation:slideUp .3s forwards;animation:slideUp .3s forwards}.slideDown{-webkit-animation:slideDown .3s forwards;animation:slideDown .3s forwards}@-webkit-keyframes slideIn{0%{opacity:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}to{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}}@keyframes slideIn{0%{opacity:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}to{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}}@-webkit-keyframes slideOut{0%{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}to{opacity:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}}@keyframes slideOut{0%{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}to{opacity:0;-webkit-transform:translate3d(100%,0,0);transform:translate3d(100%,0,0)}}@-webkit-keyframes slideUp{0%{-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0);opacity:0}to{-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0);opacity:1}}@keyframes slideUp{0%{-webkit-transform:translate3d(0,100%,0);transform:translate3d(0,100%,0);opacity:0}to{-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0);opacity:1}}@-webkit-keyframes slideDown{0%{opacity:0;-webkit-transform:translate3d(0,-100%,0);transform:translate3d(0,-100%,0)}to{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}}@keyframes slideDown{0%{opacity:0;-webkit-transform:translate3d(0,-100%,0);transform:translate3d(0,-100%,0)}to{opacity:1;-webkit-transform:translate3d(0,0,0);transform:translate3d(0,0,0)}}
	</style>
</head>
<body>
	<div class="page" id="loader"><div id="loader_icon_wrap" style="text-align:center;margin-left:auto;margin-right:auto;margin-top:150px;margin-bottom:50px;height:100px"><img src="/home/logo.png" style="width:100px;height:100px"></div><div style="width:65%;margin:0 auto;line-height:1.6em;font-size:14px;text-align:center"><div style="display:-webkit-box;display:-webkit-flex;display:flex;-webkit-box-align:center;-webkit-align-items:center;align-items:center"><div style="background-color:#EBEBEB;height:3px;-webkit-box-flex:1;-webkit-flex:1;flex:1"><div id="loader_progress_bar" style="width:0;height:100%;background-color:#09BB07"></div></div></div><p id="loader_progress_text" style="margin-top:10px;color:#888;font-size:14px">Loading...</p></div><div style="position:absolute;bottom:.52em;left:0;right:0;color:#999;font-size:14px;text-align:center"><a href="javascript:void(0);" style="color:#586C94;display:inline-block;vertical-align:top;margin:0 .62em;position:relative;font-size:14px;text-decoration:none;-webkit-tap-highlight-color:transparent">微应用站</a><p style="padding:0 .34em;font-size:12px">Copyright © 2016 vapp.site</p></div></div>

	<!-- web app 容器 -->
	<div id="app" style="opacity:1; z-index:1000; position:absolute; top:0;right:0;bottom:0;left:0"></div>

	<script>
		var body=document.body||document.documentElement,style=body.style,transition="transition",transitionEnd,animationEnd,vendorPrefix,transition=transition.charAt(0).toUpperCase()+transition.substr(1);vendorPrefix=function(){for(var b=0,a=["Moz","Webkit","Khtml","O","ms"];b<a.length;){if("string"===typeof style[a[b]+transition])return a[b];b++}return!1}();transitionEnd=function(){var b={WebkitTransition:"webkitTransitionEnd",MozTransition:"transitionend",OTransition:"oTransitionEnd otransitionend",transition:"transitionend"},a;for(a in b)if("string"===typeof style[a])return b[a]}();animationEnd=function(){var b={WebkitAnimation:"webkitAnimationEnd",animation:"animationend"},a;for(a in b)if("string"===typeof style[a])return b[a]}();
	</script>
	<script>
		// 全局配置
		var GV = window.GV = {
			api:{
				// food:'/json/foods.json',  // Get 通过门店ID(sid)获取菜品
				food:'/api/foods',  // Get 通过门店ID(sid)获取菜品
				// foodCate:'json/foodCate.json', // Get 通过门店ID(sid)获取菜品分类
				foodCate:'/api/foods/cates', // Get 通过门店ID(sid)获取菜品分类
				order:'/'	// Post 创建订单 Put 更新订单
			},

			// store:{
			// 	id:1,
			// 	name:'雕爷牛腩',
			// 	avatar:'',
			// 	table:{id:1, title:'A01'}
			// },
			store:{!! $store !!},
			user:{id:5, name:'小菜', avatar:'logo.png'},
			order:{
				id:0,
				list:[
					{fid:1, taste:0, user:{id:1, name:'小菜', avatar:'logo.png'}},
					{fid:3, taste:2, user:{id:2, name:'菜小宝', avatar:'img/1_min.jpg'}}
				]
			}
		};
		// 静态资源加载配置
		var appRes = {
			js:['/home/js/zepto.min.js', '/home/js/swiper.min.js', '/home/js/app.min.js'], // app脚本文件URL
			css:['/home/css/swiper.min.css', '/home/css/app.min.css'], // app样式文件URL
			img:[], // 需要预加载的图片（没有则留空），在进入app前缓存，提高用户浏览体验
			delay: 0, // 加载延时，单位毫秒
			progress: function(per) {
				console.log('has loaded %d\%...', per);
				var load_text = document.getElementById('loader_progress_text'),
				    load_bar = document.getElementById('loader_progress_bar');

				load_bar.style.width = per+'%';
				load_text.innerHTML = per+'%';
			},
			success: function () {
				var loader_page = document.getElementById('loader'),
					app_page = document.getElementById('app');

				setTimeout(function() {
					loader_page.style.display = "none";
					app_page.classList.add('slideIn');

					app_page.addEventListener(animationEnd, function() {app_page.classList.remove('slideIn');app_page.style.opacity = "1.0";}, false);

					console.log('\nApp runnig:\n\n');
					new App();
				}, 500);
			}
		};
	</script>

    <script>
		eval(function(p,a,c,k,e,r){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('1q(1e(p,a,c,k,e,r){e=1e(c){1f c.1p(a)};1g(!\'\'.1i(/^/,1k)){1h(c--)r[e(c)]=k[c]||e(c);k=[1e(e){1f r[e]}];e=1e(){1f\'\\\\w+\'};c=1};1h(c--)1g(k[c])p=p.1i(1j 1r(\'\\\\b\'+e(c)+\'\\\\b\',\'g\'),k[c]);1f p}(\'1.b=3(){f(3(){d i(4)},4.h||0)};1.j("l",3(a){a=c.9("e");5(g==1.2||0==1.2)a.6.7="k";5(8==1.2||-8==1.2)a.6.7="m"});\',23,23,\'|1u|1z|1e|1E|1g|1F|1J|1K|1L||1s|1l|1j|1v|1w|1x|1y|1m|1A|1B|1C|1D\'.1n(\'|\'),0,{}));1q(1e(p,a,c,k,e,r){e=1e(c){1f(c<a?\'\':e(1o(c/a)))+((c=c%a)>1G?1k.1H(c+29):c.1p(1I))};1g(!\'\'.1i(/^/,1k)){1h(c--)r[e(c)]=k[c]||e(c);k=[1e(e){1f r[e]}];e=1e(){1f\'\\\\w+\'};c=1};1h(c--)1g(k[c])p=p.1i(1j 1r(\'\\\\b\'+e(c)+\'\\\\b\',\'g\'),k[c]);1f p}(\'7 Z=8(a){8 g(){4.5("a 2 A j...",e+1);h()}8 k(){4.5("a 3 A j...",e+1);h()}8 m(){4.5("a X A j...",e+1);h()}8 h(){e++;7 b=e/l;1<b&&(b=1);b=R(T*b);a.K?a.K(b):u;e>=l&&(4.5("Y 10 j Q!"),a.s?a.s():u)}6(a){7 f=a.2?a.2.i?a.2.i:0:0,n=a.3?a.3.i?a.3.i:0:0,p=a.9?a.9.i?a.9.i:0:0,l=f+n+p,e=0;6(0==l)a.s&&a.s();I{7 q=z.N("1d")[0],r=z.N("11")[0];6(f)G(7 c C a.2){7 b=z.M("V");"W"===J a.2[c]?(b.y="F/L",b.x=a.2[c],b.w="w",b.v="v"):"S"===J a.2[c]&&(b.y=a.2[c].y||"F/L",b.x=a.2[c].x||"",a.2[c].E&&(b.E=a.2[c].E),a.2[c].w&&(b.w=!0),a.2[c].v&&(b.v=!0));b.o?b.t=8(){6("j"==b.o||"H"==b.o)b.t=u,g()}:b.D=g;b.B=g;r.O(b);4.5("P 2 :",a.2[c])}6(n)G(c C a.3){7 d=z.M("12");d.y="F/3";d.13="14";d.15=a.3[c];d.o?d.t=8(){6("j"==d.o||"H"==d.o)d.t=u,k()}:d.D=k;d.B=k;q.O(d);4.5("P 3 :",a.3[c])}6(p)G(c C a.9)f=16 17,f.D=m,f.B=m,f.x=a.9[c],4.5("18 19 :",a.9[c])}}I 4.1a("1b 1c U!")};\',1M,1N,\'||1O|1P|1Q|1R|1g|1S|1e|1T|||||||||1U|1V|||||1W||||1X|1Y|1Z|20|21|22|24|1l|25|26|27|1s|28|2a|2b|2c|2d|2e|2f|2g|2h|2i|2j|2k|2l|1o|2m|2n|2o|2p|2q|2r|2s|1m|2t|2u|2v|2w|2x|2y|1j|2z|2A|2B|2C|2D|2E|1t\'.1n(\'|\'),0,{}))',62,165,'||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||||function|return|if|while|replace|new|String|document|Loader|split|parseInt|toString|eval|RegExp|onload|head|window|loader_icon_wrap|setTimeout|180|delay|orientation|addEventListener|150px|orientationchange|50px|appRes|style|35|fromCharCode|36|marginTop|90|getElementById|62|76|js|css|console|log|var|img|length|loaded|readyState|success|onreadystatechange|null|defer|async|src||type|file|onerror|in|id||text|for|complete|else|typeof|progress|javascript|createElement|getElementsByTagName|appendChild|loading|successfully|object|100|empty|script|string|image|all|resoures|body|link|rel|stylesheet|href|Image|preloading|images|error|resource|is'.split('|'),0,{}))
    </script>
</body>
</html>
