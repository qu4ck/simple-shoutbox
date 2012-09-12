/*
 * isoweb Shoutbox sederhana
 *
 * Copyleft (c) 2012 Airlangga bayu seto (www.iso.web.id)
 * Date: 2012-04-07 20:53:17 -0644 (Sun, 07 Apr 2012) $
 *
 */

function listdata(data){
    var htm = "<div class=\"data-row\">";
    var url;
    $.each(data.list,function(k,v){
        url = (v.URL == "")?v.NAMA:"<a href=\""+v.URL+"\">"+v.NAMA+"</a>";
        htm += "<div class=\"group_list\"> \
            <div class=\"tgl\">"+ v.TGL +"</div> \
            <span class=\"nama\">"+ url +"</span>: \
            <span class=\"message\">"+ v.PESAN +"</span>";
        if(data.login == true){
            htm +="<a href=\"#\" key=\""+v.ID+"\" class=\"del\"></a>";
        }
        htm +="</div>";
    });
        htm +="</div> \
                <div id=\"paging\"></div>";
    if(data.login == true){
        $("#frm_login").hide();
        $("#admin").hide();
        $("#logout").show();
    }else{
        $("#logout").hide();
        $("#admin").show();
    }
    $('#listdata').html(htm);
    $('#paging').html(data.paging);
}
function show_data(){
    $.ajax({
         url: 'proses.php'
        ,type: 'GET'
        ,dataType: 'json'
        ,success: function(data) {
            if(data.valid == true){
                listdata(data);
            }else{
                $('#listdata').html("<center>"+data.message+"</center>");
            }
        }
        ,error: function(){
            alert('Kesalahan mengambil data.');
        }
    });
}
$(document).ready(function(){
    show_data();
    $("#loading").ajaxStart(function() {
        $(this).show();
    }).ajaxStop(function() {
        $(this).hide();
    });
    
    $("#admin").click(function(){
        $("#frm_shoutbox").hide();
        $("#admin").hide();
        $("#frm_login").show();

        return false;
    });
    
    $("#cancel").click(function(){
        $("#frm_shoutbox").show();
        $("#admin").show();
        $("#frm_login").hide();

        return false;
    });

    $("#selanjutnya").live("click",function(event){
        event.preventDefault();
        var url = $("#selanjutnya").attr("href");
       $.ajax({
             url        : url
            ,type       : 'get'
            ,dataType   : 'json'
            ,success    : function(data){
                listdata(data);
            }
            ,error      : function(){
                alert('Kesalahan mengambil data.');
            }
        });
    });
    $("#sebelumnya").live("click",function(event){
        event.preventDefault();
        var url = $("#sebelumnya").attr("href");
       $.ajax({
             url        : url
            ,type       : 'get'
            ,dataType   : 'json'
            ,success    : function(data){
                listdata(data);
            }
            ,error      : function(){
                alert('Kesalahan mengambil data.');
            }
        });
    });

    $('#frm_shoutbox').submit(function() {
        $.ajax({
             type: 'POST'
            ,url: 'proses.php?show=add'
            ,data: $(this).serialize()
            ,dataType: 'json'
            ,success: function(data) {
                if(data.valid == true){
                    show_data();
                    $(":input[type=\"text\"]").val('');
                    $("textarea").val('');
                }else{
                    $("#msg").html("<font color=\"red\">"+data.message+"</font>");
                }
            }
            ,error: function(){
                alert('Kesalahan input data.');
            }
        });
        return false;
    });

    $('#frm_login').submit(function() {
        $.ajax({
             type: 'POST'
            ,url: 'proses.php?show=login'
            ,data: $(this).serialize()
            ,dataType: 'json'
            ,success: function(data) {
                if(data.valid == true){
                    show_data();
                    $("#admin").hide();
                    $("#frm_login").hide();
                    $("#frm_shoutbox").show();                    
                }else{
                    $("#msg").html("<font color=\"red\">"+data.message+"</font>");
                }
            }
            ,error: function(){
                alert('Kesalahan input data.');
            }
        });
        return false;
    });
    
    $('#logout').live("click",function(e){
        e.preventDefault();
        $.ajax({
             type: 'GET'
            ,dataType : 'json'
            ,url: 'proses.php?show=logout'
            ,success: function(data) {
                if(data.valid == true){
                    show_data();
                    $("#logout").hide();
                    $("#admin").show();
                }else{
                    $("#msg").html("<font color=\"red\">"+data.message+"</font>");
                }
            }
            ,error: function(){
                alert('Kesalahan delete data.');
            }
        });        
    });

    $('.del').live("click",function(e){
        e.preventDefault();

        var id = $(this).attr("key");
        $.ajax({
             type: 'POST'
            ,url: 'proses.php?show=delete'
            ,data: ({id:id})
            ,dataType: 'json'
            ,success: function(data) {
                if(data.valid == true){
                    show_data();   
                    $("#msg").html("<font color=\"green\">"+data.message+"</font>");
                }else{
                    $("#msg").html("<font color=\"red\">"+data.message+"</font>");
                }
            }
            ,error: function(){
                alert('Kesalahan delete data.');
            }
        });        
    });    
});
