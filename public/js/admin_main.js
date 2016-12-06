/**
 * Created by User on 2015/10/26.
 */

$(document).ready(function () {
    tinymce.init(tinymceConfig);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    /*
     var $summernote = $('#summernote');
     $summernote.summernote({
     "fontSize": 18,
     height: 600,
     onImageUpload: function (files, editor, welEditable) {
     // upload image to server and create imgNode...
     for (var i = files.length - 1; i >= 0; i--) {
     sendFile(files[i], this);
     }
     }

     });
     $('.note-editable').css('font-size','18px');
     function sendFile(file, editor, welEditable) {
     data = new FormData();
     data.append("image", file);
     $.ajax({
     data: data,
     type: "POST",
     url: myURL['base'] + "/admin/articles/upload",
     cache: false,
     contentType: false,
     processData: false,
     success: function (url) {
     console.log(url);
     $(editor).summernote('editor.insertImage', url);
     }
     });
     }
     */
    jQuery.validator.addMethod("notEqualTo",
        function (value, element, param) {
            return this.optional(element) || value != $(param).val();
        },
        "新密碼與舊密碼必須不同"
    );
    jQuery.validator.addMethod("special", function (value, element) {
        // allow any non-whitespace characters as the host part
        return this.optional(element) || /^[a-zA-Z0-9-_.]+$/.test(value);
    }, "帳號只允許英數字或(-)(.)(_)特殊字元");

    jQuery.validator.addMethod("parameter", function(value, element) {
        return this.optional(element) || /^[A-Za-z0-9_]+$/.test(value);
    }, "參數名稱只允許英數字或(_)特殊字元！");
    /**
     * */
    $("#self_form").validate({
        rules: {
            email: {
                remote: {
                    url: myURL['base'] + "/admin/users/check_repeat",
                    type: "post",
                    data: {
                        type: 'self_update_email',
                        email: function () {
                            return $("#email").val();
                        }
                    }

                }
            }

        },
        messages: {
            email: {
                remote: "這個電子郵件已被使用"
            }
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });


    $("#user_password").validate({
        rules: {
            password: {
                required: true,
                minlength: 6,
                remote: {
                    url: myURL['base'] + "/admin/users/password/check",
                    type: "post",
                    data: {
                        password: function () {
                            //console.log($("#gsn_code").val());//GKICRNJLYUNZQ
                            return $("#password").val();
                        }
                    }
                }
            },
            new_password: {
                required: true,
                minlength: 6,
                notEqualTo: "#password"
            }
        },
        messages: {
            password: {
                remote: "舊密碼錯誤"
            }
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });
    $("#user_form").validate({
        rules: {
            email: {
                remote: {
                    url: myURL['base'] + "/admin/users/check_repeat",
                    type: "post",
                    data: {
                        type: 'create_email',
                        email: function () {
                            return $("#email").val();
                        }
                    }
                }
            },
            name: {
                special: true,
                remote: {
                    url: myURL['base'] + "/admin/users/check_repeat",
                    type: "post",
                    data: {
                        type: 'create',
                        name: function () {
                            //console.log($("#gsn_code").val());//GKICRNJLYUNZQ
                            return $("#name").val();
                        }
                    }
                }
            }

        },
        messages: {
            name: {
                remote: "帳號名稱重覆"
            },
            email: {
                remote: "這個電子郵件已被使用"
            }
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });
    $("#user_edit").validate({
        rules: {
            email: {
                remote: {
                    url: myURL['base'] + "/admin/users/check_repeat",
                    type: "post",
                    data: {

                        type: 'update_email',
                        email: function () {
                            return $("#email").val();
                        },
                        id: function () {
                            return $("#id").val();
                        }
                    }

                }
            },
            name: {
                remote: {
                    url: myURL['base'] + "/admin/users/check_repeat",
                    type: "post",
                    data: {
                        type: 'update',
                        name: function () {
                            return $("#name").val();
                        },
                        id: function () {
                            return $("#id").val();
                        }
                    }
                }
            }

        },
        messages: {
            name: {
                remote: "帳號名稱重覆"
            },
            email: {
                remote: "這個電子郵件已被使用"
            }
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });
    $("#article_form").validate({
        rules: {
            photo: {
                accept: "jpg|jpeg|png|gif"
            },
            publish_at: {
                //required: true,
                date: true
            }
            /*,
             title: {
             remote: {
             url: myURL['base'] + "/admin/articles/check_repeat",
             type: "post",
             data: {
             type: 'create',
             title: function () {
             //console.log($("#gsn_code").val());//GKICRNJLYUNZQ
             return $("#title").val();
             }
             }
             }
             }*/

        },
        messages: {
            /*
             title: {
             remote: "已經有相同標題的文章囉!!"
             }*/
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });
    $("#article_edit").validate({
        rules: {
            photo: {
                accept: "jpg|jpeg|png|gif"
            },
            publish_at: {
                //required: true,
                date: true
            }/*,
             title: {
             remote: {
             url: myURL['base'] + "/admin/articles/check_repeat",
             type: "post",
             data: {
             type: 'update',
             title: function () {
             //console.log($("#gsn_code").val());//GKICRNJLYUNZQ
             return $("#title").val();
             },
             id: function () {
             //console.log($("#gsn_code").val());//GKICRNJLYUNZQ
             return $("#id").val();
             }
             }
             }
             }*/

        },
        messages: {
            /* title: {
             remote: "已經有相同標題的文章囉!!"
             }*/
        },
        submitHandler: function (form) {
            $('input[type=submit]').attr('disabled', 'disabled');
            form.submit();
        }
    });
});

/**
 * bootstrap css adjust
 * **/

$.validator.setDefaults({
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});
/**
 * 動圖驗證
 * **/
$("#animation_form,#animation_edit").validate({
    rules: {
        photo: {
            accept: "jpg|jpeg|png|gif"
        }
    },
    messages: {

    },
    submitHandler: function (form) {
        $('input[type=submit]').attr('disabled', 'disabled');
        form.submit();
    }
});
/**
 * 舊的  新的寫在datatables_filter.blade.php
 * */
$('.article_delete_form').submit(function (e) {
    var currentForm = this;
    e.preventDefault();
    bootbox.dialog({
        size: 'small',
        message: "HI,你確認要刪除此文章嗎?",
        title: "刪除確認",
        buttons: {
            danger: {
                label: "Cancel",
                className: "btn btn-outline pull-left",
                callback: function () {
                    //do something
                }
            },
            main: {
                className: "btn btn-outline ",
                label: "確認刪除",
                callback: function (result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            }
        }
    }).find('.modal-dialog').addClass('modal-warning');


});

$('.delete_form').submit(function (e) {
    //$(this).attr('data-show')
    var currentForm = this;
    e.preventDefault();

    bootbox.dialog({
        size: 'small',
        message: $(this).find("input[name='message']").val(),
        title: "刪除確認",
        buttons: {
            danger: {
                label: "Cancel",
                className: "btn btn-outline pull-left",
                callback: function () {
                    //do something
                }
            },
            main: {
                className: "btn btn-outline ",
                label: "確認刪除",
                callback: function (result) {
                    if (result) {
                        currentForm.submit();
                    }
                }
            }
        }
    }).find('.modal-dialog').addClass('modal-warning');


});
$("[data-toggle=popover]").popover({trigger: "hover", html: true, placement: "bottom"});


/**
 * 1.預覽圖示
 * 2.圖片格式判斷
 * */
var fileTypes = ['jpg', 'jpeg', 'png', 'gif'];  //acceptable file types

function readURL(input) {
    if (input.files && input.files[0]) {
        var extension = input.files[0].name.split('.').pop().toLowerCase(),  //file extension from input file
            isSuccess = fileTypes.indexOf(extension) > -1;  //is extension in acceptable types

        if (isSuccess) { //yes
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);

            }

            reader.readAsDataURL(input.files[0]);
        }
        else { //no
            //warning
            $('#blah').attr('src', 'data:image/png;base64,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');
        }
    }
}

$("#imgInp").change(function () {

    readURL(this);
    $("#blah").show();
    if( $('#my_video').length ) {
        $("#my_video").hide();
    }
    if( $('#resize').length ) {
        $("#resize").show();
    }
});
/**
 * 日期表單元素
 * */
var d = new Date();
var strDate = d.getFullYear() + "/" + (d.getMonth() + 1) + "/" + d.getDate() + " " + d.getHours() + ":" + d.getMinutes()
$('#publish_at').datetimepicker({
    startDate: strDate,
    format: "yyyy-mm-dd hh:ii",
    language: "zh-TW",
    minuteStep: 5
});

/**
 * datatables
 */
$('#article_server').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": myURL['base'] + "/admin/articles/json"
});
$('#article_list').DataTable();
$('#user_list').DataTable();
/**
 * auto save
 * **/
//refer http://stackoverflow.com/questions/19910843/autosave-input-boxs-to-database-during-pause-in-typing/19911256#19911256
var timeoutId;
$('.article_form').on('input propertychange change', function () {
    console.log('Textarea Change');

    clearTimeout(timeoutId);
    timeoutId = setTimeout(function () {
        // Runs 1 second (1000 ms) after the last change
        saveToDB(this);
    }, 1000);
});
//refer


function fix_cut_page(content){
    if(content.indexOf('@enl@') === -1){
        return content;
    }

    var i, imax, ds, d, div, result;

    result=[];

    div=document.createElement('div');

    ds = content.split('@enl@');

    for(i=0,imax=ds.length; i<imax; i++){
        d=ds[i];

        div.innerHTML = d;
        result.push(div.innerHTML);

    }


    return result.join('@enl@');


}

function saveToDB() {
    console.log('Saving to the db');
    var token = $('input[name=_token]');
    ;
    var form = $('form')[0]; // You need to use standart javascript object here
    var fd = new FormData(form);
    var type = $('#type').val();
    var content;

    //  console.log($('#type').val());
    if (type == "articles") {
        content = tinymce.activeEditor.getContent();
        content = fix_cut_page(content);
        fd.append('content', content);
        
    }
    console.log(fd);
    // formData.append('file', $('input[type=file]')[0].files[0]);
    $.ajax({
        url: myURL['base'] + "/admin/" + type + "/auto_save",
        type: "POST",
        data: fd,
        headers: {
            'X-CSRF-TOKEN': token.val()
        },
        processData: false,
        contentType: false,
        // data: form.serialize(), // serializes the form's elements.
        beforeSend: function (xhr) {
            // Let them know we are saving
            $('#auto-save-block').show();
            $('.form-status-holder').html('儲存中');
        },
        success: function (msg, textStatus, jqXHR) {
            var jqObj = jQuery(msg); // You can get data returned from your ajax call here. ex. jqObj.find('.returned-data').html()
            // Now show them we saved and when we did
            // var json = $.parseJSON(msg); //
            var d = new Date();
            if (msg.redirect) {
                // data.redirect contains the string URL to redirect to
                window.location.href = msg.redirect;
            }
            else {
                //   console.log(jqObj); console.log(textStatus);console.log(jqXHR);
                $('#id').val(jqObj[0].id);
                $('.article_form').attr('action', myURL['base'] + "/admin/" + type + "/update/" + jqObj[0].id);
                $.param(msg)

                if (!jqObj[0].error) {
                    $('.form-status-holder').html('自動緩存: ' + d.toLocaleTimeString());

                } else {
                    $('.form-status-holder').html('圖片錯誤，緩存失敗: ' + d.toLocaleTimeString());
                }
                console.log(jqObj[0]);
                if (jqObj[0].unique_id) {
                    if ($("#flag").prop('checked') == true) {
                        $("#instant").prop('checked', false);
                        // $("#radio-choice-2").attr("checked",true);
                    }
                    $('#unique_id').val(jqObj[0].unique_id);
                    $(".form-status-holder").append(
                        " &nbsp;&nbsp;<a class=\"btn btn-xs btn-success\" href=" + jqObj[0].url + "  target='_" + jqObj[0].unique_id + "'>"
                        + ' &nbsp;<i class="fa  fa-location-arrow"></i> 查看前台文章#' + jqObj[0].unique_id + "</a>"
                        + " &nbsp;&nbsp;<a class=\"btn btn-xs btn-primary\" href=" + jqObj[0].share_url + "  target='_" + jqObj[0].unique_id + "'>"
                        + ' &nbsp;<i class="fa  fa-location-arrow"></i> 分享#' + jqObj[0].unique_id + "</a>"
                        + " &nbsp;&nbsp;<a class=\"btn btn-xs btn-danger\" href=" + jqObj[0].debug_url + "  target='_" + jqObj[0].unique_id + "'>"
                        + ' &nbsp;<i class="fa  fa-bug"></i> 除錯#' + jqObj[0].unique_id + "</a>"
                        + " &nbsp;&nbsp;<a class=\"btn btn-xs btn-primary bg-navy\" href=" + jqObj[0].instant_url + "  target='instant_" + jqObj[0].unique_id + "'>"
                        + ' &nbsp;<i class="fa  fa-rocket"></i> 即時文章#' + jqObj[0].unique_id + "</a>"
                    );
                    //#網址參數
                    $("#ENL").val(jqObj[0].ENL);
                    $("#Getez").val(jqObj[0].Getez);
                    $("#ENL_url").attr("href", jqObj[0].ENL);
                    /*文章圖片網址*/
                    if (jqObj[0].photo_url != "" && type == 'article') {

                        $("#article_bar").html("");
                    } else {
                        $("#article_bar").html('<a class="col-sm-2 col-lg-2  copy btn btn-xs btn-success"  id="btn_photo" title="點擊可複製" data-toggle="tooltip" data-clipboard-target="#photo_url">圖片 </a>  <div class="col-sm-10 col-lg-10"> <input class="form-control" id="photo_url"  type="text" value="' + jqObj[0].photo_url + '" aria-invalid="false">');
                    }

                }
            }



            /*動圖網址*/
            if (jqObj[0].photo_url == "" && type=='animations') {
                $("#animation_bar").html("");
            } else if (type=='animations') {
                $(".form-status-holder").append(" &nbsp;&nbsp;<a class=\"btn btn-xs btn-success\" href=" +  myURL['base'] + "/admin/" + type + "/datatables" + " >"

                + ' &nbsp;<i class="fa  fa-location-arrow"></i> 動圖列表' + "</a>");
                var tmp='';
                $.each( jqObj[0].photo_url, function(site_name, site_url) {
                    tmp+='<a class="col-sm-2 col-lg-1  copy btn btn-xs btn-success"  id="'+site_name+'_btn" title="點擊可複製" data-toggle="tooltip" data-clipboard-target="#'+site_name+'_url">'
                    +site_name+'</a>'

                    +'<div class="col-sm-10 col-lg-11"> <input class="form-control" name="'+site_name+'_url" id="'+site_name+'_url"  type="text" value="' + site_url + '" ></div>'
                });
                $("#animation_bar").html(tmp);

            }
            $('.article_form').attr('action', jqObj[0].action_url);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR, textStatus);
            $('.form-status-holder').html('Error: ' + textStatus);
        }
    });
}

// This is just so we don't go anywhere
// and still save if you submit the form
$('.contact-form').submit(function (e) {
    saveToDB();
    e.preventDefault();
});
