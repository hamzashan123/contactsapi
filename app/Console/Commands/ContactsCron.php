<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ContactsCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'contacts:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $lastcount = DB::table('contacts')->count();
       
        $response = Http::get('https://api.opentoclose.com/v1/contacts', [
            'api_token' => 'V0kwamxiSXU3dFA5MHFqOGFxd3pGZz09OkduWEdCcGVDalBuUUVqalRrZXFndExEZEo0TDNQQVlTOmNkYjgzYmZkMzljM2RmYTA2YjhiMTA2YzEyOTc5Yjc0MDZlM2QxODkwZWI0NzI3ZmIwOGExNmYyYzM5YjIwMDc=',
            'offset' => $lastcount,
            'limit' => 10,
        ]);

        if ($response->successful()) {
            $contacts = $response->json();
           
            foreach($contacts as $contact){
                
                DB::table('contacts')->insert([
                    "contact_id" => $contact['id'],
                    "created" => $contact['created']['date'],
                    "first_name" => $contact['first_name'],
                    "last_name" => $contact['last_name'],
                    "middle_name" => $contact['middle_name'],
                    "nick_name" => $contact['nick_name'],
                    "email" => $contact['email'],
                    "office_email" => $contact['office_email'],
                    "address" => $contact['address'],
                    "unit_number" => $contact['unit_number'],
                    "city" => $contact['city'],
                    "state" => $contact['state'],
                    "zip" => $contact['zip'],
                    "country" => $contact['country'],
                    "county" => $contact['county'],
                    "office_name" => $contact['office_name'],
                    "office_address" => $contact['office_address'],
                    "office_unit_number" => $contact['office_unit_number'],
                    "office_city" => $contact['office_city'],
                    "office_state" => $contact['office_state'],
                    "office_country" => $contact['office_country'],
                    "office_county" => $contact['office_county'],
                    "office_zip" => $contact['office_zip'],
                    "phone" => $contact['phone'],
                    "phone_ext" => $contact['phone_ext'],
                    "office_phone" => $contact['office_phone'],
                    "office_phone_ext" => $contact['office_phone_ext'],
                    "fax" => $contact['fax'],
                    "office_fax" => $contact['office_fax'],
                    "cell_phone" => $contact['cell_phone'],
                    "cell_phone_ext" => $contact['cell_phone_ext'],
                    "office_cell_phone" => $contact['office_cell_phone'],
                    "office_cell_phone_ext" => $contact['office_cell_phone_ext'],
                    "personal_website" => $contact['personal_website'],
                    "office_website" => $contact['office_website'],
                    "agent_license_number" => $contact['agent_license_number'],
                    "office_license_number" => $contact['office_license_number'],
                    "shared" => $contact['shared'],
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now()
                ]);
            }
           
            $this->info('Successfully added contacts to database.');

        } else {
            $this->warning('Something went wrong.');
        }

        
    }
}
