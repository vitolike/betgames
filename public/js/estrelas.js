var image = new Image();
image.src = 'img/test.png';

var stars = [];

for (var i = 0; i < 50; i++) {
  var x = Math.floor(Math.random() * window.innerWidth);
  var y = Math.floor(Math.random() * window.innerHeight);
  var star = { x: x, y: y, image: image };
  stars.push(star);
}

function animate() {
  requestAnimationFrame(animate);

  for (var i = 0; i < stars.length; i++) {
    var star = stars[i];
    star.y += 1;
    if (star.y > window.innerHeight) {
      star.y = 0;
    }
  }

  render();
}

function render() {
  var canvas = document.getElementById('canvas');
  var context = canvas.getContext('2d');

  context.clearRect(0, 0, canvas.width, canvas.height);

  for (var i = 0; i < stars.length; i++) {
    var star = stars[i];
    context.drawImage(star.image, star.x, star.y);
  }
}

animate();