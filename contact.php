<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>Project design, Student ID:11638</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDlBR3_Xp9mYyRE4k-LmJg-lRkqKUVQDo8"></script>

</head>
<body>
<div id="container">
    <header>
        <form id="login" method="post" class="fr">
            <label for="username">AGENT LOGIN</label>
            <input type="text" class="login-inputs" name="username" id="username"
                   value="" placeholder="Username">
            <input type="password" class="login-inputs" name="password" id="password" value=""
                   placeholder="Password">
            <input type="submit" value="Sign in">
        </form>
        <div class="clear"></div>
        <div id="logo">
            <a href="index.php" title="Home page"></a>
        </div>
        <nav class="fr">
            <ul id="menu">
                <li>
                    <a href="newsletter.php" class="buttom-enquire iframe">
                        Sign up to our newsletter
                    </a>
                </li>
                <li><a href="index.php" title="Home page">Home</a></li>
            </ul>
        </nav>
        <div class="clear"></div>
    </header>

    <div id="main">
        <div id="page-vehicles">
            <h1>Contact us</h1>


            <div class="fr" id="content-vehicles">
                <div class="product-item">
                    <div style='overflow:hidden;height:400px;width:680px;'>
                        <div id='gmap_canvas' style='height:400px;width:680px;'></div>
                    </div>
                    <a href='http://maps-generator.com/'>map generator</a>
                    <script type='text/javascript'
                            src='http://embedmaps.com/google-maps-authorization/script.js?id=9f60a432de8cc69920340a9beee9208353796cb7'></script>
                    <script type='text/javascript'> function init_map() {
                            var myOptions = {
                                zoom: 12, center: new google.maps.LatLng(51.5159686, -0.06760170000006838),
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            };
                            map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
                            marker = new google.maps.Marker({
                                map: map,
                                position: new google.maps.LatLng(51.5159686, -0.06760170000006838)
                            });
                            infowindow = new google.maps.InfoWindow({
                                content: '<h3><strong>Icon College</strong><br>' +
                                '21 Adler Street<br>' +
                                'E1 1EG London<br>' +
                                'STUDENT ID: 11638<br>Iulian C Moldovanu</h3>'
                            });
                            google.maps.event.addListener(marker, 'click', function () {
                                infowindow.open(map, marker);
                            });
                            infowindow.open(map, marker);
                        }
                        google.maps.event.addDomListener(window, 'load', init_map);
                    </script>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
</div>
<!--! end of #container -->
<div id="footer">
    <div id="article">
        <div class="fr">
            <span id="copyright">&copy; 2015 Student ID: 11638</span>
            <span>Web Design & Development "Final project" by Iulian C Moldovanu</span>
        </div>
        <div class="clear"></div>
    </div>
</div>
</body>
</html>








