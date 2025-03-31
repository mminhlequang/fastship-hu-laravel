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
    protected $fillable = ['title', 'image', 'description', 'content', 'type', 'user_id', 'read_at', 'order_id', 'is_all'];

    // Chuyển cột operating_hours thành mảng khi truy vấn
    protected $casts = [
        'type' => 'string',
        'order_id' => 'integer',
    ];

    public static $TYPE = [
        "" => "--Type--",
        "system" => "System",
        "news" => "News",
        "promotion" => "Promotion",
        "order" => "Order",
        "transaction" => "WalletTransaction",
    ];

    public static $IS_ALL = [
        "" => "--Choose user--",
        "1" => "All",
        "0" => "Choose list user",
    ];

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
        $lastId = \DB::table('notifications')->insertGetId([
            'title' => $title,
            'description' => $description,
            'image' => !empty($image) ? $image : 'images/no-image.png',
            'user_id' => $userId,
            'order_id' => $orderId ?? "",
            'type' => $type ?? 'order',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        event(new SendNotificationEvent($title, $description, $image = "", $type, $userId, $lastId));
    }


    public static function insertNotificationAll($requestData)
    {
        $now = Carbon::now();
        $userIds = [];
        if (isset($requestData['user_id']) && !empty($requestData['user_id']))
            $userIds = explode(",", $requestData['user_id']);
        $type = $requestData['type'];
        $isAll = $requestData['is_all'] ?? 1;

        //1:All, 0:User
        if ($requestData['is_all'] == 0) {
            $users = \DB::table('customers')->whereNotNull('device_token')->where('enabled_notify', 1)->whereIn('id', $userIds);
            $users->select(['id', 'device_token'])->orderByDesc('created_at')->chunk(50, function ($users) use ($requestData, $now, $isAll) {
                $title = $requestData['title'] ?? "Tiêu đề";
                $description = $requestData['description'] ?? "Mô tả";
                $content = isset($requestData['content']) ? $requestData['content'] : "Nội dung";
                $image = !empty($requestData['image']) ? $requestData['image'] : null;
                $usersIds = [];
                foreach ($users as $item) {
                    $usersIds[] = $item->id;
                    event(new SendNotificationEvent($title, $description, $image, $type, $item->id));
                }
                $data = [
                    'title' => $title,
                    'description' => $description,
                    'image' => $image,
                    'user_id' => implode(",", $usersIds),
                    'content' => $content,
                    'type' => $requestData['type'] ?? 'system',
                    'is_all' => $isAll,
                    'created_at' => $now

                ];
                \DB::table('notifications')->insert($data);
            });
        } else {
            $users = \DB::table('customers')->whereNotNull('device_token')->where('enabled_notify', 1);
            $title = $requestData['title'] ?? "Tiêu đề";
            $description = $requestData['description'] ?? "Mô tả";
            $content = isset($requestData['content']) ? $requestData['content'] : "Nội dung";
            $image = !empty($requestData['image']) ? $requestData['image'] : null;
            $users->select(['id', 'device_token'])->orderByDesc('created_at')->chunk(50, function ($users) use ($title, $description, $content, $image, $type, $now, $isAll) {
                $usersIds = [];
                foreach ($users as $item) {
                    $usersIds[] = $item->id;
                    event(new SendNotificationEvent($title, $description, $image, $type, $item->id));
                }
                $data = [
                    'title' => $title,
                    'description' => $description,
                    'image' => $image,
                    'user_id' => implode(",", $usersIds),
                    'content' => $content,
                    'type' => $type,
                    'is_all' => $isAll,
                    'created_at' => $now
                ];
                \DB::table('notifications')->insert($data);
            });
        }

    }


}
