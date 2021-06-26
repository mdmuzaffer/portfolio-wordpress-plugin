<!DOCTYPE html>
<html lang="en">
<head>
  <title>Portfolio List</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body> 

<div class="container">
    <form class="form-horizontal">
      <h2>Upload Category images</h2>
      <div class="form-group">
        <p id="ErmessageImage" style="color:green"></p>
        <input type="hidden" id="image_url" value=""/>

      <label  class="control-label col-sm-2">Select list:</label>
      <div class="col-sm-2">
          <select class="form-control" id="selectCat">
          <option>select</option>
            <?php foreach($category as $cat){ ?>
            <option value="<?php echo $cat->id ?>"><?php echo $cat->category ?></option>
          <?php } ?>
          </select>
      </div>

        <label class="control-label col-sm-1" for="email">Category:</label>
        <div class="col-sm-2">
          <input type="button" class="form-control btn btn-info" id="categoryMedia" value="Upload Image">
        </div>

        <div class="col-sm-offset-1 col-sm-1">
          <input type="button" class="btn btn-info" id="uploadImages" value="Upload">
        </div>

    </div>





    </form>

  
</div>

</body>
</html>
