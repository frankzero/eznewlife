if (typeof console == 'undefined') {
    console = {
        log : function () {},
        trace : function () {}
    }
}
var _onresize = new function () {
    var _e = [];
    this.add = function (func) {
        _e.push(func);
    };
    this.events = function () {
        for (var i = 0; i < _e.length; i++) {
            _e[i].call();
        }
    };
    this.clearEvents = function () {
        _e = [];
    };
}();

(function(window,undefined){
    var EZ = {};
    EZ.importCSS = function (href) {
        var l = document.createElement('link');
        l.setAttribute('type', 'text/css');
        l.setAttribute('rel', 'stylesheet');
        l.setAttribute('href', href);
        var head = document.getElementsByTagName('head')[0];
        if (head) {
            head.appendChild(l);
        } else {
            document.body.appendChild(l);
        }
    };
    EZ.importJS = new function(){
        var wait_for_script_load = function (look_for, callback) {
            var interval = setInterval(function () {
                    if (eval("typeof " + look_for) != 'undefined') {
                        clearInterval(interval);
                        callback();
                    }
                }, 50);
        };
        return function(src, look_for, onload){
            var s = document.createElement('script');
            s.setAttribute('type', 'text/javascript');
            s.setAttribute('src', src);
            if (onload) wait_for_script_load(look_for, onload);
            if (eval("typeof " + look_for) == 'undefined') {
                var head = document.getElementsByTagName('head')[0];
                if (head)
                    head.appendChild(s);
                else
                    document.body.appendChild(s);
            }
        }
    }();
    EZ.loadScript = function (src, callback) {
        var head = document.getElementsByTagName('head')[0],
        script = document.createElement('script');
        done = false;
        script.setAttribute('src', src);
        script.setAttribute('type', 'text/javascript');
        script.setAttribute('charset', 'utf-8');
        script.onload = script.onreadystatechange = function () {
            if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
                done = true;
                script.onload = script.onreadystatechange = null;
                if (callback) {
                    callback();
                }
            }
        }
        head.insertBefore(script, head.firstChild);
    };
    //語系
    EZ.language = new function () {
        var StrVar = {};
        StrVar["zh-tw"] = {};
        StrVar["zh-cn"] = {};
        StrVar["en-us"] = {};
        StrVar["ja-jp"] = {};
        var _langx;

        var Self = {
            get : function (text, l) {
                if (!text)
                    return '';
                if (!l) {
                    if (typeof _langx == 'undefined') {
                        l = (typeof langx == 'undefined' || langx == null) ? 'zh-tw' : langx;
                    } else {
                        l = _langx;
                    }
                }
                if (!StrVar[l])
                    return text;
                var newtext = StrVar[l][text];
                return newtext ? newtext : text;
            },
            set : function (l, key, text) {
                StrVar[l][key] = text;
            },
            setLang : function (l) {
                _langx = l;
            },
            getLang : function () {
                return _langx;
            }
        }
        return Self;
    }();
    //語系快取
    EZ.lang = EZ.language.get;

    EZ._GET = function () {
        if (document.location.toString().indexOf('?') == -1) {
            return {};
        }
        var _get = {};
        var t = document.location.toString().split('?');
        var t2 = t[1].split('&');
        for (var i = 0; i < t2.length; i++) {
            var t3 = t2[i].split('=');
            _get[t3[0]] = t3[1];
        }
        return _get;
    }();

    EZ.show = function (ArrayData, type) {
        var _t = '';
        for (var key in ArrayData) {
            if (typeof type == 'undefined') {
                _t += key + ' = ' + ArrayData[key] + "\n";
            } else {
                _t += key + ' = ' + ArrayData[key] + "<br />";
            }
        }
        if (typeof type == 'undefined') {
            alert(_t);
        } else {
            var aa = window.open();
            aa.document.write(_t);
        }
    };

    EZ.round = function (num, digit) {
        var x = 1;
        if (digit) {
            x = Math.pow(10, digit);
        }
        return Math.round(num * x) / x;
    };

    EZ.random = function (min, max) {
        return Math.round(Math.random() * (max - min) + min);
    };

    EZ.randomx = function () {
        var maxNum = 10000;
        var minNum = 1;
        var n = Math.floor(Math.random() * (maxNum - minNum + 1)) + minNum;
        var t = new Date().getTime();
        return n + '' + t;
    };

    EZ.unique_id = function (num) {
        var t = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M'
            , 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
            , 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm'
            , 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
            , '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];
        var f = [];
        for (var i = 0; i < num; i++) {
            f[i] = t[(i != 0) ? Math.floor((Math.random() * 62)) : Math.floor((Math.random() * 52))];
        }
        return f.join('');
    };

    EZ.id = new function () {
        var count=0;
        var token = EZ.unique_id(10)+'_';
        return function(){
            count++;
            return token + count;
        }
    }();

    EZ.insertAfter = function (newEl, targetEl) {
        var parentEl = targetEl.parentNode;

        if (parentEl.lastChild == targetEl) {
            parentEl.appendChild(newEl);
        } else {
            parentEl.insertBefore(newEl, targetEl.nextSibling);
        }
    }

    //複製object
    EZ.clone = function (obj) {
        // Handle the 3 simple types, and null or undefined
        if (null == obj || "object" != typeof obj)
            return obj;
        // Handle Date
        if (obj instanceof Date) {
            var copy = new Date();
            copy.setTime(obj.getTime());
            return copy;
        }
        // Handle Array
        if (obj instanceof Array) {
            var copy = [];
            for (var i = 0, len = obj.length; i < len; ++i) {
                copy[i] = this.clone(obj[i]);
            }
            return copy;
        }
        // Handle Object
        if (obj instanceof Object) {
            var copy = {};
            for (var attr in obj) {
                if (obj.hasOwnProperty(attr))
                    copy[attr] = this.clone(obj[attr]);
            }
            return copy;
        }
        throw new Error("Unable to copy obj! Its type isn't supported.");
    }

    EZ.in_array = function (find, myArray) {
        if (myArray.length == 0)
            return false;
        for (var i = 0; i < myArray.length; i++) {
            if (myArray[i] == find)
                return true;
        }
        return false;
    };

    // 自動左邊補0
    EZ.padLeft = function (str, lenght) {
        if (typeof(str) == 'number') {
            str = str.toString();
        }
        if (str.length >= lenght)
            return str;
        else
            return this.padLeft("0" + str, lenght);
    };
    //自動右邊補0
    EZ.padRight = function (str, lenght) {
        if (typeof(str) == 'number') {
            str = str.toString();
        }
        if (str.length >= lenght)
            return str;
        else
            return this.padRight(str + "0", lenght);
    };
/*
    //url encode
    EZ.url_encode = function (string) {
        string = string.replace(/\r\n/g, "\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            } else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    };
    EZ.url_decode = function (utftext) {
        utftext = unescape(unescape);
        var string = "";
        var i = 0;
        var c = c1 = c2 = 0;
        while (i < utftext.length) {
            c = utftext.charCodeAt(i);
            if (c < 128) {
                string += String.fromCharCode(c);
                i++;
            } else if ((c > 191) && (c < 224)) {
                c2 = utftext.charCodeAt(i + 1);
                string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
                i += 2;
            } else {
                c2 = utftext.charCodeAt(i + 1);
                c3 = utftext.charCodeAt(i + 2);
                string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
                i += 3;
            }
        }
        return string;
    };
*/
    // 物件的座標
    EZ.posxy = function (objID) {
        if(typeof objID=='string') var elmt = document.getElementById(objID);
        else elmt = objID;
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
            if (e.scrollTop)
                y -= e.scrollTop;
        }
        return [x,y];
    };

    //取得表單的值
    EZ.getForm = function (_oForm) {
        var oForm;
        if (typeof(_oForm) == 'string') {
            oForm = document.getElementById(_oForm);
        } else {
            oForm = _oForm;
        }
        var elements = oForm.elements;
        var formData = {};
        for (i = 0; i < elements.length; i++) {
            var field_type = elements[i].type;
            if (typeof field_type == 'undefined')
                continue;
            field_type = field_type.toLowerCase();
            var name = elements[i].name;
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
                    var v = elements[i].getAttribute('noValue')
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

                break;
            }
        }
        return formData;
    };
    EZ.setForm = function (_oForm, formData) {
        var oForm;
        if (typeof(_oForm) == 'string') {
            oForm = document.getElementById(_oForm);
        } else {
            oForm = _oForm;
        }
        var elements = oForm.elements;
        for (i = 0; i < elements.length; i++) {
            var field_type = elements[i].type;
            var name = elements[i].name;
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
                for (var j = 0, jmax = os.length; j < jmax; j++) {
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
                break;
            }
        }
    };
    //表單驗證 如果驗證失敗 把該dom 存入陣列 result    並且回傳
    EZ.checkForm = function (_oForm, formCheck) {
        var oForm;
        if (typeof(_oForm) == 'string') {
            oForm = document.getElementById(_oForm);
        } else {
            oForm = _oForm;
        }
        var elements = oForm.elements;
        var result = [];
        for (i = 0; i < elements.length; i++) {
            var field_type = elements[i].type.toLowerCase();
            var name = elements[i].name;
            switch (field_type) {
            case "text":
            case "password":
            case "textarea":

                if (typeof formCheck[name] == 'function') {
                    var value = elements[i].value;
                    var rtn = formCheck[name].apply(this, [elements[i]]);
                    if (typeof rtn != 'boolean') {
                        rtn = true;
                    } //fucntion 沒回傳true fasle 就算他驗證通過,管他的
                    if (rtn == false) {
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

    EZ.emptyFN = new Function();

    //取得絕對座標
    EZ.getPos = function (elmt) {
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
            x : x,
            y : y
        };
    }

    EZ.readCookie = function (name) {
        var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
        if (arr != null)
            return unescape(arr[2]);
        return null;
    }
    EZ.setCookie = function (name, value, time) {
        var Days = 1; //此 cookie ?被保存 30 天
        if (typeof time == 'undefined')
            time = Days * 24 * 60 * 60 * 1000;
        var exp = new Date(); //new Date("December 31, 9998");
        exp.setTime(exp.getTime() + time);
        document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
    }
    EZ.deleteCookie = function (name) {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval = EZ.readCookie(name);
        if (cval != null)
            document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
    }

    EZ.deleteChilds = function (f) {
        var childs = f.childNodes;
        for (var i = childs.length - 1; i >= 0; i--) {
            f.removeChild(childs[i]);
        }
    }

    //滑鼠座標
    EZ.mouseCoords = function (e) {
        e = e || window.event;
        if (e.pageX || e.pageY) {
            return {
                x : e.pageX,
                y : e.pageY
            };
        }
        return {
            x : e.clientX + document.body.scrollLeft - document.body.clientLeft,
            y : e.clientY + document.body.scrollTop - document.body.clientTop
        };
    }

    //時間 format
    EZ.Date = new function () {
        var self = this;
        var init = function (date) {
            var self = this,
            obj = date,
            Self = {
                getTime : function () {
                    return obj.getTime();
                },
                show : function (mask) {
                    return DateFormat(obj, mask);
                },
                this_week : function(startDay){
                    var now = new Date();
                    startDay = startDay||1; //0=sunday, 1=monday etc.
                    var d = now.getDay(); //get the current day
                    var weekStart = new Date(now.valueOf() - (d<=0 ? 7-startDay:d-startDay)*86400000); //rewind to start day
                    var weekEnd = new Date(weekStart.valueOf() + 6*86400000); //add 6 days to get last day
                    var firstday = EZ.Date(weekStart).show('Y-mm-dd');
                    var lastday = EZ.Date(weekEnd).show('Y-mm-dd');
                    return [firstday,lastday];
                },
                last_week : function(startDay){
                    var now = new Date();
                    startDay = startDay||1; //0=sunday, 1=monday etc.
                    var d = now.getDay(); //get the current day
                    var weekStart = new Date(now.valueOf() - (d<=0 ? 7-startDay:d-startDay)*86400000); //rewind to start day
                    var weekEnd = new Date(weekStart.valueOf() + 6*86400000); //add 6 days to get last day
                    var firstday = EZ.Date(weekStart.getTime()-604800000).show('Y-mm-dd');
                    var lastday = EZ.Date(weekEnd.getTime()-604800000).show('Y-mm-dd');
                    return [firstday,lastday];
                },
                year : function(i){
                    obj.setYear(obj.getYear()+i)
                    return Self;
                },
                month : function(i){
                    obj.setMonth(obj.getMonth()+i)
                    return Self;
                },
                week : function(i){
                    obj.setTime(obj.getTime()+i*604800000);
                    return Self;
                },
                day : function(i){
                    obj.setTime(obj.getTime()+i*86400000);
                    return Self;
                },
                hour : function(i){
                    obj.setTime(obj.getTime()+i*3600000);
                    return Self;
                },
                minute : function(i){
                    obj.setTime(obj.getTime()+i*60000);
                    return Self;
                },
                second : function(i){
                    obj.setTime(obj.getTime()+i*1000);
                    return Self;
                }
                
            }
            return Self;
        };
        var DateFormat = new function () {
            var token = /d{1,4}|m{1,4}|yy(?:yy)?|Y(?:Y)?|([HhMisTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g,
            //var token = /d{1,4}|m{1,4}|yy(?:yy)?|([HhMsTt])\1?|[LloSZ]|"[^"]*"|'[^']*'/g
            timezone = /\b(?:[PMCEA][SDP]T|(?:Pacific|Mountain|Central|Eastern|Atlantic) (?:Standad|Daylight|Prevailing) Time|(?:GMT|UTC)(?:[-+]\d{4})?)\b/g,
            timezoneClip = /[^-+\dA-Z]/g,
            pad = function (val, len) {
                val = String(val);
                len = len || 2;
                while (val.length < len)
                    val = "0" + val;
                return val;
            },
            dateFormat = {
                masks : {
                    //"default":            "ddd mmm dd yyyy HH:MM:ss",
                    "default" : "yyyy-mm-dd HH:MM:ss",
                    shortDate : "m/d/yy",
                    mediumDate : "mmm d, yyyy",
                    longDate : "mmmm d, yyyy",
                    fullDate : "dddd, mmmm d, yyyy",
                    shortTime : "h:MM TT",
                    mediumTime : "h:MM:ss TT",
                    longTime : "h:MM:ss TT Z",
                    isoDate : "yyyy-mm-dd",
                    isoTime : "HH:MM:ss",
                    isoDateTime : "yyyy-mm-dd'T'HH:MM:ss",
                    isoUtcDateTime : "UTC:yyyy-mm-dd'T'HH:MM:ss'Z'"
                },
                i18n : {
                    dayNames : [
                        "Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat",
                        "Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"
                    ],
                    monthNames : [
                        "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec",
                        "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
                    ]
                }
            };

            return function (date, mask, utc) {
                var dF = dateFormat;
                // You can't provide utc if you skip other args (use the "UTC:" mask prefix)
                if (arguments.length == 1 && Object.prototype.toString.call(date) == "[object String]" && !/\d/.test(date)) {
                    mask = date;
                    date = undefined;
                }

                // Passing date through Date applies Date.parse, if necessary
                date = date ? new Date(date) : new Date;
                if (isNaN(date)) {
                    throw SyntaxError("invalid date");

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
                    d : d,
                    dd : pad(d),
                    ddd : dF.i18n.dayNames[D],
                    dddd : dF.i18n.dayNames[D + 7],
                    m : m + 1,
                    mm : pad(m + 1),
                    mmm : dF.i18n.monthNames[m],
                    mmmm : dF.i18n.monthNames[m + 12],
                    y : String(y).slice(2),
                    yy : String(y).slice(2),
                    yyyy : y,
                    Y : y,
                    h : H % 12 || 12,
                    hh : pad(H % 12 || 12),
                    H : H,
                    HH : pad(H),
                    M : M,
                    i : M,
                    MM : pad(M),
                    ii : pad(M),
                    s : s,
                    ss : pad(s),
                    l : pad(L, 3),
                    L : pad(L > 99 ? Math.round(L / 10) : L),
                    t : H < 12 ? "a" : "p",
                    tt : H < 12 ? "am" : "pm",
                    T : H < 12 ? "A" : "P",
                    TT : H < 12 ? "AM" : "PM",
                    Z : utc ? "UTC" : (String(date).match(timezone) || [""]).pop().replace(timezoneClip, ""),
                    o : (o > 0 ? "-" : "+") + pad(Math.floor(Math.abs(o) / 60) * 100 + Math.abs(o) % 60, 4),
                    S : ["th", "st", "nd", "rd"][d % 10 > 3 ? 0 : (d % 100 - d % 10 != 10) * d % 10]
                };
                return mask.replace(token, function ($0) {
                    return $0 in flags ? flags[$0] : $0.slice(1, $0.length - 1);
                });
            }
        }()

        return function (date) {
            if (typeof date == 'string') {
                date = date.replace('-', '/').replace('-', '/');
                date = new Date(date);
                return init(date);
            } else if (typeof date == 'undefined') {
                date = new Date();
                return init(date);
            } else {
                return init(date);
            }
        }
    }();

    EZ.isEmpty = function (obj) {
        return (typeof obj == 'undefined' || obj == null || obj == '') ? true : false;
    }
    
    EZ.Timer = function (o) {
        var self = this,
        now,
        isRunning = false,
        runningId = 0,
        status = 'stop',
        timer_id,
        emptyFN = function () {},
        log = new function () {
            var timeStart = 0;

            return function (text, bool) {
                if (typeof bool == 'boolean' && bool == true) {
                    timeStart = new Date().getTime();
                }
                var nowTime = new Date().getTime();
                console.log((nowTime - timeStart) + ' : ' + text);
            }
        }
        (),
        opt = {
            callback : emptyFN,
            block : 1000,
            direct : 0 //0 逆向 1 正向
        ,
            max : 60,
            min : 0,
            renderTo : '',
            debug : 0 //每次跑nextsecond 是否要寫
        ,
            timer_name : EZ.id() //寫 log的時候會顯示出來 方便判斷是哪個timer
        },
        option = function (o) {
            if (typeof o == 'undefined')
                return;
            if (typeof o.callback != 'undefined') {
                opt.callback = o.callback;
            }
            if (typeof o.block != 'undefined') {
                opt.block = o.block;
            }
            if (typeof o.direct != 'undefined') {
                opt.direct = o.direct;
            }
            if (typeof o.max != 'undefined') {
                opt.max = o.max;
            }
            if (typeof o.callback != 'undefined') {
                opt.min = o.min;
            }
            if (typeof o.renderTo != 'undefined') {
                opt.renderTo = o.renderTo;
                if (typeof opt.renderTo == 'string') {
                    opt.renderTo = document.getElementById(opt.renderTo);
                }
            }
            if (typeof o.debug != 'undefined') {
                opt.debug = o.debug;
            }
            if (typeof o.timer_name != 'undefined') {
                opt.timer_name = o.timer_name;
            }
        }
        //秒數增加
    ,
        run = function () {
            var tid = new Date().getTime();
            timer_id = setTimeout(call(tid), opt.block);
        },
        call = function (id) {
            return function () {
                nextSecond(id);
            }
        },
        nextSecond = function (tid) {
            if (opt.debug == '1')
                console.log('timer ' + opt.timer_name + ' = ' + now);
            if (isRunning == false) {
                runningId = tid;
                isRunning = true;
            } else if (runningId != tid) {
                return;
            }
            if (opt.direct == 0) {
                now = Math.round((now - (opt.block / 1000)) * 10) / 10;
                // if(typeof opt.renderTo == 'undefined') alert(1);
                // if(opt.renderTo == null) alert(2);
                // if(opt.renderTo == '') alert(3);
                if (typeof opt.renderTo != 'undefined' && opt.renderTo != null) {
                    if (opt.renderTo.tagName == 'INPUT') {
                        opt.renderTo.value = now;
                    } else {
                        opt.renderTo.innerHTML = now;
                    }
                }
                if (now <= opt.min) {
                    opt.callback();
                    now = opt.max;
                }
            } else {
                now = Math.round((now + (opt.block / 1000)) * 10) / 10;
                if (typeof opt.renderTo != 'undefined' && opt.renderTo != null) {
                    if (opt.renderTo.tagName == 'INPUT') {
                        opt.renderTo.value = now;
                    } else {
                        opt.renderTo.innerHTML = now;
                    }
                }
                if (now >= opt.max) {
                    opt.callback();
                    now = opt.min;
                }
            }
            timer_id = setTimeout(call(tid), opt.block);
        },
        format = function (t) {
            if (opt.direct == 0) {
                t = opt.max;
            } else {
                t = opt.min;
            }
            return t;
        },
        Self = {
            play : function (o) {
                if (typeof o != 'undefined')
                    option(o);
                if (status == 'stop')
                    now = format(now);
                status = 'play';
                //log('play',true);
                run();
            },
            stop : function () {
                status = 'stop';
                clearTimeout(timer_id);
                isRunning = false;
            },
            pause : function () {
                status = 'pause';
                clearTimeout(timer_id);
                isRunning = false;
            },
            option : option,
            opt : function () {
                return opt;
            }
        };
        option(o);

        return Self;
    }

    //偵測瀏覽器 browser version OS isIE
    EZ.System = new function () {
        var self = this,
        init = function () {
            //瀏覽器
            browser = searchString(dataBrowser) || "unknow browser";
            //版本
            version = searchVersion(navigator.userAgent) || searchVersion(navigator.appVersion) || "unknow version";
            //OS
            OS = searchString(dataOS) || "unknow OS";
            isIE = (browser == 'Explorer') ? true : false;
            isChrome = (browser == 'Chrome') ? true : false;
            isFirefox = (browser == 'Firefox') ? true : false;
            isSafari = (browser == 'Safari') ? true : false;
            isOpera = (browser == 'Opera') ? true : false;

            //是否手機
            var ua = navigator.userAgent.toLowerCase();
            isMobile = false;
            for (var i = 0; i < dataMobiles.length; i++) {
                if (ua.indexOf(dataMobiles[i]) > 0) {
                    isMobile = true;
                    break;
                }
            }
            Self = {
                show : show,
                browser : browser,
                version : version,
                OS : OS,
                isIE : isIE,
                isChrome : isChrome,
                isFirefox : isFirefox,
                isOpera : isOpera,
                isSafari : isSafari,
                isMobile : isMobile,
                versionSearchString : versionSearchString,
                width : function () {
                    if (typeof(window.innerWidth) == 'number') {
                        //Non-IE
                        return window.innerWidth;
                    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
                        //IE 6+ in 'standards compliant mode'
                        return document.documentElement.clientWidth;
                    } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
                        //IE 4 compatible
                        return document.body.clientWidth;
                    }
                },
                height : function () {
                    if (typeof(window.innerHeight) == 'number') {
                        //Non-IE
                        return window.innerHeight;
                    } else if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) {
                        //IE 6+ in 'standards compliant mode'
                        return document.documentElement.clientHeight;
                    } else if (document.body && (document.body.clientWidth || document.body.clientHeight)) {
                        //IE 4 compatible
                        return document.body.clientHeight;
                    }
                }
            }
        },
        browser,
        version,
        OS,
        isIE,
        isChrome,
        isFirefox,
        isOpera,
        isSafari,
        versionSearchString,
        dataMobiles = [
            "midp", "j2me", "avant", "docomo", "novarra", "palmos", "palmsource",
            "240x320", "opwv", "chtml", "pda", "windows ce", "mmp/",
            "blackberry", "mib/", "symbian", "wireless", "nokia", "hand", "mobi",
            "phone", "cdm", "up.b", "audio", "sie-", "sec-", "samsung", "htc",
            "mot-", "mitsu", "sagem", "sony", "alcatel", "lg", "eric", "vx",
            "NEC", "philips", "mmm", "xx", "panasonic", "sharp", "wap", "sch",
            "rover", "pocket", "benq", "java", "pt", "pg", "vox", "amoi",
            "bird", "compal", "kg", "voda", "sany", "kdd", "dbt", "sendo",
            "sgh", "gradi", "jb", "dddi", "moto", "iphone", "android",
            "iPod", "incognito", "webmate", "dream", "cupcake", "webos",
            "s8000", "bada", "googlebot-mobile"
        ],
        dataBrowser = [{
                string : navigator.userAgent,
                subString : "Chrome",
                identity : "Chrome"
            }, {
                string : navigator.userAgent,
                subString : "OmniWeb",
                versionSearch : "OmniWeb/",
                identity : "OmniWeb"
            }, {
                string : navigator.vendor,
                subString : "Apple",
                identity : "Safari",
                versionSearch : "Version"
            }, {
                prop : window.opera,
                identity : "Opera",
                versionSearch : "Version"
            }, {
                string : navigator.vendor,
                subString : "iCab",
                identity : "iCab"
            }, {
                string : navigator.vendor,
                subString : "KDE",
                identity : "Konqueror"
            }, {
                string : navigator.userAgent,
                subString : "Firefox",
                identity : "Firefox"
            }, {
                string : navigator.vendor,
                subString : "Camino",
                identity : "Camino"
            }, { // for newer Netscapes (6+)
                string : navigator.userAgent,
                subString : "Netscape",
                identity : "Netscape"
            }, {
                string : navigator.userAgent,
                subString : "MSIE",
                identity : "Explorer",
                versionSearch : "MSIE"
            }, {
                string : navigator.userAgent,
                subString : "Gecko",
                identity : "Mozilla",
                versionSearch : "rv"
            }, { // for older Netscapes (4-)
                string : navigator.userAgent,
                subString : "Mozilla",
                identity : "Netscape",
                versionSearch : "Mozilla"
            }
        ],
        dataOS = [{
                string : navigator.platform,
                subString : "Win",
                identity : "Windows"
            }, {
                string : navigator.platform,
                subString : "Mac",
                identity : "Mac"
            }, {
                string : navigator.userAgent,
                subString : "iPhone",
                identity : "iPhone/iPod"
            }, {
                string : navigator.platform,
                subString : "Linux",
                identity : "Linux"
            }
        ],
        searchString = function (data) {
            for (var i = 0; i < data.length; i++) {
                var dataString = data[i].string;
                var dataProp = data[i].prop;
                versionSearchString = data[i].versionSearch || data[i].identity;
                if (dataString) {
                    if (dataString.indexOf(data[i].subString) != -1)
                        return data[i].identity;
                } else if (dataProp)
                    return data[i].identity;
            }
        },
        searchVersion = function (dataString) {
            var index = dataString.indexOf(versionSearchString);
            if (index == -1)
                return;
            return parseFloat(dataString.substring(index + versionSearchString.length + 1));
        },
        show = function () {
            return [
                "browser : " + Self.browser, "version : " + Self.version, "OS : " + Self.OS, "isIE : " + Self.isIE, "isChrome : " + Self.isChrome, "isFirefox : " + Self.isFirefox, "isSafari : " + Self.isSafari, "isOpera : " + Self.isOpera, "isMobile : " + Self.isMobile, "versionSearchString : " + Self.versionSearchString, "width : " + Self.width(), "height : " + Self.height()
            ]
        },
        Self
        init();
        return Self;
    }

    EZ.loadMask = function (o) {
        var self = this,
        opt = {
            renderTo : null,
            overflow : '',
            position : '',
            zIndex : 100,
            backgroundColor : '#ccc',
            opacity : 50,
            url : '',
            noMsg : false // true 中間就不會有文字 或者 圈圈
        },
        proxy,
        proxyMsg,
        proxyText,
        toBody = false,
        createProxy = function () {
            proxy = document.createElement('div');
            proxy.className = 'EZloadmask';
            proxy.style.zIndex = opt.zIndex;
            proxy.style.display = "none";
            proxy.style.backgroundColor = opt.backgroundColor;
            proxy.style.filter = "progid:DXImageTransform.Microsoft.Alpha(Opacity=" + opt.opacity + ")";
            proxy.style.MozOpacity = opt.opacity / 100;
            proxy.style.opacity = opt.opacity / 100;

            proxyMsg = document.createElement('div');
            proxyMsg.className = 'EZloadmask-msg';
            proxyMsg.style.zIndex = opt.zIndex + 1;
            proxyMsg.style.display = "none";

            proxyText = document.createElement('div');
            proxyText.style.display = "none";
            proxyText.style.zIndex = opt.zIndex + 2;
            proxyMsg.appendChild(proxyText);
        },
        init = function (o) {
            if (typeof o == 'undefined') {
                return;
            }
            if (typeof o.zIndex != 'undefined' && o.zIndex != '') {
                opt.zIndex = o.zIndex;
            }
            if (typeof o.backgroundColor != 'undefined' && o.backgroundColor != '') {
                opt.backgroundColor = o.backgroundColor;
            }
            if (typeof o.opacity != 'undefined' && o.opacity != '') {
                opt.opacity = o.opacity;
            }
            if (typeof o.url != 'undefined' && o.url != '') {
                opt.url = o.url;
            }
            if (typeof o.noMsg != 'undefined' && o.noMsg != '') {
                opt.noMsg = o.noMsg;
            }
            if (typeof o.renderTo != 'undefined') {
                createProxy();
                if (typeof o.renderTo == 'string' && o.renderTo.toLowerCase() == 'body') {
                    opt.renderTo = document.body;
                    //proxy.style.width = (document.body.clientWidth>window.screen.availWidth)?document.body.clientWidth:window.screen.availWidth + 'px';
                    //proxy.style.height = (document.body.clientHeight>window.screen.availHeight)?document.body.clientHeight:(window.screen.availHeight-window.screenTop) + 'px';
                    //proxy.style.width = EZ.System.width();
                    //proxy.style.height = EZ.System.height();
                    toBody = true;
                } else if (typeof o.renderTo == 'string') {
                    opt.renderTo = document.getElementById(o.renderTo);
                    var r = opt.renderTo;
                    //proxy.style.width = r.clientWidth + 'px';
                    //proxy.style.height = r.clientHeight + 'px';
                    toBody = false;
                } else {
                    opt.renderTo = o.renderTo;
                    var r = opt.renderTo;
                    //proxy.style.width = r.clientWidth + 'px';
                    //proxy.style.height = r.clientHeight + 'px';
                    toBody = false;
                }
            }
        },
        adjustCenter = function () {
            var w = proxy.clientWidth;
            var h = proxy.clientHeight;
            var _w = proxyMsg.clientWidth;
            var _h = proxyMsg.clientHeight;
            //置中
            var top = (h - _h) / 2;
            var left = (w - _w) / 2;
            proxyMsg.style.left = left + 'px';
            proxyMsg.style.top = top + 'px';
            //如果parent 不在可是範圍 就置中 否則放在可視範圍中間

            //如果 top 超過可視範圍, 調整回中間
            var minTop = document.documentElement.scrollTop || document.body.scrollTop || 0;
            var maxTop = EZ.System.height() + minTop;
            var posMsg = EZ.getPos(proxyMsg);
            if (posMsg.y < minTop || posMsg.y > maxTop) {
                var pos = EZ.getPos(proxy);
                //alert(minTop+' '+EZ.System.height()+' '+_h+' '+pos.y);
                top = minTop + (EZ.System.height() - _h) / 2 - pos.y;
                proxyMsg.style.left = left + 'px';
                proxyMsg.style.top = top + 'px';
            }
            //alert(left+' = '+top);
        },
        show = function (text, delay) {
            var r = opt.renderTo;
            opt.overflow = r.style.overflow;
            opt.position = r.style.position;
            r.style.overflow = 'hidden';
            r.style.position = 'relative';
            opt.renderTo.appendChild(proxy);
            opt.renderTo.appendChild(proxyMsg);
            proxy.style.display = "block";
            //alert(r.clientWidth+' '+r.clientHeight);
            if (toBody == true) {
                var h = document.body.clientHeight;
                var _h = EZ.System.height();
                if (h < _h)
                    h = _h;
                var w = document.body.clientWidth;
                var _w = EZ.System.width();
                if (w < _w)
                    w = _w;
                proxy.style.width = w + 'px';
                proxy.style.height = h + 'px';
            } else {
                proxy.style.width = r.clientWidth + 'px';
                proxy.style.height = r.clientHeight + 'px';
            }
            if (typeof text == 'string' && text != '') {
                proxyText.innerHTML = text;
                proxyMsg.style.display = "inline-block";
                proxyText.style.display = "inline-block";
                adjustCenter();
            } else {
                if (opt.noMsg == false)
                    proxy.className = proxy.className.toString() + ' EZloading_l';
                if (opt.url != '') {
                    proxy.style.backgroundImage = "url('" + opt.url + "')";
                    proxy.style.backgroundRepeat = "no-repeat";
                    proxy.style.backgroundPosition = "center";
                }
            }
            if (typeof delay == 'number') {
                setTimeout(hide, delay);
            }
        },
        hide = function () {
            var r = opt.renderTo;
            //alert(typeof opt.overflow+' = '+typeof opt.position);
            r.style.overflow = opt.overflow;
            r.style.position = opt.position;
            proxy.style.display = "none";
            proxyMsg.style.display = "none";
            proxyText.style.display = "none";
            try {
                proxy.parentNode.removeChild(proxy);
                proxyMsg.parentNode.removeChild(proxyMsg);
            } catch (e) {}
        },
        Self = {
            show : show,
            hide : hide
        }
        init(o);
        return Self;
    };
    /*
    EZ.loading = function (render, bool, o) {
        var opt = {
            attachment : '',
            position : ''
        }
        if (typeof o != 'undefined') {
            if (typeof o.att != 'undefined') {
                opt.attachmemt = o.att;
            }
            if (typeof o.pos != 'undefined') {
                opt.position = o.pos;
            }
        }
        if (typeof render != 'undefined') {
            if (typeof render == 'string') {
                render = document.getElementById(render);
            }
            var cls = render.className.toString();
            if (bool == true) {
                render.className = cls + ' EZloading';

                if (opt.attachment != '')
                    render.style.backgroundAttachment = opt.attachment;
                if (opt.position != '')
                    render.style.backgroundPosition = opt.position;
            } else {
                render.className = cls.replace(' EZloading', '');
            }
        }
    }
    */
    // 小圈圈
    EZ.loadingbar = {
        show : EZ.emptyFN
        ,hide : EZ.emptyFN
    };
    EZ.loading = function(_o){
        var self=this
        ,opt
        ,img
        ,Self = {
            show : function(){
                opt.renderTo.appendChild(img);
            }
            ,hide : function(){
                if(img.parentNode==opt.renderTo) opt.renderTo.removeChild(img);
            }
        }
        opt=_o;
        if(typeof opt.renderTo=='string') opt.renderTo = document.getElementById(opt.renderTo);
        if(!opt.type) opt.type = '75';
        switch(opt.type)
        {
            case 'long':
                opt.type='24';
                break;
            case 'small':
                opt.type='75';
                break;
            default:
                //opt.type='75';
                break;
        }
        img = document.createElement('img');
        img.src="/plugin/EZ/images/loading/loading ("+opt.type+").gif";
        return Self;
    }
    EZ.is_array = function (obj) {
        return (typeof(obj) == 'object' && (obj instanceof Array));
    };

    //檔案上傳
    EZ.FileUpload = function () {
        var self = this,
        uploading = false,
        style = 0,
        loading = function () {
            if (uploading == false) {
                DOM.button.innerHTML = opt.buttonName + EZ.lang('完成');
                setTimeout(function(){
                    DOM.button.innerHTML = opt.buttonName;
                },2000);
                DOM.button.style.backgroundColor = '#880000';
                return;
            }
            if (style == 0) {
                style = 1;
                DOM.button.style.backgroundColor = '#880000';
            } else {
                DOM.button.style.backgroundColor = '#CC0000';
                style = 0;
            }
            setTimeout(loading, 500);
        },
        formData = {
            cmd : '969',
            parame : {
                uid : uid
            }
        },
        DOM = {
            form : null,
            button : null,
            iframe : null,
            file : null,
            submit : null,
            cmd : null
        },
        ID = {
            iframe : EZ.id()
        },
        onload = function (Self, e) {
            uploading = false;
            //opt.onload(Self, e);
            opt.onload.apply(Self,[Self,e]);
        },
        opt = {
            buttonName : EZ.lang('上傳檔案')
            //選好檔案
            ,onchange : function (Self, e) {
                Self.submit();
            }
            ,_onchange : function(Self,e){
                
            }
            //點擊按鈕
            ,onclick : function (Self, e) {}
            //載完檔案
            ,onload : function (Self, e) {}
            //php路徑
            ,url : '../pub/gateway.php'
            ,renderTo : null
            ,cls:'EZuploadbutton'
        },
        isInit = false,
        init = function () {
            if (isInit == false) {
                DOM.button = document.createElement('a');
                DOM.button.className = opt.cls;
                DOM.button.style.fontSize = '12px';
                DOM.button.onclick = function (e) {
                    opt.onclick(Self, e);
                }

                DOM.form = document.createElement('form');
                DOM.form.style.display = 'inline-block';
                //DOM.form.enctype='multipart/form-data';
                DOM.form.setAttribute('enctype', 'multipart/form-data');
                DOM.form.setAttribute('method', 'post');
                DOM.form.setAttribute('target', ID.iframe);
                //DOM.form.method = 'post';
                //DOM.form.target = ID.iframe;
                DOM.form.style.margin = '0';
                DOM.form.style.padding = '0';
                DOM.form.style.position = 'relative';
                DOM.form.style.overflow = 'hidden';
                try {
                    DOM.iframe = document.createElement('<iframe name="' + ID.iframe + '">');
                } catch (e) {
                    DOM.iframe = document.createElement('iframe');
                    DOM.iframe.setAttribute('name', ID.iframe);
                }
                DOM.iframe.style.display = 'none';

                document.body.appendChild(DOM.iframe);
                if (DOM.iframe.attachEvent) {
                    var func = function () {
                        DOM.iframe.attachEvent("onload", function (e) {
                            onload(Self, e);
                        });
                    }
                    setTimeout(func, 1000);
                } else {
                    DOM.iframe.onload = function (e) {
                        onload(Self, e);
                    }
                }
                try {
                    DOM.file = document.createElement('<input name="imagefile">');
                    DOM.file.setAttribute('type', 'file');
                } catch (e) {
                    DOM.file = document.createElement('input');
                    DOM.file.setAttribute('name', 'imagefile');
                    DOM.file.setAttribute('type', 'file');
                }

                //DOM.file.type='file';
                DOM.file.style.margin = '0';
                DOM.file.style.padding = '0';
                DOM.file.style.fontSize = '118px';
                DOM.file.style.position = 'absolute';
                DOM.file.style.right = '0px';
                DOM.file.style.top = '0px';
                DOM.file.style.cursor = 'pointer';
                DOM.file.className = 'EZopacity0';
                //DOM.file.setAttribute('multiple','multiple');
                //DOM.file.setAttribute('accept','image/*');
                DOM.file.onchange = function (e) {
                    opt.onchange(Self, e);
                    opt._onchange.apply(Self,[Self,e]);
                }

                //<input type="hidden" name="cmd" value='{"cmd":"969","parame":{"uid":"489443636"}}'>
                try {
                    DOM.cmd = document.createElement('<input type="hidden" name="cmd">');
                    DOM.cmd.value = '';
                } catch (e) {
                    DOM.cmd = document.createElement('input');
                    DOM.cmd.setAttribute('type', 'hidden');
                    DOM.cmd.setAttribute('name', 'cmd');
                    DOM.cmd.setAttribute('value', '');
                }
                DOM.form.appendChild(DOM.cmd);
                DOM.form.appendChild(DOM.button);
                DOM.form.appendChild(DOM.file);

                //DOM.submit = document.createElement('input');
                //DOM.submit.type='submit';
                //DOM.form.appendChild(DOM.submit);

                opt.renderTo.appendChild(DOM.form);
                //document.body.appendChild(DOM.form);
            }
            DOM.form.action = opt.url;
            DOM.button.innerHTML = opt.buttonName;
            //renderTo
            isInit = true;
        },
        Self = {
            init : function (o) {
                opt.buttonName = o.buttonName || opt.buttonName;
                opt._onchange = o.onchange || opt._onchange;
                opt.onclick = o.onclick || opt.onclick;
                opt.onload = o.onload || opt.onload;
                opt.url = o.url || opt.url;
                opt.renderTo = o.renderTo || opt.renderTo;
                opt.cls = o.cls || opt.cls;
                if (typeof opt.renderTo == 'string') {
                    opt.renderTo = document.getElementById(opt.renderTo);
                }
                if (typeof opt.renderTo != 'undefined') {
                    init();
                }
            },
            GET : {},
            POST : {},
            submit : function () {
                //DOM.form.style.display='block';
                //DOM.iframe.style.display='block';
                uploading = true;
                DOM.button.innerHTML = EZ.lang('上傳中...');
                DOM.cmd.setAttribute('value', EZ.JSON.encode(formData));
                DOM.form.action = opt.url + '?c=' + formData['cmd'];
                loading();
                DOM.form.submit();
                //DOM.form.display='none';
                //DOM.submit.click();
            },
            set : function (k, v) {
                //formData
                formData['parame'][k] = v;
                //'{"cmd":"969","parame":{"uid":""}}'
            },
            cmd : function (cmd) {
                formData['cmd'] = cmd;
            },
            content : function () {
                var iFrame = DOM.iframe;
                var iFrameBody = '';
                if (iFrame.contentDocument) {
                    iFrameBody = iFrame.contentDocument.getElementsByTagName('body')[0];
                } else if (iFrame.contentWindow) {
                    iFrameBody = iFrame.contentWindow.document.getElementsByTagName('body')[0];
                }
                return iFrameBody.innerHTML;
            }
        }
        return Self;
    };

    EZ.StopWatch = function () {
        var 
        c = 0
        ,ts = []
        ,Self = {
            play : function () {
                ts[ts.length] = new Date().getTime();
                return ts[ts.length-1];
            },
            stop : function (show) {
                c += (new Date().getTime()) - ts[ts.length-1];
                var tmp = c;
                c=0;
                if(show) console.log(tmp);
                return tmp;
            },
            pause : function (show) {
                c += (new Date().getTime()) - ts[ts.length-1];
                if(show) console.log(c);
                return c;
            }
        }
        return Self;
    };
    EZ.divFreeze = function(renderTo){
        var self = this
        ,getScrollXY = function(){
            var scrOfX = 0, scrOfY = 0;
            if (typeof(window.pageYOffset) == 'number') {
                //Netscape compliant
                scrOfY = window.pageYOffset;
                scrOfX = window.pageXOffset;
            } else if (document.body && (document.body.scrollLeft || document.body.scrollTop)) {
                //DOM compliant
                scrOfY = document.body.scrollTop;
                scrOfX = document.body.scrollLeft;
            } else if (document.documentElement && (document.documentElement.scrollLeft || document.documentElement.scrollTop)) {
                //IE6 standards compliant mode
                scrOfY = document.documentElement.scrollTop;
                scrOfX = document.documentElement.scrollLeft;
            }
            return [scrOfX, scrOfY];
        }
        ,max_now=0
        ,min_now=0
        ,maxline=0
        ,minline=0
        ,status=0
        ,sidebar
        ,init = function(){
            if(typeof renderTo == 'string') renderTo = document.getElementById(renderTo);
            sidebar = renderTo;
            
            minline = EZ.posxy(sidebar)[1];
            maxline = EZ.posxy(sidebar)[1]+sidebar.clientHeight;
        }
        ,Self={
            scroll : function(e){
                e = e || window.event;
                var offsetY = getScrollXY()[1];
                var max_now = offsetY+EZ.System.height();
                var min_now = offsetY;
                //console.log('min '+minline+'>'+min_now);
                //console.log('max '+max_now+' > '+maxline);    
                var nowstatus=0;
                if(min_now <= minline){
                    nowstatus = 1;
                }else if((max_now-2) > maxline || min_now < minline){
                    //console.log(max_now +'>='+ maxline);
                    //console.log(min_now +'<='+ minline);
                    nowstatus = 2;
                }else{
                    nowstatus = 3;
                }
                //console.log(nowstatus);
                if(status!=nowstatus){
                    status=nowstatus;
                    switch(nowstatus)
                    {
                        case 2:
                            sidebar.style.position='fixed';
                            if(EZ.System.height()>sidebar.clientHeight){
                                sidebar.style.top='0';
                            }else{
                                sidebar.style.bottom='0';
                            }
                            break;
                        default:
                            sidebar.style.position='relative';
                            sidebar.style.bottom='auto';
                            sidebar.style.top='auto';
                            break;
                    }
                }
            }
            ,resize : function(e){
                //init();
            }
        }
        init();
        
        return Self;
    };
    
    //共用變數
    EZ.v={};
    //共用function
    EZ.f={};
    
    EZ.register = new function(){
        var self=this
        ,events={}
        ,Self={
            trigger:function(e){
                if(!EZ.isEmpty(e.name)){
                    var fns = events[e.name] || [];
                    for(var i=0;i<fns.length;i++)
                    {
                        fns[i].apply(this,[e]);
                    }
                }
            }
            ,setEvent:function(e){
                if(EZ.isEmpty(e.name)) return;
                if(EZ.isEmpty(events[e.name]))events[e.name]=[];
                events[e.name].push(e.fn);
            }
        };
        return Self;
    }();
    EZ.trigger=EZ.register.trigger;
    EZ.setEvent=EZ.register.setEvent;
    
    EZ.MVVM=new function(){
        var self=this
        ,_Views=[]
        ,isFound=false
        ,pushstate = function(v,p){
            //if(typeof history.pushState != 'undefined' && v.indexOf('.show') >=0 && typeof p.container == 'string'){
            if(typeof history.pushState != 'undefined' && typeof p.container == 'string'){
                var view = v;
                var param = EZ.clone(p);
                // 判斷 是否是 當前畫面的子View
                isFound = false;
                _Views = find_view(_Views,view,param,0);
                var state = {
                    Views : _Views
                }
                //重組網址參數
                var g = EZ._GET;
                g['Views'] = EZ.JSON.encode(_Views);
                var tmp = [];
                for(var key in g){
                    if(g.hasOwnProperty(key)){
                        tmp[tmp.length] = key+'='+g[key];
                    }
                }
                history.pushState(state,'','?'+tmp.join('&'));
            }
        }
        //改變狀態
        ,gostate = function(vs){
            console.log('gostate');
            console.log(vs);
            if(typeof vs=='undefined' || vs==''){
                console.log('gostate 1');
                return;
            }
            if(typeof vs == 'string' && vs != ''){
                try{
                    _Views = eval('('+vs+')');
                    vs = eval('('+vs+')');
                }catch(e){
                    _Views = [];
                    vs = [];
                    console.log('gostate 2');
                    console.log(e.toString());
                }
            }
            
            for(var i=0,imax=vs.length;i<imax;i++)
            {
                var v = vs[i];
                if(typeof v[0] != 'undefined' && v[0] != ''){
                    console.log('gostate 3 '+v[0]);
                    Self.execute(v[0],v[1]);
                }
            }
        }
        ,onpopstate = function(e){
            var state = e.state;
            if(state){
                console.log(state.Views);
                gostate(state.Views);
            }
        }
        ,find_view = function(views,view,param,lv){
            var level = lv+1;
            var isSub = false;
            if(isFound == false){
                for(var i=0,imax=views.length;i<imax;i++){
                    var v = views[i];
                    var _v = v[0];
                    var _p = v[1];
                    var container = v[1].container;
                    
                    //1. 先找 container 是否有存在的
                    if(container == param.container){
                        views[i] = [view,param];
                        isFound = true;
                        break;
                    }
                    //2.是不是 子View
                    var tmp = _v.split('.');
                    _v = _v.replace(tmp[tmp.length-1],'');
                    //console.log(level+' m '+view);
                    //console.log(level+' s '+_v);
                    if(view.indexOf(_v) >= 0){
                        //console.log(level+' 222 ' + view);
                        //表示是子view
                        if(typeof views[i][1].Views == 'undefined'){
                            views[i][1].Views = [[view,param]];
                            isFound = true;
                        }else{
                            views[i][1].Views = find_view(views[i][1].Views,view,param,level);
                            isSub = true;
                        }
                    }
                }
            }
            
            // 都找不到    push
            if(isFound == false && isSub == false){
                //console.log(level+' 333 ' + view);
                views[views.length] = [view,param];
            }
            return views;
        }
        ,Self={
            click : function(e){
                var e = e || window.event;
                var dom = e.target || e.srcElement;
                var controller = $(dom).attr('controller');
                if(typeof controller == 'undefined')return;
                console.log('click = '+controller);
                var p = {};
                var attrs = dom.attributes;
                for(var i=0,imax=attrs.length;i<imax;i++){
                    var a = attrs[i];
                    p[a.name]=a.value;
                }
                // var ck = EZ.click_fns[controller];
                // if(typeof ck == 'function'){
                    // ck.apply(this,[p,e,dom]);
                // }
                Self.exec(controller,p);
                //e.preventDefault(); //阻止默認行為 (form submit)
                e.stopPropagation(); //阻止event flow
            }
            //
            ,execute : function(controller,p){
                p=p||{};
                if(typeof p == 'string'){
                    try{
                        p=eval('('+p+')');
                    }catch(e){
                        p={}
                    }
                }
                var fn = EZ.controller[controller];
                if(!fn){
                    //偷懶 預設動作 把model跟 view 找出來使用 直接call
                    // 有model就先 call model 再把資料丟給view
                    var c = controller.split('.');
                    var method = c[c.length-1];
                    c.pop();
                    c.shift();
                    var name = c.join('.');
                    if(EZ.model.is_exists(name) && EZ.view.is_exists(name)){
                        var model = EZ.model(name);
                        var view = EZ.view(name);
                        //console.log('default1 name='+name+' , method = '+method);
                        if(typeof model[method] == 'function'){
                            p.callback=view[method];
                            model[method].call(this,p);
                        }else if(typeof view[method] == 'function'){
                            view[method].apply(this,[p]);
                        }
                    }else if(EZ.view.is_exists(name)){
                        console.log('default2 name='+name+' , method = '+method);
                        var view = EZ.view(name);
                        if(typeof view[method] == 'function')view[method].apply(this,[p]);
                    }
                }else{
                    fn.apply(this,[p]);
                }
                /*
                var c = controller.split('.');
                var method = c[c.length-1];
                var type=c[0];
                c.pop();
                c.shift();
                var view = c.join('.');
                //var fn = EZ.view(view)[method];
                var fn = EZ[type](view)[method];
                if(!EZ.isEmpty(fn)) fn.apply(this,[p]);
                */
            }
            ,exec:function(controller,p){
                pushstate(controller,p);
                Self.execute(controller,p);
            }
            ,gostate : gostate
            ,onpopstate : onpopstate
        };
        return Self;
    }();
    EZ.exec = EZ.MVVM.exec;
    EZ.click = EZ.MVVM.click;
    EZ.extent_click = EZ.MVVM.extent_click;
    EZ.gostate = EZ.MVVM.gostate;
    EZ.onpopstate = EZ.MVVM.onpopstate;
    
    EZ.controller = {};
    EZ.click_fns={};
    //View , Model
    EZ.view = new function () {
        var self = this,
        views = {},
        _constructors = {},
        Self = function (key, _constructor) {
            if (typeof _constructor == 'undefined') {
                if (typeof _constructors[key] == 'undefined') {
                    alert('View: ' + key + ' is not defined');
                    return;
                }
                if (typeof views[key] == 'undefined') {
                    views[key] = new _constructors[key]();
                }
                return views[key];
            } else {
                if (typeof _constructors[key] == 'undefined') {
                    _constructors[key] = _constructor;
                } else {
                    alert('View key(' + key + ') is already exist. ');
                }
            }
        };
        Self.constructor = function (key) {
            return _constructors[key] || function () {};
        }
        Self.is_exists = function(key){
            if(typeof _constructors[key]=='undefined') return false;
            else return true;
        }
        return Self;
    }();

    EZ.model = new function () {
        var self = this,
        models = {},
        _constructors = {},
        Self = function (key, _constructor) {
            if (typeof _constructor == 'undefined') {
                if (typeof _constructors[key] == 'undefined') {
                    alert('Model: ' + key + ' is not defined');
                    return;
                }
                return new _constructors[key]();
            } else {
                if (typeof _constructors[key] == 'undefined') {
                    _constructors[key] = _constructor;
                } else {
                    alert('Model key(' + key + ') is already exist. ');
                }
            }
        };
        Self.constructor = function (key) {
            return _constructors[key] || function () {};
        }
        Self.is_exists = function(key){
            if(typeof _constructors[key]=='undefined') return false;
            else return true;
        }
        return Self;
    }();
    
    //jquery
    if(typeof $ != 'undefined'){
        EZ.ajax = function (URL, _POST, callback) {
            _POST=_POST||{};
            jQuery.ajax({
                type : 'POST',
                data : _POST,
                cache : false,
                url : URL,
                success : function (result, request) { 
                    //console.log(result);
                    if (typeof(callback) != 'undefined') {
                        callback(result);
                    }
                }
            });
        };
    }else{
        EZ.ajax = new function (url, _post, callback) {
            var create_ajax = function () {
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
                    alert('Giving up :( Cannot create an XMLHTTP instance');
                    return false;
                }
                return http_request;
            };

            return function (url, _post, callback) {
                var postdata;
                if (typeof _post != 'undefined') {
                    postdata = '';
                    for (var key in _post) {
                        postdata += '&' + key + '=' + _post[key];
                    }
                } else {
                    postdata = null;
                }
                var tt = new Date().getTime();
                if (url.indexOf('?') == -1)
                    url += '?tt=' + tt;
                else
                    url += '&tt=' + tt;

                var xml_request = create_ajax();
                xml_request.open('POST', url, true);
                xml_request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                //xml_request.setRequestHeader("Connection", "close");
                xml_request.send(postdata);
                xml_request.onreadystatechange = function () {
                    if (xml_request.readyState == 4) {
                        if (xml_request.status == 200) {
                            if (typeof callback != 'undefined') {
                                callback(xml_request.responseText);
                            }
                        } else {
                            //alert ("伺服器處理錯誤");
                            alert("Server is no response");
                        }
                    }
                }
            }
        }();
    }
    window.EZ = EZ;
})(window);

if (typeof JSON !== 'object') {
    JSON = {};
}

(function () {
    'use strict';

    function f(n) {
        // Format integers to have at least two digits.
        return n < 10 ? '0' + n : n;
    }

    if (typeof Date.prototype.toJSON !== 'function') {

        Date.prototype.toJSON = function () {

            return isFinite(this.valueOf())
                ? this.getUTCFullYear()         + '-' +
                    f(this.getUTCMonth() + 1) + '-' +
                    f(this.getUTCDate())            + 'T' +
                    f(this.getUTCHours())         + ':' +
                    f(this.getUTCMinutes())     + ':' +
                    f(this.getUTCSeconds())     + 'Z'
                : null;
        };

        String.prototype.toJSON            =
            Number.prototype.toJSON    =
            Boolean.prototype.toJSON = function () {
                return this.valueOf();
            };
    }

    var cx = /[\u0000\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        escapable = /[\\\"\x00-\x1f\x7f-\x9f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g,
        gap,
        indent,
        meta = {        // table of character substitutions
            '\b': '\\b',
            '\t': '\\t',
            '\n': '\\n',
            '\f': '\\f',
            '\r': '\\r',
            '"' : '\\"',
            '\\': '\\\\'
        },
        rep;


    function quote(string) {

// If the string contains no control characters, no quote characters, and no
// backslash characters, then we can safely slap some quotes around it.
// Otherwise we must also replace the offending characters with safe escape
// sequences.

        escapable.lastIndex = 0;
        return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
            var c = meta[a];
            return typeof c === 'string'
                ? c
                : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
        }) + '"' : '"' + string + '"';
    }


    function str(key, holder) {

// Produce a string from holder[key].

        var i,                    // The loop counter.
            k,                    // The member key.
            v,                    // The member value.
            length,
            mind = gap,
            partial,
            value = holder[key];

// If the value has a toJSON method, call it to obtain a replacement value.

        if (value && typeof value === 'object' &&
                typeof value.toJSON === 'function') {
            value = value.toJSON(key);
        }

// If we were called with a replacer function, then call the replacer to
// obtain a replacement value.

        if (typeof rep === 'function') {
            value = rep.call(holder, key, value);
        }

// What happens next depends on the value's type.

        switch (typeof value) {
        case 'string':
            return quote(value);

        case 'number':

// JSON numbers must be finite. Encode non-finite numbers as null.

            return isFinite(value) ? String(value) : 'null';

        case 'boolean':
        case 'null':

// If the value is a boolean or null, convert it to a string. Note:
// typeof null does not produce 'null'. The case is included here in
// the remote chance that this gets fixed someday.

            return String(value);

// If the type is 'object', we might be dealing with an object or an array or
// null.

        case 'object':

// Due to a specification blunder in ECMAScript, typeof null is 'object',
// so watch out for that case.

            if (!value) {
                return 'null';
            }

// Make an array to hold the partial results of stringifying this object value.

            gap += indent;
            partial = [];

// Is the value an array?

            if (Object.prototype.toString.apply(value) === '[object Array]') {

// The value is an array. Stringify every element. Use null as a placeholder
// for non-JSON values.

                length = value.length;
                for (i = 0; i < length; i += 1) {
                    partial[i] = str(i, value) || 'null';
                }

// Join all of the elements together, separated with commas, and wrap them in
// brackets.

                v = partial.length === 0
                    ? '[]'
                    : gap
                    ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']'
                    : '[' + partial.join(',') + ']';
                gap = mind;
                return v;
            }

// If the replacer is an array, use it to select the members to be stringified.

            if (rep && typeof rep === 'object') {
                length = rep.length;
                for (i = 0; i < length; i += 1) {
                    if (typeof rep[i] === 'string') {
                        k = rep[i];
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            } else {

// Otherwise, iterate through all of the keys in the object.

                for (k in value) {
                    if (Object.prototype.hasOwnProperty.call(value, k)) {
                        v = str(k, value);
                        if (v) {
                            partial.push(quote(k) + (gap ? ': ' : ':') + v);
                        }
                    }
                }
            }

// Join all of the member texts together, separated with commas,
// and wrap them in braces.

            v = partial.length === 0
                ? '{}'
                : gap
                ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}'
                : '{' + partial.join(',') + '}';
            gap = mind;
            return v;
        }
    }

// If the JSON object does not yet have a stringify method, give it one.

    if (typeof JSON.stringify !== 'function') {
        JSON.stringify = function (value, replacer, space) {

// The stringify method takes a value and an optional replacer, and an optional
// space parameter, and returns a JSON text. The replacer can be a function
// that can replace values, or an array of strings that will select the keys.
// A default replacer method can be provided. Use of the space parameter can
// produce text that is more easily readable.

            var i;
            gap = '';
            indent = '';

// If the space parameter is a number, make an indent string containing that
// many spaces.

            if (typeof space === 'number') {
                for (i = 0; i < space; i += 1) {
                    indent += ' ';
                }

// If the space parameter is a string, it will be used as the indent string.

            } else if (typeof space === 'string') {
                indent = space;
            }

// If there is a replacer, it must be a function or an array.
// Otherwise, throw an error.

            rep = replacer;
            if (replacer && typeof replacer !== 'function' &&
                    (typeof replacer !== 'object' ||
                    typeof replacer.length !== 'number')) {
                throw new Error('JSON.stringify');
            }

// Make a fake root object containing our value under the key of ''.
// Return the result of stringifying the value.

            return str('', {'': value});
        };
    }


// If the JSON object does not yet have a parse method, give it one.

    if (typeof JSON.parse !== 'function') {
        JSON.parse = function (text, reviver) {

// The parse method takes a text and an optional reviver function, and returns
// a JavaScript value if the text is a valid JSON text.

            var j;

            function walk(holder, key) {

// The walk method is used to recursively walk the resulting structure so
// that modifications can be made.

                var k, v, value = holder[key];
                if (value && typeof value === 'object') {
                    for (k in value) {
                        if (Object.prototype.hasOwnProperty.call(value, k)) {
                            v = walk(value, k);
                            if (v !== undefined) {
                                value[k] = v;
                            } else {
                                delete value[k];
                            }
                        }
                    }
                }
                return reviver.call(holder, key, value);
            }


// Parsing happens in four stages. In the first stage, we replace certain
// Unicode characters with escape sequences. JavaScript handles many characters
// incorrectly, either silently deleting them, or treating them as line endings.

            text = String(text);
            cx.lastIndex = 0;
            if (cx.test(text)) {
                text = text.replace(cx, function (a) {
                    return '\\u' +
                        ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
                });
            }

// In the second stage, we run the text against regular expressions that look
// for non-JSON patterns. We are especially concerned with '()' and 'new'
// because they can cause invocation, and '=' because it can cause mutation.
// But just to be safe, we want to reject all unexpected forms.

// We split the second stage into 4 regexp operations in order to work around
// crippling inefficiencies in IE's and Safari's regexp engines. First we
// replace the JSON backslash pairs with '@' (a non-JSON character). Second, we
// replace all simple value tokens with ']' characters. Third, we delete all
// open brackets that follow a colon or comma or that begin the text. Finally,
// we look to see that the remaining characters are only whitespace or ']' or
// ',' or ':' or '{' or '}'. If that is so, then the text is safe for eval.

            if (/^[\],:{}\s]*$/
                    .test(text.replace(/\\(?:["\\\/bfnrt]|u[0-9a-fA-F]{4})/g, '@')
                        .replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']')
                        .replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {

// In the third stage we use the eval function to compile the text into a
// JavaScript structure. The '{' operator is subject to a syntactic ambiguity
// in JavaScript: it can begin a block or an object literal. We wrap the text
// in parens to eliminate the ambiguity.

                j = eval('(' + text + ')');

// In the optional fourth stage, we recursively walk the new structure, passing
// each name/value pair to a reviver function for possible transformation.

                return typeof reviver === 'function'
                    ? walk({'': j}, '')
                    : j;
            }

// If the text is not JSON parseable, then a SyntaxError is thrown.

            throw new SyntaxError('JSON.parse');
        };
    }
}());

EZ.JSON={
    encode : JSON.stringify
    ,decode : JSON.parse
}


if(typeof $ !='undefined')$(document).ready(function(){
    if($.ui){
        EZ.alert = new function () {
            var alertDiv = document.createElement('div');
            var button = {
                yes : EZ.lang("確定"),
                no : EZ.lang("取消"),
                cancel : EZ.lang("取消"),
                ok : EZ.lang("確定"),
                title : EZ.lang("系統訊息")
            }
            $(alertDiv).attr('title', button.title);

            var buttons = {};
            buttons[button.ok] = function () {
                $(alertDiv).dialog('close');
            }
            $(alertDiv).dialog({
                autoOpen : false,
                width : 300,
                minHeight : 100,
                modal : true,
                buttons : buttons,
                //hide: { effect: 'drop', direction: "up" },
                //show: { effect: 'drop', direction: "up" },
                // buttons: {
                // "確認": yesButton,
                // "取消": closeTradeWindow
                // },
                //dialogClass: className,
                overlay : {
                    opacity : 0.7,
                    background : "#FF8899"
                }
            });
            return function (text, callback) {
                $(alertDiv).dialog({
                    close : callback
                });
                $(alertDiv).html(text);
                $(alertDiv).dialog('open');
            }
        }();
        //2.confirm
        EZ.confirm = new function () {
            var confirmDiv = document.createElement('div');
            //confirmDiv.style.display='none';
            var button = {
                yes : EZ.lang("確定"),
                no : EZ.lang("取消"),
                cancel : EZ.lang("取消"),
                ok : EZ.lang("確定"),
                title : EZ.lang("網頁訊息")
            }
            $(confirmDiv).attr('title', button.title);
            confirmDiv.id = 'ttt';
            var buttons = {};
            buttons[button.yes] = function () {}
            buttons[button.no] = function () {
                $(confirmDiv).dialog('close');
            }
            $(confirmDiv).dialog({
                autoOpen : false,
                width : 300,
                minHeight : 100,
                modal : true,
                buttons : buttons,
                overlay : {
                    opacity : 0.7,
                    background : "#FF8899"
                }
            });

            var Self = function (text, yesFunction, noFunction, closeFunction) {
                if (typeof text == 'undefined') {
                    $(confirmDiv).dialog('close');
                    return;
                }
                $(confirmDiv).html(text);
                $(confirmDiv).on("dialogclose", closeFunction || EZ.emptyFN);
                var buttons = {};
                buttons[button.yes] = function () {
                    $(confirmDiv).dialog('close');
                    yesFunction();
                }
                buttons[button.no] = function () {
                    $(confirmDiv).dialog('close');
                    noFunction();
                }
                $(confirmDiv).dialog('option', 'buttons', buttons);
                $(confirmDiv).dialog('open');
            }
            Self.html = function (text) {}
            return Self;
        }();
    }
});

EZ.api = function(cmd,param,callback,timeout){
    if(typeof callback == 'undefined') callback=EZ.emptyFN;
    EZ.ajax('api.php?cmd='+cmd+'&tt='+new Date().getTime(),{
        cmd:cmd
        ,param:param
        ,timeout:(timeout||30)
    },callback);
};

EZ.fancybox = new function(){
    var self=this
    ,overlay
    ,box
    ,box_wrapper
    ,id_msg = EZ.id()
    ,id_close = EZ.id()
    ,ccc=0
    ,make_overlay = function(){
        if(typeof overlay=='undefined'){
            overlay = document.createElement('div');
            overlay.style.backgroundColor='rgb(119,119,119)';
            overlay.style.opacity='0.7';
            overlay.style.width='100%';
            overlay.style.height='100%';
            overlay.style.zIndex='1100';
            overlay.style.display='none';
            overlay.style.position='fixed';
            overlay.style.left='0';
            overlay.style.top='0';
            document.body.appendChild(overlay);
            overlay.onclick=Self.hide;
        }
    }
    ,click = function(e){
        e = e || window.event;
        var dom = e.target || e.srcElement;
        var clickfor=  dom.getAttribute('clickfor');
        switch(clickfor){
            case 'close':
                Self.hide();
                break;
            default:
                break;
        }
    }
    ,make_box = function(){
        if(typeof box=='undefined'){
            box_wrapper = document.createElement('div');
            box_wrapper.style.textAlign='center';
            box_wrapper.style.width='100%';
            box_wrapper.style.position='fixed';
            box_wrapper.style.left='0';
            box_wrapper.style.zIndex='1103';
            box_wrapper.style.display='none';
            
            box = document.createElement('div');
            box.style.borderWidth='10px';
            box.style.height='auto';
            box.style.display='inline-block';
            box.style.position='relative';
            //box.style.zIndex='1103';
            
            box.style.backgroundColor='#fff';
            box.style.padding='10px';
            box.style.margin='0 auto';
            //box_wrapper.style.top='50%';
            box.style.borderRadius='6px';
            //box.innerHTML = '<div ><input id="'+id_msg+'" type="text" style="background-color:#fff;border:0;width:400px;"></div>';
            box_wrapper.appendChild(box);
            document.body.appendChild(box_wrapper);
            box_wrapper.addEventListener('click',click);
            box_wrapper.setAttribute('clickfor','close');
            //document.getElementById(id_close).onclick=Self.hide;
        }
    }
    ,adjust = function(){
        var h = EZ.System.height();
        var h2 = box.clientHeight;
        if(h2 > h){
            box_wrapper.style.top='10px';
        }else{
            box_wrapper.style.top=((h/2)-(h2/2))+'px';
        }
        
        if(ccc<300){
            ccc++;
            setTimeout(adjust,0)
        }else{
            ccc=0;
        }
    }
    ,Self={
        show : function(msg,callback){
            make_overlay();
            make_box();
            //overlay.style.height=h+'px';
            overlay.style.display='block';
            box_wrapper.style.display='block';
            msg+='<a clickfor="close" class="fancybox-close"></a>';
            box.innerHTML = msg;
            setTimeout(adjust,0);
            if(typeof callback=='function') callback(Self,box_wrapper);
            //setTimeout(adjust,3000);
            return box;
        }
        ,hide:function(){
            overlay.style.display='none';
            box_wrapper.style.display='none';
        }
    }
    return Self;
}();

EZ.json_decode = function(response){
    var r;
    //console.log(response);
    try{
        r = eval('('+response+')');
    }catch(e){
        return {success:0,msg:'server response error',data:[]};
    }
    if(r.msg=='login fail'){
        location.replace(location.href);
    }
    return r;
}