/**
 * jquery.mask.js
 * @version: v1.7.7
 * @author: Igor Escobar
 *
 * Created by Igor Escobar on 2012-03-10. Please report any bug at http://blog.igorescobar.com
 *
 * Copyright (c) 2012 Igor Escobar http://blog.igorescobar.com
 *
 * The MIT License (http://www.opensource.org/licenses/mit-license.php)
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */
/*jshint laxbreak: true */
/* global define */

// UMD (Universal Module Definition) patterns for JavaScript modules that work everywhere.
// https://github.com/umdjs/umd/blob/master/jqueryPlugin.js
(function (factory) {
    if (typeof define === "function" && define.amd) {
        // AMD. Register as an anonymous module.
        define(["jquery"], factory);
    } else {
        // Browser globals
        factory(window.jQuery || window.Zepto);
    }
}(function ($) {
    "use strict";
    var Mask = function (el, mask, options) {
        var jMask = this, old_value, regexMask;
        el = $(el);

        mask = typeof mask === "function" ? mask(el.val(), undefined, el,  options) : mask;

        var p = {
            getCaret: function () {
                try {
                    var sel,
                        pos = 0,
                        ctrl = el.get(0),
                        dSel = document.selection,
                        cSelStart = ctrl.selectionStart;

                    // IE Support
                    if (dSel && !~navigator.appVersion.indexOf("MSIE 10")) {
                        sel = dSel.createRange();
                        sel.moveStart('character', el.is("input") ? -el.val().length : -el.text().length);
                        pos = sel.text.length;
                    }
                    // Firefox support
                    else if (cSelStart || cSelStart === '0') {
                        pos = cSelStart;
                    }

                    return pos;
                } catch (e) {}
            },
            setCaret: function(pos) {
                try {
                    if (el.is(":focus")) {
                        var range, ctrl = el.get(0);

                        if (ctrl.setSelectionRange) {
                            ctrl.setSelectionRange(pos,pos);
                        } else if (ctrl.createTextRange) {
                            range = ctrl.createTextRange();
                            range.collapse(true);
                            range.moveEnd('character', pos);
                            range.moveStart('character', pos);
                            range.select();
                        }
                    }
                } catch (e) {}
            },
            events: function() {
                el
                .on('keydown.mask', function() {
                    old_value = p.val();
                })
                .on('keyup.mask', p.behaviour)
                .on("paste.mask drop.mask", function() {
                    setTimeout(function() {
                        el.keydown().keyup();
                    }, 100);
                })
                .on("change.mask", function() {
                    el.data("changed", true);
                })
                .on("blur.mask", function(){
                    if (old_value !== el.val() && !el.data("changed")) {
                        el.trigger("change");
                    }
                    el.data("changed", false);
                })
                // clear the value if it not complete the mask
                .on("focusout.mask", function() {
                    if (options.clearIfNotMatch && !regexMask.test(p.val())) {
                       p.val('');
                   }
                });
            },
            getRegexMask: function() {
                var maskChunks = [], translation, pattern, optional, recursive, oRecursive, r;

                for (var i = 0; i < mask.length; i++) {
                    translation = jMask.translation[mask[i]];

                    if (translation) {

                        pattern = translation.pattern.toString().replace(/.{1}$|^.{1}/g, "");
                        optional = translation.optional;
                        recursive = translation.recursive;

                        if (recursive) {
                            maskChunks.push(mask[i]);
                            oRecursive = {digit: mask[i], pattern: pattern};
                        } else {
                            maskChunks.push(!optional && !recursive ? pattern : (pattern + "?"));
                        }

                    } else {
                        maskChunks.push(mask[i].replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&'));
                    }
                }

                r = maskChunks.join("");

                if (oRecursive) {
                    r = r.replace(new RegExp("(" + oRecursive.digit + "(.*" + oRecursive.digit + ")?)"), "($1)?")
                         .replace(new RegExp(oRecursive.digit, "g"), oRecursive.pattern);
                }

                return new RegExp(r);
            },
            destroyEvents: function() {
                el.off(['keydown', 'keyup', 'paste', 'drop', 'change', 'blur', 'focusout', 'DOMNodeInserted', ''].join('.mask '))
                .removeData("changeCalled");
            },
            val: function(v) {
                var isInput = el.is('input');
                return arguments.length > 0
                    ? (isInput ? el.val(v) : el.text(v))
                    : (isInput ? el.val() : el.text());
            },
            getMCharsBeforeCount: function(index, onCleanVal) {
                for (var count = 0, i = 0, maskL = mask.length; i < maskL && i < index; i++) {
                    if (!jMask.translation[mask.charAt(i)]) {
                        index = onCleanVal ? index + 1 : index;
                        count++;
                    }
                }
                return count;
            },
            caretPos: function (originalCaretPos, oldLength, newLength, maskDif) {
                var translation = jMask.translation[mask.charAt(Math.min(originalCaretPos - 1, mask.length - 1))];

                return !translation ? p.caretPos(originalCaretPos + 1, oldLength, newLength, maskDif)
                                    : Math.min(originalCaretPos + newLength - oldLength - maskDif, newLength);
            },
            behaviour: function(e) {
                e = e || window.event;
                var keyCode = e.keyCode || e.which;
                if ($.inArray(keyCode, jMask.byPassKeys) === -1) {

                    var caretPos = p.getCaret(),
                        currVal = p.val(),
                        currValL = currVal.length,
                        changeCaret = caretPos < currValL,
                        newVal = p.getMasked(),
                        newValL = newVal.length,
                        maskDif = p.getMCharsBeforeCount(newValL - 1) - p.getMCharsBeforeCount(currValL - 1);

                    if (newVal !== currVal) {
                        p.val(newVal);
                    }

                    // change caret but avoid CTRL+A
                    if (changeCaret && !(keyCode === 65 && e.ctrlKey)) {
                        // Avoid adjusting caret on backspace or delete
                        if (!(keyCode === 8 || keyCode === 46)) {
                            caretPos = p.caretPos(caretPos, currValL, newValL, maskDif);
                        }
                        p.setCaret(caretPos);
                    }

                    return p.callbacks(e);
                }
            },
            getMasked: function (skipMaskChars) {
                var buf = [],
                    value = p.val(),
                    m = 0, maskLen = mask.length,
                    v = 0, valLen = value.length,
                    offset = 1, addMethod = "push",
                    resetPos = -1,
                    lastMaskChar,
                    check;

                if (options.reverse) {
                    addMethod = "unshift";
                    offset = -1;
                    lastMaskChar = 0;
                    m = maskLen - 1;
                    v = valLen - 1;
                    check = function () {
                        return m > -1 && v > -1;
                    };
                } else {
                    lastMaskChar = maskLen - 1;
                    check = function () {
                        return m < maskLen && v < valLen;
                    };
                }

                while (check()) {
                    var maskDigit = mask.charAt(m),
                        valDigit = value.charAt(v),
                        translation = jMask.translation[maskDigit];

                    if (translation) {
                        if (valDigit.match(translation.pattern)) {
                            buf[addMethod](valDigit);
                             if (translation.recursive) {
                                if (resetPos === -1) {
                                    resetPos = m;
                                } else if (m === lastMaskChar) {
                                    m = resetPos - offset;
                                }

                                if (lastMaskChar === resetPos) {
                                    m -= offset;
                                }
                            }
                            m += offset;
                        } else if (translation.optional) {
                            m += offset;
                            v -= offset;
                        }
                        v += offset;
                    } else {
                        if (!skipMaskChars) {
                            buf[addMethod](maskDigit);
                        }

                        if (valDigit === maskDigit) {
                            v += offset;
                        }

                        m += offset;
                    }
                }

                var lastMaskCharDigit = mask.charAt(lastMaskChar);
                if (maskLen === valLen + 1 && !jMask.translation[lastMaskCharDigit]) {
                    buf.push(lastMaskCharDigit);
                }

                return buf.join("");
            },
            callbacks: function (e) {
                var val = p.val(),
                    changed = val !== old_value;
                if (changed === true) {
                    if (typeof options.onChange === "function") {
                        options.onChange(val, e, el, options);
                    }
                }

                if (changed === true && typeof options.onKeyPress === "function") {
                    options.onKeyPress(val, e, el, options);
                }

                if (typeof options.onComplete === "function" && val.length === mask.length) {
                    options.onComplete(val, e, el, options);
                }
            }
        };


        // public methods
        jMask.mask = mask;
        jMask.options = options;
        jMask.remove = function() {
            var caret;
            p.destroyEvents();
            p.val(jMask.getCleanVal()).removeAttr('maxlength');

            caret = p.getCaret();
            p.setCaret(caret - p.getMCharsBeforeCount(caret));
            return el;
        };

        // get value without mask
        jMask.getCleanVal = function() {
           return p.getMasked(true);
        };

       jMask.init = function() {
            options = options || {};

            jMask.byPassKeys = [9, 13, 16, 17, 18, 36, 37, 38, 39, 40, 91];
            jMask.translation = {
                '0': {pattern: /\d/},
                '9': {pattern: /\d/, optional: true},
                '#': {pattern: /\d/, recursive: true},
                'A': {pattern: /[a-zA-Z0-9]/},
                'S': {pattern: /[a-zA-Z]/}
            };

            jMask.translation = $.extend({}, jMask.translation, options.translation);
            jMask = $.extend(true, {}, jMask, options);

            regexMask = p.getRegexMask();

            if (options.maxlength !== false) {
                el.attr('maxlength', mask.length);
            }

            if (options.placeholder) {
                el.attr('placeholder' , options.placeholder);
            }

            el.attr('autocomplete', 'off');
            p.destroyEvents();
            p.events();

            var caret = p.getCaret();

            p.val(p.getMasked());
            p.setCaret(caret + p.getMCharsBeforeCount(caret, true));

        }();

    };

    var watchers = {},
        live = 'DOMNodeInserted.mask',
        HTMLAttributes = function () {
            var input = $(this),
                options = {},
                prefix = "data-mask-";

            if (input.attr(prefix + 'reverse')) {
                options.reverse = true;
            }

            if (input.attr(prefix + 'maxlength') === 'false') {
                options.maxlength = false;
            }

            if (input.attr(prefix + 'clearifnotmatch')) {
                options.clearIfNotMatch = true;
            }

            input.mask(input.attr('data-mask'), options);
        };

    $.fn.mask = function(mask, options) {
        var selector = this.selector,
            maskFunction = function() {
                var maskObject = $(this).data('mask'),
                    stringify = JSON.stringify;

                if (typeof maskObject !== "object" || stringify(maskObject.options) !== stringify(options) || maskObject.mask !== mask) {
                    return $(this).data('mask', new Mask(this, mask, options));
                }
            };

        this.each(maskFunction);

        if (selector && !watchers[selector]) {
            // dynamically added elements.
            watchers[selector] = true;
            setTimeout(function(){
                $(document).on(live, selector, maskFunction);
            }, 500);
        }
    };

    $.fn.unmask = function() {
        try {
            return this.each(function() {
                $(this).data('mask').remove().removeData('mask');
            });
        } catch(e) {};
    };

    $.fn.cleanVal = function() {
        return this.data('mask').getCleanVal();
    };

    // looking for inputs with data-mask attribute
    $('*[data-mask]').each(HTMLAttributes);

    // dynamically added elements with data-mask html notation.
    $(document).on(live, '*[data-mask]', HTMLAttributes);

}));




(function ($) {

    $.fn.tableHeadFixer = function (param) {

        return this.each(function () {
            table.call(this);
        });

        function table() {
            /*
             This function solver z-index problem in corner cell where fix row and column at the same time,
             set corner cells z-index 1 more then other fixed cells
             */
            function setCorner() {
                var table = $(settings.table);

                if (settings.head) {
                    if (settings.left > 0) {
                        var tr = table.find("> thead > tr");

                        tr.each(function (k, row) {
                            solverLeftColspan(row, function (cell) {
                                $(cell).css("z-index", settings['z-index'] + 1);
                            });
                        });
                    }

                    if (settings.right > 0) {
                        var tr = table.find("> thead > tr");

                        tr.each(function (k, row) {
                            solveRightColspan(row, function (cell) {
                                $(cell).css("z-index", settings['z-index'] + 1);
                            });
                        });
                    }
                }

                if (settings.foot) {
                    if (settings.left > 0) {
                        var tr = table.find("> tfoot > tr");

                        tr.each(function (k, row) {
                            solverLeftColspan(row, function (cell) {
                                $(cell).css("z-index", settings['z-index']);
                            });
                        });
                    }

                    if (settings.right > 0) {
                        var tr = table.find("> tfoot > tr");

                        tr.each(function (k, row) {
                            solveRightColspan(row, function (cell) {
                                $(cell).css("z-index", settings['z-index']);
                            });
                        });
                    }
                }
            }

            // Set style of table parent
            function setParent() {
                var parent = $(settings.parent);
                var table = $(settings.table);

                parent.append(table);
                parent
                    .css({
                        'overflow-x': 'auto',
                        'overflow-y': 'auto'
                    });

                parent.scroll(function () {
                    var scrollWidth = parent[0].scrollWidth;
                    var clientWidth = parent[0].clientWidth;
                    var scrollHeight = parent[0].scrollHeight;
                    var clientHeight = parent[0].clientHeight;
                    var top = parent.scrollTop();
                    var left = parent.scrollLeft();

                    if (settings.head)
                        this.find("> thead > tr > *").css("top", top);

                    if (settings.foot)
                        this.find("> tfoot > tr > *").css("bottom", scrollHeight - clientHeight - top);

                    if (settings.left > 0)
                        settings.leftColumns.css("left", left);

                    if (settings.right > 0)
                        settings.rightColumns.css("right", scrollWidth - clientWidth - left);
                }.bind(table));
            }

            // Set table head fixed
            function fixHead() {
                var thead = $(settings.table).find("> thead");
                var tr = thead.find("> tr");
                var cells = thead.find("> tr > *");

                setBackground(cells);
                cells.css({
                    'position': 'relative'
                });
            }

            // Set table foot fixed
            function fixFoot() {
                var tfoot = $(settings.table).find("> tfoot");
                var tr = tfoot.find("> tr");
                var cells = tfoot.find("> tr > *");

                setBackground(cells);
                cells.css({
                    'position': 'relative'
                });
            }

            // Set table left column fixed
            function fixLeft() {
                var table = $(settings.table);

                // var fixColumn = settings.left;

                settings.leftColumns = $();

                var tr = table.find("> thead > tr, > tbody > tr, > tfoot > tr");
                tr.each(function (k, row) {

                    solverLeftColspan(row, function (cell) {
                        settings.leftColumns = settings.leftColumns.add(cell);
                    });
                    // var inc = 1;

                    // for(var i = 1; i <= fixColumn; i = i + inc) {
                    // 	var nth = inc > 1 ? i - 1 : i;

                    // 	var cell = $(row).find("*:nth-child(" + nth + ")");
                    // 	var colspan = cell.prop("colspan");

                    // 	settings.leftColumns = settings.leftColumns.add(cell);

                    // 	inc = colspan;
                    // }
                });

                var column = settings.leftColumns;

                column.each(function (k, cell) {
                    var cell = $(cell);

                    setBackground(cell);
                    cell.css({
                        'position': 'relative'
                    });
                });
            }

            // Set table right column fixed
            function fixRight() {
                var table = $(settings.table);

                var fixColumn = settings.right;

                settings.rightColumns = $();

                var tr_head = table.find('> thead').find("> tr");
                var tr_body = table.find('> tbody').find("> tr");
                var fcell = null;
                tr_head.each(function (k, row) {
                    solveRightColspanHead(row, function (cell) {
                        if (k === 0) {
                            fcell = cell;
                        }
                        settings.rightColumns = settings.rightColumns.add(fcell);
                    });
                });

                tr_body.each(function (k, row) {
                    solveRightColspanBody(row, function (cell) {
                        settings.rightColumns = settings.rightColumns.add(cell);
                    });
                });

                var column = settings.rightColumns;

                column.each(function (k, cell) {
                    var cell = $(cell);

                    setBackground(cell);
                    cell.css({
                        'position': 'relative',
                        'z-index': '9999'
                    });
                });

            }

            // Set fixed cells backgrounds
            function setBackground(elements) {
                elements.each(function (k, element) {
                    var element = $(element);
                    var parent = $(element).parent();

                    var elementBackground = element.css("background-color");
                    elementBackground = (elementBackground == "transparent" || elementBackground == "rgba(0, 0, 0, 0)") ? null : elementBackground;

                    var parentBackground = parent.css("background-color");
                    parentBackground = (parentBackground == "transparent" || parentBackground == "rgba(0, 0, 0, 0)") ? null : parentBackground;

                    var background = parentBackground ? parentBackground : "white";
                    background = elementBackground ? elementBackground : background;

                    element.css("background-color", background);
                });
            }

            function solverLeftColspan(row, action) {
                var fixColumn = settings.left;
                var inc = 1;

                for (var i = 1; i <= fixColumn; i = i + inc) {
                    var nth = inc > 1 ? i - 1 : i;

                    var cell = $(row).find("> *:nth-child(" + nth + ")");
                    var colspan = cell.prop("colspan");

                    if (typeof cell.cellPos() != 'undefined' && cell.cellPos().left < fixColumn) {
                        action(cell);
                    }

                    inc = colspan;
                }
            }

            function solveRightColspanHead(row, action) {
                var fixColumn = settings.right;
                var inc = 1;
                for (var i = 1; i <= fixColumn; i = i + inc) {
                    var nth = inc > 1 ? i - 1 : i;

                    var cell = $(row).find("> *:nth-last-child(" + nth + ")");
                    var colspan = cell.prop("colspan");

                    action(cell);

                    inc = colspan;
                }
            }

            function solveRightColspanBody(row, action) {
                var fixColumn = settings.right;
                var inc = 1;
                for (var i = 1; i <= fixColumn; i = i + inc) {
                    var nth = inc > 1 ? i - 1 : i;

                    var cell = $(row).find("> *:nth-last-child(" + nth + ")");
                    var colspan = cell.prop("colspan");
                    action(cell);
                    inc = colspan;
                }
            }

            var defaults = {
                head: true,
                foot: false,
                left: 0,
                right: 0,
                'z-index': 0
            };

            var settings = $.extend({}, defaults, param);

            settings.table = this;
            settings.parent = $(settings.table).parent();
            setParent();

            if (settings.head == true) {
                fixHead();
            }

            if (settings.foot == true) {
                fixFoot();
            }

            if (settings.left > 0) {
                fixLeft();
            }

            if (settings.right > 0) {
                fixRight();
            }

            setCorner();

            $(settings.parent).trigger("scroll");

            $(window).resize(function () {
                $(settings.parent).trigger("scroll");
            });
        }
    };

})(jQuery);

/*  cellPos jQuery plugin
 ---------------------
 Get visual position of cell in HTML table (or its block like thead).
 Return value is object with "top" and "left" properties set to row and column index of top-left cell corner.
 Example of use:
 $("#myTable tbody td").each(function(){
 $(this).text( $(this).cellPos().top +", "+ $(this).cellPos().left );
 });
 */
(function ($) {
    /* scan individual table and set "cellPos" data in the form { left: x-coord, top: y-coord } */
    function scanTable($table) {
        var m = [];
        $table.children("tr").each(function (y, row) {
            $(row).children("td, th").each(function (x, cell) {
                var $cell = $(cell),
                    cspan = $cell.attr("colspan") | 0,
                    rspan = $cell.attr("rowspan") | 0,
                    tx, ty;
                cspan = cspan ? cspan : 1;
                rspan = rspan ? rspan : 1;
                for (; m[y] && m[y][x]; ++x);  //skip already occupied cells in current row
                for (tx = x; tx < x + cspan; ++tx) {  //mark matrix elements occupied by current cell with true
                    for (ty = y; ty < y + rspan; ++ty) {
                        if (!m[ty]) {  //fill missing rows
                            m[ty] = [];
                        }
                        m[ty][tx] = true;
                    }
                }
                var pos = {top: y, left: x};
                $cell.data("cellPos", pos);
            });
        });
    };

    /* plugin */
    $.fn.cellPos = function (rescan) {
        var $cell = this.first(),
            pos = $cell.data("cellPos");
        if (!pos || rescan) {
            var $table = $cell.closest("table, thead, tbody, tfoot");
            scanTable($table);
        }
        pos = $cell.data("cellPos");
        return pos;
    }
})(jQuery);
