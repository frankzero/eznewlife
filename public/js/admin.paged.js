(function(){
    "use strict";
    var __paged=function(id, filter){
        this.id=id;
        this.$container = ff('#'+id);
        this.filter=filter;
    };

    var fn = __paged.prototype;


    fn.loadData=function(data){
        var i, imax;

        var h=  '';

        var filter =this.filter;

        h+='';
        h+='<ul class="pagination">';
        for (i=0,imax=data.length; i < imax; i++) { 
            var text=data[i];
            var index = d;
            var active= '';

            if(index === filter.page){
                active='active';
            }

            if(i===0){
                text = 'Previous';
            }

            if(i === (imax-1)){
                text = 'Next';
            }

            h+='<li class="paginate_button '+active+'"><a href="#" class="page_pointer" aria-controls="articles-table" data-index="'+index+'" tabindex="0">'+text+'</a></li>';

        }

        h+='</ul>';
        h+='</div>';





        this.$container.empty();
        this.$container.html(h);

        this.binding();
    };


    fn.binding=function(){
        var filter = this.filter;
        
        ff('.page_pointer', document.getElementById(this.id)).bind('click', function(e){
            
        });
    };

    window.__paged=__paged;

}());


<div class="dataTables_paginate paging_simple_numbers" id="articles-table_paginate">
   <ul class="pagination">
      <li class="paginate_button previous disabled" id="articles-table_previous"><a href="#" aria-controls="articles-table" data-dt-idx="0" tabindex="0">Previous</a></li>
      <li class="paginate_button active"><a href="#" aria-controls="articles-table" data-dt-idx="1" tabindex="0">1</a></li>
      <li class="paginate_button "><a href="#" aria-controls="articles-table" data-dt-idx="2" tabindex="0">2</a></li>
      <li class="paginate_button "><a href="#" aria-controls="articles-table" data-dt-idx="3" tabindex="0">3</a></li>
      <li class="paginate_button "><a href="#" aria-controls="articles-table" data-dt-idx="4" tabindex="0">4</a></li>
      <li class="paginate_button "><a href="#" aria-controls="articles-table" data-dt-idx="5" tabindex="0">5</a></li>
      <li class="paginate_button disabled" id="articles-table_ellipsis"><a href="#" aria-controls="articles-table" data-dt-idx="6" tabindex="0">…</a></li>
      <li class="paginate_button "><a href="#" aria-controls="articles-table" data-dt-idx="7" tabindex="0">2195</a></li>
      <li class="paginate_button next" id="articles-table_next"><a href="#" aria-controls="articles-table" data-dt-idx="8" tabindex="0">Next</a></li>
   </ul>
</div>