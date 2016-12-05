@if ( Auth::enl_user()->check()===false)
    <a href="#" rel="nofollow">
    <div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel"> <img src="{{("images/logo.png")}}"></h3>
                </div>
                <div class="modal-body">
                    <strong>您還未登入，要現在登入嗎？</strong>
                    <br>

                    你必需成為會員, 加入會員，<u class="text-red">完全免費!!</u>


                    <br><br>
                </div>


                <div class="modal-footer">
                    <a href="{{route('enl.facebook.login')}}" class="btn btn-social btn-facebook btn-lg btn-block "><i class="fa fa-facebook"></i>Facebook 註冊/登入</a>

                </div> </div>
        </div>
    </div>
    </a>
@endif