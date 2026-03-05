// Hiệu ứng hoa sen rơi
document.addEventListener('DOMContentLoaded', function() {
    var canvas = document.getElementById("falling-canvas");

    if (!canvas) {
        console.error('Canvas not found!');
        return;
    }

    var ctx = canvas.getContext("2d"),
        things = [],
        thingsCount = 15, // Giảm xuống còn 15
        mouse = {
            x: -100,
            y: -100
        },
        minDist = 150;

    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;

    // Tạo object image
    var image = new Image();
    image.src = window.location.origin + '/images/hoasen.png';

    image.onerror = function() {
        console.error('Failed to load image:', image.src);
    };

    // Khởi tạo mảng vật thể sau khi ảnh load xong
    image.onload = function() {
        console.log('Image loaded successfully');
        initThings();
        tick();
    };

    function initThings() {
        for (var i = 0; i < thingsCount; i++) {
            let thingWidth = Math.floor(Math.random() * 25) + 25; // Giảm kích thước 25-50px
            let thingHeight = image.naturalHeight / image.naturalWidth * thingWidth;
            let speed = Math.random() * 0.5 + 0.3; // Giảm tốc độ rơi (0.3-0.8)

            things.push({
                width: thingWidth,
                height: thingHeight,
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height - thingHeight,
                speed: speed,
                vY: speed,
                vX: 0,
                d: Math.random() * 1.2 - 0.6,
                stepSize: (Math.random()) / 20,
                step: 0,
                angle: Math.random() * 180 - 90,
                rad: Math.floor(Math.random()),
                opacity: Math.random() * 0.5 + 0.4, // Tăng opacity tý
                _ratate: Math.floor(Math.random())
            });
        }
    }

    function drawThings() {
        things.map((thing) => {
            ctx.beginPath();
            thing.rad = (thing.angle * Math.PI) / 180;
            ctx.save();

            var cx = thing.x + thing.width / 2;
            var cy = thing.y + thing.height / 2;

            ctx.globalAlpha = thing.opacity;
            ctx.setTransform(
                Math.cos(thing.rad),
                Math.sin(thing.rad),
                -Math.sin(thing.rad),
                Math.cos(thing.rad),
                cx - cx * Math.cos(thing.rad) + cy * Math.sin(thing.rad),
                cy - cx * Math.sin(thing.rad) - cy * Math.cos(thing.rad)
            );

            ctx.drawImage(image, thing.x, thing.y, thing.width, thing.height);
            ctx.restore();
        });
    }

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawThings();
    }

    function update() {
        things.map((thing) => {
            var dist = Math.sqrt((thing.x - mouse.x) ** 2 + (thing.y - mouse.y) ** 2);

            if (dist < minDist) {
                var force = minDist / (dist * dist),
                    xcomp = (mouse.x - thing.x) / dist,
                    ycomp = (mouse.y - thing.y) / dist,
                    deltaV = force * 2;

                thing.vX -= deltaV * xcomp;
                thing.vY -= deltaV * ycomp;

                if (thing.d * xcomp > 0) {
                    thing.d = 0 - thing.d;
                }
            } else {
                thing.vX *= .98;

                if (thing.vY < thing.speed) {
                    thing.vY = thing.speed;
                }

                thing.vX += Math.cos(thing.step += (Math.random() * 0.05)) * thing.stepSize;
            }

            thing.y += thing.vY;
            thing.x += thing.vX + thing.d;

            var _angle = Math.random() + 0.2;
            if (thing._ratate == 0) {
                thing.angle += _angle;
            } else {
                thing.angle -= _angle;
            }

            if (thing.y > canvas.height) {
                reset(thing);
            }

            if (thing.x > canvas.width || thing.x < (0 - thing.width)) {
                reset(thing);
            }
        });
    }

    function reset(thing) {
        thing.width = Math.floor(Math.random() * 25) + 25; // Giảm kích thước 25-50px
        thing.height = image.naturalHeight / image.naturalWidth * thing.width;
        thing.x = Math.floor(Math.random() * canvas.width);
        thing.y = 0 - thing.height;
        thing.speed = Math.random() * 0.5 + 0.3; // Giảm tốc độ
        thing.vY = thing.speed;
        thing.vX = 0;
        thing._ratate = Math.floor(Math.random());
    }

    canvas.addEventListener('mousemove', function(e) {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
    });

    // Xử lý resize window
    window.addEventListener('resize', function() {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });

    function tick() {
        draw();
        update();
        requestAnimationFrame(tick);
    }
});
