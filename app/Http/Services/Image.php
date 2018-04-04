<?php

namespace App\Http\Services;

class Image {
	
	private $imgUrl;
	
	private $imgInfo;
	
	private $imgRatioinfo;
	
	private $cachePath = './';
	
	public function __construct ($cachePath = '') {
		if (!empty($cachePath) && is_dir($cachePath)) {
			$this->cachePath = $cachePath;
		}
		
	}
	
	//生成纯色颜色背景
	public function createPureColorSource ($W, $H, $R, $G, $B, $alpha=0){
		
		$tmpImg = imagecreatetruecolor($W, $H);
		
		if ($alpha == 0) {
			$color = imagecolorallocate($tmpImg, $R, $G, $B);
		} else {
			$color = imagecolorallocatealpha($tmpImg, $R, $G, $B, $alpha);
			
			//处理透明色
			imagecolortransparent($tmpImg, $color);
			imagealphablending($tmpImg,false);
			imagesavealpha($tmpImg,true);
		}
		
		
		
		imagefill($tmpImg, 0, 0, $color);
		
		return $tmpImg;
	}
	
	//计算字符宽度
	private function countStrLen($str, $fontsize, $encode = 'UTF8'){
		$strlen                 = mb_strlen($str, $encode);  //字符串长度
		$strwidth            = 0;  //存储所有字符串的总宽度
		for($i = 0; $i < $strlen; $i++){
		  $strwidth += $fontsize;
		}
		return $strwidth;
	}
	
	//获得文字标题居中位置
	public function getTitleCenterSite($im, $strwidth){
	  $imgwidth = imagesx($im);
	  return (($imgwidth / 2) - ($strwidth / 2) + 15);
	}
	
	//图片验证码
	public function createVerifyImg ($width, $height, $code, $configs=[]){
		$tmpImg = @imagecreatetruecolor ($width, $height);
		
		/* 图片颜色 */
		if (!isset($configs['color'])) {
			$configs['color'] = [255,255,255];
		}
		
		/* 字体颜色 */
		if (!isset($configs['fcolor'])) {
			$configs['fcolor'] = [0,0,0];
		}
		
		/* 画干扰线 */
		if (!isset($configs['line'])) {
		  $configs['line'] = 10;
		}
		
		/* 字号 */
		if (!isset($configs['size'])) {
		  $configs['size'] = 16;
		}
		
		/* 字体 */
		if (!isset($configs['font'])) {
		  $configs['font'] = './GBK.ttf';
		}
		
		/* 字体倾斜度 */
		if (!isset($configs['rotate'])) {
		  $configs['rotate'] = 0;
		}
		
		/* 位置 */
		if (!isset($configs['xy'])) {
			$configs['xy'] = [false, ($height/2+$configs['size']/2)];
		}

		$color = imagecolorallocate($tmpImg, $configs['color'][0], $configs['color'][1], $configs['color'][2]);
		imagefill($tmpImg,0,0,$color);
		
		$rgbs = $this->randomColorRGB($configs['line']);
		$rgb_len = count($rgbs);
		
		for ($i = 0; $i < $rgb_len; $i++) {
			$line_color = $this->createColorResource($rgbs[$i][0], $rgbs[$i][1], $rgbs[$i][2]);
			$xy = $this->createLineRange($width, $height);
			imageline($tmpImg, $xy[0], $xy[1], $xy[2], $xy[3], $line_color);
		}
		
		$tmpImg = $this->createTextImg($tmpImg, $width, $height, [[
			'text'   => $code,
			'size'   => $configs['size'],
			'xy'     => $configs['xy'],
			'font'   => $configs['font'],
			'color'  => $configs['fcolor'],
			'rotate' => $configs['rotate']
		]], true);
	  
		return imagejpeg($tmpImg);
	}
	
	/* 生成范围线条 */
	private function createLineRange ($width, $height) {
		
		$sx = rand(1, $width);
		$sy = rand(1, $height);
		
		$ex = 0;
		$ey = 0;
		
		$lengthx = rand(1, 15);
		$lengthy = rand(1, 15);
		
		/* 正负 */
		$pn = rand(0,1);
		
		/* 正 */
		if ($pn === 1) {
			$ex = $sx+$lengthx;
			if ($ex > $width) {
				$ex = $sx - $lengthx;
			}
			
			$ey = $sy+$lengthy;
			if ($ey > $height) {
				$ey = $sy - $lengthy;
			}
		} else { //负
			$ex = $sx-$lengthx;
			if (($sx-$lengthx) < 0) {
				$ex = $sx + $lengthx;
			}
			
			$ey = $sy-$lengthy;
			if ($ey < 0) {
				$ey = $sy + $lengthy;
			}
		}
		
		return [$sx, $sy, $ex, $ey];
		
	}
	
	/* 随机生成RGB颜色 */
	private function randomColorRGB ($count = 1) {
		$rgb = [];
		for ($i = 0; $i < $count; $i++) {
			$tmp_rgb = [];
			for ($j = 0; $j < 3; $j++) {
				$tmp_rgb[] = rand(0, 255);
			}
			$rgb[] = $tmp_rgb;
		}
		return $rgb;
	}
	
	//生成颜色资源并返回
	private function createColorResource($R, $G, $B){
	  $tmpImg = @imagecreatetruecolor (1, 1) or die ("生成图片资源失败");
	  $color  = imagecolorallocate($tmpImg, $R, $G, $B);
	  return $color;
	}
	
	/* 获取bmp图片资源 */
	private function imagecreatefrombmp($filename) {
		$tmp_name = tempnam("/tmp", "GD");
		if($this->ConvertBMP2GD($filename, $tmp_name)) {
			$img = imagecreatefromgd($tmp_name);
			unlink($tmp_name);
			return $img;
		}
		return false;
	}
	
	/* 转换BMP为GD类型 */
	private function ConvertBMP2GD ($src, $dest = false) {
		if(!($src_f = fopen($src, "rb"))) {
			return false;
		}
		if(!($dest_f = fopen($dest, "wb"))) {
			return false;
		}
		$header = unpack("vtype/Vsize/v2reserved/Voffset", fread($src_f,14));
		$info = unpack("Vsize/Vwidth/Vheight/vplanes/vbits/Vcompression/Vimagesize/Vxres/Vyr es/Vncolor/Vimportant",
		fread($src_f, 40));

		extract($info);
		extract($header);

		if($type != 0x4D42) { // signature "BM"
			return false;
		}
		
		$palette_size = $offset - 54;
		$ncolor = $palette_size / 4;
		$gd_header = "";
		// true-color vs. palette
		$gd_header .= ($palette_size == 0) ? "\xFF\xFE" : "\xFF\xFF";
		$gd_header .= pack("n2", $width, $height);
		$gd_header .= ($palette_size == 0) ? "\x01" : "\x00";
		if($palette_size) {
			$gd_header .= pack("n", $ncolor);
		}
		// no transparency
		$gd_header .= "\xFF\xFF\xFF\xFF";

		fwrite($dest_f, $gd_header);

		if($palette_size) {
			$palette = fread($src_f, $palette_size);
			$gd_palette = "";
			$j = 0;
			while($j < $palette_size) {
				$b = $palette{$j++};
				$g = $palette{$j++};
				$r = $palette{$j++};
				$a = $palette{$j++};
				$gd_palette .= "$r$g$b$a";
			}
			$gd_palette .= str_repeat("\x00\x00\x00\x00", 256 - $ncolor);
			fwrite($dest_f, $gd_palette);
		}

		$scan_line_size = (($bits * $width) + 7) >> 3;
		$scan_line_align = ($scan_line_size & 0x03) ? 4 - ($scan_line_size & 0x03) : 0;

		for($i = 0, $l = $height - 1; $i < $height; $i++, $l--) {
			// BMP stores scan lines starting from bottom
			fseek($src_f, $offset + (($scan_line_size + $scan_line_align) * $l));
			$scan_line = fread($src_f, $scan_line_size);
			if($bits == 24) {
				$gd_scan_line = "";
				$j = 0;
				while($j < $scan_line_size) {
					$b = $scan_line{$j++};
					$g = $scan_line{$j++};
					$r = $scan_line{$j++};
					$gd_scan_line .= "\x00$r$g$b";
				}
			} else if($bits == 8) {
				$gd_scan_line = $scan_line;
			} else if($bits == 4) {
				$gd_scan_line = "";
				$j = 0;
				while($j < $scan_line_size) {
					$byte = ord($scan_line{$j++});
					$p1 = chr($byte >> 4);
					$p2 = chr($byte & 0x0F);
					$gd_scan_line .= "$p1$p2";
				} 
				$gd_scan_line = substr($gd_scan_line, 0, $width);
			} else if($bits == 1) {
				$gd_scan_line = "";
				$j = 0;
				while($j < $scan_line_size) {
					$byte = ord($scan_line{$j++});
					$p1 = chr((int) (($byte & 0x80) != 0));
					$p2 = chr((int) (($byte & 0x40) != 0));
					$p3 = chr((int) (($byte & 0x20) != 0));
					$p4 = chr((int) (($byte & 0x10) != 0));
					$p5 = chr((int) (($byte & 0x08) != 0));
					$p6 = chr((int) (($byte & 0x04) != 0));
					$p7 = chr((int) (($byte & 0x02) != 0));
					$p8 = chr((int) (($byte & 0x01) != 0));
					$gd_scan_line .= "$p1$p2$p3$p4$p5$p6$p7$p8";
				} 
				$gd_scan_line = substr($gd_scan_line, 0, $width);
			}

			fwrite($dest_f, $gd_scan_line);
		}
		
		fclose($src_f);
		fclose($dest_f);
		
		return true;
	}
	
	/* 获取图片资源 */
	public function getImageSource ($imgUrl, &$type=null) {
		
		if (empty($imgUrl) || (strpos($imgUrl, 'http://') == -1 || strpos($imgUrl, 'https://') == -1) && !file_exists($imgUrl)) {
			return null;
		} else if (strpos($imgUrl, 'http://') > -1 || strpos($imgUrl, 'https://') > -1) {
			
			$tmp_imgUrl = $this->cachePath.'/'.MD5($imgUrl);
			
			$http_content = '';
			
			if (!file_exists($tmp_imgUrl)) {
				$http_content = @file_get_contents($imgUrl);
				if (empty($http_content)) {
					$http_content = '';
					exit;
				}
			} else {
				$http_content = @file_get_contents($tmp_imgUrl);
			}
			
			$imgUrl = $tmp_imgUrl;
			
			file_put_contents($imgUrl, $http_content);
			
		}
		
		$this->imgUrl = $imgUrl;
		
		$this->imgInfo = $imgtype = @getimagesize($imgUrl);
		
		$this->imgRatioinfo = [];
		
		$im = null;
		
		switch ($imgtype['mime']) {
			case 'image/png' :
				$im = imagecreatefrompng($imgUrl);
				$type = 'png';
				break;
			case 'image/jpeg' :
				$im = imagecreatefromjpeg($imgUrl);
				$type = 'jpg';
				break;
			case 'image/gif' :
				$im = imagecreatefromgif($imgUrl);
				$type = 'gif';
				break;
			case 'image/x-ms-bmp' :
				$im = $this->imagecreatefrombmp($imgUrl);
				$type = 'bmp';
				break;
		}
		
		return $im;
		
	}
	
	//创建文字图片
	public function createTextImg ($img, $width, $height, $texts, $single=false) {
		
		if (empty($img) || empty($texts) || !is_array($texts)) {
			return false;
		}
		
		foreach($texts as $text){
			
			if ($text['xy'][0] === false) {
				$text['xy'][0] = $this->getTitleCenterSite($img, $this->countStrLen($text['text'], $text['size']));
			}
			
			if (isset($text['xy_offset'])) {
				$text['xy'][0] = $text['xy'][0] + $text['xy_offset'][0];
				$text['xy'][1] = $text['xy'][1] + $text['xy_offset'][1];
			}
			
			$text['rotate'] = isset($text['rotate'])?$text['rotate']:0;
			
			if ($single === true) {
				$text_len = strlen(str_replace(' ', '', $text['text']));
				for ($i = 0; $i < $text_len; $i++) {
					imagettftext($img, $text['size'], rand(-$text['rotate'], abs($text['rotate'])), $text['xy'][0]+($i*$text['size']-($text['size']/2)), $text['xy'][1], $this->createColorResource($text['color'][0], $text['color'][1], $text['color'][2]), $text['font'], mb_substr($text['text'], $i, 1, 'UTF-8'));
				}
			} else {
				imagettftext($img, $text['size'], rand(-$text['rotate'], abs($text['rotate'])), $text['xy'][0], $text['xy'][1], $this->createColorResource($text['color'][0], $text['color'][1], $text['color'][2]), $text['font'], mb_convert_encoding($text['text'], 'UTF-8'));
			}
			
		}
		
		return $img;
	}
	
	/* 裁剪图片 */
	public function cutImage ($img, $configs, &$imgInfo = []) {
		
		$x = $y = 0;
		
		$cutWidth = $cutHeight = 0;
		
		if (!empty($configs)) {
			
			/* 自动截取 */
			if ($configs['autoWay'] !== false) {
				
				/* 默认取值比例 50% */
				if (!isset($configs['autoScale'])) {
					$configs['autoScale'] = 50;
				}
				
				/* 超出范围校正 */
				if ($configs['autoScale'] > 100) {
					$configs['autoScale'] = 100;
				} else if ($configs['autoScale'] < 1) {
					$configs['autoScale'] = 1;
				}
				
				$srcWidth  = isset($this->imgRatioinfo[0])?$this->imgRatioinfo[0]:$this->imgInfo[0];
				$srcHeight = isset($this->imgRatioinfo[1])?$this->imgRatioinfo[1]:$this->imgInfo[1];
				
				/* 上下左右取中 */
				if ($configs['autoWay'] == 'all') {
					
					$cutWidth  = floor($srcWidth * ($configs['autoScale'] / 100));
					$cutX = floor(($srcWidth - $cutWidth) / 2);
					
					$cutHeight  = floor($srcHeight * ($configs['autoScale'] / 100));
					$cutY = floor(($srcHeight - $cutHeight) / 2);
					
				} else if ($configs['autoWay'] == 'center') { /* 左右取中 */
					
					$cutWidth  = floor(($srcWidth / 2) * ($configs['autoScale'] / 100));
					$cutX = floor(($srcWidth - $cutWidth) / 2);
					
					$cutHeight  = $srcHeight;
					$cutY = 0;
					
				} else if ($configs['autoWay'] == 'left') { /* 取左 */
					
					$cutWidth  = floor(($srcWidth / 2) * ($configs['autoScale'] / 100));
					$cutX = 0;
					
					$cutHeight = $srcHeight;
					$cutY = 0;
					
				} else if ($configs['autoWay'] == 'right') { /* 取右 */
					
					$cutWidth  = floor(($srcWidth / 2) * ($configs['autoScale'] / 100));
					$cutX = $srcWidth - $cutWidth;
					
					$cutHeight = $srcHeight;
					$cutY = 0;
					
				} else if ($configs['autoWay'] == 'top') { /* 取上 */
					
					$cutWidth  = $srcWidth;
					$cutX = 0;
					
					$cutHeight  = floor(($srcHeight / 2) * ($configs['autoScale'] / 100));
					$cutY = 0;
					
				} else if ($configs['autoWay'] == 'middle') { /* 上下取中 */
					
					$cutWidth  = $srcWidth;
					$cutX = 0;
					
					$cutHeight  = floor(($srcHeight / 2) * ($configs['autoScale'] / 100));
					$cutY = floor(($srcHeight - $cutHeight) / 2);
					
				} else if ($configs['autoWay'] == 'bottom') { /* 取下 */
					
					$cutWidth  = $srcWidth;
					$cutX = 0;
					
					$cutHeight  = floor(($srcHeight / 2) * ($configs['autoScale'] / 100));
					$cutY = floor($srcHeight - $cutHeight);
					
				} else if ($configs['autoWay'] == 'lefttop') { /* 取左上 */
					
					$cutWidth  = floor(($srcWidth / 2) * ($configs['autoScale'] / 100));
					$cutX = 0;
					
					$cutHeight  = floor(($srcHeight / 2) * ($configs['autoScale'] / 100));
					$cutY = 0;
					
				} else if ($configs['autoWay'] == 'righttop') { /* 取右上 */
					
					$cutWidth  = floor(($srcWidth / 2) * ($configs['autoScale'] / 100));
					$cutX = $srcWidth - $cutWidth;
					
					$cutHeight  = floor(($srcHeight / 2) * ($configs['autoScale'] / 100));
					$cutY = 0;
					
				} else if ($configs['autoWay'] == 'leftbottom') { /* 取左下 */
					
					$cutWidth  = floor(($srcWidth / 2) * ($configs['autoScale'] / 100));
					$cutX = 0;
					
					$cutHeight  = floor(($srcHeight / 2) * ($configs['autoScale'] / 100));
					$cutY = $srcHeight - $cutHeight;
					
				} else if ($configs['autoWay'] == 'rightbottom') { /* 取右下 */
					
					$cutWidth  = floor(($srcWidth / 2) * ($configs['autoScale'] / 100));
					$cutX = $srcWidth - $cutWidth;
					
					$cutHeight  = floor(($srcHeight / 2) * ($configs['autoScale'] / 100));
					$cutY = $srcHeight - $cutHeight;
					
				} else if ($configs['autoWay'] == 'custom') { /* 自定义高宽 */
					
					$cutWidth  = floor($configs['w'] * ($configs['autoScale'] / 100));
					$cutX = floor(($srcWidth / 2) - ($cutWidth / 2));
					
					$cutHeight  = floor($configs['h'] * ($configs['autoScale'] / 100));
					$cutY = floor(($srcHeight / 2) - ($cutHeight / 2));
					
				}
				
				/* 配置偏移量 */
				if (isset($configs['autoOffset']) && is_array($configs['autoOffset'])) {
					$cutX = $cutX + $configs['autoOffset'][0];
					$cutY = $cutY + $configs['autoOffset'][1];
				}
				
			} else { 
				
				//随意截取图片
				
				$cutWidth  = $configs['w'];
				$cutHeight = $configs['h'];
				
				$cutX = $configs['x'];
				$cutY = $configs['y'];

			}
			
			$tmpImg = @imagecreatetruecolor ($cutWidth, $cutHeight) or die ("生成图片资源失败");
			
			$imgInfo = [
				'width' => $cutWidth,
				'height' => $cutHeight
			];
			
		}
		
		imagecopy ($tmpImg, $img , 0, 0, $cutX, $cutY, $cutWidth, $cutHeight);
		
		return $tmpImg;
	}
	
	/* 等比例缩放 zoom ，true 放大 false 缩小*/
	public function ratioZoom ($img, $percent, $zoom) {
		
		$imgtype = @getimagesize($this->imgUrl);
		
		if ($zoom) {
			
			$realWidth  = is_array($percent) && isset($percent[0])?$percent[0]:$imgtype[0] + ($imgtype[0] * ($percent / 100));
			$realHeight = is_array($percent) && isset($percent[1])?$percent[1]:$imgtype[1] + ($imgtype[1] * ($percent / 100));
			
		} else {
			
			$realWidth  = is_array($percent) && isset($percent[0])?$percent[0]:$imgtype[0] - ($imgtype[0] * ($percent / 100));
			$realHeight = is_array($percent) && isset($percent[1])?$percent[1]:$imgtype[1] - ($imgtype[1] * ($percent / 100));
			
		}
		
		$tmpImg = @imagecreatetruecolor ($realWidth, $realHeight) or die ("生成图片资源失败");
		imagealphablending($tmpImg,false);
		imagesavealpha($tmpImg,true);
		
		if (imagecopyresized($tmpImg, $img , 0, 0, 0, 0, $realWidth, $realHeight, $imgtype[0], $imgtype[1]) === false) {
			return false;
		}
		
		$this->imgRatioinfo = [
			$realWidth, $realHeight
		];
		
		return $tmpImg;
	}
	
	/* 获得缩放后的数据 */
	public function getRatioZoom () {
		return $this->imgRatioinfo?$this->imgRatioinfo:[];
	}
	
	//添加水印 $backImg 底图 $srcImg 水印图 $align 位置('center'、'left'、'leftbottom'、'lefttop')或者数组 array(x, y);
	public function addWatermark($backImg, $srcImg, $align){

		if(empty($backImg) || empty($srcImg) || empty($align)){
		  return;
		}

		if(is_array($align)){
			$src = [imagesx($srcImg), imagesy($srcImg)];
			imagecopy ($backImg, $srcImg , $align[0], $align[1], 0, 0, $src[0], $src[1]);
		}else{
			$leftspace = 5;  //左间距
			$topspace  = 5;  //右间距
			$in[] = imagesx($backImg);
			$in[] = imagesy($backImg);
			$src[] = imagesx($srcImg);
			$src[] = imagesy($srcImg);
			
			$site = [];
			if(strtolower($align) == 'left'){  //左
				$site['x'] = $leftspace;
				$site['y'] = (($in[1] / 2) - ($src[1] / 2));
			}else if(strtolower($align) == 'right'){  //右
				$site['x'] = ($in[0] - $src[0]) - $leftspace;
				$site['y'] = (($in[1] / 2) - ($src[1] / 2));
			}else if(strtolower($align) == 'center'){  //中
				$site['x'] = ($in[0] / 2) - ($src[0] / 2);
				$site['y'] = (($in[1] / 2) - ($src[1] / 2));
			}else if(strtolower($align) == 'top'){  //中上
				$site['x'] = ($in[0] / 2) - ($src[0] / 2);
				$site['y'] = $topspace;
			}else if(strtolower($align) == 'bottom'){  //中下
				$site['x'] = ($in[0] / 2) - ($src[0] / 2);
				$site['y'] = $in[1] - $src[1] - $topspace;
			}else if(strtolower($align) == 'lefttop'){  //左上
				$site['x'] = $leftspace;
				$site['y'] = $topspace;
			}else if(strtolower($align) == 'leftbottom'){  //左下
				$site['x'] = $leftspace;
				$site['y'] = $in[1] - $src[1] - $topspace;
			}else if(strtolower($align) == 'righttop'){  //右上
				$site['x'] = ($in[0] - $src[0]) - $leftspace;
				$site['y'] = $topspace;
			}else if(strtolower($align) == 'rightbottom'){  //右下
				$site['x'] = ($in[0] - $src[0]) - $leftspace;
				$site['y'] = $in[1] - $src[1] - $topspace;
			}
			imagecopy ($backImg, $srcImg , $site['x'], $site['y'], 0, 0, $src[0], $src[1]);
		}
										
		return $backImg;
	}	
	
}