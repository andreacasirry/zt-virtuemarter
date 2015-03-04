/* jQuery elevateZoom 3.0.8 - Demo's and documentation: - www.elevateweb.co.uk/image-zoom - Copyright (c) 2013 Andrew Eades - www.elevateweb.co.uk - Dual licensed under the LGPL licenses. - http://en.wikipedia.org/wiki/MIT_License - http://en.wikipedia.org/wiki/GNU_General_Public_License */
"function" !== typeof Object.create && (Object.create = function (d) {
    function h() {
    }

    h.prototype = d;
    return new h
});
(function (d, h, l, m) {
    var k = {init: function (b, a) {
        var c = this;
        c.elem = a;
        c.$elem = d(a);
        c.imageSrc = c.$elem.data("zoom-image") ? c.$elem.data("zoom-image") : c.$elem.attr("src");
        c.options = d.extend({}, d.fn.elevateZoom.options, b);
        c.options.tint && (c.options.lensColour = "none", c.options.lensOpacity = "1");
        "inner" == c.options.zoomType && (c.options.showLens = !1);
        c.$elem.parent().removeAttr("title").removeAttr("alt");
        c.zoomImage = c.imageSrc;
        c.refresh(1);
        d("#" + c.options.gallery + " a").click(function (a) {
            c.options.galleryActiveClass &&
            (d("#" + c.options.gallery + " a").removeClass(c.options.galleryActiveClass), d(this).addClass(c.options.galleryActiveClass));
            a.preventDefault();
            d(this).data("zoom-image") ? c.zoomImagePre = d(this).data("zoom-image") : c.zoomImagePre = d(this).data("image");
            c.swaptheimage(d(this).data("image"), c.zoomImagePre);
            return!1
        })
    }, refresh: function (b) {
        var a = this;
        setTimeout(function () {
            a.fetch(a.imageSrc)
        }, b || a.options.refresh)
    }, fetch: function (b) {
        var a = this, c = new Image;
        c.onload = function () {
            a.largeWidth = c.width;
            a.largeHeight =
                c.height;
            a.startZoom();
            a.currentImage = a.imageSrc;
            a.options.onZoomedImageLoaded(a.$elem)
        };
        c.src = b
    }, startZoom: function () {
        var b = this;
        b.nzWidth = b.$elem.width();
        b.nzHeight = b.$elem.height();
        b.isWindowActive = !1;
        b.isLensActive = !1;
        b.isTintActive = !1;
        b.overWindow = !1;
        b.options.imageCrossfade && (b.zoomWrap = b.$elem.wrap('<div style="height:' + b.nzHeight + "px;width:" + b.nzWidth + 'px;" class="zoomWrapper" />'), b.$elem.css("position", "absolute"));
        b.zoomLock = 1;
        b.scrollingLock = !1;
        b.changeBgSize = !1;
        b.currentZoomLevel = b.options.zoomLevel;
        b.nzOffset = b.$elem.offset();
        b.widthRatio = b.largeWidth / b.currentZoomLevel / b.nzWidth;
        b.heightRatio = b.largeHeight / b.currentZoomLevel / b.nzHeight;
        "window" == b.options.zoomType && (b.zoomWindowStyle = "overflow: hidden;background-position: 0px 0px;text-align:center;background-color: " + String(b.options.zoomWindowBgColour) + ";width: " + String(b.options.zoomWindowWidth) + "px;height: " + String(b.options.zoomWindowHeight) + "px;float: left;background-size: " + b.largeWidth / b.currentZoomLevel + "px " + b.largeHeight / b.currentZoomLevel +
            "px;display: none;z-index:100;border: " + String(b.options.borderSize) + "px solid " + b.options.borderColour + ";background-repeat: no-repeat;position: absolute;");
        if ("inner" == b.options.zoomType) {
            var a = b.$elem.css("border-left-width");
            b.zoomWindowStyle = "overflow: hidden;margin-left: " + String(a) + ";margin-top: " + String(a) + ";background-position: 0px 0px;width: " + String(b.nzWidth) + "px;height: " + String(b.nzHeight) + "px;float: left;display: none;cursor:" + b.options.cursor + ";px solid " + b.options.borderColour + ";background-repeat: no-repeat;position: absolute;"
        }
        "window" ==
            b.options.zoomType && (lensHeight = b.nzHeight < b.options.zoomWindowWidth / b.widthRatio ? b.nzHeight : String(b.options.zoomWindowHeight / b.heightRatio), lensWidth = b.largeWidth < b.options.zoomWindowWidth ? b.nzWidth : b.options.zoomWindowWidth / b.widthRatio, b.lensStyle = "background-position: 0px 0px;width: " + String(b.options.zoomWindowWidth / b.widthRatio) + "px;height: " + String(b.options.zoomWindowHeight / b.heightRatio) + "px;float: right;display: none;overflow: hidden;z-index: 999;-webkit-transform: translateZ(0);opacity:" +
            b.options.lensOpacity + ";filter: alpha(opacity = " + 100 * b.options.lensOpacity + "); zoom:1;width:" + lensWidth + "px;height:" + lensHeight + "px;background-color:" + b.options.lensColour + ";cursor:" + b.options.cursor + ";border: " + b.options.lensBorderSize + "px solid " + b.options.lensBorderColour + ";background-repeat: no-repeat;position: absolute;");
        b.tintStyle = "display: block;position: absolute;background-color: " + b.options.tintColour + ";filter:alpha(opacity=0);opacity: 0;width: " + b.nzWidth + "px;height: " + b.nzHeight + "px;";
        b.lensRound = "";
        "lens" == b.options.zoomType && (b.lensStyle = "background-position: 0px 0px;float: left;display: none;border: " + String(b.options.borderSize) + "px solid " + b.options.borderColour + ";width:" + String(b.options.lensSize) + "px;height:" + String(b.options.lensSize) + "px;background-repeat: no-repeat;position: absolute;");
        "round" == b.options.lensShape && (b.lensRound = "border-top-left-radius: " + String(b.options.lensSize / 2 + b.options.borderSize) + "px;border-top-right-radius: " + String(b.options.lensSize / 2 + b.options.borderSize) +
            "px;border-bottom-left-radius: " + String(b.options.lensSize / 2 + b.options.borderSize) + "px;border-bottom-right-radius: " + String(b.options.lensSize / 2 + b.options.borderSize) + "px;");
        b.zoomContainer = d('<div class="zoomContainer" style="-webkit-transform: translateZ(0);position:absolute;left:' + b.nzOffset.left + "px;top:" + b.nzOffset.top + "px;height:" + b.nzHeight + "px;width:" + b.nzWidth + 'px;"></div>');
        d("body").append(b.zoomContainer);
        b.options.containLensZoom && "lens" == b.options.zoomType && b.zoomContainer.css("overflow",
            "hidden");
        "inner" != b.options.zoomType && (b.zoomLens = d("<div class='zoomLens' style='" + b.lensStyle + b.lensRound + "'>&nbsp;</div>").appendTo(b.zoomContainer).click(function () {
            b.$elem.trigger("click")
        }), b.options.tint && (b.tintContainer = d("<div/>").addClass("tintContainer"), b.zoomTint = d("<div class='zoomTint' style='" + b.tintStyle + "'></div>"), b.zoomLens.wrap(b.tintContainer), b.zoomTintcss = b.zoomLens.after(b.zoomTint), b.zoomTintImage = d('<img style="position: absolute; left: 0px; top: 0px; max-width: none; width: ' +
            b.nzWidth + "px; height: " + b.nzHeight + 'px;" src="' + b.imageSrc + '">').appendTo(b.zoomLens).click(function () {
            b.$elem.trigger("click")
        })));
        isNaN(b.options.zoomWindowPosition) ? b.zoomWindow = d("<div style='z-index:999;left:" + b.windowOffsetLeft + "px;top:" + b.windowOffsetTop + "px;" + b.zoomWindowStyle + "' class='zoomWindow'>&nbsp;</div>").appendTo("body").click(function () {
            b.$elem.trigger("click")
        }) : b.zoomWindow = d("<div style='z-index:999;left:" + b.windowOffsetLeft + "px;top:" + b.windowOffsetTop + "px;" + b.zoomWindowStyle +
            "' class='zoomWindow'>&nbsp;</div>").appendTo(b.zoomContainer).click(function () {
            b.$elem.trigger("click")
        });
        b.zoomWindowContainer = d("<div/>").addClass("zoomWindowContainer").css("width", b.options.zoomWindowWidth);
        b.zoomWindow.wrap(b.zoomWindowContainer);
        "lens" == b.options.zoomType && b.zoomLens.css({backgroundImage: "url('" + b.imageSrc + "')"});
        "window" == b.options.zoomType && b.zoomWindow.css({backgroundImage: "url('" + b.imageSrc + "')"});
        "inner" == b.options.zoomType && b.zoomWindow.css({backgroundImage: "url('" + b.imageSrc +
            "')"});
        b.$elem.bind("touchmove", function (a) {
            a.preventDefault();
            b.setPosition(a.originalEvent.touches[0] || a.originalEvent.changedTouches[0])
        });
        b.zoomContainer.bind("touchmove", function (a) {
            "inner" == b.options.zoomType && b.showHideWindow("show");
            a.preventDefault();
            b.setPosition(a.originalEvent.touches[0] || a.originalEvent.changedTouches[0])
        });
        b.zoomContainer.bind("touchend", function (a) {
            b.showHideWindow("hide");
            b.options.showLens && b.showHideLens("hide");
            b.options.tint && "inner" != b.options.zoomType && b.showHideTint("hide")
        });
        b.$elem.bind("touchend", function (a) {
            b.showHideWindow("hide");
            b.options.showLens && b.showHideLens("hide");
            b.options.tint && "inner" != b.options.zoomType && b.showHideTint("hide")
        });
        b.options.showLens && (b.zoomLens.bind("touchmove", function (a) {
            a.preventDefault();
            b.setPosition(a.originalEvent.touches[0] || a.originalEvent.changedTouches[0])
        }), b.zoomLens.bind("touchend", function (a) {
            b.showHideWindow("hide");
            b.options.showLens && b.showHideLens("hide");
            b.options.tint && "inner" != b.options.zoomType && b.showHideTint("hide")
        }));
        b.$elem.bind("mousemove", function (a) {
            !1 == b.overWindow && b.setElements("show");
            if (b.lastX !== a.clientX || b.lastY !== a.clientY)b.setPosition(a), b.currentLoc = a;
            b.lastX = a.clientX;
            b.lastY = a.clientY
        });
        b.zoomContainer.bind("mousemove", function (a) {
            !1 == b.overWindow && b.setElements("show");
            if (b.lastX !== a.clientX || b.lastY !== a.clientY)b.setPosition(a), b.currentLoc = a;
            b.lastX = a.clientX;
            b.lastY = a.clientY
        });
        "inner" != b.options.zoomType && b.zoomLens.bind("mousemove", function (a) {
            if (b.lastX !== a.clientX || b.lastY !== a.clientY)b.setPosition(a),
                b.currentLoc = a;
            b.lastX = a.clientX;
            b.lastY = a.clientY
        });
        b.options.tint && "inner" != b.options.zoomType && b.zoomTint.bind("mousemove", function (a) {
            if (b.lastX !== a.clientX || b.lastY !== a.clientY)b.setPosition(a), b.currentLoc = a;
            b.lastX = a.clientX;
            b.lastY = a.clientY
        });
        "inner" == b.options.zoomType && b.zoomWindow.bind("mousemove", function (a) {
            if (b.lastX !== a.clientX || b.lastY !== a.clientY)b.setPosition(a), b.currentLoc = a;
            b.lastX = a.clientX;
            b.lastY = a.clientY
        });
        b.zoomContainer.add(b.$elem).mouseenter(function () {
            !1 == b.overWindow &&
            b.setElements("show")
        }).mouseleave(function () {
            b.scrollLock || b.setElements("hide")
        });
        "inner" != b.options.zoomType && b.zoomWindow.mouseenter(function () {
            b.overWindow = !0;
            b.setElements("hide")
        }).mouseleave(function () {
            b.overWindow = !1
        });
        b.minZoomLevel = b.options.minZoomLevel ? b.options.minZoomLevel : 2 * b.options.scrollZoomIncrement;
        b.options.scrollZoom && b.zoomContainer.add(b.$elem).bind("mousewheel DOMMouseScroll MozMousePixelScroll", function (a) {
            b.scrollLock = !0;
            clearTimeout(d.data(this, "timer"));
            d.data(this, "timer",
                setTimeout(function () {
                    b.scrollLock = !1
                }, 250));
            var e = a.originalEvent.wheelDelta || -1 * a.originalEvent.detail;
            a.stopImmediatePropagation();
            a.stopPropagation();
            a.preventDefault();
            0 < e / 120 ? b.currentZoomLevel >= b.minZoomLevel && b.changeZoomLevel(b.currentZoomLevel - b.options.scrollZoomIncrement) : b.options.maxZoomLevel ? b.currentZoomLevel <= b.options.maxZoomLevel && b.changeZoomLevel(parseFloat(b.currentZoomLevel) + b.options.scrollZoomIncrement) : b.changeZoomLevel(parseFloat(b.currentZoomLevel) + b.options.scrollZoomIncrement);
            return!1
        })
    }, setElements: function (b) {
        if (!this.options.zoomEnabled)return!1;
        "show" == b && this.isWindowSet && ("inner" == this.options.zoomType && this.showHideWindow("show"), "window" == this.options.zoomType && this.showHideWindow("show"), this.options.showLens && this.showHideLens("show"), this.options.tint && "inner" != this.options.zoomType && this.showHideTint("show"));
        "hide" == b && ("window" == this.options.zoomType && this.showHideWindow("hide"), this.options.tint || this.showHideWindow("hide"), this.options.showLens && this.showHideLens("hide"),
            this.options.tint && this.showHideTint("hide"))
    }, setPosition: function (b) {
        if (!this.options.zoomEnabled)return!1;
        this.nzHeight = this.$elem.height();
        this.nzWidth = this.$elem.width();
        this.nzOffset = this.$elem.offset();
        this.options.tint && "inner" != this.options.zoomType && (this.zoomTint.css({top: 0}), this.zoomTint.css({left: 0}));
        this.options.responsive && !this.options.scrollZoom && this.options.showLens && (lensHeight = this.nzHeight < this.options.zoomWindowWidth / this.widthRatio ? this.nzHeight : String(this.options.zoomWindowHeight /
            this.heightRatio), lensWidth = this.largeWidth < this.options.zoomWindowWidth ? this.nzWidth : this.options.zoomWindowWidth / this.widthRatio, this.widthRatio = this.largeWidth / this.nzWidth, this.heightRatio = this.largeHeight / this.nzHeight, "lens" != this.options.zoomType && (lensHeight = this.nzHeight < this.options.zoomWindowWidth / this.widthRatio ? this.nzHeight : String(this.options.zoomWindowHeight / this.heightRatio), lensWidth = this.options.zoomWindowWidth < this.options.zoomWindowWidth ? this.nzWidth : this.options.zoomWindowWidth /
            this.widthRatio, this.zoomLens.css("width", lensWidth), this.zoomLens.css("height", lensHeight), this.options.tint && (this.zoomTintImage.css("width", this.nzWidth), this.zoomTintImage.css("height", this.nzHeight))), "lens" == this.options.zoomType && this.zoomLens.css({width: String(this.options.lensSize) + "px", height: String(this.options.lensSize) + "px"}));
        this.zoomContainer.css({top: this.nzOffset.top});
        this.zoomContainer.css({left: this.nzOffset.left});
        this.mouseLeft = parseInt(b.pageX - this.nzOffset.left);
        this.mouseTop =
            parseInt(b.pageY - this.nzOffset.top);
        "window" == this.options.zoomType && (this.Etoppos = this.mouseTop < this.zoomLens.height() / 2, this.Eboppos = this.mouseTop > this.nzHeight - this.zoomLens.height() / 2 - 2 * this.options.lensBorderSize, this.Eloppos = this.mouseLeft < 0 + this.zoomLens.width() / 2, this.Eroppos = this.mouseLeft > this.nzWidth - this.zoomLens.width() / 2 - 2 * this.options.lensBorderSize);
        "inner" == this.options.zoomType && (this.Etoppos = this.mouseTop < this.nzHeight / 2 / this.heightRatio, this.Eboppos = this.mouseTop > this.nzHeight -
            this.nzHeight / 2 / this.heightRatio, this.Eloppos = this.mouseLeft < 0 + this.nzWidth / 2 / this.widthRatio, this.Eroppos = this.mouseLeft > this.nzWidth - this.nzWidth / 2 / this.widthRatio - 2 * this.options.lensBorderSize);
        0 >= this.mouseLeft || 0 > this.mouseTop || this.mouseLeft > this.nzWidth || this.mouseTop > this.nzHeight ? this.setElements("hide") : (this.options.showLens && (this.lensLeftPos = String(this.mouseLeft - this.zoomLens.width() / 2), this.lensTopPos = String(this.mouseTop - this.zoomLens.height() / 2)), this.Etoppos && (this.lensTopPos = 0),
            this.Eloppos && (this.tintpos = this.lensLeftPos = this.windowLeftPos = 0), "window" == this.options.zoomType && (this.Eboppos && (this.lensTopPos = Math.max(this.nzHeight - this.zoomLens.height() - 2 * this.options.lensBorderSize, 0)), this.Eroppos && (this.lensLeftPos = this.nzWidth - this.zoomLens.width() - 2 * this.options.lensBorderSize)), "inner" == this.options.zoomType && (this.Eboppos && (this.lensTopPos = Math.max(this.nzHeight - 2 * this.options.lensBorderSize, 0)), this.Eroppos && (this.lensLeftPos = this.nzWidth - this.nzWidth - 2 * this.options.lensBorderSize)),
            "lens" == this.options.zoomType && (this.windowLeftPos = String(-1 * ((b.pageX - this.nzOffset.left) * this.widthRatio - this.zoomLens.width() / 2)), this.windowTopPos = String(-1 * ((b.pageY - this.nzOffset.top) * this.heightRatio - this.zoomLens.height() / 2)), this.zoomLens.css({backgroundPosition: this.windowLeftPos + "px " + this.windowTopPos + "px"}), this.changeBgSize && (this.nzHeight > this.nzWidth ? ("lens" == this.options.zoomType && this.zoomLens.css({"background-size": this.largeWidth / this.newvalueheight + "px " + this.largeHeight / this.newvalueheight +
                "px"}), this.zoomWindow.css({"background-size": this.largeWidth / this.newvalueheight + "px " + this.largeHeight / this.newvalueheight + "px"})) : ("lens" == this.options.zoomType && this.zoomLens.css({"background-size": this.largeWidth / this.newvaluewidth + "px " + this.largeHeight / this.newvaluewidth + "px"}), this.zoomWindow.css({"background-size": this.largeWidth / this.newvaluewidth + "px " + this.largeHeight / this.newvaluewidth + "px"})), this.changeBgSize = !1), this.setWindowPostition(b)), this.options.tint && "inner" != this.options.zoomType &&
            this.setTintPosition(b), "window" == this.options.zoomType && this.setWindowPostition(b), "inner" == this.options.zoomType && this.setWindowPostition(b), this.options.showLens && (this.fullwidth && "lens" != this.options.zoomType && (this.lensLeftPos = 0), this.zoomLens.css({left: this.lensLeftPos + "px", top: this.lensTopPos + "px"})))
    }, showHideWindow: function (b) {
        "show" != b || this.isWindowActive || (this.options.zoomWindowFadeIn ? this.zoomWindow.stop(!0, !0, !1).fadeIn(this.options.zoomWindowFadeIn) : this.zoomWindow.show(), this.isWindowActive = !0);
        "hide" == b && this.isWindowActive && (this.options.zoomWindowFadeOut ? this.zoomWindow.stop(!0, !0).fadeOut(this.options.zoomWindowFadeOut) : this.zoomWindow.hide(), this.isWindowActive = !1)
    }, showHideLens: function (b) {
        "show" != b || this.isLensActive || (this.options.lensFadeIn ? this.zoomLens.stop(!0, !0, !1).fadeIn(this.options.lensFadeIn) : this.zoomLens.show(), this.isLensActive = !0);
        "hide" == b && this.isLensActive && (this.options.lensFadeOut ? this.zoomLens.stop(!0, !0).fadeOut(this.options.lensFadeOut) : this.zoomLens.hide(),
            this.isLensActive = !1)
    }, showHideTint: function (b) {
        "show" != b || this.isTintActive || (this.options.zoomTintFadeIn ? this.zoomTint.css({opacity: this.options.tintOpacity}).animate().stop(!0, !0).fadeIn("slow") : (this.zoomTint.css({opacity: this.options.tintOpacity}).animate(), this.zoomTint.show()), this.isTintActive = !0);
        "hide" == b && this.isTintActive && (this.options.zoomTintFadeOut ? this.zoomTint.stop(!0, !0).fadeOut(this.options.zoomTintFadeOut) : this.zoomTint.hide(), this.isTintActive = !1)
    }, setLensPostition: function (b) {
    },
        setWindowPostition: function (b) {
            var a = this;
            if (isNaN(a.options.zoomWindowPosition))a.externalContainer = d("#" + a.options.zoomWindowPosition), a.externalContainerWidth = a.externalContainer.width(), a.externalContainerHeight = a.externalContainer.height(), a.externalContainerOffset = a.externalContainer.offset(), a.windowOffsetTop = a.externalContainerOffset.top, a.windowOffsetLeft = a.externalContainerOffset.left; else switch (a.options.zoomWindowPosition) {
                case 1:
                    a.windowOffsetTop = a.options.zoomWindowOffety;
                    a.windowOffsetLeft = +a.nzWidth;
                    break;
                case 2:
                    a.options.zoomWindowHeight > a.nzHeight && (a.windowOffsetTop = -1 * (a.options.zoomWindowHeight / 2 - a.nzHeight / 2), a.windowOffsetLeft = a.nzWidth);
                    break;
                case 3:
                    a.windowOffsetTop = a.nzHeight - a.zoomWindow.height() - 2 * a.options.borderSize;
                    a.windowOffsetLeft = a.nzWidth;
                    break;
                case 4:
                    a.windowOffsetTop = a.nzHeight;
                    a.windowOffsetLeft = a.nzWidth;
                    break;
                case 5:
                    a.windowOffsetTop = a.nzHeight;
                    a.windowOffsetLeft = a.nzWidth - a.zoomWindow.width() - 2 * a.options.borderSize;
                    break;
                case 6:
                    a.options.zoomWindowHeight >
                        a.nzHeight && (a.windowOffsetTop = a.nzHeight, a.windowOffsetLeft = -1 * (a.options.zoomWindowWidth / 2 - a.nzWidth / 2 + 2 * a.options.borderSize));
                    break;
                case 7:
                    a.windowOffsetTop = a.nzHeight;
                    a.windowOffsetLeft = 0;
                    break;
                case 8:
                    a.windowOffsetTop = a.nzHeight;
                    a.windowOffsetLeft = -1 * (a.zoomWindow.width() + 2 * a.options.borderSize);
                    break;
                case 9:
                    a.windowOffsetTop = a.nzHeight - a.zoomWindow.height() - 2 * a.options.borderSize;
                    a.windowOffsetLeft = -1 * (a.zoomWindow.width() + 2 * a.options.borderSize);
                    break;
                case 10:
                    a.options.zoomWindowHeight > a.nzHeight &&
                    (a.windowOffsetTop = -1 * (a.options.zoomWindowHeight / 2 - a.nzHeight / 2), a.windowOffsetLeft = -1 * (a.zoomWindow.width() + 2 * a.options.borderSize));
                    break;
                case 11:
                    a.windowOffsetTop = a.options.zoomWindowOffety;
                    a.windowOffsetLeft = -1 * (a.zoomWindow.width() + 2 * a.options.borderSize);
                    break;
                case 12:
                    a.windowOffsetTop = -1 * (a.zoomWindow.height() + 2 * a.options.borderSize);
                    a.windowOffsetLeft = -1 * (a.zoomWindow.width() + 2 * a.options.borderSize);
                    break;
                case 13:
                    a.windowOffsetTop = -1 * (a.zoomWindow.height() + 2 * a.options.borderSize);
                    a.windowOffsetLeft =
                        0;
                    break;
                case 14:
                    a.options.zoomWindowHeight > a.nzHeight && (a.windowOffsetTop = -1 * (a.zoomWindow.height() + 2 * a.options.borderSize), a.windowOffsetLeft = -1 * (a.options.zoomWindowWidth / 2 - a.nzWidth / 2 + 2 * a.options.borderSize));
                    break;
                case 15:
                    a.windowOffsetTop = -1 * (a.zoomWindow.height() + 2 * a.options.borderSize);
                    a.windowOffsetLeft = a.nzWidth - a.zoomWindow.width() - 2 * a.options.borderSize;
                    break;
                case 16:
                    a.windowOffsetTop = -1 * (a.zoomWindow.height() + 2 * a.options.borderSize);
                    a.windowOffsetLeft = a.nzWidth;
                    break;
                default:
                    a.windowOffsetTop =
                        a.options.zoomWindowOffety, a.windowOffsetLeft = a.nzWidth
            }
            a.isWindowSet = !0;
            a.windowOffsetTop += a.options.zoomWindowOffety;
            a.windowOffsetLeft += a.options.zoomWindowOffetx;
            a.zoomWindow.css({top: a.windowOffsetTop});
            a.zoomWindow.css({left: a.windowOffsetLeft});
            "inner" == a.options.zoomType && (a.zoomWindow.css({top: 0}), a.zoomWindow.css({left: 0}));
            a.windowLeftPos = String(-1 * ((b.pageX - a.nzOffset.left) * a.widthRatio - a.zoomWindow.width() / 2));
            a.windowTopPos = String(-1 * ((b.pageY - a.nzOffset.top) * a.heightRatio - a.zoomWindow.height() /
                2));
            a.Etoppos && (a.windowTopPos = 0);
            a.Eloppos && (a.windowLeftPos = 0);
            a.Eboppos && (a.windowTopPos = -1 * (a.largeHeight / a.currentZoomLevel - a.zoomWindow.height()));
            a.Eroppos && (a.windowLeftPos = -1 * (a.largeWidth / a.currentZoomLevel - a.zoomWindow.width()));
            a.fullheight && (a.windowTopPos = 0);
            a.fullwidth && (a.windowLeftPos = 0);
            if ("window" == a.options.zoomType || "inner" == a.options.zoomType)1 == a.zoomLock && (1 >= a.widthRatio && (a.windowLeftPos = 0), 1 >= a.heightRatio && (a.windowTopPos = 0)), a.largeHeight < a.options.zoomWindowHeight && (a.windowTopPos =
                0), a.largeWidth < a.options.zoomWindowWidth && (a.windowLeftPos = 0), a.options.easing ? (a.xp || (a.xp = 0), a.yp || (a.yp = 0), a.loop || (a.loop = setInterval(function () {
                a.xp += (a.windowLeftPos - a.xp) / a.options.easingAmount;
                a.yp += (a.windowTopPos - a.yp) / a.options.easingAmount;
                a.scrollingLock ? (clearInterval(a.loop), a.xp = a.windowLeftPos, a.yp = a.windowTopPos, a.xp = -1 * ((b.pageX - a.nzOffset.left) * a.widthRatio - a.zoomWindow.width() / 2), a.yp = -1 * ((b.pageY - a.nzOffset.top) * a.heightRatio - a.zoomWindow.height() / 2), a.changeBgSize && (a.nzHeight >
                    a.nzWidth ? ("lens" == a.options.zoomType && a.zoomLens.css({"background-size": a.largeWidth / a.newvalueheight + "px " + a.largeHeight / a.newvalueheight + "px"}), a.zoomWindow.css({"background-size": a.largeWidth / a.newvalueheight + "px " + a.largeHeight / a.newvalueheight + "px"})) : ("lens" != a.options.zoomType && a.zoomLens.css({"background-size": a.largeWidth / a.newvaluewidth + "px " + a.largeHeight / a.newvalueheight + "px"}), a.zoomWindow.css({"background-size": a.largeWidth / a.newvaluewidth + "px " + a.largeHeight / a.newvaluewidth + "px"})),
                    a.changeBgSize = !1), a.zoomWindow.css({backgroundPosition: a.windowLeftPos + "px " + a.windowTopPos + "px"}), a.scrollingLock = !1, a.loop = !1) : (a.changeBgSize && (a.nzHeight > a.nzWidth ? ("lens" == a.options.zoomType && a.zoomLens.css({"background-size": a.largeWidth / a.newvalueheight + "px " + a.largeHeight / a.newvalueheight + "px"}), a.zoomWindow.css({"background-size": a.largeWidth / a.newvalueheight + "px " + a.largeHeight / a.newvalueheight + "px"})) : ("lens" != a.options.zoomType && a.zoomLens.css({"background-size": a.largeWidth / a.newvaluewidth +
                    "px " + a.largeHeight / a.newvaluewidth + "px"}), a.zoomWindow.css({"background-size": a.largeWidth / a.newvaluewidth + "px " + a.largeHeight / a.newvaluewidth + "px"})), a.changeBgSize = !1), a.zoomWindow.css({backgroundPosition: a.xp + "px " + a.yp + "px"}))
            }, 16))) : (a.changeBgSize && (a.nzHeight > a.nzWidth ? ("lens" == a.options.zoomType && a.zoomLens.css({"background-size": a.largeWidth / a.newvalueheight + "px " + a.largeHeight / a.newvalueheight + "px"}), a.zoomWindow.css({"background-size": a.largeWidth / a.newvalueheight + "px " + a.largeHeight /
                a.newvalueheight + "px"})) : ("lens" == a.options.zoomType && a.zoomLens.css({"background-size": a.largeWidth / a.newvaluewidth + "px " + a.largeHeight / a.newvaluewidth + "px"}), a.largeHeight / a.newvaluewidth < a.options.zoomWindowHeight ? a.zoomWindow.css({"background-size": a.largeWidth / a.newvaluewidth + "px " + a.largeHeight / a.newvaluewidth + "px"}) : a.zoomWindow.css({"background-size": a.largeWidth / a.newvalueheight + "px " + a.largeHeight / a.newvalueheight + "px"})), a.changeBgSize = !1), a.zoomWindow.css({backgroundPosition: a.windowLeftPos +
                "px " + a.windowTopPos + "px"}))
        }, setTintPosition: function (b) {
            this.nzOffset = this.$elem.offset();
            this.tintpos = String(-1 * (b.pageX - this.nzOffset.left - this.zoomLens.width() / 2));
            this.tintposy = String(-1 * (b.pageY - this.nzOffset.top - this.zoomLens.height() / 2));
            this.Etoppos && (this.tintposy = 0);
            this.Eloppos && (this.tintpos = 0);
            this.Eboppos && (this.tintposy = -1 * (this.nzHeight - this.zoomLens.height() - 2 * this.options.lensBorderSize));
            this.Eroppos && (this.tintpos = -1 * (this.nzWidth - this.zoomLens.width() - 2 * this.options.lensBorderSize));
            this.options.tint && (this.fullheight && (this.tintposy = 0), this.fullwidth && (this.tintpos = 0), this.zoomTintImage.css({left: this.tintpos + "px"}), this.zoomTintImage.css({top: this.tintposy + "px"}))
        }, swaptheimage: function (b, a) {
            var c = this, e = new Image;
            c.options.loadingIcon && (c.spinner = d("<div style=\"background: url('" + c.options.loadingIcon + "') no-repeat center;height:" + c.nzHeight + "px;width:" + c.nzWidth + 'px;z-index: 2000;position: absolute; background-position: center center;"></div>'), c.$elem.after(c.spinner));
            c.options.onImageSwap(c.$elem);
            e.onload = function () {
                c.largeWidth = e.width;
                c.largeHeight = e.height;
                c.zoomImage = a;
                c.zoomWindow.css({"background-size": c.largeWidth + "px " + c.largeHeight + "px"});
                c.zoomWindow.css({"background-size": c.largeWidth + "px " + c.largeHeight + "px"});
                c.swapAction(b, a)
            };
            e.src = a
        }, swapAction: function (b, a) {
            var c = this, e = new Image;
            e.onload = function () {
                c.nzHeight = e.height;
                c.nzWidth = e.width;
                c.options.onImageSwapComplete(c.$elem);
                c.doneCallback()
            };
            e.src = b;
            c.currentZoomLevel = c.options.zoomLevel;
            c.options.maxZoomLevel = !1;
            "lens" == c.options.zoomType && c.zoomLens.css({backgroundImage: "url('" + a + "')"});
            "window" == c.options.zoomType && c.zoomWindow.css({backgroundImage: "url('" + a + "')"});
            "inner" == c.options.zoomType && c.zoomWindow.css({backgroundImage: "url('" + a + "')"});
            c.currentImage = a;
            if (c.options.imageCrossfade) {
                var f = c.$elem, g = f.clone();
                c.$elem.attr("src", b);
                c.$elem.after(g);
                g.stop(!0).fadeOut(c.options.imageCrossfade, function () {
                    d(this).remove()
                });
                c.$elem.width("auto").removeAttr("width");
                c.$elem.height("auto").removeAttr("height");
                f.fadeIn(c.options.imageCrossfade);
                c.options.tint && "inner" != c.options.zoomType && (f = c.zoomTintImage, g = f.clone(), c.zoomTintImage.attr("src", a), c.zoomTintImage.after(g), g.stop(!0).fadeOut(c.options.imageCrossfade, function () {
                    d(this).remove()
                }), f.fadeIn(c.options.imageCrossfade), c.zoomTint.css({height: c.$elem.height()}), c.zoomTint.css({width: c.$elem.width()}));
                c.zoomContainer.css("height", c.$elem.height());
                c.zoomContainer.css("width", c.$elem.width());
                "inner" != c.options.zoomType || c.options.constrainType ||
                (c.zoomWrap.parent().css("height", c.$elem.height()), c.zoomWrap.parent().css("width", c.$elem.width()), c.zoomWindow.css("height", c.$elem.height()), c.zoomWindow.css("width", c.$elem.width()))
            } else c.$elem.attr("src", b), c.options.tint && (c.zoomTintImage.attr("src", a), c.zoomTintImage.attr("height", c.$elem.height()), c.zoomTintImage.css({height: c.$elem.height()}), c.zoomTint.css({height: c.$elem.height()})), c.zoomContainer.css("height", c.$elem.height()), c.zoomContainer.css("width", c.$elem.width());
            c.options.imageCrossfade &&
            (c.zoomWrap.css("height", c.$elem.height()), c.zoomWrap.css("width", c.$elem.width()));
            c.options.constrainType && ("height" == c.options.constrainType && (c.zoomContainer.css("height", c.options.constrainSize), c.zoomContainer.css("width", "auto"), c.options.imageCrossfade ? (c.zoomWrap.css("height", c.options.constrainSize), c.zoomWrap.css("width", "auto"), c.constwidth = c.zoomWrap.width()) : (c.$elem.css("height", c.options.constrainSize), c.$elem.css("width", "auto"), c.constwidth = c.$elem.width()), "inner" == c.options.zoomType &&
                (c.zoomWrap.parent().css("height", c.options.constrainSize), c.zoomWrap.parent().css("width", c.constwidth), c.zoomWindow.css("height", c.options.constrainSize), c.zoomWindow.css("width", c.constwidth)), c.options.tint && (c.tintContainer.css("height", c.options.constrainSize), c.tintContainer.css("width", c.constwidth), c.zoomTint.css("height", c.options.constrainSize), c.zoomTint.css("width", c.constwidth), c.zoomTintImage.css("height", c.options.constrainSize), c.zoomTintImage.css("width", c.constwidth))), "width" ==
                c.options.constrainType && (c.zoomContainer.css("height", "auto"), c.zoomContainer.css("width", c.options.constrainSize), c.options.imageCrossfade ? (c.zoomWrap.css("height", "auto"), c.zoomWrap.css("width", c.options.constrainSize), c.constheight = c.zoomWrap.height()) : (c.$elem.css("height", "auto"), c.$elem.css("width", c.options.constrainSize), c.constheight = c.$elem.height()), "inner" == c.options.zoomType && (c.zoomWrap.parent().css("height", c.constheight), c.zoomWrap.parent().css("width", c.options.constrainSize), c.zoomWindow.css("height",
                c.constheight), c.zoomWindow.css("width", c.options.constrainSize)), c.options.tint && (c.tintContainer.css("height", c.constheight), c.tintContainer.css("width", c.options.constrainSize), c.zoomTint.css("height", c.constheight), c.zoomTint.css("width", c.options.constrainSize), c.zoomTintImage.css("height", c.constheight), c.zoomTintImage.css("width", c.options.constrainSize))))
        }, doneCallback: function () {
            this.options.loadingIcon && this.spinner.hide();
            this.nzOffset = this.$elem.offset();
            this.nzWidth = this.$elem.width();
            this.nzHeight = this.$elem.height();
            this.currentZoomLevel = this.options.zoomLevel;
            this.widthRatio = this.largeWidth / this.nzWidth;
            this.heightRatio = this.largeHeight / this.nzHeight;
            "window" == this.options.zoomType && (lensHeight = this.nzHeight < this.options.zoomWindowWidth / this.widthRatio ? this.nzHeight : String(this.options.zoomWindowHeight / this.heightRatio), lensWidth = this.options.zoomWindowWidth < this.options.zoomWindowWidth ? this.nzWidth : this.options.zoomWindowWidth / this.widthRatio, this.zoomLens && (this.zoomLens.css("width",
                lensWidth), this.zoomLens.css("height", lensHeight)))
        }, getCurrentImage: function () {
            return this.zoomImage
        }, getGalleryList: function () {
            var b = this;
            b.gallerylist = [];
            b.options.gallery ? d("#" + b.options.gallery + " a").each(function () {
                var a = "";
                d(this).data("zoom-image") ? a = d(this).data("zoom-image") : d(this).data("image") && (a = d(this).data("image"));
                a == b.zoomImage ? b.gallerylist.unshift({href: "" + a + "", title: d(this).find("img").attr("title")}) : b.gallerylist.push({href: "" + a + "", title: d(this).find("img").attr("title")})
            }) :
                b.gallerylist.push({href: "" + b.zoomImage + "", title: d(this).find("img").attr("title")});
            return b.gallerylist
        }, changeZoomLevel: function (b) {
            this.scrollingLock = !0;
            this.newvalue = parseFloat(b).toFixed(2);
            newvalue = parseFloat(b).toFixed(2);
            maxheightnewvalue = this.largeHeight / (this.options.zoomWindowHeight / this.nzHeight * this.nzHeight);
            maxwidthtnewvalue = this.largeWidth / (this.options.zoomWindowWidth / this.nzWidth * this.nzWidth);
            "inner" != this.options.zoomType && (maxheightnewvalue <= newvalue ? (this.heightRatio = this.largeHeight /
                maxheightnewvalue / this.nzHeight, this.newvalueheight = maxheightnewvalue, this.fullheight = !0) : (this.heightRatio = this.largeHeight / newvalue / this.nzHeight, this.newvalueheight = newvalue, this.fullheight = !1), maxwidthtnewvalue <= newvalue ? (this.widthRatio = this.largeWidth / maxwidthtnewvalue / this.nzWidth, this.newvaluewidth = maxwidthtnewvalue, this.fullwidth = !0) : (this.widthRatio = this.largeWidth / newvalue / this.nzWidth, this.newvaluewidth = newvalue, this.fullwidth = !1), "lens" == this.options.zoomType && (maxheightnewvalue <= newvalue ?
                (this.fullwidth = !0, this.newvaluewidth = maxheightnewvalue) : (this.widthRatio = this.largeWidth / newvalue / this.nzWidth, this.newvaluewidth = newvalue, this.fullwidth = !1)));
            "inner" == this.options.zoomType && (maxheightnewvalue = parseFloat(this.largeHeight / this.nzHeight).toFixed(2), maxwidthtnewvalue = parseFloat(this.largeWidth / this.nzWidth).toFixed(2), newvalue > maxheightnewvalue && (newvalue = maxheightnewvalue), newvalue > maxwidthtnewvalue && (newvalue = maxwidthtnewvalue), maxheightnewvalue <= newvalue ? (this.heightRatio = this.largeHeight /
                newvalue / this.nzHeight, this.newvalueheight = newvalue > maxheightnewvalue ? maxheightnewvalue : newvalue, this.fullheight = !0) : (this.heightRatio = this.largeHeight / newvalue / this.nzHeight, this.newvalueheight = newvalue > maxheightnewvalue ? maxheightnewvalue : newvalue, this.fullheight = !1), maxwidthtnewvalue <= newvalue ? (this.widthRatio = this.largeWidth / newvalue / this.nzWidth, this.newvaluewidth = newvalue > maxwidthtnewvalue ? maxwidthtnewvalue : newvalue, this.fullwidth = !0) : (this.widthRatio = this.largeWidth / newvalue / this.nzWidth, this.newvaluewidth =
                newvalue, this.fullwidth = !1));
            scrcontinue = !1;
            "inner" == this.options.zoomType && (this.nzWidth > this.nzHeight && (this.newvaluewidth <= maxwidthtnewvalue ? scrcontinue = !0 : (scrcontinue = !1, this.fullwidth = this.fullheight = !0)), this.nzHeight > this.nzWidth && (this.newvaluewidth <= maxwidthtnewvalue ? scrcontinue = !0 : (scrcontinue = !1, this.fullwidth = this.fullheight = !0)));
            "inner" != this.options.zoomType && (scrcontinue = !0);
            scrcontinue && (this.zoomLock = 0, this.changeZoom = !0, this.options.zoomWindowHeight / this.heightRatio <= this.nzHeight &&
                (this.currentZoomLevel = this.newvalueheight, "lens" != this.options.zoomType && "inner" != this.options.zoomType && (this.changeBgSize = !0, this.zoomLens.css({height: String(this.options.zoomWindowHeight / this.heightRatio) + "px"})), "lens" == this.options.zoomType || "inner" == this.options.zoomType) && (this.changeBgSize = !0), this.options.zoomWindowWidth / this.widthRatio <= this.nzWidth && ("inner" != this.options.zoomType && this.newvaluewidth > this.newvalueheight && (this.currentZoomLevel = this.newvaluewidth), "lens" != this.options.zoomType &&
                "inner" != this.options.zoomType && (this.changeBgSize = !0, this.zoomLens.css({width: String(this.options.zoomWindowWidth / this.widthRatio) + "px"})), "lens" == this.options.zoomType || "inner" == this.options.zoomType) && (this.changeBgSize = !0), "inner" == this.options.zoomType && (this.changeBgSize = !0, this.nzWidth > this.nzHeight && (this.currentZoomLevel = this.newvaluewidth), this.nzHeight > this.nzWidth && (this.currentZoomLevel = this.newvaluewidth)));
            this.setPosition(this.currentLoc)
        }, closeAll: function () {
            self.zoomWindow && self.zoomWindow.hide();
            self.zoomLens && self.zoomLens.hide();
            self.zoomTint && self.zoomTint.hide()
        }, changeState: function (b) {
            "enable" == b && (this.options.zoomEnabled = !0);
            "disable" == b && (this.options.zoomEnabled = !1)
        }};
    d.fn.elevateZoom = function (b) {
        return this.each(function () {
            var a = Object.create(k);
            a.init(b, this);
            d.data(this, "elevateZoom", a)
        })
    };
    d.fn.elevateZoom.options = {zoomActivation: "hover", zoomEnabled: !0, preloading: 1, zoomLevel: 1, scrollZoom: !1, scrollZoomIncrement: 0.1, minZoomLevel: !1, maxZoomLevel: !1, easing: !1, easingAmount: 12, lensSize: 200,
        zoomWindowWidth: 400, zoomWindowHeight: 400, zoomWindowOffetx: 0, zoomWindowOffety: 0, zoomWindowPosition: 1, zoomWindowBgColour: "#fff", lensFadeIn: !1, lensFadeOut: !1, debug: !1, zoomWindowFadeIn: !1, zoomWindowFadeOut: !1, zoomWindowAlwaysShow: !1, zoomTintFadeIn: !1, zoomTintFadeOut: !1, borderSize: 4, showLens: !0, borderColour: "#888", lensBorderSize: 1, lensBorderColour: "#000", lensShape: "square", zoomType: "window", containLensZoom: !1, lensColour: "white", lensOpacity: 0.4, lenszoom: !1, tint: !1, tintColour: "#333", tintOpacity: 0.4, gallery: !1,
        galleryActiveClass: "zoomGalleryActive", imageCrossfade: !1, constrainType: !1, constrainSize: !1, loadingIcon: !1, cursor: "default", responsive: !0, onComplete: d.noop, onZoomedImageLoaded: function () {
        }, onImageSwap: d.noop, onImageSwapComplete: d.noop}
})(jQuery, window, document);
(function (g) {
    var q = {vertical: !1, rtl: !1, start: 1, offset: 1, size: null, scroll: 3, visible: null, animation: "normal", easing: "swing", auto: 0, wrap: null, initCallback: null, setupCallback: null, reloadCallback: null, itemLoadCallback: null, itemFirstInCallback: null, itemFirstOutCallback: null, itemLastInCallback: null, itemLastOutCallback: null, itemVisibleInCallback: null, itemVisibleOutCallback: null, animationStepCallback: null, buttonNextHTML: "<div><span class='fa fa-angle-right'></span></div>", buttonPrevHTML: "<div><span class='fa fa-angle-left'></span></div>", buttonNextEvent: "click", buttonPrevEvent: "click", buttonNextCallback: null, buttonPrevCallback: null, itemFallbackDimension: null}, m = !1;
    g(window).bind("load.jcarousel", function () {
        m = !0
    });
    g.jcarousel = function (a, c) {
        this.options = g.extend({}, q, c || {});
        this.autoStopped = this.locked = !1;
        this.buttonPrevState = this.buttonNextState = this.buttonPrev = this.buttonNext = this.list = this.clip = this.container = null;
        if (!c || c.rtl === void 0)this.options.rtl = (g(a).attr("dir") || g("html").attr("dir") || "").toLowerCase() == "rtl";
        this.wh = !this.options.vertical ? "width" : "height";
        this.lt = !this.options.vertical ? this.options.rtl ? "right" : "left" : "top";
        for (var b = "", d = a.className.split(" "), f = 0; f < d.length; f++)if (d[f].indexOf("jcarousel-skin") != -1) {
            g(a).removeClass(d[f]);
            b = d[f];
            break
        }
        a.nodeName.toUpperCase() == "UL" || a.nodeName.toUpperCase() == "OL" ? (this.list = g(a), this.clip = this.list.parents(".jcarousel-clip"), this.container = this.list.parents(".jcarousel-container")) : (this.container = g(a), this.list = this.container.find("ul,ol").eq(0), this.clip = this.container.find(".jcarousel-clip"));
        if (this.clip.size() === 0)this.clip = this.list.wrap("<div></div>").parent();
        if (this.container.size() === 0)this.container = this.clip.wrap("<div></div>").parent();
        b !== "" && this.container.parent()[0].className.indexOf("jcarousel-skin") == -1 && this.container.wrap('<div class=" ' + b + '"></div>');
        this.buttonPrev = g(".jcarousel-prev", this.container);
        if (this.buttonPrev.size() === 0 && this.options.buttonPrevHTML !== null)this.buttonPrev = g(this.options.buttonPrevHTML).appendTo(this.container);
        this.buttonPrev.addClass(this.className("jcarousel-prev"));
        this.buttonNext = g(".jcarousel-next", this.container);
        if (this.buttonNext.size() === 0 && this.options.buttonNextHTML !== null)this.buttonNext = g(this.options.buttonNextHTML).appendTo(this.container);
        this.buttonNext.addClass(this.className("jcarousel-next"));
        this.clip.addClass(this.className("jcarousel-clip")).css({position: "relative"});
        this.list.addClass(this.className("jcarousel-list")).css({overflow: "hidden", position: "relative", top: 0, margin: 0, padding: 0}).css(this.options.rtl ? "right" : "left", 0);
        this.container.addClass(this.className("jcarousel-container")).css({position: "relative"});
        !this.options.vertical && this.options.rtl && this.container.addClass("jcarousel-direction-rtl").attr("dir", "rtl");
        var j = this.options.visible !== null ? Math.ceil(this.clipping() / this.options.visible) : null, b = this.list.children("li"), e = this;
        if (b.size() > 0) {
            var h = 0, i = this.options.offset;
            b.each(function () {
                e.format(this, i++);
                h += e.dimension(this, j)
            });
            this.list.css(this.wh, h + 100 + "px");
            if (!c || c.size === void 0)this.options.size = b.size()
        }
        this.container.css("display", "block");
        this.buttonNext.css("display", "block");
        this.buttonPrev.css("display", "block");
        this.funcNext = function () {
            e.next()
        };
        this.funcPrev = function () {
            e.prev()
        };
        this.funcResize = function () {
            e.resizeTimer && clearTimeout(e.resizeTimer);
            e.resizeTimer = setTimeout(function () {
                e.reload()
            }, 100)
        };
        this.options.initCallback !== null && this.options.initCallback(this, "init");
        !m && g.browser.safari ? (this.buttons(!1, !1), g(window).bind("load.jcarousel", function () {
            e.setup()
        })) : this.setup()
    };
    var f = g.jcarousel;
    f.fn = f.prototype = {jcarousel: "0.2.8"};
    f.fn.extend = f.extend = g.extend;
    f.fn.extend({setup: function () {
        this.prevLast = this.prevFirst = this.last = this.first = null;
        this.animating = !1;
        this.tail = this.resizeTimer = this.timer = null;
        this.inTail = !1;
        if (!this.locked) {
            this.list.css(this.lt, this.pos(this.options.offset) + "px");
            var a = this.pos(this.options.start, !0);
            this.prevFirst = this.prevLast = null;
            this.animate(a, !1);
            g(window).unbind("resize.jcarousel", this.funcResize).bind("resize.jcarousel", this.funcResize);
            this.options.setupCallback !== null && this.options.setupCallback(this)
        }
    }, reset: function () {
        this.list.empty();
        this.list.css(this.lt, "0px");
        this.list.css(this.wh, "10px");
        this.options.initCallback !== null && this.options.initCallback(this, "reset");
        this.setup()
    }, reload: function () {
        this.tail !== null && this.inTail && this.list.css(this.lt, f.intval(this.list.css(this.lt)) + this.tail);
        this.tail = null;
        this.inTail = !1;
        this.options.reloadCallback !== null && this.options.reloadCallback(this);
        if (this.options.visible !== null) {
            var a = this, c = Math.ceil(this.clipping() / this.options.visible), b = 0, d = 0;
            this.list.children("li").each(function (f) {
                b += a.dimension(this, c);
                f + 1 < a.first && (d = b)
            });
            this.list.css(this.wh, b + "px");
            this.list.css(this.lt, -d + "px")
        }
        this.scroll(this.first, !1)
    }, lock: function () {
        this.locked = !0;
        this.buttons()
    }, unlock: function () {
        this.locked = !1;
        this.buttons()
    }, size: function (a) {
        if (a !== void 0)this.options.size = a, this.locked || this.buttons();
        return this.options.size
    }, has: function (a, c) {
        if (c === void 0 || !c)c = a;
        if (this.options.size !== null && c > this.options.size)c = this.options.size;
        for (var b = a; b <= c; b++) {
            var d = this.get(b);
            if (!d.length || d.hasClass("jcarousel-item-placeholder"))return!1
        }
        return!0
    }, get: function (a) {
        return g(">.jcarousel-item-" + a, this.list)
    }, add: function (a, c) {
        var b = this.get(a), d = 0, p = g(c);
        if (b.length === 0)for (var j, e = f.intval(a), b = this.create(a); ;) {
            if (j = this.get(--e), e <= 0 || j.length) {
                e <= 0 ? this.list.prepend(b) : j.after(b);
                break
            }
        } else d = this.dimension(b);
        p.get(0).nodeName.toUpperCase() == "LI" ? (b.replaceWith(p), b = p) : b.empty().append(c);
        this.format(b.removeClass(this.className("jcarousel-item-placeholder")), a);
        p = this.options.visible !== null ? Math.ceil(this.clipping() / this.options.visible) : null;
        d = this.dimension(b, p) - d;
        a > 0 && a < this.first && this.list.css(this.lt, f.intval(this.list.css(this.lt)) - d + "px");
        this.list.css(this.wh, f.intval(this.list.css(this.wh)) + d + "px");
        return b
    }, remove: function (a) {
        var c = this.get(a);
        if (c.length && !(a >= this.first && a <= this.last)) {
            var b = this.dimension(c);
            a < this.first && this.list.css(this.lt, f.intval(this.list.css(this.lt)) + b + "px");
            c.remove();
            this.list.css(this.wh, f.intval(this.list.css(this.wh)) - b + "px")
        }
    }, next: function () {
        this.tail !== null && !this.inTail ? this.scrollTail(!1) : this.scroll((this.options.wrap == "both" || this.options.wrap == "last") && this.options.size !== null && this.last == this.options.size ? 1 : this.first + this.options.scroll)
    }, prev: function () {
        this.tail !== null && this.inTail ? this.scrollTail(!0) : this.scroll((this.options.wrap == "both" || this.options.wrap == "first") && this.options.size !== null && this.first == 1 ? this.options.size : this.first - this.options.scroll)
    }, scrollTail: function (a) {
        if (!this.locked && !this.animating && this.tail) {
            this.pauseAuto();
            var c = f.intval(this.list.css(this.lt)), c = !a ? c - this.tail : c + this.tail;
            this.inTail = !a;
            this.prevFirst = this.first;
            this.prevLast = this.last;
            this.animate(c)
        }
    }, scroll: function (a, c) {
        !this.locked && !this.animating && (this.pauseAuto(), this.animate(this.pos(a), c))
    }, pos: function (a, c) {
        var b = f.intval(this.list.css(this.lt));
        if (this.locked || this.animating)return b;
        this.options.wrap != "circular" && (a = a < 1 ? 1 : this.options.size && a > this.options.size ? this.options.size : a);
        for (var d = this.first > a, g = this.options.wrap != "circular" && this.first <= 1 ? 1 : this.first, j = d ? this.get(g) : this.get(this.last), e = d ? g : g - 1, h = null, i = 0, k = !1, l = 0; d ? --e >= a : ++e < a;) {
            h = this.get(e);
            k = !h.length;
            if (h.length === 0 && (h = this.create(e).addClass(this.className("jcarousel-item-placeholder")), j[d ? "before" : "after"](h), this.first !== null && this.options.wrap == "circular" && this.options.size !== null && (e <= 0 || e > this.options.size)))j = this.get(this.index(e)), j.length && (h = this.add(e, j.clone(!0)));
            j = h;
            l = this.dimension(h);
            k && (i += l);
            if (this.first !== null && (this.options.wrap == "circular" || e >= 1 && (this.options.size === null || e <= this.options.size)))b = d ? b + l : b - l
        }
        for (var g = this.clipping(), m = [], o = 0, n = 0, j = this.get(a - 1), e = a; ++o;) {
            h = this.get(e);
            k = !h.length;
            if (h.length === 0) {
                h = this.create(e).addClass(this.className("jcarousel-item-placeholder"));
                if (j.length === 0)this.list.prepend(h); else j[d ? "before" : "after"](h);
                if (this.first !== null && this.options.wrap == "circular" && this.options.size !== null && (e <= 0 || e > this.options.size))j = this.get(this.index(e)), j.length && (h = this.add(e, j.clone(!0)))
            }
            j = h;
            l = this.dimension(h);
            if (l === 0)throw Error("jCarousel: No width/height set for items. This will cause an infinite loop. Aborting...");
            this.options.wrap != "circular" && this.options.size !== null && e > this.options.size ? m.push(h) : k && (i += l);
            n += l;
            if (n >= g)break;
            e++
        }
        for (h = 0; h < m.length; h++)m[h].remove();
        i > 0 && (this.list.css(this.wh, this.dimension(this.list) + i + "px"), d && (b -= i, this.list.css(this.lt, f.intval(this.list.css(this.lt)) - i + "px")));
        i = a + o - 1;
        if (this.options.wrap != "circular" && this.options.size && i > this.options.size)i = this.options.size;
        if (e > i) {
            o = 0;
            e = i;
            for (n = 0; ++o;) {
                h = this.get(e--);
                if (!h.length)break;
                n += this.dimension(h);
                if (n >= g)break
            }
        }
        e = i - o + 1;
        this.options.wrap != "circular" && e < 1 && (e = 1);
        if (this.inTail && d)b += this.tail, this.inTail = !1;
        this.tail = null;
        if (this.options.wrap != "circular" && i == this.options.size && i - o + 1 >= 1 && (d = f.intval(this.get(i).css(!this.options.vertical ? "marginRight" : "marginBottom")), n - d > g))this.tail = n - g - d;
        if (c && a === this.options.size && this.tail)b -= this.tail, this.inTail = !0;
        for (; a-- > e;)b += this.dimension(this.get(a));
        this.prevFirst = this.first;
        this.prevLast = this.last;
        this.first = e;
        this.last = i;
        return b
    }, animate: function (a, c) {
        if (!this.locked && !this.animating) {
            this.animating = !0;
            var b = this, d = function () {
                b.animating = !1;
                a === 0 && b.list.css(b.lt, 0);
                !b.autoStopped && (b.options.wrap == "circular" || b.options.wrap == "both" || b.options.wrap == "last" || b.options.size === null || b.last < b.options.size || b.last == b.options.size && b.tail !== null && !b.inTail) && b.startAuto();
                b.buttons();
                b.notify("onAfterAnimation");
                if (b.options.wrap == "circular" && b.options.size !== null)for (var c = b.prevFirst; c <= b.prevLast; c++)c !== null && !(c >= b.first && c <= b.last) && (c < 1 || c > b.options.size) && b.remove(c)
            };
            this.notify("onBeforeAnimation");
            if (!this.options.animation || c === !1)this.list.css(this.lt, a + "px"), d(); else {
                var f = !this.options.vertical ? this.options.rtl ? {right: a} : {left: a} : {top: a}, d = {duration: this.options.animation, easing: this.options.easing, complete: d};
                if (g.isFunction(this.options.animationStepCallback))d.step = this.options.animationStepCallback;
                this.list.animate(f, d)
            }
        }
    }, startAuto: function (a) {
        if (a !== void 0)this.options.auto = a;
        if (this.options.auto === 0)return this.stopAuto();
        if (this.timer === null) {
            this.autoStopped = !1;
            var c = this;
            this.timer = window.setTimeout(function () {
                c.next()
            }, this.options.auto * 1E3)
        }
    }, stopAuto: function () {
        this.pauseAuto();
        this.autoStopped = !0
    }, pauseAuto: function () {
        if (this.timer !== null)window.clearTimeout(this.timer), this.timer = null
    }, buttons: function (a, c) {
        if (a == null && (a = !this.locked && this.options.size !== 0 && (this.options.wrap && this.options.wrap != "first" || this.options.size === null || this.last < this.options.size), !this.locked && (!this.options.wrap || this.options.wrap == "first") && this.options.size !== null && this.last >= this.options.size))a = this.tail !== null && !this.inTail;
        if (c == null && (c = !this.locked && this.options.size !== 0 && (this.options.wrap && this.options.wrap != "last" || this.first > 1), !this.locked && (!this.options.wrap || this.options.wrap == "last") && this.options.size !== null && this.first == 1))c = this.tail !== null && this.inTail;
        var b = this;
        this.buttonNext.size() > 0 ? (this.buttonNext.unbind(this.options.buttonNextEvent + ".jcarousel", this.funcNext), a && this.buttonNext.bind(this.options.buttonNextEvent + ".jcarousel", this.funcNext), this.buttonNext[a ? "removeClass" : "addClass"](this.className("jcarousel-next-disabled")).attr("disabled", a ? !1 : !0), this.options.buttonNextCallback !== null && this.buttonNext.data("jcarouselstate") != a && this.buttonNext.each(function () {
            b.options.buttonNextCallback(b, this, a)
        }).data("jcarouselstate", a)) : this.options.buttonNextCallback !== null && this.buttonNextState != a && this.options.buttonNextCallback(b, null, a);
        this.buttonPrev.size() > 0 ? (this.buttonPrev.unbind(this.options.buttonPrevEvent + ".jcarousel", this.funcPrev), c && this.buttonPrev.bind(this.options.buttonPrevEvent + ".jcarousel", this.funcPrev), this.buttonPrev[c ? "removeClass" : "addClass"](this.className("jcarousel-prev-disabled")).attr("disabled", c ? !1 : !0), this.options.buttonPrevCallback !== null && this.buttonPrev.data("jcarouselstate") != c && this.buttonPrev.each(function () {
            b.options.buttonPrevCallback(b, this, c)
        }).data("jcarouselstate", c)) : this.options.buttonPrevCallback !== null && this.buttonPrevState != c && this.options.buttonPrevCallback(b, null, c);
        this.buttonNextState = a;
        this.buttonPrevState = c
    }, notify: function (a) {
        var c = this.prevFirst === null ? "init" : this.prevFirst < this.first ? "next" : "prev";
        this.callback("itemLoadCallback", a, c);
        this.prevFirst !== this.first && (this.callback("itemFirstInCallback", a, c, this.first), this.callback("itemFirstOutCallback", a, c, this.prevFirst));
        this.prevLast !== this.last && (this.callback("itemLastInCallback", a, c, this.last), this.callback("itemLastOutCallback", a, c, this.prevLast));
        this.callback("itemVisibleInCallback", a, c, this.first, this.last, this.prevFirst, this.prevLast);
        this.callback("itemVisibleOutCallback", a, c, this.prevFirst, this.prevLast, this.first, this.last)
    }, callback: function (a, c, b, d, f, j, e) {
        if (!(this.options[a] == null || typeof this.options[a] != "object" && c != "onAfterAnimation")) {
            var h = typeof this.options[a] == "object" ? this.options[a][c] : this.options[a];
            if (g.isFunction(h)) {
                var i = this;
                if (d === void 0)h(i, b, c); else if (f === void 0)this.get(d).each(function () {
                    h(i, this, d, b, c)
                }); else for (var a = function (a) {
                    i.get(a).each(function () {
                        h(i, this, a, b, c)
                    })
                }, k = d; k <= f; k++)k !== null && !(k >= j && k <= e) && a(k)
            }
        }
    }, create: function (a) {
        return this.format("<li></li>", a)
    }, format: function (a, c) {
        for (var a = g(a), b = a.get(0).className.split(" "), d = 0; d < b.length; d++)b[d].indexOf("jcarousel-") != -1 && a.removeClass(b[d]);
        a.addClass(this.className("jcarousel-item")).addClass(this.className("jcarousel-item-" + c)).css({"float": this.options.rtl ? "right" : "left", "list-style": "none"}).attr("jcarouselindex", c);
        return a
    }, className: function (a) {
        return a + " " + a + (!this.options.vertical ? "-horizontal" : "-vertical")
    }, dimension: function (a, c) {
        var b = g(a);
        if (c == null)return!this.options.vertical ? b.outerWidth(!0) || f.intval(this.options.itemFallbackDimension) : b.outerHeight(!0) || f.intval(this.options.itemFallbackDimension); else {
            var d = !this.options.vertical ? c - f.intval(b.css("marginLeft")) - f.intval(b.css("marginRight")) : c - f.intval(b.css("marginTop")) - f.intval(b.css("marginBottom"));
            g(b).css(this.wh, d + "px");
            return this.dimension(b)
        }
    }, clipping: function () {
        return!this.options.vertical ? this.clip[0].offsetWidth - f.intval(this.clip.css("borderLeftWidth")) - f.intval(this.clip.css("borderRightWidth")) : this.clip[0].offsetHeight - f.intval(this.clip.css("borderTopWidth")) - f.intval(this.clip.css("borderBottomWidth"))
    }, index: function (a, c) {
        if (c == null)c = this.options.size;
        return Math.round(((a - 1) / c - Math.floor((a - 1) / c)) * c) + 1
    }});
    f.extend({defaults: function (a) {
        return g.extend(q, a || {})
    }, intval: function (a) {
        a = parseInt(a, 10);
        return isNaN(a) ? 0 : a
    }, windowLoaded: function () {
        m = !0
    }});
    g.fn.jcarousel = function (a) {
        if (typeof a == "string") {
            var c = g(this).data("jcarousel"), b = Array.prototype.slice.call(arguments, 1);
            return c[a].apply(c, b)
        } else return this.each(function () {
            var b = g(this).data("jcarousel");
            b ? (a && g.extend(b.options, a), b.reload()) : g(this).data("jcarousel", new f(this, a))
        })
    }
})(jQuery);

(function (a) {
    function d(b) {
        var c = b || window.event, d = [].slice.call(arguments, 1), e = 0, f = !0, g = 0, h = 0;
        return b = a.event.fix(c), b.type = "mousewheel", c.wheelDelta && (e = c.wheelDelta / 120), c.detail && (e = -c.detail / 3), h = e, c.axis !== undefined && c.axis === c.HORIZONTAL_AXIS && (h = 0, g = -1 * e), c.wheelDeltaY !== undefined && (h = c.wheelDeltaY / 120), c.wheelDeltaX !== undefined && (g = -1 * c.wheelDeltaX / 120), d.unshift(b, e, g, h), (a.event.dispatch || a.event.handle).apply(this, d)
    }

    var b = ["DOMMouseScroll", "mousewheel"];
    if (a.event.fixHooks)for (var c = b.length; c;)a.event.fixHooks[b[--c]] = a.event.mouseHooks;
    a.event.special.mousewheel = {setup: function () {
        if (this.addEventListener)for (var a = b.length; a;)this.addEventListener(b[--a], d, !1); else this.onmousewheel = d
    }, teardown: function () {
        if (this.removeEventListener)for (var a = b.length; a;)this.removeEventListener(b[--a], d, !1); else this.onmousewheel = null
    }}, a.fn.extend({mousewheel: function (a) {
        return a ? this.bind("mousewheel", a) : this.trigger("mousewheel")
    }, unmousewheel: function (a) {
        return this.unbind("mousewheel", a)
    }})
})(jQuery);
/*custom scrollbar*/
(function (c) {
    var b = {init: function (e) {
        var f = {set_width: false, set_height: false, horizontalScroll: false, scrollInertia: 950, mouseWheel: true, mouseWheelPixels: "auto", autoDraggerLength: true, autoHideScrollbar: false, snapAmount: null, snapOffset: 0, scrollButtons: {enable: false, scrollType: "continuous", scrollSpeed: "auto", scrollAmount: 40}, advanced: {updateOnBrowserResize: true, updateOnContentResize: false, autoExpandHorizontalScroll: false, autoScrollOnFocus: true, normalizeMouseWheelDelta: false}, contentTouchScroll: true, callbacks: {onScrollStart: function () {
        }, onScroll: function () {
        }, onTotalScroll: function () {
        }, onTotalScrollBack: function () {
        }, onTotalScrollOffset: 0, onTotalScrollBackOffset: 0, whileScrolling: function () {
        }}, theme: "light"}, e = c.extend(true, f, e);
        return this.each(function () {
            var m = c(this);
            if (e.set_width) {
                m.css("width", e.set_width)
            }
            if (e.set_height) {
                m.css("height", e.set_height)
            }
            if (!c(document).data("mCustomScrollbar-index")) {
                c(document).data("mCustomScrollbar-index", "1")
            } else {
                var t = parseInt(c(document).data("mCustomScrollbar-index"));
                c(document).data("mCustomScrollbar-index", t + 1)
            }
            m.wrapInner("<div class='mCustomScrollBox mCS-" + e.theme + "' id='mCSB_" + c(document).data("mCustomScrollbar-index") + "' style='position:relative; height:100%; overflow:hidden; max-width:100%;' />").addClass("mCustomScrollbar _mCS_" + c(document).data("mCustomScrollbar-index"));
            var g = m.children(".mCustomScrollBox");
            if (e.horizontalScroll) {
                g.addClass("mCSB_horizontal").wrapInner("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />");
                var k = g.children(".mCSB_h_wrapper");
                k.wrapInner("<div class='mCSB_container' style='position:absolute; left:0;' />").children(".mCSB_container").css({width: k.children().outerWidth(), position: "relative"}).unwrap()
            } else {
                g.wrapInner("<div class='mCSB_container' style='position:relative; top:0;' />")
            }
            var o = g.children(".mCSB_container");
            if (c.support.touch) {
                o.addClass("mCS_touch")
            }
            o.after("<div class='mCSB_scrollTools' style='position:absolute;'><div class='mCSB_draggerContainer'><div class='mCSB_dragger' style='position:absolute;' oncontextmenu='return false;'><div class='mCSB_dragger_bar' style='position:relative;'></div></div><div class='mCSB_draggerRail'></div></div></div>");
            var l = g.children(".mCSB_scrollTools"), h = l.children(".mCSB_draggerContainer"), q = h.children(".mCSB_dragger");
            if (e.horizontalScroll) {
                q.data("minDraggerWidth", q.width())
            } else {
                q.data("minDraggerHeight", q.height())
            }
            if (e.scrollButtons.enable) {
                if (e.horizontalScroll) {
                    l.prepend("<a class='mCSB_buttonLeft' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonRight' oncontextmenu='return false;'></a>")
                } else {
                    l.prepend("<a class='mCSB_buttonUp' oncontextmenu='return false;'></a>").append("<a class='mCSB_buttonDown' oncontextmenu='return false;'></a>")
                }
            }
            g.bind("scroll", function () {
                if (!m.is(".mCS_disabled")) {
                    g.scrollTop(0).scrollLeft(0)
                }
            });
            m.data({mCS_Init: true, mCustomScrollbarIndex: c(document).data("mCustomScrollbar-index"), horizontalScroll: e.horizontalScroll, scrollInertia: e.scrollInertia, scrollEasing: "mcsEaseOut", mouseWheel: e.mouseWheel, mouseWheelPixels: e.mouseWheelPixels, autoDraggerLength: e.autoDraggerLength, autoHideScrollbar: e.autoHideScrollbar, snapAmount: e.snapAmount, snapOffset: e.snapOffset, scrollButtons_enable: e.scrollButtons.enable, scrollButtons_scrollType: e.scrollButtons.scrollType, scrollButtons_scrollSpeed: e.scrollButtons.scrollSpeed, scrollButtons_scrollAmount: e.scrollButtons.scrollAmount, autoExpandHorizontalScroll: e.advanced.autoExpandHorizontalScroll, autoScrollOnFocus: e.advanced.autoScrollOnFocus, normalizeMouseWheelDelta: e.advanced.normalizeMouseWheelDelta, contentTouchScroll: e.contentTouchScroll, onScrollStart_Callback: e.callbacks.onScrollStart, onScroll_Callback: e.callbacks.onScroll, onTotalScroll_Callback: e.callbacks.onTotalScroll, onTotalScrollBack_Callback: e.callbacks.onTotalScrollBack, onTotalScroll_Offset: e.callbacks.onTotalScrollOffset, onTotalScrollBack_Offset: e.callbacks.onTotalScrollBackOffset, whileScrolling_Callback: e.callbacks.whileScrolling, bindEvent_scrollbar_drag: false, bindEvent_content_touch: false, bindEvent_scrollbar_click: false, bindEvent_mousewheel: false, bindEvent_buttonsContinuous_y: false, bindEvent_buttonsContinuous_x: false, bindEvent_buttonsPixels_y: false, bindEvent_buttonsPixels_x: false, bindEvent_focusin: false, bindEvent_autoHideScrollbar: false, mCSB_buttonScrollRight: false, mCSB_buttonScrollLeft: false, mCSB_buttonScrollDown: false, mCSB_buttonScrollUp: false});
            if (e.horizontalScroll) {
                if (m.css("max-width") !== "none") {
                    if (!e.advanced.updateOnContentResize) {
                        e.advanced.updateOnContentResize = true
                    }
                }
            } else {
                if (m.css("max-height") !== "none") {
                    var s = false, r = parseInt(m.css("max-height"));
                    if (m.css("max-height").indexOf("%") >= 0) {
                        s = r, r = m.parent().height() * s / 100
                    }
                    m.css("overflow", "hidden");
                    g.css("max-height", r)
                }
            }
            m.mCustomScrollbar("update");
            if (e.advanced.updateOnBrowserResize) {
                var i, j = c(window).width(), u = c(window).height();
                c(window).bind("resize." + m.data("mCustomScrollbarIndex"), function () {
                    if (i) {
                        clearTimeout(i)
                    }
                    i = setTimeout(function () {
                        if (!m.is(".mCS_disabled") && !m.is(".mCS_destroyed")) {
                            var w = c(window).width(), v = c(window).height();
                            if (j !== w || u !== v) {
                                if (m.css("max-height") !== "none" && s) {
                                    g.css("max-height", m.parent().height() * s / 100)
                                }
                                m.mCustomScrollbar("update");
                                j = w;
                                u = v
                            }
                        }
                    }, 150)
                })
            }
            if (e.advanced.updateOnContentResize) {
                var p;
                if (e.horizontalScroll) {
                    var n = o.outerWidth()
                } else {
                    var n = o.outerHeight()
                }
                p = setInterval(function () {
                    if (e.horizontalScroll) {
                        if (e.advanced.autoExpandHorizontalScroll) {
                            o.css({position: "absolute", width: "auto"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width: o.outerWidth(), position: "relative"}).unwrap()
                        }
                        var v = o.outerWidth()
                    } else {
                        var v = o.outerHeight()
                    }
                    if (v != n) {
                        m.mCustomScrollbar("update");
                        n = v
                    }
                }, 300)
            }
        })
    }, update: function () {
        var n = c(this), k = n.children(".mCustomScrollBox"), q = k.children(".mCSB_container");
        q.removeClass("mCS_no_scrollbar");
        n.removeClass("mCS_disabled mCS_destroyed");
        k.scrollTop(0).scrollLeft(0);
        var y = k.children(".mCSB_scrollTools"), o = y.children(".mCSB_draggerContainer"), m = o.children(".mCSB_dragger");
        if (n.data("horizontalScroll")) {
            var A = y.children(".mCSB_buttonLeft"), t = y.children(".mCSB_buttonRight"), f = k.width();
            if (n.data("autoExpandHorizontalScroll")) {
                q.css({position: "absolute", width: "auto"}).wrap("<div class='mCSB_h_wrapper' style='position:relative; left:0; width:999999px;' />").css({width: q.outerWidth(), position: "relative"}).unwrap()
            }
            var z = q.outerWidth()
        } else {
            var w = y.children(".mCSB_buttonUp"), g = y.children(".mCSB_buttonDown"), r = k.height(), i = q.outerHeight()
        }
        if (i > r && !n.data("horizontalScroll")) {
            y.css("display", "block");
            var s = o.height();
            if (n.data("autoDraggerLength")) {
                var u = Math.round(r / i * s), l = m.data("minDraggerHeight");
                if (u <= l) {
                    m.css({height: l})
                } else {
                    if (u >= s - 10) {
                        var p = s - 10;
                        m.css({height: p})
                    } else {
                        m.css({height: u})
                    }
                }
                m.children(".mCSB_dragger_bar").css({"line-height": m.height() + "px"})
            }
            var B = m.height(), x = (i - r) / (s - B);
            n.data("scrollAmount", x).mCustomScrollbar("scrolling", k, q, o, m, w, g, A, t);
            var D = Math.abs(q.position().top);
            n.mCustomScrollbar("scrollTo", D, {scrollInertia: 0, trigger: "internal"})
        } else {
            if (z > f && n.data("horizontalScroll")) {
                y.css("display", "block");
                var h = o.width();
                if (n.data("autoDraggerLength")) {
                    var j = Math.round(f / z * h), C = m.data("minDraggerWidth");
                    if (j <= C) {
                        m.css({width: C})
                    } else {
                        if (j >= h - 10) {
                            var e = h - 10;
                            m.css({width: e})
                        } else {
                            m.css({width: j})
                        }
                    }
                }
                var v = m.width(), x = (z - f) / (h - v);
                n.data("scrollAmount", x).mCustomScrollbar("scrolling", k, q, o, m, w, g, A, t);
                var D = Math.abs(q.position().left);
                n.mCustomScrollbar("scrollTo", D, {scrollInertia: 0, trigger: "internal"})
            } else {
                k.unbind("mousewheel focusin");
                if (n.data("horizontalScroll")) {
                    m.add(q).css("left", 0)
                } else {
                    m.add(q).css("top", 0)
                }
                y.css("display", "none");
                q.addClass("mCS_no_scrollbar");
                n.data({bindEvent_mousewheel: false, bindEvent_focusin: false})
            }
        }
    }, scrolling: function (h, p, m, j, w, e, A, v) {
        var k = c(this);
        if (!k.data("bindEvent_scrollbar_drag")) {
            var n, o;
            if (c.support.msPointer) {
                j.bind("MSPointerDown", function (H) {
                    H.preventDefault();
                    k.data({on_drag: true});
                    j.addClass("mCSB_dragger_onDrag");
                    var G = c(this), J = G.offset(), F = H.originalEvent.pageX - J.left, I = H.originalEvent.pageY - J.top;
                    if (F < G.width() && F > 0 && I < G.height() && I > 0) {
                        n = I;
                        o = F
                    }
                });
                c(document).bind("MSPointerMove." + k.data("mCustomScrollbarIndex"),function (H) {
                    H.preventDefault();
                    if (k.data("on_drag")) {
                        var G = j, J = G.offset(), F = H.originalEvent.pageX - J.left, I = H.originalEvent.pageY - J.top;
                        D(n, o, I, F)
                    }
                }).bind("MSPointerUp." + k.data("mCustomScrollbarIndex"), function (x) {
                    k.data({on_drag: false});
                    j.removeClass("mCSB_dragger_onDrag")
                })
            } else {
                j.bind("mousedown touchstart",function (H) {
                    H.preventDefault();
                    H.stopImmediatePropagation();
                    var G = c(this), K = G.offset(), F, J;
                    if (H.type === "touchstart") {
                        var I = H.originalEvent.touches[0] || H.originalEvent.changedTouches[0];
                        F = I.pageX - K.left;
                        J = I.pageY - K.top
                    } else {
                        k.data({on_drag: true});
                        j.addClass("mCSB_dragger_onDrag");
                        F = H.pageX - K.left;
                        J = H.pageY - K.top
                    }
                    if (F < G.width() && F > 0 && J < G.height() && J > 0) {
                        n = J;
                        o = F
                    }
                }).bind("touchmove", function (H) {
                    H.preventDefault();
                    H.stopImmediatePropagation();
                    var K = H.originalEvent.touches[0] || H.originalEvent.changedTouches[0], G = c(this), J = G.offset(), F = K.pageX - J.left, I = K.pageY - J.top;
                    D(n, o, I, F)
                });
                c(document).bind("mousemove." + k.data("mCustomScrollbarIndex"),function (H) {
                    if (k.data("on_drag")) {
                        var G = j, J = G.offset(), F = H.pageX - J.left, I = H.pageY - J.top;
                        D(n, o, I, F)
                    }
                }).bind("mouseup." + k.data("mCustomScrollbarIndex"), function (x) {
                    k.data({on_drag: false});
                    j.removeClass("mCSB_dragger_onDrag")
                })
            }
            k.data({bindEvent_scrollbar_drag: true})
        }
        function D(G, H, I, F) {
            if (k.data("horizontalScroll")) {
                k.mCustomScrollbar("scrollTo", (j.position().left - (H)) + F, {moveDragger: true, trigger: "internal"})
            } else {
                k.mCustomScrollbar("scrollTo", (j.position().top - (G)) + I, {moveDragger: true, trigger: "internal"})
            }
        }

        if (c.support.touch && k.data("contentTouchScroll")) {
            if (!k.data("bindEvent_content_touch")) {
                var l, B, r, s, u, C, E;
                p.bind("touchstart", function (x) {
                    x.stopImmediatePropagation();
                    l = x.originalEvent.touches[0] || x.originalEvent.changedTouches[0];
                    B = c(this);
                    r = B.offset();
                    u = l.pageX - r.left;
                    s = l.pageY - r.top;
                    C = s;
                    E = u
                });
                p.bind("touchmove", function (x) {
                    x.preventDefault();
                    x.stopImmediatePropagation();
                    l = x.originalEvent.touches[0] || x.originalEvent.changedTouches[0];
                    B = c(this).parent();
                    r = B.offset();
                    u = l.pageX - r.left;
                    s = l.pageY - r.top;
                    if (k.data("horizontalScroll")) {
                        k.mCustomScrollbar("scrollTo", E - u, {trigger: "internal"})
                    } else {
                        k.mCustomScrollbar("scrollTo", C - s, {trigger: "internal"})
                    }
                })
            }
        }
        if (!k.data("bindEvent_scrollbar_click")) {
            m.bind("click", function (F) {
                var x = (F.pageY - m.offset().top) * k.data("scrollAmount"), y = c(F.target);
                if (k.data("horizontalScroll")) {
                    x = (F.pageX - m.offset().left) * k.data("scrollAmount")
                }
                if (y.hasClass("mCSB_draggerContainer") || y.hasClass("mCSB_draggerRail")) {
                    k.mCustomScrollbar("scrollTo", x, {trigger: "internal", scrollEasing: "draggerRailEase"})
                }
            });
            k.data({bindEvent_scrollbar_click: true})
        }
        if (k.data("mouseWheel")) {
            if (!k.data("bindEvent_mousewheel")) {
                h.bind("mousewheel", function (H, J) {
                    var G, F = k.data("mouseWheelPixels"), x = Math.abs(p.position().top), I = j.position().top, y = m.height() - j.height();
                    if (k.data("normalizeMouseWheelDelta")) {
                        if (J < 0) {
                            J = -1
                        } else {
                            J = 1
                        }
                    }
                    if (F === "auto") {
                        F = 100 + Math.round(k.data("scrollAmount") / 2)
                    }
                    if (k.data("horizontalScroll")) {
                        I = j.position().left;
                        y = m.width() - j.width();
                        x = Math.abs(p.position().left)
                    }
                    if ((J > 0 && I !== 0) || (J < 0 && I !== y)) {
                        H.preventDefault();
                        H.stopImmediatePropagation()
                    }
                    G = x - (J * F);
                    k.mCustomScrollbar("scrollTo", G, {trigger: "internal"})
                });
                k.data({bindEvent_mousewheel: true})
            }
        }
        if (k.data("scrollButtons_enable")) {
            if (k.data("scrollButtons_scrollType") === "pixels") {
                if (k.data("horizontalScroll")) {
                    v.add(A).unbind("mousedown touchstart MSPointerDown mouseup MSPointerUp mouseout MSPointerOut touchend", i, g);
                    k.data({bindEvent_buttonsContinuous_x: false});
                    if (!k.data("bindEvent_buttonsPixels_x")) {
                        v.bind("click", function (x) {
                            x.preventDefault();
                            q(Math.abs(p.position().left) + k.data("scrollButtons_scrollAmount"))
                        });
                        A.bind("click", function (x) {
                            x.preventDefault();
                            q(Math.abs(p.position().left) - k.data("scrollButtons_scrollAmount"))
                        });
                        k.data({bindEvent_buttonsPixels_x: true})
                    }
                } else {
                    e.add(w).unbind("mousedown touchstart MSPointerDown mouseup MSPointerUp mouseout MSPointerOut touchend", i, g);
                    k.data({bindEvent_buttonsContinuous_y: false});
                    if (!k.data("bindEvent_buttonsPixels_y")) {
                        e.bind("click", function (x) {
                            x.preventDefault();
                            q(Math.abs(p.position().top) + k.data("scrollButtons_scrollAmount"))
                        });
                        w.bind("click", function (x) {
                            x.preventDefault();
                            q(Math.abs(p.position().top) - k.data("scrollButtons_scrollAmount"))
                        });
                        k.data({bindEvent_buttonsPixels_y: true})
                    }
                }
                function q(x) {
                    if (!j.data("preventAction")) {
                        j.data("preventAction", true);
                        k.mCustomScrollbar("scrollTo", x, {trigger: "internal"})
                    }
                }
            } else {
                if (k.data("horizontalScroll")) {
                    v.add(A).unbind("click");
                    k.data({bindEvent_buttonsPixels_x: false});
                    if (!k.data("bindEvent_buttonsContinuous_x")) {
                        v.bind("mousedown touchstart MSPointerDown", function (y) {
                            y.preventDefault();
                            var x = z();
                            k.data({mCSB_buttonScrollRight: setInterval(function () {
                                k.mCustomScrollbar("scrollTo", Math.abs(p.position().left) + x, {trigger: "internal", scrollEasing: "easeOutCirc"})
                            }, 17)})
                        });
                        var i = function (x) {
                            x.preventDefault();
                            clearInterval(k.data("mCSB_buttonScrollRight"))
                        };
                        v.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", i);
                        A.bind("mousedown touchstart MSPointerDown", function (y) {
                            y.preventDefault();
                            var x = z();
                            k.data({mCSB_buttonScrollLeft: setInterval(function () {
                                k.mCustomScrollbar("scrollTo", Math.abs(p.position().left) - x, {trigger: "internal", scrollEasing: "easeOutCirc"})
                            }, 17)})
                        });
                        var g = function (x) {
                            x.preventDefault();
                            clearInterval(k.data("mCSB_buttonScrollLeft"))
                        };
                        A.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", g);
                        k.data({bindEvent_buttonsContinuous_x: true})
                    }
                } else {
                    e.add(w).unbind("click");
                    k.data({bindEvent_buttonsPixels_y: false});
                    if (!k.data("bindEvent_buttonsContinuous_y")) {
                        e.bind("mousedown touchstart MSPointerDown", function (y) {
                            y.preventDefault();
                            var x = z();
                            k.data({mCSB_buttonScrollDown: setInterval(function () {
                                k.mCustomScrollbar("scrollTo", Math.abs(p.position().top) + x, {trigger: "internal", scrollEasing: "easeOutCirc"})
                            }, 17)})
                        });
                        var t = function (x) {
                            x.preventDefault();
                            clearInterval(k.data("mCSB_buttonScrollDown"))
                        };
                        e.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", t);
                        w.bind("mousedown touchstart MSPointerDown", function (y) {
                            y.preventDefault();
                            var x = z();
                            k.data({mCSB_buttonScrollUp: setInterval(function () {
                                k.mCustomScrollbar("scrollTo", Math.abs(p.position().top) - x, {trigger: "internal", scrollEasing: "easeOutCirc"})
                            }, 17)})
                        });
                        var f = function (x) {
                            x.preventDefault();
                            clearInterval(k.data("mCSB_buttonScrollUp"))
                        };
                        w.bind("mouseup touchend MSPointerUp mouseout MSPointerOut", f);
                        k.data({bindEvent_buttonsContinuous_y: true})
                    }
                }
                function z() {
                    var x = k.data("scrollButtons_scrollSpeed");
                    if (k.data("scrollButtons_scrollSpeed") === "auto") {
                        x = Math.round((k.data("scrollInertia") + 100) / 40)
                    }
                    return x
                }
            }
        }
        if (k.data("autoScrollOnFocus")) {
            if (!k.data("bindEvent_focusin")) {
                h.bind("focusin", function () {
                    h.scrollTop(0).scrollLeft(0);
                    var x = c(document.activeElement);
                    if (x.is("input,textarea,select,button,a[tabindex],area,object")) {
                        var G = p.position().top, y = x.position().top, F = h.height() - x.outerHeight();
                        if (k.data("horizontalScroll")) {
                            G = p.position().left;
                            y = x.position().left;
                            F = h.width() - x.outerWidth()
                        }
                        if (G + y < 0 || G + y > F) {
                            k.mCustomScrollbar("scrollTo", y, {trigger: "internal"})
                        }
                    }
                });
                k.data({bindEvent_focusin: true})
            }
        }
        if (k.data("autoHideScrollbar")) {
            if (!k.data("bindEvent_autoHideScrollbar")) {
                h.bind("mouseenter",function (x) {
                    h.addClass("mCS-mouse-over");
                    d.showScrollbar.call(h.children(".mCSB_scrollTools"))
                }).bind("mouseleave touchend", function (x) {
                    h.removeClass("mCS-mouse-over");
                    if (x.type === "mouseleave") {
                        d.hideScrollbar.call(h.children(".mCSB_scrollTools"))
                    }
                });
                k.data({bindEvent_autoHideScrollbar: true})
            }
        }
    }, scrollTo: function (e, f) {
        var i = c(this), o = {moveDragger: false, trigger: "external", callbacks: true, scrollInertia: i.data("scrollInertia"), scrollEasing: i.data("scrollEasing")}, f = c.extend(o, f), p, g = i.children(".mCustomScrollBox"), k = g.children(".mCSB_container"), r = g.children(".mCSB_scrollTools"), j = r.children(".mCSB_draggerContainer"), h = j.children(".mCSB_dragger"), t = draggerSpeed = f.scrollInertia, q, s, m, l;
        if (!k.hasClass("mCS_no_scrollbar")) {
            i.data({mCS_trigger: f.trigger});
            if (i.data("mCS_Init")) {
                f.callbacks = false
            }
            if (e || e === 0) {
                if (typeof(e) === "number") {
                    if (f.moveDragger) {
                        p = e;
                        if (i.data("horizontalScroll")) {
                            e = h.position().left * i.data("scrollAmount")
                        } else {
                            e = h.position().top * i.data("scrollAmount")
                        }
                        draggerSpeed = 0
                    } else {
                        p = e / i.data("scrollAmount")
                    }
                } else {
                    if (typeof(e) === "string") {
                        var v;
                        if (e === "top") {
                            v = 0
                        } else {
                            if (e === "bottom" && !i.data("horizontalScroll")) {
                                v = k.outerHeight() - g.height()
                            } else {
                                if (e === "left") {
                                    v = 0
                                } else {
                                    if (e === "right" && i.data("horizontalScroll")) {
                                        v = k.outerWidth() - g.width()
                                    } else {
                                        if (e === "first") {
                                            v = i.find(".mCSB_container").find(":first")
                                        } else {
                                            if (e === "last") {
                                                v = i.find(".mCSB_container").find(":last")
                                            } else {
                                                v = i.find(e)
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if (v.length === 1) {
                            if (i.data("horizontalScroll")) {
                                e = v.position().left
                            } else {
                                e = v.position().top
                            }
                            p = e / i.data("scrollAmount")
                        } else {
                            p = e = v
                        }
                    }
                }
                if (i.data("horizontalScroll")) {
                    if (i.data("onTotalScrollBack_Offset")) {
                        s = -i.data("onTotalScrollBack_Offset")
                    }
                    if (i.data("onTotalScroll_Offset")) {
                        l = g.width() - k.outerWidth() + i.data("onTotalScroll_Offset")
                    }
                    if (p < 0) {
                        p = e = 0;
                        clearInterval(i.data("mCSB_buttonScrollLeft"));
                        if (!s) {
                            q = true
                        }
                    } else {
                        if (p >= j.width() - h.width()) {
                            p = j.width() - h.width();
                            e = g.width() - k.outerWidth();
                            clearInterval(i.data("mCSB_buttonScrollRight"));
                            if (!l) {
                                m = true
                            }
                        } else {
                            e = -e
                        }
                    }
                    var n = i.data("snapAmount");
                    if (n) {
                        e = Math.round(e / n) * n - i.data("snapOffset")
                    }
                    d.mTweenAxis.call(this, h[0], "left", Math.round(p), draggerSpeed, f.scrollEasing);
                    d.mTweenAxis.call(this, k[0], "left", Math.round(e), t, f.scrollEasing, {onStart: function () {
                        if (f.callbacks && !i.data("mCS_tweenRunning")) {
                            u("onScrollStart")
                        }
                        if (i.data("autoHideScrollbar")) {
                            d.showScrollbar.call(r)
                        }
                    }, onUpdate: function () {
                        if (f.callbacks) {
                            u("whileScrolling")
                        }
                    }, onComplete: function () {
                        if (f.callbacks) {
                            u("onScroll");
                            if (q || (s && k.position().left >= s)) {
                                u("onTotalScrollBack")
                            }
                            if (m || (l && k.position().left <= l)) {
                                u("onTotalScroll")
                            }
                        }
                        h.data("preventAction", false);
                        i.data("mCS_tweenRunning", false);
                        if (i.data("autoHideScrollbar")) {
                            if (!g.hasClass("mCS-mouse-over")) {
                                d.hideScrollbar.call(r)
                            }
                        }
                    }})
                } else {
                    if (i.data("onTotalScrollBack_Offset")) {
                        s = -i.data("onTotalScrollBack_Offset")
                    }
                    if (i.data("onTotalScroll_Offset")) {
                        l = g.height() - k.outerHeight() + i.data("onTotalScroll_Offset")
                    }
                    if (p < 0) {
                        p = e = 0;
                        clearInterval(i.data("mCSB_buttonScrollUp"));
                        if (!s) {
                            q = true
                        }
                    } else {
                        if (p >= j.height() - h.height()) {
                            p = j.height() - h.height();
                            e = g.height() - k.outerHeight();
                            clearInterval(i.data("mCSB_buttonScrollDown"));
                            if (!l) {
                                m = true
                            }
                        } else {
                            e = -e
                        }
                    }
                    var n = i.data("snapAmount");
                    if (n) {
                        e = Math.round(e / n) * n - i.data("snapOffset")
                    }
                    d.mTweenAxis.call(this, h[0], "top", Math.round(p), draggerSpeed, f.scrollEasing);
                    d.mTweenAxis.call(this, k[0], "top", Math.round(e), t, f.scrollEasing, {onStart: function () {
                        if (f.callbacks && !i.data("mCS_tweenRunning")) {
                            u("onScrollStart")
                        }
                        if (i.data("autoHideScrollbar")) {
                            d.showScrollbar.call(r)
                        }
                    }, onUpdate: function () {
                        if (f.callbacks) {
                            u("whileScrolling")
                        }
                    }, onComplete: function () {
                        if (f.callbacks) {
                            u("onScroll");
                            if (q || (s && k.position().top >= s)) {
                                u("onTotalScrollBack")
                            }
                            if (m || (l && k.position().top <= l)) {
                                u("onTotalScroll")
                            }
                        }
                        h.data("preventAction", false);
                        i.data("mCS_tweenRunning", false);
                        if (i.data("autoHideScrollbar")) {
                            if (!g.hasClass("mCS-mouse-over")) {
                                d.hideScrollbar.call(r)
                            }
                        }
                    }})
                }
                if (i.data("mCS_Init")) {
                    i.data({mCS_Init: false})
                }
            }
        }
        function u(w) {
            this.mcs = {top: k.position().top, left: k.position().left, draggerTop: h.position().top, draggerLeft: h.position().left, topPct: Math.round((100 * Math.abs(k.position().top)) / Math.abs(k.outerHeight() - g.height())), leftPct: Math.round((100 * Math.abs(k.position().left)) / Math.abs(k.outerWidth() - g.width()))};
            switch (w) {
                case"onScrollStart":
                    i.data("mCS_tweenRunning", true).data("onScrollStart_Callback").call(i, this.mcs);
                    break;
                case"whileScrolling":
                    i.data("whileScrolling_Callback").call(i, this.mcs);
                    break;
                case"onScroll":
                    i.data("onScroll_Callback").call(i, this.mcs);
                    break;
                case"onTotalScrollBack":
                    i.data("onTotalScrollBack_Callback").call(i, this.mcs);
                    break;
                case"onTotalScroll":
                    i.data("onTotalScroll_Callback").call(i, this.mcs);
                    break
            }
        }
    }, stop: function () {
        var g = c(this), e = g.children().children(".mCSB_container"), f = g.children().children().children().children(".mCSB_dragger");
        d.mTweenAxisStop.call(this, e[0]);
        d.mTweenAxisStop.call(this, f[0])
    }, disable: function (e) {
        var j = c(this), f = j.children(".mCustomScrollBox"), h = f.children(".mCSB_container"), g = f.children(".mCSB_scrollTools"), i = g.children().children(".mCSB_dragger");
        f.unbind("mousewheel focusin mouseenter mouseleave touchend");
        h.unbind("touchstart touchmove");
        if (e) {
            if (j.data("horizontalScroll")) {
                i.add(h).css("left", 0)
            } else {
                i.add(h).css("top", 0)
            }
        }
        g.css("display", "none");
        h.addClass("mCS_no_scrollbar");
        j.data({bindEvent_mousewheel: false, bindEvent_focusin: false, bindEvent_content_touch: false, bindEvent_autoHideScrollbar: false}).addClass("mCS_disabled")
    }, destroy: function () {
        var e = c(this);
        e.removeClass("mCustomScrollbar _mCS_" + e.data("mCustomScrollbarIndex")).addClass("mCS_destroyed").children().children(".mCSB_container").unwrap().children().unwrap().siblings(".mCSB_scrollTools").remove();
        c(document).unbind("mousemove." + e.data("mCustomScrollbarIndex") + " mouseup." + e.data("mCustomScrollbarIndex") + " MSPointerMove." + e.data("mCustomScrollbarIndex") + " MSPointerUp." + e.data("mCustomScrollbarIndex"));
        c(window).unbind("resize." + e.data("mCustomScrollbarIndex"))
    }}, d = {showScrollbar: function () {
        this.stop().animate({opacity: 1}, "fast")
    }, hideScrollbar: function () {
        this.stop().animate({opacity: 0}, "fast")
    }, mTweenAxis: function (g, i, h, f, o, y) {
        var y = y || {}, v = y.onStart || function () {
        }, p = y.onUpdate || function () {
        }, w = y.onComplete || function () {
        };
        var n = t(), l, j = 0, r = g.offsetTop, s = g.style;
        if (i === "left") {
            r = g.offsetLeft
        }
        var m = h - r;
        q();
        e();
        function t() {
            if (window.performance && window.performance.now) {
                return window.performance.now()
            } else {
                if (window.performance && window.performance.webkitNow) {
                    return window.performance.webkitNow()
                } else {
                    if (Date.now) {
                        return Date.now()
                    } else {
                        return new Date().getTime()
                    }
                }
            }
        }

        function x() {
            if (!j) {
                v.call()
            }
            j = t() - n;
            u();
            if (j >= g._time) {
                g._time = (j > g._time) ? j + l - (j - g._time) : j + l - 1;
                if (g._time < j + 1) {
                    g._time = j + 1
                }
            }
            if (g._time < f) {
                g._id = _request(x)
            } else {
                w.call()
            }
        }

        function u() {
            if (f > 0) {
                g.currVal = k(g._time, r, m, f, o);
                s[i] = Math.round(g.currVal) + "px"
            } else {
                s[i] = h + "px"
            }
            p.call()
        }

        function e() {
            l = 1000 / 60;
            g._time = j + l;
            _request = (!window.requestAnimationFrame) ? function (z) {
                u();
                return setTimeout(z, 0.01)
            } : window.requestAnimationFrame;
            g._id = _request(x)
        }

        function q() {
            if (g._id == null) {
                return
            }
            if (!window.requestAnimationFrame) {
                clearTimeout(g._id)
            } else {
                window.cancelAnimationFrame(g._id)
            }
            g._id = null
        }

        function k(B, A, F, E, C) {
            switch (C) {
                case"linear":
                    return F * B / E + A;
                    break;
                case"easeOutQuad":
                    B /= E;
                    return -F * B * (B - 2) + A;
                    break;
                case"easeInOutQuad":
                    B /= E / 2;
                    if (B < 1) {
                        return F / 2 * B * B + A
                    }
                    B--;
                    return -F / 2 * (B * (B - 2) - 1) + A;
                    break;
                case"easeOutCubic":
                    B /= E;
                    B--;
                    return F * (B * B * B + 1) + A;
                    break;
                case"easeOutQuart":
                    B /= E;
                    B--;
                    return -F * (B * B * B * B - 1) + A;
                    break;
                case"easeOutQuint":
                    B /= E;
                    B--;
                    return F * (B * B * B * B * B + 1) + A;
                    break;
                case"easeOutCirc":
                    B /= E;
                    B--;
                    return F * Math.sqrt(1 - B * B) + A;
                    break;
                case"easeOutSine":
                    return F * Math.sin(B / E * (Math.PI / 2)) + A;
                    break;
                case"easeOutExpo":
                    return F * (-Math.pow(2, -10 * B / E) + 1) + A;
                    break;
                case"mcsEaseOut":
                    var D = (B /= E) * B, z = D * B;
                    return A + F * (0.499999999999997 * z * D + -2.5 * D * D + 5.5 * z + -6.5 * D + 4 * B);
                    break;
                case"draggerRailEase":
                    B /= E / 2;
                    if (B < 1) {
                        return F / 2 * B * B * B + A
                    }
                    B -= 2;
                    return F / 2 * (B * B * B + 2) + A;
                    break
            }
        }
    }, mTweenAxisStop: function (e) {
        if (e._id == null) {
            return
        }
        if (!window.requestAnimationFrame) {
            clearTimeout(e._id)
        } else {
            window.cancelAnimationFrame(e._id)
        }
        e._id = null
    }, rafPolyfill: function () {
        var f = ["ms", "moz", "webkit", "o"], e = f.length;
        while (--e > -1 && !window.requestAnimationFrame) {
            window.requestAnimationFrame = window[f[e] + "RequestAnimationFrame"];
            window.cancelAnimationFrame = window[f[e] + "CancelAnimationFrame"] || window[f[e] + "CancelRequestAnimationFrame"]
        }
    }};
    d.rafPolyfill.call();
    c.support.touch = !!("ontouchstart" in window);
    c.support.msPointer = window.navigator.msPointerEnabled;
    var a = ("https:" == document.location.protocol) ? "https:" : "http:";
    c.event.special.mousewheel || document.write('<script src="' + a + '//cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.0.6/jquery.mousewheel.min.js"><\/script>');
    c.fn.mCustomScrollbar = function (e) {
        if (b[e]) {
            return b[e].apply(this, Array.prototype.slice.call(arguments, 1))
        } else {
            if (typeof e === "object" || !e) {
                return b.init.apply(this, arguments)
            } else {
                c.error("Method " + e + " does not exist")
            }
        }
    }
})(jQuery);

/* jQuery Form Styler v1.3.4 | (c) Dimox | http://dimox.name/jquery-form-styler/ */
(function (d) {
    d.fn.styler = function (n) {
        n = d.extend({idSuffix: "-styler", browseText: "\u0412\u044b\u0431\u0440\u0430\u0442\u044c", selectVisibleOptions: 0, singleSelectzIndex: "100", selectSmartPositioning: !0}, n);
        return this.each(function () {
            var a = d(this), l = "", q = "", r = "";
            void 0 !== a.attr("id") && "" != a.attr("id") && (l = ' id="' + a.attr("id") + n.idSuffix + '"');
            void 0 !== a.attr("class") && "" != a.attr("class") && (q = " " + a.attr("class"));
            var s = a.data(), e;
            for (e in s)"" != s[e] && (r += " data-" + e + '="' + s[e] + '"');
            l += r;
            a.is(":checkbox") ?
                a.css({position: "absolute", left: -9999}).each(function () {
                    if (1 > a.next("span.jq-checkbox").length) {
                        var b = d("<span" + l + ' class="jq-checkbox' + q + '" style="display: inline-block"><span></span></span>');
                        a.after(b);
                        a.is(":checked") && b.addClass("checked");
                        a.is(":disabled") && b.addClass("disabled");
                        b.click(function () {
                            if (!b.is(".disabled"))return a.is(":checked") ? (a.prop("checked", !1), b.removeClass("checked")) : (a.prop("checked", !0), b.addClass("checked")), a.change(), !1
                        });
                        a.parent("label").add('label[for="' + a.attr("id") +
                            '"]').click(function (a) {
                            b.click();
                            a.preventDefault()
                        });
                        a.change(function () {
                            a.is(":checked") ? b.addClass("checked") : b.removeClass("checked")
                        }).keydown(function (d) {
                            a.parent("label").length && (13 == d.which || 32 == d.which) && b.click()
                        }).focus(function () {
                            b.is(".disabled") || b.addClass("focused")
                        }).blur(function () {
                            b.removeClass("focused")
                        });
                        a.on("refresh", function () {
                            a.is(":checked") ? b.addClass("checked") : b.removeClass("checked");
                            a.is(":disabled") ? b.addClass("disabled") : b.removeClass("disabled")
                        })
                    }
                }) : a.is(":radio") ?
                a.css({position: "absolute", left: -9999}).each(function () {
                    if (1 > a.next("span.jq-radio").length) {
                        var b = d("<span" + l + ' class="jq-radio' + q + '" style="display: inline-block"><span></span></span>');
                        a.after(b);
                        a.is(":checked") && b.addClass("checked");
                        a.is(":disabled") && b.addClass("disabled");
                        b.click(function () {
                            if (!b.is(".disabled"))return d('input[name="' + a.attr("name") + '"]').prop("checked", !1).next().removeClass("checked"), a.prop("checked", !0).next().addClass("checked"), a.change(), !1
                        });
                        a.parent("label").add('label[for="' +
                            a.attr("id") + '"]').click(function (a) {
                            b.click();
                            a.preventDefault()
                        });
                        a.change(function () {
                            d('input[name="' + a.attr("name") + '"]').next().removeClass("checked");
                            a.next().addClass("checked")
                        }).focus(function () {
                            b.is(".disabled") || b.addClass("focused")
                        }).blur(function () {
                            b.removeClass("focused")
                        });
                        a.on("refresh", function () {
                            a.is(":checked") ? (d('input[name="' + a.attr("name") + '"]').next().removeClass("checked"), b.addClass("checked")) : b.removeClass("checked");
                            a.is(":disabled") ? b.addClass("disabled") : b.removeClass("disabled")
                        })
                    }
                }) :
                a.is(":file") ? a.css({position: "absolute", top: "-50%", right: "-50%", fontSize: "200px", opacity: 0}).each(function () {
                    if (1 > a.parent("span.jq-file").length) {
                        var b = d("<span" + l + ' class="jq-file' + q + '" style="display: inline-block; position: relative; overflow: hidden"></span>'), e = d('<div class="name" style="float: left; white-space: nowrap"></div>').appendTo(b);
                        d('<div class="browse" style="float: left">' + n.browseText + "</div>").appendTo(b);
                        a.after(b);
                        b.append(a);
                        a.is(":disabled") && b.addClass("disabled");
                        a.change(function () {
                            e.text(a.val().replace(/.+[\\\/]/,
                                ""))
                        }).focus(function () {
                            b.addClass("focused")
                        }).blur(function () {
                            b.removeClass("focused")
                        }).click(function () {
                            b.removeClass("focused")
                        }).on("refresh", function () {
                            a.is(":disabled") ? b.addClass("disabled") : b.removeClass("disabled")
                        })
                    }
                }) : a.is("select") && a.each(function () {
                    if (1 > a.next("span.jqselect").length) {
                        var b = function () {
                            function b(a) {
                                a.bind("mousewheel DOMMouseScroll", function (a) {
                                    var b = null;
                                    "mousewheel" == a.type ? b = -1 * a.originalEvent.wheelDelta : "DOMMouseScroll" == a.type && (b = 40 * a.originalEvent.detail);
                                    b &&
                                    (a.preventDefault(), d(this).scrollTop(b + d(this).scrollTop()))
                                })
                            }

                            function r() {
                                for (e = 0; e < f.length; e++) {
                                    var a = "", b = "", d = "", c = "";
                                    f.eq(e).prop("selected") && (b = "selected sel");
                                    f.eq(e).is(":disabled") && (b = "disabled");
                                    f.eq(e).is(":selected:disabled") && (b = "selected sel disabled");
                                    void 0 !== f.eq(e).attr("class") && (d = " " + f.eq(e).attr("class"));
                                    a = '<li class="' + b + d + '">' + f.eq(e).text() + "</li>";
                                    f.eq(e).parent().is("optgroup") && (void 0 !== f.eq(e).parent().attr("class") && (c = " " + f.eq(e).parent().attr("class")), a = '<li class="' +
                                        b + d + " option" + c + '">' + f.eq(e).text() + "</li>", f.eq(e).is(":first-child") && (a = '<li class="optgroup' + c + '">' + f.eq(e).parent().attr("label") + "</li>" + a));
                                    w += a
                                }
                            }

                            var f = d("option", a), w = "";
                            if (a.is("[multiple]")) {
                                var h = d("<span" + l + ' class="jq-select-multiple jqselect' + q + '" style="display: inline-block"></span>');
                                a.after(h).css({position: "absolute", left: -9999});
                                r();
                                h.append('<ul style="position: relative">' + w + "</ul>");
                                var j = d("ul", h), g = d("li", h).attr("unselectable", "on").css({"-webkit-user-select": "none", "-moz-user-select": "none",
                                    "-ms-user-select": "none", "-o-user-select": "none", "user-select": "none"}), t = a.attr("size"), u = j.outerHeight(), x = g.outerHeight();
                                void 0 !== t && 0 < t ? j.css({height: x * t}) : j.css({height: 4 * x});
                                u > h.height() && (j.css("overflowY", "scroll"), b(j), g.filter(".selected").length && j.scrollTop(j.scrollTop() + g.filter(".selected").position().top));
                                a.is(":disabled") ? (h.addClass("disabled"), f.each(function () {
                                    d(this).is(":selected") && g.eq(d(this).index()).addClass("selected")
                                })) : (g.filter(":not(.disabled):not(.optgroup)").click(function (b) {
                                    a.focus();
                                    h.removeClass("focused");
                                    var c = d(this);
                                    b.ctrlKey || c.addClass("selected");
                                    b.shiftKey || c.addClass("first");
                                    !b.ctrlKey && !b.shiftKey && c.siblings().removeClass("selected first");
                                    b.ctrlKey && (c.is(".selected") ? c.removeClass("selected first") : c.addClass("selected first"), c.siblings().removeClass("first"));
                                    if (b.shiftKey) {
                                        var e = !1, k = !1;
                                        c.siblings().removeClass("selected").siblings(".first").addClass("selected");
                                        c.prevAll().each(function () {
                                            d(this).is(".first") && (e = !0)
                                        });
                                        c.nextAll().each(function () {
                                            d(this).is(".first") &&
                                            (k = !0)
                                        });
                                        e && c.prevAll().each(function () {
                                            if (d(this).is(".selected"))return!1;
                                            d(this).not(".disabled, .optgroup").addClass("selected")
                                        });
                                        k && c.nextAll().each(function () {
                                            if (d(this).is(".selected"))return!1;
                                            d(this).not(".disabled, .optgroup").addClass("selected")
                                        });
                                        1 == g.filter(".selected").length && c.addClass("first")
                                    }
                                    f.prop("selected", !1);
                                    g.filter(".selected").each(function () {
                                        var a = d(this), b = a.index();
                                        a.is(".option") && (b -= a.prevAll(".optgroup").length);
                                        f.eq(b).prop("selected", !0)
                                    });
                                    a.change()
                                }), f.each(function (a) {
                                    d(this).data("optionIndex",
                                        a)
                                }), a.change(function () {
                                    g.removeClass("selected");
                                    var a = [];
                                    f.filter(":selected").each(function () {
                                        a.push(d(this).data("optionIndex"))
                                    });
                                    g.not(".optgroup").filter(function (b) {
                                        return-1 < d.inArray(b, a)
                                    }).addClass("selected")
                                }).focus(function () {
                                    h.addClass("focused")
                                }).blur(function () {
                                    h.removeClass("focused")
                                }), u > h.height() && a.keydown(function (a) {
                                    (38 == a.which || 37 == a.which || 33 == a.which) && j.scrollTop(j.scrollTop() + g.filter(".selected").position().top - x);
                                    (40 == a.which || 39 == a.which || 34 == a.which) && j.scrollTop(j.scrollTop() +
                                        g.filter(".selected:last").position().top - j.innerHeight() + 2 * x)
                                }))
                            } else {
                                var k = d("<span" + l + ' class="jq-selectbox jqselect' + q + '" style="display: inline-block; position: relative; z-index:' + n.singleSelectzIndex + '"><div class="select" style="float: left"><div class="text"></div><b class="trigger"><i class="arrow"></i></b></div></span>');
                                a.after(k).css({position: "absolute", left: -9999});
                                var t = d("div.select", k), v = d("div.text", k), u = f.filter(":selected");
                                u.length ? v.text(u.text()) : v.text(f.first().text());
                                if (a.is(":disabled"))k.addClass("disabled");
                                else {
                                    r();
                                    var c = d('<div class="dropdown" style="position: absolute; overflow: auto; overflow-x: hidden"><ul style="list-style: none">' + w + "</ul></div>");
                                    k.append(c);
                                    var m = d("li", c);
                                    1 > m.filter(".selected").length && m.first().addClass("selected sel");
                                    var s = k.outerHeight();
                                    "auto" == c.css("left") && c.css({left: 0});
                                    "auto" == c.css("top") && c.css({top: s});
                                    var p = m.outerHeight(), y = c.css("top");
                                    c.hide();
                                    t.click(function () {
                                        a.focus();
                                        if (n.selectSmartPositioning) {
                                            var e = d(window), f = k.offset().top, j = e.height() - s - (f - e.scrollTop()),
                                                g = n.selectVisibleOptions, h = 6 * p, l = p * g;
                                            0 < g && 6 > g && (h = l);
                                            0 > j || j < h ? (c.height("auto").css({top: "auto", bottom: y}), c.outerHeight() > f - e.scrollTop() - 20 && (c.height(Math.floor((f - e.scrollTop() - 20) / p) * p), 0 < g && 6 > g ? c.height() > h && c.height(h) : 6 < g && c.height() > l && c.height(l))) : j > h && (c.height("auto").css({bottom: "auto", top: y}), c.outerHeight() > j - 20 && (c.height(Math.floor((j - 20) / p) * p), 0 < g && 6 > g ? c.height() > h && c.height(h) : 6 < g && c.height() > l && c.height(l)))
                                        }
                                        d("span.jqselect").css({zIndex: n.singleSelectzIndex - 1}).removeClass("focused");
                                        k.css({zIndex: n.singleSelectzIndex});
                                        c.is(":hidden") ? (d("div.dropdown:visible").hide(), c.show(), k.addClass("opened")) : (c.hide(), k.removeClass("opened"));
                                        m.filter(".selected").length && c.scrollTop(c.scrollTop() + m.filter(".selected").position().top - c.innerHeight() / 2 + p / 2);
                                        b(c);
                                        return!1
                                    });
                                    m.hover(function () {
                                        d(this).siblings().removeClass("selected")
                                    });
                                    var z = m.filter(".selected").text();
                                    m.filter(":not(.disabled):not(.optgroup)").click(function () {
                                        var b = d(this), e = b.text();
                                        if (z != e) {
                                            var g = b.index();
                                            b.is(".option") &&
                                            (g -= b.prevAll(".optgroup").length);
                                            b.addClass("selected sel").siblings().removeClass("selected sel");
                                            f.prop("selected", !1).eq(g).prop("selected", !0);
                                            z = e;
                                            v.text(e);
                                            a.change()
                                        }
                                        c.hide()
                                    });
                                    c.mouseout(function () {
                                        d("li.sel", c).addClass("selected")
                                    });
                                    a.change(function () {
                                        v.text(f.filter(":selected").text());
                                        m.removeClass("selected sel").not(".optgroup").eq(a[0].selectedIndex).addClass("selected sel")
                                    }).focus(function () {
                                        k.addClass("focused")
                                    }).blur(function () {
                                        k.removeClass("focused")
                                    }).bind("keydown keyup",
                                        function (b) {
                                            v.text(f.filter(":selected").text());
                                            m.removeClass("selected sel").not(".optgroup").eq(a[0].selectedIndex).addClass("selected sel");
                                            (38 == b.which || 37 == b.which || 33 == b.which) && c.scrollTop(c.scrollTop() + m.filter(".selected").position().top);
                                            (40 == b.which || 39 == b.which || 34 == b.which) && c.scrollTop(c.scrollTop() + m.filter(".selected").position().top - c.innerHeight() + p);
                                            13 == b.which && c.hide()
                                        });
                                    d(document).on("click", function (a) {
                                        !d(a.target).parents().hasClass("selectbox") && "OPTION" != a.target.nodeName &&
                                        (c.hide().find("li.sel").addClass("selected"), k.removeClass("focused opened"))
                                    })
                                }
                            }
                        };
                        b();
                        a.on("refresh", function () {
                            a.next().remove();
                            b()
                        })
                    }
                })
        })
    }
})(jQuery);
(function ($) {
    function Countdown() {
        this.regional = [];
        this.regional[''] = {labels: ['Years', 'Months', 'Weeks', 'Days', 'Hours', 'Minutes', 'Seconds'], labels1: ['Year', 'Month', 'Week', 'Day', 'Hour', 'Minute', 'Second'], compactLabels: ['y', 'm', 'w', 'd'], whichLabels: null, digits: ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], timeSeparator: ':', isRTL: false};
        this._defaults = {until: null, since: null, timezone: null, serverSync: null, format: 'dHMS', layout: '', compact: false, significant: 0, description: '', expiryUrl: '', expiryText: '', alwaysExpire: false, onExpiry: null, onTick: null, tickInterval: 1};
        $.extend(this._defaults, this.regional['']);
        this._serverSyncs = [];
        var c = (typeof Date.now == 'function' ? Date.now : function () {
            return new Date().getTime()
        });
        var d = (window.performance && typeof window.performance.now == 'function');

        function timerCallBack(a) {
            var b = (a < 1e12 ? (d ? (performance.now() + performance.timing.navigationStart) : c()) : a || c());
            if (b - f >= 1000) {
                x._updateTargets();
                f = b
            }
            e(timerCallBack)
        }

        var e = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || null;
        var f = 0;
        if (!e || $.noRequestAnimationFrame) {
            $.noRequestAnimationFrame = null;
            setInterval(function () {
                x._updateTargets()
            }, 980)
        } else {
            f = window.animationStartTime || window.webkitAnimationStartTime || window.mozAnimationStartTime || window.oAnimationStartTime || window.msAnimationStartTime || c();
            e(timerCallBack)
        }
    }

    var Y = 0;
    var O = 1;
    var W = 2;
    var D = 3;
    var H = 4;
    var M = 5;
    var S = 6;
    $.extend(Countdown.prototype, {markerClassName: 'hasCountdown', propertyName: 'countdown', _rtlClass: 'countdown_rtl', _sectionClass: 'countdown_section', _amountClass: 'countdown_amount', _rowClass: 'countdown_row', _holdingClass: 'countdown_holding', _showClass: 'countdown_show', _descrClass: 'countdown_descr', _timerTargets: [], setDefaults: function (a) {
        this._resetExtraLabels(this._defaults, a);
        $.extend(this._defaults, a || {})
    }, UTCDate: function (a, b, c, e, f, g, h, i) {
        if (typeof b == 'object' && b.constructor == Date) {
            i = b.getMilliseconds();
            h = b.getSeconds();
            g = b.getMinutes();
            f = b.getHours();
            e = b.getDate();
            c = b.getMonth();
            b = b.getFullYear()
        }
        var d = new Date();
        d.setUTCFullYear(b);
        d.setUTCDate(1);
        d.setUTCMonth(c || 0);
        d.setUTCDate(e || 1);
        d.setUTCHours(f || 0);
        d.setUTCMinutes((g || 0) - (Math.abs(a) < 30 ? a * 60 : a));
        d.setUTCSeconds(h || 0);
        d.setUTCMilliseconds(i || 0);
        return d
    }, periodsToSeconds: function (a) {
        return a[0] * 31557600 + a[1] * 2629800 + a[2] * 604800 + a[3] * 86400 + a[4] * 3600 + a[5] * 60 + a[6]
    }, _attachPlugin: function (a, b) {
        a = $(a);
        if (a.hasClass(this.markerClassName)) {
            return
        }
        var c = {options: $.extend({}, this._defaults), _periods: [0, 0, 0, 0, 0, 0, 0]};
        a.addClass(this.markerClassName).data(this.propertyName, c);
        this._optionPlugin(a, b)
    }, _addTarget: function (a) {
        if (!this._hasTarget(a)) {
            this._timerTargets.push(a)
        }
    }, _hasTarget: function (a) {
        return($.inArray(a, this._timerTargets) > -1)
    }, _removeTarget: function (b) {
        this._timerTargets = $.map(this._timerTargets, function (a) {
            return(a == b ? null : a)
        })
    }, _updateTargets: function () {
        for (var i = this._timerTargets.length - 1; i >= 0; i--) {
            this._updateCountdown(this._timerTargets[i])
        }
    }, _optionPlugin: function (a, b, c) {
        a = $(a);
        var d = a.data(this.propertyName);
        if (!b || (typeof b == 'string' && c == null)) {
            var e = b;
            b = (d || {}).options;
            return(b && e ? b[e] : b)
        }
        if (!a.hasClass(this.markerClassName)) {
            return
        }
        b = b || {};
        if (typeof b == 'string') {
            var e = b;
            b = {};
            b[e] = c
        }
        if (b.layout) {
            b.layout = b.layout.replace(/&lt;/g, '<').replace(/&gt;/g, '>')
        }
        this._resetExtraLabels(d.options, b);
        var f = (d.options.timezone != b.timezone);
        $.extend(d.options, b);
        this._adjustSettings(a, d, b.until != null || b.since != null || f);
        var g = new Date();
        if ((d._since && d._since < g) || (d._until && d._until > g)) {
            this._addTarget(a[0])
        }
        this._updateCountdown(a, d)
    }, _updateCountdown: function (a, b) {
        var c = $(a);
        b = b || c.data(this.propertyName);
        if (!b) {
            return
        }
        c.html(this._generateHTML(b)).toggleClass(this._rtlClass, b.options.isRTL);
        if ($.isFunction(b.options.onTick)) {
            var d = b._hold != 'lap' ? b._periods : this._calculatePeriods(b, b._show, b.options.significant, new Date());
            if (b.options.tickInterval == 1 || this.periodsToSeconds(d) % b.options.tickInterval == 0) {
                b.options.onTick.apply(a, [d])
            }
        }
        var e = b._hold != 'pause' && (b._since ? b._now.getTime() < b._since.getTime() : b._now.getTime() >= b._until.getTime());
        if (e && !b._expiring) {
            b._expiring = true;
            if (this._hasTarget(a) || b.options.alwaysExpire) {
                this._removeTarget(a);
                if ($.isFunction(b.options.onExpiry)) {
                    b.options.onExpiry.apply(a, [])
                }
                if (b.options.expiryText) {
                    var f = b.options.layout;
                    b.options.layout = b.options.expiryText;
                    this._updateCountdown(a, b);
                    b.options.layout = f
                }
                if (b.options.expiryUrl) {
                    window.location = b.options.expiryUrl
                }
            }
            b._expiring = false
        } else if (b._hold == 'pause') {
            this._removeTarget(a)
        }
        c.data(this.propertyName, b)
    }, _resetExtraLabels: function (a, b) {
        var c = false;
        for (var n in b) {
            if (n != 'whichLabels' && n.match(/[Ll]abels/)) {
                c = true;
                break
            }
        }
        if (c) {
            for (var n in a) {
                if (n.match(/[Ll]abels[02-9]|compactLabels1/)) {
                    a[n] = null
                }
            }
        }
    }, _adjustSettings: function (a, b, c) {
        var d;
        var e = 0;
        var f = null;
        for (var i = 0; i < this._serverSyncs.length; i++) {
            if (this._serverSyncs[i][0] == b.options.serverSync) {
                f = this._serverSyncs[i][1];
                break
            }
        }
        if (f != null) {
            e = (b.options.serverSync ? f : 0);
            d = new Date()
        } else {
            var g = ($.isFunction(b.options.serverSync) ? b.options.serverSync.apply(a, []) : null);
            d = new Date();
            e = (g ? d.getTime() - g.getTime() : 0);
            this._serverSyncs.push([b.options.serverSync, e])
        }
        var h = b.options.timezone;
        h = (h == null ? -d.getTimezoneOffset() : h);
        if (c || (!c && b._until == null && b._since == null)) {
            b._since = b.options.since;
            if (b._since != null) {
                b._since = this.UTCDate(h, this._determineTime(b._since, null));
                if (b._since && e) {
                    b._since.setMilliseconds(b._since.getMilliseconds() + e)
                }
            }
            b._until = this.UTCDate(h, this._determineTime(b.options.until, d));
            if (e) {
                b._until.setMilliseconds(b._until.getMilliseconds() + e)
            }
        }
        b._show = this._determineShow(b)
    }, _destroyPlugin: function (a) {
        a = $(a);
        if (!a.hasClass(this.markerClassName)) {
            return
        }
        this._removeTarget(a[0]);
        a.removeClass(this.markerClassName).empty().removeData(this.propertyName)
    }, _pausePlugin: function (a) {
        this._hold(a, 'pause')
    }, _lapPlugin: function (a) {
        this._hold(a, 'lap')
    }, _resumePlugin: function (a) {
        this._hold(a, null)
    }, _hold: function (a, b) {
        var c = $.data(a, this.propertyName);
        if (c) {
            if (c._hold == 'pause' && !b) {
                c._periods = c._savePeriods;
                var d = (c._since ? '-' : '+');
                c[c._since ? '_since' : '_until'] = this._determineTime(d + c._periods[0] + 'y' + d + c._periods[1] + 'o' + d + c._periods[2] + 'w' + d + c._periods[3] + 'd' + d + c._periods[4] + 'h' + d + c._periods[5] + 'm' + d + c._periods[6] + 's');
                this._addTarget(a)
            }
            c._hold = b;
            c._savePeriods = (b == 'pause' ? c._periods : null);
            $.data(a, this.propertyName, c);
            this._updateCountdown(a, c)
        }
    }, _getTimesPlugin: function (a) {
        var b = $.data(a, this.propertyName);
        return(!b ? null : (b._hold == 'pause' ? b._savePeriods : (!b._hold ? b._periods : this._calculatePeriods(b, b._show, b.options.significant, new Date()))))
    }, _determineTime: function (k, l) {
        var m = function (a) {
            var b = new Date();
            b.setTime(b.getTime() + a * 1000);
            return b
        };
        var n = function (a) {
            a = a.toLowerCase();
            var b = new Date();
            var c = b.getFullYear();
            var d = b.getMonth();
            var e = b.getDate();
            var f = b.getHours();
            var g = b.getMinutes();
            var h = b.getSeconds();
            var i = /([+-]?[0-9]+)\s*(s|m|h|d|w|o|y)?/g;
            var j = i.exec(a);
            while (j) {
                switch (j[2] || 's') {
                    case's':
                        h += parseInt(j[1], 10);
                        break;
                    case'm':
                        g += parseInt(j[1], 10);
                        break;
                    case'h':
                        f += parseInt(j[1], 10);
                        break;
                    case'd':
                        e += parseInt(j[1], 10);
                        break;
                    case'w':
                        e += parseInt(j[1], 10) * 7;
                        break;
                    case'o':
                        d += parseInt(j[1], 10);
                        e = Math.min(e, x._getDaysInMonth(c, d));
                        break;
                    case'y':
                        c += parseInt(j[1], 10);
                        e = Math.min(e, x._getDaysInMonth(c, d));
                        break
                }
                j = i.exec(a)
            }
            return new Date(c, d, e, f, g, h, 0)
        };
        var o = (k == null ? l : (typeof k == 'string' ? n(k) : (typeof k == 'number' ? m(k) : k)));
        if (o)o.setMilliseconds(0);
        return o
    }, _getDaysInMonth: function (a, b) {
        return 32 - new Date(a, b, 32).getDate()
    }, _normalLabels: function (a) {
        return a
    }, _generateHTML: function (c) {
        var d = this;
        c._periods = (c._hold ? c._periods : this._calculatePeriods(c, c._show, c.options.significant, new Date()));
        var e = false;
        var f = 0;
        var g = c.options.significant;
        var h = $.extend({}, c._show);
        for (var i = Y; i <= S; i++) {
            e |= (c._show[i] == '?' && c._periods[i] > 0);
            h[i] = (c._show[i] == '?' && !e ? null : c._show[i]);
            f += (h[i] ? 1 : 0);
            g -= (c._periods[i] > 0 ? 1 : 0)
        }
        var j = [false, false, false, false, false, false, false];
        for (var i = S; i >= Y; i--) {
            if (c._show[i]) {
                if (c._periods[i]) {
                    j[i] = true
                } else {
                    j[i] = g > 0;
                    g--
                }
            }
        }
        var k = (c.options.compact ? c.options.compactLabels : c.options.labels);
        var l = c.options.whichLabels || this._normalLabels;
        var m = function (a) {
            var b = c.options['compactLabels' + l(c._periods[a])];
            return(h[a] ? d._translateDigits(c, c._periods[a]) + (b ? b[a] : k[a]) + ' ' : '')
        };
        var n = function (a) {
            var b = c.options['labels' + l(c._periods[a])];
            return((!c.options.significant && h[a]) || (c.options.significant && j[a]) ? '<span class="' + x._sectionClass + '">' + '<span class="' + x._amountClass + '">' + d._translateDigits(c, c._periods[a]) + '</span><br/>' + (b ? b[a] : k[a]) + '</span>' : '')
        };
        return(c.options.layout ? this._buildLayout(c, h, c.options.layout, c.options.compact, c.options.significant, j) : ((c.options.compact ? '<span class="' + this._rowClass + ' ' + this._amountClass + (c._hold ? ' ' + this._holdingClass : '') + '">' + m(Y) + m(O) + m(W) + m(D) + (h[H] ? this._minDigits(c, c._periods[H], 2) : '') + (h[M] ? (h[H] ? c.options.timeSeparator : '') + this._minDigits(c, c._periods[M], 2) : '') + (h[S] ? (h[H] || h[M] ? c.options.timeSeparator : '') + this._minDigits(c, c._periods[S], 2) : '') : '<span class="' + this._rowClass + ' ' + this._showClass + (c.options.significant || f) + (c._hold ? ' ' + this._holdingClass : '') + '">' + n(Y) + n(O) + n(W) + n(D) + n(H) + n(M) + n(S)) + '</span>' + (c.options.description ? '<span class="' + this._rowClass + ' ' + this._descrClass + '">' + c.options.description + '</span>' : '')))
    }, _buildLayout: function (c, d, e, f, g, h) {
        var j = c.options[f ? 'compactLabels' : 'labels'];
        var k = c.options.whichLabels || this._normalLabels;
        var l = function (a) {
            return(c.options[(f ? 'compactLabels' : 'labels') + k(c._periods[a])] || j)[a]
        };
        var m = function (a, b) {
            return c.options.digits[Math.floor(a / b) % 10]
        };
        var o = {desc: c.options.description, sep: c.options.timeSeparator, yl: l(Y), yn: this._minDigits(c, c._periods[Y], 1), ynn: this._minDigits(c, c._periods[Y], 2), ynnn: this._minDigits(c, c._periods[Y], 3), y1: m(c._periods[Y], 1), y10: m(c._periods[Y], 10), y100: m(c._periods[Y], 100), y1000: m(c._periods[Y], 1000), ol: l(O), on: this._minDigits(c, c._periods[O], 1), onn: this._minDigits(c, c._periods[O], 2), onnn: this._minDigits(c, c._periods[O], 3), o1: m(c._periods[O], 1), o10: m(c._periods[O], 10), o100: m(c._periods[O], 100), o1000: m(c._periods[O], 1000), wl: l(W), wn: this._minDigits(c, c._periods[W], 1), wnn: this._minDigits(c, c._periods[W], 2), wnnn: this._minDigits(c, c._periods[W], 3), w1: m(c._periods[W], 1), w10: m(c._periods[W], 10), w100: m(c._periods[W], 100), w1000: m(c._periods[W], 1000), dl: l(D), dn: this._minDigits(c, c._periods[D], 1), dnn: this._minDigits(c, c._periods[D], 2), dnnn: this._minDigits(c, c._periods[D], 3), d1: m(c._periods[D], 1), d10: m(c._periods[D], 10), d100: m(c._periods[D], 100), d1000: m(c._periods[D], 1000), hl: l(H), hn: this._minDigits(c, c._periods[H], 1), hnn: this._minDigits(c, c._periods[H], 2), hnnn: this._minDigits(c, c._periods[H], 3), h1: m(c._periods[H], 1), h10: m(c._periods[H], 10), h100: m(c._periods[H], 100), h1000: m(c._periods[H], 1000), ml: l(M), mn: this._minDigits(c, c._periods[M], 1), mnn: this._minDigits(c, c._periods[M], 2), mnnn: this._minDigits(c, c._periods[M], 3), m1: m(c._periods[M], 1), m10: m(c._periods[M], 10), m100: m(c._periods[M], 100), m1000: m(c._periods[M], 1000), sl: l(S), sn: this._minDigits(c, c._periods[S], 1), snn: this._minDigits(c, c._periods[S], 2), snnn: this._minDigits(c, c._periods[S], 3), s1: m(c._periods[S], 1), s10: m(c._periods[S], 10), s100: m(c._periods[S], 100), s1000: m(c._periods[S], 1000)};
        var p = e;
        for (var i = Y; i <= S; i++) {
            var q = 'yowdhms'.charAt(i);
            var r = new RegExp('\\{' + q + '<\\}([\\s\\S]*)\\{' + q + '>\\}', 'g');
            p = p.replace(r, ((!g && d[i]) || (g && h[i]) ? '$1' : ''))
        }
        $.each(o, function (n, v) {
            var a = new RegExp('\\{' + n + '\\}', 'g');
            p = p.replace(a, v)
        });
        return p
    }, _minDigits: function (a, b, c) {
        b = '' + b;
        if (b.length >= c) {
            return this._translateDigits(a, b)
        }
        b = '0000000000' + b;
        return this._translateDigits(a, b.substr(b.length - c))
    }, _translateDigits: function (b, c) {
        return('' + c).replace(/[0-9]/g, function (a) {
            return b.options.digits[a]
        })
    }, _determineShow: function (a) {
        var b = a.options.format;
        var c = [];
        c[Y] = (b.match('y') ? '?' : (b.match('Y') ? '!' : null));
        c[O] = (b.match('o') ? '?' : (b.match('O') ? '!' : null));
        c[W] = (b.match('w') ? '?' : (b.match('W') ? '!' : null));
        c[D] = (b.match('d') ? '?' : (b.match('D') ? '!' : null));
        c[H] = (b.match('h') ? '?' : (b.match('H') ? '!' : null));
        c[M] = (b.match('m') ? '?' : (b.match('M') ? '!' : null));
        c[S] = (b.match('s') ? '?' : (b.match('S') ? '!' : null));
        return c
    }, _calculatePeriods: function (c, d, e, f) {
        c._now = f;
        c._now.setMilliseconds(0);
        var g = new Date(c._now.getTime());
        if (c._since) {
            if (f.getTime() < c._since.getTime()) {
                c._now = f = g
            } else {
                f = c._since
            }
        } else {
            g.setTime(c._until.getTime());
            if (f.getTime() > c._until.getTime()) {
                c._now = f = g
            }
        }
        var h = [0, 0, 0, 0, 0, 0, 0];
        if (d[Y] || d[O]) {
            var i = x._getDaysInMonth(f.getFullYear(), f.getMonth());
            var j = x._getDaysInMonth(g.getFullYear(), g.getMonth());
            var k = (g.getDate() == f.getDate() || (g.getDate() >= Math.min(i, j) && f.getDate() >= Math.min(i, j)));
            var l = function (a) {
                return(a.getHours() * 60 + a.getMinutes()) * 60 + a.getSeconds()
            };
            var m = Math.max(0, (g.getFullYear() - f.getFullYear()) * 12 + g.getMonth() - f.getMonth() + ((g.getDate() < f.getDate() && !k) || (k && l(g) < l(f)) ? -1 : 0));
            h[Y] = (d[Y] ? Math.floor(m / 12) : 0);
            h[O] = (d[O] ? m - h[Y] * 12 : 0);
            f = new Date(f.getTime());
            var n = (f.getDate() == i);
            var o = x._getDaysInMonth(f.getFullYear() + h[Y], f.getMonth() + h[O]);
            if (f.getDate() > o) {
                f.setDate(o)
            }
            f.setFullYear(f.getFullYear() + h[Y]);
            f.setMonth(f.getMonth() + h[O]);
            if (n) {
                f.setDate(o)
            }
        }
        var p = Math.floor((g.getTime() - f.getTime()) / 1000);
        var q = function (a, b) {
            h[a] = (d[a] ? Math.floor(p / b) : 0);
            p -= h[a] * b
        };
        q(W, 604800);
        q(D, 86400);
        q(H, 3600);
        q(M, 60);
        q(S, 1);
        if (p > 0 && !c._since) {
            var r = [1, 12, 4.3482, 7, 24, 60, 60];
            var s = S;
            var t = 1;
            for (var u = S; u >= Y; u--) {
                if (d[u]) {
                    if (h[s] >= t) {
                        h[s] = 0;
                        p = 1
                    }
                    if (p > 0) {
                        h[u]++;
                        p = 0;
                        s = u;
                        t = 1
                    }
                }
                t *= r[u]
            }
        }
        if (e) {
            for (var u = Y; u <= S; u++) {
                if (e && h[u]) {
                    e--
                } else if (!e) {
                    h[u] = 0
                }
            }
        }
        return h
    }});
    var w = ['getTimes'];

    function isNotChained(a, b) {
        if (a == 'option' && (b.length == 0 || (b.length == 1 && typeof b[0] == 'string'))) {
            return true
        }
        return $.inArray(a, w) > -1
    }

    $.fn.countdown = function (a) {
        var b = Array.prototype.slice.call(arguments, 1);
        if (isNotChained(a, b)) {
            return x['_' + a + 'Plugin'].apply(x, [this[0]].concat(b))
        }
        return this.each(function () {
            if (typeof a == 'string') {
                if (!x['_' + a + 'Plugin']) {
                    throw'Unknown command: ' + a;
                }
                x['_' + a + 'Plugin'].apply(x, [this].concat(b))
            } else {
                x._attachPlugin(this, a || {})
            }
        })
    };
    var x = $.countdown = new Countdown()
})(jQuery);
var RESPONSIVEUIQUICK = {};
RESPONSIVEUIQUICK.responsiveTabsQuick = function () {
    var e = jQuery(".responsive-tabs2");
    if (!e.hasClass("responsive-tabs--enabled")) {
        e.addClass("responsive-tabs--enabled");
        var t = 1;
        e.each(function () {
            var e = jQuery(this);
            e.children("h1,h2,h3,h4,h5,h6").addClass("responsive-tabs__heading");
            e.children("div").addClass("responsive-tabs__panel");
            var n = e.find(".responsive-tabs__panel--active");
            if (!n.length) {
                var n = e.find(".responsive-tabs__panel").first().addClass("responsive-tabs__panel--active")
            }
            e.find(".responsive-tabs__panel").not(".responsive-tabs__panel--active").hide().attr("aria-hidden", "true");
            n.attr("aria-hidden", "false");
            n.addClass("responsive-tabs__panel--closed-accordion-only");
            var r = jQuery("<div/>", {Class: "responsive-tabs-wrapper"});
            e.wrap(r);
            var i = 0;
            e.find(".responsive-tabs__panel").each(function () {
                var e = jQuery(this).height();
                if (e > i) {
                    i = e
                }
            });
            var s = jQuery("<ul/>", {Class: "responsive-tabs__list", role: "tablist"});
            var o = 1;
            e.find(".responsive-tabs__heading").each(function () {
                var n = jQuery(this);
                var u = jQuery(this).next();
                n.attr("tabindex", 0);
                var a = jQuery("<li/>", {Class: "responsive-tabs__list__item", id: "tablist" + t + "-tab" + o, "aria-controls": "tablist" + t + "-panel" + o, role: "tab", tabindex: 0, text: n.text(), keydown: function (e) {
                    if (e.keyCode == 13) {
                        a.click()
                    }
                }, click: function () {
                    r.css("height", i);
                    e.find(".responsive-tabs__panel--closed-accordion-only").removeClass("responsive-tabs__panel--closed-accordion-only");
                    e.find(".responsive-tabs__panel--active").toggle().removeClass("responsive-tabs__panel--active").attr("aria-hidden", "true").prev().removeClass("responsive-tabs__heading--active");
                    u.toggle().addClass("responsive-tabs__panel--active").attr("aria-hidden", "false");
                    n.addClass("responsive-tabs__heading--active");
                    s.find(".responsive-tabs__list__item--active").removeClass("responsive-tabs__list__item--active");
                    a.addClass("responsive-tabs__list__item--active");
                    r.css("height", "auto")
                }});
                u.attr({role: "tabpanel", "aria-labelledby": a.attr("id"), id: "tablist" + t + "-panel" + o});
                if (u.hasClass("responsive-tabs__panel--active")) {
                    a.addClass("responsive-tabs__list__item--active")
                }
                s.append(a);
                n.keydown(function (e) {
                    if (e.keyCode == 13) {
                        n.click()
                    }
                });
                n.click(function () {
                    e.find(".responsive-tabs__panel--closed-accordion-only").removeClass("responsive-tabs__panel--closed-accordion-only");
                    if (!n.hasClass("responsive-tabs__heading--active")) {
                        if (jQuery(".responsive-tabs__heading--active").length) {
                            var t = jQuery(".responsive-tabs__heading--active").offset().top
                        }
                        e.find(".responsive-tabs__panel--active").slideToggle().removeClass("responsive-tabs__panel--active").prev().removeClass("responsive-tabs__heading--active");
                        e.find(".responsive-tabs__panel").hide().attr("aria-hidden", "true");
                        u.slideToggle().addClass("responsive-tabs__panel--active").attr("aria-hidden", "false");
                        n.addClass("responsive-tabs__heading--active");
                        var r = e.find(".responsive-tabs__list__item--active");
                        r.removeClass("responsive-tabs__list__item--active");
                        var i = u.attr("id");
                        var s = i.replace("panel", "tab");
                        jQuery("#" + s).addClass("responsive-tabs__list__item--active");
                        var o = jQuery(".responsive-tabs").offset().top;
                        var a = jQuery(".responsive-tabs__heading--active").offset().top;
                        if (t < a) {
                            jQuery("html, body").animate({scrollTop: o}, 0).animate({scrollTop: a}, 400)
                        }
                    } else {
                        u.removeClass("responsive-tabs__panel--active").slideToggle(function () {
                            jQuery(this).addClass("responsive-tabs__panel--closed-accordion-only")
                        });
                        n.removeClass("responsive-tabs__heading--active")
                    }
                });
                o++
            });
            e.prepend(s);
            t++
        })
    }
};
