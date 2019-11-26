<?php
namespace glorifiedking\BusTravel\Http\Controllers;

use Illuminate\Routing\Controller;
use glorifiedking\BusTravel\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('web');
        $this->middleware('auth');
    }

    public function index()
    {
        if(!auth()->user()->can('View BT Stations'))
        {
            return redirect()->route('bustravel.errors.403');
        }
        $bus_stations = Station::all();
        return view('bustravel::backend.stations.index',compact('bus_stations'));
    }

    public function show($id=null)
    {
        if(!auth()->user()->can('Manage BT Stations'))
        {
            return redirect()->route('bustravel.errors.403');
        }
        $station = (!empty($id)) ? Station::findOrFail($id) : null;        
        return view('bustravel::backend.stations.create',compact('station'));
    }

    public function store(Request $request)
    {
        if(!auth()->user()->can('Manage BT Stations'))
        {
            return redirect()->route('bustravel.errors.403');
        }
        //validate 
        $station = (!empty($request->station_id)) ? Station::findOrFail($request->station_id) : new Station;
        $validated_data = $request->validate([
            "station_name" => 'required|alpha_num',
            "code" =>  'required|size:3|unique:stations,code,'.$station->id,
        ]);
        
        $station->name = $request->input('station_name');
        $station->code = strtoupper($request->input('code'));
        $station->address = $request->input('address');
        $station->province = Str::title($request->input('province'));
        $station->city = Str::title($request->input('city'));
        $station->longitude = $request->input('longitude');
        $station->latitude = $request->input('latitude');
        $station->save();

        //send flash session data
        $alerts = [
            'bustravel-flash' => true,
            'bustravel-flash-type' => 'success',
            'bustravel-flash-title' => 'Station Saving',
            'bustravel-flash-message' => 'Station has successfully been saved'
        ];
        return redirect()->route('bustravel.stations')->with($alerts);

    }

    public function destroy($id)
    {
        if(!auth()->user()->can('Manage BT Stations'))
        {
            return redirect()->route('bustravel.errors.403');
        }
        $station = Station::findOrFail($id);

        //confirm if it is used in route before deleting 
        $station->delete();

        //send flash session data
        $alerts = [
            'bustravel-flash' => true,
            'bustravel-flash-type' => 'error',
            'bustravel-flash-title' => 'Station Deleting',
            'bustravel-flash-message' => "Station: $station->name has successfully been deleted"
        ];
        return redirect()->route('bustravel.stations')->with($alerts);
    }

    
}