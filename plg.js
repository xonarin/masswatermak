
jQuery( document ).ready(function() {
	var pid = jQuery("#post_ID").val();
	
	jQuery("#acf-group_61e2f85d713da .inside").append('<div class="acf-field compl"></div><div class="acf-field"><a href="#" class="acf-button button button-primary wtmk" onclick="start_wtmk('+pid+');">Пересоздать изображения</a></div>');
});

function start_wtmk(pid)
{
	var inp1 = jQuery("#acf-group_61e2f85d713da .inside input").eq(0).val();
	var inp2 = jQuery("#acf-group_61e2f85d713da .inside input").eq(1).val();

	if(inp1 !="" || inp2 !="" )
	{	
		var arr_img  = [];
		var arr_name = [];
		
		var nal      = '';
		var inp_name = '';
		var img_src  = '';
		
		jQuery(".acf-gallery-main").each(function (a, d){
			nal = jQuery("img", this).eq(0).attr("src");
			
			var p = jQuery(".acf-gallery-attachment input", this)
			
			if( nal )
			{
				jQuery(".acf-gallery-attachment", this).each(function (i, v){
					
					var data_id = jQuery(this).attr("data-id");
					
					img_src = jQuery("img", this).attr("src");
					var wt = img_src.split('WTMK');
					
					if(wt[1])
					{
						jQuery(".acf-gallery-attachment[data-id='"+data_id+"']").remove();
						jQuery.ajax({
							type: "POST",
							url: "/wp-content/plugins/water/ax.php",
							data: "op=del_att&pid="+pid,
							success: function(m){
								//
							}
						});
					}
					
				});
				
				jQuery(".acf-gallery-attachment", this).each(function (i, v){
					
					inp_name = jQuery("input", this).attr("name");
					img_src = jQuery("img", this).attr("src");
					
					arr_img.push(img_src);
					arr_name.push(inp_name);
				});
			}
		});
		
		if( arr_img[0] )
		{
			img_render(arr_img, arr_name, pid);
		}
	}
	else
	{
		if( confirm("Удалить все водяные знаки?") )
		{
			var arr_img  = [];
			var img_src  = '';
			
			jQuery(".acf-gallery-main").each(function (a, d){
				nal = jQuery("img", this).eq(0).attr("src");
		
				if( nal )
				{
					jQuery(".acf-gallery-attachment", this).each(function (i, v){
						img_src = jQuery("img", this).attr("src");
						
						arr_img.push(img_src);
					});
				}
			});
			
			if( arr_img[0] )
			{
				del_wt(arr_img);
			}
		}
	}
	
	function del_wt(arr_img)
	{
		if(arr_img[0])
		{
			var c = arr_img.length;
			
			jQuery(".compl").html("<img src='/wp-content/plugins/water/loading.gif' height='20' /> Удаление.. <b>" + c + '</b>');
			
			jQuery.ajax({
				type: "POST",
				url: "/wp-content/plugins/water/ax.php",
				data: "op=del_att&im="+arr_img[0],
				success: function(m){
					console.log(m);
					arr_img.splice(0, 1);
					del_wt(arr_img);
				}
			});
		}
		else
		{
			jQuery(".compl").html("Все водные знаки удалены.");
			alert("Удаление завершено");
		}
	}

	function img_render(arr_img, arr_name, pid)
	{
		if(arr_img[0])
		{
			var c = arr_img.length;
			
			jQuery(".compl").html("<img src='/wp-content/plugins/water/loading.gif' height='20' /> Обработка файлов.. <b>" + c + '</b>');
			
			jQuery.ajax({
				type: "POST",
				url: "/wp-content/plugins/water/ax.php",
				data: "op=add_img&im="+arr_img[0]+"&txt1="+inp1+"&txt2="+inp2+"&arr_name="+arr_name[0]+"&pid="+pid,
				success: function(m){
				
					console.log(m);
					
					arr_img.splice(0, 1);
					arr_name.splice(0, 1);
					img_render(arr_img, arr_name, pid);
				}
			});
		}
		else
		{
			jQuery(".compl").html("Файлы успешно перезаписаны.");
			
			alert("Файлы успешно перезаписаны.")
		}
	}
}

