<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  
  <form action="{{ route('test_send') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label for="file">Input file Ics</label>
    <input type="file" name="file">
    <button type="submit">submit</button>
  </form>
  <button id="kirim">kirim</button>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script>
    $(document).ready(function(){
      $('#kirim').click(function(){

      })
    })
  </script>
</body>
</html>