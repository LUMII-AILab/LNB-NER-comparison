<?php
include("filetree/php_file_tree.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" href="style2.css" type="text/css" />
		<link href="filetree/default.css" rel="stylesheet" type="text/css" />
		<script type="text/javascript" src="jquery.js"></script>
		<script type="text/javascript">
			$(document).ready(function() { 
				//FileTree
				// Hide all subfolders at startup
				$(".php-file-tree").find("UL").hide();

				// Expand/collapse on click
				$(".php-file-tree").on('click','.pft-directory', function() {
					$(this).find("UL:first").slideToggle("medium");
					return false;
				});

				$(".php-file-tree").on('click','.file', function() {
					fails=$(this).attr('rel');
					$.post("diff.php",{'file':$(this).attr('rel')},function(resp) {$("#result").html(resp)});
					return false;
					
				});
			});
		</script>
    </head>
    <body>
		<div id="izvele">
			<?php echo php_file_tree("compare/pc/"); ?>
		</div>
		<div id="result">
		
		</div>
	</body>
</html>