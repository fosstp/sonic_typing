<link rel="stylesheet" type="text/css" href="css/index.css"/>
<script type="text/javascript" src="./js/website_config.js"></script>

<div class="website_config_page">
    <div style="text-align: center; font-size: 20px; font-weight:bold;">網站設定</div>
    <hr>
    <form method="post" action="" id="config_form">
        <div>管理員帳號：<input type="text" name="adminname" value=<?php echo "'$website_data[adminname]'";?>/><div class="status"></div></div>
        <div>管理員密碼：<input type="password" name="password1"/><div class="status"></div></div>
        <div>　確認密碼：<input type="password" name="password2"/><div class="status" id="check_pw_status"></div></div>
        <div>管理員名稱：<input type="text" name="realname" value=<?php echo "'$website_data[realname]'";?>/><div class="status"></div></div>
        <div>　網站名稱：<input type="text" name="websitename" value=<?php echo "'$website_data[websitename]'";?>/><div class="status"></div></div>
        <div class="submit"><input type="submit" value="設定"></div>
    </form>
    <div class="msg" id="config_msg"></div>
</div>
