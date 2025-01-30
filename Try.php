<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRY </title>


    <link rel="stylesheet" href="Style/Try.css" />
 

</head>

<body>

<div class="editable-container">
    <h2>Photo Description</h2>
    <textarea class="editable-description" placeholder="Edit description here...">This is the default description of the photo.</textarea>
  </div>
  <button onclick="saveDescription()">Save Description</button>


</body>

<script>
    function saveDescription() {
      let description = document.querySelector('.editable-description').value;
      alert('Description saved: ' + description);
    }
  </script>

</html>