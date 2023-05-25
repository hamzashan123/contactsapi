<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PropertyCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'properties:cron';

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
        $lastcount = DB::table('properties')->count();

        $response = Http::get('https://api.opentoclose.com/v1/properties', [
            'api_token' => 'V0kwamxiSXU3dFA5MHFqOGFxd3pGZz09OkduWEdCcGVDalBuUUVqalRrZXFndExEZEo0TDNQQVlTOmNkYjgzYmZkMzljM2RmYTA2YjhiMTA2YzEyOTc5Yjc0MDZlM2QxODkwZWI0NzI3ZmIwOGExNmYyYzM5YjIwMDc=',
            'offset' => $lastcount,
            'limit' => 2,
        ]);

        if ($response->successful()) {
            $properties = $response->json();

            

            foreach ($properties as $item) {

                    $propertyExisit = DB::table('properties')->where('property_id',$item['id'])->exists();
                    if($propertyExisit == false){
                            // Loop through the field_values array
                            
                            foreach ($item['field_values'] as $field) {
                                                
                                DB::table('properties')->insert([
                                    "property_id" => $item['id'],
                                    "field_id" => $field['id'],
                                    "value" => $field['value'],
                                    "type" => $field['type'],
                                    "label" => $field['label'],
                                    "key" => $field['key'],
                                ]);
                            }
                    }
                    

                  
            }

                
            
           
            $this->info('Properties Added Successfully!'); 

        } else {
            // Handle the failed request
        }
    }
}
