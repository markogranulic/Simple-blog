<head>
    <meta charset="utf-8">
    <title>HOME</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style/navstyle.css">
    <link rel="stylesheet" href="style/normalize.css">
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
    <script type="text/javascript">
            $(window).on('scroll', function(){
                if($(window).scrollTop()){
                    $('nav').addClass('black');
                }else{
                    $('nav').removeClass('black');
                }
            })
    </script>
    <script language="JavaScript" type="text/javascript">
        function delpost(id, title)
        {
            if (confirm("Are you sure you want to delete '" + title + "'"))
            {
                window.location.href = 'viewpost.php?delpost=' + id;
            }
        }
    </script>
</head>