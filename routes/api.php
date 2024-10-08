<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\OrganizationGroupMessageController;
use App\Http\Controllers\OrganizationTwoPersonChatController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\UpdateProfileController;
use App\Http\Controllers\MediaController;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Controllers\AuditController;


Route::get("/", function () {
    return response()->json([
        "status" => "up",
        "date" => now()
    ], 200);
});

Route::group(["prefix" => "{local}/auth"], function () {
    Route::group(["middleware" => LanguageMiddleware::class], function () {
        Route::post("register", [AuthController::class, "register"]);
        Route::post("login", [AuthController::class, "login"]);
    });
});


Route::group(["prefix" => "{local}/user"], function () {
    Route::group(["middleware" => ["auth:api", LanguageMiddleware::class]], function () {
        Route::get("profile", [AuthController::class, "profile"]);
        Route::get("logout", [AuthController::class, "logout"]);
        Route::get("/search/{query}", [UserController::class, "search"]);
        Route::put("update/profile", [UpdateProfileController::class, "updateProfile"]);
    });
});

Route::group(["prefix" => "{local}/message"], function () {
    Route::group(["middleware" => ["auth:api", LanguageMiddleware::class]], function () {
        Route::get("/{id}", [MessageController::class, "display"]);
        Route::post("/create", [MessageController::class, "store"]);
        Route::put("/update", [MessageController::class, "update"]);
        Route::delete("/delete", [MessageController::class, "delete"]);
    });
});

Route::group(["prefix" => "{local}/group"], function () {
    Route::group(["middleware" => ["auth:api", LanguageMiddleware::class]], function () {
        Route::get("/{id}", [GroupController::class, "display"]);
        Route::get("/", [GroupController::class, "displayGroup"]);
        Route::get("{group_id}/messages", [GroupController::class, "getGroupMessages"]);
        Route::post("create", [GroupController::class, "create"]);
        Route::post("addUser", [GroupController::class, "addUser"]);
        Route::post("addMessage", [GroupController::class, "addMessage"]);
        Route::put("update/message/{message_id}", [GroupController::class, "updateMessage"]);
        Route::delete("delete/{group_id}", [GroupController::class, "deleteGroup"]);
        Route::delete("delete/{group_id}/message/{message_id}", [GroupController::class, "deleteMessage"]);
    });
});

Route::group(["prefix" => "{local}/meeting"], function () {
    Route::group(["middleware" => ["auth:api", LanguageMiddleware::class]], function () {
        Route::post("schedule", [MeetingController::class, "scheduleMeeting"]);
    });
});


Route::group(["prefix" => "{local}/organization"], function () {
    Route::group(["middleware" => ["auth:api", LanguageMiddleware::class]], function () {
        Route::post('/create', [OrganizationController::class, 'store']);
        Route::post('/{organizationId}/groups', [OrganizationController::class, 'createGroup']);

        Route::post('/groups/{groupId}/messages', [OrganizationGroupMessageController::class, 'store']);
        Route::get('/groups/{groupId}/messages', [OrganizationGroupMessageController::class, 'index']);

        Route::post("/group/addUser", [OrganizationController::class, 'addGroupUser']);

        Route::post('/{organizationId}/two_person_chats', [OrganizationTwoPersonChatController::class, 'store']);
        Route::get('/{organizationId}/two_person_chats/{senderId}/{receiverId}', [OrganizationTwoPersonChatController::class, 'index']);

        Route::post('{organizationId}/add-user', [OrganizationController::class, 'AddUserToOrganization']);

    });
});

Route::group(["prefix" => "{local}/invite"], function () {
    Route::group(["middleware" => ["auth:api", LanguageMiddleware::class]], function () {
        Route::post("create", [InviteController::class, "createToken"]);
    });
});

Route::get("invite/{userId}/verify/{token}", [InviteController::class, "verifyToken"]);

Route::get("/languages", [LanguageController::class, "index"]);
Route::get("/translation/{lang}", [LanguageController::class, "translation"]);

Route::group(["prefix" => "media"], function () {
    Route::group(["middleware" => ["auth:api", LanguageMiddleware::class]], function () {
        Route::post("upload", [MediaController::class, "uploadMedia"]);
        Route::post("group/upload", [MediaController::class, "uploadGroupMedia"]);
        Route::post("organization/upload", [MediaController::class, "OrganizationTwoPersonMedia"]);
        Route::post("organization/group/upload", [MediaController::class, "uploadOrganizationMedia"]);

        // get routes

        // organization
        Route::get('/org/{receiverId}', [MediaController::class, 'getAllMediaForTwoPersons']);
        Route::get('/org/{org_grp_id}/group', [MediaController::class, 'getAllMediaByOrgGroup']);

        Route::get('{receiverId}/user', [MediaController::class, 'getMediaByReceiver']);
        Route::get('{groupId}/group', [MediaController::class, 'getAllMediaByGroup']);

    });
});

Route::group(["middleware" => "auth:api"], function () {
    Route::get("/audit", [AuditController::class, "index"]);
});