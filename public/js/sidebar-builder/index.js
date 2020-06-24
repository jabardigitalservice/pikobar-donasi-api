!function (e, t, i, n) {
    var r = "ontouchstart" in i,
        o = function () {
            var e = i.createElement("div"),
                n = i.documentElement;
            if (!("pointerEvents" in e.style))
                return !1;
            e.style.pointerEvents = "auto",
                e.style.pointerEvents = "x",
                n.appendChild(e);
            var r = t.getComputedStyle && "auto" === t.getComputedStyle(e, "").pointerEvents;
            return n.removeChild(e),
                !!r
        }(),
        a = {
            listNodeName: "ol",
            itemNodeName: "li",
            rootClass: "dd",
            listClass: "dd-list",
            itemClass: "dd-item",
            dragClass: "dd-dragel",
            handleClass: "dd-handle",
            collapsedClass: "dd-collapsed",
            placeClass: "dd-placeholder",
            noDragClass: "dd-nodrag",
            emptyClass: "dd-empty",
            expandBtnHTML: '<button data-action="expand" type="button">Expand</button>',
            collapseBtnHTML: '<button data-action="collapse" type="button">Collapse</button>',
            group: 0,
            maxDepth: 5,
            threshold: 20
        };

    function s(t, n) {
        this.w = e(i),
            this.el = e(t),
            this.options = e.extend({}, a, n),
            this.init()
    }

    s.prototype = {
        init: function () {
            var i = this;
            i.reset(),
                i.el.data("nestable-group", this.options.group),
                i.placeEl = e('<div class="' + i.options.placeClass + '"/>'),
                e.each(this.el.find(i.options.itemNodeName), function (t, n) {
                    i.setParent(e(n))
                }),
                i.el.on("click", "button", function (t) {
                    if (!i.dragEl) {
                        var n = e(t.currentTarget),
                            r = n.data("action"),
                            o = n.parent(i.options.itemNodeName);
                        "collapse" === r && i.collapseItem(o),
                        "expand" === r && i.expandItem(o)
                    }
                });
            var n = function (t) {
                    var n = e(t.target);
                    if (!n.hasClass(i.options.handleClass)) {
                        if (n.closest("." + i.options.noDragClass).length)
                            return;
                        n = n.closest("." + i.options.handleClass)
                    }
                    n.length && !i.dragEl && (i.isTouch = /^touch/.test(t.type), i.isTouch && 1 !== t.touches.length ||
                    (t.preventDefault(), i.dragStart(t.touches ? t.touches[0] : t)))
                },
                o = function (e) {
                    i.dragEl && (e.preventDefault(), i.dragMove(e.touches ? e.touches[0] : e))
                },
                a = function (e) {
                    i.dragEl && (e.preventDefault(), i.dragStop(e.touches ? e.touches[0] : e))
                };
            r && (i.el[0].addEventListener("touchstart", n, !1), t.addEventListener("touchmove", o, !1), t.addEventListener("touchend", a, !1), t.addEventListener("touchcancel", a, !1)),
                i.el.on("mousedown", n),
                i.w.on("mousemove", o),
                i.w.on("mouseup", a)
        },
        serialize: function () {
            var t = this;
            return step = function (i, n) {
                var r = [];
                return i.children(t.options.itemNodeName).each(function () {
                    var i = e(this),
                        o = e.extend({}, i.data()),
                        a = i.children(t.options.listNodeName);
                    a.length && (o.children = step(a, n + 1)),
                        r.push(o)
                }), r
            }, step(t.el.find(t.options.listNodeName).first(), 0)
        },
        serialise: function () {
            return this.serialize()
        },
        reset: function () {
            this.mouse = {
                offsetX: 0,
                offsetY: 0,
                startX: 0,
                startY: 0,
                lastX: 0,
                lastY: 0,
                nowX: 0,
                nowY: 0,
                distX: 0,
                distY: 0,
                dirAx: 0,
                dirX: 0,
                dirY: 0,
                lastDirX: 0,
                lastDirY: 0,
                distAxX: 0,
                distAxY: 0
            },
                this.isTouch = !1,
                this.moving = !1,
                this.dragEl = null,
                this.dragRootEl = null,
                this.dragDepth = 0,
                this.hasNewRoot = !1,
                this.pointEl = null
        },
        expandItem: function (e) {
            e.removeClass(this.options.collapsedClass),
                e.children('[data-action="expand"]').hide(),
                e.children('[data-action="collapse"]').show(),
                e.children(this.options.listNodeName).show()
        },
        collapseItem: function (e) {
            e.children(this.options.listNodeName).length && (e.addClass(this.options.collapsedClass),
                e.children('[data-action="collapse"]').hide(),
                e.children('[data-action="expand"]').show(),
                e.children(this.options.listNodeName).hide())
        },
        expandAll: function () {
            var t = this;
            t.el.find(t.options.itemNodeName).each(function () {
                t.expandItem(e(this))
            })
        },
        collapseAll: function () {
            var t = this;
            t.el.find(t.options.itemNodeName).each(function () {
                t.collapseItem(e(this))
            })
        },
        setParent: function (t) {
            t.children(this.options.listNodeName).length &&
            (t.prepend(e(this.options.expandBtnHTML)), t.prepend(e(this.options.collapseBtnHTML))),
                t.children('[data-action="expand"]').hide()
        },
        unsetParent: function (e) {
            e.removeClass(this.options.collapsedClass),
                e.children("[data-action]").remove(),
                e.children(this.options.listNodeName).remove()
        },
        dragStart: function (t) {
            var n = this.mouse,
                r = e(t.target),
                o = r.closest(this.options.itemNodeName);
            this.placeEl.css("height", o.height()),
                n.offsetX = void 0 !== t.offsetX ? t.offsetX : t.pageX - r.offset().left,
                n.offsetY = void 0 !== t.offsetY ? t.offsetY : t.pageY - r.offset().top,
                n.startX = n.lastX = t.pageX,
                n.startY = n.lastY = t.pageY,
                this.dragRootEl = this.el,
                this.dragEl = e(i.createElement(this.options.listNodeName))
                    .addClass(this.options.listClass + " " + this.options.dragClass),
                this.dragEl.css("width", o.width()),
                o.after(this.placeEl),
                o[0].parentNode.removeChild(o[0]),
                o.appendTo(this.dragEl),
                e(i.body).append(this.dragEl),
                this.dragEl.css({
                    left: t.pageX - n.offsetX,
                    top: t.pageY - n.offsetY
                });
            var a,
                s,
                l = this.dragEl.find(this.options.itemNodeName);
            for (a = 0; a < l.length; a++)
                (s = e(l[a]).parents(this.options.listNodeName).length) > this.dragDepth && (this.dragDepth = s)
        },
        dragStop: function (e) {
            var t = this.dragEl.children(this.options.itemNodeName).first();
            t[0].parentNode.removeChild(t[0]),
                this.placeEl.replaceWith(t),
                this.dragEl.remove(),
                this.el.trigger("change"),
            this.hasNewRoot && this.dragRootEl.trigger("change"),
                this.reset()
        },
        dragMove: function (n) {
            var r,
                a,
                s,
                l = this.options,
                d = this.mouse;
            this.dragEl.css({
                left: n.pageX - d.offsetX,
                top: n.pageY - d.offsetY
            }),
                d.lastX = d.nowX,
                d.lastY = d.nowY,
                d.nowX = n.pageX,
                d.nowY = n.pageY,
                d.distX = d.nowX - d.lastX,
                d.distY = d.nowY - d.lastY,
                d.lastDirX = d.dirX,
                d.lastDirY = d.dirY,
                d.dirX = 0 === d.distX ? 0 : d.distX > 0 ? 1 : -1,
                d.dirY = 0 === d.distY ? 0 : d.distY > 0 ? 1 : -1;
            var c = Math.abs(d.distX) > Math.abs(d.distY) ? 1 : 0;
            if (!d.moving)
                return d.dirAx = c, void(d.moving = !0);
            d.dirAx !== c ? (d.distAxX = 0, d.distAxY = 0) : (d.distAxX += Math.abs(d.distX), 0 !== d.dirX && d.dirX !== d.lastDirX && (d.distAxX = 0), d.distAxY += Math.abs(d.distY), 0 !== d.dirY && d.dirY !== d.lastDirY && (d.distAxY = 0)),
                d.dirAx = c,
            d.dirAx && d.distAxX >= l.threshold && (d.distAxX = 0, s = this.placeEl.prev(l.itemNodeName), d.distX > 0 && s.length && !s.hasClass(l.collapsedClass) && (r = s.find(l.listNodeName).last(), this.placeEl.parents(l.listNodeName).length + this.dragDepth <= l.maxDepth && (r.length ? (r = s.children(l.listNodeName).last()).append(this.placeEl) : ((r = e("<" + l.listNodeName + "/>").addClass(l.listClass)).append(this.placeEl), s.append(r), this.setParent(s)))), d.distX < 0 && (this.placeEl.next(l.itemNodeName).length || (a = this.placeEl.parent(), this.placeEl.closest(l.itemNodeName).after(this.placeEl), a.children().length || this.unsetParent(a.parent()))));
            var h = !1;
            if (o || (this.dragEl[0].style.visibility = "hidden"), this.pointEl = e(i.elementFromPoint(n.pageX - i.body.scrollLeft, n.pageY - (t.pageYOffset || i.documentElement.scrollTop))), o || (this.dragEl[0].style.visibility = "visible"), this.pointEl.hasClass(l.handleClass) && (this.pointEl = this.pointEl.parent(l.itemNodeName)), this.pointEl.hasClass(l.emptyClass))
                h = !0;
            else if (!this.pointEl.length || !this.pointEl.hasClass(l.itemClass))
                return;
            var p = this.pointEl.closest("." + l.rootClass),
                u = this.dragRootEl.data("nestable-id") !== p.data("nestable-id");
            if (!d.dirAx || u || h) {
                if (u && l.group !== p.data("nestable-group"))
                    return;
                if (this.dragDepth - 1 + this.pointEl.parents(l.listNodeName).length > l.maxDepth)
                    return;
                var f = n.pageY < this.pointEl.offset().top + this.pointEl.height() / 2;
                a = this.placeEl.parent(),
                    h ? ((r = e(i.createElement(l.listNodeName)).addClass(l.listClass)).append(this.placeEl), this.pointEl.replaceWith(r)) : f ? this.pointEl.before(this.placeEl) : this.pointEl.after(this.placeEl),
                a.children().length || this.unsetParent(a.parent()),
                this.dragRootEl.find(l.itemNodeName).length || this.dragRootEl.append('<div class="' + l.emptyClass + '"/>'),
                u && (this.dragRootEl = p, this.hasNewRoot = this.el[0] !== this.dragRootEl[0])
            }
        }
    }, e.fn.nestable = function (t) {
        var i = this;
        return this.each(function () {
            var n = e(this).data("nestable");
            n ? "string" == typeof t && "function" == typeof n[t] && (i = n[t]()) : (e(this).data("nestable", new s(this, t)), e(this).data("nestable-id", (new Date).getTime()))
        }),
        i || this
    }
}(window.jQuery, window, document);
