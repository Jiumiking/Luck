var lotteryProject = function() {
    this.users = [];
    this.count = 0;
    this.userSize = 12;
    this.usernames = [];

    this.mx = canvas.width/2;
    this.my = canvas.height/2;
    //this.radius = this.mx-20;
    if( this.mx > this.my ){
        this.radius = this.my-20;
    }else{
        this.radius = this.mx-20;
    }
    this.fSize = 26;
    this.word = {width:27, height:32}; // 文字宽度 和 高度

    this.arcRecoup = -0.078;
    this.proportion = 3/2;

    this.winner = -1;

    this.nowIndex = 0;
    this.minSpeed = 1000;
    this.maxSpeed = 40;
    this.acceleration = 80;
    this.speedMode = true;
    this.speed = this.minSpeed;
    this.runing = false;
    this.allowStop = false;
    this.winnerAddUrl = '';

    this.colors = [
        "#50BEFA", "#CE52F8", "#CE52F8",
        "#50BEFA", "#CE52F8", "#CE52F8",
        "#50BEFA", "#CE52F8", "#CE52F8",
        "#50BEFA", "#CE52F8", "#CE52F8"
    ];

    this.init = function() {
        this.winnerAddUrl = winnerAddUrl;
        this.users = users.split(",");
        this.count = this.users.length;
    };
}

lotteryProject.prototype = {
    // 随机出 12个用户
    randUsers: function() {
        if( this.count < this.userSize ){
            var cn = this.userSize - this.count;
            for( var i = 0; i<cn; i++ ){
                this.users.push(0);
            }
            this.count = this.userSize;
        }
        this.users.sort(function () { // 在取出用户前 先进行乱序排列，打乱顺序
            return 0.5 - Math.random();
        });

        this.usernames = [];
        var keys = {}, k = 0, u = '', len = 0;

        while(true) {

            k = Math.floor( Math.random()*this.count );
            u = this.users[k];

            if(keys[k] == undefined) {
                len = this.usernames.push(u);
                keys[k] = k;

                if(len >= this.userSize) break;
            }
        }
        //alert(this.usernames);
    },

    // 绘制格子
    create: function(i, color, isWin) {
        var start = 0.1666*i+this.arcRecoup,
                finish = 0.1666*(i+1)-0.01+this.arcRecoup;

        var s1 = Math.sin(Math.PI*start), c1 = Math.cos(Math.PI*start),
                s2 = Math.sin(Math.PI*finish), c2 = Math.cos(Math.PI*finish);

        var ratio = this.radius/this.proportion,
                point = {x: this.mx + ratio*c1, y: this.my + ratio*s1},
                lineTo1 = {x: this.mx + this.radius*c1, y: this.my + this.radius*s1},
                lineTo2 = {x: this.mx + ratio*c2, y: this.my + ratio*s2};

        ctx.fillStyle = color;
        ctx.beginPath();
        ctx.moveTo(point.x, point.y);
        ctx.lineTo(lineTo1.x, lineTo1.y);
        ctx.arc(this.mx, this.my, this.radius, Math.PI*start, Math.PI*finish); // 外圈
        ctx.lineTo(lineTo2.x, lineTo2.y);
        ctx.arc(this.mx, this.my, ratio, Math.PI*finish, Math.PI*start, true); // 内圈
        ctx.lineTo(point.x, point.y);
        ctx.fill();

        this.drawFont(i, start, isWin);
    },

    // 绘制文字
    drawFont: function(i, start, isWin) {
        ctx.fillStyle = isWin ? "#f00" : "#333";
        ctx.font='bold '+this.fSize+'px Microsoft YaHei';
        ctx.textBaseline='top';

        var nameLen = this.usernames[i].length;
        var wordWidth = nameLen > 3 ? this.word.width*4 : this.word.width*nameLen;
        wordWidth = 0;
        for(var k = 0; k < nameLen; k++) {
            var chr = this.usernames[i].charCodeAt(k);
            if(chr > 47 && chr < 58) wordWidth += this.word.width/2;
            else wordWidth += this.word.width;
        }

        var fontCoordinate = {};
        fontCoordinate.x = this.mx+this.radius*(0.5 + 0.5/this.proportion) * Math.cos(Math.PI*(start-this.arcRecoup)) - wordWidth/2;
        fontCoordinate.y = this.my+this.radius*(0.5 + 0.5/this.proportion) * Math.sin(Math.PI*(start-this.arcRecoup)) - this.word.height/2;

        ctx.fillText(this.usernames[i], fontCoordinate.x, fontCoordinate.y);
    },

    // 旋转
    whirling: function() {
        this.nowIndex = this.nowIndex%12;
        var fontIndex = this.nowIndex == 0 ? 11 : this.nowIndex-1;

        if(this.speedMode == true) { // 加速
            this.speed -= this.acceleration;
            if(this.speed < this.maxSpeed) {
                this.allowStop = true;
                this.speed = this.maxSpeed;
            }
        } else { // 减速
            this.speed += this.acceleration;
            if(this.speed > this.minSpeed) {
                this.winner = this.nowIndex;
            }
        }

        this.create(fontIndex, this.colors[fontIndex]);
        this.create(this.nowIndex, this.createHoverColor());
        this.nowIndex++;

        var _this = this;
        if(this.winner != -1) {
            setTimeout(function() {
                _this.showWinner();
            }, 1000);
            return false;
        }

        autoTime = setTimeout(function() {
            _this.whirling();
        }, this.speed);
    },

    nowColorIndex: 0,
    createHoverColor: function() {
        this.nowColorIndex++;
        this.nowColorIndex = this.nowColorIndex % colorCount;

        return colorList[this.nowColorIndex];
    },

    // 显示获胜者
    showWinner: function() {
        var winColors = ['#ff00ff', '#ffff00', '#00ffff', '#ff0000', '#35E854', '#4E8FFE'];
        var i = 0, time = 0, _this = this;
        time = setInterval(function() {
            _this.create(_this.winner, winColors[i%6]);
            i++;

            if(i > 16) {
                clearTimeout(time);
                _this.create(_this.winner, winColors[1], true);
                _this.runing = false;
            }
        }, 100);
        //alert(this.usernames[this.winner]);
        this.users.splice(jQuery.inArray(this.usernames[this.winner],this.users),1);//去掉获奖者
        this.count--;//总人数减一
        this.winnerListAdd( this.usernames[this.winner] );
    },

    winnerListAdd: function(id, saveToDb) {
        var grade = $("#grade").val();
        var _this = this;
        $.ajax({
            type : "GET",
            async : false,
            url : _this.winnerAddUrl,
            data : { id:id,grade:grade },
            success : function(msg){
                if(msg){
                    //alert(msg);
                    $("#msgShow").html(msg);
                    $("#msgModal").modal('show');
                }
            }
        });
    },

    // 绘制
    draw: function() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        var m = 12, _this = this, k = 0;
        for(var i = 0; i <= m; i++) {
            setTimeout(function() {
                if(k < m) _this.create(k, _this.colors[k]);
                else if(k == m) _this.whirling();
                k++;
            }, 700*i);
        }
    },

    run: function() {
        if(this.runing) return;
        this.runing = true;

        this.acceleration = Math.floor( Math.random()*60+60 ); // 加速度 60-120
        this.speedMode = true;
        this.allowStop = false;
        this.winner = -1;
        this.speed = this.minSpeed;

        this.randUsers();
        this.draw();
    },

    stop: function() {
        if(this.allowStop) {
            this.allowStop = false;
            this.speedMode = false;
        }
    }
};

var canvas = document.getElementById('tutorial');
var ctx = canvas.getContext('2d');
ctx.globalCompositeOperation = 'lighter';
var cHeight = document.documentElement.clientHeight;
var cWidth = $("div.container").width();
canvas.height = cHeight;
canvas.width = cWidth;
// 创建 渐变颜色列表
var colorList = [];
var colorCount = 0;
function createColor() {
    var colors = {"r":['ff'], "b":['ff'], "g":['ff']};
    colors.c = ['g', 'b', 'g', 'r', 'b', 'g', 'r'];
    colors.n = [255, 255, 0, 255, 0, 255, 0];

    // 颜色压缩
    var ratio = 8, len = 256 / ratio;

    // 定义基础颜色
    var r=255, g=255, b=255, rgb = {"r":"", "g":"", "b":""};

    var n = 0, color = '';
    for(var k in colors.c) {

        if(k == 3) continue;

        // 记录RBG 颜色变化流程
        for(var rgbk in rgb) {
            colors[rgbk][k*1+1] = rgbk == colors.c[k] ? dechex(255 - colors.n[k]) : colors[rgbk][k];
        }

        // 绘制渐变线条
        for(var i = colors.n[k]; i > -1 && i < 256;) {
            if(k == 0 && i > 128) i = 128;

            switch(colors.c[k]) {
                case 'r': r = i; break;
                case 'b': b = i; break;
                case 'g': g = i; break;
            }

            color = 'rgb('+r+','+b+','+g+')';

            if(colors.n[k] == 255) i -= ratio;
            else i += ratio;

            colorList.push(color);
        }
    }
    colorCount = colorList.length;
}
function dechex(num) {
    var r = Math.round(num).toString(16);
    return r.length == 1 ? '0'+r : r;
}
createColor();

var lottery = new lotteryProject();
lottery.init();
$(function() {
    $("#stop_button").find("a").css({"top":(cHeight/2-50)+"px", "left":(cWidth/2-50)+"px"});
    $(document).keyup(function(event){
        if( event.keyCode == 13 ){
            $("#stop_button").click();
        }
    });
    $("#stop_button").click(function() {
        if(lottery.allowStop) {
            $(this).find("a").html("走");
            lottery.stop();
        } else if(!lottery.runing) {
            $(this).find("a").html("停");
            lottery.run();
        }
        return false;
    });
});
