<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>

<body
    style="
    	display: flex;
        flex-direction: column;
    	justify-content: center;
        background-color: #181623;
        color: white;
        margin: 0 50px;
    ">
    <div
        style="
    	display: flex;
    	flex-direction: column;
        align-items: center;
        margin-top: 50px;
        margin-bottom: 50px;
    ">
        <img width="40" src="https://i.ibb.co/k8Q4q3J/Vector.png" alt="email-photo">
        <p style="color: #DDCCAA">MOVIE QUOTES</p>
    </div>
    <div>
        <p style="margin-bottom: 30px;">Hola {{ $username }}</p>
        <p style="margin-bottom: 30px;">Thanks for joining Movie quotes! We really appreciate it. Please click the
            button below to verify your account:</p>
        <a href="{{ $url }}"
            style="
            padding: 10px 20px;
            background-color: #E31221;
            border: none;
            color: white;
            cursor: pointer;
            margin-bottom: 30px;

        ">Verify
            Account</a>
        <p style="margin-bottom: 30px; margin-top: 50px;">If clicking doesn't work, you can try copying and pasting it
            to your browser:</p>
        <a style="color: #DDCCAA; word-wrap: break-word;">{{ $url }}</a>
        <p>If you have any problems, please contact us: support@moviequotes.ge</p>
        <p>MovieQuotes Crew</p>
    </div>

</body>

</html>
