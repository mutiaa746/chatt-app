<!DOCTYPE html>
<html>
<head>
    <title>Chat App</title>

    <style>

        body{
            font-family: Arial;
            background:#f2f2f2;
            display:flex;
            justify-content:center;
            margin-top:50px;
        }

        .chat-box{
            width:400px;
            background:white;
            padding:20px;
            border-radius:10px;
        }

        #messages{
            height:300px;
            border:1px solid #ccc;
            overflow-y:scroll;
            padding:10px;
            margin-bottom:10px;
        }

        input{
            width:100%;
            padding:10px;
            margin-top:10px;
        }

        button{
            margin-top:10px;
            padding:10px;
            width:100%;
        }

        .my-message{
            background:#007bff;
            color:white;
            padding:10px;
            border-radius:10px;
            margin:10px 0;
            margin-left:80px;
        }

        .other-message{
            background:#e4e6eb;
            color:black;
            padding:10px;
            border-radius:10px;
            margin:10px 0;
            margin-right:80px;
        }

    </style>

</head>

<body>

<div class="chat-box">

    <h2>Chat Application</h2>

    <input type="text"
           id="username"
           placeholder="Username">

    <div id="messages"></div>

    <input type="text"
           id="message"
           placeholder="Type message...">

    <button onclick="sendMessage()">
        Send
    </button>

</div>

<script>

function sendMessage(){

    let username =
        document.getElementById("username").value;

    let message =
        document.getElementById("message").value;

    let messages =
        document.getElementById("messages");

    if(username.trim() !== "" &&
       message.trim() !== ""){

        let messageClass =
            username === "Mutia"
            ? "my-message"
            : "other-message";

        messages.innerHTML += `

        <div class="${messageClass}">
            <strong>${username}</strong>
            <p>${message}</p>
        </div>

        `;

        document.getElementById("message").value = "";

        messages.scrollTop = messages.scrollHeight;
    }
}

</script>

</body>
</html>