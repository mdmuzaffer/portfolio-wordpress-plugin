<!DOCTYPE html>
<html lang="en">
<head>
  <title>Portfolio List</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="container">
  <h2>Portfolio List</h2>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>category_id</th>
        <th>category</th>
        <th>images</th>
        <th> statues &nbsp;&nbsp;</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      foreach ( $portfolioData as $classified ) {
      $last_pos = strripos("$classified->images","@");
     
      $all_iamges = substr($classified->images, 0, $last_pos);
      $image_array = explode("@",$all_iamges);

      ?>
      <tr>
        <td><?php echo $classified->id; ?></td>
        <td><?php echo $classified->category_id; ?></td>
        <td><?php echo $classified->category; ?></td>
        <td>
          <?php
          foreach ( $image_array as $image_array_val ) {
          ?>
          <img src="<?php echo $image_array_val;  ?>" style="width: 100px; height: 100px;">
          <?php
          } 
          ?>
        </td>
        <td><?php if($classified->status =="1"){ echo'<span class="text-info">Active</span>';}else{echo'<span class="text-danger">In active</span>';} ?></td>
      </tr>
      <?php 
      }
      ?>
    </tbody>
  </table>
</div>

</body>
</html>
