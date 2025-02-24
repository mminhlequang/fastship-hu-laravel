<?php

namespace App\Models;

use App\Events\SendNotificationEvent;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Kyslik\ColumnSortable\Sortable;

class Notification extends Model
{

    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * The database primary key value.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    public $sortable = [
        'title',
        'updated_at'
    ];

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'image', 'description', 'content', 'type', 'user_id', 'read_at', 'order_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\Customer', 'user_id');
    }

    static public function uploadAndResize($image, $width = 100, $height = null)
    {
        if (empty($image)) return;
        $folder = "/images/notifications/";
        if (!\Storage::disk(config('filesystems.disks.public.visibility'))->has($folder)) {
            \Storage::makeDirectory(config('filesystems.disks.public.visibility') . $folder);
        }
        //getting timestamp
        $timestamp = Carbon::now()->toDateTimeString();
        $fileExt = $image->getClientOriginalExtension();
        $filename = str_slug(basename($image->getClientOriginalName(), '.' . $fileExt));
        $pathImage = str_replace([' ', ':'], '-', $folder . $timestamp . '-' . $filename . '.' . $fileExt);

        $img = \Image::make($image->getRealPath())->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
        });

        $img->save(storage_path('app/public') . $pathImage);

        return config('filesystems.disks.public.path') . $pathImage;
    }

    public static function insertNotificationByUser($title, $description, $image = "", $type, $userId, $orderId = null)
    {
        $now = Carbon::now();
        $data = self::create([
            'title' => $title,
            'description' => $description,
            'image' => !empty($image) ? $image : url('images/icon_gift.jpg'),
            'user_id' => $userId,
            'order_id' => $orderId ?? "",
            'type' => $type,
            'created_at' => $now
        ]);

        event(new SendNotificationEvent($title, $description, $image = "", $type, $userId, $data->id));
    }



    public static function insertNotificationAll($requestData)
    {
        $now = Carbon::now();
        $userIds = [];
        if (isset($requestData['user_id']) && !empty($requestData['user_id']))
            $userIds = explode(",", $requestData['user_id']);
        if ($requestData['type'] == 1) {
            $users = \DB::table('customers')->whereNotNull('device_token')->whereIn('id', $userIds);
            $users->select(['id', 'device_token'])->orderByDesc('created_at')->chunk(50, function ($users) use ($requestData, $now) {
                $title = $requestData['title'] ?? "Tiêu đề";
                $description = $requestData['description'] ?? "Mô tả";
                $content = isset($requestData['content']) ? $requestData['content'] : "Nội dung";
                $image = !empty($requestData['image']) ? $requestData['image'] : url('images/icon_gift.jpg');
                $type = 1;
                $usersIds = [];
                foreach ($users as $item) {
                    $usersIds[] = $item->id;
//                    event(new SendNotificationEvent($title, $description, $image, $type, $item->id));
                }
                $data = [
                    'title' => $title,
                    'description' => $description,
                    'image' => $image,
                    'user_id' => implode(",", $usersIds),
                    'content' => $content,
                    'type' => $type,
                    'created_at' => $now

                ];
                \DB::table('notifications')->insert($data);
            });
        } else {
            $users = \DB::table('customers')->whereNotNull('device_token');
            $title = $requestData['title'] ?? "Tiêu đề";
            $description = $requestData['description'] ?? "Mô tả";
            $content = isset($requestData['content']) ? $requestData['content'] : "Nội dung";
            $image = !empty($requestData['image']) ? $requestData['image'] : url('images/icon_gift.jpg');
            $type = 1;
//            event(new SendNotificationEvent($title, $description, $image, $type));
            $users->select(['id', 'device_token'])->orderByDesc('created_at')->chunk(50, function ($users) use ($title, $description, $content, $image, $type, $now) {
                $usersIds = [];
                foreach ($users as $item) {
                    $usersIds[] = $item->id;
//                    event(new SendNotificationEvent($title, $description, $image, $type, $item->id));
                }
                $data = [
                    'title' => $title,
                    'description' => $description,
                    'image' => $image,
                    'user_id' => implode(",", $usersIds),
                    'content' => $content,
                    'type' => $type,
                    'created_at' => $now
                ];
                \DB::table('notifications')->insert($data);
            });
        }

    }



}
