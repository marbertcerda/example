<?php 
include 'db_connect.php';
$login_type = isset($_SESSION['login_type']) ? $_SESSION['login_type'] : 0;

$folder_parent = isset($_GET['fid']) ? $_GET['fid'] : 0;

// Check login type and adjust the query accordingly
if ($login_type == 1) {
    $folders = $conn->query("SELECT * FROM folders WHERE id = $folder_parent OR parent_id = $folder_parent ORDER BY id ASC");
    $files = $conn->query("SELECT * FROM files WHERE folder_id = $folder_parent ORDER BY name ASC");
} else {
    $user_id = isset($_SESSION['login_id']) ? $_SESSION['login_id'] : 0;
    $folders = $conn->query("SELECT * FROM folders WHERE (id = $folder_parent OR parent_id = $folder_parent) AND user_id = $user_id ORDER BY id ASC");
    $files = $conn->query("SELECT * FROM files WHERE folder_id = $folder_parent ORDER BY name ASC");
}
function getFolderPaths($conn, $folder_parent, $login_type)
{
    $paths = array();

    while ($folder_parent > 0) {
        $pathQuery = $conn->query("SELECT * FROM folders WHERE id = $folder_parent");
        $path = $pathQuery->fetch_assoc();

        // Check if the user has permission to access this folder
        if ($login_type == 1 || $path['user_id'] == $_SESSION['login_id']) {
            $paths[] = $path;
        }

        $folder_parent = $path['parent_id'];
    }

    return array_reverse($paths);
}   

// Fetch folder paths based on login type
$paths = getFolderPaths($conn, $folder_parent, $login_type);
function getUploaderName($conn, $user_id) {
    $query = $conn->query("SELECT username FROM users WHERE id = $user_id");
    $result = $query->fetch_assoc();

    return $result ? $result['username'] : 'Unknown Uploader';
}
?>
<style>

	.folder-item{
		cursor: pointer;
	}
	.folder-item:hover{
		background: #eaeaea;
	    color: black;
	    box-shadow: 3px 3px #0000000f;
	}
	.custom-menu {
        z-index: 1000;
	    position: absolute;
	    background-color: #ffffff;
	    border: 1px solid #0000001c;
	    border-radius: 5px;
	    padding: 8px;
	    min-width: 13vw;
}
a.custom-menu-list {
    width: 100%;
    display: flex;
    color: #4c4b4b;
    font-weight: 600;
    font-size: 1em;
    padding: 1px 11px;
}
.file-item{
	cursor: pointer;
}
a.custom-menu-list:hover,.file-item:hover,.file-item.active {
    background: #80808024;
}
table th,td{
	/*border-left:1px solid gray;*/
}
a.custom-menu-list span.icon{
		width:1em;
		margin-right: 5px
}

/* added design */
.card {
    border: 1px solid #ddd;
    border-radius: 8px;
    transition: box-shadow 0.3s;
}

.card:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Table */
table {
    width: 100%;
    border-collapse: collapse;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

</style>
<div class="container-fluid">
	<div class="col-lg-12">
		<div class="row">
			<div class="card col-lg-12">
				<div class="card-body" id="paths">
				<!-- <a href="index.php?page=files" class="">..</a>/ -->
				<?php
    foreach ($paths as $path) {
        echo '<script>
            $("#paths").prepend("<a href=\"index1.php?page=files&fid=' . $path['id'] . '\">' . $path['name'] . '</a>/")
        </script>';
    }

    echo '<script>
        $("#paths").prepend("<a href=\"index1.php?page=files\">../</a>")
    </script>';
    ?>
					
				</div>
			</div>
		</div>
<br>
		<div class="row">
			<button class="btn btn-primary btn-sm" id="new_folder"><i class="fa fa-plus"></i> New Folder</button>
			<button class="btn btn-primary btn-sm ml-4" id="new_file"><i class="fa fa-upload"></i> Upload File</button>
		</div>
		<hr>
		<div class="row">
			<div class="col-lg-12">
			<div class="col-md-4 input-group offset-4">
				
  				<input type="text" class="form-control" id="search" aria-label="Small" aria-describedby="inputGroup-sizing-sm">
  				<div class="input-group-append">
   					 <span class="input-group-text" id="inputGroup-sizing-sm"><i class="fa fa-search"></i></span>
  				</div>
			</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12"><h4><b>Folders</b></h4></div>
		</div>
		<hr>
		<div class="row">
			 <?php 
    while ($row = $folders->fetch_assoc()):
        // Check if the folder has the specified parent ID
        if ($row['parent_id'] == $folder_parent):
    ?>
        <div class="card col-md-3 mt-2 ml-2 mr-2 mb-2 folder-item" data-id="<?php echo $row['id'] ?>">
            <div class="card-body">
                <large><span><i class="fa fa-folder"></i></span><b class="to_folder"> <?php echo $row['name'] ?></b></large>
            </div>
        </div>
    <?php 
        endif;
    endwhile; 
    ?>
		</div>
		<hr>
		<div class="row">
			<div class="card col-md-12">
				<div class="card-body">
					<table id="filesTable" class="display">
    <thead>
        <tr>
			<th>Uploader</th>
            <th>Filename</th>
            <th>Date</th>
            <th>Description</th>
             <!-- Added column for uploader name -->
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $files->fetch_assoc()):
            $name = explode(' ||', $row['name']);
            $name = isset($name[1]) ? $name[0] . " (" . $name[1] . ")." . $row['file_type'] : $name[0] . "." . $row['file_type'];

            $img_arr = array('png', 'jpg', 'jpeg', 'gif', 'psd', 'tif');
            $doc_arr = array('doc', 'docx');
            $pdf_arr = array('pdf', 'ps', 'eps', 'prn');
            $icon = 'fa-file';

            if (in_array(strtolower($row['file_type']), $img_arr))
                $icon = 'fa-image';
            if (in_array(strtolower($row['file_type']), $doc_arr))
                $icon = 'fa-file-word';
            if (in_array(strtolower($row['file_type']), $pdf_arr))
                $icon = 'fa-file-pdf';
            if (in_array(strtolower($row['file_type']), ['xlsx', 'xls', 'xlsm', 'xlsb', 'xltm', 'xlt', 'xla', 'xlr']))
                $icon = 'fa-file-excel';
            if (in_array(strtolower($row['file_type']), ['zip', 'rar', 'tar']))
                $icon = 'fa-file-archive';
        ?>
        <tr class='file-item' data-id="<?php echo $row['id'] ?>" data-name="<?php echo $name ?>">
		<td><?php echo getUploaderName($conn, $row['user_id']) ?></td>
            <td>
                <large><span><i class="fa <?php echo $icon ?>"></i></span><b> <?php echo $name ?></b></large>
                <input type="text" class="rename_file" value="<?php echo $row['name'] ?>" data-id="<?php echo $row['id'] ?>" data-type="<?php echo $row['file_type'] ?>" style="display: none">
            </td>
            <td><i><?php echo date('Y/m/d h:i A', strtotime($row['date_updated'])) ?></i></td>
            <td><i><?php echo $row['description'] ?></i></td>
            
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
					
				</div>
			</div>
			
		</div>
	</div>
</div>
<div id="menu-folder-clone" style="display: none;">
	<a href="javascript:void(0)" class="custom-menu-list file-option edit">Rename</a>
	<a href="javascript:void(0)" class="custom-menu-list file-option delete">Delete</a>

</div>
<div id="menu-file-clone" style="display: none;">
	<a href="javascript:void(0)" class="custom-menu-list file-option edit"><span><i class="fa fa-edit"></i> </span>Rename</a>
	<a href="javascript:void(0)" class="custom-menu-list file-option download"><span><i class="fa fa-download"></i> </span>Download</a>
	<a href="javascript:void(0)" class="custom-menu-list file-option delete"><span><i class="fa fa-trash"></i> </span>Delete</a>
</div>

<script>
	
	$('#new_folder').click(function(){
		uni_modal('','manage_folder.php?fid=<?php echo $folder_parent ?>')
	})
	$('#new_file').click(function(){
		uni_modal('','manage_files.php?fid=<?php echo $folder_parent ?>')
	})
	$('.folder-item').dblclick(function(){
		location.href = 'index1.php?page=files&fid='+$(this).attr('data-id')
	})
	$('.folder-item').bind("contextmenu", function(event) { 
    event.preventDefault();
    $("div.custom-menu").hide();
    var custom =$("<div class='custom-menu'></div>")
        custom.append($('#menu-folder-clone').html())
        custom.find('.edit').attr('data-id',$(this).attr('data-id'))
        custom.find('.delete').attr('data-id',$(this).attr('data-id'))
    custom.appendTo("body")
	custom.css({top: event.pageY + "px", left: event.pageX + "px"});

	$("div.custom-menu .edit").click(function(e){
		e.preventDefault()
		uni_modal('Rename Folder','manage_folder.php?fid=<?php echo $folder_parent ?>&id='+$(this).attr('data-id') )
	})
	$("div.custom-menu .delete").click(function(e){
		e.preventDefault()
		_conf("Are you sure to delete this Folder?",'delete_folder',[$(this).attr('data-id')])
	})
})

	//FILE
	$('.file-item').bind("contextmenu", function(event) { 
    event.preventDefault();

    $('.file-item').removeClass('active')
    $(this).addClass('active')
    $("div.custom-menu").hide();
    var custom =$("<div class='custom-menu file'></div>")
        custom.append($('#menu-file-clone').html())
        custom.find('.edit').attr('data-id',$(this).attr('data-id'))
        custom.find('.delete').attr('data-id',$(this).attr('data-id'))
        custom.find('.download').attr('data-id',$(this).attr('data-id'))
    custom.appendTo("body")
	custom.css({top: event.pageY + "px", left: event.pageX + "px"});

	$("div.file.custom-menu .edit").click(function(e){
		e.preventDefault()
		$('.rename_file[data-id="'+$(this).attr('data-id')+'"]').siblings('large').hide();
		$('.rename_file[data-id="'+$(this).attr('data-id')+'"]').show();
	})
	$("div.file.custom-menu .delete").click(function(e){
		e.preventDefault()
		_conf("Are you sure to delete this file?",'delete_file',[$(this).attr('data-id')])
	})
	$("div.file.custom-menu .download").click(function(e){
		e.preventDefault()
		window.open('download.php?id='+$(this).attr('data-id'))
	})

	$('.rename_file').keypress(function(e){
		var _this = $(this)
		if(e.which == 13){
			start_load()
			$.ajax({
				url:'ajax.php?action=file_rename',
				method:'POST',
				data:{id:$(this).attr('data-id'),name:$(this).val(),type:$(this).attr('data-type'),folder_id:'<?php echo $folder_parent ?>'},
				success:function(resp){
					if(typeof resp != undefined){
						resp = JSON.parse(resp);
						if(resp.status== 1){
								_this.siblings('large').find('b').html(resp.new_name);
								end_load();
								_this.hide()
								_this.siblings('large').show()
						}
					}
				}
			})
		}
	})

})
//FILE


	$('.file-item').click(function(){
		if($(this).find('input.rename_file').is(':visible') == true)
    	return false;
		uni_modal($(this).attr('data-name'),'manage_files.php?<?php echo $folder_parent ?>&id='+$(this).attr('data-id'))
	})
	$(document).bind("click", function(event) {
    $("div.custom-menu").hide();
    $('#file-item').removeClass('active')

});
	$(document).keyup(function(e){

    if(e.keyCode === 27){
        $("div.custom-menu").hide();
    $('#file-item').removeClass('active')

    }

});
	$(document).ready(function(){

         $('#filesTable').DataTable();

		$('#search').keyup(function(){
			var _f = $(this).val().toLowerCase()
			$('.to_folder').each(function(){
				var val  = $(this).text().toLowerCase()
				if(val.includes(_f))
					$(this).closest('.card').toggle(true);
					else
					$(this).closest('.card').toggle(false);

				
			})
			$('.to_file').each(function(){
				var val  = $(this).text().toLowerCase()
				if(val.includes(_f))
					$(this).closest('tr').toggle(true);
					else
					$(this).closest('tr').toggle(false);

				
			})
		})
	})
	function delete_folder($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_folder',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp == 1){
					alert_toast("Folder successfully deleted.",'success')
						setTimeout(function(){
							location.reload()
						},1500)
				}
			}
		})
	}
	function delete_file($id){
		start_load();
		$.ajax({
			url:'ajax.php?action=delete_file',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp == 1){
					alert_toast("Folder successfully deleted.",'success')
						setTimeout(function(){
							location.reload()
						},1500)
				}
			}
		})
	}



</script>