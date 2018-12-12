<html>
<head>
	<title></title>
	<style type="text/css">
		span{
			float:left;
		}
	</style>
	<script type="text/javascript" charset="utf8" src="<?= base_url().'assets/plugins/DataTables-1.10.4/media/js/jquery.js' ?>"></script>
</head>
<body>

<div class="links"></div>
<script type="text/javascript">

$(document).ready(function(){
	var get_link = $.ajax({
		type : "get",
		url  : "http://localhost/grabing/index.php/ajax_redirect/get_all_link"
	});

	get_link.done(function(msg){
		var data  = JSON.parse(msg);
		var count = 0; 
		while(count < (data.length-1) ) {
			var coba_link = $.ajax({
				type : "get",
				data : {link : data[count] },
				url  : "http://localhost/grabing/index.php/ajax_redirect/redir_link"
			});
			coba_link.done(function(msg){
				console.log(link);
				var link = JSON.parse(msg);
				$(".links").append("<span>"+link+"</span>");

				
			});
			count++;
		}
	});
});

</script>
</body>
</html>