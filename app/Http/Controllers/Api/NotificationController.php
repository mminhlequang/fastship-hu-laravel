<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Models\Customer;
use App\Models\Notification;
use Illuminate\Http\Request;
use Validator;


class NotificationController extends BaseController
{

    /**
     * @OA\Get(
     *     path="/api/v1/notification",
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
        $type = $request->type ?? 'system';

        $customer = Customer::getAuthorizationUser($request);

        $customerId = $customer->id ?? 0;
        try {


            $data = Notification::with('user')
                ->where('type', $type)
                ->whereRaw("FIND_IN_SET(?, user_ids)", [$customerId])
                ->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(NotificationResource::collection($data), 'Get all notifications successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
        $customer = Customer::getAuthorizationUser($request);

        $customerId = $customer->id ?? 0;

        if ($validator->fails())
            return $this->sendError(join(PHP_EOL, $validator->errors()->all()));
        try {
            $data = Notification::find($requestData['id']);

            $readAt = ($data->user_ids != null) ? $data->user_ids . ',' . $customerId : $customerId;
            $data->update(['read_at' => $readAt]);

            return $this->sendResponse(new NotificationResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
        $customer = Customer::getAuthorizationUser($request);

        try {
            $customerId = $customer->id ?? 0;
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
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
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
        $customer = Customer::getAuthorizationUser($request);

        \DB::beginTransaction();
        try {
            $customerId = $customer->id ?? 0;

            $notifications = \DB::table('notifications')->where('user_id', $customerId)->get();

            if (!empty($notifications)) {
                foreach ($notifications as $itemN) {
                    $readAt = ($itemN->user_ids != null) ? $itemN->user_ids . ',' . $customerId : $customerId;
                    \DB::table('notifications')->where('id', $itemN->id)->update([
                        'read_at' => $readAt
                    ]);
                }

            }
            \DB::commit();
            return $this->sendResponse(null, __('api.notification_deleted'));
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->sendError(__('errors.ERROR_SERVER') . $e->getMessage());
        }

    }

}
