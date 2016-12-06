function tiny_customize(editor) {
    "use strict";

    window.editor = editor;

    tinymce.PluginManager.add('example', function(editor, url) {
        // Add a button that opens a window
        editor.addButton('example', {
            text: '客製',
            icon: false,
            onclick: function() {
                // Open window
                editor.windowManager.open({
                    title: '客製化功能',
                    body: [

                        {
                            type: 'textbox',
                            name: 'title',
                            label: 'Title',
                            placeholder: 'abc'
                        }
                    ],
                    onsubmit: function(e) {
                        // Insert content when the window form is submitted
                        editor.insertContent('Title: ' + e.data.title);
                    }
                });
            }
        });

        // Adds a menu item to the tools menu
        editor.addMenuItem('example', {
            text: 'Example plugin',
            context: 'tools',
            onclick: function() {
                // Open window with a specific url
                editor.windowManager.open({
                    title: 'TinyMCE site',
                    url: 'http://www.tinymce.com',
                    width: 800,
                    height: 600,
                    buttons: [{
                        text: 'Close',
                        onclick: 'close'
                    }]
                });
            }
        });
    });


    // editor.on('click', tiny_onclick);

    // editor.on('paste',tiny_onpaste);

    // editor.on('dragover', tiny_ondragover);

    // editor.on('dragleave', tiny_ondragleave);

    // editor.on('drop', tiny_ondrop);

    editor.on('BeforeExecCommand', BeforeExecCommand);

    // editor.execCommand("FontSize",true,'18px');

    editor.on('ObjectSelected', function(e) {
        console.log('ObjectSelected');
    });

    editor.on('init', function(e) {
        editor.execCommand("FontSize", true, '18px');
        var el = ff('#customimageuploadbutton').el.parentNode;
        //$('#mceu_31-body').append('<div id="editorstatus" class="editorstatus"></div>');
        $('.mce-tinymce').append('<div id="editorstatus" class="editorstatus"></div>');

    });

    var saveflag = false;

    var save = function() {
        console.log('change');
        saveflag = true;
        $('#editorstatus').css('background-color', 'red');
        editor.save();
    };

    var autosave = function() {
        //console.log('autosave', saveflag);
        if (saveflag === true) {
            saveflag = false;
            $('#editorstatus').css('background-color', 'green');
            saveToDB();

        }
        setTimeout(autosave, 3000);
    };


    autosave();


    editor.on('change', save);
    editor.on('keyup', save);

    editor.addShortcut('alt+1', 'cut page', function() {
        editor.insertContent('@enl@');
    });


    editor.addShortcut('alt+2', 'hotkeytemplate', function(e) {
        open_hotkeytemplete(e);
    });
    //editor.on('BeforeSetContent', BeforeSetContent);

    ff('.uploader-editor').on('dragleave', function(e) {
        ff(this).removeClass('droppable');
    });


    ff('.uploader-editor').on('dragover', function(e) {
        e.preventDefault();
        console.log('dragover');
    });

    // ff('.uploader-editor').on('drop',tiny_ondrop);


    /*custom button */
    editor.addButton('imageupload', {
        text: "插入圖片",
        icon: 'image',
        title: '插入圖片',
        id: 'customimageuploadbutton',
        onclick: function(e) {

            //console.log($(e.target));
            console.log('imageupload');

            /*
             editor.windowManager.open({
                title: '客製化功能',
                body: [
                  {type: 'container', html:'<div id="mceu_86-body" class="mce-container-body mce-abs-layout" style="width: 242.256px; height: 30px;"><div id="mceu_86-absend" class="mce-abs-end"></div><label id="mceu_82-l" class="mce-widget mce-label mce-abs-layout-item mce-first" for="mceu_82" style="line-height: 17.2727px; left: 0px; top: 6px; width: 57px; height: 17.2727px;">圖片網址</label><input id="mceu_82" class="mce-textbox mce-abs-layout-item mce-last" value="" hidefocus="1" aria-labelledby="mceu_82-l" style="left: 57px; top: 0px; width: 175.437px; height: 28.1818px;"></div> '}
                ],
                onsubmit: function(e) {
                  // Insert content when the window form is submitted
                  editor.insertContent('Title: ' + e.data.title);
                }
              });
            */


            //if ($(e.target).parent().parent().find('input').attr('id') != 'mytinymce-uploader') {
            if (document.getElementById('mytinymce-uploader') === null) {
                var dom_input = document.createElement('input');
                dom_input.id = "mytinymce-uploader";
                dom_input.setAttribute('type', 'file');
                dom_input.setAttribute('name', 'pic');
                dom_input.setAttribute('accept', 'image/*');
                dom_input.setAttribute('multiple', '1');
                ff(dom_input).css('width', '100px');
                ff(dom_input).css('position', 'absolute');
                ff(dom_input).css('top', '0');
                ff(dom_input).css('left', '0');
                ff(dom_input).css('display', 'none');
                //ff('#customimageuploadbutton').append('<input id="mytinymce-uploader" type="file" name="pic" accept="image/*" multiple style="display:none;"');
                //$(document.body).append('<input id="mytinymce-uploader" type="file" name="pic" accept="image/*" multiple style=""');
                document.body.appendChild(dom_input);

                $('#mytinymce-uploader').change(function(e) {
                    var input, file, fr, img;

                    if (typeof window.FileReader !== 'function') {
                        alert("The file API isn't supported on this browser yet.");
                        return;
                    }

                    var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
                    //console.log(files);
                    var i, imax, f, fs=[];

                    for (i = 0, imax = files.length; i < imax; i++) {
                        fs.push(files[i]);
                        //console.log(f, f.name, f.size);
                        //custom_uplaod_image(f, f.name);
                    }
                    

                    var finsert = function(){
                        
                        if(!fs.length) return;
                        var f = fs.shift();
                        
                        custom_uplaod_image(f, f.name).then(finsert);
                    };
                    finsert();
                    return;

                    

                    return;

                });


            }

            //document.getElementById('mytinymce-uploader').click();
            $('#mytinymce-uploader').trigger('click');
        }


    });


    editor.addButton('imageurl', {
        text: "",
        icon: 'image',
        title: '圖片網址',
        id: 'customimageurlbutton',
        onclick: function(e) {

            //console.log($(e.target));
            console.log('customimageurlbutton');

            editor.windowManager.open({
                title: '輸入圖片網址',
                width: 500,
                height: 75,
                body: [{
                    type: 'container',
                    html: '<input id="imageurl" style="border:1px solid #c5c6c6;width:100%;padding:5px" placeholder="輸入圖片網址"> '
                }],
                onsubmit: function(e) {
                    // Insert content when the window form is submitted
                    //editor.insertContent('Title: ' + e.data.title);
                    var url = document.getElementById('imageurl').value;
                    if (url) {
                        custom_insert_image(url);
                    }

                }
            });

        }


    });



    editor.addButton('cutpage', {
        text: '分頁符號',
        title: 'alt+1',
        id: 'customcutpage',
        onclick: function(e) {
            editor.insertContent('@enl@');
        }
    });



    editor.addButton('scraping', {
        text: "爬文",
        title: '爬文',
        id: 'customscrapingbutton',
        onclick: function(e) {

            //console.log($(e.target));


            editor.windowManager.open({
                title: '輸入文章網址',
                width: 500,
                height: 75,
                body: [{
                    type: 'container',
                    html: '<p> <input value="" id="scrapingurl" style="border:1px solid #c5c6c6;width:100%;padding:5px" placeholder="http://life.tw/?app=view&no=383981"></p><p> <a href="/api/scraping.php?allowurl=1" target="_blank" style="color:blue;">點我看支援網站</a> </p>'
                }],
                onsubmit: function(e) {
                    // Insert content when the window form is submitted
                    //editor.insertContent('Title: ' + e.data.title);
                    var url = document.getElementById('scrapingurl').value;
                    if (url) {

                        confirm_url_used(url)
                            /*
                            .then(function(r){
                                return url_debug(url);
                            },function(){
                                EZ.Promise.stop();
                            })
                            */
                            .then(function(r) {
                                custom_scraping(url);
                            });

                        //custom_scraping(url);
                    }

                }
            });

            $('#scrapingurl').focus();
        }


    });



    editor.addButton('readmore', {
        text: "介紹文章",
        title: '介紹文章',
        id: 'customreadmorebutton',
        onclick: function(e) {

            //console.log($(e.target));


            editor.windowManager.open({
                title: '輸入ENL網址',
                width: 500,
                height: 75,
                body: [{
                    type: 'container',
                    html: '<input value="" id="readmoreurl" style="border:1px solid #c5c6c6;width:100%;padding:5px" placeholder="http://eznewlife.com/141519/僅僅只有八個月大的男娃被媽媽賣給科學家作實驗，但後果卻讓這位媽媽崩潰喚不回．．"> '
                }],
                onsubmit: function(e) {
                    // Insert content when the window form is submitted
                    //editor.insertContent('Title: ' + e.data.title);
                    var url = document.getElementById('readmoreurl').value;
                    if (url) {
                        readmore_append(url);
                    }

                }
            });

            $('#readmoreurl').focus();
        }


    });


    

    editor.addButton('hotkey', {
        text: '熱鍵2',
        title: 'alt+2',
        id: 'customhotkeybutton',
        onclick: function(e) {

            open_hotkeytemplete(e);
        }


    });


    
    // =====================================================================================


    function open_hotkeytemplete(e){
        //console.log($(e.target));
        var tpl = '';

        if(window.localStorage['hotkeytemplate']){
            tpl = window.localStorage['hotkeytemplate'];
        }else{
            
             tpl = 
            `<p>
                看更多 >> <a href="$0">$0</a></p>
            </p>`;
        }

       

        var html = 
        `<input value="" id="hotkeyurl" style="border:1px solid #c5c6c6;width:100%;padding:5px" placeholder="http://eznewlife.com/141519/">
        <p>
        <textarea id="hotkeytemplate" style="border:1px solid #000;width:50%;height:300px;margin-top:20px;margin-bottom:20px;"> ${tpl} </textarea>
        <textarea style="border:1px solid #000;width:50%;height:300px;margin-top:20px;margin-bottom:20px;background-color:#333;color:#fff;">
            這邊是簡易教學  左邊才是樣版 樣版會自動儲存 

            輸入的網址會取代 $0 

            <h1>標題1</h1>
            <h3>標題3</h3>
            <p>
              段落 
            </p>

            <a href="http://eznewlife.com">超連結</a>


        </textarea>
        </p>`

        editor.windowManager.open({
            title: '輸入網址',
            width: 900,
            height: 400,
            body: [{
                type: 'container',
                html: html
            }],
            onsubmit: function(e) {
                // Insert content when the window form is submitted
                //editor.insertContent('Title: ' + e.data.title);
                var url = document.getElementById('hotkeyurl').value;

                var tpl = ff('#hotkeytemplate').val();

                var pattern = 

                //tpl = tpl.replace(new RegExp('$0','g'), url);
                tpl = tpl.replace(/\$0/g, url);

                tinymce.activeEditor.insertContent(tpl);

                //alert(tpl);


            }
        });

        $('#hotkeyurl').focus();

        ff('#hotkeyurl').bind('keydown', function(e){
            var keycode = e.which || e.keyCode;

            if(keycode===13){
                tinyMCE.activeEditor.windowManager.windows[0].submit();
                return;
            }
        });

        ff('#hotkeytemplate').bind('keyup', function(e){
            //console.log(this.value);
            window.localStorage.setItem('hotkeytemplate', this.value);
        });



    }



    function BeforeSetContent(e) {
        //console.log('BeforeSetContent');
        return;
        //console.log(arguments);
        e.preventDefault();
        return false;
    }


    function BeforeExecCommand(o) {
        //console.log('BeforeExecCommand');
        //console.log(o);
    }

    function tiny_onclick(e) {
        console.log('tiny_onclick');
    }


    function tiny_onpaste(e) {
        console.log('tiny_onpaste');
        e.preventDefault();
    }


    function tiny_ondragover(e) {
        console.log('tiny_ondragover');
        //console.log(editor.selectionPrint);
        e.preventDefault();
        //console.log(this.parentNode);
        //ff('.uploader-editor', this.parentNode).addClass('droppable');
    }

    function tiny_ondragstart(e) {
        console.log('tiny_ondragstart');
    }


    function tiny_ondragleave(e) {
        console.log('tiny_ondragleave');
        ff('.uploader-editor', this.parentNode).removeClass('droppable');
    }



    function tiny_ondrop(e) {
        console.log('tiny_ondrop');
        return;
        e.preventDefault();
        console.log(this);
        return;
        editor.setProgressState(1);

        //console.log(e);
        ff('.uploader-editor', this.parentNode).removeClass('droppable');
        // 抓圖
        var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;

        console.log('files', files);
        var i, imax, blob;
        for (i = 0, imax = files.length; i < imax; i++) {
            blob = files[i];
            let reader = new FileReader();
            reader.onload = function(e) {
                var content = e.target.result;
                file_upload(content);
                //console.log(content);
            };
            reader.readAsDataURL(blob);

        }

    }


    function custom_uplaod_image(blob, filename) {
        
        return EZ.Promise(function(resolve, reject){

            var xhr, formData;
            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            //xhr.open('POST', 'imageupload.php');
            xhr.open('POST', '/admin/articles/upload');
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            //xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                var json, response;

                if (xhr.status !== 200) {
                    console.log('http error ' + xhr.status);
                    return;
                }

                response = xhr.responseText;
                json = JSON.parse(response);

                if (!json || typeof json.location !== 'string') {
                    console.log('Invaild JSON');
                    return;
                }

                console.log('upload success');
                //success(json.location);
                custom_insert_image(json.location);

                resolve();
            };

            formData = new FormData();
            formData.append('file', blob, filename);

            xhr.send(formData);

        });
    }


    function custom_insert_image(url) {
        var content = '<p style=""><img src="' + url + '" alt="" data-mce-src="' + url + '" ></p>';
        //console.log(tinymce.activeEditor.insertContent(content));
        tinymce.activeEditor.insertContent(content);
    }


    function confirm_url_used(url) {
        return EZ.Promise(function(resolve, reject) {
            fpost('/api/url_used.php', {
                url: url
            }, function(xhr) {
                var r = xhr.responseText;
                if (r === '1') {
                    if (confirm('網址爬過了 要繼續? Y/N')) {
                        resolve(r);
                        return;
                    }

                    reject(r);
                    return;
                }

                resolve(r);
            });
        });

    }



    function url_debug(url) {
        return EZ.Promise(function(resolve, reject) {
            fpost('/api/url_debug.php', {
                url: url
            }, function(xhr) {
                var r = xhr.responseText;
                if (r === '1') {
                    if (confirm('這個網址被FB封鎖  要繼續? Y/N')) {
                        resolve(r);
                        return;
                    }

                    reject(r);
                    return;
                }

                resolve(r);
            });
        });
    }


    function custom_scraping(url) {
        //console.log(url);
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        //xhr.open('POST', 'imageupload.php');
        xhr.open('POST', '/api/scraping.php', true);
        xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var p = {
            url: url
        };
        xhr.send(EZ.param(p));

        
        var notification = editor.notificationManager.open({
          text: '處理中..',
          progressBar: true
        });
        
        ff('.mce-notification-inner').css('min-width','100px');
        ff('.mce-progress .mce-bar-container').css('width','200px');
        var r3text='';
        //notification.progressBar.value(50);


        xhr.onreadystatechange = function(e) {
            
            if (xhr.readyState == 3) {
                var text, p;
                text = xhr.responseText;
                text = text.split('/***/');
                
                text=text[text.length-1];
                text=text.split(',');
                p=text[1];
                text=text[0];

                ff('.mce-notification-inner').html(text);
                notification.progressBar.value(p);
                return;
            }            


            if (xhr.readyState == 4) {
                notification.close();

                var r = xhr.responseText;
                //r = r.replace(r3text, '');
                //console.log(r);
                if(r.indexOf('/**!@#$%***/') === -1){
                    tinymce.activeEditor.setContent(r);
                    return;
                }

                r = r.split('/*****/');
                r=r[1];

                var sp = '/**!@#$%***/';
                var title, html, sitename;
                if (r.indexOf(sp) === -1) {
                    title = '';
                    html = r;

                } else {
                    r = r.split(sp);
                    title = r[0];
                    html = r[1];
                    sitename = r[2];
                }


                $('#title').val(title);

                var u = new URL(url);
                var _url = u.protocol + '//' + u.host;

                // html+='<p>via <a href="'+_url+'" target="_blank">網路</a></p>';

                if(url.indexOf('eyny.com') !== -1){
                    tinyMCE.activeEditor.selection.select(tinyMCE.activeEditor.getBody(), true);
                    tinyMCE.activeEditor.selection.collapse(false);
                    tinymce.activeEditor.insertContent(html);
                }else{
                    html += '<p style="margin-top:30px;">via ' + sitename + '</p>';
                    tinymce.activeEditor.setContent(html);
                }
                saveToDB();
                return;
            }
        };
    }


    function cs1(url) {
        //console.log(url);

        return EZ.Promise(function(resolve, reject){



            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            //xhr.open('POST', 'imageupload.php');
            xhr.open('POST', '/api/scraping.php', true);
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            var p = {
                url: url
            };
            xhr.send(EZ.param(p));

            xhr.onreadystatechange = function(e) {


                if (xhr.readyState == 4) {
                    var r = xhr.responseText;
                    var sp = '/**!@#$%***/';
                    var title, html, sitename;
                    if (r.indexOf(sp) === -1) {
                        title = '';
                        html = r;

                    } else {
                        r = r.split(sp);
                        title = r[0];
                        html = r[1];
                        sitename = r[2];
                    }


                    $('#title').val(title);

                    var u = new URL(url);
                    var _url = u.protocol + '//' + u.host;

                    // html+='<p>via <a href="'+_url+'" target="_blank">網路</a></p>';
                    html += '<p style="margin-top:30px;">via ' + sitename + '</p>';
                    resolve(html);
                    return;
                }
            };

        });
    }

    function cs2(url) {
        //console.log(url);

        return EZ.Promise(function(resolve, reject){



            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            //xhr.open('POST', 'imageupload.php');
            xhr.open('POST', '/api2/scraping.php', true);
            xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            var p = {
                url: url
            };
            xhr.send(EZ.param(p));

            xhr.onreadystatechange = function(e) {


                if (xhr.readyState == 4) {
                    var r = xhr.responseText;
                    var sp = '/**!@#$%***/';
                    var title, html, sitename;
                    if (r.indexOf(sp) === -1) {
                        title = '';
                        html = r;

                    } else {
                        r = r.split(sp);
                        title = r[0];
                        html = r[1];
                        sitename = r[2];
                    }


                    $('#title').val(title);

                    var u = new URL(url);
                    var _url = u.protocol + '//' + u.host;

                    // html+='<p>via <a href="'+_url+'" target="_blank">網路</a></p>';
                    html += '<p style="margin-top:30px;">via ' + sitename + '</p>';
                    resolve(html);
                    return;
                }
            };

        });
    }

    function fpost(url, p, callback) {
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        //xhr.open('POST', 'imageupload.php');
        xhr.open('POST', url, true);
        xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.send(EZ.param(p));

        xhr.onreadystatechange = function(e) {
            if (xhr.readyState == 4) {
                if (callback) callback(xhr);
            }
        };
    }


    function readmore_append(url) {
        xhr = new XMLHttpRequest();
        xhr.withCredentials = false;
        //xhr.open('POST', 'imageupload.php');
        xhr.open('POST', '/api/readmore.php', true);
        xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        var p = {
            url: url
        };
        xhr.send(EZ.param(p));

        xhr.onreadystatechange = function(e) {


            if (xhr.readyState == 4) {
                var r = xhr.responseText;

                var sp = '/*****/';
                var title, image_url;
                if (r.indexOf(sp) === -1) {
                    image_url = '';
                    html = r;

                    return '';
                } else {
                    r = r.split(sp);
                    r = r[1];
                    r = JSON.parse(r);
                    title = r['title'];
                    image_url = r['image_url'];
                }

                var h = '';
                h += '<h3><a style="color: #0000ff; text-decoration: none;" href="' + url + '" target="_blank">' + title + '</a></h3>';
                h += '<h1 style="margin-bottom:20px;"><a href="' + url + '" target="_blank"><img src="' + image_url + '" alt="' + title + '" data-mce-src="' + image_url + '" /></a></h1>';

                tinymce.activeEditor.insertContent(h);
                return;
            }
        };
    }



    window.custom_scraping=custom_scraping;
    window.custom_uplaod_image=custom_uplaod_image;
    window.cs1=cs1;
    window.cs2=cs2;


} // end



var tinymceConfig = {
        selector: '#myeditor',
        skin: 'lightgray',
        formats: {
            alignleft: [{
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li',
                styles: {
                    textAlign: 'left'
                }
            }, {
                selector: 'img,table,dl.wp-caption',
                classes: 'alignleft'
            }],
            aligncenter: [{
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li',
                styles: {
                    textAlign: 'center'
                }
            }, {
                selector: 'img,table,dl.wp-caption',
                classes: 'aligncenter'
            }],
            alignright: [{
                selector: 'p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li',
                styles: {
                    textAlign: 'right'
                }
            }, {
                selector: 'img,table,dl.wp-caption',
                classes: 'alignright'
            }],
            strikethrough: {
                inline: 'del'
            }
        },

        height: 700,
        theme: 'modern',
        language: 'zh_TW',
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools example'
        ],
        menubar: false,
        paste_data_images: true,
        // toolbar1: 'insertfile undo redo | styleselect | bold italic fontsizeselect | forecolor backcolor emoticon | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image imageupload example | code fullscreen',
        toolbar1: ' styleselect | bold italic | forecolor backcolor emoticon | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link imageurl imageupload scraping readmore hotkey cutpage | code fullscreen',
        //toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        "toolbar2": "formatselect,fontsizeselect,underline,alignjustify,forecolor,pastetext,removeformat,charmap,outdent,indent,undo,redo,wp_help",
        "toolbar3": "",
        "toolbar4": "",
        fontsize_formats: '8px 10px 12px 14px 18px 24px 36px',
        fullpage_default_fontsize: "18px",
        content_css: [
            '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
            '//www.tinymce.com/css/codepen.min.css',
            '/css/tinymce.css'
        ],

        //automatic_uploads:false,



        paste_preprocess: function(plugin, args) {
            // console.log('paste_preprocess');
            // console.log(args.content);
            //args.content += ' preprocess';
        },

        paste_postprocess: function(plugin, args) {
            // console.log('paste_postprocess ');
            // console.log(args.node);
            //alert(1);
            //args.node.setAttribute('id', '42');

        },

        images_upload_handler: function(blobInfo, success, failure) {
            console.log('images_upload_handler');

            //console.log(arguments);
            failure('');
            custom_uplaod_image(blobInfo.blob(), blobInfo.filename());


        },


        setup : function(editor){
            //debugger;
            tiny_customize(editor);
        },

        browser_spellcheck: true,

        images_upload_url: 'imageupload.php'
            //contextmenu_never_use_native: false,

    };
