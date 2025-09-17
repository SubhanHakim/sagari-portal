<?php
// app/Services/DualWriteUser.php
namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

class DualWriteUser
{
    /** CREATE: tulis ke DB1 & DB2; rollback bila salah satu gagal. */
    public function create(array $data): User
    {
        $now = now();
        $payload = [
            'name'        => $data['name'],
            'email'       => $data['email'],
            'position_id' => $data['position_id'] ?? null,
            'password'    => Hash::make(Str::random(32)),
            'global_id'   => (string) Str::uuid(),
            'created_at'  => $now,
            'updated_at'  => $now,
        ];

        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql2')->beginTransaction();

        try {
            $created1 = (new User)->setConnection('mysql')->create($payload);
            (new User)->setConnection('mysql2')->create($payload);

            DB::connection('mysql')->commit();
            DB::connection('mysql2')->commit();

            return $created1; // kembalikan instance dari DB1
        } catch (Throwable $e) {
            DB::connection('mysql')->rollBack();
            DB::connection('mysql2')->rollBack();
            throw $e;
        }
    }

    /** UPDATE by global_id: all-or-nothing */
    public function update(string $globalId, array $data): void
    {
        $data['updated_at'] = now();

        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql2')->beginTransaction();

        try {
            User::on('mysql')->where('global_id', $globalId)->update($data);
            User::on('mysql2')->where('global_id', $globalId)->update($data);

            DB::connection('mysql')->commit();
            DB::connection('mysql2')->commit();
        } catch (Throwable $e) {
            DB::connection('mysql')->rollBack();
            DB::connection('mysql2')->rollBack();
            throw $e;
        }
    }

    /** DELETE by global_id: all-or-nothing */
    public function delete(string $globalId): void
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql2')->beginTransaction();

        try {
            User::on('mysql')->where('global_id', $globalId)->delete();
            User::on('mysql2')->where('global_id', $globalId)->delete();

            DB::connection('mysql')->commit();
            DB::connection('mysql2')->commit();
        } catch (Throwable $e) {
            DB::connection('mysql')->rollBack();
            DB::connection('mysql2')->rollBack();
            throw $e;
        }
    }
}