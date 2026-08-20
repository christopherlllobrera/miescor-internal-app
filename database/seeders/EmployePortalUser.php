<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployePortalUser extends Seeder
{
    public function run(): void
    {
        // Ensure roles exist
        $roles = ['TDE Team', 'Department PIC'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $users = [
            ['10028966', 'Martina Coleen Z. Paredes', 'mczparedes@miescor.ph', 'q1nEI52ks0Zk', 'TDE Team'],
            ['10019828', 'Ziegred Cajusay', 'zcajusay@miescor.ph', '4B600w07XUc', 'Department PIC'],
            ['10016770', 'Razelle R. Vale', 'rrvale@miescor.ph', '6k87wHE084aZ', 'Department PIC'],
            ['10027226', 'Carlo Joaquin DR. Nana', 'cjdnana@miescor.ph', 'rVl98sZ80XUZ', 'Department PIC'],
            ['10030567', 'Bryan Jay E. Pajares', 'bjepajares@miescor.ph', 'w2m393zNDjFG', 'Department PIC'],
            ['10020741', 'Mary Ann G. Pasia', 'magpasia@miescor.ph', 'hUD3ZR1537Z5', 'Department PIC'],
            ['10019934', 'Bea Patricia R. Sikat', 'bprsikat@miescor.ph', 'T9D0DQqI8oBx', 'Department PIC'],
            ['10022911', 'Lorenzo D. Armedilla', 'ldarmedilla@miescor.ph', '25SHhMVtzgJ9', 'Department PIC'],
            ['10002238', 'Aris O. Untalan', 'aountalan@miescor.ph', 'sHJb250DPM9x', 'Department PIC'],
            ['10013616', 'Arvin John A. Doza', 'ajadoza@miescor.ph', 'E013w6CZhsU0', 'Department PIC'],
            ['10031107', 'Jeremiah Robin N. Llamas', 'jrnllamas@miescor.ph', '9eFe55PYD7in', 'Department PIC'],
            ['10002180', 'Ma. Jocelyn J. Salinas', 'jjsalinas@miescor.ph', 'Q82VOLQit7g5', 'Department PIC'],
            ['10031050', 'Lee Ann E. Perdigon', 'laeperdigon@miescor.ph', 'mV1962Q2IGjg', 'Department PIC'],
            ['10002254', 'Verma D. Nardo', 'vdnardo@miescor.ph', '6T48mTsH8Xqs', 'Department PIC'],
            ['10025284', 'Giezelle Mae C. Gonzales', 'gmcgonzales@miescor.ph', 'ww4D4e7dXK4u', 'Department PIC'],
        ];

        foreach ($users as $data) {

            $user = User::updateOrCreate(
                ['comp_email' => $data[2]], // unique key
                [
                    'empNo' => $data[0],
                    'username' => $data[1],
                    'password' => Hash::make($data[3]),
                    'password_expires_at' => Carbon::now()->addMonths(3),
                    'access_level' => $data[4],
                    'is_locked' => 0,
                    'first_login' => 1,
                ]
            );

            // ⭐ Spatie role assignment
            $user->syncRoles([$data[4]]);
        }

        $this->command->info('Employee users seeded with roles successfully!');
    }
}
