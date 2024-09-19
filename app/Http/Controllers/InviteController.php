<?php

namespace App\Http\Controllers;

use App\Http\Requests\InviteRequest;
use App\Models\InviteUser;
use App\Models\User;
use Http;
use Str;

class InviteController extends Controller
{
    protected string $url = "http://teams-backend-laravel.test";
    public function createToken(InviteRequest $request)
    {
        $data = [
            "token" => Str::random(20),
            "expires_at" => now()->addMinutes(10),
            "email" => $request->email,
            "invitedBy" => $request->invitedBy,
            "organization_id" => "jkvjkbk",
            "invitedTo" => $request->invitedTo
        ];
        $invite = InviteUser::create($data);
        $user = User::where("email", $invite->email)->first();
        $invited_to_name = User::where("id", $invite->invitedTo)->first();
        $invited_by_name = User::where("id", $invite->invitedBy)->first();

        if (!$user) {
            return response()->json([
                "status" => false,
                "message" => "User not found"
            ], 404);
        }

        if (auth()->user()->id == $user->id) {
            return response()->json([
                "status" => false,
                "message" => "You can't invite yourself"
            ], 400);
        }

        // check who send to who??
        $sendData = [];
        // $checkInvitedByisLegit = 

        if ($request->invitedBy === auth()->user()->id) {
            $sendData = [
                "body" => "<p>You are invited to join organization Arihant. Click the following link to accept invite. <a href=$this->url/invite/verify/$invite->token>Click Here</a></p>",
                "subject" => "You are invited in organization Arihant",
                "email" => $request->email
            ];
        } else {
            $sendData = [
                "body" => "<p>$invited_to_name->name is requesting to join organization Arihant. Click the following link to accept invite. <a href=$this->url/invite/verify/$invite->token>Click Here</a></p>",
                "subject" => "You are invited in organization Arihant",
                "email" => $request->email
            ];
        }

        Http::post("https://connect.pabbly.com/workflow/sendwebhookdata/IjU3NjYwNTZkMDYzMjA0M2M1MjY4NTUzZDUxMzQi_pc", $sendData);

        return response()->json([
            "status" => true,
            "message" => "Invite sent successfully"
        ],200);
    }
}

// thing add to forgot 
// -> who send to who
// -> organization id