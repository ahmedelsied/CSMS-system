<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطأ 404</title>
    <link href="<?=IMGS?>logo<?=DS?>csms_logo_icon.png" rel="icon"/>
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        * {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: cairo;
        }
        body{
            width: 100%;
            height: 100%;
            background-image: url('<?=DOMAIN?>/public_front_files/imgs/error404/backsvg.svg');
            background-repeat: no-repeat;
            background-size: cover;
            background-color:rgb(34 34 34);
            padding-top:5%;
        }
        a {
            text-decoration: none;
        }
        footer{
            width: 100%;
            padding: 15px;
            text-align: center;
        }
        article {
            text-align: center;
        }
        footer{
            bottom: 0;
            padding: 8px;
        }
        .link-footer a{
            position:relative;
        }
        .line::before{
            content: "";
            position: absolute;
            width: 8px;
            height: 8px;
            top:35%;
            border-left: 1.5px solid;
            border-top: 1.5px solid;
            transform: rotateZ(-44deg) translateX(-9.4px) translateY(-100%);
            color : #e3e5e5;
        }
        footer a span {
            font-size: 31px;
            font-weight: 500;
            color: #e3e5e5;
            margin-left: 5px;
        }
        .mob{
            display:none;
        }
        @media (max-width:768px){
            body{
                padding-top: 30%;
            }
            .pc{
                display:none;
            }
            .mob{
                display:inline-block;
            }
        }
    </style>
</head>

<body>
    <article>
        <section>
            <img class="pc" src="<?=IMGS?>error404/imgErrorPC.png" alt="هذه الصفحه ليست موجوده">
            <img class="mob" src="<?=IMGS?>error404/imgErrorMob.png" alt="هذه الصفحه ليست موجوده">
        </section>
    </article>
    <footer>
        <div class="link-footer">
            <a class="line" href="<?=DOMAIN?>"><span>العوده للصفحه الرئيسيه</span></a>
        </div>
    </footer>
</body>

</html>