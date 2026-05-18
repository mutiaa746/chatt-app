<x-app-layout>

<script>
    window.LaravelUser = "{{ auth()->user()->name }}";
</script>

<div style="padding:20px; background:#f5f5f5; min-height:100vh;">

    <h1 style="font-size:32px; font-weight:bold; margin-bottom:20px;">
        Realtime Chat App
    </h1>

    <div style="display:flex; gap:20px;">

        <!-- USER LIST -->
        <div style="
            width:250px;
            background:white;
            border-radius:15px;
            padding:20px;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        ">

            <h3 style="margin-bottom:20px; font-size:22px;">
                Users
            </h3>

            @foreach($users as $user)

                <div style="
                    padding:15px;
                    border-radius:10px;
                    margin-bottom:10px;
                    background:#f1f1f1;
                    font-weight:bold;
                ">
                    {{ $user->name }}
                </div>

            @endforeach

        </div>

        <!-- CHAT AREA -->
        <div style="
            flex:1;
            background:white;
            border-radius:15px;
            padding:20px;
            box-shadow:0 2px 10px rgba(0,0,0,0.1);
        ">

            <!-- CHAT MESSAGE -->
            <div id="messages"
                 style="
                    height:500px;
                    overflow-y:auto;
                    padding:10px;
                    background:#fafafa;
                    border-radius:10px;
                    margin-bottom:20px;
                 ">
            </div>

            <!-- INPUT -->
            <div style="display:flex; gap:10px;">

                <input
                    type="text"
                    id="message"
                    placeholder="Type message..."
                    style="
                        flex:1;
                        padding:15px;
                        border-radius:10px;
                        border:1px solid #ccc;
                        outline:none;
                    ">

                <button
                    id="sendBtn"
                    style="
                        background:#0d6efd;
                        color:white;
                        border:none;
                        padding:15px 25px;
                        border-radius:10px;
                        cursor:pointer;
                        font-weight:bold;
                    ">

                    Send

                </button>

            </div>

        </div>

    </div>

</div>

</x-app-layout>