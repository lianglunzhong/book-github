function uploadImageToServer(fileElmId, type, id)
{
	$('#'+id).attr('src', '/admin/static/h-ui.admin/images/loading1.gif');
	$.ajaxFileUpload({
		url: '/service/upload'+type,
		fileElementId: fileElmId,
		dataType: 'text',
		success: function(data) {
			var result = JSON.parse(data);
			$('#'+id).attr('src', result.uri);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			alert(errorThrown);
		}
	});
	return false;
}