EZ.controller['view.logout.show']=function(p){
    EZ.deleteCookie('sid');
    location.replace('');
};
