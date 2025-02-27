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

        $customer = Customer::getAuthorizationUser($request);
        if (!$customer)
            return $this->sendError("Invalid signature");
        $customerId = $customer->id ?? 0;
        try {
            $data = Notification::with('user');

            $data = $data->where('user_id', $customerId)->orWhere('user_id', 'like', $customerId.',%')->orWhere('user_id', 'like', '%,'.$customerId)->orWhere('user_id', 'like', '%,'.$customerId.',%')->latest()->skip($offset)->take($limit)->get();

            return $this->sendResponse(NotificationResource::collection($data), 'Get all notifications successfully.');
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
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
     *     )
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
            $data = Notification::find($requestData['id']);

            $data->update(['read_at' => 1]);

            return $this->sendResponse(new NotificationResource($data), "Get detail successfully");
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
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
     *             @OA\Property(property="id", type="integer", example="1"),
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
        if (!$customer)
            return $this->sendError("Invalid signature");
        try {
            \DB::table('notifications')->where('id', $request->id)->delete();
            return $this->sendResponse(null, __('api.notification_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
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
        if (!$customer)
            return $this->sendError("Invalid signature");
        try {
            \DB::table('notifications')->where('user_id', $customer->id)->update([
                'read_at' => 1
            ]);
            return $this->sendResponse(null, __('api.notification_deleted'));
        } catch (\Exception $e) {
            return $this->sendError(__('api.error_server') . $e->getMessage());
        }

    }

}
