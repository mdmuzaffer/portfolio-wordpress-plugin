$(function() {
	// Add category
    $('#addCategory').click(function(){
    	var category = $('#category').val();
    	var ajaxurl = portfolio_ajax_object.adminajaxUrl; 
    	if(category ==""){
    		$("#Ermessage").html('Please enter the category name !');
    	}else{

			var data = {
			        action: 'save_category',
			        contentType: false,
                    processData: false,
			        category_name:  category,
			    };
			    jQuery.post(
			        ajaxurl,
			        data,
			        function(response){
			        	var returndata = JSON.parse( response );
			            console.log(returndata.message);
			            $("#Ermessage").html(returndata.message);
						setTimeout(function () { location.reload(); }, 3000);
			        }
			    );
    	}
    });

    //Upload gellery images
    $("#categoryMedia").on('click', function(e){
    	e.preventDefault();
	        var image = wp.media({ 
	        title: 'Category Images',
	        multiple: true
		    }).open()
		      .on('select', function(e){
		     //var uploaded_image = image.state().get('selection').first();
		      	var uploaded_images = image.state().get('selection');
		        var attachment_ids = uploaded_images.map( function( attachment ) {
                var imgs = attachment.toJSON().url;
                $('#image_url').append(imgs+'@');
                //console.log(attachment.url);

            }).join();
		 });
    });

    // select data
    $("#uploadImages").on('click', function(e){
    	e.preventDefault();
    	var ajaxurl = portfolio_ajax_object.adminajaxUrl; 
    	var imagesUrl = $("#image_url").text();
    	var cat = $("#selectCat").find(":selected").val();
    	if(imagesUrl ==""){
    		alert('Please select the category images');
    	}
    	if(imagesUrl ==""){
    		alert('Please select the category of portfolio');
    	}else{

			var data = {
		        action: 'save_category_images',
		        contentType: false,
                processData: false,
		        category_id:  cat,
		        image:  imagesUrl,
		    };
		    jQuery.post(
		        ajaxurl,
		        data,
		        function(response){
		        	var returndata = JSON.parse( response );
		           //console.log(returndata.message);
		            if(returndata.status ==1){
		            	$("#ErmessageImage").html(returndata.message);
		        	}
		        }
		    );
    	}
 

    });

});