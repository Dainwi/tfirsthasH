<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>

    </style>
</head>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>


<body>
    <div id="editor">

    </div>

    <div id="editor1">
        <ol>
            <li>hghj<strong>ghhj</strong></li>
            <li><strong>gfgh</strong></li>
            <li><strong>ghfgh</strong></li>
        </ol>
    </div>

    <div id="editor2">

    </div>

    <div id="editor3">

    </div>
    <script>
        var eee = "#editor1";
        var quill = new Quill(eee, {
            theme: 'snow'
        });
    </script>
</body>

</html>