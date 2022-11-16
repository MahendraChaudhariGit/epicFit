/*(function () { function b(n, h, g, t, e, o) { o = o || {}; var c = this, q = [], k = n.set(), s = n.set(), m = n.set(), x = [], z = e.length, A = 0, D = 0, C = 0, f = 9, B = true; function w(I, H, i, K, G, P) { var M = Math.PI / 180, E = I + i * Math.cos(-K * M), p = I + i * Math.cos(-G * M), J = I + i / 2 * Math.cos(-(K + (G - K) / 2) * M), O = H + i * Math.sin(-K * M), N = H + i * Math.sin(-G * M), F = H + i / 2 * Math.sin(-(K + (G - K) / 2) * M), L = ["M", I, H, "L", E, O, "A", i, i, 0, +(Math.abs(G - K) > 180), 1, p, N, "z"]; L.middle = { x: J, y: F }; return L } s.covers = k; if (z == 1) { m.push(n.circle(h, g, t).attr({ fill: c.colors[0], stroke: o.stroke || "#fff", "stroke-width": o.strokewidth == null ? 1 : o.strokewidth })); k.push(n.circle(h, g, t).attr(c.shim)); D = e[0]; e[0] = { value: e[0], order: 0, valueOf: function () { return this.value } }; m[0].middle = { x: h, y: g }; m[0].mangle = 180 } else { for (var y = 0; y < z; y++) { D += e[y]; e[y] = { value: e[y], order: y, valueOf: function () { return this.value } } } e.sort(function (p, i) { return i.value - p.value }); for (y = 0; y < z; y++) { if (B && e[y] * 360 / D <= 1.5) { f = y; B = false } if (y > f) { B = false; e[f].value += e[y]; e[f].others = true; C = e[f].value } } z = Math.min(f + 1, e.length); C && e.splice(z) && (e[f].others = true); for (y = 0; y < z; y++) { var j = A - 360 * e[y] / D / 2; if (!y) { A = 90 - j; j = A - 360 * e[y] / D / 2 } if (o.init) { var l = w(h, g, 1, A, A - 360 * e[y] / D).join(",") } var v = w(h, g, t, A, A -= 360 * e[y] / D); var u = n.path(o.init ? l : v).attr({ fill: o.colors && o.colors[y] || c.colors[y] || "#666", stroke: o.stroke || "#fff", "stroke-width": (o.strokewidth == null ? 1 : o.strokewidth), "stroke-linejoin": "round" }); u.value = e[y]; u.middle = v.middle; u.mangle = j; q.push(u); m.push(u); o.init && u.animate({ path: v.join(",") }, (+o.init - 1) || 1000, ">") } for (y = 0; y < z; y++) { u = n.path(q[y].attr("path")).attr(c.shim); o.href && o.href[y] && u.attr({ href: o.href[y] }); u.attr = function () { }; k.push(u); m.push(u) } } s.hover = function (F, r) { r = r || function () { }; var E = this; for (var p = 0; p < z; p++) { (function (G, H, i) { var I = { sector: G, cover: H, cx: h, cy: g, mx: G.middle.x, my: G.middle.y, mangle: G.mangle, r: t, value: e[i], total: D, label: E.labels && E.labels[i] }; H.mouseover(function () { F.call(I) }).mouseout(function () { r.call(I) }) })(m[p], k[p], p) } return this }; s.each = function (E) { var r = this; for (var p = 0; p < z; p++) { (function (F, G, i) { var H = { sector: F, cover: G, cx: h, cy: g, x: F.middle.x, y: F.middle.y, mangle: F.mangle, r: t, value: e[i], total: D, label: r.labels && r.labels[i] }; E.call(H) })(m[p], k[p], p) } return this }; s.click = function (E) { var r = this; for (var p = 0; p < z; p++) { (function (F, G, i) { var H = { sector: F, cover: G, cx: h, cy: g, mx: F.middle.x, my: F.middle.y, mangle: F.mangle, r: t, value: e[i], total: D, label: r.labels && r.labels[i] }; G.click(function () { E.call(H) }) })(m[p], k[p], p) } return this }; s.inject = function (i) { i.insertBefore(k[0]) }; var d = function (J, E, r, p) { var N = h + t + t / 5, M = g, I = M + 10; J = J || []; p = (p && p.toLowerCase && p.toLowerCase()) || "east"; r = n[r && r.toLowerCase()] || "circle"; s.labels = n.set(); for (var H = 0; H < z; H++) { var O = m[H].attr("fill"), F = e[H].order, G; e[H].others && (J[F] = E || "Others"); J[F] = c.labelise(J[F], e[H], D); s.labels.push(n.set()); s.labels[H].push(n[r](N + 5, I, 5).attr({ fill: O, stroke: "none" })); s.labels[H].push(G = n.text(N + 20, I, J[F] || e[F]).attr(c.txtattr).attr({ fill: o.legendcolor || "#000", "text-anchor": "start" })); k[H].label = s.labels[H]; I += G.getBBox().height * 1.2 } var K = s.labels.getBBox(), L = { east: [0, -K.height / 2], west: [-K.width - 2 * t - 20, -K.height / 2], north: [-t - K.width / 2, -t - K.height - 10], south: [-t - K.width / 2, t + 10] }[p]; s.labels.translate.apply(s.labels, L); s.push(s.labels) }; if (o.legend) { d(o.legend, o.legendothers, o.legendmark, o.legendpos) } s.push(m, k); s.series = m; s.covers = k; return s } var a = function () { }; a.prototype = Raphael.g; b.prototype = new a; Raphael.fn.piechart = function (c, g, f, d, e) { return new b(this, c, g, f, d, e) } })();*/
/*!
 * g.Raphael 0.51 - Charting library, based on Raphaël
 *
 * Copyright (c) 2009-2012 Dmitry Baranovskiy (http://g.raphaeljs.com)
 * Licensed under the MIT (http://www.opensource.org/licenses/mit-license.php) license.
 */

/*
* piechart method on paper
*/
/*\
 * Paper.piechart
 [ method ]
 **
 * Creates a pie chart
 **
 > Parameters
 **
 - cx (number) x coordinate of the chart
 - cy (number) y coordinate of the chart
 - r (integer) radius of the chart
 - values (array) values used to plot
 - opts (object) options for the chart
 o {
 o minPercent (number) minimal percent threshold which will have a slice rendered. Sliced corresponding to data points below this threshold will be collapsed into 1 additional slice. [default `1`]
 o maxSlices (number) a threshold for how many slices should be rendered before collapsing all remaining slices into 1 additional slice (to focus on most important data points). [default `100`]
 o stroke (string) color of the circle stroke in HTML color format [default `"#FFF"`]
 o strokewidth (integer) width of the chart stroke [default `1`]
 o init (boolean) whether or not to show animation when the chart is ready [default `false`]
 o colors (array) colors be used to plot the chart
 o href (array) urls to to set up clicks on chart slices
 o legend (array) array containing strings that will be used in a legend. Other label options work if legend is defined.
 o legendcolor (string) color of text in legend [default `"#000"`]
 o legendothers (string) text that will be used in legend to describe options that are collapsed into 1 slice, because they are too small to render [default `"Others"`]
 o legendmark (string) symbol used as a bullet point in legend that has the same colour as the chart slice [default `"circle"`]
 o legendpos (string) position of the legend on the chart [default `"east"`]. Other options are `"north"`, `"south"`, `"west"`
 o }
 **
 = (object) path element of the popup
 > Usage
 | r.piechart(cx, cy, r, values, opts)
 \*/

(function () {

    function Piechart(paper, cx, cy, r, values, opts) {
        opts = opts || {};

        var chartinst = this,
            sectors = [],
            covers = paper.set(),
            chart = paper.set(),
            series = paper.set(),
            order = [],
            len = values.length,
            angle = 0,
            total = 0,
            others = 0,
            cut = opts.maxSlices || 100,
            minPercent = parseFloat(opts.minPercent) || 1,
            defcut = Boolean(minPercent);

        function sector(cx, cy, r, startAngle, endAngle, fill) {
            var rad = Math.PI / 180,
                x1 = cx + r * Math.cos(-startAngle * rad),
                x2 = cx + r * Math.cos(-endAngle * rad),
                xm = cx + r / 2 * Math.cos(-(startAngle + (endAngle - startAngle) / 2) * rad),
                y1 = cy + r * Math.sin(-startAngle * rad),
                y2 = cy + r * Math.sin(-endAngle * rad),
                ym = cy + r / 2 * Math.sin(-(startAngle + (endAngle - startAngle) / 2) * rad),
                res = [
                    "M", cx, cy,
                    "L", x1, y1,
                    "A", r, r, 0, +(Math.abs(endAngle - startAngle) > 180), 1, x2, y2,
                    "z"
                ];

            res.middle = { x: xm, y: ym };
            return res;
        }

        chart.covers = covers;

        if (len == 1) {
            series.push(paper.circle(cx, cy, r).attr({ fill: opts.colors && opts.colors[0] || chartinst.colors[0], stroke: opts.stroke || "#fff", "stroke-width": opts.strokewidth == null ? 1 : opts.strokewidth }));
            covers.push(paper.circle(cx, cy, r).attr(chartinst.shim));
            total = values[0];
            values[0] = { value: values[0], order: 0, valueOf: function () { return this.value; } };
            opts.href && opts.href[0] && covers[0].attr({ href: opts.href[0] });
            series[0].middle = { x: cx, y: cy };
            series[0].mangle = 180;
        } else {
            for (var i = 0; i < len; i++) {
                total += values[i];
                values[i] = { value: values[i], order: i, valueOf: function () { return this.value; } };
            }

            //values are sorted numerically
            values.sort(function (a, b) {
                return b.value - a.value;
            });

            for (i = 0; i < len; i++) {
                if (defcut && values[i] * 100 / total < minPercent) {
                    cut = i;
                    defcut = false;
                }

                if (i > cut) {
                    defcut = false;
                    values[cut].value += values[i];
                    values[cut].others = true;
                    others = values[cut].value;
                }
            }

            len = Math.min(cut + 1, values.length);
            others && values.splice(len) && (values[cut].others = true);

            for (i = 0; i < len; i++) {
                var mangle = angle - 360 * values[i] / total / 2;

                if (!i) {
                    angle = 90 - mangle;
                    mangle = angle - 360 * values[i] / total / 2;
                }

                if (opts.init) {
                    var ipath = sector(cx, cy, 1, angle, angle - 360 * values[i] / total).join(",");
                }

                var path = sector(cx, cy, r, angle, angle -= 360 * values[i] / total);
                var j = (opts.matchColors && opts.matchColors == true) ? values[i].order : i;
                var p = paper.path(opts.init ? ipath : path).attr({ fill: opts.colors && opts.colors[j] || chartinst.colors[j] || "#666", stroke: opts.stroke || "#fff", "stroke-width": (opts.strokewidth == null ? 1 : opts.strokewidth), "stroke-linejoin": "round" });

                p.value = values[i];
                p.middle = path.middle;
                p.mangle = mangle;
                sectors.push(p);
                series.push(p);
                opts.init && p.animate({ path: path.join(",") }, (+opts.init - 1) || 1000, ">");
            }

            for (i = 0; i < len; i++) {
                p = paper.path(sectors[i].attr("path")).attr(chartinst.shim);
                opts.href && opts.href[i] && p.attr({ href: opts.href[i] });
                p.attr = function () { };
                covers.push(p);
                series.push(p);
            }
        }

        chart.hover = function (fin, fout) {
            fout = fout || function () { };

            var that = this;

            for (var i = 0; i < len; i++) {
                (function (sector, cover, j) {
                    var o = {
                        sector: sector,
                        cover: cover,
                        cx: cx,
                        cy: cy,
                        mx: sector.middle.x,
                        my: sector.middle.y,
                        mangle: sector.mangle,
                        r: r,
                        value: values[j],
                        total: total,
                        label: that.labels && that.labels[j]
                    };
                    cover.mouseover(function () {
                        fin.call(o);
                    }).mouseout(function () {
                        fout.call(o);
                    });
                })(series[i], covers[i], i);
            }
            return this;
        };

        // x: where label could be put
        // y: where label could be put
        // value: value to show
        // total: total number to count %
        chart.each = function (f) {
            var that = this;

            for (var i = 0; i < len; i++) {
                (function (sector, cover, j) {
                    var o = {
                        sector: sector,
                        cover: cover,
                        cx: cx,
                        cy: cy,
                        x: sector.middle.x,
                        y: sector.middle.y,
                        mangle: sector.mangle,
                        r: r,
                        value: values[j],
                        total: total,
                        label: that.labels && that.labels[j]
                    };
                    f.call(o);
                })(series[i], covers[i], i);
            }
            return this;
        };

        chart.click = function (f) {
            var that = this;

            for (var i = 0; i < len; i++) {
                (function (sector, cover, j) {
                    var o = {
                        sector: sector,
                        cover: cover,
                        cx: cx,
                        cy: cy,
                        mx: sector.middle.x,
                        my: sector.middle.y,
                        mangle: sector.mangle,
                        r: r,
                        value: values[j],
                        total: total,
                        label: that.labels && that.labels[j]
                    };
                    cover.click(function () { f.call(o); });
                })(series[i], covers[i], i);
            }
            return this;
        };

        chart.inject = function (element) {
            element.insertBefore(covers[0]);
        };

        var legend = function (labels, otherslabel, mark, dir) {
            var x = cx + r + r / 5,
                y = cy,
                h = y + 10;

            labels = labels || [];
            dir = (dir && dir.toLowerCase && dir.toLowerCase()) || "east";
            mark = paper[mark && mark.toLowerCase()] || "circle";
            chart.labels = paper.set();

            for (var i = 0; i < len; i++) {
                var clr = series[i].attr("fill"),
                    j = values[i].order,
                    txt;

                values[i].others && (labels[j] = otherslabel || "Others");
                labels[j] = chartinst.labelise(labels[j], values[i], total);
                chart.labels.push(paper.set());
                chart.labels[i].push(paper[mark](x + 5, h, 5).attr({ fill: clr, stroke: "none" }));
                chart.labels[i].push(txt = paper.text(x + 20, h, labels[j] || values[j]).attr(chartinst.txtattr).attr({ fill: opts.legendcolor || "#000", "text-anchor": "start" }));
                covers[i].label = chart.labels[i];
                h += txt.getBBox().height * 1.2;
            }

            var bb = chart.labels.getBBox(),
                tr = {
                    east: [0, -bb.height / 2],
                    west: [-bb.width - 2 * r - 20, -bb.height / 2],
                    north: [-r - bb.width / 2, -r - bb.height - 10],
                    south: [-r - bb.width / 2, r + 10]
                }[dir];

            chart.labels.translate.apply(chart.labels, tr);
            chart.push(chart.labels);
        };

        if (opts.legend) {
            legend(opts.legend, opts.legendothers, opts.legendmark, opts.legendpos);
        }

        chart.push(series, covers);
        chart.series = series;
        chart.covers = covers;

        return chart;
    };

    //inheritance
    var F = function () { };
    F.prototype = Raphael.g;
    Piechart.prototype = new F;

    //public
    Raphael.fn.piechart = function (cx, cy, r, values, opts) {
        return new Piechart(this, cx, cy, r, values, opts);
    }

})();