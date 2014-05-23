<!DOCTYPE html>
<head>
	<title>英速小子</title>
	<meta charset="utf-8">
	<link rel="stylesheet" href="css/jquery-ui-1.10.3.custom.css" type="text/css">
	<link rel="stylesheet" href="css/index.css" type="text/css">
	<script type="text/javascript" src="./js/jquery-latest.min.js"></script>
	<script type="text/javascript" src="./js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/setup.js"></script>
	<script type="text/javascript" src="js/jQuery.md5.js"></script>
</head>
<body>
    <header>
        <h1>英速小子 Sonic Typing</h1>
    </header>
	<div class="setup_page">
		<div style="text-align: center; font-size: 20px; font-weight:bold;">安裝網站</div>
		<div id="readme_button"><div class="button">服務條款</div></div>
		<hr>
		<pre>
伺服器需求：
MySQL 5.5.27以上版本
PHP 5.4.7以上版本
		</pre>
		<pre>
瀏覽器需求：
GoogleChrome 28以上版本
Firefox 22以上版本
Internet Explorer10以上版本
		</pre>
		<form method="post" action="" id="setup_form">
			<div>
				<div>MySQL位置：<input type="text" name="mysql_url"/><div class="status"></div></div>
				<div>MySQL帳號：<input type="text" name="mysql_username"/><div class="status"></div></div>
				<div>MySQL密碼：<input type="password" name="mysql_password"/><div class="status"></div></div>
				<div>設定管理員帳號：<input type="text" name="username"/><div class="status"></div></div>
				<div>設定管理員密碼：<input type="password" name="password1"/><div class="status"></div></div>
				<div>確認管理員密碼：<input type="password" name="password2"/><div class="status" id="check_pw_status"></div></div>
				<!--<div>電子郵件：<input type="text" name="email"/><div class="status" id="check_email_status"></div></div>-->
			</div>
			<div class="submit"><input type="submit" value="安裝網站"></div>
		</form>
	</div>
	<div class="msg" id="setup_msg"></div>
	<div id="setup_success_page" title="註冊成功">
		<p>註冊成功，請等待管理員審核</p>
	</div>
	<div id="readme_page" title="服務條款">
		<pre>
我與父親不相見已兩年餘了。

父親是一個胖子。

那天在車站。

我看見他戴著黑布小帽，穿著黑布大馬褂，深青布棉袍。

蹣跚地走到鐵道邊，慢慢探身下去，尚不大難。

可是他穿過鐵道要爬上那邊月臺，就不容易了。

我拿出口袋內潮到滴水的IPhone5把這幕拍了下來，還打了卡並且標註了父親。

但父親不會知道，因為我把他的關係設定為＂點頭之交＂。

這樣他就不知道我昨天上傳了張去夜店喝得爛醉，還跟兩個大奶妹合照的照片。

他用兩手攀著上面，兩腳再向上縮；他肥胖的身子向左微傾，顯出努力的樣子。

這時我看見他的背影，我的眼淚很快地流下來了。

因為我看到鐵路警察走過去拉了父親一把。

然後給了他一張色調鮮明的紅色罰單：攀爬月台。

我趕緊拭乾了淚，怕他看見，也怕別人看見。

但我的地上還是溼了一片，因為我的IPhone5太潮了，一直在出水。

我再向外看時，他已抱了朱紅的橘子望回走了，口袋裡塞著紅色的罰單。

過鐵道時，他先將橘子散放在地上自己慢慢爬下，再抱起橘子走。

到這邊時，我又流淚了。

因為鐵路警察又來了，然後他給了父親第二張罰單：穿越鐵軌。

父親總共被開了四張單後才爬到我這。

我趕緊去攙他。

他和我走到車上，將橘子一股腦兒放在我的皮大衣上。

於是他撲撲衣上的泥土，心裡很輕鬆似的。

我說：「爸爸，下次我買樓上便利商店的水果吃就好了，別費事。」

父親問：「便利商店有水果？」

我：「三支香蕉40元，哪像您這橘子折合罰單平均一顆要300元呢！」

父親沒回我，因為他低著頭在玩Line Pop。

過一會他手機沒電了，拿出顆五百萬的行動電源插著，抬頭跟我說：

「我走了到那邊來信！」

我說：「我待會就微信給您。」

我望著他走出去。

他走了幾步，回過頭看見我。

父親說：「進去吧，裡邊沒人！」

我說：「爸，電扶梯在這邊，你走那方向是月台。」

父親靦腆的笑笑，說：「這習慣就是改不了。」

等他的背影混入來來往往的人叢裡，再找不著了。

我便進來車廂，我的眼淚又來了。

因為我的座位上坐了個裝睡的老婆婆，想必我的坐票又得一路站到了台北。

近幾年來，父親和我都是東奔西走。

家中光景，一日不如一日，父親的退休金福利也被馬政府砍得寥寥無幾。

我北來後，他還是寫了一封信給我。

信中說道：「我身體平安，惟膀子疼痛得厲害，舉箸提筆諸多不便，大約大去之期不遠矣，你媽也老了，我想我需要娶個大陸新娘來伺候。」

我讀到此處，在晶瑩的淚光中，又看見那肥胖的青布棉袍、黑布馬褂的背影。

我打開臉書，看到他傳給了我個訊息。

嗯，一樣，是Line POP的遊戲邀請。

唉！我不知何時再能與他相見！
		</pre>
	</div>
</body>

