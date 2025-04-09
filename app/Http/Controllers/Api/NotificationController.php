<?php

namespace App\Http\Controllers\Api;

use App\Events\SendNotificationEvent;
use App\Events\SendNotificationFcmEvent;
use App\Http\Resources\NotificationResource;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Validator;


class NotificationController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/notification/get_notifications",
     *     tags={"Notification"},
     *     summary="Get all notification",
     *     @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type(system, news, promotion, order,transaction)",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="offset",
     *         in="query",
     *         description="Offset",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Get all notifications"),
     *     security={{"bearerAuth":{}}}
     * )
     */
    public function getList(Request $request)
    {

        $limit = $request->limit ?? 10;
        $offset = isset($request->offset) ? $request->offset * $limit : 0;
        $type = $request->type ?? '';
        try {

            $customerId = auth('api')->id() ?? 0;
            $data = Notification::with('user')
                ->when($type != '', function ($query) use ($type){
                    $query->where('type', $type);
                })
                ->whereRaw("FIND_IN_SET(?, user_id)", [$customerId])
                ->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(NotificationResource::collection($data), __('GET_NOTIFICATIONS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/notification/detail",
     *     tags={"Notification"},
     *     summary="Get detail notification by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="query",
     *         description="ID of the notification",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Notification details"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Notification not found"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function detail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'id' => 'required|exists:notifications,id',
        ]);

        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $customerId = auth('api')->id() ?? 0;

            $data = Notification::find($requestData['id']);

            $readAt = ($data->user_id != null) ? $data->user_id . ',' . $customerId : $customerId;
            $data->update(['read_at' => $readAt]);

            return $this->sendResponse(new NotificationResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/notification/delete",
     *     tags={"Notification"},
     *     summary="Delete notification",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Delete notification",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example="1", description="ID notification"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Delete successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function delete(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'id' => 'required|exists:notifications,id',
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $customerId = auth('api')->id() ?? 0;
            $id = $request->id;
            $data = Notification::find($id);

            //Nếu tất cả thì update read_at
            if ($data->is_all == 1) {
                $ids = $data->user_ids;
                // Chuyển chuỗi thành mảng
                $idArray = explode(',', $ids);
                if (count($idArray) == 1) {
                    $data->delete();
                } else {
                    // Loại bỏ phần tử có giá trị bằng $userId
                    $idArray = array_filter($idArray, function ($id) use ($customerId) {
                        return $id != $customerId;
                    });
                    // Chuyển mảng trở lại thành chuỗi, nếu mảng không rỗng
                    $idsUpdated = implode(',', array_values($idArray));

                    $data->update([
                        'user_ids' => $idsUpdated
                    ]);
                }
            } else {
                $data->delete();
            }

            return $this->sendResponse(null, __('api.notification_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/notification/read_all",
     *     tags={"Notification"},
     *     summary="Read all notification",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Read all notification",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Read all successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function readAll(Request $request)
    {

        \DB::beginTransaction();
        try {
            $customerId = auth('api')->id() ?? 0;

            $notifications = \DB::table('notifications')->where('user_id', $customerId)->get();

            if (!empty($notifications)) {
                foreach ($notifications as $itemN) {
                    $readAt = ($itemN->read_at != null) ? $itemN->read_at . ',' . $customerId : $customerId;
                    \DB::table('notifications')->where('id', $itemN->id)->update([
                        'read_at' => $readAt
                    ]);
                }

            }
            \DB::commit();
            return $this->sendResponse(null, __('NOTIFICATION_READ_ALL'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }


    /**
     * @OA\Post(
     *     path="/api/v1/notification/sent_customize_notification",
     *     tags={"Notification"},
     *     summary="Send custom notification",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Send notification",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="body", type="string"),
     *             @OA\Property(property="payload", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Send all successfully"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     ),
     *     security={{"bearerAuth":{}}},
     * )
     */
    public function sendNotification(Request $request)
    {
        $requestData = $request->all();
        $validator = \Validator::make($requestData, [
            'user_id' => 'required|exists:customers,id',
            'title' => 'required',
            'body' => 'required'
        ]);
        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));

        try {
            $title = $request->title ?? '';
            $body = $request->body ?? '';
            $userId = $request->user_id ?? '';
            $payload = $request->payload ?? [];

            event(new SendNotificationFcmEvent($title, $body, $userId, $payload));
            return $this->sendResponse(null, __('SEND_NOTIFICATION_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(__('ERROR_SERVER') . $e->getMessage());
        }

    }

}
