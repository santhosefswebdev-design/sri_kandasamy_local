<?php 
if($view == true){
    $readonly = 'readonly';
}
?>
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
            <h2>Archanai<small>Archanai / <b>Diety</b></small></h2>
        </div>
        <!-- Basic Examples -->
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                            <div class="row"><div class="col-md-8"></div>
                        <div class="col-md-4" align="right"><a href="<?php echo base_url(); ?>/archanai/diety_list"><button type="button" class="btn bg-deep-purple waves-effect">List</button></a></div></div>
                    </div>
                    <form action="<?php echo base_url(); ?>/archanai/save_diety" method="POST" enctype="multipart/form-data">
                    <div class="body">
                        <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                        <div class="container-fluid">
                        <div class="row clearfix">
                            <div class="col-sm-6">
                                <div class="form-diety form-float">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" name="name" value="<?php echo strtoupper($data['name']);?>" <?php echo $readonly; ?>>
                                        <label class="form-label">Name English</label>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-diety form-float">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" name="name_tamil" value="<?php echo strtoupper($data['name_tamil']);?>" <?php echo $readonly; ?>>
                                        <label class="form-label">Name Tamil</label>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-diety form-float">
                                    <div class="form-line">
                                        <input type="text"  class="form-control" name="code" value="<?php echo strtoupper($data['code']);?>" <?php echo $readonly; ?>>
                                        <label class="form-label">Deity Code</label>
                                        
                                    </div>
                                </div>
                            </div>
							<div class="col-sm-4">
                                <div class="form-diety form-float">
                                    <div class="form-line focused">
                                        <input name="image" id="image" class="form-control" type="file" accept="image/png, image/gif, image/jpeg">
                                        <label class="form-label">Diety Image</label>
                                    </div>
                                </div>
                            </div>
							 <div class="col-sm-2">
								<?php $img_url = !empty($data['image']) ? base_url() . '/uploads/diety/' . $data['image'] : ''; ?>
								<img id="img_pre" src="<?php echo $img_url; ?>" class="img-responsive" style="width:100px;">
							</div>
                            <div class="col-sm-2">
                                <div class="form-diety form-float">
                                    <div class="form-line">
                                        <input type="checkbox" id="kattalai" name="kattalai_deity" value="1" <?php if($data['kattalai_deity'] == '1'){ echo "checked"; } ?> class="form-control" >
                                        <label for="kattalai" class="form-label">Kattalai Archanai </label>
                                    </div>
                                </div>
                            </div>
                            <?php if($view != true) { ?>
                            <div class="col-sm-12" align="center">
                                <button type="submit" class="btn btn-success btn-lg waves-effect">SAVE</button>
                                <button type="button" id="clear" class="btn btn-primary btn-lg waves-effect">CLEAR</button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<link href="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/css/bootstrap-select.css" rel="stylesheet" />
<script src="<?php echo base_url(); ?>/assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>
<script>
$(document).ready(function(){
	$("#image").change(function(){
		// alert (0);
		readURL(this);		
	});
			
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				//alert (URL.createObjectURL(e.target.files[0]))
				$('#img_pre').attr('src', e.target.result);
				$('#img_pre').show();
				//$('#img_anchor').attr("href", e.target.result)				
			}            
			reader.readAsDataURL(input.files[0]);
		}
	}
});
</script>
<script>
	$("#clear").click(function(){
	   $("input").val("");
	});
	$("form").on("submit", function(){
        $('input[type=submit]').prop('disabled', true);
        $("#loader").show();
    });
</script>