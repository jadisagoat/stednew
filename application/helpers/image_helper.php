<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Performance: return the URL of a light version of an uploaded image.
 *
 * Uploaded pictures are often 1500-5000 px / 2-3 MB but are shown small.
 * The first time an image is requested, a resized WebP copy is created in
 * uploads/<folder>/optimized_<width>/ (the original file is never changed).
 * If anything is missing (file, GD, WebP support, write permission) the
 * original image URL is returned, so the page always works.
 *
 * Usage in a view:  <img src="<?php echo optimized_image_url('course', $row['image']); ?>">
 */
if (!function_exists('optimized_image_url')) {
	function optimized_image_url($folder, $file, $max_width = 600)
	{
		$src = 'uploads/' . $folder . '/' . $file;
		$original_url = base_url() . $src;

		if ($file == '' || !is_file($src) || !function_exists('imagewebp'))
			return $original_url;

		$dir = 'uploads/' . $folder . '/optimized_' . (int) $max_width;
		$dst = $dir . '/' . basename($file) . '.webp'; // keep the extension in the name (a.png / a.jpg never collide)

		// already optimized and up to date
		if (is_file($dst) && filemtime($dst) >= filemtime($src))
			return base_url() . $dst;
		// already tried: the original is the lightest version
		if (is_file($dst . '.skip') && filemtime($dst . '.skip') >= filemtime($src))
			return $original_url;

		// create at most a few copies per page view, so no visitor waits long (the rest are created on the next views)
		static $generated = 0;
		if ($generated >= 4)
			return $original_url;

		$info = @getimagesize($src);
		if (!$info)
			return $original_url;
		list($width, $height, $type) = $info;

		// small enough already
		if ($width <= $max_width && filesize($src) <= 150 * 1024)
			return $original_url;

		switch ($type) {
			case IMAGETYPE_JPEG:
				$image = @imagecreatefromjpeg($src);
				break;
			case IMAGETYPE_PNG:
				$image = @imagecreatefrompng($src);
				break;
			case IMAGETYPE_GIF:
				return $original_url; // keep animations
			default:
				$image = function_exists('imagecreatefromwebp') && $type == IMAGETYPE_WEBP ? @imagecreatefromwebp($src) : false;
		}
		if (!$image)
			return $original_url;
		$generated++;

		$new_width = min($width, (int) $max_width);
		$new_height = max(1, (int) round($height * $new_width / $width));
		$resized = imagecreatetruecolor($new_width, $new_height);
		imagealphablending($resized, false);
		imagesavealpha($resized, true);
		imagefill($resized, 0, 0, imagecolorallocatealpha($resized, 0, 0, 0, 127));
		imagecopyresampled($resized, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		imagedestroy($image);

		if (!is_dir($dir))
			@mkdir($dir, 0755, true);

		// write to a temp file first so a visitor never receives a half-written image
		$tmp = $dst . '.' . uniqid() . '.tmp';
		$ok = is_dir($dir) && @imagewebp($resized, $tmp, 80);
		imagedestroy($resized);

		if ($ok && filesize($tmp) > 0 && filesize($tmp) < filesize($src) && @rename($tmp, $dst))
			return base_url() . $dst;

		@unlink($tmp);
		if ($ok)
			@touch($dst . '.skip');
		return $original_url;
	}
}

/**
 * Course picture, or the default STED picture (img/default-course.jpg)
 * when the course has no image or the file is missing.
 *
 * Usage in a view:  <img src="<?php echo course_image_url($row['image']); ?>">
 */
if (!function_exists('course_image_url')) {
	function course_image_url($file, $max_width = 600)
	{
		if ($file == '' || !is_file('uploads/course/' . $file))
			return base_url() . 'img/default-course.jpg';
		return optimized_image_url('course', $file, $max_width);
	}
}
