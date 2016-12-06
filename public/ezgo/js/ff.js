(function() {
    "use strict";

    var

        r20 = /%20/g,

        class2type = {},

        fn,

        core_toString = class2type.toString,

        __selector = function(selector, context, results, seed) {
            this.el = {};
            this.els = [];

            this.init(selector, context, results, seed);
        },


        ff = function(selector, context, results, seed) {
            return new __selector(selector, context, results, seed);
        };


    (function(s, class2type) {
        var row, name;

        s = s.split(" ");

        while (name = s.shift()) {
            class2type["[object " + name + "]"] = name.toLowerCase();
        }
    }("Boolean Number String Function Array Date RegExp Object Error", class2type));


    ff.fn = fn = __selector.prototype;


    fn.init = function(selector, context, results, seed) {
        if (ff.isEventObject(selector)) {

            /*
                直接從 Event Object 把 Element 找出來 
            */
            selector = selector || window.event;
            this.el = selector.target || selector.srcElement;
            this.els = [this.el];
        } else if (ff.type(selector) == 'object') {
            this.els = ff.maybeArray(selector) ? selector : [selector];
            this.el = this.els[0] || {};
        } else {
            this.els = ff.find(selector, context, results, seed);
            this.el = this.els[0] || {};
        }
        return this;
    };



    window.__selector = __selector;
    window.ff = ff;

    // functions *********************************************************************************



    ff.emptyFN = new Function();



    ff.id = function() {
        var count, token;

        count = 0;
        token = ff.unique_id(10);

        ff.id = function() {
            count++;
            return token + count;
        };
        return ff.id();
    };



    ff.unique_id = function(num) {
        var
            t = [
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
            ],

            f = []

        ;

        f[0] = t[Math.floor(Math.random() * 52)];

        for (var i = 1; i < num; i++) {
            f[i] = t[Math.floor(Math.random() * 62)];
        }

        return f.join('');
    };



    ff.rand = ff.random = function(min, max) {
        return Math.round(Math.random() * (max - min) + min);
    };



    ff.round = function(num, digit) {
        var x = 1;
        if (digit) {
            x = Math.pow(10, digit);
        }
        return Math.round(num * x) / x;
    };



    ff.find = ff.Sizzle = function(selector, el, results, seed) {
        var els;

        el = el || document;

        els = el.querySelectorAll(selector);

        els = ff.maybeArray(els) ? els : (ff.isEmpty(els) ? [] : [els]);

        if (ff.maybeArray(results)) {
            els = ff.merge(results, els);
        }

        return els;

    };



    //複製object
    ff.clone = function(obj) {
        var copy, i, len, att;
        // Handle the 3 simple types, and null or undefined
        if (null === obj || "object" != typeof obj)
            return obj;

        // Handle Date
        if (obj instanceof Date) {
            copy = new Date();
            copy.setTime(obj.getTime());
            return copy;
        }

        // Handle Array
        if (obj instanceof Array) {
            copy = [];
            for (i = 0, len = obj.length; i < len; ++i) {
                copy[i] = this.clone(obj[i]);
            }
            return copy;
        }

        // html elements
        if (obj.cloneNode) {
            return obj.cloneNode(true);
        }

        // Handle Object
        if (obj instanceof Object) {
            copy = {};
            for (attr in obj) {
                if (obj.hasOwnProperty(attr))
                    copy[attr] = this.clone(obj[attr]);
            }
            return copy;
        }
        throw new Error("Unable to copy obj! Its type isn't supported.");
    };



    /*
        回傳在陣列的位置 
    */
    ff.arrayIndexOf = function(value, arr, fromIndex) {
        var len,
            core_indexOf = Array.prototype.indexOf,
            i = fromIndex;

        if (arr) {
            if (core_indexOf) {
                return core_indexOf.call(arr, value, i);
            }

            len = arr.length;
            i = i ? i < 0 ? Math.max(0, len + i) : i : 0;

            for (; i < len; i++) {
                // Skip accessing in sparse arrays
                if (i in arr && arr[i] === value) {
                    return i;
                }
            }
        }

        return -1;
    };



    /*
        從陣列移除item
        remove item from array
    */
    ff.removeFromArray = function(find, arr) {

        var index = ff.arrayIndexOf(find, arr);

        if (index !== -1) arr.splice(index, 1);

        return arr;
    };



    /*
        回傳bool 
    */
    ff.in_array = function(find, myArray) {
        if (myArray.length === 0)
            return false;
        for (var i = 0; i < myArray.length; i++) {
            if (myArray[i] == find)
                return true;
        }
        return false;
    };



    // 自動左邊補0
    ff.padLeft = function(str, lenght, item) {

        if (typeof item === 'undefined') item = '0';

        if (typeof(str) == 'number') {
            str = str.toString();
        }
        if (str.length >= lenght)
            return str;
        else
            return this.padLeft("0" + str, lenght);
    };

    //自動右邊補0
    ff.padRight = function(str, lenght, item) {

        if (typeof item === 'undefined') item = '0';

        if (typeof(str) == 'number') {
            str = str.toString();
        }
        if (str.length >= lenght)
            return str;
        else
            return this.padRight(str + "0", lenght);
    };



    //取得絕對座標
    ff.getPos = function(elmt) {

        if (typeof elmt == 'string') elmt = document.getElementById(elmt);

        var x = 0;
        var y = 0;
        //繞行 offsetParents
        for (var e = elmt; e; e = e.offsetParent) {
            //把 offsetLeft 值加總
            x += e.offsetLeft;
            //把 offsetTop 值加總
            y += e.offsetTop;
        }
        //繞行至 document.body
        for (e = elmt.parentNode; e && e != document.body; e = e.parentNode) {
            //減去捲軸值
            if (e.scrollLeft)
                x -= e.scrollLeft;
            //減去捲軸值
            if (e.scrollTop)
                y -= e.scrollTop;
        }
        return {
            x: x,
            y: y
        };
    };



    ff.getCookie = function(prop) {
        prop = prop + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i].trim();
            if (c.indexOf(prop) === 0) return c.substring(prop.length, c.length);
        }
        return "";
    };



    /*
       global => 設在 /  全站都抓的到
    */
    ff.setCookie = function(prop, value, second, Path) {
        var expire;
        expire = new Date();
        expire.setTime(new Date().getTime() + second * 1000);
        expire = expire.toGMTString();

        if (typeof Path === 'undefined') Path = '/';

        document.cookie = prop + "=" + escape(value) + ";Path=" + Path + ";" + "expires=" + expire;

    };



    ff.removeCookie = ff.deleteCookie = function(name, global) {
        ff.setCookie(name, '', -1, (global ? '/' : ''));
        //document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    };



    ff.cookie = function(name, value, day, global) {
        if (ff.type(value) == 'undefined') {
            return ff.getCookie(name);
        } else {
            day = day || 1;

            if (ff.isNumeric(day)) {
                day = day * 86400;
            } else if (day.indexOf('hour') !== -1) {
                day = day.replace('hour', '') * 3600;
            } else if (day.indexOf('minute') !== -1) {
                day = day.replace('minute', '') * 60;
            } else if (day.indexOf('second') !== -1) {
                day = day.replace('second', '') * 1;
            } else if (day.indexOf('month') !== -1) {
                day = day.replace('month', '') * 86400 * 30;
            } else if (day.indexOf('day') !== -1) {
                day = day.replace('day', '') * 86400;
            }
            ff.setCookie(name, value, day, (global ? '/' : ''));
        }
    };



    //滑鼠座標
    ff.mouseCoords = function(e) {
        e = e || window.event;
        if (e.pageX || e.pageY) {
            return {
                x: e.pageX,
                y: e.pageY
            };
        }
        return {
            x: e.clientX + document.body.scrollLeft - document.body.clientLeft,
            y: e.clientY + document.body.scrollTop - document.body.clientTop
        };
    };



    /*
        判斷是 事件 物件   
        [object KeyboardEvent]
        [object mouseEvent]
        [object pointerEvent] IE
    */
    ff.isEventObject = function(e) {
        return (Object.prototype.toString.call(e).toUpperCase().indexOf('EVENT') != -1) ? true : false;
    }

    ff.isNumeric = function(obj) {
        return !isNaN(parseFloat(obj)) && isFinite(obj);
    };

    ff.isEmptyObject = function(obj) {
        var name;
        for (name in obj) {
            return false;
        }
        return true;
    };

    ff.param = ff.object_to_querystring = function(a) {

        var s = [],

            add = function(key, value) {
                value = typeof value === 'function' ? value() : (value === null ? "" : value);
                s[s.length] = encodeURIComponent(key) + "=" + encodeURIComponent(value);
                //s[ s.length ] = key + "=" + value;
            },

            prefix,

            i,

            imax,

            isHtmlElement = function(el) {
                return (el && el.tagName && el.nodeType) ? true : false;
            },

            buildParams = function(prefix, obj, add) {

                if (isHtmlElement(obj) || typeof obj === 'function') {

                    return Object.prototype.toString.call(obj);
                }

                var name, i, imax, v;
                //debugger;
                if (Object.prototype.toString.call(obj) === '[object Array]') {
                    for (i = 0, imax = obj.length; i < imax; i++) {
                        v = obj[i];
                        buildParams(prefix + "[" + (typeof v === "object" ? i : "") + "]", v, add);
                    }
                } else if (typeof obj === "object") {
                    for (name in obj) {
                        if (obj.hasOwnProperty(name)) {
                            buildParams(prefix + "[" + name + "]", obj[name], add);
                        }
                    }
                } else {
                    add(prefix, obj);
                }
            },

            r20 = /%20/g;

        if (Object.prototype.toString.call(a) === '[object Array]') {
            // Serialize the form elements
            for (i = 0, imax = a.length; i < imax; i++) {
                add('p[' + i + ']', a[i]);
            }

        } else {
            for (prefix in a) {
                buildParams(prefix, a[prefix], add);
            }
        }
        //console.log(s.join( "&" ).replace( r20, "+" ));
        return s.join("&").replace(r20, "+");
    };



    /*
        合併陣列 
        ff.merge([1,2,3],[4,5,6],[7,8,9,10,11],12);
        [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
        
        也會合併 疑似陣列 
    */
    ff.merge = function() {

        var i, j, imax, jmax, r = [],
            arr, a;

        for (i = 0, imax = arguments.length; i < imax; i++) {

            arr = arguments[i];

            if (this.type(arr) == 'array') {

                r = r.concat(arr);

            } else if (this.canbeArray(arr)) {
                /*
                    疑似陣列 手工合併 !
                */
                j = 0;
                for (j = 0, jmax = arr.length; j < jmax; j++) {
                    a = arr[j];
                    r[r.length] = a;
                }

            } else if (ff.type(arr) != 'undefined') {
                r[r.length] = arr;
            }
        }
        return r;
    };



    /*
        判斷是 html 元素
    */
    ff.isElem = ff.isHtmlElement = function(el) {
        /*
            2種方法都可 
        return (Object.prototype.toString.call(el).toUpperCase().indexOf('HTML') != -1) ? true : false;
        */
        return (el && el.tagName && el.nodeType) ? true : false;

    };



    /*
        判斷是 事件 物件   
        [object KeyboardEvent]
        [object mouseEvent]
        [object pointerEvent] IE
    */
    ff.isEventObject = function(e) {
        return (Object.prototype.toString.call(e).toUpperCase().indexOf('EVENT') != -1) ? true : false;
    };



    ff.isNumeric = function(obj) {
        return !isNaN(parseFloat(obj)) && isFinite(obj);
    };



    ff.isEmptyObject = function(obj) {
        var name;
        for (name in obj) {
            return false;
        }
        return true;
    };



    /*
        querySelector 回傳的東西
        [object NodeList] 
        [object HTMLCollection]
        都可以當陣列只用 所以判定為 true
        
    */
    ff.maybeArray = function(arr) {
        var
            type;

        if (ff.isEmpty(arr) || ff.isWindow(arr) || !('length' in arr)) return false;

        type = Object.prototype.toString.call(arr);

        if (type === '[object NodeList]' || type === '[object HTMLCollection]') return true;

        if (arr && ff.isNumeric(arr.length) && !this.isElem(arr)) return true;

        return false;
    };



    ff.is_array = ff.isArray = Array.isArray || function(obj) {
        //return ff.type(obj) === "array";

        return Object.prototype.toString.call(obj) === '[object Array]';
    };



    ff.isFunction = function(obj) {
        return ff.type(obj) === "function";
    };



    ff.isJSON = ff.is_json_string = function(str) {
        //JSON RegExp
        var
            rvalidchars = /^[\],:{}\s]*$/,
            rvalidbraces = /(?:^|:|,)(?:\s*\[)+/g,
            rvalidescape = /\\(?:["\\\/bfnrt]|u[\da-fA-F]{4})/g,
            rvalidtokens = /"[^"\\\r\n]*"|true|false|null|-?(?:\d+\.|)\d+(?:[eE][+-]?\d+|)/g;

        if (rvalidchars.test(str.replace(rvalidescape, "@")
                .replace(rvalidtokens, "]")
                .replace(rvalidbraces, ""))) {
            return true;
        }
        return false;
    };



    ff.isEmpty = function(obj) {
        return (typeof obj === 'undefined' || obj === null || obj === '') ? true : false;
    };



    ff.type = function(obj) {

        if (obj === null) {
            return String(obj);
        }

        return typeof obj === "object" || typeof obj === "function" ?
            class2type[core_toString.call(obj)] || "object" : typeof obj;
    };



    ff.isWindow = function(obj) {
        return obj != null && obj == obj.window;
    };

    /*
        去頭尾空白 
    */
    ff.trim = function(text) {
        //var rtrim = /^\s+|\s+$/g;
        return text.replace(/^\s+|\s+$/g, '');
    };


    ff.loadcss = function(src) {
        var link;

        link = document.createElement('link');
        link.type = 'text/css';
        link.rel = 'stylesheet';
        link.href = src;
        link.media = 'screen';

        document.getElementsByTagName('head')[0].appendChild(link);


        return ff.Promise(function(resolve, reject) {
            ff(link).bind('error', resolve);
            ff(link).bind('load', resolve);
        });
    };



    ff.loadjs = function(src) {
        var fjs = document.getElementsByTagName('script')[0],
            js = document.createElement('script');

        js.type = 'text/javascript';

        js.async = true;

        js.src = src;

        fjs.parentNode.insertBefore(js, fjs);

        return ff.Promise(function(resolve, reject) {
            ff(js).bind('error', resolve);

            ff(js).bind('load', resolve);
        });
    };



    ff.matchSelector = function(el, selector) {

        var div = document.createElement('div');

        var clone = el.cloneNode(false);

        div.appendChild(clone);

        var find = ff(selector, div).el;

        if (find === el) return true;

        return false;

    };



    /*
        載入lib
    */
    ff.load = function(lib) {
        ff.loadjs(ff.scriptUrl + 'src/' + lib + '.js');
        return ff;
    };



    /*
        把文字 轉成 element
    */
    ff.html_decode = function(text) {

        if (this.type(text) != 'string') return [];

        var div, rs = [],
            child, i;

        div = document.createElement('div');
        div.innerHTML = text;

        i = 0;
        while (child = div.childNodes[i++]) {
            rs[rs.length] = child;
        }
        return rs;
    };

    ff.xml_decode = function(data) {
        var xml, tmp;
        if (!data || typeof data !== "string") {
            return null;
        }
        try {
            if (window.DOMParser) { // Standard
                tmp = new DOMParser();
                xml = tmp.parseFromString(data, "text/xml");
            } else { // IE
                xml = new ActiveXObject("Microsoft.XMLDOM");
                xml.async = "false";
                xml.loadXML(data);
            }
        } catch (e) {
            xml = undefined;
        }
        if (!xml || !xml.documentElement || xml.getElementsByTagName("parsererror").length) {
            ff.error("Invalid XML: " + data);
        }
        return xml;
    };



}());



/*
    Promise *******************************************
*/

(function(window, document, undefined) {
    var _Promise;

    if (window.Promise) {
        //window._Promise = Promise;return;
    }

    _Promise = function(fn) {
        var self = this;

        this.status = 'pending';

        var resolve = function(value) {
            self.resolve.apply(self, [value]);
        };

        var reject = function(error) {
            self.reject.apply(self, [error]);
        };

        fn(resolve, reject);
    };

    _Promise.prototype.isPromise = function(o) {
        o = o || {};
        if (o.isPromise) return true;
        else return false;
    };

    _Promise.prototype.then = function(resolve1, reject1) {

        var self = this;

        self.resolve1 = resolve1;
        self.reject1 = reject1;

        return new _Promise(function(resolve2, reject2) {
            self.resolve2 = resolve2;
            self.reject2 = reject2;
            if (self.status !== 'pending') self.done();
        });

    };

    _Promise.prototype.done = function() {

        if (!this.resolve1 && !this.reject1) return;
        var self = this,
            result;

        if (self.status === 'resolved') {
            result = self.resolve1(self.value);
        } else if (self.status === 'rejected') {
            if (typeof self.reject1 === 'function') result = self.reject1(self.value);
        }

        if (self.isPromise(result)) {
            result.then(self.resolve2, self.reject2);
        } else {
            if (typeof self.resolve2 === 'function') self.resolve2(result);
        }

    };

    _Promise.prototype.resolve = function(value) {
        this.status = 'resolved';
        this.value = value;
        this.done();
    };

    _Promise.prototype.reject = function(error) {
        this.status = 'rejected';
        this.value = error;
        this.done();
    };



    _Promise.resolve = function(value) {
        return new _Promise(function(resolve) {
            resolve(value);
        });
    };

    _Promise.reject = function(value) {
        return new _Promise(function(resolve, reject) {
            reject(value);
        });
    };

    _Promise.all = function(ps) {

        //if( !is_array(ps) ) ps = [];

        return new _Promise(function(resolve, reject) {

            var wait = ps.length,
                rejected = false;

            var fn = function(response) {
                var i, imax, p, vs;
                wait--;
                //console.log(wait);
                if (wait === 0) {
                    vs = [];
                    for (i = 0, imax = ps.length; i < imax; i++) {
                        p = ps[i];
                        vs[vs.length] = p.value;
                    }
                    resolve(vs);
                }
            };

            ps.map(function(p) {
                p.then(fn, function(err) {
                    if (rejected === false) {
                        rejected = true;
                        reject(err);
                    }
                });
            });

        });
    };

    var is_array = Array.isArray || function(arg) {
        return Object.prototype.toString.call(arg) === '[object Array]';
    };

    window._Promise = _Promise;

}(window, document, undefined));


//  ff.Promise *******************************************
(function(ff, window, document, undefined) {

    //ff.Promise = _Promise;
    ff.Promise = function(work) {
        return new _Promise(work);
    };

    ff.Promise.all = function(ps) {
        return _Promise.all(ps);
    };

    ff.Promise.stop = function() {
        return ff.Promise(function(resolve, reject) {

        });
    };

    //window.Promise=_Promise;
}(ff, window, document, undefined));



// Array.prototype *******************************************
(function(ff, window, document, undefined) {

    // Production steps of ECMA-262, Edition 5, 15.4.4.18
    // Reference: http://es5.github.io/#x15.4.4.18
    if (!Array.prototype.forEach) {

        Array.prototype.forEach = function(callback, thisArg) {

            var T, k;

            if (this == null) {
                throw new TypeError(' this is null or not defined');
            }

            // 1. Let O be the result of calling ToObject passing the |this| value as the argument.
            var O = Object(this);

            // 2. Let lenValue be the result of calling the Get internal method of O with the argument "length".
            // 3. Let len be ToUint32(lenValue).
            var len = O.length >>> 0;

            // 4. If IsCallable(callback) is false, throw a TypeError exception.
            // See: http://es5.github.com/#x9.11
            if (typeof callback !== "function") {
                throw new TypeError(callback + ' is not a function');
            }

            // 5. If thisArg was supplied, let T be thisArg; else let T be undefined.
            if (arguments.length > 1) {
                T = thisArg;
            }

            // 6. Let k be 0
            k = 0;

            // 7. Repeat, while k < len
            while (k < len) {

                var kValue;

                // a. Let Pk be ToString(k).
                //   This is implicit for LHS operands of the in operator
                // b. Let kPresent be the result of calling the HasProperty internal method of O with argument Pk.
                //   This step can be combined with c
                // c. If kPresent is true, then
                if (k in O) {

                    // i. Let kValue be the result of calling the Get internal method of O with argument Pk.
                    kValue = O[k];

                    // ii. Call the Call internal method of callback with T as the this value and
                    // argument list containing kValue, k, and O.
                    callback.call(T, kValue, k, O);
                }
                // d. Increase k by 1.
                k++;
            }
            // 8. return undefined
        };
    }


    // Production steps of ECMA-262, Edition 5, 15.4.4.19
    // Reference: http://es5.github.io/#x15.4.4.19
    if (!Array.prototype.map) {

        Array.prototype.map = function(callback, thisArg) {

            var T, A, k;

            if (this == null) {
                throw new TypeError(" this is null or not defined");
            }

            // 1. Let O be the result of calling ToObject passing the |this| 
            //    value as the argument.
            var O = Object(this);

            // 2. Let lenValue be the result of calling the Get internal 
            //    method of O with the argument "length".
            // 3. Let len be ToUint32(lenValue).
            var len = O.length >>> 0;

            // 4. If IsCallable(callback) is false, throw a TypeError exception.
            // See: http://es5.github.com/#x9.11
            if (typeof callback !== "function") {
                throw new TypeError(callback + " is not a function");
            }

            // 5. If thisArg was supplied, let T be thisArg; else let T be undefined.
            if (arguments.length > 1) {
                T = thisArg;
            }

            // 6. Let A be a new array created as if by the expression new Array(len) 
            //    where Array is the standard built-in constructor with that name and 
            //    len is the value of len.
            A = new Array(len);

            // 7. Let k be 0
            k = 0;

            // 8. Repeat, while k < len
            while (k < len) {

                var kValue, mappedValue;

                // a. Let Pk be ToString(k).
                //   This is implicit for LHS operands of the in operator
                // b. Let kPresent be the result of calling the HasProperty internal 
                //    method of O with argument Pk.
                //   This step can be combined with c
                // c. If kPresent is true, then
                if (k in O) {

                    // i. Let kValue be the result of calling the Get internal 
                    //    method of O with argument Pk.
                    kValue = O[k];

                    // ii. Let mappedValue be the result of calling the Call internal 
                    //     method of callback with T as the this value and argument 
                    //     list containing kValue, k, and O.
                    mappedValue = callback.call(T, kValue, k, O);

                    // iii. Call the DefineOwnProperty internal method of A with arguments
                    // Pk, Property Descriptor 
                    // { Value: mappedValue, 
                    //   Writable: true, 
                    //   Enumerable: true, 
                    //   Configurable: true },
                    // and false.

                    // In browsers that support Object.defineProperty, use the following:
                    // Object.defineProperty(A, k, { 
                    //   value: mappedValue, 
                    //   writable: true, 
                    //   enumerable: true, 
                    //   configurable: true 
                    // });

                    // For best browser support, use the following:
                    A[k] = mappedValue;
                }
                // d. Increase k by 1.
                k++;
            }

            // 9. return A
            return A;
        };
    }


    if (!Array.prototype.every) {
        Array.prototype.every = function(callbackfn, thisArg) {
            'use strict';
            var T, k;

            if (this == null) {
                throw new TypeError('this is null or not defined');
            }

            // 1. Let O be the result of calling ToObject passing the this 
            //    value as the argument.
            var O = Object(this);

            // 2. Let lenValue be the result of calling the Get internal method
            //    of O with the argument "length".
            // 3. Let len be ToUint32(lenValue).
            var len = O.length >>> 0;

            // 4. If IsCallable(callbackfn) is false, throw a TypeError exception.
            if (typeof callbackfn !== 'function') {
                throw new TypeError();
            }

            // 5. If thisArg was supplied, let T be thisArg; else let T be undefined.
            if (arguments.length > 1) {
                T = thisArg;
            }

            // 6. Let k be 0.
            k = 0;

            // 7. Repeat, while k < len
            while (k < len) {

                var kValue;

                // a. Let Pk be ToString(k).
                //   This is implicit for LHS operands of the in operator
                // b. Let kPresent be the result of calling the HasProperty internal 
                //    method of O with argument Pk.
                //   This step can be combined with c
                // c. If kPresent is true, then
                if (k in O) {

                    // i. Let kValue be the result of calling the Get internal method
                    //    of O with argument Pk.
                    kValue = O[k];

                    // ii. Let testResult be the result of calling the Call internal method
                    //     of callbackfn with T as the this value and argument list 
                    //     containing kValue, k, and O.
                    var testResult = callbackfn.call(T, kValue, k, O);

                    // iii. If ToBoolean(testResult) is false, return false.
                    if (!testResult) {
                        return false;
                    }
                }
                k++;
            }
            return true;
        };
    }

    /*
    if (!Array.prototype.find) {
      Array.prototype.find = function(predicate) {
        if (this == null) {
          throw new TypeError('Array.prototype.find called on null or undefined');
        }
        if (typeof predicate !== 'function') {
          throw new TypeError('predicate must be a function');
        }
        var list = Object(this);
        var length = list.length >>> 0;
        var thisArg = arguments[1];
        var value;

        for (var i = 0; i < length; i++) {
          value = list[i];
          if (predicate.call(thisArg, value, i, list)) {
            return value;
          }
        }
        return undefined;
      };
    }
    */

}(ff, window, document, undefined));



/*********************************************************
    xhr
********************************************************/

(function() {
    "use strict";
    var get_http_request = function() {
            var http_request = false;
            if (window.XMLHttpRequest) { // Mozilla, Safari,...
                http_request = new XMLHttpRequest();
            } else if (window.ActiveXObject) { // IE
                try {
                    http_request = new ActiveXObject("Msxml2.XMLHTTP");
                } catch (e) {
                    try {
                        http_request = new ActiveXObject("Microsoft.XMLHTTP");
                    } catch (e) {}
                }
            }

            if (!http_request) {
                console.log('Giving up :( Cannot create an XMLHTTP instance');
                return false;
            }

            return http_request;
        },

        http_build_query = function(param) {

            var s = [],

                add = function(key, value) {
                    value = typeof value === 'function' ? value() : (value === null ? "" : value);
                    s[s.length] = encodeURIComponent(key) + "=" + encodeURIComponent(value);
                    //s[ s.length ] = key + "=" + value;
                },

                prefix,

                i,

                imax,

                isHtmlElement = function(el) {
                    return (el && el.tagName && el.nodeType) ? true : false;
                },

                buildParams = function(prefix, obj, add) {

                    if (isHtmlElement(obj) || typeof obj === 'function') {

                        return Object.prototype.toString.call(obj);
                    }

                    var name, i, imax, v;
                    //debugger;
                    if (Object.prototype.toString.call(obj) === '[object Array]') {
                        for (i = 0, imax = obj.length; i < imax; i++) {
                            v = obj[i];
                            buildParams(prefix + "[" + (typeof v === "object" ? i : "") + "]", v, add);
                        }
                    } else if (typeof obj === "object") {
                        for (name in obj) {
                            if (obj.hasOwnProperty(name)) {
                                buildParams(prefix + "[" + name + "]", obj[name], add);
                            }
                        }
                    } else {
                        add(prefix, obj);
                    }
                },

                r20 = /%20/g;

            if (Object.prototype.toString.call(param) === '[object Array]') {
                // Serialize the form elements
                for (i = 0, imax = param.length; i < imax; i++) {
                    add('p[' + i + ']', param[i]);
                }

            } else {
                for (prefix in param) {
                    buildParams(prefix, param[prefix], add);
                }
            }
            //console.log(s.join( "&" ).replace( r20, "+" ));
            return s.join("&").replace(r20, "+");
        },

        http_build_url = function(url, param) {

            if (url.indexOf('?') === -1) url += '?';
            else url += '&';

            url += http_build_query(param);
            return url;
        };


    var __xhr = function(path, method, param) {
        
        this.path = path;
        this.method = method;
        this.param = param;
        this.xhr = get_http_request();
        this.headers = {};
    };


    __xhr.prototype.setHeader = function(key, value) {
        this.headers[key] = value;
        return this;
    };



    __xhr.prototype.set = function(key, value) {
        this.xhr[key] = value;
        return this;
    };


    __xhr.prototype.parse = function() {
        var response = this.response;

        if (response.indexOf('/*****/') !== -1) {
            response = response.split('/*****/');
            response = response[1];
        }

        var data;

        try {
            data = JSON.parse(response);
        } catch (e) {
            data = false;
        }

        return data;
    };


    __xhr.prototype.send = function() {
        var path, method, param, xhr, that;

        path = this.path;
        method = this.method;
        param = this.param;
        xhr = this.xhr;
        that = this;

        return ff.Promise(function(resolve, reject) {
            var key;

            if (method === 'GET' && typeof param === 'object') {
                path = http_build_url(path, param);
            }

            xhr.open(method, path, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            for (key in that.headers) {
                xhr.setRequestHeader(key, that.headers[key]);
            }

            if (method === 'GET') xhr.send(null);
            else xhr.send(http_build_query(param));


            xhr.onreadystatechange = function(e) {
                if (xhr.readyState == 4) {
                    that.status = xhr.status;
                    that.response = xhr.responseText;

                    resolve(that);
                }
            };

        });



    };


    window._xhr = __xhr;

    ff.xhr = function(path, method, param) {
        return new __xhr(path, method, param);
    };



}());



/*********************************************************
    Date
********************************************************/
(function() {
    "use strict";


    var DateFormat = (new function() {
        var token = /d{1,4}|m{1,4}|yy(?:yy)?|Y(?:Y)?|([HhMisTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
            //var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g
            timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standad|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
            timezoneClip = /[^-+\dA-Z]/g,
            pad = function(val, len) {
                val = String(val);
                len = len || 2;
                while (val.length < len)
                    val = "0" + val;
                return val;
            },
            dateFormat = {
                masks: {
                    //"default":            "ddd mmm dd yyyy HH:MM:ss",
                    "default": "yyyy-mm-dd HH:MM:ss",
                    shortDate: "m/d/yy",
                    mediumDate: "mmm d, yyyy",
                    longDate: "mmmm d, yyyy",
                    fullDate: "dddd, mmmm d, yyyy",
                    shortTime: "h:MM TT",
                    mediumTime: "h:MM:ss TT",
                    longTime: "h:MM:ss TT Z",
                    isoDate: "yyyy-mm-dd",
                    isoTime: "HH:MM:ss",
                    isoDateTime: "yyyy-mm-dd'T'HH:MM:ss",
                    isoUtcDateTime: "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
                },
                i18n: {
                    dayNames: [
                        "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
                        "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
                    ],
                    monthNames: [
                        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
                        "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
                    ]
                }
            };

        return function(date, mask, utc) {
            var dF = dateFormat;
            // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
            if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
                mask = date;
                date = undefined;
            }

            // Passing date through Date applies Date.parse, if necessary
            var tmp_pattern = date;
            date = date ? new Date(date) : new Date;
            if (isNaN(date)) {
                throw SyntaxError("invalid date" + tmp_pattern);

            }

            mask = String(dF.masks[mask] || mask || dF.masks["default"]);
            // Allow setting the utc argument via the mask
            if (mask.slice(0, 4) == "UTC:") {
                mask = mask.slice(4);
                utc = true;
            }
            var _ = utc ? "getUTC" : "get",
                d = date[_ + "Date"](),
                D = date[_ + "Day"](),
                m = date[_ + "Month"](),
                y = date[_ + "FullYear"](),
                H = date[_ + "Hours"](),
                M = date[_ + "Minutes"](),
                s = date[_ + "Seconds"](),
                L = date[_ + "Milliseconds"](),
                o = utc ? 0 : date.getTimezoneOffset(),
                flags = {
                    d: d,
                    dd: pad(d),
                    ddd: dF.i18n.dayNames[D],
                    dddd: dF.i18n.dayNames[D + 7],
                    m: m + 1,
                    mm: pad(m + 1),
                    mmm: dF.i18n.monthNames[m],
                    mmmm: dF.i18n.monthNames[m + 12],
                    y: String(y).slice(2),
                    yy: String(y).slice(2),
                    yyyy: y,
                    Y: y,
                    h: H % 12 || 12,
                    hh: pad(H % 12 || 12),
                    H: H,
                    HH: pad(H),
                    M: M,
                    i: M,
                    MM: pad(M),
                    ii: pad(M),
                    s: s,
                    ss: pad(s),
                    l: pad(L, 3),
                    L: pad(L > 99 ? Math.round(L / 10) : L),
                    t: H < 12 ? "a" : "p",
                    tt: H < 12 ? "am" : "pm",
                    T: H < 12 ? "A" : "P",
                    TT: H < 12 ? "AM" : "PM",
                    Z: utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                    o: (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                    S: ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
                };
            return mask.replace(token, function($0) {
                return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
            });
        }
    }());


    function __date(date) {

        this.date = date;

        this.init(date);

    }



    var fn = __date.prototype;


    fn.init = function(date) {
        if (typeof date == 'string') {
            date = date.replace('-', '/').replace('-', '/');
            date = new Date(date);
            this.dateObject = date;
            return;
        }

        if (typeof date === 'number') {
            date = new Date(date);
            this.dateObject = date;
            return;
        }

        if (typeof date === 'undefined') {
            date = new Date();
            this.dateObject = date;
            return;
        }
    };



    fn.getTime = function() {
        return this.dateObject.getTime();
    };

    fn.time = fn.getTime;



    fn.show = function(mask) {
        return DateFormat(this.dateObject, mask);
    };


    fn.this_week = function(startDay) {
        //var now = new Date();
        var now = this.dateObject;
        startDay = startDay || 1; //0=sunday, 1=monday etc.
        var d = now.getDay(); //get the current day
        var weekStart = new Date(now.valueOf() - (d <= 0 ? 7 - startDay : d - startDay) * 86400000); //rewind to start day
        var weekEnd = new Date(weekStart.valueOf() + 6 * 86400000); //add 6 days to get last day
        var firstday = ff.Date(weekStart).show('Y-mm-dd');
        var lastday = ff.Date(weekEnd).show('Y-mm-dd');
        return [firstday, lastday];
    };



    fn.last_week = function(startDay) {
        //var now = new Date();
        var now = this.dateObject;
        startDay = startDay || 1; //0=sunday, 1=monday etc.
        var d = now.getDay(); //get the current day
        var weekStart = new Date(now.valueOf() - (d <= 0 ? 7 - startDay : d - startDay) * 86400000); //rewind to start day
        var weekEnd = new Date(weekStart.valueOf() + 6 * 86400000); //add 6 days to get last day
        var firstday = EZ.Date(weekStart.getTime() - 604800000).show('Y-mm-dd');
        var lastday = EZ.Date(weekEnd.getTime() - 604800000).show('Y-mm-dd');
        return [firstday, lastday];
    };



    fn.this_month = function() {
        // var date = new Date();
        var date = this.dateObject;
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        firstDay = EZ.Date(firstDay).show('Y-mm-dd');
        lastDay = EZ.Date(lastDay).show('Y-mm-dd');
        return [firstDay, lastDay];
    };



    fn.set = function(pattern) {
        this.dateObject = ff.Date(this.show(pattern)).dateObject;
    };



    fn.year = function(i) {
        this.dateObject.setYear(this.dateObject.getYear() + i);
    };



    fn.month = function(i) {
        this.dateObject.setMonth(this.dateObject.getMonth() + i);
    };



    fn.week = function(i) {
        this.dateObject.setTime(this.dateObject.getTime() + i * 604800000);
    };



    fn.day = function(i) {
        this.dateObject.setTime(this.dateObject.getTime() + i * 86400000);
    };



    fn.hour = function(i) {
        this.dateObject.setTime(this.dateObject.getTime() + i * 3600000);
    };



    fn.minute = function(i) {
        this.dateObject.setTime(this.dateObject.getTime() + i * 60000);
    };



    fn.second = function(i) {
        this.dateObject.setTime(this.dateObject.getTime() + i * 1000);
    };



    fn.setTime = function(i) {
        this.dateObject.setTime(this.dateObject.getTime() + i);
    };



    ff.Date = function(date) {
        return new __date(date);
    };
}());



/*************************************************************************************
    元素處理器
*************************************************************************************/
(function(ff, window, document, undefined) {

    var

        core_rnotwhite = /\S+/g,

        rspaces = /\s+/,

        rclass = /[\n\t]/g,

        cssNumber = {
            "columnCount": true,
            "fillOpacity": true,
            "fontWeight": true,
            "lineHeight": true,
            "opacity": true,
            "order": true,
            "orphans": true,
            "widows": true,
            "zIndex": true,
            "zoom": true
        },

        /*
            style name 
        */
        rmsPrefix = /^-ms-/,
        rdashAlpha = /-([\da-z])/gi,

        // 改 styleu名稱用 
        right_style_name_case = function(all, letter) {
            return letter.toUpperCase();
        }

    ;

    /*
        類似 background-color => backgroundColor 的行為 
    */
    ff.right_style_name = function(string) {
        return string.replace(rmsPrefix, "ms-").replace(rdashAlpha, right_style_name_case);
    };

    ff.addClass = function(elem, cl) {
        var c;
        var classNames = (cl || "").split(rspaces);
        var className = " " + elem.className + " ",
            setClass = elem.className;
        for (c = 0, cl = classNames.length; c < cl; c++) {
            if (className.indexOf(" " + classNames[c] + " ") < 0) {
                setClass += " " + classNames[c];
            }
        }
        //elem.className = setClass.replace(/^\s+|\s+$/g,'');//trim
        elem.className = ff.trim(setClass);
    };

    ff.removeClass = function(elem, cl) {
        var c;
        var classNames = (cl || "").split(rspaces);
        var className = (" " + elem.className + " ").replace(rclass, " ");
        for (c = 0, cl = classNames.length; c < cl; c++) {
            className = className.replace(" " + classNames[c] + " ", " ");
        }

        elem.className = ff.trim(className);
    };

    ff.hasClass = function(el, cls) {
        var
            className,
            i = 0;

        className = " " + cls + " ";

        if (el.nodeType === 1 && (" " + el.className + " ").replace(rclass, " ").indexOf(className) >= 0) {
            return true;
        }
        return false;
    };

    ff.toggleClass = function(el, cls) {
        var i = 0,
            className,
            classNames = cls.match(core_rnotwhite) || [];

        while ((className = classNames[i++])) {
            if (ff.hasClass(el, className)) {
                ff.removeClass(el, className);
            } else {
                ff.addClass(el, className);
            }
        }
    };

    ff.css = function(el, styleName, value) {

        if (ff.type(el) === 'undefined') {
            return '';
        } else if (ff.type(styleName) === 'object') {
            var style;
            for (style in styleName) {
                ff.css(el, style, styleName[style]);
            }
            return;
        } else if (typeof value === 'undefined') {
            styleName = ff.right_style_name(styleName);
            return el.style[styleName];
        }


        var originName = styleName;

        if (!ff.isHtmlElement(el)) return;

        // Don't set styles on text and comment nodes
        if (!el || el.nodeType === 3 || el.nodeType === 8 || !el.style) return;

        styleName = ff.right_style_name(styleName);

        // If a number was passed in, add 'px' to the (except for certain CSS properties)
        if (ff.isNumeric(value) && !cssNumber[styleName]) {
            value += "px";
        }

        //console.log(originName+' = '+styleName+' = '+value);

        /*
            try catch 防止 IE 8 出錯 
        */
        try {
            el.style[styleName] = value;

            if (styleName === 'transform') {
                el.style.webkitTransform = value;
                el.style.MozTransform = value;
                el.style.msTransform = value;
                el.style.OTransform = value;
            }

        } catch (e) {
            //console.log(styleName+' '+value);
            throw new Error('ff.css ' + styleName + ' ' + value);
        }
    };

    ff.removeCss = function(el, style) {
        el.style[style] = '';
    };

    ff.toggleCss = function(el, styleName, value) {
        if (ff.type(styleName) === 'object') {
            var style;
            for (style in styleName) {
                ff.toggleCss(el, style, styleName[style]);
            }
        } else {

            if (!ff.css(el, styleName)) {
                ff.css(el, styleName, value);
            } else {
                ff.removeCss(el, styleName);
            }
        }
    };



    ff.attr = function(el, key, value) {
        if (ff.type(value) == 'undefined') {
            return (el && el.getAttribute) ? el.getAttribute(key) : '';
        } else {
            el.setAttribute(key, value);
            return value;
        }
    };



    ff.removeAttr = function(el, key) {
        el.removeAttribute(key);
    };



    //取得表單的值
    ff.getForm = function(_oForm) {
        var oForm,
            i,
            elements,
            formData,
            field_type,
            name,
            v;

        if (typeof(_oForm) == 'string') {
            oForm = document.getElementById(_oForm);
        } else {
            oForm = _oForm;
        }
        elements = oForm.elements;
        formData = {};
        for (i = 0; i < elements.length; i++) {
            field_type = elements[i].type;
            if (typeof field_type == 'undefined')
                continue;
            field_type = field_type.toLowerCase();
            name = elements[i].name;
            switch (field_type) {
                case "text":
                case "password":
                case "textarea":
                case "hidden":
                    formData[name] = elements[i].value;
                    break;

                case "radio":
                    if (elements[i].checked == true) {
                        formData[name] = elements[i].value;
                    }
                    break;
                case "checkbox": //    如果沒勾也要有值 把值存在一個屬性 叫做noValue
                    if (elements[i].checked == true) {
                        formData[name] = elements[i].value;
                    } else {
                        v = elements[i].getAttribute('noValue')
                        if (v) {
                            formData[name] = v;
                        }
                    }
                    break;
                case "select-one":
                case "select-multi":
                    //elements[i].selectedIndex = -1;
                    formData[name] = elements[i].value;
                    break;

                default:
                    formData[name] = elements[i].value;
                    break;
            }
        }
        return formData;
    };

    ff.setForm = function(_oForm, formData) {
        var oForm,
            i,
            imax,
            j,
            jmax,
            elements,
            field_type,
            name,
            v;

        if (typeof(_oForm) == 'string') {
            oForm = document.getElementById(_oForm);
        } else {
            oForm = _oForm;
        }
        elements = oForm.elements;
        for (i = 0; i < elements.length; i++) {
            field_type = elements[i].type;
            name = elements[i].name;
            if (typeof field_type == 'undefined')
                continue;
            field_type = field_type.toLowerCase();
            //var field_type = elements[i].type.toLowerCase();


            switch (field_type) {
                case "text":
                case "password":
                case "textarea":
                case "hidden":
                    if (typeof formData[name] != 'undefined') {
                        elements[i].value = formData[name];
                    }
                    break;

                case "radio":
                    if (typeof formData[name] != 'undefined' && formData[name] == elements[i].value) {
                        elements[i].checked = true;
                    }
                    break;
                case "checkbox": //    如果沒勾也要有值 把值存在一個屬性 叫做noValue
                    var yesValue = elements[i].getAttribute('value');
                    var noValue = elements[i].getAttribute('noValue');

                    if (typeof formData[name] != 'undefined') {
                        if (formData[name] == yesValue) {
                            elements[i].checked = true;
                        } else if (formData[name] == noValue) {
                            elements[i].checked = false;
                        }
                    }
                    break;
                case "select-one":
                    var os = elements[i].getElementsByTagName('option');
                    for (j = 0, jmax = os.length; j < jmax; j++) {
                        var o = os[j];
                        if (o.value == formData[name]) {
                            o.selected = true;
                        }
                    }
                    break;
                case "select-multi":
                    //elements[i].selectedIndex = -1;
                    break;
                default:
                    if (typeof formData[name] != 'undefined') {
                        elements[i].value = formData[name];
                    }
                    break;
            }
        }
    };

    //表單驗證 如果驗證失敗 把該dom 存入陣列 result    並且回傳
    ff.checkForm = function(_oForm, formCheck) {
        var oForm,
            i,
            imax,
            j,
            jmax,
            elements,
            formData,
            field_type,
            name,
            result,
            rtn,
            value,
            v;

        if (typeof(_oForm) == 'string') {
            oForm = document.getElementById(_oForm);
        } else {
            oForm = _oForm;
        }
        elements = oForm.elements;
        result = [];
        for (i = 0; i < elements.length; i++) {
            field_type = elements[i].type.toLowerCase();
            name = elements[i].name;
            switch (field_type) {
                case "text":
                case "password":
                case "textarea":

                    if (typeof formCheck[name] == 'function') {
                        value = elements[i].value;
                        rtn = formCheck[name].apply(this, [elements[i]]);
                        if (typeof rtn != 'boolean') {
                            rtn = true;
                        } //fucntion 沒回傳true fasle 就算他驗證通過,管他的
                        if (rtn === false) {
                            result.push(elements[i]);
                        }
                    }
                    break;
                default:
                    break;
            }
        }

        return result;
    };



    ff.html = function(el, content) {

        if (ff.type(content) == 'undefined') {
            return el.innerHTML;
        }

        ff.empty(el);

        if (ff.isElem(content)) el.appendChild(content);
        else el.innerHTML = content;
    };

    /*
        name 跟 nodename 比對名子
    */
    ff.nodeName = function(elem, name) {
        return elem.nodeName && elem.nodeName.toLowerCase() === name.toLowerCase();
    };

    /*
        clear element and remove event of all children element
    */
    ff.empty = function(el) {
        // Remove element nodes and prevent memory leaks
        if (el.nodeType === 1) {
            ff.event.cleanall(el);
        }

        while (el.firstChild) {
            el.removeChild(el.firstChild);
        }

        /*
            If this is a select, ensure that it displays empty (#12336)
            Support: IE<9
        */
        if (el.options && ff.nodeName(el, "select")) {
            el.options.length = 0;
        }
    };

    ff.val = function(el, v) {
        var
            elem_type,
            tagName,
            value;

        tagName = (el && el.tagName && el.tagName.toLowerCase()) || '';

        if (!tagName) return '';

        elem_type = (el && el.type && el.type.toLowerCase()) || '';

        switch (tagName + '_' + elem_type) {
            case "input_text":
            case "input_password":
            case "textarea_textarea":
            case "input_hidden":
            case 'input_radio':
            case 'input_date':
            case 'input_color':
            case 'input_range':
            case 'input_month':
            case 'input_week':
            case 'input_time':
            case 'input_datetime':
            case 'input_datetime-local':
            case 'input_email':
            case 'input_search':
            case 'input_tel':
            case 'input_url':
            case "select_select-one":
            case "select_select-multi":

                if (ff.type(v) != 'undefined') {
                    el.value = v;
                }

                value = el.value;

                break;

            case 'input_checkbox':

                if (ff.type(v) != 'undefined') {
                    el.value = v;
                }

                // 如果沒勾也要有值 找 noValue

                if (el.checked === false && el.hasAttribute('noValue')) {
                    value = ff.attr(el, 'noValue');
                } else {
                    value = el.value;
                }
                break;

            case "form_":
                if (ff.type(v) != 'undefined') {
                    ff.setForm(el, v);
                }
                value = ff.getForm(el);
                break;

            default:

                if (tagName === 'input') {
                    if (ff.type(v) != 'undefined') {
                        el.value = v;
                    }

                    value = el.value;
                } else {
                    if (ff.type(v) != 'undefined') {
                        //el.value = v;
                        el.innerHTML = v;
                        value = v;
                    } else {
                        value = el.value || el.innerHTML || '';
                    }
                }

                break;
        }

        return value;
    };

    ff.width = function(el) {
        var
            w,
            clone;

        if (el === window) {
            return window.innerWidth;
        } else if (el === document) {
            el = document.documentElement;
        }

        if (!ff.isElem(el)) return 0;

        w = el.clientWidth;

        if (w === 0) {
            clone = el.cloneNode(true);
            clone.style.display = 'inline-block';
            clone.style.position = 'absolute';
            document.body.appendChild(clone);
            w = clone.clientWidth;
            document.body.removeChild(clone);
            clone = null;
        }
        return w;

    };

    ff.height = function(el) {
        var
            h,
            clone;

        if (el === window) {
            return window.innerHeight;
        } else if (el === document) {
            el = document.documentElement;
        }

        if (!ff.isElem(el)) return 0;

        h = el.clientHeight;

        if (h === 0) {
            clone = el.cloneNode(true);
            clone.style.display = 'inline-block';
            clone.style.position = 'absolute';
            document.body.appendChild(clone);
            h = clone.clientHeight;
            document.body.removeChild(clone);
            clone = null;
        }
        return h;
    };

    ff.append = function(el, child) {
        var
            tmp, els, _child, i;

        if (ff.type(child) == 'string') {

            tmp = document.createDocumentFragment();
            els = ff.html_decode(child);
            i = 0;
            while (_child = els[i++]) {
                tmp.appendChild(_child);
            }

            el.appendChild(tmp);

        } else if (ff.isHtmlElement(el) && ff.isHtmlElement(child)) {
            el.appendChild(child);
        }

    };

    ff.remove = function(el, child) {
        if (child) {
            //console.log(el);
            //console.log(child);
            el.removeChild(child);
        } else if (el.parentNode) {
            el.parentNode.removeChild(el);
        }
    };

    ff.before = ff.insertBefore = function(el, child) {
        var
            tmp,
            els,
            i,
            imax,
            _child;

        if (ff.type(child) === 'string') {
            els = ff.html_decode(child);

            for (i = 0, imax = els.length; i < imax; i++) {
                el.parentNode.insertBefore(els[i], el);
            }

        } else if (ff.isHtmlElement(el) && ff.isHtmlElement(child) && el.parentNode) {

            el.parentNode.insertBefore(child, el);
        }

    };

    ff.after = ff.insertAfter = function(el, child) {
        var
            tmp,
            els,
            i,
            imax,
            _child,
            parent;

        if (ff.type(child) === 'string') {
            els = ff.html_decode(child);
            parent = el.parentNode;
            if (parent.lastChild === el) {

                for (i = 0, imax = els.length; i < imax; i++) {
                    parent.appendChild(els[i]);
                }
            } else {
                for (i = 0, imax = els.length; i < imax; i++) {
                    parent.insertBefore(els[i], el.nextSibling);
                }
            }

        } else if (ff.isHtmlElement(el) && ff.isHtmlElement(child) && el.parentNode) {
            parent = el.parentNode;
            if (parent.lastChild === el) {
                parent.appendChild(child);
            } else {
                parent.insertBefore(child, el.nextSibling);
            }
        }
    }


}(ff, window, document, undefined));



/*************************************************************************************
    元素處理器 2
*************************************************************************************/
(function(ff, window, document, undefined) {
    "use strict";
    var fn = ff.fn;


    fn.find = function(selector, results, seed) {
        return ff(selector, this.els[0], results, seed);
    };



    /*
        跟 find 類似 但是他只找 母元素底下 第一層的子元素 
    */
    fn.children = function(selector, results, seed) {

        selector = selector || '*';

        var new_els = [],
            s, i, imax, el;

        this.each(function(index, el) {
            s = ff(selector, el, results, seed);
            for (i = 0, imax = s.els.length; i < imax; i++) {
                el = s.els[i];
                if (el.parentNode && el.parentNode === this.el) {
                    new_els[new_els.length] = el;
                }
            }
        });

        this.el = new_els[0];
        this.els = new_els;
        return this;
    };



    fn.parent = function(selector) {
        var new_els = [];

        this.each(function(index, el) {

            if (selector && !ff.matchSelector(el, selector)) return;

            if (el.parentNode) new_els[new_els.length] = el;

        });


        this.el = new_els[0];
        this.els = new_els;

        return this;
    };



    /*
        只保留第一個元素
    */
    fn.first = function() {
        this.els = [this.el];
        return this;
    };



    fn.get = function(index) {

        if (this.els[index]) {
            this.els = [this.els[index]];
            this.el = this.els[0];
        } else {
            this.els = [];
            this.el = null;
        }

        return this;
    };



    fn.each = function(fn) {

        var i, imax, el;

        for (i = 0, imax = this.els.length; i < imax; i++) {
            el = this.els[i];
            fn.apply(this, [i, el]);
        }

        return this;
    };



    fn.show = function() {

        this.each(function(index, el) {

            var display = ff.css(el, 'display');

            if (!display || display === 'none') {
                ff.css(el, 'display', 'block');
            }

        });

    };



    fn.hide = function() {

        this.each(function(index, el) {

            var display = ff.css(el, 'display');

            ff.css(el, 'display', 'none');

        });

    };



    fn.attr = function(key, value) {

        if (typeof value === 'undefined') {

            if (!this.els[0]) return undefined;

            var v = this.els[0].getAttribute(key);

            if (v !== null) return v;

            v = this.els[0].getAttribute('data-' + key);

            return v;

        }


        this.each(function(index, el) {
            el.setAttribute(key, value);
        });

        return this;
    };



    fn.hasClass = function(cls) {
        if (typeof this.els[0] == 'undefined') return false;
        return ff.hasClass(this.els[0], cls);
    };



    fn.html = function(content) {

        if (ff.type(content) !== 'undefined') {
            this.each(function(index, el) {
                ff.html(el, content);
            });
        } else {
            return ff.html(this.els[0]);
        }

        return this;
    };



    fn.clean = function() {

        this.each(function(index, el) {
            ff.event.clean(el);
        });

        return this;
    };



    fn.val = function(value) {

        if (ff.type(value) == 'undefined') {
            return ff.val(this.els[0]);
        } else {
            this.each(function(index, el) {
                ff.val(el, value);
            });
        }
        return this;
    };



    fn.width = function() {

        return ff.width(this.els[0]);

    };



    fn.height = function() {

        return ff.height(this.els[0]);

    };



    fn.addClass = function(cl) {
        this.each(function(index, el) {
            ff.addClass(el, cl);
        });

        return this;
    };



    fn.removeClass = function(cl) {
        this.each(function(index, el) {
            ff.removeClass(el, cl);
        });

        return this;
    };



    fn.removeAttr = function(key) {
        this.each(function(index, el) {
            ff.removeAttr(el, key);
        });

        return this;
    };



    fn.css = function(style, value) {
        if (typeof value === 'undefined') {
            return ff.css(this.els[0], style, value);
        } else {
            this.each(function(index, el) {
                ff.css(el, style, value);
            });
        }

        return this;
    };



    fn.removeCss = function(style) {
        this.each(function(index, el) {
            ff.removeCss(el, style);
        });

        return this;
    };



    fn.toggleCss = function(style, value) {
        this.each(function(index, el) {
            ff.toggleCss(el, style, value);
        });

        return this;
    };



    fn.bind = function(type, handle) {
        this.each(function(index, el) {
            ff.bind(el, type, handle);
        });

        return this;
    };

    fn.on = fn.bind;



    fn.unbind = function(type, handle) {
        this.each(function(index, el) {
            ff.unbind(el, type, handle);
        });

        return this;
    };

    fn.off = fn.unbind;



    fn.toggleClass = function(cls) {
        this.each(function(index, el) {
            ff.toggleClass(el, cls);
        });

        return this;
    };



    fn.empty = function() {
            this.each(function(index, el) {
                ff.empty(el);
            });

            return this;
        },



        fn.append = function(child) {
            this.each(function(index, el) {
                ff.append(el, child);
            });

            return this;
        };



    fn.remove = function(child) {
        this.each(function(index, el) {
            ff.remove(el, child);
        });

        return this;
    };



    fn.insertBefore = function(child) {
        this.each(function(index, el) {
            ff.insertBefore(el, child);
        });

        return this;
    };

    fn.before = fn.insertBefore;


    fn.insertAfter = function(child) {
        this.each(function(index, el) {
            ff.insertAfter(el, child);
        });

        return this;
    };

    fn.after = fn.insertAfter;


    // hotkey 
    ["onblur", "onchange", "ontent", "onfocus", "onselect", "onsubmit", "onreset", "onkeydown", "onkeypress", "onkeyup", "onmouseover", "onmouseout", "onmousedown", "onmouseup", "onmousemove", "onclick", "ondblclick", "onload", "onerror", "onunload", "onresize"]
    .forEach(function(v) {
        v = v.replace(/^on/, '');
        fn[v] = function(handle) {
            return this.on(v, handle);
        };
    });


    //fn.click = function( handle ){

    //return this.bind('click',handle);

    //};



}(ff, window, document, undefined));



/*************************************************************************************
    事件處理器
*************************************************************************************/
(function(ff, window, document, undefined) {
    "use strict";


    ff.event = {

        fn: {},

        /*
            移除事件 所有相關物件 
        */
        removefn: function(id, el, type, handle) {

            delete this.fn[id];

            // 從 id群中 移除 id
            ff.removeFromArray(id, el.eeid);

            this.remove(el, type, handle);
        },

        /*
            清除所有子元素事件 
        */
        cleanall: function(el) {

            var els, tmp, i = 0;

            els = ff.find('*', el);

            while (tmp = els[i++]) {
                this.clean(tmp);
            }

        },

        /*
            清除元素所有事件
        */
        clean: function(el) {
            var
                f,
                type,
                id,
                eeid,
                i,
                imax;

            eeid = el.eeid || [];

            for (i = eeid.length; i >= 0; i--) {
                id = eeid[i];
                f = this.fn[id];
                if (ff.type(f) === 'object') {
                    for (type in f) {
                        ff.removeEvent(el, type, f[type]);
                    }
                }
            }
        },

        add: function(el, type, handle) {
            if (document.addEventListener) {
                ff.event.add = function(el, type, handle) {
                    el.addEventListener(type, handle, false); // true :capture , false : bubbling
                }
            } else if (document.attachEvent) {
                ff.event.add = function(el, type, handle) {
                    el.attachEvent("on" + type, handle);
                }
            } else {
                ff.event.add = function(el, type, handle) {
                    el['on' + type] = handle;
                }
            }
            ff.event.add(el, type, handle);
        },

        remove: function(el, type, handle) {
            if (document.removeEventListener) {
                ff.event.remove = function(el, type, handle) {
                    el.removeEventListener(type, handle);
                }
            } else if (document.detachEvent) {
                ff.event.remove = function(el, type, handle) {
                    el.detachEvent("on" + type, handle);
                }
            } else {
                ff.event.remove = function(el, type, handle) {
                    el['on' + type] = null;
                }
            }
            ff.event.remove(el, type, handle);
        }
    };


    ff.bind = function(el, type, handle) {
        /*
            偷存 handle , clean用 
        */
        var id,
            old_id,
            i,
            imax,
            eeid,
            _handle,
            found;

        if (ff.type(el && el.eeid) == 'undefined') {

            id = ff.id();
            el.eeid = [id];
            ff.event.fn[id] = ff.event.fn[id] || {};
            ff.event.fn[id][type] = handle;
            ff.event.add(el, type, handle);

        } else {
            /*
                如果 handle 已經註冊過 就不必再註冊事件 
            */
            eeid = el.eeid;
            found = false;
            for (i = 0, imax = eeid.length; i < imax; i++) {
                old_id = eeid[i];
                _handle = ff.event.fn[old_id] && ff.event.fn[old_id][type];

                if (_handle && _handle === handle) {
                    found = true;
                    break;
                }
            }

            if (found === false) {
                id = ff.id();
                el.eeid.push(id);
                ff.event.fn[id] = ff.event.fn[id] || {};
                ff.event.fn[id][type] = handle;
                ff.event.add(el, type, handle);
            }
        }
    };

    ff.on = ff.addEvent = ff.bind;


    ff.unbind = function(el, type, handle) {
        var
            eeid,
            i,
            imax,
            id,
            _handle,
            _type,
            fn;

        if (ff.isEmpty(type)) {
            // 沒給 type  移除 element 上所以事件 
            ff.event.clean(el);

        } else if (ff.type(handle) == 'undefined') {
            // 移除所有 type 事件
            //handle = handle || (ff.event.fn[el.eeid] && ff.event.fn[el.eeid][type]);

            eeid = el.eeid || [];

            for (i = eeid.length; i >= 0; i--) {

                id = eeid[i];

                fn = ff.event.fn[id] || {};

                for (_type in fn) {

                    if (_type == type && fn.hasOwnProperty(_type)) {

                        _handle = fn[_type];

                        ff.event.removefn(id, el, _type, _handle);

                    }
                }
            }

        } else {

            eeid = el.eeid || [];

            //for(i=0,imax=eeid.length; i<imax; i++){
            for (i = eeid.length; i >= 0; i--) {

                id = eeid[i];

                _handle = ff.event.fn[id] && ff.event.fn[id][type];

                if (_handle && _handle === handle) {

                    ff.event.removefn(id, el, type, handle);

                    break;
                }

            }
        }
    };

    ff.off = ff.removeEvent = ff.unbind;


}(ff, window, document, undefined));



/* auto load script ************************************************************************************
    
*/
(function(ff, window, document, undefined) {
    var scripts = document.getElementsByTagName('script');
    var index = scripts.length - 1;
    var myScript = scripts[index];

    var tmp = myScript.src.split('/');

    ff.scriptUrl = myScript.src.replace(tmp[tmp.length - 1], '');
    ff.pluginUrl = ff.scriptUrl.replace('/EZ/', '/');
}(ff, window, document, undefined));

/* <script src="/plugin/EZ/ff.2.js?load=window,system,sha512"></script>
    
*/
(function(ff, window, document, undefined) {
    var scripts = document.getElementsByTagName('script');
    var index = scripts.length - 1;
    var myScript = scripts[index];

    var s = myScript.src;
    if (s.indexOf('?') != -1) {
        s = s.split('?');
        s = s[1].split('&');
        for (var i = 0, imax = s.length; i < imax; i++) {
            var t = s[i];
            t = t.split('=');
            if (t[0] == 'load' && typeof(t[1]) == 'string') {
                var tt = t[1].split(',');
                for (var j = 0, jmax = tt.length; j < jmax; j++) {
                    ff.load(tt[j]);
                }
            }
        }
    }
}(ff, window, document, undefined));