var sprite = function (params) {
	var width = params.width,
		height = params.height,
		imagesWidth = params.imagesWidth,
		$element = params.$drawTarget.append('<div/>').find(':last'),
		elemStyle = $element[0].style,
		// Store a local reference to the Math floor function for faster access
		mathFloor = Math.floor;

	$element.css({
		position: 'absolute',
		width: width,
		height: height,
		backgroundImage: 'url(' + params.images + ')'
	});

	var that = {
		draw: function (x, y) {
			elemStyle.left = x + 'px';
			elemStyle.top = y + 'px';
		},

		changeImage: function (index) {
			index *= width;
			var vOffset = -mathFloor(index / imagesWidth) * height;
			var hOffset = -index % imagesWidth;
			elemStyle.backgroundPosition = hOffset + 'px ' + vOffset + 'px';
		},
		show: function () {
			elemStyle.display = 'block'
		},
		hide: function () {
			elemStyle.display = 'hide';
		},
		destroy: function () {
			$element.remove();
		}
	};
	//return the instance of sprite
	return that;
}

var bouncySprite = function (params) {
	var x = params.x,
			y = params.y,
			xDir = params.xDir,
			yDir = params.yDir,
			maxX = params.maxX,  // 416, width of draw target -64 (width of sprite)
			maxY = params.maxY,  // 256 height of draw targer - 64 (height of sprite)
			animIndex = 0,
			that = sprite(params);
	that.moveAndDraw = function () {
		x += xDir;
		y += yDir;
		animIndex += xDir > 0 ? 1 : -1;
		animIndex %= 5;                            // what does this do?
		animIndex += animIndex < 0 ? 5 : 0;
		if ((xDir < 0 && x < 0) || (xDir > 0 && x >= maxX)) {
			xDir = -xDir;
		}
		if ((yDir < 0 && y < 0) || (yDir > 0 && y >= maxY)) {
			yDir = -yDir;
		}
		that.changeImage(animIndex);
		that.draw(x, y);
	};
	return that;
};


var bouncyBoss = function (numBouncy, $drawTarget) {
	var bouncys = [];
	for (var i = 0; i < numBouncy; i++) {
		bouncys.push(bouncySprite({
			images: ("images/cogs.png"),
			imagesWidth: 256,
			width: 64,
			height: 64,
			$drawTarget: $drawTarget,
			x: Math.random() * ($drawTarget.width() - 64),
			y: Math.random() * ($drawTarget.height() - 64),
			xDir: Math.random() * 4 - 2,
			yDir: Math.random() * 4 - 2,
			maxX: $drawTarget.width() - 64,
			maxY: $drawTarget.height() - 64
		}));

	}
	var moveAll = function () {
		var len = bouncys.length;
		for (var i = 0; i < len; i++) {
			bouncys[i].moveAndDraw();
		}
		setTimeout(moveAll, 15);
	}
	moveAll();
};

