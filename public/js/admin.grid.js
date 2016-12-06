


(function(){
    "use strict";
    function escapeHtml(unsafe) {
        return unsafe
             .replace(/&/g, "&amp;")
             .replace(/</g, "&lt;")
             .replace(/>/g, "&gt;")
             .replace(/"/g, "&quot;")
             .replace(/'/g, "&#039;");
     }



     function cut_host(host){

        if(host.indexOf('admin.') !== -1) return host.replace('admin.', '');
        
        return host;
     }


    var __grid=function(pattern){
        this._data={};
        this.pattern=pattern;
        this.init();
    };

    
    var fn = __grid.prototype;


    fn.define = function(prop){

        var p ={};
        var that=this;

        p.get=function(){
            //console.log('getter ', prop);

            if(typeof this._data[prop] === 'undefined') return '';
            return this._data[prop];
        };


        p.set=function(value){
            //console.log('setter ', prop, value);
            this._data[prop] = value;
        };


        if(this.getter[prop]){
            p.get = function(){
                return that.getter[prop].apply(that, [prop]);
            };
        }

        if(this.setter[prop]){
            p.set = function(value){
                return that.setter[prop].apply(that, [prop, value]);
            };
        }

        Object.defineProperty(this, prop, p);
    };


    fn.init = function(){
        this.define('category_id');
        this.define('status');
        this.define('flag');
        this.define('instant');
        this.define('created_user');
    };


    fn.getter={};

    fn.setter={};


    fn.getter['category_id'] = function(prop){
        
        if(!this._data[prop]){
            this._data[prop]='';
        }

    };


    fn.tbody = function(data){
        var h = '', i, j, imax, jmax, d, tag, cls;

        for (i=0,imax=data.length; i < imax; i++) { 
            d=data[i];
            var deleted=false;
            if(d.deleted_at != null) deleted=true;

            var publish_at = d.publish_at;
            var updated_at = d.updated_at;

            if(publish_at === '0000-00-00 00:00:00'){
                publish_at = '';
            }else{
                publish_at = EZ.Date(publish_at).show('Y-mm-dd <br />H:ii:ss');
            }

            if(updated_at === '0000-00-00 00:00:00'){
                updated_at = '';
            }else{
                updated_at = EZ.Date(updated_at).show('Y-mm-dd <br />H:ii:ss');
            }

            //var title = encodeURIComponent(d.title);
            var title = escapeHtml(d.title);


            h+='<tr role="row" class="'+( (i+1)%2===0 ? 'even' : 'odd')+'">';
               h+='<td class="sorting_1" style="'+(deleted ? 'background:red;' : '')+'"">';
               h+='<a href="/'+d.unique_id+'" data-toggle="tooltip" target="view_'+d.id+'" data-placement="bottom" title="#'+d.unique_id+' '+d.id+'查看" rel="nofollow"> ';
                  if(d.photo!==''){
                    h+='<img src="/focus_photos/'+d.photo+'" style="width:52px;height:32px;">';
                  }else{
                    h+=''+d.unique_id;
                  }
                  if(deleted)h+='<br />已刪除';
                  h+='</a></td>';
               

               h+='<td><a href="/admin/articles/'+d.id+'/edit" title="'+d.unique_id+' '+d.id+'">['+d.unique_id+'] '+title+'</a></td>';
                  

               h+='<td>'+d.categories_name+'</td>';

               h+='<td><span class="label label-'+this.status_class(d.status)+'">'+this.status_name(d.status)+'</span></td>';

               h+='<td><span class="label label-success bg-purple">'+this.flag_name(d.flag)+'</span></td>';

               if(d.isPorn=='1'){
                    h+='<td style="text-align:center;color:red;">'+this.instant_name(d.instant)+'</td>';
               }else{

                    if(d.instant=='1'){
                        cls='label label-primary bg-navy';
                    }else{
                        cls='label label-default';
                    }

                    h+='<td> <a data-role="instantArticle" id="'+d.id+'" isPorn="'+d.isPorn+'" instant="'+d.instant+'" data-title="'+title+'" class="btn" rel="nofollow" data-toggle="tooltip" data-placement="bottom">';
                    h+='<span class="'+cls+'">'+this.instant_name(d.instant)+'</span></a></td>';
               }
               
               //h+='<td> <a data-role="instantArticle" title="'+d.title+'" id="'+d.id+'" isPorn="'+d.isPorn+'" instant="'+d.instant+'" class="btn" rel="nofollow" data-toggle="tooltip" data-placement="bottom" data-original-title="'+this.status_name(d.status)+'文章">';
               //h+='<span class="label label-default">'+this.instant_name(d.instant)+'</span></a></td>';

               h+='<td>'+d.created_user_name+'</td>';

               h+='<td>'+d.updated_user_name+'</td>';

               h+='<td>'+publish_at+'</td>';

               h+='<td>'+updated_at+'</td>';

               h+='<td style="margin-bottom: 5px; width:200px;">';
               for (j=0,jmax=d.tags.length; j < jmax; j++) { 
                   tag=d.tags[j];
                   h+='<span class="label label-default" style="float:left;margin-right:2px;" >'+tag+'</span>&nbsp;';
               }
               h+='</td>';

               
               h+='<td style="width:100px;">';
               h+='<a style="width:25px;" href="https://www.facebook.com/sharer/sharer.php?u=http://getez.info/'+d.unique_id+'/'+title+'" target="_facebook" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-xs small-btn" data-original-title="立即分享" rel="nofollow"><i class=" fa  fa-facebook"></i></a>';
               h+='<a style="width:25px;" href="https://developers.facebook.com/tools/debug/og/object/?q=http://getez.info/'+d.unique_id+'/'+title+'" target="_facebook" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-xs small-btn" data-original-title="Facebook Debugger" rel="nofollow"><i class=" fa   fa-bug"></i></a>';
               h+='<a style="width:25px;" href="/instant/'+d.unique_id+'" target="_instant" data-toggle="tooltip" data-placement="bottom" class="btn btn-default btn-xs small-btn" data-original-title="即時文章查看" rel="nofollow"><i class="fa fa-hourglass-1"></i></a>';
               h+='<a style="width:25px;" href="/admin/articles/'+d.id+'/edit " style="color:rgba(8, 4, 4, 0.99)" class="btn btn-default btn-xs small-btn" rel="nofollow" data-toggle="tooltip" target="edit_'+d.id+'" data-placement="bottom" data-original-title="修改"><i class=" fa  fa-pencil-square-o"></i></a>';
               h+='<a style="width:25px;" data-role="deleteArticle" id="'+d.id+'" unique_id="'+d.unique_id+'" class="btn btn-default btn-xs small-btn" rel="nofollow" data-toggle="tooltip" data-placement="bottom" data-original-title="刪除" ><i class=" fa  fa-trash-o"></i></a>';
              // h+='<a style="width:25px;" href="http://'+cut_host(location.host)+'/'+d.unique_id+'" data-toggle="tooltip" target="view_'+d.id+'" data-placement="bottom" class="btn btn-default btn-xs pull-left small-btn" title="#'+d.unique_id+' 查看" rel="nofollow"><i class=" fa  fa-search"></i></a>';
                h+='<a style="width:25px;" href="http://'+'getez.info'+'/'+d.unique_id+'" data-toggle="tooltip" target="view_'+d.id+'" data-placement="bottom" class="btn btn-default btn-xs pull-left small-btn" title="#'+d.unique_id+' 查看" rel="nofollow"><i class=" fa  fa-search"></i></a>';
            h+='</td>';
            h+='</tr>';

        }

        return h;
    };


    fn.flag_name = function(status){
        if(status==='P'){
            return '情色';
        }

        return '';

    }


    fn.status_class = function(status){
        if(status==='0'){
            return 'warning';
        }

        if(status === '1'){
            return 'success';
        }


        if(status === '2'){
            return 'danger';
        }

    };
    

    fn.status_name = function(status){
        if(status==='0'){
            return '草稿';
        }

        if(status === '1'){
            return '上架';
        }


        if(status === '2'){
            return '未發佈';
        }
    };


    fn.instant_name=function(instant){


        if(instant === '0') return '非即時';
        if(instant === '1') return '即時';

    };


    fn.loadData=function(data){

        let tpl = this.tbody(data);
        $(this.pattern+' tbody').html(tpl);
        
    };

    window.__grid=__grid;

    
}());
