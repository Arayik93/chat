<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold see_users_list text-xl text-gray-800 leading-tight cursor-pointer">
            {{ __('Users') }}
        </h2>
        <h2 class="font-semibold  see_messages_list  text-xl text-gray-800 leading-tight cursor-pointer">
            {{ __('Messages') }}
        </h2>
    </x-slot>
    <div class="w3-container users_list ">
        <h2>Users List</h2>
        <ul class="w3-ul w3-card-4">
            @foreach($users as $user)
                <li class="w3-bar">
                    <span data-room_id = "{{$user->privateRoomId()}}" data-user_id="{{$user->id}}"
                          class="w3-bar-item private_chat w3-button w3-white w3-xlarge w3-right">Private Chat</span>
                    <div class="w3-bar-item">
                        <span class="w3-large">{{$user->name}}</span><br>
                        @if($user->online())
                            <span style="color : green ">online</span>

                        @else
                            <span style="color : red ">offline</span>
                        @endif
                    </div>
                </li>
            @endforeach

        </ul>
    </div>
    <div class="w3-container messages_list row d-none">


        <div class="col-md-3">

            <ul class="w3-ul w3-card-4 room_list">

                @foreach($rooms as $room)
                    <li class="w3-bar ">
                                <span data-room_id = "{{$room->id}}"
                                      class="  w3-bar-item w3-button w3-white w3-xlarge write_now w3-right">Write</span>
                        @foreach($room->users as $user)

                            <div class="w3-bar-item">
                                <span class="w3-large">{{$user->name}}</span><br>
                                @if($user->online())
                                    <span style="color : green ">online</span>

                                @else
                                    <span style="color : red ">offline</span>
                                @endif
                            </div>
                        @endforeach
                    </li>
                @endforeach

            </ul>
        </div>
        <div class="col-md-9 room_message_list">
            @foreach($rooms as $key => $room)
                <div  data-room_id = "{{$room->id}}" class="room_{{$room->id}}  message_list  {{$key != 0 ? "d-none" : "d-block"}}" style="height: 100%; width: 100%">
                @foreach($room->messages as $message)
                    {{$message->body}}<br>
                    @endforeach
            </div>
            @endforeach
            <textarea class="message_body" style="width: 100%"></textarea>
                <span
                      class="send_message w3-bar-item w3-button w3-white w3-xlarge w3-right">Send</span>
        </div>
    </div>

</x-app-layout>

<script>

    $('.see_messages_list').click(function () {
        $('.messages_list').removeClass('d-none')
        $('.users_list').addClass('d-none')
    })

    $('.see_users_list').click(function () {
        $('.users_list').removeClass('d-none')
        $('.messages_list').addClass('d-none')
    })
    $('.write_now').click(function (){
        let room_id = $(this).data("room_id");
        $(".message_list").removeClass("d-block").addClass("d-none")
        $(`.room_${room_id}`).addClass("d-block")
    })


    $('.private_chat').click(function (){
        let room_id = $(this).data("room_id");

        if(room_id != 0){
            $(".message_list").removeClass("d-block").addClass("d-none")
            $(`.room_${room_id}`).addClass("d-block")
        }else {
            let user_id = $(this).data("user_id");
            let token = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: "new/room",
                method: "post",
                data: {user_id:user_id,_token:token},
                success:function (response){
                    $(".message_list").removeClass("d-block").addClass("d-none")
                    $('.messages_list').removeClass('d-none')
                    $('.users_list').addClass('d-none')
                    $('.room_list').append(` <li class="w3-bar ">
                                <span data-room_id = "${response.room.id}"
                                      class="  w3-bar-item w3-button w3-white w3-xlarge write_now w3-right">Write</span><div class="w3-bar-item">
                                <span class="w3-large">${response.user.name}</span><br>


</div></li>`)
                    $('.room_message_list').prepend(` <div  data-room_id = "${response.room.id}" class="room_${response.room.id}  message_list d-block" style="height: 100%; width: 100%"> </div>`)
                    console.log(response)
                }
            })
        }

    })

    $(".send_message").click(function (){
        let body = $('.message_body').val();
        let room = $('.message_list.d-block');
        let room_id = room.data("room_id")
        let token = $('meta[name="csrf-token"]').attr('content');

        $.ajax({
            url:"send/message",
            data:{body:body,room_id:room_id,_token:token},
            method: "post",
            success:function (respons){
                room.append(respons.body)
            }
        })
    })
    const user_id = {!! \Illuminate\Support\Facades\Auth::id() !!};




</script>
