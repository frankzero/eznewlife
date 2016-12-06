function clearcache(){
    $.ajax({
        type : 'POST'
        ,data : {act:'clearcache'}
        ,url : 'http://216.158.68.100/wp-content/themes/eznewlife-mobile/api.php'
        ,success : function(){
            
        }
    });
}