<?php
namespace App\Models;

use Carbon\Carbon;

class ResizeImage
{
    public $image, $imageName, $imageExt, $pathImage, $imageWidth, $imageHeight, $thumbWidth, $thumbHeight;

    /**
     * ResizeImage constructor.
     * @param $image: file ảnh truyền vào
     * @param $pathImage: đường dẫn muốn lưu
     * @param int $imageWidth: chiều rộng resize ảnh
     * @param null $imageHeight: chiều cao resize ảnh
     * @param int $thumbWidth: chiều drộng resize thumbnail
     * @param int $thumbHeight: chiều cao resize thumbnail
     */
    public function __construct($image, $pathImage, $imageWidth = 769, $imageHeight = null, $thumbWidth = 300, $thumbHeight = 200){
        if (empty($image) || empty($pathImage)) return;

        $imageExt = $image->getClientOriginalExtension();
        $this->imageExt = $imageExt; //Đuôi ảnh

        $imageName = str_slug(basename($image->getClientOriginalName(), '.'.$imageExt));
        $this->imageName = $imageName; //Tên ảnh

        $this->image = $image;
        $this->pathImage = $pathImage;
        $this->imageWidth = $imageWidth;
        $this->imageHeight = $imageHeight;
        $this->thumbWidth = $thumbWidth;
        $this->thumbHeight = $thumbHeight;
    }

    /**
     * Resize, lưu ảnh và thumbnail
     *
     * @return string
     */
    public function resizeImageAndThumbnail(){
        $image = $this->image;
        $fileName = $this->checkImage();

        //Save Image
        $folderImage = $this->pathImage;
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folderImage)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folderImage);
        }
        $pathImage = $folderImage.$fileName;
        \Image::make($image->getRealPath())->resize($this->imageWidth, $this->imageHeight)->save(public_path('/storage').$pathImage);

        //Save Thumbnail
        $folder = '/images/cache/';
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }

        $pathThumbnail = $folder . $fileName;
        \Image::make($image->getRealPath())->resize($this->thumbWidth, $this->thumbHeight)->save(public_path('/storage').$pathThumbnail);

        return $pathImage;
    }

    public function saveOnlyThumbnail(){
        $image = $this->image;
        $fileName = $this->checkImage();

        //Save Thumbnail
        $folderImage = $this->pathImage;
        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folderImage)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folderImage);
        }
        $pathImage = $folderImage.$fileName;
        \Image::make($image->getRealPath())->resize($this->imageWidth, $this->imageHeight)->save(public_path('/storage').$pathImage);

        return $pathImage;
    }

    /**
     * Kiểm tra ảnh trong thư mục
     *
     * @return string: return tên ảnh
     */
    public function checkImage(){
        $path = $this->pathImage;
        $imageName = $this->imageName;
        $imageExt = $this->imageExt;
        if (\Storage::disk(config('filesystems.disks.public.visibility'))->exists($path.$imageName.'.'.$imageExt)){
//            $allFiles = \Storage::allFiles(config('filesystems.disks.public.visibility').$path);
            $allFiles = \Storage::allFiles(config('filesystems.disks.public.visibility').'/images');
            if (in_array(config('filesystems.disks.public.visibility').$path.$imageName.'.'.$imageExt, $allFiles)){
                for ($i = 1; $i <= 100; $i++){
                    $newImage = $imageName.'-'.$i;
                    if (!in_array(config('filesystems.disks.public.visibility').$path.$newImage.'.'.$imageExt, $allFiles)) break;
                }
            }
            return $newImage.'.'.$imageExt;
        }else{
            return $imageName.'.'.$imageExt;
        }
    }

    /**
     * Lấy ảnh thumbnail, save thumbnail nếu chưa có ảnh
     *
     * @param $image: đường dẫn image truyền vào
     * @param int $width: độ rộng của ảnh
     * @param int $height: chiều cao của ảnh
     * @return string|void
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function getThumbnail($image, $width = 300, $height = 200){
        if (empty($image)) return;
        $folder = '/images/cache/';
        $imageArr = explode('/', $image);//public/images/name_folder/"name_image"
        $imageName = end($imageArr);
        $pathThumbnail = $folder . $imageName;

        if(!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)){
            \Storage::makeDirectory(config('filesystems.disks.public.visibility').$folder);
        }

        if (\Storage::exists(config('filesystems.disks.public.visibility').$pathThumbnail)){
            return config('filesystems.disks.public.visibility').$pathThumbnail;
        }else{
            //Save Thumbnail
            $imageCurrent = \Storage::get($image);
            \Image::make($imageCurrent)->resize($width, $height)->save(public_path('/storage').$pathThumbnail);
            return config('filesystems.disks.public.visibility').$pathThumbnail;
        }
    }

    /**
     * Xóa ảnh và thumbnail
     *
     * @param $oldFile: đường dẫn file cũ
     */
    public static function deleteOldImage($oldFile){
        if (empty($oldFile)) return;
        //Xóa image
        if (\Storage::exists($oldFile)){
            \Storage::delete($oldFile);
        }

        //Xóa thumbnail
        $folder = 'public/images/cache/';
        $imageArr = explode('/', $oldFile);
        $imageName = $imageArr[3];
        $pathThumbnail = $folder . $imageName;
        if (\Storage::exists($pathThumbnail)){
            \Storage::delete($pathThumbnail);
        }
    }
}