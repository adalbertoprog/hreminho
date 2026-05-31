<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

return new class extends Migration {
    /**
     * Migra profile_photo de base64 (longtext) para path relativo (string).
     * Fotos existentes em base64 são gravadas em storage/app/public/employees/photos/.
     */
    public function up(): void
    {
        // 1. Migrar fotos base64 existentes para ficheiros em storage
        $rows = DB::table('employees')
            ->whereNotNull('profile_photo')
            ->select('id', 'profile_photo')
            ->get();

        foreach ($rows as $row) {
            $photo = $row->profile_photo;

            // Só processar se for base64 (começa com data: ou é string longa sem /)
            $isBase64 = str_starts_with($photo, 'data:')
                || (strlen($photo) > 500 && !str_contains($photo, '/'));

            if ($isBase64) {
                try {
                    // Extrair dados do base64
                    if (str_starts_with($photo, 'data:')) {
                        $parts   = explode(',', $photo, 2);
                        $imgData = base64_decode($parts[1] ?? '');
                        // Detectar extensão a partir do mime type
                        preg_match('/data:image\/(\w+);/', $photo, $m);
                        $ext = $m[1] ?? 'jpg';
                    } else {
                        $imgData = base64_decode($photo);
                        $ext     = 'jpg';
                    }

                    if ($imgData) {
                        $filename = 'employees/photos/' . $row->id . '_' . time() . '.' . $ext;
                        Storage::disk('public')->put($filename, $imgData);

                        DB::table('employees')
                            ->where('id', $row->id)
                            ->update(['profile_photo' => $filename]);
                    }
                } catch (\Throwable $e) {
                    // Se falhar, limpar o campo para não deixar base64 inválido
                    DB::table('employees')
                        ->where('id', $row->id)
                        ->update(['profile_photo' => null]);
                }
            }
        }

        // 2. Alterar coluna de longText para string
        Schema::table('employees', function (Blueprint $table) {
            $table->string('profile_photo', 500)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->longText('profile_photo')->nullable()->change();
        });
    }
};
