<div class="card h-100">
    <!-- Header -->
    <div class="card-header">
        <div class="chat-user-info w-100 d-flex align-items-center">
            <div class="chat-user-info-img">
                <img class="avatar-img onerror-image"
                src="{{\App\CentralLogics\Helpers::onerror_image_helper($user['image'], asset('storage/app/public/profile/').'/'.$user['image'], asset('public/assets/admin/img/160x160/img1.jpg'), 'profile/') }}"
                    data-onerror-image="{{asset('public/assets/admin')}}/img/160x160/img1.jpg"
                    alt="Image Description">
            </div>
            <div class="chat-user-info-content">
                <h5 class="mb-0 text-capitalize">
                    {{$user['f_name'].' '.$user['l_name']}}</h5>
                <small>{{ $user['phone'] }}</small>
            </div>
        </div>
    </div>

    <div class="card-body d-flex flex-column">
        <div class="scroll-down">
            <div class="d-flex justify-content-center">Today, 8:30PM</div>
            @foreach($conversations as $con)
                @if($con->sender_id == $user->id)
                    <div class="py-2 d-flex gap-2 align-items-end">
                        <div class="chat-user-conv-img">
                            <img class="avatar-img onerror-image" width="28" height="28" src="http://localhost:8000/public/assets/admin/img/160x160/img1.jpg" data-onerror-image="http://localhost:8000/public/assets/admin/img/160x160/img1.jpg" alt="Image Description">
                        </div>

                        <div class="conv-reply-1">
                            <h6 data-toggle="tooltip" data-placement="top" title="{{date('d M Y',strtotime($con->created_at))}} {{date(config('timeformat'),strtotime($con->created_at))}}">{{$con->message}}</h6>
                            @if($con->file!=null)
                            @foreach (json_decode($con->file) as $img)
                            <br>
                                <img class="w-100" src="{{asset('storage/app/public/conversation').'/'.$img}}">
                                @endforeach
                            @endif
                        </div>
                    </div>
                @else
                    <div class="py-2">
                        <div class="conv-reply-2">
                            <h6 data-toggle="tooltip" data-placement="top" title="{{date('d M Y',strtotime($con->created_at))}} {{date(config('timeformat'),strtotime($con->created_at))}}">{{$con->message}}</h6>
                            @if($con->file!=null)
                            @foreach (json_decode($con->file) as $img)
                            <br>
                                <img class="w-100" src="{{asset('storage/app/public/conversation').'/'.$img}}">
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach
            <div id="scroll-here"></div>

            {{-- For empty conversation --}}
            <div class="empty-conversation-content d-flex flex-column align-items-center gap-3">
                <img width="128" height="128" src="{{asset('/public/assets/admin/img/icons/empty-conversation.png')}}" alt="public">
                <h5 class="text-muted">
                    {{translate('no_conversation_found')}}
                </h5>
            </div>
        </div>
    </div>
    <div class="mt-auto d-flex justify-content-center fs-12 font-medium text-dark p-3">
        You can’t reply to this conversation. &nbsp;
        <div class="text-danger d-inline-block learn-more-wrap cursor-pointer"> 
            Learn more

            <div class="learn-more-content p-4">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <img class="rounded-circle" width="20" height="20" src="{{asset('public/assets/admin/img/icons/info-icon.png')}}" alt="">
                    <h6 class="mb-0">Learn more</h6>
                </div>
                <p class="mb-0 text-muted text-normal">You can’t chat in deliveryman chat because it’s delivery man previous chat history, only you can monitor or view their conversation to avoid unexpected situation.</p>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('public/assets/admin')}}/js/view-pages/common.js"></script>
<script>
    "use strict";
    $(document).ready(function () {
        $('.scroll-down').animate({
            scrollTop: $('#scroll-here').offset().top
        },0);
    });
</script>