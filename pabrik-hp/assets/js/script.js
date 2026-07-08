$(document).ready(function(){
	
	// event ketika keyword ditulis

	$('#keyword').on('keyup', function(){
		$('#container').load('ajax/inventaris.php?keyword=' + $('#keyword').val());
	});
})