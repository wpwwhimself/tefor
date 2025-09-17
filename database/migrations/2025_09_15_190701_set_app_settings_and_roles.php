<?php

use App\Models\Shipyard\Role;
use App\Models\Shipyard\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ([
            "app_name" => "Tefor",
            "app_logo_path" => "/media/t3_color.svg",
            "app_accent_color_1_light" => "#9679e6",
            "app_accent_color_1_dark" => "#9679e6",
            "app_accent_color_3_light" => "#ff80c0",
            "app_accent_color_3_dark" => "#ff80c0",
            "metadata_title" => "Tefor",
            "metadata_author" => "Wojciech Przybyła",
        ] as $name => $value) {
            Setting::find($name)->update(["value" => $value]);
        }

        Role::create([
            "name" => "teacher",
            "icon" => "human-male-board",
            "description" => "Ma dostęp do kartotek uczniów i sesji."
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Role::where("name", "teacher")->delete();
    }
};
