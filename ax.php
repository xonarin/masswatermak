<?
	mb_internal_encoding("UTF-8");
	
	session_start();
	
	require_once $_SERVER['DOCUMENT_ROOT'].'/wp-config.php';
	
	function data_cut($data)
	{
		return htmlspecialchars(trim($data));
	}
	
	$op       = data_cut($_REQUEST['op']);
	$src      = data_cut($_POST['im']);
	$txt1     = data_cut($_POST['txt1']);
	$txt2     = data_cut($_POST['txt2']);
	$arr_name = data_cut($_POST['arr_name']);
	$post_id  = data_cut($_POST['pid']);
	
	if($op == 'del_att' && $src)
	{
		$arr = explode('wp-content/', $src);
		
		if($arr[1])
		{
			$path_file = explode('/', $arr[1]);
			
			$c = count($path_file) - 1;
			
			$filename = $path_file[$c]; //имя файла первоначальное
			
			unset( $path_file[$c] );
			
			$path_web = '/wp-content/' . implode('/', $path_file) . '/';
			$path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/' . implode('/', $path_file) . '/'; //путь
			
			$ex = explode('.', $filename);
			$ext = $ex[1];
			
			$before = explode('-', $ex[0]);
			array_pop($before);
			
			$before = implode('-', $before);
			
			if( file_exists($path.'copy-'.$before.'.'.$ext) )
			{
				if(copy($path.'copy-'.$before.'.'.$ext, $path.$before.'.'.$ext ))
				{
					unlink($path.'copy-'.$before.'.'.$ext);
					echo $path.'copy-'.$before.'.'.$ext .' - удален';
				}
			}
			else
			{
				echo 'Не существует копии';
			}
		}
	}
	
	if($op == 'add_img' && $src && $post_id > 0)
	{
		$arr = explode('wp-content/', $src);
		
		if($arr[1])
		{
			$path_file = explode('/', $arr[1]);
			
			$c = count($path_file) - 1;
			
			$filename = $path_file[$c]; //имя файла первоначальное
			
			unset( $path_file[$c] );
			
			$path_web = '/wp-content/' . implode('/', $path_file) . '/';
			$path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/' . implode('/', $path_file) . '/'; //путь
			
			$ex = explode('.', $filename);
			$ext = $ex[1];
			
			$before = explode('-', $ex[0]);
			array_pop($before);
			
			$before = implode('-', $before);
			
			$file = $path . $before . '.' . $ext;
			
			
			if( file_exists($file) ) 
			{
				//если нет копии оригинала, создаем
				if( !file_exists($path.'copy-'.$before.'.'.$ext) )
				{
					copy($file, $path.'copy-'.$before.'.'.$ext );
					
				}
				else
				{
					$file = $path.'copy-'.$before.'.'.$ext; //копия есть берем чистую копию
				}
				
				$new_file = $path.$before.'.'.$ext;
				
				$img_size = getimagesize($file);
				
				$im_w = $img_size[0];
				$im_h = $img_size[1];
				
				$center = round($im_w/2);

				$font_size = 18;
				$y = $im_h - 60;
				
				$fontName = $_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/water/arial.ttf";
				
				if($ext == 'jpg' || $ext == 'jpeg') $img = imagecreatefromjpeg( $file );
				if($ext == 'png') $img = imagecreatefrompng( $file );
				if($ext == 'gif') $img = imagecreatefromgif( $file );

				$grey  = imagecolorallocate($img, 0, 0, 0);
				$color = imagecolorallocate($img, 255, 255, 255);
				
				if($txt1 && $txt2)	$text = $txt1 . "\r\n" . $txt2; 
				if($txt1 && !$txt2)	$text = $txt1; 
				if(!$txt1 && $txt2)	$text = $txt2; 
				
				$box = imagettfbbox($font_size, 0, $fontName, $text);
				
				$left = $im_w - ($box[2]-$box[0]);
				
				imagettftext($img, $font_size, 0, $left-31, $y+1, $grey,  $fontName, $text);
				imagettftext($img, $font_size, 0, $left-30, $y, $color, $fontName, $text);
				
				if($ext == 'jpg' || $ext == 'jpeg') imagejpeg($img, $new_file, 100 );
				if($ext == 'png') imagepng($img, $new_file );
				if($ext == 'gif') imagegif($img, $new_file );

				imagedestroy( $img );
				
				echo $new_file;
				
			}
		}
	}
		
	
	
	exit;
	
	
	
	
	
	
	
	
	
	