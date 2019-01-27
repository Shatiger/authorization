var temp = false;
var delete_comment = false;
var hide_delay = 500;

var tmp_id = 0;
var tmp_comment = null;

$(document).ready(function() {

    $(document).on("click", "#captcha", function(event) {
        var captcha = $(event.target);
        var src = $(captcha).attr("src");
        if ((i = src.indexOf("?")) == -1) src += "?" + Math.random();
        else src = src.substring(0, i) + "?" + Math.random();
        $(captcha).attr("src", src);
    });

    $(document).on("click", ".fa-search", function(event) {
        search();
    });

    $(document).on("click", "button[name='delete_profile']", function(event) {
        if (confirm('Вы уверены, что хотите удалить профиль?')) {
            location.href = location.href + '?delete_profile=1';
        }
        return false;
    });

    $(document).on("click", "#my_notes", function(event) {
        var comments = $("div.comment");
        for(var i = 0; i < comments.length; i++) {
            var c = $(comments.get(i));
            if (!c.hasClass("user_" + $(event.target).attr('data-user'))) {
                if (c.is(':visible')) c.hide();
                else c.show();
            }
        }
    });

    $(document).on("keyup", "input[name='search']", function(event) {
        if (event.keyCode == 13) search(true);
    });

    $(document).on("click", ".an__star-block i", function(event) {
        var query;
        var id = $(event.target).parents('.page__block').get(0).id;
        id = id.substr("note_".length)
        query = "func=add_like&note_id=" + id;
        ajax(query, error, successLike);
    });

    $(document).on("click", "#add_note", function(event) {
        cancelNote();
        showFormNote();
    });

    $(document).on("click", "#form_add_note .save", function(event) {
        if ($("#form_add_note textarea").val()) {
            var query;
            var id = $("#id").val();
            var text = $("#text").val();
            var file = $("#file");
            var form_data = new FormData();
            if (file.val()) form_data.append('file', file.prop('files')[0]);
            if (id != 0) {
                form_data.append('obj', 'note');
                form_data.append('func', 'edit');
                form_data.append('name', 'text_' + id);
                form_data.append('value', text);
                ajax(form_data, error, successEditNote);
            }
            else {
                var parent_id = $("#parent_id").val();
                var article_id = $("#article_id").val();
                form_data.append('func', 'add_note');
                form_data.append('parent_id', parent_id);
                form_data.append('article_id', article_id);
                form_data.append('text', text);
                ajax(form_data, error, successAddNote);
            }
        }
        else alert("Вы не ввели текст комментария!");
    });

    $(document).on("click", ".an__settings i", function(event) {
        var obj = $(event.target).parent().find('.settings_note');
        if (obj.is(':visible')) obj.hide();
        else obj.show();
    });

    $(document).on("click", "#comments .an__comment a", function(event) {
        cancelNote();
        var parent_id = $(event.target).parents(".page__block").get(0).id;
        $("#" + parent_id).after($("#form_add_note"));
        $("#form_add_note").addClass('sub');
        $("#form_add_note #text").val($(event.target).parents(".page__block").find('.an__name').text() + ', ');
        $("#form_add_note #parent_id").val(parent_id.substr("note_".length));
        showFormNote();
    });

    $(document).on("click", "#comments .edit_note", function(event) {
        cancelNote();
        var parent_id = $(event.target).parents(".page__block").get(0).id;
        tmp_comment = $("#" + parent_id).clone();
        $("#form_add_note #id").val(parent_id.substr("note_".length));
        var temp = $("#" + parent_id + " .an__text").html();
        temp = temp.replace(/&lt;/g, "<");
        temp = temp.replace(/&gt;/g, ">");
        temp = temp.replace(/&amp;/g, "&");
        $("#form_add_note #text").val(temp);
        $("#form_add_note").find("p").hide();
        if ($(event.target).parents(".page__block").hasClass('sub')) $("#form_add_note").addClass('sub');
        $($("#" + parent_id)).replaceWith($("#form_add_note"));
        showFormNote();
    });

    $(document).on("click", "#comments .delete_note", function(event) {
        cancelNote();
        if (confirm("Вы уверены, что хотите удалить заметку?")) {
            var id = $(event.target).parents(".page__block").get(0).id.substr("note_".length);
            tmp_id = id;
            var query = "obj=note&func=edit&name=date_block_" + id + "&type=date";
            ajax(query, error, successDeleteNote);
        }
    });

    $(document).on("click", "#form_add_note .cancel", function(event) {
        cancelNote();
    });

});

function error() {
    alert("Произошла ошибка! Попробуйте операцию позже.");
}

function successLike(data) {
    data = data["r"];
    data = JSON.parse(data);
    var obj = $('#note_' + data.id).find('.an__star-block');
    if (data.count_likes > 0) {
        obj.find('span').text(data.count_likes);
        obj.find('i').removeClass('fa-star-o');
        obj.find('i').addClass('fa-star');
    }
    else {
        obj.find('span').text('');
        obj.find('i').removeClass('fa-star');
        obj.find('i').addClass('fa-star-o');
    }
}

function getTemplateNote(data) {
    var cn = 'an page__block';
    if (data.parent_id) cn += ' sub';

    var str = '<div class="comment user_' + data.user_id + ' ' + cn + '" id="note_' + data.id + '"><div class="an__header"><div class="an__avatar-block">';
    str += '<a href="' + data.link + '"><img class="an__avatar" src="' + data.avatar + '"></a>';
    str += '<div><div class="an__name-block"><h4 class="an__name">' + data.name + '</h4>';
    str += '<i class="fa fa-star-o an__icon"></i></div><span class="text-help">' + data.date + '</span>';
    str += '</div></div><div class="an__settings"><i class="fa fa-ellipsis-h"></i><div class="clear"></div><div class="settings_note"><a class="edit_note">Изменить</a><br /><a class="delete_note">Удалить</a></div></div></div>';
    str += '<div class="an__body"><p class="an__text">' + data.text + '</p>';
    if (data.file) {
        if (data.file.indexOf('.mp3') != -1) {
            str += '<audio controls><source src="' + data.file + '" type="audio/mpeg"></audio>';
        }
        else str += '<img class="note_img" src="' + data.file + '" alt="" />';
    }
    str += '';
    str += '</div>';
    str += '<div class="an__footer"><div class="an__star-block"><i class="fa an__icon fa-star-o"></i></div>';
    str += '<div class="an__comment"><i class="fa an__icon fa-comment-o"></i><a>Комментировать</a></div>';
    str += '</div></div>';
    return str;
}

function cancelNote() {
    if (tmp_comment) {
        successEditNote(true);
    }
    else closeFormNote();
}

function showFormNote() {
    $("#form_add_note").css("display", "inline-block");
    $("#form_add_note textarea").focus();
}

function closeFormNote() {
    $("#form_add_note #parent_id").val(0);
    $("#form_add_note #text").val("");
    $("#form_add_note #id").val(0);
    $("#form_add_note #file").val('');
    $("#form_add_note").find("p").show();
    $("#form_add_note").css("display", "none");
    $("#comments").prepend($("#form_add_note"));
    $("#form_add_note").removeClass('sub');
    $(".settings_note").hide();
    //$("#count_comments").text($(".comment").length);
}

function successAddNote(data) {
    data = data["r"];
    data = JSON.parse(data);
    if (data['error']) {
        alert(data['error']);
    }
    else {
        var note = getTemplateNote(data);
        if (data.parent_id != null) {
            $("#form_add_note").appendTo("#comments");
            $("#note_" + data.parent_id).after(note);
        }
        else $("#form_add_note").after(note);
        closeFormNote();
    }
}

function successEditNote(data) {
    if (data["r"]) $(tmp_comment).find(".an__text").html(data["r"]);
    if (data) {
        var form = $("#form_add_note").clone();
        $("#form_add_note").replaceWith($(tmp_comment));
        tmp_comment = null;
        $(form).appendTo("#comments");
    }
    else error();

    closeFormNote();
}

function successDeleteNote(data) {
    if (data["r"]) {
        $("#note_" + tmp_id).fadeOut(hide_delay, function() {
            $("#note_" + tmp_id).remove();
            //$("#count_comments").text($(".comment").length);
            tmp_id = 0;
        });
    }
    else error();
}

function getSocialNetwork(f, t, u) {
    if (!t) t=document.title;
    if (!u) u=location.href;
    t = encodeURIComponent(t);
    u = encodeURIComponent(u);
    var s = new Array(
        'http://www.facebook.com/sharer.php?u='+u+'&t='+t+'" title="Поделиться в Facebook"',
        'http://vkontakte.ru/share.php?url='+u+'" title="Поделиться В Контакте"',
        'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st._surl='+u+'&title='+t+'" title="Добавить в Одноклассники"',
        'http://twitter.com/share?text='+t+'&url='+u+'" title="Добавить в Twitter"',
        'http://connect.mail.ru/share?url='+u+'&title='+t+'" title="Поделиться в Моем Мире@Mail.Ru"',
        'http://www.google.com/buzz/post?message='+t+'&url='+u+'" title="Добавить в Google Buzz"',
        'http://www.livejournal.com/update.bml?event='+u+'&subject='+t+'" title="Опубликовать в LiveJournal"',
        'http://www.friendfeed.com/share?title='+t+' - '+u+'" title="Добавить в FriendFeed"'
    );
    for(i = 0; i < s.length; i++)
        document.write('<a rel="nofollow" style="display:inline-block;width:32px;height:32px;margin:0 7px 0 0;background:url('+f+'icons.png) -'+32*i+'px 0" href="'+s[i]+'" target="_blank"></a>');
}

function getXmlHttp() {
    var xmlhttp;
    try {
        xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
        try {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        } catch (E) {
            xmlhttp = false;
        }
    }
    if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
        xmlhttp = new XMLHttpRequest();
    }
    return xmlhttp;
}

$(document).ready(function() {
    $(".edit").bind("click", function(event) {
        var target = $(event.target);
        var id = target.attr("data-name");
        save_obj = $("#" + id);
        if (target.attr("data-typecontent") == "html") text = save_obj.html();
        else if (target.attr("data-typecontent") == "bb") {
            var text = save_obj.html();
            text = text.replace(/<br>/g, "\n");
            text = text.replace(/<b>(.*)<\/b>/g, "[b]$1[/b]");
            text = text.replace(/<i>(.*)<\/i>/g, "[i]$1[/i]");
            text = text.replace(/<a href=["'](.*?)["']>(.*)<\/a>/g, "[url=$1]$2[/url]");
        }
        else text = save_obj.text();
        var input = getInput(target.attr("data-type"), target.attr("data-typecontent"), target.attr("data-obj"), target.attr("data-name"), target.attr("data-values"), text);
        $("#" + id).replaceWith(input);
        if ($(input).is("div")) $(input).children("textarea, input").focus();
        else input.focus();
    });

    $(".invert").bind("click", function(event) {
        var target = $(event.target);
        var img = false;
        if (target.is("img")) {
            img = true;
            text = target.attr("src");
        }
        else text = target.text();
        var obj = target.attr("data-obj");
        var name = target.attr("data-name");
        var type = target.attr("data-type");
        var ct;
        var value;
        var nt;
        if (text == target.attr("data-nt1")) {
            ct = target.attr("data-ct1");
            value = target.attr("data-v1");
            nt = target.attr("data-nt2");
        }
        else if (text == target.attr("data-nt2")) {
            ct = target.attr("data-ct2");
            value = target.attr("data-v2");
            nt = target.attr("data-nt1");
        }
        else return;
        if (ct)
            if (!confirm(ct)) return;
        ajax({obj: obj, name: name, value: value, type: type, func: "edit"}, function() {
            alert("Ошибка соединения");
        }, function (result) {
            if (result["e"] == false) {
                var element = $("[data-name='" + name + "']");
                if (img) element.attr("src", nt);
                else element.text(nt);
            }
            else alert("Неизвестная ошибка");
        });
    });

    $(".delete").bind("click", function(event) {
        var target = $(event.target);
        if (!confirm("Вы уверены, что хотите удалить " + target.attr("data-text") + "?")) return;
        var obj = target.attr("data-obj");
        var id = target.attr("data-id");
        ajax({obj: obj, id: id, func: "delete"}, function() {
            alert("Ошибка соединения");
        }, function (result) {
            if (result["e"] == false) $("#" + obj + "_" + id).hide(hide_delay);
            else alert("Неизвестная ошибка");
        });
    });

    $(document).on("blur", "[data-func='edit']", function(event) {
        var target = $(event.target);
        var obj = target.attr("data-obj");
        var name = target.attr("name");
        var value = $(event.target).val();
        var load = $(getLoad());
        var select_value = false;
        if (target.is("select")) select_value = $("select[name='" + name + "'] option:selected" ).text();
        if (target.is("textarea")) target.parent().replaceWith(load);
        else target.replaceWith(load);

        ajax({obj: obj, name: name, value: value, func: "edit"}, function() {
            alert("Ошибка соединения");
            $(load).replaceWith(save_obj);
        }, function (result) {
            if (result["e"] == false) {
                if (select_value) result["r"] = select_value;
                if (target.attr("data-typecontent") == "html" || target.attr("data-typecontent") == "bb") $(save_obj).html(result["r"]);
                else $(save_obj).html(result["r"].replace(/\n/g, "<br/>\n"));
                $(load).replaceWith(save_obj);
            }
            else {
                alert("Некорректное значение");
                $(load).replaceWith(save_obj);
            }
        });
    });

});

function ajax(data, func_error, func_success) {
    var ajax_data = {
        url: "/api.php",
        type: "POST",
        data: (data),
        dataType: "text",
        error: func_error,
        success: function(result) {
            result = $.parseJSON(result);
            func_success(result);
        }
    };
    if (data instanceof FormData) {
        ajax_data['contentType'] = false;
        ajax_data['processData'] = false;
    }
    $.ajax(ajax_data);
}

function getInput(type, type_content, obj, name, values, active) {
    var input;
    if (type == "select") {
        input = $("<select data-func='edit' data-obj='" + obj + "' name='" + name + "'>");
        values = values.replace(/\x27+/g, "\x22");
        var data = $.parseJSON(values);
        for (var i in data) {
            if (data[i] == active) $(input).append("<option value='" + i + "' selected='selected'>" + data[i] + "</option>");
            else $(input).append("<option value='" + i + "'>" + data[i] + "</option>");
        }
    }
    else if (type == "text") {
        input = $("<input type='text' data-func='edit' data-obj='" + obj + "' name='" + name + "' value='" + active + "' />");
    }
    else if (type == "textarea") {
        input = $("<div class='textarea'><textarea data-func='edit' cols='56' rows='6' data-obj='" + obj + "' data-typecontent='" + type_content + "' name='" + name + "'>" + active + "</textarea></div>");
    }
    else return false;

    return input;
}

function getLoad() {
    return "<img src='/images/load.gif' alt='' />";
}

function search(enter = false) {
    var obj = $('input[name="search"]');
    if (obj.is(':visible')) {
        var val = obj.val();
        if (val == '') {
            if (enter) alert('Введите строку для поиска');
            else obj.hide();
        }
        else {
            var link = location.href;
            if (link.indexOf('?') != -1) link += '&search=' + encodeURIComponent(val);
            else link += '?search=' + encodeURIComponent(val);
            link += '#comments';
            location.href = link;
        }
    }
    else {
        obj.css('display', 'inline');
        obj.focus();
    }
}