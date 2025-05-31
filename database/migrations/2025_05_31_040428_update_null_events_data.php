<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use App\Models\Event;

return new class extends Migration
{
    public function up()
    {
        // الحصول على أول مستخدم أو إنشاء مستخدم افتراضي
        $defaultUser = User::firstOrCreate(
            ['email' => 'system@example.com'],
            [
                'name' => 'System',
                'password' => bcrypt('password')
            ]
        );

        // تحديث الأحداث التي تحتوي على قيم NULL
        Event::whereNull('created_by_name')
            ->update([
                'created_by_name' => $defaultUser->name,
                'created_by_email' => $defaultUser->email
            ]);
    }

    public function down()
    {
        // لا يمكن التراجع عن هذا التحديث
    }
};