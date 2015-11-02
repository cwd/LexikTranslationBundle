/**
 * Created by machw_000 on 05.10.2015.
 */
$(function() {

    var candleSprite, flameSprite;

    function initCandle(canvas,state) {
        console.log(canvas,state);
        var stage = new createjs.Stage($(canvas)[ 0 ]);
        var candle = new createjs.Sprite(candleSprite);
        candle.y = 55;
        candle.gotoAndStop(state);
        var flame = new createjs.Sprite(flameSprite);
        stage.addChild(candle);
        if(state <3) {
            flame.gotoAndPlay(Math.floor(Math.random()*100));
            flame.x = 4;
            flame.y = 5 + state * 17;
            stage.addChild(flame);
        }

        createjs.Ticker.addEventListener("tick", tick);
        function tick(e) {
            stage.update(e);
        }
    }

    function init() {
        var dataCandle = {
            images: [preload.getResult('candle')],
            frames: {width:90, height:120}
        };
        var dataFlame = {
            images: [preload.getResult('animation')],
            frames: {width:65, height:65},
        };
        candleSprite = new createjs.SpriteSheet(dataCandle);
        flameSprite = new createjs.SpriteSheet(dataFlame);

        $(".candle").each(function() {
            var state = $(this).data('state');
            var canvas = $(this).append("<canvas width='70' height='180'></canvas>").find('canvas');
            initCandle(canvas,state);
        });
    }

    var preload = new createjs.LoadQueue();
    preload.addEventListener("complete", init);
    preload.loadManifest([
        {id: "candle", src:"/bundles/aspetosfrontend/assets/candle/img/candle_tiny.png"},
        {id: "animation", src:"/bundles/aspetosfrontend/assets/candle/img/spritesheet_tiny.png"}
    ]);
});
