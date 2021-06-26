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
      <h2>Create Categories</h2>
      <div class="form-group">
        <label class="control-label col-sm-2" for="email">Category:</label>
        <div class="col-sm-5">
          <p id="Ermessage" style="color:green"></p>
          <input type="text" class="form-control" id="category" placeholder="Enter category">
        </div>
         <div class="col-sm-offset-2 col-sm-3">
          <p></p>
          <input type="button" class="btn btn-info" id="addCategory" value="Submit">
        </div>

      </div>

    </form>
	
	 <h2>Category List</h2>
	  <table class="table">
		<thead>
		  <tr>
			<th>ID</th>
			<th>category</th>
			<th>status</th>
			<th>created</th>
			<th>Action</th>
		  </tr>
		</thead>
		<tbody>
		<?php foreach($category as $cat){
			echo'<tr>
			<td>'.$cat->id.'</td>
			<td>'.$cat->category.'</td>
			<td>'.$cat->status.'</td>
			<td>'.$cat->created_at.'</td>
			<td><a href="'.get_site_url().'/wp-admin/admin.php?page=portfolio-edit&id='.$cat->id.'" class="btn btn-primary a-btn-slide-text">
				<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
				<span><strong>Edit</strong></span>            
			</a>
			<a href="'.get_site_url().'/wp-admin/admin.php?page=portfolio-delete&id='.$cat->id.'" class="btn btn-primary a-btn-slide-text">
			   <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
				<span><strong>Delete</strong></span>            
			</a></td>
			</tr>';
			}?>
		</tbody>
		</table>
 
</div>
</body>
</html>
