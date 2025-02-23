<?php
namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Response;
use Storage;

trait ImageResize
{
	/**
	 * 1. Cài đặt: intervention/image, intervention/imagecache
	 * 2. Cấu hình: file config/image, thêm: 'thumbnails_route' => 'thumbnails'//để xác định đường dẫn xử lý thumbnails
	 * 3. Khai báo trait ImageResize trong model để sử dụng
	 * 3.1 Các tham số có thể sử dụng để custom
	public static $imageFolder = "";
	public static $imageMaxWidth = 1024;
	public static $imageMaxHeight = 1024;
	public static $imageThumbnailWidth = 520;
	public static $imageThumbnailHeight = 520;
	public static $imageIsSubdomain = true;
	public static $imageAspectRatio = true;//giữ nguyên tỉ lệ ảnh
	 * 4. Lưu ý đối với xử lý ảnh thumbnail sẽ gồm 2 bước:
	 * - Khai báo 1 route cho ảnh thumbnail chung
	 Route::get('/'.config('image.thumbnails_route').'/{width}x{height}/{image_path}', function ($width, $height, $image_path){
		return \App\Traits\ImageResize::setThumbnail($width, $height, $image_path);
	})->where('image_path', '.*');
	 * - Gọi hàm getThumbnail để lấy url thumbnail cho ảnh.
	 */

	private static $imageRootFolder = "/images";
	private static $imageThumbnailFolder = "/images/thumbnails";
	//TODO: các tham số có thể sử dụng để cấu hình ImageResize được ví dụ trong comment Property

	/**
	 * Property: public $imageFolder = "";
	 * Folder lưu trữ riêng của mỗi modal
	 * @return int|mixed
	 */
	private static function getImageFolder(){
		if(isset(self::$imageFolder)) return self::$imageFolder;
		return null;
	}
	/**
	 * Property: public $imageMaxWidth = 1024;
	 * Kích thướt width tối đa
	 * @return int|mixed
	 */
	private static function getImageMaxWidth(){
		if(isset(self::$imageMaxWidth)) return self::$imageMaxWidth;
		return 1024;
	}
	/**
	 * Property: public $imageMaxHeight = 1024;
	 * Kích thướt Height tối đa
	 * @return int|mixed
	 */
	private static function getImageMaxHeight(){
		if(isset(self::$imageMaxHeight)) return self::$imageMaxHeight;
		return 1024;
	}
	/**
	 * Property: public $imageThumbnailWidth = 520;
	 * Kích thướt thumbnail width
	 * @return int|mixed
	 */
	private static function getImageThumbnailWidth(){
		if(isset(self::$imageThumbnailWidth)) return self::$imageThumbnailWidth;
		return 520;
	}
	/**
	 * Property: public $imageThumbnailHeight = 520;
	 * Kích thướt thumbnail height
	 * @return int|mixed
	 */
	private static function getImageThumbnailHeight(){
		if(isset(self::$imageThumbnailHeight)) return self::$imageThumbnailHeight;
		return 520;
	}
	/**
	 * Property: public $imageIsSubdomain = true;
	 * Có xử lý lưu theo subdomain hay không.
	 * @return int|mixed
	 */
	private static function getIsSubdomain(){
		if(isset(self::$imageIsSubdomain)) return self::$imageIsSubdomain;
		return true;
	}
	/**
	 * Property: public $imageAspectRatio = true;
	 * Có xử lý lưu theo subdomain hay không.
	 * @return int|mixed
	 */
	private static function getImageAspectRatio(){
		if(isset(self::$imageAspectRatio)) return self::$imageAspectRatio;
		return true;
	}

	private static function createFolder($folder){
		if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
			\Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
		}
	}
	private static function getFolder(){
		//Khởi tạo folder lưu theo tên table nếu imageFolder rỗng
		$imageFolder = self::getImageFolder();

		//Xử lý folder lưu trữ
		//Domain chính: /images/<table_name>
		//Subdomain" /images/sites/<subdomain>/<table_name>/
		$folder = self::$imageRootFolder.'/'.$imageFolder."/";
		//Tạo folder lưu trữ nếu chưa tồn tại
		self::createFolder($folder);

		return $folder;
	}
	
	/**
	 * @param $file
	 * @param bool $aspectRatio - giữ nguyển tỉ lệ ảnh hay không? mặc định là có
	 *
	 * @return string|null
	 */
	public static function saveImageResize($file, $width = null, $height = null){
		if(empty($file)) return null;
		//Xử lý folder lưu trữ
		$folder = self::getFolder();
		$width = !empty($width) ? $width : self::getImageMaxWidth();
		$height = !empty($height) ? $height : self::getImageMaxHeight();

		//Xử lý tên ảnh
		$imageExt = $file->getClientOriginalExtension();
		$imageName = Str::slug(basename($file->getClientOriginalName(), '.'.$imageExt));
		$imageName .= "_".Carbon::now()->timestamp;
		//resize và Lưu file
		$pathImage = $folder.$imageName. '.' .$imageExt;
		$img = \Image::make($file->getRealPath());
		if(self::getImageAspectRatio()) {//giữ nguyên tỉ lệ
			$img->resize( self::getImageMaxWidth(), self::getImageMaxHeight(), function ( $constraint ) {
				$constraint->aspectRatio();//giữ nguyên tỉ lệ ảnh
				$constraint->upsize();//ngăn phóng to ảnh nếu ảnh nhỏ hơn kích thướt đưa vào
			} );
		}else{
			$img->resize($width, $height);
		}
        $img->save(storage_path('app/public') . $pathImage);

        return config('filesystems.disks.public.path') . $pathImage;
	}

	/**
	 * Return url thumbnail
	 * @param $image_url
	 * @param null $width
	 * @param null $height
	 *
	 * @return string
	 */
	public static function getThumbnail($image_url, $width = null, $height = null){
		$width = $width ? $width : self::getImageThumbnailWidth();
		$height = $height ? $height : self::getImageThumbnailHeight();
		$image_url = Storage::url($image_url);
		return asset(config('image.thumbnails_route')."/".$width."x$height$image_url");
	}

	/**
	 * For route: Route::get('/'.config('image.thumbnails_route').'/{width}x{height}/{image_path}');
	 * Exp: http://domain.com/thumbnails/400x164/storage/images/sites/eagle/banners/duan_1561449206.jpg
	 * Return image thumbnail (process cache)
	 * @param $width
	 * @param $height
	 * @param $image_path
	 *
	 * @return \Illuminate\Http\Response
	 */
	public static function setThumbnail($width, $height, $image_path){
		$img = \Image::cache(function($image) use ($image_path, $width, $height) {
			$image->make(asset($image_path));
			$image->resize( $width, $height );
		}, 30 * 24 * 60);

		return Response::make($img, 200)
		               ->header("Content-Type", 'image')
		               ->setMaxAge(30 * 24 * 60 * 60)//seconds
		               ->setPublic();

	}
	public static function deleteFile($file){
		if (!empty($file) && \Storage::exists($file)){
			\Storage::delete($file);
		}
	}
}