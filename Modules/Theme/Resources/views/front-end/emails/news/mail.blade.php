<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Document</title>
</head>
<body style="background: aliceblue;" >
        <div class="notifi">
            <div class="hoaphan">
                <p>Hightlandscoffee có tin tức mới !!</p>
                <div class="box-a">
                    <a href="">Notification Action</a>
                </div>
            </div>
            <div style="text-align: center; border-bottom: 3px solid #1ba100; padding: 5px;">
                
                <img src="https://www.highlandscoffee.com.vn/vnt_upload/weblink/HCO-7548-PHIN-SUA-DA-2019-TALENT-WEB_1.jpg" alt="" width="100%" />
                
            </div>
            <h5 class="pb-3">{{ $title }}</h5>
                <p class="p-mail">{{ $des }}</p>
                <p class="pt-5 thanks">Cám ơn quý khách đã sử dụng dịch vụ !!!</p>
        </div>
       

</body>
</html>
<style>
    .hoaphan{
        display: flex
    }
    .hoaphan p{
        padding-top: 23px;
    padding-left: 33px;
    width: 42%;
    font-size: 24px;
    .banner img{
        width: 100%;
    }
    .notifi {
    line-height: 4;
    font-size: 25px;
    color: brown;
    font-weight: 500;
    background: #e3ead5;
    text-align: center;
    box-shadow: 1px 1px 1px 1px #dcdce0;
}
.notifi p{
    width: 50%;
}
.notifi .box-a{
    width: 50%;
}
.notifi a {
    text-decoration: none;
    padding: 15px;
    border: 1px solid #3097d1;
    border-radius: 2%;
    background: #3097d1;
    color: white;
}
.p-mail{
    line-height: 2;
    padding-top: 15px;
        padding-left: 15px;
}
.thanks{
    color: #74787e;
    padding-top: 15px;
        padding-left: 15px;
}
</style>