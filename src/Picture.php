<?php

namespace Lexinzector\Picture;

class Picture
{
	private $image_file;
	
	public $image;
	public $image_type;
	public $image_width;
	public $image_height;
	public $image_contents;
	
	public function __construct(string $image_file)
	{
		if(!$image_file)
		{
			throw new Exception("Image URL or Path to image file is required");
		}
		$this->image_file = $image_file;
		$image_info = getimagesize($this->image_file);
		$this->image_width = $image_info[0];
		$this->image_height = $image_info[1];
		$this->image_contents = '';
		switch($image_info[2])
		{
			case 1: $this->image_type = 'gif'; break;//1: IMAGETYPE_GIF
			case 2: $this->image_type = 'jpeg'; break;//2: IMAGETYPE_JPEG
			case 3: $this->image_type = 'png'; break;//3: IMAGETYPE_PNG
			case 4: $this->image_type = 'swf'; break;//4: IMAGETYPE_SWF
			case 5: $this->image_type = 'psd'; break;//5: IMAGETYPE_PSD
			case 6: $this->image_type = 'bmp'; break;//6: IMAGETYPE_BMP
			case 7: $this->image_type = 'tiffi'; break;//7: IMAGETYPE_TIFF_II (������� ���� intel)
			case 8: $this->image_type = 'tiffm'; break;//8: IMAGETYPE_TIFF_MM (������� ���� motorola)
			case 9: $this->image_type = 'jpc'; break;//9: IMAGETYPE_JPC
			case 10: $this->image_type = 'jp2'; break;//10: IMAGETYPE_JP2
			case 11: $this->image_type = 'jpx'; break;//11: IMAGETYPE_JPX
			case 12: $this->image_type = 'jb2'; break;//12: IMAGETYPE_JB2
			case 13: $this->image_type = 'swc'; break;//13: IMAGETYPE_SWC
			case 14: $this->image_type = 'iff'; break;//14: IMAGETYPE_IFF
			case 15: $this->image_type = 'wbmp'; break;//15: IMAGETYPE_WBMP
			case 16: $this->image_type = 'xbm'; break;//16: IMAGETYPE_XBM
			case 17: $this->image_type = 'ico'; break;//17: IMAGETYPE_ICO
			default: $this->image_type = ''; break;
		}
		$this->fotoimage();
	}
	
	private function fotoimage()
	{
		switch($this->image_type)
		{
			case 'gif': $this->image = imagecreatefromgif($this->image_file); break;
			case 'jpeg': $this->image = imagecreatefromjpeg($this->image_file); break;
			case 'png': $this->image = imagecreatefrompng($this->image_file); break;
		}
	}
	
	public function autoimageresize($new_w, $new_h)
	{
		$difference_w = 0;
		$difference_h = 0;
		if($this->image_width < $new_w and $this->image_height < $new_h)
		{
			$this->imageresize($this->image_width, $this->image_height);
		}
		else
		{
			if($this->image_width > $new_w)
			{
				$difference_w = $this->image_width - $new_w;
			}
			if($this->image_height > $new_h)
			{
				$difference_h = $this->image_height - $new_h;
			}
			if($difference_w > $difference_h)
			{
				$this->imageresizewidth($new_w);
			}
			elseif($difference_w < $difference_h)
			{
				$this->imageresizeheight($new_h);
			}
			else
			{
				$this->imageresize($new_w, $new_h);
			}
		}
	}
	
	public function percentimagereduce($percent)
	{
		$new_w = $this->image_width * $percent / 100;
		$new_h = $this->image_height * $percent / 100;
		$this->imageresize($new_w, $new_h);
	}
	
	public function imageresizewidth($new_w)
	{
		$new_h = $this->image_height * ($new_w / $this->image_width);
		$this->imageresize($new_w, $new_h);
	}
	
	public function imageresizeheight($new_h)
	{
		$new_w = $this->image_width * ($new_h / $this->image_height);
		$this->imageresize($new_w, $new_h);
	}
	
	public function imageresize($new_w, $new_h)
	{
		$new_image = imagecreatetruecolor($new_w, $new_h);
		if($this->image_type == 'png')
		{
			imagealphablending($new_image, false);
			imagesavealpha($new_image, true);
		}
		if($this->image_type == 'gif')
		{
			$transparent_source_index = imagecolortransparent($this->image);
			// ��������� ������� ������������
			if($transparent_source_index !== -1)
			{
				$transparent_color = imagecolorsforindex($this->image, $transparent_source_index);
				// ��������� ���� � ������� ������ �����������, � ������������� ��� ��� ����������
				$transparent_destination_index = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				imagecolortransparent($new_image, $transparent_destination_index);
				
				// �� ������ ������ �������� ��� ���� ������
				imagefill($new_image, 0, 0, $transparent_destination_index);
			}
		}
		imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $new_w, $new_h, $this->image_width, $this->image_height);
		$this->image_width = $new_w;
		$this->image_height = $new_h;
		$this->image = $new_image;
	}
	
	public function imagesave($image_type = 'jpeg', $image_file = null, $image_compress = 100, $image_permiss = '')
	{
		if($image_file == null)
		{
			switch($this->image_type)
			{
				case 'gif': header('Content-type: image/gif'); break;
				case 'jpeg': header('Content-type: image/jpeg'); break;
				case 'png': header('Content-type: image/png'); break;
			}
		}
		if($image_file=='out') ob_start();
		switch($this->image_type)
		{
			case 'gif': imagegif($this->image, $image_file != 'out' ? $image_file : null); break;
			case 'jpeg': imagejpeg($this->image, $image_file != 'out' ? $image_file : null, $image_compress); break;
			case 'png': imagepng($this->image, $image_file != 'out' ? $image_file : null); break;
		}
		if($image_file=='out')
		{
			$this->image_contents = ob_get_contents();
			ob_end_clean();
		}
		if($image_permiss != '')
		{
			chmod($image_file, $image_permiss);
		}
	}
	
	public function imageout()
	{
		imagedestroy($this->image);
	}
	
	public function __destruct(){}
}
