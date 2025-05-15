<?php

namespace App\Console\Commands;

use App\Helpers\CodeHelper;
use App\Helpers\Constants;
use App\Models\AddressFixLog;
use Illuminate\Console\Command;
use Modules\Media\Helpers\FileHelper;
use Modules\Media\Models\MediaFile;
use Modules\Space\Models\Space;

class FixAddresses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:addresses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix address of spaces in system';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lastSpaceId = 0;
        $findLastFixLog = AddressFixLog::query()->orderBy('space_id', 'DESC')->first();
        if ($findLastFixLog != null) {
            $lastSpaceId = $findLastFixLog->space_id;
        }
        $spaces = Space::where('id', '>', $lastSpaceId)->limit(100)->get();
        foreach ($spaces as $space) {

            $address = "$space->address, $space->city, $space->state $space->zip, $space->country";

            $pageUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($address) . '&key=AIzaSyCRu_qlT0HNjPcs45NXXiOSMd3btAUduSc';
            
            $addressResponse = CodeHelper::pageResponse($pageUrl);

            if (is_array($addressResponse)) {
                if (array_key_exists('results', $addressResponse)) {
                    $results = $addressResponse['results'];
                    //print_r($results);die;
                    if (is_array($results) && array_key_exists(0, $results)) {
                        $addressComponent = $results[0];
                        if (is_array($addressComponent)) {
                            $geo = $addressComponent['geometry']['location'];
                            if (is_array($geo)) {
                                if (array_key_exists('lat', $geo) && array_key_exists('lng', $geo)) {
                                    $space->map_lat = (string) $geo['lat'];
                                    $space->map_lng = (string) $geo['lng'];
                                    $space->update();
                                }
                            }
                        }
                    }
                }
            }

            $data = new AddressFixLog([
                'space_id' => $space->id,
                'address' => $address,
                'api_response' => json_encode($addressResponse)
            ]);

            $data->save();

        }

    }

}
