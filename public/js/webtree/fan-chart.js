var t, e;
(t = this),
    (e = function (t) {
        var e = "http://www.w3.org/1999/xhtml",
            n = { svg: "http://www.w3.org/2000/svg", xhtml: e, xlink: "http://www.w3.org/1999/xlink", xml: "http://www.w3.org/XML/1998/namespace", xmlns: "http://www.w3.org/2000/xmlns/" };
        function r(t) {
            var e = (t += ""),
                r = e.indexOf(":");
            return r >= 0 && "xmlns" !== (e = t.slice(0, r)) && (t = t.slice(r + 1)), n.hasOwnProperty(e) ? { space: n[e], local: t } : t;
        }
        function i(t) {
            return function () {
                var n = this.ownerDocument,
                    r = this.namespaceURI;
                return r === e && n.documentElement.namespaceURI === e ? n.createElement(t) : n.createElementNS(r, t);
            };
        }
        function a(t) {
            return function () {
                return this.ownerDocument.createElementNS(t.space, t.local);
            };
        }
        function o(t) {
            var e = r(t);
            return (e.local ? a : i)(e);
        }
        function s() {}
        function u(t) {
            return null == t
                ? s
                : function () {
                      return this.querySelector(t);
                  };
        }
        function l() {
            return [];
        }
        function h(t) {
            return null == t
                ? l
                : function () {
                      return this.querySelectorAll(t);
                  };
        }
        function c(t) {
            return function () {
                return null == (e = t.apply(this, arguments)) ? [] : Array.isArray(e) ? e : Array.from(e);
                var e;
            };
        }
        function f(t) {
            return function () {
                return this.matches(t);
            };
        }
        function d(t) {
            return function (e) {
                return e.matches(t);
            };
        }
        var p = Array.prototype.find;
        function g() {
            return this.firstElementChild;
        }
        var m = Array.prototype.filter;
        function v() {
            return Array.from(this.children);
        }
        function _(t) {
            return new Array(t.length);
        }
        function y(t, e) {
            (this.ownerDocument = t.ownerDocument), (this.namespaceURI = t.namespaceURI), (this._next = null), (this._parent = t), (this.__data__ = e);
        }
        function w(t, e, n, r, i, a) {
            for (var o, s = 0, u = e.length, l = a.length; s < l; ++s) (o = e[s]) ? ((o.__data__ = a[s]), (r[s] = o)) : (n[s] = new y(t, a[s]));
            for (; s < u; ++s) (o = e[s]) && (i[s] = o);
        }
        function x(t, e, n, r, i, a, o) {
            var s,
                u,
                l,
                h = new Map(),
                c = e.length,
                f = a.length,
                d = new Array(c);
            for (s = 0; s < c; ++s) (u = e[s]) && ((d[s] = l = o.call(u, u.__data__, s, e) + ""), h.has(l) ? (i[s] = u) : h.set(l, u));
            for (s = 0; s < f; ++s) (l = o.call(t, a[s], s, a) + ""), (u = h.get(l)) ? ((r[s] = u), (u.__data__ = a[s]), h.delete(l)) : (n[s] = new y(t, a[s]));
            for (s = 0; s < c; ++s) (u = e[s]) && h.get(d[s]) === u && (i[s] = u);
        }
        function b(t) {
            return t.__data__;
        }
        function M(t) {
            return "object" == typeof t && "length" in t ? t : Array.from(t);
        }
        function A(t, e) {
            return t < e ? -1 : t > e ? 1 : t >= e ? 0 : NaN;
        }
        function N(t) {
            return function () {
                this.removeAttribute(t);
            };
        }
        function k(t) {
            return function () {
                this.removeAttributeNS(t.space, t.local);
            };
        }
        function S(t, e) {
            return function () {
                this.setAttribute(t, e);
            };
        }
        function E(t, e) {
            return function () {
                this.setAttributeNS(t.space, t.local, e);
            };
        }
        function $(t, e) {
            return function () {
                var n = e.apply(this, arguments);
                null == n ? this.removeAttribute(t) : this.setAttribute(t, n);
            };
        }
        function P(t, e) {
            return function () {
                var n = e.apply(this, arguments);
                null == n ? this.removeAttributeNS(t.space, t.local) : this.setAttributeNS(t.space, t.local, n);
            };
        }
        function T(t) {
            return (t.ownerDocument && t.ownerDocument.defaultView) || (t.document && t) || t.defaultView;
        }
        function z(t) {
            return function () {
                this.style.removeProperty(t);
            };
        }
        function C(t, e, n) {
            return function () {
                this.style.setProperty(t, e, n);
            };
        }
        function R(t, e, n) {
            return function () {
                var r = e.apply(this, arguments);
                null == r ? this.style.removeProperty(t) : this.style.setProperty(t, r, n);
            };
        }
        function D(t, e) {
            return t.style.getPropertyValue(e) || T(t).getComputedStyle(t, null).getPropertyValue(e);
        }
        function I(t) {
            return function () {
                delete this[t];
            };
        }
        function L(t, e) {
            return function () {
                this[t] = e;
            };
        }
        function O(t, e) {
            return function () {
                var n = e.apply(this, arguments);
                null == n ? delete this[t] : (this[t] = n);
            };
        }
        function B(t) {
            return t.trim().split(/^|\s+/);
        }
        function q(t) {
            return t.classList || new X(t);
        }
        function X(t) {
            (this._node = t), (this._names = B(t.getAttribute("class") || ""));
        }
        function j(t, e) {
            for (var n = q(t), r = -1, i = e.length; ++r < i; ) n.add(e[r]);
        }
        function H(t, e) {
            for (var n = q(t), r = -1, i = e.length; ++r < i; ) n.remove(e[r]);
        }
        function G(t) {
            return function () {
                j(this, t);
            };
        }
        function Y(t) {
            return function () {
                H(this, t);
            };
        }
        function V(t, e) {
            return function () {
                (e.apply(this, arguments) ? j : H)(this, t);
            };
        }
        function F() {
            this.textContent = "";
        }
        function U(t) {
            return function () {
                this.textContent = t;
            };
        }
        function W(t) {
            return function () {
                var e = t.apply(this, arguments);
                this.textContent = null == e ? "" : e;
            };
        }
        function K() {
            this.innerHTML = "";
        }
        function Q(t) {
            return function () {
                this.innerHTML = t;
            };
        }
        function Z(t) {
            return function () {
                var e = t.apply(this, arguments);
                this.innerHTML = null == e ? "" : e;
            };
        }
        function J() {
            this.nextSibling && this.parentNode.appendChild(this);
        }
        function tt() {
            this.previousSibling && this.parentNode.insertBefore(this, this.parentNode.firstChild);
        }
        function et() {
            return null;
        }
        function nt() {
            var t = this.parentNode;
            t && t.removeChild(this);
        }
        function rt() {
            var t = this.cloneNode(!1),
                e = this.parentNode;
            return e ? e.insertBefore(t, this.nextSibling) : t;
        }
        function it() {
            var t = this.cloneNode(!0),
                e = this.parentNode;
            return e ? e.insertBefore(t, this.nextSibling) : t;
        }
        function at(t) {
            return function () {
                var e = this.__on;
                if (e) {
                    for (var n, r = 0, i = -1, a = e.length; r < a; ++r) (n = e[r]), (t.type && n.type !== t.type) || n.name !== t.name ? (e[++i] = n) : this.removeEventListener(n.type, n.listener, n.options);
                    ++i ? (e.length = i) : delete this.__on;
                }
            };
        }
        function ot(t, e, n) {
            return function () {
                var r,
                    i = this.__on,
                    a = (function (t) {
                        return function (e) {
                            t.call(this, e, this.__data__);
                        };
                    })(e);
                if (i)
                    for (var o = 0, s = i.length; o < s; ++o)
                        if ((r = i[o]).type === t.type && r.name === t.name) return this.removeEventListener(r.type, r.listener, r.options), this.addEventListener(r.type, (r.listener = a), (r.options = n)), void (r.value = e);
                this.addEventListener(t.type, a, n), (r = { type: t.type, name: t.name, value: e, listener: a, options: n }), i ? i.push(r) : (this.__on = [r]);
            };
        }
        function st(t, e, n) {
            var r = T(t),
                i = r.CustomEvent;
            "function" == typeof i ? (i = new i(e, n)) : ((i = r.document.createEvent("Event")), n ? (i.initEvent(e, n.bubbles, n.cancelable), (i.detail = n.detail)) : i.initEvent(e, !1, !1)), t.dispatchEvent(i);
        }
        function ut(t, e) {
            return function () {
                return st(this, t, e);
            };
        }
        function lt(t, e) {
            return function () {
                return st(this, t, e.apply(this, arguments));
            };
        }
        (y.prototype = {
            constructor: y,
            appendChild: function (t) {
                return this._parent.insertBefore(t, this._next);
            },
            insertBefore: function (t, e) {
                return this._parent.insertBefore(t, e);
            },
            querySelector: function (t) {
                return this._parent.querySelector(t);
            },
            querySelectorAll: function (t) {
                return this._parent.querySelectorAll(t);
            },
        }),
            (X.prototype = {
                add: function (t) {
                    this._names.indexOf(t) < 0 && (this._names.push(t), this._node.setAttribute("class", this._names.join(" ")));
                },
                remove: function (t) {
                    var e = this._names.indexOf(t);
                    e >= 0 && (this._names.splice(e, 1), this._node.setAttribute("class", this._names.join(" ")));
                },
                contains: function (t) {
                    return this._names.indexOf(t) >= 0;
                },
            });
        var ht = [null];
        function ct(t, e) {
            (this._groups = t), (this._parents = e);
        }
        function ft() {
            return new ct([[document.documentElement]], ht);
        }
        function dt(t) {
            return "string" == typeof t ? new ct([[document.querySelector(t)]], [document.documentElement]) : new ct([[t]], ht);
        }
        function pt(t, e) {
            if (
                ((t = (function (t) {
                    let e;
                    for (; (e = t.sourceEvent); ) t = e;
                    return t;
                })(t)),
                void 0 === e && (e = t.currentTarget),
                e)
            ) {
                var n = e.ownerSVGElement || e;
                if (n.createSVGPoint) {
                    var r = n.createSVGPoint();
                    return (r.x = t.clientX), (r.y = t.clientY), [(r = r.matrixTransform(e.getScreenCTM().inverse())).x, r.y];
                }
                if (e.getBoundingClientRect) {
                    var i = e.getBoundingClientRect();
                    return [t.clientX - i.left - e.clientLeft, t.clientY - i.top - e.clientTop];
                }
            }
            return [t.pageX, t.pageY];
        }
        ct.prototype = ft.prototype = {
            constructor: ct,
            select: function (t) {
                "function" != typeof t && (t = u(t));
                for (var e = this._groups, n = e.length, r = new Array(n), i = 0; i < n; ++i)
                    for (var a, o, s = e[i], l = s.length, h = (r[i] = new Array(l)), c = 0; c < l; ++c) (a = s[c]) && (o = t.call(a, a.__data__, c, s)) && ("__data__" in a && (o.__data__ = a.__data__), (h[c] = o));
                return new ct(r, this._parents);
            },
            selectAll: function (t) {
                t = "function" == typeof t ? c(t) : h(t);
                for (var e = this._groups, n = e.length, r = [], i = [], a = 0; a < n; ++a) for (var o, s = e[a], u = s.length, l = 0; l < u; ++l) (o = s[l]) && (r.push(t.call(o, o.__data__, l, s)), i.push(o));
                return new ct(r, i);
            },
            selectChild: function (t) {
                return this.select(
                    null == t
                        ? g
                        : (function (t) {
                              return function () {
                                  return p.call(this.children, t);
                              };
                          })("function" == typeof t ? t : d(t))
                );
            },
            selectChildren: function (t) {
                return this.selectAll(
                    null == t
                        ? v
                        : (function (t) {
                              return function () {
                                  return m.call(this.children, t);
                              };
                          })("function" == typeof t ? t : d(t))
                );
            },
            filter: function (t) {
                "function" != typeof t && (t = f(t));
                for (var e = this._groups, n = e.length, r = new Array(n), i = 0; i < n; ++i) for (var a, o = e[i], s = o.length, u = (r[i] = []), l = 0; l < s; ++l) (a = o[l]) && t.call(a, a.__data__, l, o) && u.push(a);
                return new ct(r, this._parents);
            },
            data: function (t, e) {
                if (!arguments.length) return Array.from(this, b);
                var n,
                    r = e ? x : w,
                    i = this._parents,
                    a = this._groups;
                "function" != typeof t &&
                    ((n = t),
                    (t = function () {
                        return n;
                    }));
                for (var o = a.length, s = new Array(o), u = new Array(o), l = new Array(o), h = 0; h < o; ++h) {
                    var c = i[h],
                        f = a[h],
                        d = f.length,
                        p = M(t.call(c, c && c.__data__, h, i)),
                        g = p.length,
                        m = (u[h] = new Array(g)),
                        v = (s[h] = new Array(g));
                    r(c, f, m, v, (l[h] = new Array(d)), p, e);
                    for (var _, y, A = 0, N = 0; A < g; ++A)
                        if ((_ = m[A])) {
                            for (A >= N && (N = A + 1); !(y = v[N]) && ++N < g; );
                            _._next = y || null;
                        }
                }
                return ((s = new ct(s, i))._enter = u), (s._exit = l), s;
            },
            enter: function () {
                return new ct(this._enter || this._groups.map(_), this._parents);
            },
            exit: function () {
                return new ct(this._exit || this._groups.map(_), this._parents);
            },
            join: function (t, e, n) {
                var r = this.enter(),
                    i = this,
                    a = this.exit();
                return "function" == typeof t ? (r = t(r)) && (r = r.selection()) : (r = r.append(t + "")), null != e && (i = e(i)) && (i = i.selection()), null == n ? a.remove() : n(a), r && i ? r.merge(i).order() : i;
            },
            merge: function (t) {
                for (var e = t.selection ? t.selection() : t, n = this._groups, r = e._groups, i = n.length, a = r.length, o = Math.min(i, a), s = new Array(i), u = 0; u < o; ++u)
                    for (var l, h = n[u], c = r[u], f = h.length, d = (s[u] = new Array(f)), p = 0; p < f; ++p) (l = h[p] || c[p]) && (d[p] = l);
                for (; u < i; ++u) s[u] = n[u];
                return new ct(s, this._parents);
            },
            selection: function () {
                return this;
            },
            order: function () {
                for (var t = this._groups, e = -1, n = t.length; ++e < n; ) for (var r, i = t[e], a = i.length - 1, o = i[a]; --a >= 0; ) (r = i[a]) && (o && 4 ^ r.compareDocumentPosition(o) && o.parentNode.insertBefore(r, o), (o = r));
                return this;
            },
            sort: function (t) {
                function e(e, n) {
                    return e && n ? t(e.__data__, n.__data__) : !e - !n;
                }
                t || (t = A);
                for (var n = this._groups, r = n.length, i = new Array(r), a = 0; a < r; ++a) {
                    for (var o, s = n[a], u = s.length, l = (i[a] = new Array(u)), h = 0; h < u; ++h) (o = s[h]) && (l[h] = o);
                    l.sort(e);
                }
                return new ct(i, this._parents).order();
            },
            call: function () {
                var t = arguments[0];
                return (arguments[0] = this), t.apply(null, arguments), this;
            },
            nodes: function () {
                return Array.from(this);
            },
            node: function () {
                for (var t = this._groups, e = 0, n = t.length; e < n; ++e)
                    for (var r = t[e], i = 0, a = r.length; i < a; ++i) {
                        var o = r[i];
                        if (o) return o;
                    }
                return null;
            },
            size: function () {
                let t = 0;
                for (const e of this) ++t;
                return t;
            },
            empty: function () {
                return !this.node();
            },
            each: function (t) {
                for (var e = this._groups, n = 0, r = e.length; n < r; ++n) for (var i, a = e[n], o = 0, s = a.length; o < s; ++o) (i = a[o]) && t.call(i, i.__data__, o, a);
                return this;
            },
            attr: function (t, e) {
                var n = r(t);
                if (arguments.length < 2) {
                    var i = this.node();
                    return n.local ? i.getAttributeNS(n.space, n.local) : i.getAttribute(n);
                }
                return this.each((null == e ? (n.local ? k : N) : "function" == typeof e ? (n.local ? P : $) : n.local ? E : S)(n, e));
            },
            style: function (t, e, n) {
                return arguments.length > 1 ? this.each((null == e ? z : "function" == typeof e ? R : C)(t, e, null == n ? "" : n)) : D(this.node(), t);
            },
            property: function (t, e) {
                return arguments.length > 1 ? this.each((null == e ? I : "function" == typeof e ? O : L)(t, e)) : this.node()[t];
            },
            classed: function (t, e) {
                var n = B(t + "");
                if (arguments.length < 2) {
                    for (var r = q(this.node()), i = -1, a = n.length; ++i < a; ) if (!r.contains(n[i])) return !1;
                    return !0;
                }
                return this.each(("function" == typeof e ? V : e ? G : Y)(n, e));
            },
            text: function (t) {
                return arguments.length ? this.each(null == t ? F : ("function" == typeof t ? W : U)(t)) : this.node().textContent;
            },
            html: function (t) {
                return arguments.length ? this.each(null == t ? K : ("function" == typeof t ? Z : Q)(t)) : this.node().innerHTML;
            },
            raise: function () {
                return this.each(J);
            },
            lower: function () {
                return this.each(tt);
            },
            append: function (t) {
                var e = "function" == typeof t ? t : o(t);
                return this.select(function () {
                    return this.appendChild(e.apply(this, arguments));
                });
            },
            insert: function (t, e) {
                var n = "function" == typeof t ? t : o(t),
                    r = null == e ? et : "function" == typeof e ? e : u(e);
                return this.select(function () {
                    return this.insertBefore(n.apply(this, arguments), r.apply(this, arguments) || null);
                });
            },
            remove: function () {
                return this.each(nt);
            },
            clone: function (t) {
                return this.select(t ? it : rt);
            },
            datum: function (t) {
                return arguments.length ? this.property("__data__", t) : this.node().__data__;
            },
            on: function (t, e, n) {
                var r,
                    i,
                    a = (function (t) {
                        return t
                            .trim()
                            .split(/^|\s+/)
                            .map(function (t) {
                                var e = "",
                                    n = t.indexOf(".");
                                return n >= 0 && ((e = t.slice(n + 1)), (t = t.slice(0, n))), { type: t, name: e };
                            });
                    })(t + ""),
                    o = a.length;
                if (!(arguments.length < 2)) {
                    for (s = e ? ot : at, r = 0; r < o; ++r) this.each(s(a[r], e, n));
                    return this;
                }
                var s = this.node().__on;
                if (s) for (var u, l = 0, h = s.length; l < h; ++l) for (r = 0, u = s[l]; r < o; ++r) if ((i = a[r]).type === u.type && i.name === u.name) return u.value;
            },
            dispatch: function (t, e) {
                return this.each(("function" == typeof e ? lt : ut)(t, e));
            },
            [Symbol.iterator]: function* () {
                for (var t = this._groups, e = 0, n = t.length; e < n; ++e) for (var r, i = t[e], a = 0, o = i.length; a < o; ++a) (r = i[a]) && (yield r);
            },
        };
        var gt = { value: () => {} };
        function mt() {
            for (var t, e = 0, n = arguments.length, r = {}; e < n; ++e) {
                if (!(t = arguments[e] + "") || t in r || /[\s.]/.test(t)) throw new Error("illegal type: " + t);
                r[t] = [];
            }
            return new vt(r);
        }
        function vt(t) {
            this._ = t;
        }
        function _t(t, e) {
            for (var n, r = 0, i = t.length; r < i; ++r) if ((n = t[r]).name === e) return n.value;
        }
        function yt(t, e, n) {
            for (var r = 0, i = t.length; r < i; ++r)
                if (t[r].name === e) {
                    (t[r] = gt), (t = t.slice(0, r).concat(t.slice(r + 1)));
                    break;
                }
            return null != n && t.push({ name: e, value: n }), t;
        }
        vt.prototype = mt.prototype = {
            constructor: vt,
            on: function (t, e) {
                var n,
                    r,
                    i = this._,
                    a =
                        ((r = i),
                        (t + "")
                            .trim()
                            .split(/^|\s+/)
                            .map(function (t) {
                                var e = "",
                                    n = t.indexOf(".");
                                if ((n >= 0 && ((e = t.slice(n + 1)), (t = t.slice(0, n))), t && !r.hasOwnProperty(t))) throw new Error("unknown type: " + t);
                                return { type: t, name: e };
                            })),
                    o = -1,
                    s = a.length;
                if (!(arguments.length < 2)) {
                    if (null != e && "function" != typeof e) throw new Error("invalid callback: " + e);
                    for (; ++o < s; )
                        if ((n = (t = a[o]).type)) i[n] = yt(i[n], t.name, e);
                        else if (null == e) for (n in i) i[n] = yt(i[n], t.name, null);
                    return this;
                }
                for (; ++o < s; ) if ((n = (t = a[o]).type) && (n = _t(i[n], t.name))) return n;
            },
            copy: function () {
                var t = {},
                    e = this._;
                for (var n in e) t[n] = e[n].slice();
                return new vt(t);
            },
            call: function (t, e) {
                if ((n = arguments.length - 2) > 0) for (var n, r, i = new Array(n), a = 0; a < n; ++a) i[a] = arguments[a + 2];
                if (!this._.hasOwnProperty(t)) throw new Error("unknown type: " + t);
                for (a = 0, n = (r = this._[t]).length; a < n; ++a) r[a].value.apply(e, i);
            },
            apply: function (t, e, n) {
                if (!this._.hasOwnProperty(t)) throw new Error("unknown type: " + t);
                for (var r = this._[t], i = 0, a = r.length; i < a; ++i) r[i].value.apply(e, n);
            },
        };
        var wt,
            xt,
            bt = 0,
            Mt = 0,
            At = 0,
            Nt = 1e3,
            kt = 0,
            St = 0,
            Et = 0,
            $t = "object" == typeof performance && performance.now ? performance : Date,
            Pt =
                "object" == typeof window && window.requestAnimationFrame
                    ? window.requestAnimationFrame.bind(window)
                    : function (t) {
                          setTimeout(t, 17);
                      };
        function Tt() {
            return St || (Pt(zt), (St = $t.now() + Et));
        }
        function zt() {
            St = 0;
        }
        function Ct() {
            this._call = this._time = this._next = null;
        }
        function Rt(t, e, n) {
            var r = new Ct();
            return r.restart(t, e, n), r;
        }
        function Dt() {
            (St = (kt = $t.now()) + Et), (bt = Mt = 0);
            try {
                !(function () {
                    Tt(), ++bt;
                    for (var t, e = wt; e; ) (t = St - e._time) >= 0 && e._call.call(void 0, t), (e = e._next);
                    --bt;
                })();
            } finally {
                (bt = 0),
                    (function () {
                        for (var t, e, n = wt, r = 1 / 0; n; ) n._call ? (r > n._time && (r = n._time), (t = n), (n = n._next)) : ((e = n._next), (n._next = null), (n = t ? (t._next = e) : (wt = e)));
                        (xt = t), Lt(r);
                    })(),
                    (St = 0);
            }
        }
        function It() {
            var t = $t.now(),
                e = t - kt;
            e > Nt && ((Et -= e), (kt = t));
        }
        function Lt(t) {
            bt || (Mt && (Mt = clearTimeout(Mt)), t - St > 24 ? (t < 1 / 0 && (Mt = setTimeout(Dt, t - $t.now() - Et)), At && (At = clearInterval(At))) : (At || ((kt = $t.now()), (At = setInterval(It, Nt))), (bt = 1), Pt(Dt)));
        }
        function Ot(t, e, n) {
            var r = new Ct();
            return (
                (e = null == e ? 0 : +e),
                r.restart(
                    (n) => {
                        r.stop(), t(n + e);
                    },
                    e,
                    n
                ),
                r
            );
        }
        Ct.prototype = Rt.prototype = {
            constructor: Ct,
            restart: function (t, e, n) {
                if ("function" != typeof t) throw new TypeError("callback is not a function");
                (n = (null == n ? Tt() : +n) + (null == e ? 0 : +e)), this._next || xt === this || (xt ? (xt._next = this) : (wt = this), (xt = this)), (this._call = t), (this._time = n), Lt();
            },
            stop: function () {
                this._call && ((this._call = null), (this._time = 1 / 0), Lt());
            },
        };
        var Bt = mt("start", "end", "cancel", "interrupt"),
            qt = [],
            Xt = 0,
            jt = 1,
            Ht = 2,
            Gt = 3,
            Yt = 4,
            Vt = 5,
            Ft = 6;
        function Ut(t, e, n, r, i, a) {
            var o = t.__transition;
            if (o) {
                if (n in o) return;
            } else t.__transition = {};
            !(function (t, e, n) {
                var r,
                    i = t.__transition;
                function a(t) {
                    (n.state = jt), n.timer.restart(o, n.delay, n.time), n.delay <= t && o(t - n.delay);
                }
                function o(a) {
                    var l, h, c, f;
                    if (n.state !== jt) return u();
                    for (l in i)
                        if ((f = i[l]).name === n.name) {
                            if (f.state === Gt) return Ot(o);
                            f.state === Yt
                                ? ((f.state = Ft), f.timer.stop(), f.on.call("interrupt", t, t.__data__, f.index, f.group), delete i[l])
                                : +l < e && ((f.state = Ft), f.timer.stop(), f.on.call("cancel", t, t.__data__, f.index, f.group), delete i[l]);
                        }
                    if (
                        (Ot(function () {
                            n.state === Gt && ((n.state = Yt), n.timer.restart(s, n.delay, n.time), s(a));
                        }),
                        (n.state = Ht),
                        n.on.call("start", t, t.__data__, n.index, n.group),
                        n.state === Ht)
                    ) {
                        for (n.state = Gt, r = new Array((c = n.tween.length)), l = 0, h = -1; l < c; ++l) (f = n.tween[l].value.call(t, t.__data__, n.index, n.group)) && (r[++h] = f);
                        r.length = h + 1;
                    }
                }
                function s(e) {
                    for (var i = e < n.duration ? n.ease.call(null, e / n.duration) : (n.timer.restart(u), (n.state = Vt), 1), a = -1, o = r.length; ++a < o; ) r[a].call(t, i);
                    n.state === Vt && (n.on.call("end", t, t.__data__, n.index, n.group), u());
                }
                function u() {
                    for (var r in ((n.state = Ft), n.timer.stop(), delete i[e], i)) return;
                    delete t.__transition;
                }
                (i[e] = n), (n.timer = Rt(a, 0, n.time));
            })(t, n, { name: e, index: r, group: i, on: Bt, tween: qt, time: a.time, delay: a.delay, duration: a.duration, ease: a.ease, timer: null, state: Xt });
        }
        function Wt(t, e) {
            var n = Qt(t, e);
            if (n.state > Xt) throw new Error("too late; already scheduled");
            return n;
        }
        function Kt(t, e) {
            var n = Qt(t, e);
            if (n.state > Gt) throw new Error("too late; already running");
            return n;
        }
        function Qt(t, e) {
            var n = t.__transition;
            if (!n || !(n = n[e])) throw new Error("transition not found");
            return n;
        }
        function Zt(t, e) {
            var n,
                r,
                i,
                a = t.__transition,
                o = !0;
            if (a) {
                for (i in ((e = null == e ? null : e + ""), a))
                    (n = a[i]).name === e ? ((r = n.state > Ht && n.state < Vt), (n.state = Ft), n.timer.stop(), n.on.call(r ? "interrupt" : "cancel", t, t.__data__, n.index, n.group), delete a[i]) : (o = !1);
                o && delete t.__transition;
            }
        }
        function Jt(t, e, n) {
            (t.prototype = e.prototype = n), (n.constructor = t);
        }
        function te(t, e) {
            var n = Object.create(t.prototype);
            for (var r in e) n[r] = e[r];
            return n;
        }
        function ee() {}
        var ne = 0.7,
            re = 1 / ne,
            ie = "\\s*([+-]?\\d+)\\s*",
            ae = "\\s*([+-]?(?:\\d*\\.)?\\d+(?:[eE][+-]?\\d+)?)\\s*",
            oe = "\\s*([+-]?(?:\\d*\\.)?\\d+(?:[eE][+-]?\\d+)?)%\\s*",
            se = /^#([0-9a-f]{3,8})$/,
            ue = new RegExp(`^rgb\\(${ie},${ie},${ie}\\)$`),
            le = new RegExp(`^rgb\\(${oe},${oe},${oe}\\)$`),
            he = new RegExp(`^rgba\\(${ie},${ie},${ie},${ae}\\)$`),
            ce = new RegExp(`^rgba\\(${oe},${oe},${oe},${ae}\\)$`),
            fe = new RegExp(`^hsl\\(${ae},${oe},${oe}\\)$`),
            de = new RegExp(`^hsla\\(${ae},${oe},${oe},${ae}\\)$`),
            pe = {
                aliceblue: 15792383,
                antiquewhite: 16444375,
                aqua: 65535,
                aquamarine: 8388564,
                azure: 15794175,
                beige: 16119260,
                bisque: 16770244,
                black: 0,
                blanchedalmond: 16772045,
                blue: 255,
                blueviolet: 9055202,
                brown: 10824234,
                burlywood: 14596231,
                cadetblue: 6266528,
                chartreuse: 8388352,
                chocolate: 13789470,
                coral: 16744272,
                cornflowerblue: 6591981,
                cornsilk: 16775388,
                crimson: 14423100,
                cyan: 65535,
                darkblue: 139,
                darkcyan: 35723,
                darkgoldenrod: 12092939,
                darkgray: 11119017,
                darkgreen: 25600,
                darkgrey: 11119017,
                darkkhaki: 12433259,
                darkmagenta: 9109643,
                darkolivegreen: 5597999,
                darkorange: 16747520,
                darkorchid: 10040012,
                darkred: 9109504,
                darksalmon: 15308410,
                darkseagreen: 9419919,
                darkslateblue: 4734347,
                darkslategray: 3100495,
                darkslategrey: 3100495,
                darkturquoise: 52945,
                darkviolet: 9699539,
                deeppink: 16716947,
                deepskyblue: 49151,
                dimgray: 6908265,
                dimgrey: 6908265,
                dodgerblue: 2003199,
                firebrick: 11674146,
                floralwhite: 16775920,
                forestgreen: 2263842,
                fuchsia: 16711935,
                gainsboro: 14474460,
                ghostwhite: 16316671,
                gold: 16766720,
                goldenrod: 14329120,
                gray: 8421504,
                green: 32768,
                greenyellow: 11403055,
                grey: 8421504,
                honeydew: 15794160,
                hotpink: 16738740,
                indianred: 13458524,
                indigo: 4915330,
                ivory: 16777200,
                khaki: 15787660,
                lavender: 15132410,
                lavenderblush: 16773365,
                lawngreen: 8190976,
                lemonchiffon: 16775885,
                lightblue: 11393254,
                lightcoral: 15761536,
                lightcyan: 14745599,
                lightgoldenrodyellow: 16448210,
                lightgray: 13882323,
                lightgreen: 9498256,
                lightgrey: 13882323,
                lightpink: 16758465,
                lightsalmon: 16752762,
                lightseagreen: 2142890,
                lightskyblue: 8900346,
                lightslategray: 7833753,
                lightslategrey: 7833753,
                lightsteelblue: 11584734,
                lightyellow: 16777184,
                lime: 65280,
                limegreen: 3329330,
                linen: 16445670,
                magenta: 16711935,
                maroon: 8388608,
                mediumaquamarine: 6737322,
                mediumblue: 205,
                mediumorchid: 12211667,
                mediumpurple: 9662683,
                mediumseagreen: 3978097,
                mediumslateblue: 8087790,
                mediumspringgreen: 64154,
                mediumturquoise: 4772300,
                mediumvioletred: 13047173,
                midnightblue: 1644912,
                mintcream: 16121850,
                mistyrose: 16770273,
                moccasin: 16770229,
                navajowhite: 16768685,
                navy: 128,
                oldlace: 16643558,
                olive: 8421376,
                olivedrab: 7048739,
                orange: 16753920,
                orangered: 16729344,
                orchid: 14315734,
                palegoldenrod: 15657130,
                palegreen: 10025880,
                paleturquoise: 11529966,
                palevioletred: 14381203,
                papayawhip: 16773077,
                peachpuff: 16767673,
                peru: 13468991,
                pink: 16761035,
                plum: 14524637,
                powderblue: 11591910,
                purple: 8388736,
                rebeccapurple: 6697881,
                red: 16711680,
                rosybrown: 12357519,
                royalblue: 4286945,
                saddlebrown: 9127187,
                salmon: 16416882,
                sandybrown: 16032864,
                seagreen: 3050327,
                seashell: 16774638,
                sienna: 10506797,
                silver: 12632256,
                skyblue: 8900331,
                slateblue: 6970061,
                slategray: 7372944,
                slategrey: 7372944,
                snow: 16775930,
                springgreen: 65407,
                steelblue: 4620980,
                tan: 13808780,
                teal: 32896,
                thistle: 14204888,
                tomato: 16737095,
                turquoise: 4251856,
                violet: 15631086,
                wheat: 16113331,
                white: 16777215,
                whitesmoke: 16119285,
                yellow: 16776960,
                yellowgreen: 10145074,
            };
        function ge() {
            return this.rgb().formatHex();
        }
        function me() {
            return this.rgb().formatRgb();
        }
        function ve(t) {
            var e, n;
            return (
                (t = (t + "").trim().toLowerCase()),
                (e = se.exec(t))
                    ? ((n = e[1].length),
                      (e = parseInt(e[1], 16)),
                      6 === n
                          ? _e(e)
                          : 3 === n
                          ? new xe(((e >> 8) & 15) | ((e >> 4) & 240), ((e >> 4) & 15) | (240 & e), ((15 & e) << 4) | (15 & e), 1)
                          : 8 === n
                          ? ye((e >> 24) & 255, (e >> 16) & 255, (e >> 8) & 255, (255 & e) / 255)
                          : 4 === n
                          ? ye(((e >> 12) & 15) | ((e >> 8) & 240), ((e >> 8) & 15) | ((e >> 4) & 240), ((e >> 4) & 15) | (240 & e), (((15 & e) << 4) | (15 & e)) / 255)
                          : null)
                    : (e = ue.exec(t))
                    ? new xe(e[1], e[2], e[3], 1)
                    : (e = le.exec(t))
                    ? new xe((255 * e[1]) / 100, (255 * e[2]) / 100, (255 * e[3]) / 100, 1)
                    : (e = he.exec(t))
                    ? ye(e[1], e[2], e[3], e[4])
                    : (e = ce.exec(t))
                    ? ye((255 * e[1]) / 100, (255 * e[2]) / 100, (255 * e[3]) / 100, e[4])
                    : (e = fe.exec(t))
                    ? Se(e[1], e[2] / 100, e[3] / 100, 1)
                    : (e = de.exec(t))
                    ? Se(e[1], e[2] / 100, e[3] / 100, e[4])
                    : pe.hasOwnProperty(t)
                    ? _e(pe[t])
                    : "transparent" === t
                    ? new xe(NaN, NaN, NaN, 0)
                    : null
            );
        }
        function _e(t) {
            return new xe((t >> 16) & 255, (t >> 8) & 255, 255 & t, 1);
        }
        function ye(t, e, n, r) {
            return r <= 0 && (t = e = n = NaN), new xe(t, e, n, r);
        }
        function we(t, e, n, r) {
            return 1 === arguments.length ? ((i = t) instanceof ee || (i = ve(i)), i ? new xe((i = i.rgb()).r, i.g, i.b, i.opacity) : new xe()) : new xe(t, e, n, null == r ? 1 : r);
            var i;
        }
        function xe(t, e, n, r) {
            (this.r = +t), (this.g = +e), (this.b = +n), (this.opacity = +r);
        }
        function be() {
            return `#${ke(this.r)}${ke(this.g)}${ke(this.b)}`;
        }
        function Me() {
            const t = Ae(this.opacity);
            return `${1 === t ? "rgb(" : "rgba("}${Ne(this.r)}, ${Ne(this.g)}, ${Ne(this.b)}${1 === t ? ")" : `, ${t})`}`;
        }
        function Ae(t) {
            return isNaN(t) ? 1 : Math.max(0, Math.min(1, t));
        }
        function Ne(t) {
            return Math.max(0, Math.min(255, Math.round(t) || 0));
        }
        function ke(t) {
            return ((t = Ne(t)) < 16 ? "0" : "") + t.toString(16);
        }
        function Se(t, e, n, r) {
            return r <= 0 ? (t = e = n = NaN) : n <= 0 || n >= 1 ? (t = e = NaN) : e <= 0 && (t = NaN), new $e(t, e, n, r);
        }
        function Ee(t) {
            if (t instanceof $e) return new $e(t.h, t.s, t.l, t.opacity);
            if ((t instanceof ee || (t = ve(t)), !t)) return new $e();
            if (t instanceof $e) return t;
            var e = (t = t.rgb()).r / 255,
                n = t.g / 255,
                r = t.b / 255,
                i = Math.min(e, n, r),
                a = Math.max(e, n, r),
                o = NaN,
                s = a - i,
                u = (a + i) / 2;
            return s ? ((o = e === a ? (n - r) / s + 6 * (n < r) : n === a ? (r - e) / s + 2 : (e - n) / s + 4), (s /= u < 0.5 ? a + i : 2 - a - i), (o *= 60)) : (s = u > 0 && u < 1 ? 0 : o), new $e(o, s, u, t.opacity);
        }
        function $e(t, e, n, r) {
            (this.h = +t), (this.s = +e), (this.l = +n), (this.opacity = +r);
        }
        function Pe(t) {
            return (t = (t || 0) % 360) < 0 ? t + 360 : t;
        }
        function Te(t) {
            return Math.max(0, Math.min(1, t || 0));
        }
        function ze(t, e, n) {
            return 255 * (t < 60 ? e + ((n - e) * t) / 60 : t < 180 ? n : t < 240 ? e + ((n - e) * (240 - t)) / 60 : e);
        }
        Jt(ee, ve, {
            copy(t) {
                return Object.assign(new this.constructor(), this, t);
            },
            displayable() {
                return this.rgb().displayable();
            },
            hex: ge,
            formatHex: ge,
            formatHex8: function () {
                return this.rgb().formatHex8();
            },
            formatHsl: function () {
                return Ee(this).formatHsl();
            },
            formatRgb: me,
            toString: me,
        }),
            Jt(
                xe,
                we,
                te(ee, {
                    brighter(t) {
                        return (t = null == t ? re : Math.pow(re, t)), new xe(this.r * t, this.g * t, this.b * t, this.opacity);
                    },
                    darker(t) {
                        return (t = null == t ? ne : Math.pow(ne, t)), new xe(this.r * t, this.g * t, this.b * t, this.opacity);
                    },
                    rgb() {
                        return this;
                    },
                    clamp() {
                        return new xe(Ne(this.r), Ne(this.g), Ne(this.b), Ae(this.opacity));
                    },
                    displayable() {
                        return -0.5 <= this.r && this.r < 255.5 && -0.5 <= this.g && this.g < 255.5 && -0.5 <= this.b && this.b < 255.5 && 0 <= this.opacity && this.opacity <= 1;
                    },
                    hex: be,
                    formatHex: be,
                    formatHex8: function () {
                        return `#${ke(this.r)}${ke(this.g)}${ke(this.b)}${ke(255 * (isNaN(this.opacity) ? 1 : this.opacity))}`;
                    },
                    formatRgb: Me,
                    toString: Me,
                })
            ),
            Jt(
                $e,
                function (t, e, n, r) {
                    return 1 === arguments.length ? Ee(t) : new $e(t, e, n, null == r ? 1 : r);
                },
                te(ee, {
                    brighter(t) {
                        return (t = null == t ? re : Math.pow(re, t)), new $e(this.h, this.s, this.l * t, this.opacity);
                    },
                    darker(t) {
                        return (t = null == t ? ne : Math.pow(ne, t)), new $e(this.h, this.s, this.l * t, this.opacity);
                    },
                    rgb() {
                        var t = (this.h % 360) + 360 * (this.h < 0),
                            e = isNaN(t) || isNaN(this.s) ? 0 : this.s,
                            n = this.l,
                            r = n + (n < 0.5 ? n : 1 - n) * e,
                            i = 2 * n - r;
                        return new xe(ze(t >= 240 ? t - 240 : t + 120, i, r), ze(t, i, r), ze(t < 120 ? t + 240 : t - 120, i, r), this.opacity);
                    },
                    clamp() {
                        return new $e(Pe(this.h), Te(this.s), Te(this.l), Ae(this.opacity));
                    },
                    displayable() {
                        return ((0 <= this.s && this.s <= 1) || isNaN(this.s)) && 0 <= this.l && this.l <= 1 && 0 <= this.opacity && this.opacity <= 1;
                    },
                    formatHsl() {
                        const t = Ae(this.opacity);
                        return `${1 === t ? "hsl(" : "hsla("}${Pe(this.h)}, ${100 * Te(this.s)}%, ${100 * Te(this.l)}%${1 === t ? ")" : `, ${t})`}`;
                    },
                })
            );
        var Ce = (t) => () => t;
        function Re(t) {
            return 1 == (t = +t)
                ? De
                : function (e, n) {
                      return n - e
                          ? (function (t, e, n) {
                                return (
                                    (t = Math.pow(t, n)),
                                    (e = Math.pow(e, n) - t),
                                    (n = 1 / n),
                                    function (r) {
                                        return Math.pow(t + r * e, n);
                                    }
                                );
                            })(e, n, t)
                          : Ce(isNaN(e) ? n : e);
                  };
        }
        function De(t, e) {
            var n = e - t;
            return n
                ? (function (t, e) {
                      return function (n) {
                          return t + n * e;
                      };
                  })(t, n)
                : Ce(isNaN(t) ? e : t);
        }
        var Ie = (function t(e) {
            var n = Re(e);
            function r(t, e) {
                var r = n((t = we(t)).r, (e = we(e)).r),
                    i = n(t.g, e.g),
                    a = n(t.b, e.b),
                    o = De(t.opacity, e.opacity);
                return function (e) {
                    return (t.r = r(e)), (t.g = i(e)), (t.b = a(e)), (t.opacity = o(e)), t + "";
                };
            }
            return (r.gamma = t), r;
        })(1);
        function Le(t, e) {
            e || (e = []);
            var n,
                r = t ? Math.min(e.length, t.length) : 0,
                i = e.slice();
            return function (a) {
                for (n = 0; n < r; ++n) i[n] = t[n] * (1 - a) + e[n] * a;
                return i;
            };
        }
        function Oe(t, e) {
            var n,
                r = e ? e.length : 0,
                i = t ? Math.min(r, t.length) : 0,
                a = new Array(i),
                o = new Array(r);
            for (n = 0; n < i; ++n) a[n] = Ye(t[n], e[n]);
            for (; n < r; ++n) o[n] = e[n];
            return function (t) {
                for (n = 0; n < i; ++n) o[n] = a[n](t);
                return o;
            };
        }
        function Be(t, e) {
            var n = new Date();
            return (
                (t = +t),
                (e = +e),
                function (r) {
                    return n.setTime(t * (1 - r) + e * r), n;
                }
            );
        }
        function qe(t, e) {
            return (
                (t = +t),
                (e = +e),
                function (n) {
                    return t * (1 - n) + e * n;
                }
            );
        }
        function Xe(t, e) {
            var n,
                r = {},
                i = {};
            for (n in ((null !== t && "object" == typeof t) || (t = {}), (null !== e && "object" == typeof e) || (e = {}), e)) n in t ? (r[n] = Ye(t[n], e[n])) : (i[n] = e[n]);
            return function (t) {
                for (n in r) i[n] = r[n](t);
                return i;
            };
        }
        var je = /[-+]?(?:\d+\.?\d*|\.?\d+)(?:[eE][-+]?\d+)?/g,
            He = new RegExp(je.source, "g");
        function Ge(t, e) {
            var n,
                r,
                i,
                a = (je.lastIndex = He.lastIndex = 0),
                o = -1,
                s = [],
                u = [];
            for (t += "", e += ""; (n = je.exec(t)) && (r = He.exec(e)); )
                (i = r.index) > a && ((i = e.slice(a, i)), s[o] ? (s[o] += i) : (s[++o] = i)), (n = n[0]) === (r = r[0]) ? (s[o] ? (s[o] += r) : (s[++o] = r)) : ((s[++o] = null), u.push({ i: o, x: qe(n, r) })), (a = He.lastIndex);
            return (
                a < e.length && ((i = e.slice(a)), s[o] ? (s[o] += i) : (s[++o] = i)),
                s.length < 2
                    ? u[0]
                        ? (function (t) {
                              return function (e) {
                                  return t(e) + "";
                              };
                          })(u[0].x)
                        : (function (t) {
                              return function () {
                                  return t;
                              };
                          })(e)
                    : ((e = u.length),
                      function (t) {
                          for (var n, r = 0; r < e; ++r) s[(n = u[r]).i] = n.x(t);
                          return s.join("");
                      })
            );
        }
        function Ye(t, e) {
            var n,
                r,
                i = typeof e;
            return null == e || "boolean" === i
                ? Ce(e)
                : ("number" === i
                      ? qe
                      : "string" === i
                      ? (n = ve(e))
                          ? ((e = n), Ie)
                          : Ge
                      : e instanceof ve
                      ? Ie
                      : e instanceof Date
                      ? Be
                      : ((r = e), !ArrayBuffer.isView(r) || r instanceof DataView ? (Array.isArray(e) ? Oe : ("function" != typeof e.valueOf && "function" != typeof e.toString) || isNaN(e) ? Xe : qe) : Le))(t, e);
        }
        function Ve(t, e) {
            return (
                (t = +t),
                (e = +e),
                function (n) {
                    return Math.round(t * (1 - n) + e * n);
                }
            );
        }
        var Fe,
            Ue = 180 / Math.PI,
            We = { translateX: 0, translateY: 0, rotate: 0, skewX: 0, scaleX: 1, scaleY: 1 };
        function Ke(t, e, n, r, i, a) {
            var o, s, u;
            return (
                (o = Math.sqrt(t * t + e * e)) && ((t /= o), (e /= o)),
                (u = t * n + e * r) && ((n -= t * u), (r -= e * u)),
                (s = Math.sqrt(n * n + r * r)) && ((n /= s), (r /= s), (u /= s)),
                t * r < e * n && ((t = -t), (e = -e), (u = -u), (o = -o)),
                { translateX: i, translateY: a, rotate: Math.atan2(e, t) * Ue, skewX: Math.atan(u) * Ue, scaleX: o, scaleY: s }
            );
        }
        function Qe(t, e, n, r) {
            function i(t) {
                return t.length ? t.pop() + " " : "";
            }
            return function (a, o) {
                var s = [],
                    u = [];
                return (
                    (a = t(a)),
                    (o = t(o)),
                    (function (t, r, i, a, o, s) {
                        if (t !== i || r !== a) {
                            var u = o.push("translate(", null, e, null, n);
                            s.push({ i: u - 4, x: qe(t, i) }, { i: u - 2, x: qe(r, a) });
                        } else (i || a) && o.push("translate(" + i + e + a + n);
                    })(a.translateX, a.translateY, o.translateX, o.translateY, s, u),
                    (function (t, e, n, a) {
                        t !== e ? (t - e > 180 ? (e += 360) : e - t > 180 && (t += 360), a.push({ i: n.push(i(n) + "rotate(", null, r) - 2, x: qe(t, e) })) : e && n.push(i(n) + "rotate(" + e + r);
                    })(a.rotate, o.rotate, s, u),
                    (function (t, e, n, a) {
                        t !== e ? a.push({ i: n.push(i(n) + "skewX(", null, r) - 2, x: qe(t, e) }) : e && n.push(i(n) + "skewX(" + e + r);
                    })(a.skewX, o.skewX, s, u),
                    (function (t, e, n, r, a, o) {
                        if (t !== n || e !== r) {
                            var s = a.push(i(a) + "scale(", null, ",", null, ")");
                            o.push({ i: s - 4, x: qe(t, n) }, { i: s - 2, x: qe(e, r) });
                        } else (1 === n && 1 === r) || a.push(i(a) + "scale(" + n + "," + r + ")");
                    })(a.scaleX, a.scaleY, o.scaleX, o.scaleY, s, u),
                    (a = o = null),
                    function (t) {
                        for (var e, n = -1, r = u.length; ++n < r; ) s[(e = u[n]).i] = e.x(t);
                        return s.join("");
                    }
                );
            };
        }
        var Ze = Qe(
                function (t) {
                    const e = new ("function" == typeof DOMMatrix ? DOMMatrix : WebKitCSSMatrix)(t + "");
                    return e.isIdentity ? We : Ke(e.a, e.b, e.c, e.d, e.e, e.f);
                },
                "px, ",
                "px)",
                "deg)"
            ),
            Je = Qe(
                function (t) {
                    return null == t
                        ? We
                        : (Fe || (Fe = document.createElementNS("http://www.w3.org/2000/svg", "g")), Fe.setAttribute("transform", t), (t = Fe.transform.baseVal.consolidate()) ? Ke((t = t.matrix).a, t.b, t.c, t.d, t.e, t.f) : We);
                },
                ", ",
                ")",
                ")"
            );
        function tn(t) {
            return ((t = Math.exp(t)) + 1 / t) / 2;
        }
        var en = (function t(e, n, r) {
            function i(t, i) {
                var a,
                    o,
                    s = t[0],
                    u = t[1],
                    l = t[2],
                    h = i[0],
                    c = i[1],
                    f = i[2],
                    d = h - s,
                    p = c - u,
                    g = d * d + p * p;
                if (g < 1e-12)
                    (o = Math.log(f / l) / e),
                        (a = function (t) {
                            return [s + t * d, u + t * p, l * Math.exp(e * t * o)];
                        });
                else {
                    var m = Math.sqrt(g),
                        v = (f * f - l * l + r * g) / (2 * l * n * m),
                        _ = (f * f - l * l - r * g) / (2 * f * n * m),
                        y = Math.log(Math.sqrt(v * v + 1) - v),
                        w = Math.log(Math.sqrt(_ * _ + 1) - _);
                    (o = (w - y) / e),
                        (a = function (t) {
                            var r,
                                i = t * o,
                                a = tn(y),
                                h =
                                    (l / (n * m)) *
                                    (a * ((r = e * i + y), ((r = Math.exp(2 * r)) - 1) / (r + 1)) -
                                        (function (t) {
                                            return ((t = Math.exp(t)) - 1 / t) / 2;
                                        })(y));
                            return [s + h * d, u + h * p, (l * a) / tn(e * i + y)];
                        });
                }
                return (a.duration = (1e3 * o * e) / Math.SQRT2), a;
            }
            return (
                (i.rho = function (e) {
                    var n = Math.max(0.001, +e),
                        r = n * n;
                    return t(n, r, r * r);
                }),
                i
            );
        })(Math.SQRT2, 2, 4);
        function nn(t, e) {
            var n, r;
            return function () {
                var i = Kt(this, t),
                    a = i.tween;
                if (a !== n)
                    for (var o = 0, s = (r = n = a).length; o < s; ++o)
                        if (r[o].name === e) {
                            (r = r.slice()).splice(o, 1);
                            break;
                        }
                i.tween = r;
            };
        }
        function rn(t, e, n) {
            var r, i;
            if ("function" != typeof n) throw new Error();
            return function () {
                var a = Kt(this, t),
                    o = a.tween;
                if (o !== r) {
                    i = (r = o).slice();
                    for (var s = { name: e, value: n }, u = 0, l = i.length; u < l; ++u)
                        if (i[u].name === e) {
                            i[u] = s;
                            break;
                        }
                    u === l && i.push(s);
                }
                a.tween = i;
            };
        }
        function an(t, e, n) {
            var r = t._id;
            return (
                t.each(function () {
                    var t = Kt(this, r);
                    (t.value || (t.value = {}))[e] = n.apply(this, arguments);
                }),
                function (t) {
                    return Qt(t, r).value[e];
                }
            );
        }
        function on(t, e) {
            var n;
            return ("number" == typeof e ? qe : e instanceof ve ? Ie : (n = ve(e)) ? ((e = n), Ie) : Ge)(t, e);
        }
        function sn(t) {
            return function () {
                this.removeAttribute(t);
            };
        }
        function un(t) {
            return function () {
                this.removeAttributeNS(t.space, t.local);
            };
        }
        function ln(t, e, n) {
            var r,
                i,
                a = n + "";
            return function () {
                var o = this.getAttribute(t);
                return o === a ? null : o === r ? i : (i = e((r = o), n));
            };
        }
        function hn(t, e, n) {
            var r,
                i,
                a = n + "";
            return function () {
                var o = this.getAttributeNS(t.space, t.local);
                return o === a ? null : o === r ? i : (i = e((r = o), n));
            };
        }
        function cn(t, e, n) {
            var r, i, a;
            return function () {
                var o,
                    s,
                    u = n(this);
                if (null != u) return (o = this.getAttribute(t)) === (s = u + "") ? null : o === r && s === i ? a : ((i = s), (a = e((r = o), u)));
                this.removeAttribute(t);
            };
        }
        function fn(t, e, n) {
            var r, i, a;
            return function () {
                var o,
                    s,
                    u = n(this);
                if (null != u) return (o = this.getAttributeNS(t.space, t.local)) === (s = u + "") ? null : o === r && s === i ? a : ((i = s), (a = e((r = o), u)));
                this.removeAttributeNS(t.space, t.local);
            };
        }
        function dn(t, e) {
            var n, r;
            function i() {
                var i = e.apply(this, arguments);
                return (
                    i !== r &&
                        (n =
                            (r = i) &&
                            (function (t, e) {
                                return function (n) {
                                    this.setAttributeNS(t.space, t.local, e.call(this, n));
                                };
                            })(t, i)),
                    n
                );
            }
            return (i._value = e), i;
        }
        function pn(t, e) {
            var n, r;
            function i() {
                var i = e.apply(this, arguments);
                return (
                    i !== r &&
                        (n =
                            (r = i) &&
                            (function (t, e) {
                                return function (n) {
                                    this.setAttribute(t, e.call(this, n));
                                };
                            })(t, i)),
                    n
                );
            }
            return (i._value = e), i;
        }
        function gn(t, e) {
            return function () {
                Wt(this, t).delay = +e.apply(this, arguments);
            };
        }
        function mn(t, e) {
            return (
                (e = +e),
                function () {
                    Wt(this, t).delay = e;
                }
            );
        }
        function vn(t, e) {
            return function () {
                Kt(this, t).duration = +e.apply(this, arguments);
            };
        }
        function _n(t, e) {
            return (
                (e = +e),
                function () {
                    Kt(this, t).duration = e;
                }
            );
        }
        var yn = ft.prototype.constructor;
        function wn(t) {
            return function () {
                this.style.removeProperty(t);
            };
        }
        var xn = 0;
        function bn(t, e, n, r) {
            (this._groups = t), (this._parents = e), (this._name = n), (this._id = r);
        }
        function Mn(t) {
            return ft().transition(t);
        }
        function An() {
            return ++xn;
        }
        var Nn = ft.prototype;
        bn.prototype = Mn.prototype = {
            constructor: bn,
            select: function (t) {
                var e = this._name,
                    n = this._id;
                "function" != typeof t && (t = u(t));
                for (var r = this._groups, i = r.length, a = new Array(i), o = 0; o < i; ++o)
                    for (var s, l, h = r[o], c = h.length, f = (a[o] = new Array(c)), d = 0; d < c; ++d)
                        (s = h[d]) && (l = t.call(s, s.__data__, d, h)) && ("__data__" in s && (l.__data__ = s.__data__), (f[d] = l), Ut(f[d], e, n, d, f, Qt(s, n)));
                return new bn(a, this._parents, e, n);
            },
            selectAll: function (t) {
                var e = this._name,
                    n = this._id;
                "function" != typeof t && (t = h(t));
                for (var r = this._groups, i = r.length, a = [], o = [], s = 0; s < i; ++s)
                    for (var u, l = r[s], c = l.length, f = 0; f < c; ++f)
                        if ((u = l[f])) {
                            for (var d, p = t.call(u, u.__data__, f, l), g = Qt(u, n), m = 0, v = p.length; m < v; ++m) (d = p[m]) && Ut(d, e, n, m, p, g);
                            a.push(p), o.push(u);
                        }
                return new bn(a, o, e, n);
            },
            selectChild: Nn.selectChild,
            selectChildren: Nn.selectChildren,
            filter: function (t) {
                "function" != typeof t && (t = f(t));
                for (var e = this._groups, n = e.length, r = new Array(n), i = 0; i < n; ++i) for (var a, o = e[i], s = o.length, u = (r[i] = []), l = 0; l < s; ++l) (a = o[l]) && t.call(a, a.__data__, l, o) && u.push(a);
                return new bn(r, this._parents, this._name, this._id);
            },
            merge: function (t) {
                if (t._id !== this._id) throw new Error();
                for (var e = this._groups, n = t._groups, r = e.length, i = n.length, a = Math.min(r, i), o = new Array(r), s = 0; s < a; ++s)
                    for (var u, l = e[s], h = n[s], c = l.length, f = (o[s] = new Array(c)), d = 0; d < c; ++d) (u = l[d] || h[d]) && (f[d] = u);
                for (; s < r; ++s) o[s] = e[s];
                return new bn(o, this._parents, this._name, this._id);
            },
            selection: function () {
                return new yn(this._groups, this._parents);
            },
            transition: function () {
                for (var t = this._name, e = this._id, n = An(), r = this._groups, i = r.length, a = 0; a < i; ++a)
                    for (var o, s = r[a], u = s.length, l = 0; l < u; ++l)
                        if ((o = s[l])) {
                            var h = Qt(o, e);
                            Ut(o, t, n, l, s, { time: h.time + h.delay + h.duration, delay: 0, duration: h.duration, ease: h.ease });
                        }
                return new bn(r, this._parents, t, n);
            },
            call: Nn.call,
            nodes: Nn.nodes,
            node: Nn.node,
            size: Nn.size,
            empty: Nn.empty,
            each: Nn.each,
            on: function (t, e) {
                var n = this._id;
                return arguments.length < 2
                    ? Qt(this.node(), n).on.on(t)
                    : this.each(
                          (function (t, e, n) {
                              var r,
                                  i,
                                  a = (function (t) {
                                      return (t + "")
                                          .trim()
                                          .split(/^|\s+/)
                                          .every(function (t) {
                                              var e = t.indexOf(".");
                                              return e >= 0 && (t = t.slice(0, e)), !t || "start" === t;
                                          });
                                  })(e)
                                      ? Wt
                                      : Kt;
                              return function () {
                                  var o = a(this, t),
                                      s = o.on;
                                  s !== r && (i = (r = s).copy()).on(e, n), (o.on = i);
                              };
                          })(n, t, e)
                      );
            },
            attr: function (t, e) {
                var n = r(t),
                    i = "transform" === n ? Je : on;
                return this.attrTween(t, "function" == typeof e ? (n.local ? fn : cn)(n, i, an(this, "attr." + t, e)) : null == e ? (n.local ? un : sn)(n) : (n.local ? hn : ln)(n, i, e));
            },
            attrTween: function (t, e) {
                var n = "attr." + t;
                if (arguments.length < 2) return (n = this.tween(n)) && n._value;
                if (null == e) return this.tween(n, null);
                if ("function" != typeof e) throw new Error();
                var i = r(t);
                return this.tween(n, (i.local ? dn : pn)(i, e));
            },
            style: function (t, e, n) {
                var r = "transform" == (t += "") ? Ze : on;
                return null == e
                    ? this.styleTween(
                          t,
                          (function (t, e) {
                              var n, r, i;
                              return function () {
                                  var a = D(this, t),
                                      o = (this.style.removeProperty(t), D(this, t));
                                  return a === o ? null : a === n && o === r ? i : (i = e((n = a), (r = o)));
                              };
                          })(t, r)
                      ).on("end.style." + t, wn(t))
                    : "function" == typeof e
                    ? this.styleTween(
                          t,
                          (function (t, e, n) {
                              var r, i, a;
                              return function () {
                                  var o = D(this, t),
                                      s = n(this),
                                      u = s + "";
                                  return null == s && (this.style.removeProperty(t), (u = s = D(this, t))), o === u ? null : o === r && u === i ? a : ((i = u), (a = e((r = o), s)));
                              };
                          })(t, r, an(this, "style." + t, e))
                      ).each(
                          (function (t, e) {
                              var n,
                                  r,
                                  i,
                                  a,
                                  o = "style." + e,
                                  s = "end." + o;
                              return function () {
                                  var u = Kt(this, t),
                                      l = u.on,
                                      h = null == u.value[o] ? a || (a = wn(e)) : void 0;
                                  (l === n && i === h) || (r = (n = l).copy()).on(s, (i = h)), (u.on = r);
                              };
                          })(this._id, t)
                      )
                    : this.styleTween(
                          t,
                          (function (t, e, n) {
                              var r,
                                  i,
                                  a = n + "";
                              return function () {
                                  var o = D(this, t);
                                  return o === a ? null : o === r ? i : (i = e((r = o), n));
                              };
                          })(t, r, e),
                          n
                      ).on("end.style." + t, null);
            },
            styleTween: function (t, e, n) {
                var r = "style." + (t += "");
                if (arguments.length < 2) return (r = this.tween(r)) && r._value;
                if (null == e) return this.tween(r, null);
                if ("function" != typeof e) throw new Error();
                return this.tween(
                    r,
                    (function (t, e, n) {
                        var r, i;
                        function a() {
                            var a = e.apply(this, arguments);
                            return (
                                a !== i &&
                                    (r =
                                        (i = a) &&
                                        (function (t, e, n) {
                                            return function (r) {
                                                this.style.setProperty(t, e.call(this, r), n);
                                            };
                                        })(t, a, n)),
                                r
                            );
                        }
                        return (a._value = e), a;
                    })(t, e, null == n ? "" : n)
                );
            },
            text: function (t) {
                return this.tween(
                    "text",
                    "function" == typeof t
                        ? (function (t) {
                              return function () {
                                  var e = t(this);
                                  this.textContent = null == e ? "" : e;
                              };
                          })(an(this, "text", t))
                        : (function (t) {
                              return function () {
                                  this.textContent = t;
                              };
                          })(null == t ? "" : t + "")
                );
            },
            textTween: function (t) {
                var e = "text";
                if (arguments.length < 1) return (e = this.tween(e)) && e._value;
                if (null == t) return this.tween(e, null);
                if ("function" != typeof t) throw new Error();
                return this.tween(
                    e,
                    (function (t) {
                        var e, n;
                        function r() {
                            var r = t.apply(this, arguments);
                            return (
                                r !== n &&
                                    (e =
                                        (n = r) &&
                                        (function (t) {
                                            return function (e) {
                                                this.textContent = t.call(this, e);
                                            };
                                        })(r)),
                                e
                            );
                        }
                        return (r._value = t), r;
                    })(t)
                );
            },
            remove: function () {
                return this.on(
                    "end.remove",
                    (function (t) {
                        return function () {
                            var e = this.parentNode;
                            for (var n in this.__transition) if (+n !== t) return;
                            e && e.removeChild(this);
                        };
                    })(this._id)
                );
            },
            tween: function (t, e) {
                var n = this._id;
                if (((t += ""), arguments.length < 2)) {
                    for (var r, i = Qt(this.node(), n).tween, a = 0, o = i.length; a < o; ++a) if ((r = i[a]).name === t) return r.value;
                    return null;
                }
                return this.each((null == e ? nn : rn)(n, t, e));
            },
            delay: function (t) {
                var e = this._id;
                return arguments.length ? this.each(("function" == typeof t ? gn : mn)(e, t)) : Qt(this.node(), e).delay;
            },
            duration: function (t) {
                var e = this._id;
                return arguments.length ? this.each(("function" == typeof t ? vn : _n)(e, t)) : Qt(this.node(), e).duration;
            },
            ease: function (t) {
                var e = this._id;
                return arguments.length
                    ? this.each(
                          (function (t, e) {
                              if ("function" != typeof e) throw new Error();
                              return function () {
                                  Kt(this, t).ease = e;
                              };
                          })(e, t)
                      )
                    : Qt(this.node(), e).ease;
            },
            easeVarying: function (t) {
                if ("function" != typeof t) throw new Error();
                return this.each(
                    (function (t, e) {
                        return function () {
                            var n = e.apply(this, arguments);
                            if ("function" != typeof n) throw new Error();
                            Kt(this, t).ease = n;
                        };
                    })(this._id, t)
                );
            },
            end: function () {
                var t,
                    e,
                    n = this,
                    r = n._id,
                    i = n.size();
                return new Promise(function (a, o) {
                    var s = { value: o },
                        u = {
                            value: function () {
                                0 == --i && a();
                            },
                        };
                    n.each(function () {
                        var n = Kt(this, r),
                            i = n.on;
                        i !== t && ((e = (t = i).copy())._.cancel.push(s), e._.interrupt.push(s), e._.end.push(u)), (n.on = e);
                    }),
                        0 === i && a();
                });
            },
            [Symbol.iterator]: Nn[Symbol.iterator],
        };
        var kn = {
            time: null,
            delay: 0,
            duration: 250,
            ease: function (t) {
                return ((t *= 2) <= 1 ? t * t * t : (t -= 2) * t * t + 2) / 2;
            },
        };
        function Sn(t, e) {
            for (var n; !(n = t.__transition) || !(n = n[e]); ) if (!(t = t.parentNode)) throw new Error(`transition ${e} not found`);
            return n;
        }
        function En(t, e) {
            return null == t || null == e ? NaN : t < e ? -1 : t > e ? 1 : t >= e ? 0 : NaN;
        }
        function $n(t, e) {
            return null == t || null == e ? NaN : e < t ? -1 : e > t ? 1 : e >= t ? 0 : NaN;
        }
        function Pn(t) {
            let e, n, r;
            function i(t, r, i = 0, a = t.length) {
                if (i < a) {
                    if (0 !== e(r, r)) return a;
                    do {
                        const e = (i + a) >>> 1;
                        n(t[e], r) < 0 ? (i = e + 1) : (a = e);
                    } while (i < a);
                }
                return i;
            }
            return (
                2 !== t.length ? ((e = En), (n = (e, n) => En(t(e), n)), (r = (e, n) => t(e) - n)) : ((e = t === En || t === $n ? t : Tn), (n = t), (r = t)),
                {
                    left: i,
                    center: function (t, e, n = 0, a = t.length) {
                        const o = i(t, e, n, a - 1);
                        return o > n && r(t[o - 1], e) > -r(t[o], e) ? o - 1 : o;
                    },
                    right: function (t, r, i = 0, a = t.length) {
                        if (i < a) {
                            if (0 !== e(r, r)) return a;
                            do {
                                const e = (i + a) >>> 1;
                                n(t[e], r) <= 0 ? (i = e + 1) : (a = e);
                            } while (i < a);
                        }
                        return i;
                    },
                }
            );
        }
        function Tn() {
            return 0;
        }
        (ft.prototype.interrupt = function (t) {
            return this.each(function () {
                Zt(this, t);
            });
        }),
            (ft.prototype.transition = function (t) {
                var e, n;
                t instanceof bn ? ((e = t._id), (t = t._name)) : ((e = An()), ((n = kn).time = Tt()), (t = null == t ? null : t + ""));
                for (var r = this._groups, i = r.length, a = 0; a < i; ++a) for (var o, s = r[a], u = s.length, l = 0; l < u; ++l) (o = s[l]) && Ut(o, t, e, l, s, n || Sn(o, e));
                return new bn(r, this._parents, t, e);
            });
        const zn = Pn(En).right;
        Pn(function (t) {
            return null === t ? NaN : +t;
        }).center;
        var Cn = zn;
        const Rn = Math.sqrt(50),
            Dn = Math.sqrt(10),
            In = Math.sqrt(2);
        function Ln(t, e, n) {
            const r = (e - t) / Math.max(0, n),
                i = Math.floor(Math.log10(r)),
                a = r / Math.pow(10, i),
                o = a >= Rn ? 10 : a >= Dn ? 5 : a >= In ? 2 : 1;
            let s, u, l;
            return (
                i < 0
                    ? ((l = Math.pow(10, -i) / o), (s = Math.round(t * l)), (u = Math.round(e * l)), s / l < t && ++s, u / l > e && --u, (l = -l))
                    : ((l = Math.pow(10, i) * o), (s = Math.round(t / l)), (u = Math.round(e / l)), s * l < t && ++s, u * l > e && --u),
                u < s && 0.5 <= n && n < 2 ? Ln(t, e, 2 * n) : [s, u, l]
            );
        }
        function On(t, e, n) {
            return Ln((t = +t), (e = +e), (n = +n))[2];
        }
        function Bn(t, e) {
            switch (arguments.length) {
                case 0:
                    break;
                case 1:
                    this.range(t);
                    break;
                default:
                    this.range(e).domain(t);
            }
            return this;
        }
        function qn(t) {
            return +t;
        }
        var Xn = [0, 1];
        function jn(t) {
            return t;
        }
        function Hn(t, e) {
            return (e -= t = +t)
                ? function (n) {
                      return (n - t) / e;
                  }
                : ((n = isNaN(e) ? NaN : 0.5),
                  function () {
                      return n;
                  });
            var n;
        }
        function Gn(t, e, n) {
            var r = t[0],
                i = t[1],
                a = e[0],
                o = e[1];
            return (
                i < r ? ((r = Hn(i, r)), (a = n(o, a))) : ((r = Hn(r, i)), (a = n(a, o))),
                function (t) {
                    return a(r(t));
                }
            );
        }
        function Yn(t, e, n) {
            var r = Math.min(t.length, e.length) - 1,
                i = new Array(r),
                a = new Array(r),
                o = -1;
            for (t[r] < t[0] && ((t = t.slice().reverse()), (e = e.slice().reverse())); ++o < r; ) (i[o] = Hn(t[o], t[o + 1])), (a[o] = n(e[o], e[o + 1]));
            return function (e) {
                var n = Cn(t, e, 1, r) - 1;
                return a[n](i[n](e));
            };
        }
        function Vn() {
            var t,
                e,
                n,
                r,
                i,
                a,
                o = Xn,
                s = Xn,
                u = Ye,
                l = jn;
            function h() {
                var t,
                    e,
                    n,
                    u = Math.min(o.length, s.length);
                return (
                    l !== jn &&
                        ((t = o[0]),
                        (e = o[u - 1]),
                        t > e && ((n = t), (t = e), (e = n)),
                        (l = function (n) {
                            return Math.max(t, Math.min(e, n));
                        })),
                    (r = u > 2 ? Yn : Gn),
                    (i = a = null),
                    c
                );
            }
            function c(e) {
                return null == e || isNaN((e = +e)) ? n : (i || (i = r(o.map(t), s, u)))(t(l(e)));
            }
            return (
                (c.invert = function (n) {
                    return l(e((a || (a = r(s, o.map(t), qe)))(n)));
                }),
                (c.domain = function (t) {
                    return arguments.length ? ((o = Array.from(t, qn)), h()) : o.slice();
                }),
                (c.range = function (t) {
                    return arguments.length ? ((s = Array.from(t)), h()) : s.slice();
                }),
                (c.rangeRound = function (t) {
                    return (s = Array.from(t)), (u = Ve), h();
                }),
                (c.clamp = function (t) {
                    return arguments.length ? ((l = !!t || jn), h()) : l !== jn;
                }),
                (c.interpolate = function (t) {
                    return arguments.length ? ((u = t), h()) : u;
                }),
                (c.unknown = function (t) {
                    return arguments.length ? ((n = t), c) : n;
                }),
                function (n, r) {
                    return (t = n), (e = r), h();
                }
            );
        }
        function Fn(t, e) {
            if ((n = (t = e ? t.toExponential(e - 1) : t.toExponential()).indexOf("e")) < 0) return null;
            var n,
                r = t.slice(0, n);
            return [r.length > 1 ? r[0] + r.slice(2) : r, +t.slice(n + 1)];
        }
        function Un(t) {
            return (t = Fn(Math.abs(t))) ? t[1] : NaN;
        }
        var Wn,
            Kn = /^(?:(.)?([<>=^]))?([+\-( ])?([$#])?(0)?(\d+)?(,)?(\.\d+)?(~)?([a-z%])?$/i;
        function Qn(t) {
            if (!(e = Kn.exec(t))) throw new Error("invalid format: " + t);
            var e;
            return new Zn({ fill: e[1], align: e[2], sign: e[3], symbol: e[4], zero: e[5], width: e[6], comma: e[7], precision: e[8] && e[8].slice(1), trim: e[9], type: e[10] });
        }
        function Zn(t) {
            (this.fill = void 0 === t.fill ? " " : t.fill + ""),
                (this.align = void 0 === t.align ? ">" : t.align + ""),
                (this.sign = void 0 === t.sign ? "-" : t.sign + ""),
                (this.symbol = void 0 === t.symbol ? "" : t.symbol + ""),
                (this.zero = !!t.zero),
                (this.width = void 0 === t.width ? void 0 : +t.width),
                (this.comma = !!t.comma),
                (this.precision = void 0 === t.precision ? void 0 : +t.precision),
                (this.trim = !!t.trim),
                (this.type = void 0 === t.type ? "" : t.type + "");
        }
        function Jn(t, e) {
            var n = Fn(t, e);
            if (!n) return t + "";
            var r = n[0],
                i = n[1];
            return i < 0 ? "0." + new Array(-i).join("0") + r : r.length > i + 1 ? r.slice(0, i + 1) + "." + r.slice(i + 1) : r + new Array(i - r.length + 2).join("0");
        }
        (Qn.prototype = Zn.prototype),
            (Zn.prototype.toString = function () {
                return (
                    this.fill +
                    this.align +
                    this.sign +
                    this.symbol +
                    (this.zero ? "0" : "") +
                    (void 0 === this.width ? "" : Math.max(1, 0 | this.width)) +
                    (this.comma ? "," : "") +
                    (void 0 === this.precision ? "" : "." + Math.max(0, 0 | this.precision)) +
                    (this.trim ? "~" : "") +
                    this.type
                );
            });
        var tr = {
            "%": (t, e) => (100 * t).toFixed(e),
            b: (t) => Math.round(t).toString(2),
            c: (t) => t + "",
            d: function (t) {
                return Math.abs((t = Math.round(t))) >= 1e21 ? t.toLocaleString("en").replace(/,/g, "") : t.toString(10);
            },
            e: (t, e) => t.toExponential(e),
            f: (t, e) => t.toFixed(e),
            g: (t, e) => t.toPrecision(e),
            o: (t) => Math.round(t).toString(8),
            p: (t, e) => Jn(100 * t, e),
            r: Jn,
            s: function (t, e) {
                var n = Fn(t, e);
                if (!n) return t + "";
                var r = n[0],
                    i = n[1],
                    a = i - (Wn = 3 * Math.max(-8, Math.min(8, Math.floor(i / 3)))) + 1,
                    o = r.length;
                return a === o ? r : a > o ? r + new Array(a - o + 1).join("0") : a > 0 ? r.slice(0, a) + "." + r.slice(a) : "0." + new Array(1 - a).join("0") + Fn(t, Math.max(0, e + a - 1))[0];
            },
            X: (t) => Math.round(t).toString(16).toUpperCase(),
            x: (t) => Math.round(t).toString(16),
        };
        function er(t) {
            return t;
        }
        var nr,
            rr,
            ir,
            ar = Array.prototype.map,
            or = ["y", "z", "a", "f", "p", "n", "µ", "m", "", "k", "M", "G", "T", "P", "E", "Z", "Y"];
        function sr(t) {
            var e,
                n,
                r =
                    void 0 === t.grouping || void 0 === t.thousands
                        ? er
                        : ((e = ar.call(t.grouping, Number)),
                          (n = t.thousands + ""),
                          function (t, r) {
                              for (var i = t.length, a = [], o = 0, s = e[0], u = 0; i > 0 && s > 0 && (u + s + 1 > r && (s = Math.max(1, r - u)), a.push(t.substring((i -= s), i + s)), !((u += s + 1) > r)); )
                                  s = e[(o = (o + 1) % e.length)];
                              return a.reverse().join(n);
                          }),
                i = void 0 === t.currency ? "" : t.currency[0] + "",
                a = void 0 === t.currency ? "" : t.currency[1] + "",
                o = void 0 === t.decimal ? "." : t.decimal + "",
                s =
                    void 0 === t.numerals
                        ? er
                        : (function (t) {
                              return function (e) {
                                  return e.replace(/[0-9]/g, function (e) {
                                      return t[+e];
                                  });
                              };
                          })(ar.call(t.numerals, String)),
                u = void 0 === t.percent ? "%" : t.percent + "",
                l = void 0 === t.minus ? "−" : t.minus + "",
                h = void 0 === t.nan ? "NaN" : t.nan + "";
            function c(t) {
                var e = (t = Qn(t)).fill,
                    n = t.align,
                    c = t.sign,
                    f = t.symbol,
                    d = t.zero,
                    p = t.width,
                    g = t.comma,
                    m = t.precision,
                    v = t.trim,
                    _ = t.type;
                "n" === _ ? ((g = !0), (_ = "g")) : tr[_] || (void 0 === m && (m = 12), (v = !0), (_ = "g")), (d || ("0" === e && "=" === n)) && ((d = !0), (e = "0"), (n = "="));
                var y = "$" === f ? i : "#" === f && /[boxX]/.test(_) ? "0" + _.toLowerCase() : "",
                    w = "$" === f ? a : /[%p]/.test(_) ? u : "",
                    x = tr[_],
                    b = /[defgprs%]/.test(_);
                function M(t) {
                    var i,
                        a,
                        u,
                        f = y,
                        M = w;
                    if ("c" === _) (M = x(t) + M), (t = "");
                    else {
                        var A = (t = +t) < 0 || 1 / t < 0;
                        if (
                            ((t = isNaN(t) ? h : x(Math.abs(t), m)),
                            v &&
                                (t = (function (t) {
                                    t: for (var e, n = t.length, r = 1, i = -1; r < n; ++r)
                                        switch (t[r]) {
                                            case ".":
                                                i = e = r;
                                                break;
                                            case "0":
                                                0 === i && (i = r), (e = r);
                                                break;
                                            default:
                                                if (!+t[r]) break t;
                                                i > 0 && (i = 0);
                                        }
                                    return i > 0 ? t.slice(0, i) + t.slice(e + 1) : t;
                                })(t)),
                            A && 0 == +t && "+" !== c && (A = !1),
                            (f = (A ? ("(" === c ? c : l) : "-" === c || "(" === c ? "" : c) + f),
                            (M = ("s" === _ ? or[8 + Wn / 3] : "") + M + (A && "(" === c ? ")" : "")),
                            b)
                        )
                            for (i = -1, a = t.length; ++i < a; )
                                if (48 > (u = t.charCodeAt(i)) || u > 57) {
                                    (M = (46 === u ? o + t.slice(i + 1) : t.slice(i)) + M), (t = t.slice(0, i));
                                    break;
                                }
                    }
                    g && !d && (t = r(t, 1 / 0));
                    var N = f.length + t.length + M.length,
                        k = N < p ? new Array(p - N + 1).join(e) : "";
                    switch ((g && d && ((t = r(k + t, k.length ? p - M.length : 1 / 0)), (k = "")), n)) {
                        case "<":
                            t = f + t + M + k;
                            break;
                        case "=":
                            t = f + k + t + M;
                            break;
                        case "^":
                            t = k.slice(0, (N = k.length >> 1)) + f + t + M + k.slice(N);
                            break;
                        default:
                            t = k + f + t + M;
                    }
                    return s(t);
                }
                return (
                    (m = void 0 === m ? 6 : /[gprs]/.test(_) ? Math.max(1, Math.min(21, m)) : Math.max(0, Math.min(20, m))),
                    (M.toString = function () {
                        return t + "";
                    }),
                    M
                );
            }
            return {
                format: c,
                formatPrefix: function (t, e) {
                    var n = c((((t = Qn(t)).type = "f"), t)),
                        r = 3 * Math.max(-8, Math.min(8, Math.floor(Un(e) / 3))),
                        i = Math.pow(10, -r),
                        a = or[8 + r / 3];
                    return function (t) {
                        return n(i * t) + a;
                    };
                },
            };
        }
        function ur(t, e, n, r) {
            var i,
                a = (function (t, e, n) {
                    n = +n;
                    const r = (e = +e) < (t = +t),
                        i = r ? On(e, t, n) : On(t, e, n);
                    return (r ? -1 : 1) * (i < 0 ? 1 / -i : i);
                })(t, e, n);
            switch ((r = Qn(null == r ? ",f" : r)).type) {
                case "s":
                    var o = Math.max(Math.abs(t), Math.abs(e));
                    return (
                        null != r.precision ||
                            isNaN(
                                (i = (function (t, e) {
                                    return Math.max(0, 3 * Math.max(-8, Math.min(8, Math.floor(Un(e) / 3))) - Un(Math.abs(t)));
                                })(a, o))
                            ) ||
                            (r.precision = i),
                        ir(r, o)
                    );
                case "":
                case "e":
                case "g":
                case "p":
                case "r":
                    null != r.precision ||
                        isNaN(
                            (i = (function (t, e) {
                                return (t = Math.abs(t)), (e = Math.abs(e) - t), Math.max(0, Un(e) - Un(t)) + 1;
                            })(a, Math.max(Math.abs(t), Math.abs(e))))
                        ) ||
                        (r.precision = i - ("e" === r.type));
                    break;
                case "f":
                case "%":
                    null != r.precision ||
                        isNaN(
                            (i = (function (t) {
                                return Math.max(0, -Un(Math.abs(t)));
                            })(a))
                        ) ||
                        (r.precision = i - 2 * ("%" === r.type));
            }
            return rr(r);
        }
        function lr(t) {
            var e = t.domain;
            return (
                (t.ticks = function (t) {
                    var n = e();
                    return (function (t, e, n) {
                        if (!((n = +n) > 0)) return [];
                        if ((t = +t) == (e = +e)) return [t];
                        const r = e < t,
                            [i, a, o] = r ? Ln(e, t, n) : Ln(t, e, n);
                        if (!(a >= i)) return [];
                        const s = a - i + 1,
                            u = new Array(s);
                        if (r)
                            if (o < 0) for (let t = 0; t < s; ++t) u[t] = (a - t) / -o;
                            else for (let t = 0; t < s; ++t) u[t] = (a - t) * o;
                        else if (o < 0) for (let t = 0; t < s; ++t) u[t] = (i + t) / -o;
                        else for (let t = 0; t < s; ++t) u[t] = (i + t) * o;
                        return u;
                    })(n[0], n[n.length - 1], null == t ? 10 : t);
                }),
                (t.tickFormat = function (t, n) {
                    var r = e();
                    return ur(r[0], r[r.length - 1], null == t ? 10 : t, n);
                }),
                (t.nice = function (n) {
                    null == n && (n = 10);
                    var r,
                        i,
                        a = e(),
                        o = 0,
                        s = a.length - 1,
                        u = a[o],
                        l = a[s],
                        h = 10;
                    for (l < u && ((i = u), (u = l), (l = i), (i = o), (o = s), (s = i)); h-- > 0; ) {
                        if ((i = On(u, l, n)) === r) return (a[o] = u), (a[s] = l), e(a);
                        if (i > 0) (u = Math.floor(u / i) * i), (l = Math.ceil(l / i) * i);
                        else {
                            if (!(i < 0)) break;
                            (u = Math.ceil(u * i) / i), (l = Math.floor(l * i) / i);
                        }
                        r = i;
                    }
                    return t;
                }),
                t
            );
        }
        function hr() {
            var t = Vn()(jn, jn);
            return (
                (t.copy = function () {
                    return (e = t), hr().domain(e.domain()).range(e.range()).interpolate(e.interpolate()).clamp(e.clamp()).unknown(e.unknown());
                    var e;
                }),
                Bn.apply(t, arguments),
                lr(t)
            );
        }
        (nr = sr({ thousands: ",", grouping: [3], currency: ["$", ""] })), (rr = nr.format), (ir = nr.formatPrefix);
        const cr = { capture: !0, passive: !1 };
        function fr(t) {
            t.preventDefault(), t.stopImmediatePropagation();
        }
        var dr = (t) => () => t;
        function pr(t, { sourceEvent: e, target: n, transform: r, dispatch: i }) {
            Object.defineProperties(this, {
                type: { value: t, enumerable: !0, configurable: !0 },
                sourceEvent: { value: e, enumerable: !0, configurable: !0 },
                target: { value: n, enumerable: !0, configurable: !0 },
                transform: { value: r, enumerable: !0, configurable: !0 },
                _: { value: i },
            });
        }
        function gr(t, e, n) {
            (this.k = t), (this.x = e), (this.y = n);
        }
        gr.prototype = {
            constructor: gr,
            scale: function (t) {
                return 1 === t ? this : new gr(this.k * t, this.x, this.y);
            },
            translate: function (t, e) {
                return (0 === t) & (0 === e) ? this : new gr(this.k, this.x + this.k * t, this.y + this.k * e);
            },
            apply: function (t) {
                return [t[0] * this.k + this.x, t[1] * this.k + this.y];
            },
            applyX: function (t) {
                return t * this.k + this.x;
            },
            applyY: function (t) {
                return t * this.k + this.y;
            },
            invert: function (t) {
                return [(t[0] - this.x) / this.k, (t[1] - this.y) / this.k];
            },
            invertX: function (t) {
                return (t - this.x) / this.k;
            },
            invertY: function (t) {
                return (t - this.y) / this.k;
            },
            rescaleX: function (t) {
                return t.copy().domain(t.range().map(this.invertX, this).map(t.invert, t));
            },
            rescaleY: function (t) {
                return t.copy().domain(t.range().map(this.invertY, this).map(t.invert, t));
            },
            toString: function () {
                return "translate(" + this.x + "," + this.y + ") scale(" + this.k + ")";
            },
        };
        var mr = new gr(1, 0, 0);
        function vr(t) {
            for (; !t.__zoom; ) if (!(t = t.parentNode)) return mr;
            return t.__zoom;
        }
        function _r(t) {
            t.stopImmediatePropagation();
        }
        function yr(t) {
            t.preventDefault(), t.stopImmediatePropagation();
        }
        function wr(t) {
            return !((t.ctrlKey && "wheel" !== t.type) || t.button);
        }
        function xr() {
            var t = this;
            return t instanceof SVGElement
                ? (t = t.ownerSVGElement || t).hasAttribute("viewBox")
                    ? [
                          [(t = t.viewBox.baseVal).x, t.y],
                          [t.x + t.width, t.y + t.height],
                      ]
                    : [
                          [0, 0],
                          [t.width.baseVal.value, t.height.baseVal.value],
                      ]
                : [
                      [0, 0],
                      [t.clientWidth, t.clientHeight],
                  ];
        }
        function br() {
            return this.__zoom || mr;
        }
        function Mr(t) {
            return -t.deltaY * (1 === t.deltaMode ? 0.05 : t.deltaMode ? 1 : 0.002) * (t.ctrlKey ? 10 : 1);
        }
        function Ar() {
            return navigator.maxTouchPoints || "ontouchstart" in this;
        }
        function Nr(t, e, n) {
            var r = t.invertX(e[0][0]) - n[0][0],
                i = t.invertX(e[1][0]) - n[1][0],
                a = t.invertY(e[0][1]) - n[0][1],
                o = t.invertY(e[1][1]) - n[1][1];
            return t.translate(i > r ? (r + i) / 2 : Math.min(0, r) || Math.max(0, i), o > a ? (a + o) / 2 : Math.min(0, a) || Math.max(0, o));
        }
        function kr() {
            var t,
                e,
                n,
                r = wr,
                i = xr,
                a = Nr,
                o = Mr,
                s = Ar,
                u = [0, 1 / 0],
                l = [
                    [-1 / 0, -1 / 0],
                    [1 / 0, 1 / 0],
                ],
                h = 250,
                c = en,
                f = mt("start", "zoom", "end"),
                d = 500,
                p = 150,
                g = 0,
                m = 10;
            function v(t) {
                t.property("__zoom", br)
                    .on("wheel.zoom", A, { passive: !1 })
                    .on("mousedown.zoom", N)
                    .on("dblclick.zoom", k)
                    .filter(s)
                    .on("touchstart.zoom", S)
                    .on("touchmove.zoom", E)
                    .on("touchend.zoom touchcancel.zoom", $)
                    .style("-webkit-tap-highlight-color", "rgba(0,0,0,0)");
            }
            function _(t, e) {
                return (e = Math.max(u[0], Math.min(u[1], e))) === t.k ? t : new gr(e, t.x, t.y);
            }
            function y(t, e, n) {
                var r = e[0] - n[0] * t.k,
                    i = e[1] - n[1] * t.k;
                return r === t.x && i === t.y ? t : new gr(t.k, r, i);
            }
            function w(t) {
                return [(+t[0][0] + +t[1][0]) / 2, (+t[0][1] + +t[1][1]) / 2];
            }
            function x(t, e, n, r) {
                t.on("start.zoom", function () {
                    b(this, arguments).event(r).start();
                })
                    .on("interrupt.zoom end.zoom", function () {
                        b(this, arguments).event(r).end();
                    })
                    .tween("zoom", function () {
                        var t = this,
                            a = arguments,
                            o = b(t, a).event(r),
                            s = i.apply(t, a),
                            u = null == n ? w(s) : "function" == typeof n ? n.apply(t, a) : n,
                            l = Math.max(s[1][0] - s[0][0], s[1][1] - s[0][1]),
                            h = t.__zoom,
                            f = "function" == typeof e ? e.apply(t, a) : e,
                            d = c(h.invert(u).concat(l / h.k), f.invert(u).concat(l / f.k));
                        return function (t) {
                            if (1 === t) t = f;
                            else {
                                var e = d(t),
                                    n = l / e[2];
                                t = new gr(n, u[0] - e[0] * n, u[1] - e[1] * n);
                            }
                            o.zoom(null, t);
                        };
                    });
            }
            function b(t, e, n) {
                return (!n && t.__zooming) || new M(t, e);
            }
            function M(t, e) {
                (this.that = t), (this.args = e), (this.active = 0), (this.sourceEvent = null), (this.extent = i.apply(t, e)), (this.taps = 0);
            }
            function A(t, ...e) {
                if (r.apply(this, arguments)) {
                    var n = b(this, e).event(t),
                        i = this.__zoom,
                        s = Math.max(u[0], Math.min(u[1], i.k * Math.pow(2, o.apply(this, arguments)))),
                        h = pt(t);
                    if (n.wheel) (n.mouse[0][0] === h[0] && n.mouse[0][1] === h[1]) || (n.mouse[1] = i.invert((n.mouse[0] = h))), clearTimeout(n.wheel);
                    else {
                        if (i.k === s) return;
                        (n.mouse = [h, i.invert(h)]), Zt(this), n.start();
                    }
                    yr(t),
                        (n.wheel = setTimeout(function () {
                            (n.wheel = null), n.end();
                        }, p)),
                        n.zoom("mouse", a(y(_(i, s), n.mouse[0], n.mouse[1]), n.extent, l));
                }
            }
            function N(t, ...e) {
                if (!n && r.apply(this, arguments)) {
                    var i = t.currentTarget,
                        o = b(this, e, !0).event(t),
                        s = dt(t.view)
                            .on(
                                "mousemove.zoom",
                                function (t) {
                                    if ((yr(t), !o.moved)) {
                                        var e = t.clientX - h,
                                            n = t.clientY - c;
                                        o.moved = e * e + n * n > g;
                                    }
                                    o.event(t).zoom("mouse", a(y(o.that.__zoom, (o.mouse[0] = pt(t, i)), o.mouse[1]), o.extent, l));
                                },
                                !0
                            )
                            .on(
                                "mouseup.zoom",
                                function (t) {
                                    s.on("mousemove.zoom mouseup.zoom", null),
                                        (function (t, e) {
                                            var n = t.document.documentElement,
                                                r = dt(t).on("dragstart.drag", null);
                                            e &&
                                                (r.on("click.drag", fr, cr),
                                                setTimeout(function () {
                                                    r.on("click.drag", null);
                                                }, 0)),
                                                "onselectstart" in n ? r.on("selectstart.drag", null) : ((n.style.MozUserSelect = n.__noselect), delete n.__noselect);
                                        })(t.view, o.moved),
                                        yr(t),
                                        o.event(t).end();
                                },
                                !0
                            ),
                        u = pt(t, i),
                        h = t.clientX,
                        c = t.clientY;
                    !(function (t) {
                        var e = t.document.documentElement,
                            n = dt(t).on("dragstart.drag", fr, cr);
                        "onselectstart" in e ? n.on("selectstart.drag", fr, cr) : ((e.__noselect = e.style.MozUserSelect), (e.style.MozUserSelect = "none"));
                    })(t.view),
                        _r(t),
                        (o.mouse = [u, this.__zoom.invert(u)]),
                        Zt(this),
                        o.start();
                }
            }
            function k(t, ...e) {
                if (r.apply(this, arguments)) {
                    var n = this.__zoom,
                        o = pt(t.changedTouches ? t.changedTouches[0] : t, this),
                        s = n.invert(o),
                        u = n.k * (t.shiftKey ? 0.5 : 2),
                        c = a(y(_(n, u), o, s), i.apply(this, e), l);
                    yr(t), h > 0 ? dt(this).transition().duration(h).call(x, c, o, t) : dt(this).call(v.transform, c, o, t);
                }
            }
            function S(n, ...i) {
                if (r.apply(this, arguments)) {
                    var a,
                        o,
                        s,
                        u,
                        l = n.touches,
                        h = l.length,
                        c = b(this, i, n.changedTouches.length === h).event(n);
                    for (_r(n), o = 0; o < h; ++o)
                        (u = [(u = pt((s = l[o]), this)), this.__zoom.invert(u), s.identifier]), c.touch0 ? c.touch1 || c.touch0[2] === u[2] || ((c.touch1 = u), (c.taps = 0)) : ((c.touch0 = u), (a = !0), (c.taps = 1 + !!t));
                    t && (t = clearTimeout(t)),
                        a &&
                            (c.taps < 2 &&
                                ((e = u[0]),
                                (t = setTimeout(function () {
                                    t = null;
                                }, d))),
                            Zt(this),
                            c.start());
                }
            }
            function E(t, ...e) {
                if (this.__zooming) {
                    var n,
                        r,
                        i,
                        o,
                        s = b(this, e).event(t),
                        u = t.changedTouches,
                        h = u.length;
                    for (yr(t), n = 0; n < h; ++n) (i = pt((r = u[n]), this)), s.touch0 && s.touch0[2] === r.identifier ? (s.touch0[0] = i) : s.touch1 && s.touch1[2] === r.identifier && (s.touch1[0] = i);
                    if (((r = s.that.__zoom), s.touch1)) {
                        var c = s.touch0[0],
                            f = s.touch0[1],
                            d = s.touch1[0],
                            p = s.touch1[1],
                            g = (g = d[0] - c[0]) * g + (g = d[1] - c[1]) * g,
                            m = (m = p[0] - f[0]) * m + (m = p[1] - f[1]) * m;
                        (r = _(r, Math.sqrt(g / m))), (i = [(c[0] + d[0]) / 2, (c[1] + d[1]) / 2]), (o = [(f[0] + p[0]) / 2, (f[1] + p[1]) / 2]);
                    } else {
                        if (!s.touch0) return;
                        (i = s.touch0[0]), (o = s.touch0[1]);
                    }
                    s.zoom("touch", a(y(r, i, o), s.extent, l));
                }
            }
            function $(t, ...r) {
                if (this.__zooming) {
                    var i,
                        a,
                        o = b(this, r).event(t),
                        s = t.changedTouches,
                        u = s.length;
                    for (
                        _r(t),
                            n && clearTimeout(n),
                            n = setTimeout(function () {
                                n = null;
                            }, d),
                            i = 0;
                        i < u;
                        ++i
                    )
                        (a = s[i]), o.touch0 && o.touch0[2] === a.identifier ? delete o.touch0 : o.touch1 && o.touch1[2] === a.identifier && delete o.touch1;
                    if ((o.touch1 && !o.touch0 && ((o.touch0 = o.touch1), delete o.touch1), o.touch0)) o.touch0[1] = this.__zoom.invert(o.touch0[0]);
                    else if ((o.end(), 2 === o.taps && ((a = pt(a, this)), Math.hypot(e[0] - a[0], e[1] - a[1]) < m))) {
                        var l = dt(this).on("dblclick.zoom");
                        l && l.apply(this, arguments);
                    }
                }
            }
            return (
                (v.transform = function (t, e, n, r) {
                    var i = t.selection ? t.selection() : t;
                    i.property("__zoom", br),
                        t !== i
                            ? x(t, e, n, r)
                            : i.interrupt().each(function () {
                                  b(this, arguments)
                                      .event(r)
                                      .start()
                                      .zoom(null, "function" == typeof e ? e.apply(this, arguments) : e)
                                      .end();
                              });
                }),
                (v.scaleBy = function (t, e, n, r) {
                    v.scaleTo(
                        t,
                        function () {
                            return this.__zoom.k * ("function" == typeof e ? e.apply(this, arguments) : e);
                        },
                        n,
                        r
                    );
                }),
                (v.scaleTo = function (t, e, n, r) {
                    v.transform(
                        t,
                        function () {
                            var t = i.apply(this, arguments),
                                r = this.__zoom,
                                o = null == n ? w(t) : "function" == typeof n ? n.apply(this, arguments) : n,
                                s = r.invert(o),
                                u = "function" == typeof e ? e.apply(this, arguments) : e;
                            return a(y(_(r, u), o, s), t, l);
                        },
                        n,
                        r
                    );
                }),
                (v.translateBy = function (t, e, n, r) {
                    v.transform(
                        t,
                        function () {
                            return a(this.__zoom.translate("function" == typeof e ? e.apply(this, arguments) : e, "function" == typeof n ? n.apply(this, arguments) : n), i.apply(this, arguments), l);
                        },
                        null,
                        r
                    );
                }),
                (v.translateTo = function (t, e, n, r, o) {
                    v.transform(
                        t,
                        function () {
                            var t = i.apply(this, arguments),
                                o = this.__zoom,
                                s = null == r ? w(t) : "function" == typeof r ? r.apply(this, arguments) : r;
                            return a(
                                mr
                                    .translate(s[0], s[1])
                                    .scale(o.k)
                                    .translate("function" == typeof e ? -e.apply(this, arguments) : -e, "function" == typeof n ? -n.apply(this, arguments) : -n),
                                t,
                                l
                            );
                        },
                        r,
                        o
                    );
                }),
                (M.prototype = {
                    event: function (t) {
                        return t && (this.sourceEvent = t), this;
                    },
                    start: function () {
                        return 1 == ++this.active && ((this.that.__zooming = this), this.emit("start")), this;
                    },
                    zoom: function (t, e) {
                        return (
                            this.mouse && "mouse" !== t && (this.mouse[1] = e.invert(this.mouse[0])),
                            this.touch0 && "touch" !== t && (this.touch0[1] = e.invert(this.touch0[0])),
                            this.touch1 && "touch" !== t && (this.touch1[1] = e.invert(this.touch1[0])),
                            (this.that.__zoom = e),
                            this.emit("zoom"),
                            this
                        );
                    },
                    end: function () {
                        return 0 == --this.active && (delete this.that.__zooming, this.emit("end")), this;
                    },
                    emit: function (t) {
                        var e = dt(this.that).datum();
                        f.call(t, this.that, new pr(t, { sourceEvent: this.sourceEvent, target: v, type: t, transform: this.that.__zoom, dispatch: f }), e);
                    },
                }),
                (v.wheelDelta = function (t) {
                    return arguments.length ? ((o = "function" == typeof t ? t : dr(+t)), v) : o;
                }),
                (v.filter = function (t) {
                    return arguments.length ? ((r = "function" == typeof t ? t : dr(!!t)), v) : r;
                }),
                (v.touchable = function (t) {
                    return arguments.length ? ((s = "function" == typeof t ? t : dr(!!t)), v) : s;
                }),
                (v.extent = function (t) {
                    return arguments.length
                        ? ((i =
                              "function" == typeof t
                                  ? t
                                  : dr([
                                        [+t[0][0], +t[0][1]],
                                        [+t[1][0], +t[1][1]],
                                    ])),
                          v)
                        : i;
                }),
                (v.scaleExtent = function (t) {
                    return arguments.length ? ((u[0] = +t[0]), (u[1] = +t[1]), v) : [u[0], u[1]];
                }),
                (v.translateExtent = function (t) {
                    return arguments.length
                        ? ((l[0][0] = +t[0][0]), (l[1][0] = +t[1][0]), (l[0][1] = +t[0][1]), (l[1][1] = +t[1][1]), v)
                        : [
                              [l[0][0], l[0][1]],
                              [l[1][0], l[1][1]],
                          ];
                }),
                (v.constrain = function (t) {
                    return arguments.length ? ((a = t), v) : a;
                }),
                (v.duration = function (t) {
                    return arguments.length ? ((h = +t), v) : h;
                }),
                (v.interpolate = function (t) {
                    return arguments.length ? ((c = t), v) : c;
                }),
                (v.on = function () {
                    var t = f.on.apply(f, arguments);
                    return t === f ? v : t;
                }),
                (v.clickDistance = function (t) {
                    return arguments.length ? ((g = (t = +t) * t), v) : Math.sqrt(g);
                }),
                (v.tapDistance = function (t) {
                    return arguments.length ? ((m = +t), v) : m;
                }),
                v
            );
        }
        function Sr(t) {
            var e = 0,
                n = t.children,
                r = n && n.length;
            if (r) for (; --r >= 0; ) e += n[r].value;
            else e = 1;
            t.value = e;
        }
        function Er(t, e) {
            t instanceof Map ? ((t = [void 0, t]), void 0 === e && (e = Pr)) : void 0 === e && (e = $r);
            for (var n, r, i, a, o, s = new Cr(t), u = [s]; (n = u.pop()); )
                if ((i = e(n.data)) && (o = (i = Array.from(i)).length)) for (n.children = i, a = o - 1; a >= 0; --a) u.push((r = i[a] = new Cr(i[a]))), (r.parent = n), (r.depth = n.depth + 1);
            return s.eachBefore(zr);
        }
        function $r(t) {
            return t.children;
        }
        function Pr(t) {
            return Array.isArray(t) ? t[1] : null;
        }
        function Tr(t) {
            void 0 !== t.data.value && (t.value = t.data.value), (t.data = t.data.data);
        }
        function zr(t) {
            var e = 0;
            do {
                t.height = e;
            } while ((t = t.parent) && t.height < ++e);
        }
        function Cr(t) {
            (this.data = t), (this.depth = this.height = 0), (this.parent = null);
        }
        function Rr(t) {
            (t.x0 = Math.round(t.x0)), (t.y0 = Math.round(t.y0)), (t.x1 = Math.round(t.x1)), (t.y1 = Math.round(t.y1));
        }
        function Dr() {
            var t = 1,
                e = 1,
                n = 0,
                r = !1;
            function i(i) {
                var a = i.height + 1;
                return (
                    (i.x0 = i.y0 = n),
                    (i.x1 = t),
                    (i.y1 = e / a),
                    i.eachBefore(
                        (function (t, e) {
                            return function (r) {
                                r.children &&
                                    (function (t, e, n, r, i) {
                                        for (var a, o = t.children, s = -1, u = o.length, l = t.value && (r - e) / t.value; ++s < u; ) ((a = o[s]).y0 = n), (a.y1 = i), (a.x0 = e), (a.x1 = e += a.value * l);
                                    })(r, r.x0, (t * (r.depth + 1)) / e, r.x1, (t * (r.depth + 2)) / e);
                                var i = r.x0,
                                    a = r.y0,
                                    o = r.x1 - n,
                                    s = r.y1 - n;
                                o < i && (i = o = (i + o) / 2), s < a && (a = s = (a + s) / 2), (r.x0 = i), (r.y0 = a), (r.x1 = o), (r.y1 = s);
                            };
                        })(e, a)
                    ),
                    r && i.eachBefore(Rr),
                    i
                );
            }
            return (
                (i.round = function (t) {
                    return arguments.length ? ((r = !!t), i) : r;
                }),
                (i.size = function (n) {
                    return arguments.length ? ((t = +n[0]), (e = +n[1]), i) : [t, e];
                }),
                (i.padding = function (t) {
                    return arguments.length ? ((n = +t), i) : n;
                }),
                i
            );
        }
        function Ir(t) {
            return function () {
                return t;
            };
        }
        (vr.prototype = gr.prototype),
            (Cr.prototype = Er.prototype = {
                constructor: Cr,
                count: function () {
                    return this.eachAfter(Sr);
                },
                each: function (t, e) {
                    let n = -1;
                    for (const r of this) t.call(e, r, ++n, this);
                    return this;
                },
                eachAfter: function (t, e) {
                    for (var n, r, i, a = this, o = [a], s = [], u = -1; (a = o.pop()); ) if ((s.push(a), (n = a.children))) for (r = 0, i = n.length; r < i; ++r) o.push(n[r]);
                    for (; (a = s.pop()); ) t.call(e, a, ++u, this);
                    return this;
                },
                eachBefore: function (t, e) {
                    for (var n, r, i = this, a = [i], o = -1; (i = a.pop()); ) if ((t.call(e, i, ++o, this), (n = i.children))) for (r = n.length - 1; r >= 0; --r) a.push(n[r]);
                    return this;
                },
                find: function (t, e) {
                    let n = -1;
                    for (const r of this) if (t.call(e, r, ++n, this)) return r;
                },
                sum: function (t) {
                    return this.eachAfter(function (e) {
                        for (var n = +t(e.data) || 0, r = e.children, i = r && r.length; --i >= 0; ) n += r[i].value;
                        e.value = n;
                    });
                },
                sort: function (t) {
                    return this.eachBefore(function (e) {
                        e.children && e.children.sort(t);
                    });
                },
                path: function (t) {
                    for (
                        var e = this,
                            n = (function (t, e) {
                                if (t === e) return t;
                                var n = t.ancestors(),
                                    r = e.ancestors(),
                                    i = null;
                                for (t = n.pop(), e = r.pop(); t === e; ) (i = t), (t = n.pop()), (e = r.pop());
                                return i;
                            })(e, t),
                            r = [e];
                        e !== n;

                    )
                        (e = e.parent), r.push(e);
                    for (var i = r.length; t !== n; ) r.splice(i, 0, t), (t = t.parent);
                    return r;
                },
                ancestors: function () {
                    for (var t = this, e = [t]; (t = t.parent); ) e.push(t);
                    return e;
                },
                descendants: function () {
                    return Array.from(this);
                },
                leaves: function () {
                    var t = [];
                    return (
                        this.eachBefore(function (e) {
                            e.children || t.push(e);
                        }),
                        t
                    );
                },
                links: function () {
                    var t = this,
                        e = [];
                    return (
                        t.each(function (n) {
                            n !== t && e.push({ source: n.parent, target: n });
                        }),
                        e
                    );
                },
                copy: function () {
                    return Er(this).eachBefore(Tr);
                },
                [Symbol.iterator]: function* () {
                    var t,
                        e,
                        n,
                        r,
                        i = this,
                        a = [i];
                    do {
                        for (t = a.reverse(), a = []; (i = t.pop()); ) if ((yield i, (e = i.children))) for (n = 0, r = e.length; n < r; ++n) a.push(e[n]);
                    } while (a.length);
                },
            });
        const Lr = Math.abs,
            Or = Math.atan2,
            Br = Math.cos,
            qr = Math.max,
            Xr = Math.min,
            jr = Math.sin,
            Hr = Math.sqrt,
            Gr = 1e-12,
            Yr = Math.PI,
            Vr = Yr / 2,
            Fr = 2 * Yr;
        function Ur(t) {
            return t >= 1 ? Vr : t <= -1 ? -Vr : Math.asin(t);
        }
        const Wr = Math.PI,
            Kr = 2 * Wr,
            Qr = 1e-6,
            Zr = Kr - Qr;
        function Jr(t) {
            this._ += t[0];
            for (let e = 1, n = t.length; e < n; ++e) this._ += arguments[e] + t[e];
        }
        class ti {
            constructor(t) {
                (this._x0 = this._y0 = this._x1 = this._y1 = null),
                    (this._ = ""),
                    (this._append =
                        null == t
                            ? Jr
                            : (function (t) {
                                  let e = Math.floor(t);
                                  if (!(e >= 0)) throw new Error(`invalid digits: ${t}`);
                                  if (e > 15) return Jr;
                                  const n = 10 ** e;
                                  return function (t) {
                                      this._ += t[0];
                                      for (let e = 1, r = t.length; e < r; ++e) this._ += Math.round(arguments[e] * n) / n + t[e];
                                  };
                              })(t));
            }
            moveTo(t, e) {
                this._append`M${(this._x0 = this._x1 = +t)},${(this._y0 = this._y1 = +e)}`;
            }
            closePath() {
                null !== this._x1 && ((this._x1 = this._x0), (this._y1 = this._y0), this._append`Z`);
            }
            lineTo(t, e) {
                this._append`L${(this._x1 = +t)},${(this._y1 = +e)}`;
            }
            quadraticCurveTo(t, e, n, r) {
                this._append`Q${+t},${+e},${(this._x1 = +n)},${(this._y1 = +r)}`;
            }
            bezierCurveTo(t, e, n, r, i, a) {
                this._append`C${+t},${+e},${+n},${+r},${(this._x1 = +i)},${(this._y1 = +a)}`;
            }
            arcTo(t, e, n, r, i) {
                if (((t = +t), (e = +e), (n = +n), (r = +r), (i = +i) < 0)) throw new Error(`negative radius: ${i}`);
                let a = this._x1,
                    o = this._y1,
                    s = n - t,
                    u = r - e,
                    l = a - t,
                    h = o - e,
                    c = l * l + h * h;
                if (null === this._x1) this._append`M${(this._x1 = t)},${(this._y1 = e)}`;
                else if (c > Qr)
                    if (Math.abs(h * s - u * l) > Qr && i) {
                        let f = n - a,
                            d = r - o,
                            p = s * s + u * u,
                            g = f * f + d * d,
                            m = Math.sqrt(p),
                            v = Math.sqrt(c),
                            _ = i * Math.tan((Wr - Math.acos((p + c - g) / (2 * m * v))) / 2),
                            y = _ / v,
                            w = _ / m;
                        Math.abs(y - 1) > Qr && this._append`L${t + y * l},${e + y * h}`, this._append`A${i},${i},0,0,${+(h * f > l * d)},${(this._x1 = t + w * s)},${(this._y1 = e + w * u)}`;
                    } else this._append`L${(this._x1 = t)},${(this._y1 = e)}`;
            }
            arc(t, e, n, r, i, a) {
                if (((t = +t), (e = +e), (a = !!a), (n = +n) < 0)) throw new Error(`negative radius: ${n}`);
                let o = n * Math.cos(r),
                    s = n * Math.sin(r),
                    u = t + o,
                    l = e + s,
                    h = 1 ^ a,
                    c = a ? r - i : i - r;
                null === this._x1 ? this._append`M${u},${l}` : (Math.abs(this._x1 - u) > Qr || Math.abs(this._y1 - l) > Qr) && this._append`L${u},${l}`,
                    n &&
                        (c < 0 && (c = (c % Kr) + Kr),
                        c > Zr
                            ? this._append`A${n},${n},0,1,${h},${t - o},${e - s}A${n},${n},0,1,${h},${(this._x1 = u)},${(this._y1 = l)}`
                            : c > Qr && this._append`A${n},${n},0,${+(c >= Wr)},${h},${(this._x1 = t + n * Math.cos(i))},${(this._y1 = e + n * Math.sin(i))}`);
            }
            rect(t, e, n, r) {
                this._append`M${(this._x0 = this._x1 = +t)},${(this._y0 = this._y1 = +e)}h${(n = +n)}v${+r}h${-n}Z`;
            }
            toString() {
                return this._;
            }
        }
        function ei(t) {
            return t.innerRadius;
        }
        function ni(t) {
            return t.outerRadius;
        }
        function ri(t) {
            return t.startAngle;
        }
        function ii(t) {
            return t.endAngle;
        }
        function ai(t) {
            return t && t.padAngle;
        }
        function oi(t, e, n, r, i, a, o) {
            var s = t - n,
                u = e - r,
                l = (o ? a : -a) / Hr(s * s + u * u),
                h = l * u,
                c = -l * s,
                f = t + h,
                d = e + c,
                p = n + h,
                g = r + c,
                m = (f + p) / 2,
                v = (d + g) / 2,
                _ = p - f,
                y = g - d,
                w = _ * _ + y * y,
                x = i - a,
                b = f * g - p * d,
                M = (y < 0 ? -1 : 1) * Hr(qr(0, x * x * w - b * b)),
                A = (b * y - _ * M) / w,
                N = (-b * _ - y * M) / w,
                k = (b * y + _ * M) / w,
                S = (-b * _ + y * M) / w,
                E = A - m,
                $ = N - v,
                P = k - m,
                T = S - v;
            return E * E + $ * $ > P * P + T * T && ((A = k), (N = S)), { cx: A, cy: N, x01: -h, y01: -c, x11: A * (i / x - 1), y11: N * (i / x - 1) };
        }
        function si() {
            var t = ei,
                e = ni,
                n = Ir(0),
                r = null,
                i = ri,
                a = ii,
                o = ai,
                s = null,
                u = (function (t) {
                    let e = 3;
                    return (
                        (t.digits = function (n) {
                            if (!arguments.length) return e;
                            if (null == n) e = null;
                            else {
                                const t = Math.floor(n);
                                if (!(t >= 0)) throw new RangeError(`invalid digits: ${n}`);
                                e = t;
                            }
                            return t;
                        }),
                        () => new ti(e)
                    );
                })(l);
            function l() {
                var l,
                    h,
                    c,
                    f = +t.apply(this, arguments),
                    d = +e.apply(this, arguments),
                    p = i.apply(this, arguments) - Vr,
                    g = a.apply(this, arguments) - Vr,
                    m = Lr(g - p),
                    v = g > p;
                if ((s || (s = l = u()), d < f && ((h = d), (d = f), (f = h)), d > Gr))
                    if (m > Fr - Gr) s.moveTo(d * Br(p), d * jr(p)), s.arc(0, 0, d, p, g, !v), f > Gr && (s.moveTo(f * Br(g), f * jr(g)), s.arc(0, 0, f, g, p, v));
                    else {
                        var _,
                            y,
                            w = p,
                            x = g,
                            b = p,
                            M = g,
                            A = m,
                            N = m,
                            k = o.apply(this, arguments) / 2,
                            S = k > Gr && (r ? +r.apply(this, arguments) : Hr(f * f + d * d)),
                            E = Xr(Lr(d - f) / 2, +n.apply(this, arguments)),
                            $ = E,
                            P = E;
                        if (S > Gr) {
                            var T = Ur((S / f) * jr(k)),
                                z = Ur((S / d) * jr(k));
                            (A -= 2 * T) > Gr ? ((b += T *= v ? 1 : -1), (M -= T)) : ((A = 0), (b = M = (p + g) / 2)), (N -= 2 * z) > Gr ? ((w += z *= v ? 1 : -1), (x -= z)) : ((N = 0), (w = x = (p + g) / 2));
                        }
                        var C = d * Br(w),
                            R = d * jr(w),
                            D = f * Br(M),
                            I = f * jr(M);
                        if (E > Gr) {
                            var L,
                                O = d * Br(x),
                                B = d * jr(x),
                                q = f * Br(b),
                                X = f * jr(b);
                            if (m < Yr)
                                if (
                                    (L = (function (t, e, n, r, i, a, o, s) {
                                        var u = n - t,
                                            l = r - e,
                                            h = o - i,
                                            c = s - a,
                                            f = c * u - h * l;
                                        if (!(f * f < Gr)) return [t + (f = (h * (e - a) - c * (t - i)) / f) * u, e + f * l];
                                    })(C, R, q, X, O, B, D, I))
                                ) {
                                    var j = C - L[0],
                                        H = R - L[1],
                                        G = O - L[0],
                                        Y = B - L[1],
                                        V = 1 / jr(((c = (j * G + H * Y) / (Hr(j * j + H * H) * Hr(G * G + Y * Y))) > 1 ? 0 : c < -1 ? Yr : Math.acos(c)) / 2),
                                        F = Hr(L[0] * L[0] + L[1] * L[1]);
                                    ($ = Xr(E, (f - F) / (V - 1))), (P = Xr(E, (d - F) / (V + 1)));
                                } else $ = P = 0;
                        }
                        N > Gr
                            ? P > Gr
                                ? ((_ = oi(q, X, C, R, d, P, v)),
                                  (y = oi(O, B, D, I, d, P, v)),
                                  s.moveTo(_.cx + _.x01, _.cy + _.y01),
                                  P < E
                                      ? s.arc(_.cx, _.cy, P, Or(_.y01, _.x01), Or(y.y01, y.x01), !v)
                                      : (s.arc(_.cx, _.cy, P, Or(_.y01, _.x01), Or(_.y11, _.x11), !v),
                                        s.arc(0, 0, d, Or(_.cy + _.y11, _.cx + _.x11), Or(y.cy + y.y11, y.cx + y.x11), !v),
                                        s.arc(y.cx, y.cy, P, Or(y.y11, y.x11), Or(y.y01, y.x01), !v)))
                                : (s.moveTo(C, R), s.arc(0, 0, d, w, x, !v))
                            : s.moveTo(C, R),
                            f > Gr && A > Gr
                                ? $ > Gr
                                    ? ((_ = oi(D, I, O, B, f, -$, v)),
                                      (y = oi(C, R, q, X, f, -$, v)),
                                      s.lineTo(_.cx + _.x01, _.cy + _.y01),
                                      $ < E
                                          ? s.arc(_.cx, _.cy, $, Or(_.y01, _.x01), Or(y.y01, y.x01), !v)
                                          : (s.arc(_.cx, _.cy, $, Or(_.y01, _.x01), Or(_.y11, _.x11), !v),
                                            s.arc(0, 0, f, Or(_.cy + _.y11, _.cx + _.x11), Or(y.cy + y.y11, y.cx + y.x11), v),
                                            s.arc(y.cx, y.cy, $, Or(y.y11, y.x11), Or(y.y01, y.x01), !v)))
                                    : s.arc(0, 0, f, M, b, v)
                                : s.lineTo(D, I);
                    }
                else s.moveTo(0, 0);
                if ((s.closePath(), l)) return (s = null), l + "" || null;
            }
            return (
                (l.centroid = function () {
                    var n = (+t.apply(this, arguments) + +e.apply(this, arguments)) / 2,
                        r = (+i.apply(this, arguments) + +a.apply(this, arguments)) / 2 - Yr / 2;
                    return [Br(r) * n, jr(r) * n];
                }),
                (l.innerRadius = function (e) {
                    return arguments.length ? ((t = "function" == typeof e ? e : Ir(+e)), l) : t;
                }),
                (l.outerRadius = function (t) {
                    return arguments.length ? ((e = "function" == typeof t ? t : Ir(+t)), l) : e;
                }),
                (l.cornerRadius = function (t) {
                    return arguments.length ? ((n = "function" == typeof t ? t : Ir(+t)), l) : n;
                }),
                (l.padRadius = function (t) {
                    return arguments.length ? ((r = null == t ? null : "function" == typeof t ? t : Ir(+t)), l) : r;
                }),
                (l.startAngle = function (t) {
                    return arguments.length ? ((i = "function" == typeof t ? t : Ir(+t)), l) : i;
                }),
                (l.endAngle = function (t) {
                    return arguments.length ? ((a = "function" == typeof t ? t : Ir(+t)), l) : a;
                }),
                (l.padAngle = function (t) {
                    return arguments.length ? ((o = "function" == typeof t ? t : Ir(+t)), l) : o;
                }),
                (l.context = function (t) {
                    return arguments.length ? ((s = null == t ? null : t), l) : s;
                }),
                l
            );
        }
        function ui(t) {
            if (!t.ok) throw new Error(t.status + " " + t.statusText);
            return t.text();
        }
        function li(t) {
            if (!t.ok) throw new Error(t.status + " " + t.statusText);
            if (204 !== t.status && 205 !== t.status) return t.json();
        }
        class hi {
            constructor(t, e = 6, n = 210, r = 100, i = !1, a = !1, o = !1, s = !1, u = !1, l = !1, h = 4) {
                (this._generations = e),
                    (this.circlePadding = 0),
                    o && (this.circlePadding = 40),
                    (this.padAngle = 0.03),
                    (this.padRadius = 10 * this.circlePadding),
                    (this.padDistance = this.padAngle * this.padRadius),
                    (this.cornerRadius = 0),
                    (this._numberOfInnerCircles = h),
                    (this.centerCircleRadius = 85),
                    (this.innerArcHeight = 85),
                    (this.outerArcHeight = 110),
                    o && ((this.innerArcHeight = this.circlePadding + 110), (this.outerArcHeight = this.circlePadding + 110)),
                    (this.colorArcWidth = 5),
                    (this.textPadding = 8),
                    (this._fontSize = 15),
                    (this._fontScale = r),
                    (this._hideEmptySegments = i),
                    (this._showColorGradients = a),
                    (this._showParentMarriageDates = o),
                    (this._showImages = s),
                    (this._showSilhouettes = u),
                    (this.updateDuration = 1250),
                    (this._fanDegree = n),
                    (this.rtl = l),
                    (this.labels = t),
                    (this.id = (() => {
                        let t = 1;
                        return function (e = !1) {
                            return e && (t = 0), t++;
                        };
                    })());
            }
            get generations() {
                return this._generations;
            }
            set generations(t) {
                this._generations = t;
            }
            get fanDegree() {
                return this._fanDegree;
            }
            set fanDegree(t) {
                this._fanDegree = t;
            }
            get fontScale() {
                return this._fontScale;
            }
            set fontScale(t) {
                this._fontScale = t;
            }
            get hideEmptySegments() {
                return this._hideEmptySegments;
            }
            set hideEmptySegments(t) {
                this._hideEmptySegments = t;
            }
            get showColorGradients() {
                return this._showColorGradients;
            }
            set showColorGradients(t) {
                this._showColorGradients = t;
            }
            get showParentMarriageDates() {
                return this._showParentMarriageDates;
            }
            set showParentMarriageDates(t) {
                this._showParentMarriageDates = t;
            }
            get showImages() {
                return this._showImages;
            }
            get showSilhouettes() {
                return this._showSilhouettes;
            }
            get numberOfInnerCircles() {
                return this._numberOfInnerCircles;
            }
            set numberOfInnerCircles(t) {
                this._numberOfInnerCircles = t;
            }
            get fontSize() {
                return this._fontSize;
            }
        }
        const ci = "M",
            fi = "F";
        class di {
            constructor(t) {
                (this._configuration = t), (this._nodes = null);
            }
            init(t) {
                let e = Er(
                        t,
                        (t) => (
                            !t.children && t.generation < this._configuration.generations && (t.children = [this.createEmptyNode(t.generation + 1, ci), this.createEmptyNode(t.generation + 1, fi)]),
                            t.children && t.children.length < 2 && (t.children[0].sex === ci ? t.children.push(this.createEmptyNode(t.generation + 1, fi)) : t.children.unshift(this.createEmptyNode(t.generation + 1, ci))),
                            t.children
                        )
                    ).count(),
                    n = Dr();
                (this._nodes = n(e).descendants()),
                    this._nodes.forEach((t) => {
                        t.data.id = this._configuration.id();
                    }),
                    this._configuration.id(!0);
            }
            get nodes() {
                return this._nodes;
            }
            createEmptyNode(t, e) {
                return { id: 0, xref: "", url: "", updateUrl: "", generation: t, name: "", firstNames: [], lastNames: [], preferredName: "", alternativeNames: [], isAltRtl: !1, sex: e, timespan: "" };
            }
        }
        class pi {
            constructor(t) {
                this._element = t.append("div").attr("class", "overlay").style("opacity", 1e-6);
            }
            show(t, e = 0, n = null) {
                this._element.select("p").remove(),
                    this._element.append("p").attr("class", "tooltip").text(t),
                    this._element
                        .transition()
                        .duration(e)
                        .style("opacity", 1)
                        .on("end", () => {
                            "function" == typeof n && n();
                        });
            }
            hide(t = 0, e = 0) {
                this._element.transition().delay(t).duration(e).style("opacity", 1e-6);
            }
            get() {
                return this._element;
            }
        }
        class gi {
            constructor(t) {
                this._element = t.append("defs");
            }
            get() {
                return this._element;
            }
        }
        class mi {
            constructor(t) {
                (this._zoom = null), (this._parent = t), this.init();
            }
            init() {
                (this._zoom = kr()),
                    this._zoom.scaleExtent([0.5, 5]).on("zoom", (t) => {
                        this._parent.attr("transform", t.transform);
                    }),
                    this._zoom.wheelDelta((t) => -t.deltaY * (1 === t.deltaMode ? 0.05 : t.deltaMode ? 1 : 0.002)),
                    this._zoom.filter((t) => {
                        if ("wheel" === t.type) {
                            if (!t.ctrlKey) return !1;
                            var e = vr(this);
                            if (e.k) {
                                if (e.k <= 0.5 && t.deltaY > 0) return t.preventDefault(), !1;
                                if (e.k >= 5 && t.deltaY < 0) return t.preventDefault(), !1;
                            }
                            return !0;
                        }
                        return t.button || "touchstart" !== t.type ? !((t.ctrlKey && "wheel" !== t.type) || t.button) : 2 === t.touches.length;
                    });
            }
            get() {
                return this._zoom;
            }
        }
        class vi {
            constructor(t) {
                (this._element = t), this._element.append("filter").attr("id", "drop-shadow").append("feDropShadow").attr("stdDeviation", "7 7").attr("dx", "0").attr("dy", "0").attr("flood-opacity", "0.3").attr("flood-color", "rgb(0,0,0)");
            }
            get() {
                return this._element;
            }
        }
        class _i {
            triggerDownload(t, e) {
                let n = new MouseEvent("click", { view: window, bubbles: !1, cancelable: !0 }),
                    r = document.createElement("a");
                r.setAttribute("download", e), r.setAttribute("href", t), r.setAttribute("target", "_blank"), r.dispatchEvent(n);
            }
        }
        class yi extends _i {
            copyStylesInline(t, e) {
                let n = ["svg", "g", "text", "textPath"];
                for (let r = 0; r < e.children.length; ++r) {
                    let i = e.children[r];
                    if (-1 !== n.indexOf(i.tagName)) {
                        this.copyStylesInline(t.children[r], i);
                        continue;
                    }
                    let a = window.getComputedStyle(t.children[r]);
                    for (let t = 0; t < a.length; ++t) i.style.setProperty(a[t], a.getPropertyValue(a[t]));
                }
            }
            calculateViewBox(t) {
                const e = t.getBBox();
                return [e.x - 50, e.y - 50, e.width + 100, e.height + 100];
            }
            createCanvas(t, e) {
                let n = document.createElement("canvas");
                return (n.width = t), (n.height = e), n;
            }
            convertToDataUrl(t, e, n) {
                return new Promise((r) => {
                    let i = new XMLSerializer().serializeToString(t),
                        a = window.URL || window.webkitURL || window,
                        o = new Blob([i], { type: "image/svg+xml;charset=utf-8" }),
                        s = a.createObjectURL(o),
                        u = new Image();
                    (u.onload = () => {
                        let t = this.createCanvas(e, n),
                            i = t.getContext("2d");
                        (i.fillStyle = "rgb(255,255,255)"), i.fillRect(0, 0, t.width, t.height), i.drawImage(u, 0, 0), a.revokeObjectURL(s);
                        let o = t.toDataURL("image/png").replace("image/png", "image/octet-stream");
                        r(o);
                    }),
                        (u.src = s);
                });
            }
            cloneSvg(t) {
                return new Promise((e) => {
                    e(t.cloneNode(!0));
                });
            }
            svgToImage(t, e, format) {
                var n;
                if(format == null){
                    n = [4960, 3508];
                }
                else{
                    n = format;
                }
                
                this.cloneSvg(t.get().node())
                    .then((e) => {
                        this.copyStylesInline(t.get().node(), e);
                        const r = this.calculateViewBox(t.get().node()),
                            i = Math.max(n[0], r[2]),
                            a = Math.max(n[1], r[3]);
                        return e.setAttribute("width", "" + i), e.setAttribute("height", "" + a), e.setAttribute("viewBox", "" + r), this.convertToDataUrl(e, i, a);
                    })
                    .then((t) => {this.triggerDownload(t, e)})
                    .catch(() => {
                        console.log("Failed to save chart as PNG image");
                    });
            }
            svgToPDF(t, e,orientation,format) {
                var n = [4960, 3508];
                
                this.cloneSvg(t.get().node())
                    .then((e) => {
                        this.copyStylesInline(t.get().node(), e);
                        const r = this.calculateViewBox(t.get().node()),
                            i = Math.max(n[0], r[2]),
                            a = Math.max(n[1], r[3]);
                        return e.setAttribute("width", "" + i), e.setAttribute("height", "" + a), e.setAttribute("viewBox", "" + r), this.convertToDataUrl(e, i, a);
                    })
                    .then((t) => {
                        var pdf = new jspdf.jsPDF({ orientation: orientation, unit: 'px', format: format });
                        var img = new Image();
                        img.src = t;
                        img.onload = function () {

                            // get pdf sizes
                            var pageWidth = pdf.internal.pageSize.getWidth();
                            var pageHeight = pdf.internal.pageSize.getHeight();

                            const imgAspectRatio = img.width / img.height;
                            const pageAspectRatio = pageWidth / pageHeight;

                            // compute image sizes to fit page and keep aspect ration
                            let drawWidth, drawHeight;

                            // Determine whether to scale based on width or height
                            if (imgAspectRatio > pageAspectRatio) {
                            // Scale based on width
                            drawWidth = pageWidth;
                            drawHeight = drawWidth / imgAspectRatio;
                            } else {
                            // Scale based on height
                            drawHeight = pageHeight;
                            drawWidth = drawHeight * imgAspectRatio;
                            }

                            // Center the image on the page
                            const xOffset = (pageWidth - drawWidth) / 2;
                            const yOffset = (pageHeight - drawHeight) / 2;


                            pdf.addImage(
                            img,
                            'JPEG',
                            5,
                            5,
                            drawWidth - 5,
                            drawHeight - 5
                            );
                            pdf.save(e);

                        };
                     })
                    .catch(() => {
                        console.log("Failed to save chart as PNG image");
                    });
            }
        }
        class wi extends _i {
            copyStylesInline(t, e) {
                return new Promise((n) => {
                    Promise.all(
                        t.map((t) =>
                            (function (t, e) {
                                return fetch(t, e).then(ui);
                            })(t)
                        )
                    ).then((t) => {
                        t.forEach((t) => {
                            t = t.replace(/.webtrees-fan-chart-container /g, "");
                            let n = document.createElementNS("http://www.w3.org/2000/svg", "style");
                            n.appendChild(document.createTextNode(t)), e.prepend(n);
                        }),
                            e.classList.add("wt-global"),
                            n(e);
                    });
                });
            }
            convertToObjectUrl(t) {
                return new Promise((e) => {
                    let n = new XMLSerializer().serializeToString(t),
                        r = window.URL || window.webkitURL || window,
                        i = new Blob([n], { type: "image/svg+xml;charset=utf-8" }),
                        a = r.createObjectURL(i),
                        o = new Image();
                    (o.onload = () => {
                        e(a);
                    }),
                        (o.src = a);
                });
            }
            cloneSvg(t) {
                return new Promise((e) => {
                    e(t.cloneNode(!0));
                });
            }
            svgToImage(t, e, n) {
                this.cloneSvg(t.get().node())
                    .then((t) => this.copyStylesInline(e, t))
                    .then((t) => this.convertToObjectUrl(t))
                    .then((t) => this.triggerDownload(t, n))
                    .catch(() => {
                        console.log("Failed to save chart as SVG image");
                    });
            }
        }
        class xi {
            constructor() {
                this._exportClass = null;
            }
            setExportClass(t) {
                switch (t) {
                    case "png":
                        this._exportClass = yi;
                        break;
                    case "svg":
                        this._exportClass = wi;
                }
            }
            createExport(t) {
                switch ((this.setExportClass(t), t)) {
                    case "png":
                    case "svg":
                        return new this._exportClass();
                }
            }
        }
        class bi {
            constructor(t, e) {
                (this._element = t.append("svg")), (this._defs = new gi(this._element)), (this._visual = null), (this._zoom = null), (this._div = null), (this._configuration = e), this.init();
            }
            init() {
                this._element.attr("width", "100%").attr("height", "100%").attr("text-rendering", "geometricPrecision").attr("text-anchor", "middle").attr("xmlns:xlink", "https://www.w3.org/1999/xlink"), new vi(this._defs.get());
            }
            initEvents(t) {
                this._element
                    .on("contextmenu", (t) => t.preventDefault())
                    .on("wheel", (e) => {
                        e.ctrlKey ||
                            t.show(this._configuration.labels.zoom, 300, () => {
                                t.hide(700, 800);
                            });
                    })
                    .on("touchend", (e) => {
                        e.touches.length < 2 && t.hide(0, 800);
                    })
                    .on("touchmove", (e) => {
                        e.touches.length >= 2 ? t.hide() : t.show(this._configuration.labels.move);
                    })
                    .on("click", (t) => this.doStopPropagation(t), !0),
                    this._configuration.rtl && this._element.classed("rtl", !0);
                const e = dt("div.tooltip");
                e.empty() ? (this._div = dt("body").append("div").attr("class", "tooltip").style("opacity", 0)) : (this._div = e.style("opacity", 0)),
                    (this._visual = this._element.append("g")),
                    this._visual.append("g").attr("class", "personGroup"),
                    (this._zoom = new mi(this._visual)),
                    this._element.call(this._zoom.get());
            }
            doStopPropagation(t) {
                t.defaultPrevented && t.stopPropagation();
            }
            export(t) {
                return new xi().createExport(t);
            }
            get defs() {
                return this._defs;
            }
            get zoom() {
                return this._zoom;
            }
            get visual() {
                return this._visual;
            }
            get() {
                return this._element;
            }
            get div() {
                return this._div;
            }
        }
        const Mi = Math.PI / 180,
            Ai = 180 / Math.PI,
            Ni = 2 * Math.PI;
        class ki {
            constructor(t) {
                this._configuration = t;
            }
            get startPi() {
                return 90 === this._configuration.fanDegree ? 0 : (-this._configuration.fanDegree / 2) * Mi;
            }
            get endPi() {
                return 90 === this._configuration.fanDegree ? this._configuration.fanDegree * Mi : (this._configuration.fanDegree / 2) * Mi;
            }
            get scale() {
                return hr().range([this.startPi, this.endPi]);
            }
            innerRadius(t) {
                return 0 === t
                    ? 0
                    : t <= this._configuration.numberOfInnerCircles
                    ? (t - 1) * this._configuration.innerArcHeight + this._configuration.centerCircleRadius + this._configuration.circlePadding
                    : this._configuration.numberOfInnerCircles * this._configuration.innerArcHeight +
                      (t - this._configuration.numberOfInnerCircles - 1) * this._configuration.outerArcHeight +
                      this._configuration.centerCircleRadius +
                      this._configuration.circlePadding;
            }
            outerRadius(t) {
                return 0 === t
                    ? this._configuration.centerCircleRadius
                    : t <= this._configuration.numberOfInnerCircles
                    ? (t - 1) * this._configuration.innerArcHeight + this._configuration.centerCircleRadius + this._configuration.innerArcHeight
                    : this._configuration.numberOfInnerCircles * this._configuration.innerArcHeight +
                      (t - this._configuration.numberOfInnerCircles - 1) * this._configuration.outerArcHeight +
                      this._configuration.centerCircleRadius +
                      this._configuration.outerArcHeight;
            }
            centerRadius(t) {
                return (this.innerRadius(t) + this.outerRadius(t)) / 2;
            }
            relativeRadius(t, e) {
                const n = this.outerRadius(t);
                return n - ((100 - e) * (n - this.innerRadius(t))) / 100;
            }
            calcAngle(t) {
                return Math.max(this.startPi, Math.min(this.endPi, this.scale(t)));
            }
            startAngle(t, e) {
                return 0 === t ? 0 : this.calcAngle(e);
            }
            endAngle(t, e) {
                return 0 === t ? Ni : this.calcAngle(e);
            }
            arcLength(t, e) {
                return (this.endAngle(t.depth, t.x1) - this.startAngle(t.depth, t.x0)) * this.relativeRadius(t.depth, e);
            }
        }
        class Si {
            constructor(t, e) {
                (this._svg = t), (this._configuration = e), (this._geometry = new ki(this._configuration));
            }
            createLabels(t, e) {
                if (this.isInnerLabel(e)) {
                    let n = dt(t.node().parentNode).attr("id");
                    if (e.data.firstNames.length) {
                        let r = this.createPathDefinition(n, 0, e),
                            i = t
                                .append("text")
                                .append("textPath")
                                .attr("xlink:href", "#" + r)
                                .attr("startOffset", "25%");
                        this.addFirstNames(i, e), this.truncateNames(i, e, 0);
                    }
                    if (e.data.lastNames.length) {
                        let r = this.createPathDefinition(n, 1, e),
                            i = t
                                .append("text")
                                .append("textPath")
                                .attr("xlink:href", "#" + r)
                                .attr("startOffset", "25%");
                        this.addLastNames(i, e), this.truncateNames(i, e, 1);
                    }
                    if (!e.data.firstNames.length && !e.data.lastNames.length) {
                        let r = this.createPathDefinition(n, 0, e),
                            i = t
                                .append("text")
                                .append("textPath")
                                .attr("xlink:href", "#" + r)
                                .attr("startOffset", "25%");
                        i.append("tspan").text(e.data.name), this.truncateNames(i, e, 0);
                    }
                    /*
                    if (e.data.alternativeNames.length) {
                        let r = this.createPathDefinition(n, 2, e),
                            i = t
                                .append("text")
                                .append("textPath")
                                .attr("xlink:href", "#" + r)
                                .attr("startOffset", "25%")
                                .attr("class", "wt-chart-box-name-alt")
                                .classed("rtl", e.data.isAltRtl);
                        this.addAlternativeNames(i, e), this.truncateNames(i, e, 2);
                    }
                    */
                    let r = this.createPathDefinition(n, 3, e),
                        i = t
                            .append("text")
                            .append("textPath")
                            .attr("xlink:href", "#" + r)
                            .attr("startOffset", "25%")
                            .attr("class", "date");
                    i.append("title").text(e.data.timespan);
                    let a = i.append("tspan").text(e.data.timespan),
                        o = this.getAvailableWidth(e, 3);
                    this.getTextLength(i) > o && (i.selectAll("tspan").each(this.truncateDate(i, o)), a.text(a.text() + "…"));
                } else {
                    if (e.depth >= 7) {
                        let n = t.append("text").attr("dy", "2px");
                        e.data.firstNames.length && this.addFirstNames(n, e),
                            e.data.lastNames.length && this.addLastNames(n, e, 0.25),
                            e.data.firstNames.length || e.data.lastNames.length || n.append("tspan").text(e.data.name),
                            this.truncateNames(n, e, 0);
                    } else {
                        if (e.data.firstNames.length) {
                            let n = t.append("text").attr("dy", "2px");
                            this.addFirstNames(n, e), this.truncateNames(n, e, 0);
                        }
                        if (e.data.lastNames.length) {
                            let n = t.append("text").attr("dy", "2px");
                            this.addLastNames(n, e), this.truncateNames(n, e, 1);
                        }
                        if (!e.data.firstNames.length && !e.data.lastNames.length) {
                            let n = t.append("text").attr("dy", "2px");
                            n.append("tspan").text(e.data.name), this.truncateNames(n, e, 0);
                        }
                        if (e.depth < 6) {
                            let n = t.append("text").attr("class", "date").attr("dy", "2px");
                            n.append("title").text(e.data.timespan);
                            let r = n.append("tspan").text(e.data.timespan),
                                i = this.getAvailableWidth(e, 2);
                            this.getTextLength(n) > i && (n.selectAll("tspan").each(this.truncateDate(n, i)), r.text(r.text() + "…"));
                        }
                    }
                    this.transformOuterText(t, e);
                }
                if (this._configuration.showParentMarriageDates && e.children && e.depth < 5) {
                    let n = dt(t.node().parentNode).attr("id"),
                        r = this.createPathDefinition(n, 4, e),
                        i = t
                            .append("text")
                            .append("textPath")
                            .attr("xlink:href", "#" + r)
                            .attr("startOffset", "25%")
                            .attr("class", "date");
                    this.addMarriageDate(i, e);
                }
            }
            addFirstNames(t, e) {
                let n = 0;
                for (let r of e.data.firstNames) {
                    let i = t.append("tspan").text(r);
                    r === e.data.preferredName && i.attr("class", "preferred"), 0 !== n && i.attr("dx", "0.25em"), ++n;
                }
            }
            addLastNames(t, e, n = 0) {
                let r = 0;
                for (let i of e.data.lastNames) {
                    let e = t.append("tspan").attr("class", "lastName").text(i);
                    0 !== r && e.attr("dx", "0.25em"), 0 !== n && e.attr("dx", n + "em"), ++r;
                }
            }
            addAlternativeNames(t, e, n = 0) {
                let r = 0;
                for (let n of e.data.alternativeNames) {
                    let i = t.append("tspan").text(n);
                    0 !== r && i.attr("dx", (e.data.isAltRtl ? -0.25 : 0.25) + "em"), ++r;
                }
            }
            addMarriageDate(t, e) {
                e.data.parentMarriage && t.append("tspan").text("⚭ " + e.data.parentMarriage);
            }
            truncateNames(t, e, n) {
                let r = this.getAvailableWidth(e, n);
                t
                    .selectAll("tspan:not(.preferred):not(.lastName)")
                    .nodes()
                    .reverse()
                    .forEach((e) => {
                        dt(e).each(this.truncateText(t, r));
                    }),
                    t.selectAll("tspan.preferred").each(this.truncateText(t, r)),
                    t.selectAll("tspan.lastName").each(this.truncateText(t, r));
            }
            truncateText(t, e) {
                let n = this;
                return function () {
                    let r = n.getTextLength(t),
                        i = dt(this),
                        a = i.text().split(/\s+/);
                    for (let o = a.length - 1; o >= 0; --o) r > e && ((a[o] = a[o].slice(0, 1) + "."), i.text(a.join(" ")), (r = n.getTextLength(t)));
                };
            }
            truncateDate(t, e) {
                let n = this;
                return function () {
                    let r = n.getTextLength(t),
                        i = dt(this),
                        a = i.text();
                    for (; r > e && a.length > 1; ) (a = a.slice(0, -1).trim()), i.text(a), (r = n.getTextLength(t));
                    "." === a[a.length - 1] && i.text(a.slice(0, -1).trim());
                };
            }
            getTextLength(t) {
                let e = 0;
                return (
                    t.selectAll("tspan").each(function () {
                        e += this.getComputedTextLength();
                    }),
                    e
                );
            }
            isInnerLabel(t) {
                return t.depth > 0 && t.depth <= this._configuration.numberOfInnerCircles;
            }
            createPathDefinition(t, e, n) {
                let r = "path-" + t + "-" + e;
                if (
                    this._svg.defs
                        .get()
                        .select("path#" + r)
                        .node()
                )
                    return r;
                let i = this.isPositionFlipped(n.depth, n.x0, n.x1),
                    a = this._geometry.startAngle(n.depth, n.x0),
                    o = this._geometry.endAngle(n.depth, n.x1),
                    s = this._geometry.relativeRadius(n.depth, this.getTextOffset(i, e));
                this._configuration.showParentMarriageDates && 4 === e && n.depth < 1 && ((a = this._geometry.calcAngle(n.x0)), (o = this._geometry.calcAngle(n.x1)));
                let u = si()
                    .startAngle(i ? o : a)
                    .endAngle(i ? a : o)
                    .innerRadius(s)
                    .outerRadius(s);
                return u.padAngle(this._configuration.padAngle).padRadius(this._configuration.padRadius).cornerRadius(this._configuration.cornerRadius), this._svg.defs.get().append("path").attr("id", r).attr("d", u), r;
            }
            isPositionFlipped(t, e, n) {
                if (360 !== this._configuration.fanDegree || t <= 1) return !1;
                const r = this._geometry.startAngle(t, e),
                    i = this._geometry.endAngle(t, n);
                return (r >= 90 * Mi && i <= 180 * Mi) || (r >= -180 * Mi && i <= -90 * Mi);
            }
            getTextOffset(t, e) {
                return t ? [23, 42, 61, 84, 125][e] : [73, 54, 35, 12, 120][e];
            }
            getAvailableWidth(t, e) {
                if (t.depth > this._configuration.numberOfInnerCircles) return this._configuration.outerArcHeight - 2 * this._configuration.textPadding - this._configuration.circlePadding;
                let n = 2 * this._configuration.centerCircleRadius - 0.15 * this._configuration.centerCircleRadius;
                if (t.depth >= 1) {
                    let r = this.isPositionFlipped(t.depth, t.x0, t.x1);
                    n = this._geometry.arcLength(t, this.getTextOffset(r, e));
                }
                return n - 2 * this._configuration.textPadding - this._configuration.padDistance / 2;
            }
            transformOuterText(t, e) {
                let n = this,
                    r = t.selectAll("text"),
                    i = r.size(),
                    a = 1;
                switch (e.depth) {
                    case 0:
                    case 5:
                        a = 1.5;
                        break;
                    case 1:
                        a = 6.5;
                        break;
                    case 2:
                        a = 3.5;
                        break;
                    case 3:
                        a = 2.2;
                        break;
                    case 4:
                        a = 1.9;
                        break;
                    case 6:
                        a = 0.5;
                }
                let o = hr()
                    .domain([0, i - 1])
                    .range([-a, a]);
                r.each(function (t, r) {
                    const i = (o(r) * n._configuration.fontScale) / 100;
                    0 === e.depth
                        ? dt(this).attr("dy", 14 * i + 7 + "px")
                        : dt(this).attr("transform", function () {
                              let t = e.x1 - e.x0,
                                  r = n._geometry.scale(e.x0 + t / 2) * Ai,
                                  a = r - i * (r > 0 ? -1 : 1),
                                  o = n._geometry.centerRadius(e.depth) - n._configuration.colorArcWidth / 2;
                              return r > 0 ? (a -= 90) : ((o = -o), (a += 90)), "rotate(" + a + ") translate(" + o + ")";
                          });
                });
            }
        }
        class Ei {
            constructor(t, e, n, r) {
                (this._svg = t), (this._configuration = e), (this._geometry = new ki(this._configuration)), this.init(n, r);
            }
            init(t, e) {
                if (
                    (t.classed("new") && this._configuration.hideEmptySegments
                        ? this.addArcToPerson(t, e)
                        : t.classed("new") || t.classed("update") || t.classed("remove") || ("" === e.data.xref && this._configuration.hideEmptySegments) || this.addArcToPerson(t, e),
                    "" !== e.data.xref)
                ) {
                    this.addTitleToPerson(t, e.data.name);
                    let n = new Si(this._svg, this._configuration),
                        r = this.addLabelToPerson(t, e);
                    n.createLabels(r, e),
                        this.addColorGroup(t, e),
                        t
                            .on("contextmenu", (t, e) => {
                                
                                this._svg.div.property("active")
                                    ? (this._svg.div.transition().duration(200).style("opacity", 0), this._svg.div.property("active", !1), t.preventDefault())
                                    : (this._svg.div.property("active", !0), this.setTooltipHtml(e), t.preventDefault());
                            })
                            .on("mouseenter", (t, e) => {
                                "" === e.data.xref && this._svg.div.style("opacity", 0), this.setTooltipHtml(e);
                            })
                            .on("mouseleave", (t, e) => {
                                // change by me
                                // add this line 4047
                                this._svg.div.transition().duration(200).style("opacity", 0);
                                "" === e.data.xref && this._svg.div.style("opacity", 0);
                            })
                            .on("mousemove", (t, e) => {
                                this._svg.div.style("left", t.pageX + "px").style("top", t.pageY - 30 + "px");
                            })
                            .on("mouseover", function (e, n) {
                                const r = t.nodes(),
                                    i = r.indexOf(this);
                                dt(r[i]).classed("hover", !0).raise();
                            })
                            .on("mouseout", function (e, n) {
                                const r = t.nodes(),
                                    i = r.indexOf(this);
                                dt(r[i]).classed("hover", !1);
                            });
                }
            }
            // change by me
            // delete this._svg.div.property("active") && in the start of lien 4091
            setTooltipHtml(t) {
                var tmmp_image = ""
                if(t.data.sex=="M"){
                    tmmp_image="male.png";
                }
                else{
                    tmmp_image="female.png";
                }
                
                if ("" === t.data.xref) return;
                
                let e = "";
                this._configuration.showImages &&
                    (t.data.image
                        ? ((e = '<div class="image">'), (e += '<img src="/storage/portraits/' + t.data.image + '" alt="'+t.data.sex+'" />'), (e += "</div>"))
                        : this._configuration.showSilhouettes && ((e = '<div class="image">'),(e += '<img src="/storage/portraits/' + tmmp_image + '" alt="'+t.data.sex+'" />'), (e += "</div>")));
                const n = t.data.birth || t.data.marriage || t.data.death;
                if(t.data.sex == null){
                    e="";
                }
                this._svg.div
                    .html(
                        e +
                            '<div class="text"><div class="name">' +
                            t.data.name +
                            "</div>" +
                            (n
                                ? "<table>" +
                                  (t.data.birth ? '<tr class="date"><th>Birth</th><td>' + t.data.birth + "</td></tr>" : "") +
                                  (t.data.marriage ? '<tr class="date"><th>⚭</th><td>' + t.data.marriage + "</td></tr>" : "") +
                                  (t.data.death ? '<tr class="date"><th>Death</th><td>' + t.data.death + "</td></tr>" : "") +
                                  "</table>"
                                : "") +
                            "</div>"
                    )
                    .style("left", event.pageX + "px")
                    .style("top", event.pageY - 30 + "px"),
                    
                    this._svg.div.transition().duration(200).style("opacity", 1);
                    
            }
            addColorGroup(t, e) {
                let n = si()
                    .startAngle(this._geometry.startAngle(e.depth, e.x0))
                    .endAngle(this._geometry.endAngle(e.depth, e.x1))
                    .innerRadius(this._geometry.outerRadius(e.depth) - this._configuration.colorArcWidth)
                    .outerRadius(this._geometry.outerRadius(e.depth) + 1);
                n.padAngle(this._configuration.padAngle).padRadius(this._configuration.padRadius);
                let r = t
                    .append("g")
                    .attr("class", "color")
                    .append("path")
                    .attr("fill", () => (this._configuration.showColorGradients ? (e.depth ? "url(#grad-" + e.data.id + ")" : "rgb(225, 225, 225)") : null))
                    .attr("d", n);
                this._configuration.showColorGradients || r.attr("class", e.data.sex === fi ? "female" : e.data.sex === ci ? "male" : "unknown");
            }
            addArcToPerson(t, e) {
                let n = si().startAngle(this._geometry.startAngle(e.depth, e.x0)).endAngle(this._geometry.endAngle(e.depth, e.x1)).innerRadius(this._geometry.innerRadius(e.depth)).outerRadius(this._geometry.outerRadius(e.depth));
                n.padAngle(this._configuration.padAngle).padRadius(this._configuration.padRadius).cornerRadius(this._configuration.cornerRadius);
                let r = t.append("g").attr("class", "arc").append("path").attr("d", n);
                t.classed("new") && r.style("opacity", 1e-6);
            }
            addTitleToPerson(t, e) {
                t.insert("title", ":first-child").text(e);
            }
            addLabelToPerson(t, e) {
                return t
                    .append("g")
                    .attr("class", "wt-chart-box-name name")
                    .style("font-size", this.getFontSize(e) + "px");
            }
            getFontSize(t) {
                let e = this._configuration.fontSize;
                return t.depth >= this._configuration.numberOfInnerCircles + 1 && (e += 1), ((e - t.depth) * this._configuration.fontScale) / 100;
            }
        }
        class $i {
            constructor(t, e) {
                (this._svg = t), (this._configuration = e);
            }
            init(t) {
                if (t.depth < 1) return;
                if (1 === t.depth) {
                    let e = [64, 143, 222],
                        n = [161, 219, 117];
                    t.data.sex === fi && ((e = [218, 102, 13]), (n = [235, 201, 33])), (t.data.colors = [e, n]);
                } else {
                    
                    let e = [
                        Math.ceil((t.parent.data.colors[0][0] + t.parent.data.colors[1][0]) / 2),
                        Math.ceil((t.parent.data.colors[0][1] + t.parent.data.colors[1][1]) / 2),
                        Math.ceil((t.parent.data.colors[0][2] + t.parent.data.colors[1][2]) / 2),
                    ];

                    t.data.sex === ci && (t.data.colors = [t.parent.data.colors[0], e]), t.data.sex === fi && (t.data.colors = [e, t.parent.data.colors[1]]);
                }

                let e = this._svg.defs
                    .get()
                    .append("svg:linearGradient")
                    .attr("id", "grad-" + t.data.id);
                e
                    .append("svg:stop")
                    .attr("offset", "0%")
                    .attr("stop-color", "rgb(" + t.data.colors[0].join(",") + ")"),
                    e
                        .append("svg:stop")
                        .attr("offset", "100%")
                        .attr("stop-color", "rgb(" + t.data.colors[1].join(",") + ")");
            }
        }
        class Pi {
            constructor(t, e, n) {
                (this._svg = t), (this._configuration = e), (this._hierarchy = n);
            }
            update(t, e) {
                
                let n = this;
                this._svg.get().selectAll("g.person").classed("hover", !1).on("click", null).on("mouseover", null).on("mouseout", null),
                    (function (t, e) {
                        return fetch(t, e).then(li);
                    })(t).then((t) => {
                        this._hierarchy.init(t),
                            this._svg
                                .get()
                                .selectAll("g.person")
                                .data(this._hierarchy.nodes, (t) => t.data.id)
                                .each(function (t) {
                                    let e = "" === t.data.xref,
                                        r = dt(this);
                                    r
                                        .classed("remove", e)
                                        .classed("update", !e && r.classed("available"))
                                        .classed("new", !e && !r.classed("available")),
                                        r.classed("new") || r.selectAll("g.name, g.color, title").classed("old", !0),
                                        new Ei(n._svg, n._configuration, r, t);
                                }),
                            this._svg.get().selectAll("g.person:not(.remove)").selectAll("g.name:not(.old), g.color:not(.old)").style("opacity", 1e-6);
                        let r = Mn()
                            .duration(this._configuration.updateDuration)
                            .call(this.endAll, () => this.updateDone(e));
                        this._svg
                            .get()
                            .selectAll("g.person.remove g.arc path")
                            .transition(r)
                            .style("fill", () => (this._configuration.hideEmptySegments ? null : "rgb(235, 235, 235)"))
                            .style("opacity", () => (this._configuration.hideEmptySegments ? 1e-6 : null)),
                            this._svg
                                .get()
                                .selectAll("g.person.new g.arc path")
                                .transition(r)
                                .style("fill", "rgb(250, 250, 250)")
                                .style("opacity", () => (this._configuration.hideEmptySegments ? 1 : null)),
                            this._svg.get().selectAll("g.person.update, g.person.remove").selectAll("g.name.old, g.color.old").transition(r).style("opacity", 1e-6),
                            this._svg.get().selectAll("g.person:not(.remove)").selectAll("g.name:not(.old), g.color:not(.old)").transition(r).style("opacity", 1);
                    });
            }
            updateDone(t) {
                this._configuration.hideEmptySegments && this._svg.get().selectAll("g.person.remove").selectAll("g.arc").remove();
                let e = Rt(() => {
                    this._svg.get().selectAll("g.person g.arc path").attr("style", null), this._svg.get().selectAll("g.person g.name, g.person g.color").style("opacity", null), e.stop();
                }, 10);
                this._svg.get().selectAll("g.person.new, g.person.update, g.person.remove").classed("new", !1).classed("update", !1).classed("remove", !1).selectAll("g.name.old, g.color.old, title.old").remove(),
                    this._svg.get().selectAll("g.person.available").classed("available", !1),
                    t();
            }
            endAll(t, e) {
                let n = 0;
                t.on("start", () => ++n).on("end", () => {
                    --n || e.apply(t);
                });
            }
        }
        class Ti {
            constructor(t, e) {
                (this._configuration = e), (this._parent = t), (this._hierarchy = new di(this._configuration)), (this._data = {});
            }
            get svg() {
                return this._svg;
            }
            updateViewBox() {
                let t = this._svg.visual.node().getBBox(),
                    e = this._parent.node().getBoundingClientRect(),
                    n = Math.max(e.width, t.width),
                    r = Math.max(e.height, t.height, 500),
                    i = (n - t.width) / 2,
                    a = (r - t.height) / 2,
                    o = Math.ceil(t.x - i - 10),
                    s = Math.ceil(t.y - a - 10);
                (n = Math.ceil(n + 20)), (r = Math.ceil(r + 20)), this._svg.get().attr("viewBox", [o, s, n, r]);
            }
            get data() {
                return this._data;
            }
            set data(t) {
                (this._data = t), this._hierarchy.init(this._data);
            }
            draw() {
                this._parent.html(""), (this._svg = new bi(this._parent, this._configuration)), (this._overlay = new pi(this._parent)), this._svg.initEvents(this._overlay);
                let t = this._svg.get().select("g.personGroup"),
                    e = new $i(this._svg, this._configuration),
                    n = this;
                t
                    .selectAll("g.person")
                    .data(this._hierarchy.nodes, (t) => t.data.id)
                    .enter()
                    .append("g")
                    .attr("class", (t) => "person depth-" + t.depth)
                    .attr("id", (t) => "person-" + t.data.id),
                    t.selectAll("g.person").each(function (t) {
                        let r = dt(this);
                        n._configuration.showColorGradients && e.init(t), new Ei(n._svg, n._configuration, r, t);
                    }),
                    this.bindClickEventListener(),
                    this.updateViewBox();
            }
            bindClickEventListener() {
                this._svg
                    .get()
                    .select("g.personGroup")
                    .selectAll("g.person")
                    .filter((t) => "" !== t.data.xref)
                    .classed("available", !0)
                    .on("click", this.personClick.bind(this));
            }
            personClick(t, e) {
                // clear form update person
                document.querySelectorAll("#formUpdatePerson input").forEach(box => {
                    box.classList.remove('input_invalid');
                });
                document.querySelectorAll("#formUpdatePerson select").forEach(box => {
                    box.classList.remove('input_invalid');
                });
                document.querySelectorAll('#formUpdatePerson span[id$="_feedback"]').forEach(box => {
                    box.classList.add('d-none');
                });
                document.querySelector("#formUpdatePerson #death_date").disabled = false;
                document.getElementById('formUpdatePerson').reset()
                document.getElementById("portrait_container").innerHTML = ""
                document.getElementById('sex').disabled = false;
                document.getElementById('deletePerson').disabled = false;
                // this is the click event
                var canvas = document.getElementById('offcanvasUpdatePerson')
                var bsOffcanvas = new bootstrap.Offcanvas(canvas)
                console.log(e.data)
                // init data
                document.getElementById('person_id').value = e.data.others.id;
                if(e.data.firstNames != "person"){
                    document.getElementById('firstname').value = e.data.firstNames;
                }
                if(e.data.lastNames != "person"){
                    document.getElementById('lastname').value = e.data.lastNames;
                }
                if(e.data.birth != ""){
                    document.getElementById('birth_date').value = e.data.birth;
                }
                if(e.data.death != ""){

                        document.getElementById('death_date').value = e.data.death;
                    
                    
                }
                
                if(e.data.sex != null){
                    document.getElementById('sex').value = e.data.sex;
                }
                if(e.data.others.root == 0){
                    document.getElementById('sex').disabled = true;
                }
                else{
                    document.getElementById('deletePerson').disabled = true;
                }
                var tmp_image = ""
                if(e.data.sex != null){
                    if(e.data.image == null){
                        if(e.data.sex == 'M'){
                            tmp_image = "male.png"
                        }
                        else{
                            tmp_image = "female.png"
                        }
                        document.getElementById("portrait_container").innerHTML = '<img class="rounded-circle d-block mx-auto" src="/storage/portraits/'+tmp_image+'" class="img-fluid">';
                    }
                    else{
                        document.getElementById("portrait_container").innerHTML = '<img class="rounded-circle d-block mx-auto" src="/storage/portraits/'+e.data.image+'" class="img-fluid">';
                    }
    
                }
                
                document.getElementById("importimagebtn").setAttribute('data-id', e.data.others.id);
                document.getElementById("importimagebtn").setAttribute('data-sex', e.data.sex);
                document.getElementById("importimagebtn").setAttribute('data-image', e.data.image);

                
                bsOffcanvas.show()
                //0 === e.depth ? this.redirectToIndividual(e.data.url) : this.update(e.data.updateUrl);
            }
            redirectToIndividual(t) {
                window.location = t;
            }
            update(t) {
                new Pi(this._svg, this._configuration, this._hierarchy).update(t, () => this.bindClickEventListener());
            }
        }
        (t.FanChart = class {
            constructor(t, e) {
                (this._selector = t),
                    (this._parent = dt(this._selector)),
                    (this._configuration = new hi(e.labels, e.generations, e.fanDegree, e.fontScale, e.hideEmptySegments, e.showColorGradients, e.showParentMarriageDates, e.showImages, e.showSilhouettes, e.rtl, e.innerArcs)),
                    (this._cssFiles = e.cssFiles),
                    (this._chart = new Ti(this._parent, this._configuration)),
                    this.init();
            }
            init() {
                dt("#centerButton").on("click", () => this.center()), dt("#exportPNG").on("click", () => this.exportPNG()), dt("#exportSVG").on("click", () => this.exportSVG());
            }
            center() {
                this._chart.svg.get().transition().duration(750).call(this._chart.svg.zoom.get().transform, mr);
            }
            get configuration() {
                return this._configuration;
            }
            draw(t) {
                (this._chart.data = t), this._chart.draw();
            }
            exportPNG(format = null) {
                if(format == null){
                    this._chart.svg.export("png").svgToImage(this._chart.svg, "fan-chart.png",format);
                }
                else{
                    this._chart.svg.export("png").svgToImage(this._chart.svg, "fan-chart.png",format);
                }
                
            }
            exportSVG() {
                this._chart.svg.export("svg").svgToImage(this._chart.svg, this._cssFiles, "fan-chart.svg");
            }
        }),
            (t.Storage = class {
                constructor(t) {
                    (this._name = t), (this._storage = JSON.parse(localStorage.getItem(this._name)) || {});
                }
                register(t) {
                    let e = document.getElementById(t),
                        n = this.read(t);
                    n ? (e.type && "checkbox" === e.type ? (e.checked = n) : (e.value = n)) : this.onInput(e),
                        e.addEventListener("input", (t) => {
                            this.onInput(t.target);
                        });
                }
                onInput(t) {
                    t.type && "checkbox" === t.type ? this.write(t.name, t.checked) : this.write(t.name, t.value);
                }
                read(t) {
                    return this._storage[t];
                }
                write(t, e) {
                    (this._storage[t] = e), localStorage.setItem(this._name, JSON.stringify(this._storage));
                }
            });
    }),
    "object" == typeof exports && "undefined" != typeof module ? e(exports) : "function" == typeof define && define.amd ? define(["exports"], e) : e(((t = "undefined" != typeof globalThis ? globalThis : t || self).WebtreesFanChart = {}));
