<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Fight club</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <span>FIGHT CLUB!</span>
        </div>
        <div class="content">
            <div id="f1" class="fighter left">
                <div class="hp">
                    <div class="ind"></div>
                    <div class="text">HP</div>
                </div>
                <div class="avatar">
                    <div class=""></div>
                </div>
                <div class="stats">
                    <div class="strength">Strength</div>
                    <div class="agility">Agility</div>
                    <div class="stamina">Stamina</div>
                </div>
            </div>
            <div id="f2" class="fighter right">
                <div class="hp">
                    <div class="ind"></div>
                    <div class="text">HP</div>
                </div>
                <div class="avatar">
                    <div class=""></div>
                </div>
                <div class="stats">
                    <div class="strength">Strength</div>
                    <div class="agility">Agility</div>
                    <div class="stamina">Stamina</div>
                </div>
            </div>
            <div class="stage">
                <div class="title">
                    <div id="fight_btn" class="fight_btn">FIGHT!</div>
                    <div id="round" class="round hide">FIGHT!</div>
                    <div id="endgame" class="endgame hide">END</div>
                </div>
                <div class="attack f1">
                    <div class="bullet fire hide">5</div>
                </div>
                <div class="attack f2">
                    <div class="bullet fire hide">5</div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="break"></div>
            <div class="title">
                ----------- FIGHT LOG -----------
            </div>
            <div class="break"></div>
        </div>
    </div>
    <script src="public/js/script.js"></script>
    <script>
        var fight_counter = 0;
        dom.one('#fight_btn').addEventListener('click', function() {
            ajax({url:'/fight.php', onload: function(result) {
                var obj = JSON.parse(result);
                if('fighters' in obj) {
                    f1 = fighter(obj.fighters.f1, 'f1');
                    f2 = fighter(obj.fighters.f2, 'f2');
                    fight_counter++;
                }
                if('rounds' in obj) {
                    log('startline', '----------- GAME #'+fight_counter+' -----------');
                    f1.log_start();
                    f2.log_start();

                    hide(dom.one('#fight_btn'),0);
                    hide(dom.one('#round'),0);
                    setTimeout(function () {
                        play_round(0, obj.rounds);
                    }, 1000);
                }
            }});
        });
        dom.one('#endgame').addEventListener('click', function() {
            log('endline', '');
            hide(dom.one('#fight_btn'),0);
            hide(dom.one('#round'),0);
            hide(dom.one('#endgame'),0);
        });
    </script>
</body>
</html>